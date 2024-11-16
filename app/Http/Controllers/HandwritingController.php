<?php

namespace App\Http\Controllers;

use App\HiraganaWritingPractice;
use App\JapaneseWritingPractice;
use App\KanjiWritingPractice;
use App\Services\HandwritingService;
use App\Services\HiraganaWritingPracticeService;
use App\Services\KanjiWritingPracticeService;
use App\Services\LmsContentService;
//use App\Services\LmsContentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Yajra\DataTables\Facades\DataTables;

class HandwritingController extends Controller
{
    private $handwritingService;
    private $hiraganaWritingPracticeService;
    private $kanjiWritingPracticeService;
    private $lmsContentService;

    public function __construct(
        HandwritingService $handwritingService,
        HiraganaWritingPracticeService $hiraganaWritingPracticeService,
        KanjiWritingPracticeService $kanjiWritingPracticeService,
        LmsContentService $lmsContentService
    ) {
        $this->middleware('auth');
        $this->handwritingService = $handwritingService;
        $this->hiraganaWritingPracticeService = $hiraganaWritingPracticeService;
        $this->kanjiWritingPracticeService = $kanjiWritingPracticeService;
        $this->lmsContentService = $lmsContentService;
    }

    /**
     * This method returns the list of handwriting
     *
     * @return view
     */
    public function index()
    {
        if (!checkRole(getUserGrade(2))) {
            prepareBlockUserMessage();
            return back();
        }

        $data['active_class'] = 'handwriting';
        $data['title']        = 'Luyện viết';
        $view_name            = 'admin.lms.handwriting.list';

        return view($view_name, $data);
    }

    /**
     * This method returns the datatables data to view
     *
     * @return mixed
     */
    public function getDatatable()
    {
        if (!checkRole(getUserGrade(2))) {
            prepareBlockUserMessage();
            return back();
        }

        $records = $this->handwritingService->getAllWithOrderBy('updated_at', 'desc');

        $table = DataTables::of($records)
            ->addColumn('action', function ($records) {
                return '<div class="dropdown more">
                         <a id="dLabel" type="button" class="more-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                             <i class="mdi mdi-dots-vertical"></i>
                         </a>
                         <ul class="dropdown-menu" aria-labelledby="dLabel">
                         <li><a href="' . '/lms/handwriting/' . $records->id . '/view"><i class="fa fa-eye"></i>Xem chi tiết</a></li>
                             <li><a href="' . '/lms/handwriting/' . $records->id . '/edit"><i class="fa fa-edit"></i>Chỉnh sửa</a></li>
                         <li><a href="javascript:void(0);" onclick="deleteRecord(\'' . $records->id . '\');"><i class="fa fa-trash"></i>' . getPhrase("delete") . '</a></li>
                         </ul>
                     </div>';
            })
            ->editColumn('type', function ($record) {
                if ($record->type == JapaneseWritingPractice::HIRAGANA) {
                    return 'Luyện viết từng chữ (Hiragana, Katakana, Kanji)';
                } elseif ($record->type == JapaneseWritingPractice::KANJI) {
                    return 'Viết hán tự cho phần được gạch chân';
                }
                return $record->type;
            })
            ->removeColumn('id')
            ->rawColumns(['action', 'title']);

        return $table->make();
    }

    /**
     * show details of a resource
     *
     * @param int $id
     * @return view
     */
    public function show(int $id)
    {
        if (!checkRole(getUserGrade(2))) {
            prepareBlockUserMessage();
            return back();
        }

        $handwriting = $this->handwritingService->findById($id);
        $data['active_class'] = 'handwriting';
        $data['title']        = $handwriting->title;
        $data['handwriting']    = $handwriting;
        $view_name            = 'admin.lms.handwriting.show';

        return view($view_name, $data);
    }

