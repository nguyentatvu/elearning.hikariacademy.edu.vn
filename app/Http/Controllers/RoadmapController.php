<?php

namespace App\Http\Controllers;

use App\LmsSeries;
use App\Roadmap;
use App\Services\LmsSeriesService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class RoadmapController extends Controller
{
    private $lmsSeriesService;

    /**
     * Create a new controller instance.
     */
    public function __construct(
        LmsSeriesService $lmsSeriesService
    )
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (!checkRole(getUserGrade(2))) {
                prepareBlockUserMessage();
                return redirect('/');
            }
            return $next($request);
        });

        $this->lmsSeriesService = $lmsSeriesService;
    }

    /**
     * List of all saved roadmaps of series.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function list() {
        $allSeries = $this->lmsSeriesService->getAllWithRoadmapsAndLessons();
        $courseSeriesList = $allSeries->filter(function ($series) {
            return $series->type_series == LmsSeries::COURSE;
        });
        $examSeriesList = $allSeries->filter(function ($series) {
            return $series->type_series == LmsSeries::EXAM;
        });
        $allRoadmaps = [];
        $mappedLessons = $allSeries->mapWithKeys(function ($series) use (&$allRoadmaps) {
            $seriesRoadmaps = $series->roadmaps->map(function ($roadmap) {
                return [
                    'id' => $roadmap->id,
                    'duration_months' => $roadmap->duration_months,
                    'course_id' => $roadmap->lmsseries_id,
                    'contents' => $roadmap->contents,
                    'description' => $roadmap->description
                ];
            })->toArray();
            if (count($seriesRoadmaps) > 0) {
                $allRoadmaps = array_merge($allRoadmaps, $seriesRoadmaps);
            }

            return [
                $series->id => $series->lmscontents->map(function ($lesson) {
                    return [
                        'id' => $lesson->id,
                        'name' => $lesson->bai,
                        'num_type' => $lesson->type
                    ];
                })
            ];
        });

        $data['active_class'] = 'roadmap';
        $data['page_title'] = 'Danh sách các gói điểm';
        $data['exam_series_list'] = $this->convertSeriesList($examSeriesList);
        $data['course_series_list'] = $this->convertSeriesList($courseSeriesList);
        $data['lessons'] = $mappedLessons;
        $data['all_roadmaps'] = $allRoadmaps;
        $data['lesson_type_map'] = config('constant.series.type_map');

        return view('admin.roadmap.list', $data);
    }

    /**
     * Convert series list.
     *
     * @param Collection $seriesList
     * @return array
     */
    public function convertSeriesList($seriesList) {
        return $seriesList->map(function ($series) {
            return [
                'id' => $series->id,
                'title' => $series->title,
                'roadmaps' => $series->roadmaps->map(function ($roadmap) {
                    return [
                        'id' => $roadmap->id,
                        'duration_months' => $roadmap->duration_months
                    ];
                })
            ];
        });
    }

    /**
     * Save roadmap.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function saveRoadmap(Request $request) {
        Roadmap::updateOrCreate(
            [
                'lmsseries_id' => $request->course_id,
                'duration_months' => $request->duration_months
            ],
            [
                'contents' => json_decode($request->contents),
                'description' => $request->description
            ]
        );

        return response('success', Response::HTTP_OK);
    }

    /**
     * Delete roadmap.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function deleteRoadmap(Request $request) {
        Roadmap::find($request->id)->delete();

        return response('success', Response::HTTP_OK);
    }
}