    /**
     * show datatable of a resource
     *
     * @param int $id
     * @return mixed
     */
    public function getHandwriting(int $id)
    {
        if (!checkRole(getUserGrade(2))) {
            prepareBlockUserMessage();
            return back();
        }

        $handwriting = $this->handwritingService->findById($id);
        $handwritingId = $handwriting->id;
        $records = null;

        if ($handwriting->type == JapaneseWritingPractice::HIRAGANA) {
            $records = $this->hiraganaWritingPracticeService->getByConditionsWithOrderBy(
                ['practice_id' => $id],
                ['id', 'character', 'number'],
                'id'
            );
        } else if ($handwriting->type == JapaneseWritingPractice::KANJI) {
            $records = $this->kanjiWritingPracticeService->getByConditionsWithOrderBy(
                ['practice_id' => $id],
                ['id', 'full_word', 'number', 'underlined_word', 'kanji'],
                'id'
            );
        }

        if ($records) {
            $table = DataTables::of($records)
                ->addColumn('action', function ($record) use ($handwritingId) {
                    return '<div class="dropdown more">
                        <a id="dLabel" type="button" class="more-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="mdi mdi-dots-vertical"></i>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="dLabel">
                       <li><a href="/lms/handwriting/'. $handwritingId . '/detail/' . $record->id . '/edit"><i class="fa fa-pencil"></i>' . getPhrase("edit") . '</a></li>
                       <li><a href="javascript:void(0);" onclick="deleteRecord(\'' . $record->id . '\');"><i class="fa fa-trash"></i>' . getPhrase("delete") . '</a></li>
                        </ul>
                    </div>';
                })
                ->removeColumn('id')
                ->rawColumns(['action']);

            return $table->make();
        }

        return null;
    }

    /**
     * This method returns the create view
     * @return [type] [description]
     */
    public function create()
    {
        if (!checkRole(getUserGrade(2))) {
            prepareBlockUserMessage();
            return back();
        }

        $data['record']       = false;
        $data['active_class'] = 'handwriting';
        $data['title']        = 'Thêm bài luyện viết';
        $data['handwriting']    = '';
        $view_name            = 'admin.lms.handwriting.add-edit';

        return view($view_name, $data);
    }

    /**
     * This method saves the data
     *
     * @param  Request $request
     * @return redirect
     */
    public function store(Request $request)
    {
        if (!checkRole(getUserGrade(2))) {
            prepareBlockUserMessage();
            return back();
        }

        $data = $request->only('title', 'type', 'file_import');

        DB::beginTransaction();
        try {
            $handwriting = $this->handwritingService->create([
                'title' => $data['title'],
                'slug' => createSlug($data['title']),
                'type' => $data['type'],
            ]);

            if (isset($data['file_import'])) {
                $file = $data['file_import'];
                $result = $this->handleDataFromExcel($handwriting->id, $handwriting->type, $file);

                if (!$result && $handwriting->type == JapaneseWritingPractice::HIRAGANA) {
                    return redirect()
                        ->back()
                        ->withErrors(['error' => 'File không hợp lệ cho bài luyện viết từng chữ Hiragana/Katakana/Kanji. File chỉ nên có 2 cột.'])
                        ->withInput($request->all());
                } else if (!$result && $handwriting->type == JapaneseWritingPractice::KANJI) {
                    return redirect()
                        ->back()
                        ->withErrors(['error' => 'File không hợp lệ cho bài luyện viết Hán tự cho phần gạch chân. File chỉ nên có 4 cột.'])
                        ->withInput($request->all());
                }
            }

            DB::commit();
            flash('Thêm bài luyện viết từ file Excel thành công', '', 'success');

            return redirect()->route('lms.handwriting.index');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * This method loads the create detail view
     *
     * @param int $id
     * @return view
     */
    public function createDetail(int $id)
    {
        if (!checkRole(getUserGrade(2))) {
            prepareBlockUserMessage();
            return back();
        }

        $handwriting = $this->handwritingService->findById($id);
        $data['record']       = false;
        $data['active_class'] = 'handwriting';
        $data['title']        = 'Thêm ' . $handwriting->title;
        $data['handwriting']    = $handwriting;
        $view_name = 'admin.lms.handwriting.detail.add-edit';

        return view($view_name, $data);
    }

    /**
     * This method saves the data
     *
     * @param  Request $request
     * @return redirect
     */
    public function storeDetail(Request $request)
    {
        $data = $request->only(['type', 'handwriting_id', 'character', 'full_word', 'underlined_word', 'kanji']);

        if ($data['type'] == JapaneseWritingPractice::HIRAGANA) {
            $this->hiraganaWritingPracticeService->createByConditionsWithIncrementedNumber(
                ['practice_id' => $data['handwriting_id']],
                [
                    'practice_id' => $data['handwriting_id'],
                    'character'   => $data['character']
                ],
                'number'
            );
        } else if ($data['type'] == JapaneseWritingPractice::KANJI) {
            $this->kanjiWritingPracticeService->createByConditionsWithIncrementedNumber(
                ['practice_id' => $data['handwriting_id']],
                [
                    'practice_id' => $data['handwriting_id'],
                    'full_word'   => $data['full_word'],
                    'underlined_word' => $data['underlined_word'],
                    'kanji'       => $data['kanji']
                ],
                'number');
        }

        flash('success', 'Thêm Flashcard thành công', 'success');

        return redirect(route('lms.handwriting.view', $data['handwriting_id']));
    }

    /**
     * This method loads the edit view
     *
     * @param int $id
     * @return view
     */
    public function edit(int $id)
    {
        if (!checkRole(getUserGrade(2))) {
            prepareBlockUserMessage();
            return back();
        }

        $record    = $this->handwritingService->findById($id);
        $data['record']       = $record;
        $data['active_class'] = 'handwriting';
        $data['title']        = 'Chỉnh sửa bài luyện viết';
        $view_name = 'admin.lms.handwriting.add-edit';

        return view($view_name, $data);
    }

    /**
     * This method updates the data
     *
     * @param  Request $request
     * @param int $id
     * @return redirect
     */
    public function update(Request $request, int $id)
    {
        if (!checkRole(getUserGrade(2))) {
            prepareBlockUserMessage();
            return back();
        }

        $data = $request->only('title', 'type','file_import');

        DB::beginTransaction();
        try {
            $handwriting = $this->handwritingService->findById($id);

            // Delete all hiragana or kanji writing practice relating to this handwriting
            if ($handwriting->type == JapaneseWritingPractice::HIRAGANA) {
                $handwriting->hiraganaWritingPractices()->delete();
            } else if ($handwriting->type == JapaneseWritingPractice::KANJI) {
                $handwriting->kanjiWritingPractices()->delete();
            }

            $upatedResult = $this->handwritingService->update($id, [
                'title' => $data['title'],
                'type'  => $data['type'],
            ]);

            if ($upatedResult && isset($data['file_import'])) {
                $file = $data['file_import'];

                $result = $this->handleDataFromExcel($id, $data['type'], $file);

                if (!$result) {
                    if ($data['type'] == JapaneseWritingPractice::HIRAGANA) {
                        DB::rollBack();
                        return redirect()
                            ->back()
                            ->withErrors(['error' => 'File không hợp lệ cho bài luyện viết từng chữ Hiragana/Katakana/Kanji. File chỉ nên có 2 cột.'])
                            ->withInput($request->all());
                    } else if ($data['type'] == JapaneseWritingPractice::KANJI) {
                        DB::rollBack();
                        return redirect()
                            ->back()
                            ->withErrors(['error' => 'File không hợp lệ cho bài luyện viết Hán tự cho phần gạch chân. File chỉ nên có 4 cột.'])
                            ->withInput($request->all());
                    }
                }
            }

            DB::commit();
            flash('success', 'Cập nhật bài luyện viết từ file Excel thành công', 'success');

            return redirect()->route('lms.handwriting.index');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * This method loads the edit view
     *
     * @param int $id
     * @param int $detailId
     * @return view
     */
    public function editDetail(int $id, int $detailId)
    {
        if (!checkRole(getUserGrade(2))) {
            prepareBlockUserMessage();
            return back();
        }

        $handwriting = $this->handwritingService->findById($id);
        $record = null;

        if ($handwriting->type == JapaneseWritingPractice::HIRAGANA) {
            $record = $this->hiraganaWritingPracticeService->getByConditions([
                'id' => $detailId,
                'practice_id' => $id
            ]);
        }
        else if ($handwriting->type == JapaneseWritingPractice::KANJI) {
            $record = $this->kanjiWritingPracticeService->getByConditions([
                'id' => $detailId,
                'practice_id' => $id
            ]);
        }

        $data['record']       = $record;
        $data['handwriting']  = $handwriting;
        $data['active_class'] = 'handwriting';
        $data['title']        = 'Cập nhật ' . $handwriting->title;
        $view_name = 'admin.lms.handwriting.detail.add-edit';

        return view($view_name, $data);
    }

    /**
     * This method updates the data
     *
     * @param  Request $request
     * @param int $id
     * @param int $detailId
     * @return redirect
     */
    public function updateDetail(Request $request, int $id, int $detailId)
    {
        if (!checkRole(getUserGrade(2))) {
            prepareBlockUserMessage();
            return back();
        }

        $data = $request->only('character', 'full_word', 'underlined_word', 'kanji');
        $handwriting = $this->handwritingService->findById($id);

        if ($handwriting->type == JapaneseWritingPractice::HIRAGANA) {
            $this->hiraganaWritingPracticeService->update($detailId, [
                'character' => $data['character'],
            ]);
        }
        else if ($handwriting->type == JapaneseWritingPractice::KANJI) {
            $this->kanjiWritingPracticeService->update($detailId, [
                'full_word' => $data['full_word'],
                'underlined_word' => $data['underlined_word'],
                'kanji' => $data['kanji'],
            ]);
        }

        flash('success', 'Cập nhật Flash thành công', 'success');

        return redirect(route('lms.handwriting.view', $id));
    }

    /**
     * Delete the record
     *
     * @param int $id
     * @return Boolean
     */
    public function delete(int $id)
    {

        if (!checkRole(getUserGrade(2))) {
            prepareBlockUserMessage();
            return back();
        }

        DB::beginTransaction();
        try {
            $this->handwritingService->delete($id);
            $this->lmsContentService->deleteByKeyValueConditions([
                'japanese_writing_practice_id' => $id
            ]);
            $response['status']  = 1;
            $response['message'] = 'Xóa thành công';

            DB::commit();
        } catch (\Illuminate\Database\QueryException $e) {
            $response['status'] = 0;

            if (getSetting('show_foreign_key_constraint', 'module')) {
                $response['message'] = $e->errorInfo;
            } else {
                $response['message'] = getPhrase('this_record_is_in_use_in_other_modules');
            }

            DB::rollback();
        }

        return json_encode($response);
    }

    /**
     * Delete the record detail
     *
     * @param int $id
     * @param int $detailId
     * @return Boolean
     */
    public function deleteDetail(int $id, int $detailId)
    {

        if (!checkRole(getUserGrade(2))) {
            prepareBlockUserMessage();
            return back();
        }

        $handwriting = $this->handwritingService->findById($id);

        try {
			if ($handwriting->type == JapaneseWritingPractice::HIRAGANA) {
                $this->hiraganaWritingPracticeService->delete($detailId);
            }
            else if ($handwriting->type == JapaneseWritingPractice::KANJI) {
                $this->kanjiWritingPracticeService->delete($detailId);
            }

            $response['status']  = 1;
            $response['message'] = 'Xóa thành công';

        } catch (\Illuminate\Database\QueryException $e) {
            $response['status'] = 0;
            if (getSetting('show_foreign_key_constraint', 'module')) {
                $response['message'] = $e->errorInfo;
            } else {
                $response['message'] = getPhrase('this_record_is_in_use_in_other_modules');
            }
        }
        return json_encode($response);
    }

    /**
     * Handle data from excel
     *
     * @param int $handwritingId
     * @param int $handwritingType
     * @param UploadedFile $file
     * @return bool
     */
    protected function handleDataFromExcel(int $handwritingId, int $handwritingType, UploadedFile $file)
    {
        $path = $file->getRealPath();
        config(['excel.import.startRow' => 2]);
        $data = Excel::selectSheetsByIndex(0)->load($path, function ($reader) {
            $reader->noHeading();
        })->get()->toArray();

        if ($handwritingType == JapaneseWritingPractice::HIRAGANA) {
            foreach ($data as $rowData) {
                if (count($rowData) != 2) {
                    return false;
                }

                $this->hiraganaWritingPracticeService->create([
                    'practice_id' => $handwritingId,
                    'number'      => $rowData[0],
                    'character'   => $rowData[1],
                ]);

            }
        } else if ($handwritingType == JapaneseWritingPractice::KANJI) {
            foreach ($data as $rowData) {
                if (count($rowData) != 4) {
                    return false;
                }

                $this->kanjiWritingPracticeService->create([
                    'practice_id' => $handwritingId,
                    'number'      => $rowData[0],
                    'full_word'   => $rowData[1],
                    'underlined_word' => $rowData[2],
                    'kanji'       => $rowData[3],
                ]);
            }
        }

        return true;
    }
}
