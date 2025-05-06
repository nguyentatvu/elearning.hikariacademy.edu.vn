<?php
namespace App\Http\Controllers;
use App\Http\Controllers\CURLFile;
use App\LmsContent;
use Auth;
use DB;
use Excel;
use Exception;
use File;
use Illuminate\Http\Request;
use Image;
use ImageSettings;
use Yajra\DataTables\DataTables;
use \App;
use App\Services\HandwritingService;
use App\Services\ImageService;
use App\Services\LmsContentService;
use App\Services\LmsFlashcardService;
use App\Services\PronunciationService;
use App\TrafficRuleTestQuestion;

class LmsContentController extends Controller
{
    private $handwritingService;
    private $lmsContentService;
    private $pronunciationService;
    private $lmsFlashcardService;
    private $imageService;

    public function __construct(
        HandwritingService $handwritingService,
        LmsContentService $lmsContentService,
        PronunciationService $pronunciationService,
        LmsFlashcardService $lmsFlashcardService,
        ImageService $imageService
    ) {
        $this->middleware('auth');
        $this->handwritingService = $handwritingService;
        $this->lmsContentService = $lmsContentService;
        $this->pronunciationService = $pronunciationService;
        $this->lmsFlashcardService = $lmsFlashcardService;
        $this->imageService = $imageService;
    }
    protected $examSettings;
    public function setSettings()
    {
        $this->examSettings = getSettings('lms');
    }
    public function getSettings()
    {
        return $this->examSettings;
    }
    /**
     * Course listing method
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function index($series)
    {
        if (!checkRole(getUserGrade(2))) {
            prepareBlockUserMessage();
            return back();
        }
        $data['series_slug']         = $series;

         $lmsseries =  DB::table('lmsseries')
                                ->where('slug',$series)
                                ->first();
                               // dd($lmsseries);

        $data['URL_LMS_CONTENT_ADD'] = PREFIX . "lms/$series/content/add";
        $data['URL_IMPORT_MUCLUC']   = PREFIX . "lms/$series/content/import-mucluc";
        $data['URL_IMPORT_EXAMS']    = PREFIX . "lms/$series/content/import-excel";
        $data['datatbl_url']         = PREFIX . "lms/$series/content/getList/";
        $data['active_class']        = 'lms';
        $data['title']               =  $lmsseries->title;
        $data['layout']              = getLayout();
        // return view('lms.lmscontents.list', $data);

        $list = DB::table('lmscontents')
            ->select(['lmscontents.stt', 'lmscontents.bai', 'lmscontents.title',
                'lmscontents.id', DB::raw("CONCAT(lmscontents.stt,'-',lmscontents.bai) AS baihoc")])
            ->join('lmsseries', 'lmsseries.id', '=', 'lmscontents.lmsseries_id')
            ->where([
                ['lmsseries.slug', $series],
                ['lmscontents.delete_status', 0],
            ])
            ->orderBy('stt')
            ->get();
        $baihoc         = array_pluck($list, 'baihoc', 'id');
        // dd($flashcard);
        $data['baihoc'] = array(''=>'-- Chọn bài học --') + $baihoc;
            // dd($data['baihoc']);
        $view_name = 'admin.lms.lmscontents.list';
        return view($view_name, $data);
    }
    /**
     * This method returns the datatables data to view
     * @return [type] [description]
     */
    public function getDatatable($slug)
    {
        if (!checkRole(getUserGrade(2))) {
            prepareBlockUserMessage();
            return back();
        }
        $records = DB::table('lmscontents')
            ->select(['lmscontents.stt', 'lmscontents.bai', 'lmscontents.title', 'lmscontents.image',
                'lmscontents.id', 'lmscontents.type', 'lmscontents.import', 'lmscontents.file_path', 'lmscontents.el_try', 'lmscontents.type', 'lmscontents.japanese_writing_practice_id'])
            ->join('lmsseries', 'lmsseries.id', '=', 'lmscontents.lmsseries_id')
            ->where([
                ['lmsseries.slug', $slug],
                ['lmscontents.delete_status', 0],
            ])
            ->orderBy('stt')
            ->get();
        $this->setSettings();
        return DataTables::of($records)
            ->addColumn('hocthu', function ($records) {
                if (in_array($records->type, ['1', '2', '6', '9', '3', '4', '5', '10', '11', '12'])) {
                    $extra = '<div class="form-check text-center">
                      <input ' . ($records->el_try == 1 ? 'checked' : '') . ' class="form-check-input" onclick="update_try(' . $records->id . ',' . $records->el_try . ')" type="checkbox" style="display: inline-block; width: 20px;height: 20px;" value="" id="flexCheckDefault">
                    </div>';
                } else {
                    $extra = '';
                }
                return $extra;
            })
            ->addColumn('action', function ($records) {
                $extra = '<div class="dropdown more">
                            <a id="dLabel" type="button" class="more-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                              <i class="mdi mdi-dots-vertical"></i>
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="dLabel">';
                if (in_array($records->type, ['3', '4', '5'])) {
                    $extra .= '<li><a href="' . 'content/view/' . $records->id . '"><i class="fa fa-eye"></i>' . getPhrase("view") . '</a></li>';
                }
                $extra .= '<li><a href="' . 'content/edit/' . $records->id . '"><i class="fa fa-pencil"></i>' . getPhrase("edit") . '</a></li>';
                $extra .= '<li><a href="' . 'content/add/after/' . $records->id . '"><i class="fa fa-plus"></i>Thêm vào sau</a></li>';
                $extra .= '<li><a href="javascript:void(0)" onclick="myModal(' . $records->id . ')"><i class="fa fa-repeat"></i>Chuyển sau vị trí</a></li>';
                if (checkRole(getUserGrade(2))) {
                    $extra .= '<li><a href="javascript:void(0);" onclick="deleteRecord(\'' . $records->id . '\');"><i class="fa fa-trash"></i>' . getPhrase("delete") . '</a></li>';
                }
                $extra .= '</ul></div>';
                return $extra;
            })
            ->editColumn('title', function ($records) {
                $icon = "";
                switch ($records->type) {
                    case '0':
                        $img = '<img data-id="' . $records->id . '" src="' . asset($records->image) . '" style="width: 20px; height: 20px !important; margin-right: 5px;">';
                        break;
                    case '5':
                        $img = '<img data-id="' . $records->id . '" src="' . asset($records->image) . '" style="width: 20px; height: 20px !important; margin-right: 5px;">';
                        break;
                    case '8':
                        $img = '> ';
                        break;
                    case '9':
                        $img = '<img data-id="' . $records->id . '" src="' . asset($records->image) . '" style="width: 20px; height: 20px !important; margin-right: 5px;">';
                        break;
                    default:
                        $img = '> >';
                        break;
                }
                return $img . " <a href='" . 'content/edit/' . $records->id . "'>" . $records->bai . ' ' . $records->title . "</a>";
            })
            ->editColumn('import', function ($records) {
                if (in_array($records->type, ['1', '2', '6', '9'])) {
                    return ($records->file_path != null) ? '<span class="label label-success">Đã import video</span> ' : '<span class="label label-warning">Chưa import video</span>';
                }
                if (in_array($records->type, ['2', '3', '4'])) {
                    $check = DB::table('lms_exams')
                        ->select('id')
                        ->where('content_id', $records->id)
                        ->get();
                    return (!$check->isEmpty()) ? '<span class="label label-success">Đã có bài tâp</span> ' : '<span class="label label-warning">Chưa có bài tập</span>';
                }
                if (in_array($records->type, ['5'])) {
                    $check = DB::table('lms_test')
                        ->select('id')
                        ->where('content_id', $records->id)
                        ->get();
                    return (!$check->isEmpty()) ? '<span class="label label-success">Đã có bài test</span> ' : '<span class="label label-warning">Chưa có bài test</span>';
                }
                if (in_array($records->type, ['13'])) {
                    $check = DB::table('traffic_rule_test_question')
                        ->select('id')
                        ->where('lms_content_id', $records->id)
                        ->get();
                    return (!$check->isEmpty()) ? '<span class="label label-success">Đã có bài kiểm tra</span> ' : '<span class="label label-warning">Chưa có bài kiểm tra</span>';
                }
                if (in_array($records->type, ['11'])) {
                    $japanese_writing_practice_id = optional($records)->japanese_writing_practice_id;
                    return ($japanese_writing_practice_id !== null) ? '<span class="label label-success">Có bài luyện viết</span> ' : '<span class="label label-warning">Chưa có bài luyện viết</span>';
                }
                return null;
                // if(in_array($records->type,['8']))
                // return
                // (in_array($records->type,['8']) ?($records->import == 1 ? '<span class="label label-success">Đã import bài tâp</span> ' : '<span class="label label-warning">Chưa import bài tâp</span>') : null);
            })
            ->editColumn('type', function ($records) {
                $dr_loai = ['0' => 'Menu', '1' => 'Từ vựng', '2' => 'Bài học', '3' => 'Bài tập', '4' => 'Bài tập toàn bài', '5' => 'Bài test', '6' => 'Hán tự', '7' => 'Bài ôn tập', '8' => 'Sub menu', '9' => 'Giới thiệu', '10' => 'Flashcard', '11' => 'Luyện viết', '12' => 'Luyện phát âm', '13' => 'Bài kiểm tra luật lệ giao thông'];
                return $dr_loai[$records->type];
            })
            ->editColumn('image', function ($records) {
                if (!in_array($records->type, ['0', '5', '8', '9'])) {
                    return null;
                }
                $html = '<div class="flex-container">';

                // Check if an image exists
                if (!empty($records->image)) {
                    $html .= '<img data-id="' . $records->id . '" src="' . asset($records->image) . '" alt="" style="width: 25px; height: 25px !important; object-fit: cover" />';
                } else {
                    $html .= '<img data-id="' . $records->id . '" src="" alt="" style="display: none; width: 25px; height: 25px !important; object-fit: cover" />';
                }

                // Add an upload button and hidden input field for uploading
                $html .= '
                    <input type="file" name="image" class="image-upload-input" accept="image/*" style="display: none;" data-id="' . $records->id . '">
                    <button type="button" class="btn btn-primary btn-sm upload-image" data-id="' . $records->id . '">Chọn Icon</button>
                </div>';

                return $html;
            })
            ->removeColumn('bai')
            ->removeColumn('file_path')
            ->removeColumn('id')
            ->removeColumn('slug')
            ->removeColumn('series_slug')
            ->removeColumn('el_try')
            ->rawColumns(['action', 'title', 'import', 'hocthu', 'image'])
            // ->editColumn('image', function($records){
            //   $image_path = IMAGE_PATH_UPLOAD_LMS_DEFAULT;
            //
            //   if($records->image)
            //   $image_path = IMAGE_PATH_UPLOAD_LMS_CONTENTS.$records->image;
            //
            //   return '<img src="'.$image_path.'" height="100" width="100" />';
            // })
            ->make();
    }
    /**
     * This method loads the create view
     * @return void
     */
    public function createAfter($series, $slug)
    {
        if (!checkRole(getUserGrade(2))) {
            prepareBlockUserMessage();
            return back();
        }
        $lsmContentAfter = LmsContent::find($slug);
        $data['lms_content_after']         = $lsmContentAfter;
        //$data['lms_content_after_bai']         = $lsmContentAfter->bai;
        //dd($lsmContent);
        $list             = DB::table('lms_flashcard')->get();
        $flashcard         = array_pluck($list, 'name', 'id');
        $listHandwriting = DB::table('japanese_writing_practices')->get();
        $handwriting = array_pluck($listHandwriting, 'title', 'id');
        $listPronunciation = $this->pronunciationService->getAll();
        $pronunciation = array_pluck($listPronunciation, 'title', 'id');
        // dd($flashcard);
        $data['flashcard'] = array(''=>'-- Chọn Flashcard --') + $flashcard;
        $data['handwriting'] = array(''=>'-- Chọn bài luyện viết --') + $handwriting;
        $data['pronunciation'] = array('' => '-- Chọn bài luyện phát âm --') + $pronunciation;
        // dd($data['flashcard']);
        $data['URL_LMS_CONTENT_ADD'] = PREFIX . "lms/$series/content/add";
        $data['URL_LMS_CONTENT']     = PREFIX . "lms/$series/content";
        $data['series_slug']         = $series;
        $data['record']              = false;
        $data['active_class']        = 'lms';
        $data['title']               = 'Thêm bài học';
        $data['layout']              = getLayout();
        // return view('lms.lmscontents.add-edit', $data);
        $view_name = 'admin.lms.lmscontents.add-edit-after';
        return view($view_name, $data);
    }
    public function storeAfter(Request $request)
    {
        if (!checkRole(getUserGrade(2))) {
            prepareBlockUserMessage();
            return back();
        }
        $file_path = '';
        //die();
        DB::beginTransaction();
        try {
            $lmsseries_id_q = DB::table('lmsseries')
                ->select('id')
                ->where('slug', $request->series_slug)
                ->get()->first();
            $lms_content_after_id = $request->lms_content_after_id;
            $lms_content_after = LmsContent::find($lms_content_after_id);
            //update stt + 1
            LmsContent::where('lmsseries_id', $lmsseries_id_q->id)
              ->where('stt','>', $lms_content_after->stt)
              ->where('delete_status', 0)
              ->increment('stt');
            // dd($lms_content_after);
            $getStt =   $lms_content_after->stt + 1;
            switch ($request->loai) {
              case 0:
                $parent_id = null; //Menu
                break;
              case 1:
                if( $lms_content_after->type == 8) {
                  $parent_id = $lms_content_after_id;
                } else {
                  $parent_id = $lms_content_after->parent_id;
                }
                break;
              case 2:
                if( $lms_content_after->type == 8) {
                  $parent_id = $lms_content_after_id;
                } else {
                  $parent_id = $lms_content_after->parent_id;
                }
                break;
              case 3:
                if( $lms_content_after->type == 8) {
                  $parent_id = $lms_content_after_id;
                } else {
                  $parent_id = $lms_content_after->parent_id;
                }
                break;
              case 4:
                if( $lms_content_after->type == 8) {
                  $parent_id = $lms_content_after_id;
                } else {
                  $parent_id = $lms_content_after->parent_id;
                }
                break;
              case 5:
                $parent_id = null;
                break;
              case 6:
                if( $lms_content_after->type == 8) {
                  $parent_id = $lms_content_after_id;
                } else {
                  $parent_id = $lms_content_after->parent_id;
                }
                break;
              case 7:
                $parent_id = null;
                break;
              case 8:
                if ($lms_content_after->type == 0 || $lms_content_after->type == 8) {
                  $parent_id = $lms_content_after_id;
                } else {
                  $lms_content_after_after = LmsContent::find($lms_content_after->parent_id)->parent_id;
                  $parent_id = $lms_content_after_after;
                }
                break;
              case 9:
                $parent_id = null;
                break;
              case 10:
                if( $lms_content_after->type == 8) {
                  $parent_id = $lms_content_after_id;
                } else {
                  $parent_id = $lms_content_after->parent_id;
                }
                break;
              case 11:
                if( $lms_content_after->type == 8) {
                    $parent_id = $lms_content_after_id;
                } else {
                $parent_id = $lms_content_after->parent_id;
                }
                break;
              case 12:
                if ($lms_content_after->type == 8) {
                    $parent_id = $lms_content_after_id;
                } else {
                    $parent_id = $lms_content_after->parent_id;
                }
                break;
              default:
                $parent_id = null; //Submenu
                break;
            }
            //dd ($request); exit;
            //insert bai hoc
            $record               = new LmsContent();
            $name                 = $request->bai;
            $slug_insert          = createSlug($name);
            // $record->title        = $name;
            $record->slug         = $slug_insert;
            $record->parent_id    = $parent_id;
            $record->flashcard_id = $request->flashcard;
            $record->japanese_writing_practice_id = $request->handwriting;
            $record->pronunciation_id = $request->pronunciation;
            $record->lmsseries_id = $lmsseries_id_q->id;
            $record->bai          = $request->bai;
            $record->type         = $request->loai;
            $record->maucau       = $request->maucau;
            $record->content_type = 'url';
            $record->stt          = $getStt;
            $record->file_path    = $request->file_path;
            $record->description  = '';
            $record->created_by   = Auth::user()->id;
            $record->save();
            DB::table('lmsseries_data')->insert([
                [
                    'lmsseries_id'  => $lmsseries_id_q->id,
                    'lmscontent_id' => $record->id,
                ],
            ]);
            # import video
            $file_name = 'lms_file';
            if ($request->hasFile($file_name)) {
                // $slug_inser = folder name ( id + random string )
                $slug_insert = $record->id . '-' . time();
                $this->setSettings();
                $examSettings = $this->getSettings();
                $path         = $examSettings->contentImagepath;
                $this->deleteFile($record->file_path, $path);
                $file_video_name = $this->processUpload($request, $record, $file_name, false);
                $realpath_save   = public_path() . '/uploads/lms/content/' . $file_video_name;
                if (!file_exists(public_path() . '/uploads/lms/content/' . $slug_insert)) {
                    mkdir(public_path() . '/uploads/lms/content/' . $slug_insert);
                }
                // send stream video
                $data = array(
                    'upload'        => 1,
                    'file_contents' => new \CURLFile($realpath_save),
                    'path'          => $slug_insert,
                );
                try {
                    $curl = curl_init();
                    curl_setopt_array($curl, array(
                        // curlopt url of dev.hikariacademy.edu.vn api end point
                        CURLOPT_URL            => env('VIDEO_ENCRYPT_URL', 'http://dev.hikariacademy.edu.vn').'/stream-video/api.php/hls',
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_CUSTOMREQUEST  => "POST",
                        CURLOPT_HTTPHEADER     => array('Content-Type: multipart/form-data'),
                        CURLOPT_POSTFIELDS     => $data,
                        CURLOPT_SSL_VERIFYHOST => false,
                        CURLOPT_SSL_VERIFYPEER => false,
                    ));
                    $response = curl_exec($curl);
                    curl_close($curl);
                    if ($response === false) {
                        throw new Exception(curl_error($curl), curl_errno($curl));
                    } else {
                        // url of zip file in dev.hikariacademy.edu.vn
                        $url     = env('VIDEO_ENCRYPT_URL', 'http://dev.hikariacademy.edu.vn').'/stream-video/zip/' . $slug_insert . '/' . $slug_insert . '.zip';
                        $zipFile = public_path() . '/uploads/lms/content/' . $slug_insert . '/video.zip';
                        file_put_contents($zipFile, fopen($url, 'r'));
                        $zip         = new \ZipArchive;
                        $extractPath = public_path() . '/uploads/lms/content/' . $slug_insert;
                        if ($zip->open($zipFile) != "true") {
                            flash('error', 'record_added_successfully', 'error');
                        }
                        $zip->extractTo($extractPath);
                        $zip->close();
                    }
                } catch (Exception $e) {
                    dd($e);
                }
                $record->file_path = '/public/uploads/lms/content/' . $slug_insert . '/video.m3u8';
                $record->import    = '1';
                $record->save();
                @unlink($zipFile);
                @unlink($realpath_save);
            }
            # end import video
            # import bai tap mau cau
            if ($request->hasFile('lms_excel')) {
                $path = $request->file('lms_excel')->getRealPath();
                config(['excel.import.startRow' => 2]);
                $data = Excel::selectSheetsByIndex(0)->load($path, function ($reader) {
                    $reader->noHeading();
                })->get();
                // dd($data);
                if (!empty($data) && $data->count()) {
                    $content_update = DB::table('lmscontents')
                        ->select(['id', 'maucau'])
                        ->where([
                            ['parent_id', $record->id],
                            ['maucau', '<>', null],
                        ])->get();
                    $dr_update = [];
                    foreach ($content_update as $r) {
                        $dr_update[$r->maucau] = $r->id;
                        DB::table('lms_exams')
                            ->where([
                                ['content_id', $r->id],
                            ])
                            ->update(['delete_status' => 1]);
                    }
                    $ignoreHeading = 'label';
                    DB::beginTransaction();
                    foreach ($dr_update as $r) {
                        DB::table('lms_exams')
                            ->where('content_id', $r)
                            ->update([
                                'delete_status' => '1',
                            ]);
                    }
                    try {
                        foreach ($data as $r) {
                            $bai    = (int) filter_var($r[0], FILTER_SANITIZE_NUMBER_INT);
                            $maucau = (int) filter_var($r[1], FILTER_SANITIZE_NUMBER_INT);
                            if (0 === 1) {
                                DB::rollBack();
                                flash('oops...!', 'Import bài tập sai bài', 'error');
                            }
                            if (isset($dr_update[$maucau])) {
                                if ($r[2] != $ignoreHeading) {
                                    $check = DB::table('lms_exams')->insertGetId([
                                        'content_id' => $dr_update[$maucau],
                                        'label'      => $r[2],
                                        'dang'       => $r[3],
                                        'cau'        => $r[4],
                                        'mota'       => (string) $r[5],
                                        'luachon1'   => $r[6],
                                        'luachon2'   => $r[7],
                                        'luachon3'   => $r[8],
                                        'luachon4'   => $r[9],
                                        'dapan'      => $r[10],
                                        'created_by' => Auth::id(),
                                    ]);
                                }
                            }
                        }
                        DB::commit();
                    } catch (Exception $e) {
                        DB::rollBack();
                        flash('Có lỗi xẩy ra', '', 'error');
                        // dd($e);
                    }
                    $record->import = '1';
                    $record->save();
                }
            }
            # end import bai tap mau cau
            # import bai test
            if ($request->hasFile('lms_test')) {
                $path = $request->file('lms_test')->getRealPath();
                config(['excel.import.startRow' => 2]);
                $data = Excel::selectSheetsByIndex(0)->load($path, function ($reader) {
                    $reader->noHeading();
                })->get();
                if (!empty($data) && $data->count()) {
                    $ignoreHeading = 'label';
                    DB::beginTransaction();
                    DB::table('lms_test')
                        ->where('content_id', $record->id)
                        ->update([
                            'delete_status' => 1,
                        ]);
                    try {
                        foreach ($data as $r) {
                            if ($r[0] != $ignoreHeading && $r[1] != null && $r[2] != null) {
                                $check = DB::table('lms_test')->insertGetId([
                                    'content_id'   => $record->id,
                                    'dang'         => $r[1],
                                    'cau'          => $r[2],
                                    'mota'         => $r[3],
                                    'luachon1'     => $r[4],
                                    'luachon2'     => $r[5],
                                    'luachon3'     => $r[6],
                                    'luachon4'     => $r[7],
                                    'dapan'        => $r[8],
                                    'diem'         => $r[9],
                                    'content_type' => '5',
                                    'created_by'   => Auth::id(),
                                ]);
                            }
                        }
                        DB::commit();
                    } catch (Exception $e) {
                        DB::rollBack();
                        // dd($e);
                    }
                    $record->import = '1';
                    $record->save();
                }
            }
            # end import bai test
            # import bai tap loai 4
            if ($request->hasFile('lms_type_4')) {
                $path = $request->file('lms_type_4')->getRealPath();
                config(['excel.import.startRow' => 2]);
                $data = Excel::selectSheetsByIndex(0)->load($path, function ($reader) {
                    $reader->noHeading();
                })->get();
                // dd($data);
                if (!empty($data) && $data->count()) {
                    try {
                        DB::beginTransaction();
                        DB::table('lms_exams')
                            ->where('content_id', $record->id)
                            ->update([
                                'delete_status' => '1',
                            ]);
                        foreach ($data as $r) {
                            if ($r[1] != null && $r[1] != '') {
                                $check = DB::table('lms_exams')->insertGetId([
                                    'content_id' => $record->id,
                                    'dang'       => $r[1],
                                    'cau'        => $r[2],
                                    'mota'       => $r[3],
                                    'luachon1'   => $r[4],
                                    'luachon2'   => $r[5],
                                    'luachon3'   => $r[6],
                                    'luachon4'   => $r[7],
                                    'dapan'      => $r[8],
                                    'created_by' => Auth::id(),
                                ]);
                            }
                        }
                        DB::commit();
                    } catch (Exception $e) {
                        DB::rollBack();
                        flash('oops...!', 'opps..........!!!', 'error');
                        dd($e);
                    }
                    $record->import = '1';
                    $record->save();
                }
            }
            # end import bai tap loai 4
            DB::commit();
            flash('Thêm bài học thành công', '', 'success');
        } catch (Exception $e) {
            dd($e);
            DB::rollBack();
            if (getSetting('show_foreign_key_constraint', 'module')) {
                flash('Lỗi', $e->errorInfo, 'error');
            } else {
                flash('Lỗi', 'improper_data_file_submitted', 'error');
            }
        }
        return redirect(PREFIX . "lms/" . $request->series_slug . '/content');
    }
    public function create($series)
    {
        if (!checkRole(getUserGrade(2))) {
            prepareBlockUserMessage();
            return back();
        }
        $data['URL_LMS_CONTENT_ADD'] = PREFIX . "lms/$series/content/add";
        $data['URL_LMS_CONTENT']     = PREFIX . "lms/$series/content";
        $data['series_slug']         = $series;
        $data['record']              = false;
        $data['active_class']        = 'lms';
        $data['title']               = getPhrase('add_content');
        $data['layout']              = getLayout();
        // return view('lms.lmscontents.add-edit', $data);
        $view_name = 'admin.lms.lmscontents.add-edit';
        return view($view_name, $data);
    }
    /**
     * This method loads the edit view based on unique slug provided by user
     * @param  [string] $slug [unique slug of the record]
     * @return [view with record]
     */
    public function edit($series, $slug)
    {
        if (!checkRole(getUserGrade(2))) {
            prepareBlockUserMessage();
            return back();
        }
        $record                       = LmsContent::getRecordWithId($slug);
        $listFlashcard = $this->lmsFlashcardService->getAll();
        $flashcard = array_pluck($listFlashcard, 'name', 'id');
        $listHandwriting = $this->handwritingService->getAll();
        $handwriting = array_pluck($listHandwriting, 'title', 'id');
        $handwritingType = null;
        $listPronunciation = $this->pronunciationService->getAll();
        $pronunciation = array_pluck($listPronunciation, 'title', 'id');

        if ($record->japanese_writing_practice_id) {
            $handwritingType = $this->handwritingService->findById($record->japanese_writing_practice_id)->type;
        }

        //dd($series );
        $data['URL_LMS_CONTENT_EDIT'] = PREFIX . "lms/$series/content/edit/" . $slug;
        $data['URL_LMS_CONTENT']      = PREFIX . "lms/$series/content";
        $data['series_slug']          = $series;
        $data['record']               = $record;
        $data['flashcard'] = array(''=>'-- Chọn Flashcard --') + $flashcard;
        $data['handwriting'] = array(''=>'-- Chọn bài luyện viết --') + $handwriting;
        $data['handwriting_type'] = $handwritingType;
        $data['pronunciation'] = array('' => '-- Chọn bài luyện phát âm --') + $pronunciation;
        $data['title']                = 'Cập nhật ' . $record->bai;
        $data['active_class']         = 'lms';
        $data['settings']             = json_encode($record);
        $data['layout']               = getLayout();
        // return view('lms.lmscontents.add-edit', $data);
        $view_name = 'admin.lms.lmscontents.add-edit';
        return view($view_name, $data);
    }
    /**
     * Update record based on slug and reuqest
     * @param  Request $request [Request Object]
     * @param  [type]  $slug    [Unique Slug]
     * @return void
     */
    public function update(Request $request, $series, $slug)
    {
        $record    = LmsContent::getRecordWithId($slug);
        $file_path = $record->file_path;
        //dd($file_path);
		DB::beginTransaction();
        // dd($record->id);
        try {
            $name = $request->title;
            if ($name != $record->title) {
                $slug_insert  = createSlug($name);
                $record->slug = $slug_insert;
            } else {
                $slug_insert = $slug;
            }
            $name                      = $request->title;
            $record->title             = $name;
            $record->bai               = $request->bai;
            $record->type              = $request->loai;
            $record->maucau            = $request->maucau;
            $record->video_duration    = $request->video_duration;
            //$record->stt               = $request->stt;
            //$record->file_path         = $file_path;
            $record->description       = $request->description;
            $record->record_updated_by = Auth::user()->id;
            $record->save();
            $file_name = 'image';
            if ($request->hasFile($file_name)) {
                $rules = array($file_name => 'mimes:jpeg,jpg,png,gif|max:10000');
                $this->validate($request, $rules);
                $this->setSettings();
                $examSettings = $this->getSettings();
                $path         = $examSettings->contentImagepath;
                $this->deleteFile($record->image, $path);
                $record->image = $this->processUpload($request, $record, $file_name);
                $record->save();
            }

			# import video clip
            $file_name = 'lms_file';
			// save_point
            if ($request->hasFile('lms_file')) {
              $slug_insert = $record->id . '-' . time();
              $this->setSettings();
              $examSettings = $this->getSettings();
              $path = $examSettings->contentImagepath;

              // Xử lý file upload
              $file_video_name = $this->processUpload($request, $record, $file_name, false);

              // Kiểm tra file path
              $realpath_save = public_path() . '/uploads/lms/content/' . $file_video_name;
              if (!file_exists($realpath_save)) {
                  throw new Exception("File not found at path: " . $realpath_save);
              }

              // Lấy mime type của file
              $finfo = finfo_open(FILEINFO_MIME_TYPE);
              $mime_type = finfo_file($finfo, $realpath_save);
              finfo_close($finfo);

              // Tạo thư mục nếu chưa tồn tại
              $upload_dir = public_path() . '/uploads/lms/content/' . $slug_insert;
              if (!file_exists($upload_dir)) {
                  mkdir($upload_dir, 0755, true);
              }

              // Chuẩn bị CURLFile
              $curl_file = new \CURLFile(
                  $realpath_save,  // File path
                  $mime_type,      // Mime type
                  basename($realpath_save) // Post name
              );

              // Chuẩn bị data
              $data = array(
                  'upload' => 1,
                  'file_contents' => $curl_file,
                  'path' => $slug_insert,
              );

              try {
                  $curl = curl_init();

                  // Enable verbose debug output
                  curl_setopt($curl, CURLOPT_VERBOSE, true);
                  $verbose = fopen('php://temp', 'w+');
                  curl_setopt($curl, CURLOPT_STDERR, $verbose);

                  curl_setopt_array($curl, array(
                      CURLOPT_URL => env('VIDEO_ENCRYPT_URL', 'http://dev.hikariacademy.edu.vn').'/stream-video/api.php/hls',
                      CURLOPT_RETURNTRANSFER => true,
                      CURLOPT_POST => true,
                      CURLOPT_CUSTOMREQUEST => "POST",
                      CURLOPT_HTTPHEADER => array('Content-Type: multipart/form-data'),
                      CURLOPT_POSTFIELDS => $data,
                      CURLOPT_CONNECTTIMEOUT => 30,
                      CURLOPT_TIMEOUT => 600,
                      CURLOPT_SSL_VERIFYHOST => false,
                      CURLOPT_SSL_VERIFYPEER => false,
                  ));

                  // Log request data trước khi gửi
                  \Log::info("Sending CURL request with data: ", [
                      'file_path' => $realpath_save,
                      'file_exists' => file_exists($realpath_save),
                      'file_size' => filesize($realpath_save),
                      'mime_type' => $mime_type,
                      'slug' => $slug_insert
                  ]);

                  $response = curl_exec($curl);

                  if ($response === false) {
                      rewind($verbose);
                      $verboseLog = stream_get_contents($verbose);

                      \Log::error("CURL Error: " . curl_error($curl));
                      \Log::error("CURL Error No: " . curl_errno($curl));
                      \Log::error("CURL Verbose Log: " . $verboseLog);

                      throw new Exception(curl_error($curl), curl_errno($curl));
                  }
                  else {
                    // url of zip file in dev.hikariacademy.edu.vn
                    $url     = env('VIDEO_ENCRYPT_URL', 'http://dev.hikariacademy.edu.vn').'/stream-video/zip/' . $slug_insert . '/' . $slug_insert . '.zip';
                    $zipFile = public_path() . '/uploads/lms/content/' . $slug_insert . '/video.zip';
                    file_put_contents($zipFile, fopen($url, 'r'));
                    $zip         = new \ZipArchive;
                    $extractPath = public_path() . '/uploads/lms/content/' . $slug_insert;
                    if ($zip->open($zipFile) != "true") {
                        flash('error', 'record_added_successfully', 'error');
                    }
                    $zip->extractTo($extractPath);
                    $zip->close();
                }

                  curl_close($curl);
                  fclose($verbose);

                  // Process response...

              } catch (Exception $e) {
                  \Log::error("Upload failed: " . $e->getMessage());
                  throw $e;
              }

              $record->file_path = '/public/uploads/lms/content/' . $slug_insert . '/video.m3u8';
              $record->import    = '1';
              $record->save();
              //Delete whole old content directory. Added by NTV@20212110
              if (isset($old_directory_path)){
                  $path = public_path() . '/uploads/lms/content/'.$old_directory_path;
                  if (\File::exists($path)) \File::deleteDirectory($path);
              }
              @unlink($zipFile); //delete zip file
              @unlink($realpath_save); //delete video file
            }

            # end import video
            # import bai tap mau cau
            if ($request->hasFile('lms_excel')) {
                $path = $request->file('lms_excel')->getRealPath();
                config(['excel.import.startRow' => 2]);
                $data = Excel::selectSheetsByIndex(0)->load($path, function ($reader) {
                    $reader->noHeading();
                })->get();
                // dd($data);
                if (!empty($data) && $data->count()) {
                    $content_update = DB::table('lmscontents')
                        ->select(['id', 'maucau'])
                        ->where([
                            ['parent_id', $record->id],
                            ['maucau', '<>', null],
                        ])->get();
                    $dr_update = [];
                    foreach ($content_update as $r) {
                        $dr_update[$r->maucau] = $r->id;
                        DB::table('lms_exams')
                            ->where([
                                ['content_id', $r->id],
                            ])
                            ->update(['delete_status' => 1]);
                    }
                    $ignoreHeading = 'label';
                    foreach ($dr_update as $r) {
                        DB::table('lms_exams')
                            ->where('content_id', $r)
                            ->update([
                                'delete_status' => '1',
                            ]);
                    }
                    try {
                        foreach ($data as $r) {
                            $bai    = (int) filter_var($r[0], FILTER_SANITIZE_NUMBER_INT);
                            $maucau = (int) filter_var($r[1], FILTER_SANITIZE_NUMBER_INT);
                            if (0 === 1) {
                                DB::rollBack();
                                flash('oops...!', 'Import bài tập sai bài', 'error');
                            }
                            if (isset($dr_update[$maucau])) {
                                if ($r[2] != $ignoreHeading) {
                                    $check = DB::table('lms_exams')->insertGetId([
                                        'content_id' => $dr_update[$maucau],
                                        'label'      => $r[2],
                                        'dang'       => $r[3],
                                        'cau'        => $r[4],
                                        'mota'       => (string) $r[5],
                                        'luachon1'   => $r[6],
                                        'luachon2'   => $r[7],
                                        'luachon3'   => $r[8],
                                        'luachon4'   => $r[9],
                                        'dapan'      => $r[10],
                                        'created_by' => Auth::id(),
                                    ]);
                                }
                            }
                        }
                    } catch (Exception $e) {
                        flash('oops...!', 'opps..........!!!', 'error');
                        // dd($e);
                    }
                    $record->import = '1';
                    $record->save();
                }
            }
            // DONE: Fix issue 18
            # Upload file PDF
            $filename = 'lms_pdf';
            if($request->hasFile('lms_pdf'))
            {

                $filenames = $_FILES['lms_pdf']['name'];
                $this->setSettings();
                $examSettings = $this->getSettings();
                // destination of the file on the server
                $destination  = $examSettings->contentPDFpath . $request->series_slug;

                // get the file extension
                $extension = pathinfo($filenames, PATHINFO_EXTENSION);
                $size = $_FILES['lms_pdf']['size'] / 1024 / 1024;

                if (!in_array($extension, ['pdf'])) {
                    DB::rollBack();
                    flash('Your file extension must be .pdf', '', 'error');
                    return redirect(PREFIX . "lms/" . $request->series . '/content');
                } elseif ($size > 10) {
                    DB::rollBack();
                    flash('File size exceeds 10 MB', '', 'error');
                    return redirect(PREFIX . "lms/" . $request->series . '/content');
                } else {
                    if($request->file($filename)->move($destination, $filenames)){
                        $record->download_doc = $destination .'/'. $filenames;
                        $record->save();
                    } else {
                        DB::rollBack();
                        flash('Failed to upload file.', 'error');
                        return redirect(PREFIX . "lms/" . $request->series . '/content');
                    }
                }
            }

            # end import bai tap mau cau
            # import bai test
            if ($request->hasFile('lms_test')) {
                $path = $request->file('lms_test')->getRealPath();
                config(['excel.import.startRow' => 2]);
                $data = Excel::selectSheetsByIndex(0)->load($path, function ($reader) {
                    $reader->noHeading();
                })->get();
                if (!empty($data) && $data->count()) {
                    $ignoreHeading = 'label';
                    DB::table('lms_test')
                        ->where('content_id', $record->id)
                        ->update([
                            'delete_status' => 1,
                        ]);
                    try {
                        foreach ($data as $r) {
                            if ($r[0] != $ignoreHeading && $r[1] != null && $r[2] != null) {
                                $check = DB::table('lms_test')->insertGetId([
                                    'content_id'   => $record->id,
                                    'dang'         => $r[1],
                                    'cau'          => $r[2],
                                    'mota'         => $r[3],
                                    'luachon1'     => $r[4],
                                    'luachon2'     => $r[5],
                                    'luachon3'     => $r[6],
                                    'luachon4'     => $r[7],
                                    'dapan'        => $r[8],
                                    'diem'         => $r[9],
                                    'content_type' => '5',
                                    'created_by'   => Auth::id(),
                                ]);
                            }
                        }
                    } catch (Exception $e) {
                        // dd($e);
                    }
                    $record->import = '1';
                    $record->save();
                }
            }
            # end import bai test
            # import bai tap loai 4
            if ($request->hasFile('lms_type_4')) {
                $path = $request->file('lms_type_4')->getRealPath();
                config(['excel.import.startRow' => 2]);
                $data = Excel::selectSheetsByIndex(0)->load($path, function ($reader) {
                    $reader->noHeading();
                })->get();
                // dd($data);
                if (!empty($data) && $data->count()) {
                    try {
                        DB::table('lms_exams')
                            ->where('content_id', $record->id)
                            ->update([
                                'delete_status' => '1',
                            ]);
                        foreach ($data as $r) {
                            if ($r[1] != null && $r[1] != '') {
                                $check = DB::table('lms_exams')->insertGetId([
                                    'content_id' => $record->id,
                                    'dang'       => $r[1],
                                    'cau'        => $r[2],
                                    'mota'       => $r[3],
                                    'luachon1'   => $r[4],
                                    'luachon2'   => $r[5],
                                    'luachon3'   => $r[6],
                                    'luachon4'   => $r[7],
                                    'dapan'      => $r[8],
                                    'created_by' => Auth::id(),
                                ]);
                            }
                        }
                    } catch (Exception $e) {
                        DB::rollBack();
                        flash('oops...!', '', 'error');
                        dd($e);
                    }
                    $record->import = '1';
                    $record->save();
                }
            }

            if ((int) $request->loai == LmsContent::FLASHCARD) {
                $result = $this->lmsFlashcardService->getByConditions([
                    'id' => $request->flashcard,
                ]);

                if (!$result) {
                    DB::rollBack();
                    return redirect()->back()
                        ->withErrors(['error' => 'Bài Flashcard không tồn tại'])
                        ->withInput($request->all());
                }

                $record->flashcard_id = $request->flashcard;
                $record->save();
            }

            if ((int) $request->loai == LmsContent::HANDWRITING) {
                $result = $this->handwritingService->getByConditions([
                    'id' => $request->handwriting,
                    'type' => $request->type
                ]);

                if (!$result) {
                    DB::rollBack();
                    return redirect()->back()
                        ->withErrors(['error' => 'Bài luyện viết và loại bài luyện viết phải khớp với nhau'])
                        ->withInput($request->all());
                }

                $record->japanese_writing_practice_id = $request->handwriting;
                $record->save();
            }

            if ((int) $request->loai == LmsContent::PRONUNCIATION_ASSESSMENT) {
                $result = $this->pronunciationService->getByConditions([
                    'id' => $request->pronunciation,
                ]);

                if (!$result) {
                    DB::rollBack();
                    return redirect()->back()
                        ->withErrors(['error' => 'Bài luyện phát âm không tồn tại'])
                        ->withInput($request->all());
                }

                $record->pronunciation_id = $request->pronunciation;
                $record->save();
            }

            // Update traffic rule test with imported excel file
            if ((int) $request->loai == LmsContent::TEST_TRAFFIC_RULE) {
                if (!$request->hasFile('lms_test_traffic_rule')) {
                    throw new Exception("Thiếu file import bài kiểm tra giao thông.");
                }

                $extension = strtolower($request->file('lms_test_traffic_rule')->getClientOriginalExtension());
                if (!in_array($extension, ['xlsx', 'xls'])) {
                    throw new Exception('File phải là 1 file excel.');
                }

                $path = $request->file('lms_test_traffic_rule')->getRealPath();
                config(['excel.import.startRow' => 2]);
                $data = Excel::selectSheetsByIndex(0)->load($path, function ($reader) {
                    $reader->noHeading();
                })->get();

                if (!empty($data) && $data->count()) {
                    TrafficRuleTestQuestion::query()
                        ->where('lms_content_id', $record->id)
                        ->update(['is_deleted' => 1]);

                    $parent_question_id = null;
                    foreach ($data as $column_index => $r) {
                        $question_order = $r[0];
                        $content = $r[1];
                        $child_content = $r[2];
                        $image_url = $r[3];
                        $option_1 = $r[4];
                        $option_2 = $r[5];
                        $answer = $r[6];

                        $is_single_question = $question_order && $content && !$child_content && $option_1 && $option_2 && $answer;
                        $is_parent_question = $question_order && $content && !$child_content && !$option_1 && !$option_2 && !$answer;
                        $is_child_question = !$question_order && !$content && $child_content && $option_1 && $option_2 && $answer;

                        $is_empty_row = !$question_order && !$content && !$child_content && !$image_url && !$option_1 && !$option_2 && !$answer;
                        if ($is_empty_row) {
                            continue;
                        }

                        $point = $is_parent_question ? 2 : 1;
                        $parent_question_id = $is_parent_question ? null : $parent_question_id;

                        if ($is_single_question || $is_parent_question || $is_child_question) {
                            $question = TrafficRuleTestQuestion::create([
                                'lms_content_id'     => $record->id,
                                'parent_question_id' => $parent_question_id,
                                'question_order'     => $question_order,
                                'content'            => $is_child_question ? $child_content : $content,
                                'point'              => $point,
                                'image_url'          => $image_url,
                                'option_1'           => $option_1,
                                'option_2'           => $option_2,
                                'answer'             => $answer,
                            ]);

                            $parent_question_id = $is_parent_question ? $question->id : (
                                $is_child_question ? $parent_question_id : null
                            );
                        } else {
                            $excel_column_index = $column_index + 2;
                            throw new Exception("Lỗi format dòng $excel_column_index.");
                        }
                    }
                    $record->save();
                }
            }
            # end import bai tap loai 4
            DB::commit();
            flash('success', 'Chỉnh sửa thành công', 'success');
        } catch (Exception $e) {
            // dd($e);
            DB::rollBack();
            flash('oops...!', $e->getMessage(), 'error');
            // if (getSetting('show_foreign_key_constraint', 'module')) {
            // } else {
            //     flash('oops...!', 'improper_data_file_submitted', 'error');
            // }
        }
        // die;
        return redirect(PREFIX . "lms/" . $request->series . '/content');
    }
    /**
     * This method adds record to DB
     * @param  Request $request [Request Object]
     * @return void
     */
    public function store(Request $request)
    {
        if (!checkRole(getUserGrade(2))) {
            prepareBlockUserMessage();
            return back();
        }
        $file_path = '';
        DB::beginTransaction();
        try {
            $lmsseries_id_q = DB::table('lmsseries')
                ->select('id')
                ->where('slug', $request->series_slug)
                ->get()->first();
            $record               = new LmsContent();
            $name                 = $request->title;
            $slug_insert          = createSlug($name);
            $record->title        = $name;
            $record->slug         = $slug_insert;
            $record->lmsseries_id = $lmsseries_id_q->id;
            $record->bai          = $request->bai;
            $record->type         = $request->loai;
            $record->maucau       = $request->maucau;
            // $record->code               = $request->code;
            $record->content_type      = 'url';
            $record->file_path         = $request->file_path;
            $record->stt               = $request->stt;
            $record->file_path         = $request->file_path;
            $record->description       = $request->description;
            $record->record_updated_by = Auth::user()->id;
            $record->save();
            DB::table('lmsseries_data')->insert([
                [
                    'lmsseries_id'  => $lmsseries_id_q->id,
                    'lmscontent_id' => $record->id,
                ],
            ]);
            $file_name = 'image';
            if ($request->hasFile($file_name)) {
                $rules = array($file_name => 'mimes:jpeg,jpg,png,gif|max:10000');
                $this->validate($request, $rules);
                $this->setSettings();
                $examSettings = $this->getSettings();
                $path         = $examSettings->contentImagepath;
                $this->deleteFile($record->image, $path);
                $record->image = $this->processUpload($request, $record, $file_name);
                $record->save();
            }
            # import video
            $file_name = 'lms_file';
            if ($request->hasFile($file_name)) {
                // $slug_inser = folder name ( id + random string )
                $slug_insert = $record->id . '-' . time();
                $this->setSettings();
                $examSettings = $this->getSettings();
                $path         = $examSettings->contentImagepath;
                $this->deleteFile($record->file_path, $path);
                $file_video_name = $this->processUpload($request, $record, $file_name, false);
                $realpath_save   = public_path() . '/uploads/lms/content/' . $file_video_name;
                if (!file_exists(public_path() . '/uploads/lms/content/' . $slug_insert)) {
                    mkdir(public_path() . '/uploads/lms/content/' . $slug_insert);
                }
                // send stream video
                $data = array(
                    'upload'        => 1,
                    'file_contents' => new \CURLFile($realpath_save),
                    'path'          => $slug_insert,
                );
                try {
                    $curl = curl_init();
                    curl_setopt_array($curl, array(
                        // curlopt url of dev.hikariacademy.edu.vn api end point
                        CURLOPT_URL            => env('VIDEO_ENCRYPT_URL', 'http://dev.hikariacademy.edu.vn')."/stream-video/api.php/hls",
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_CUSTOMREQUEST  => "POST",
                        CURLOPT_HTTPHEADER     => array('Content-Type: multipart/form-data'),
                        CURLOPT_POSTFIELDS     => $data,
                        CURLOPT_SSL_VERIFYHOST => false,
                        CURLOPT_SSL_VERIFYPEER => false,
                    ));
                    $response = curl_exec($curl);
                    curl_close($curl);
                    if ($response === false) {
                        throw new Exception(curl_error($curl), curl_errno($curl));
                    } else {
                        // url of zip file in dev.hikariacademy.edu.vn
                        $url     = env('VIDEO_ENCRYPT_URL', 'http://dev.hikariacademy.edu.vn').'/stream-video/zip/' . $slug_insert . '/' . $slug_insert . '.zip';
                        $zipFile = public_path() . '/uploads/lms/content/' . $slug_insert . '/video.zip';
                        file_put_contents($zipFile, fopen($url, 'r'));
                        $zip         = new \ZipArchive;
                        $extractPath = public_path() . '/uploads/lms/content/' . $slug_insert;
                        if ($zip->open($zipFile) != "true") {
                            flash('error', 'record_added_successfully', 'error');
                        }
                        $zip->extractTo($extractPath);
                        $zip->close();
                    }
                } catch (Exception $e) {
                    dd($e);
                }
                $record->file_path = '/public/uploads/lms/content/' . $slug_insert . '/video.m3u8';
                $record->import    = '1';
                $record->save();
                @unlink($zipFile);
                @unlink($realpath_save);
            }
            # end import video
            # import bai tap mau cau
            if ($request->hasFile('lms_excel')) {
                $path = $request->file('lms_excel')->getRealPath();
                config(['excel.import.startRow' => 2]);
                $data = Excel::selectSheetsByIndex(0)->load($path, function ($reader) {
                    $reader->noHeading();
                })->get();
                // dd($data);
                if (!empty($data) && $data->count()) {
                    $content_update = DB::table('lmscontents')
                        ->select(['id', 'maucau'])
                        ->where([
                            ['parent_id', $record->id],
                            ['maucau', '<>', null],
                        ])->get();
                    $dr_update = [];
                    foreach ($content_update as $r) {
                        $dr_update[$r->maucau] = $r->id;
                        DB::table('lms_exams')
                            ->where([
                                ['content_id', $r->id],
                            ])
                            ->update(['delete_status' => 1]);
                    }
                    $ignoreHeading = 'label';
                    DB::beginTransaction();
                    foreach ($dr_update as $r) {
                        DB::table('lms_exams')
                            ->where('content_id', $r)
                            ->update([
                                'delete_status' => '1',
                            ]);
                    }
                    try {
                        foreach ($data as $r) {
                            $bai    = (int) filter_var($r[0], FILTER_SANITIZE_NUMBER_INT);
                            $maucau = (int) filter_var($r[1], FILTER_SANITIZE_NUMBER_INT);
                            if (0 === 1) {
                                DB::rollBack();
                                flash('oops...!', 'Import bài tập sai bài', 'error');
                            }
                            if (isset($dr_update[$maucau])) {
                                if ($r[2] != $ignoreHeading) {
                                    $check = DB::table('lms_exams')->insertGetId([
                                        'content_id' => $dr_update[$maucau],
                                        'label'      => $r[2],
                                        'dang'       => $r[3],
                                        'cau'        => $r[4],
                                        'mota'       => (string) $r[5],
                                        'luachon1'   => $r[6],
                                        'luachon2'   => $r[7],
                                        'luachon3'   => $r[8],
                                        'luachon4'   => $r[9],
                                        'dapan'      => $r[10],
                                        'created_by' => Auth::id(),
                                    ]);
                                }
                            }
                        }
                        DB::commit();
                    } catch (Exception $e) {
                        DB::rollBack();
                        flash('oops...!', 'opps..........!!!', 'error');
                        // dd($e);
                    }
                    $record->import = '1';
                    $record->save();
                }
            }
            # end import bai tap mau cau
            # import bai test
            if ($request->hasFile('lms_test')) {
                $path = $request->file('lms_test')->getRealPath();
                config(['excel.import.startRow' => 2]);
                $data = Excel::selectSheetsByIndex(0)->load($path, function ($reader) {
                    $reader->noHeading();
                })->get();
                if (!empty($data) && $data->count()) {
                    $ignoreHeading = 'label';
                    DB::beginTransaction();
                    DB::table('lms_test')
                        ->where('content_id', $record->id)
                        ->update([
                            'delete_status' => 1,
                        ]);
                    try {
                        foreach ($data as $r) {
                            if ($r[0] != $ignoreHeading && $r[1] != null && $r[2] != null) {
                                $check = DB::table('lms_test')->insertGetId([
                                    'content_id'   => $record->id,
                                    'dang'         => $r[1],
                                    'cau'          => $r[2],
                                    'mota'         => $r[3],
                                    'luachon1'     => $r[4],
                                    'luachon2'     => $r[5],
                                    'luachon3'     => $r[6],
                                    'luachon4'     => $r[7],
                                    'dapan'        => $r[8],
                                    'diem'         => $r[9],
                                    'content_type' => '5',
                                    'created_by'   => Auth::id(),
                                ]);
                            }
                        }
                        DB::commit();
                    } catch (Exception $e) {
                        DB::rollBack();
                        // dd($e);
                    }
                    $record->import = '1';
                    $record->save();
                }
            }
            # end import bai test
            # import bai tap loai 4
            if ($request->hasFile('lms_type_4')) {
                $path = $request->file('lms_type_4')->getRealPath();
                config(['excel.import.startRow' => 2]);
                $data = Excel::selectSheetsByIndex(0)->load($path, function ($reader) {
                    $reader->noHeading();
                })->get();
                // dd($data);
                if (!empty($data) && $data->count()) {
                    try {
                        DB::beginTransaction();
                        DB::table('lms_exams')
                            ->where('content_id', $record->id)
                            ->update([
                                'delete_status' => '1',
                            ]);
                        foreach ($data as $r) {
                            if ($r[1] != null && $r[1] != '') {
                                $check = DB::table('lms_exams')->insertGetId([
                                    'content_id' => $record->id,
                                    'dang'       => $r[1],
                                    'cau'        => $r[2],
                                    'mota'       => $r[3],
                                    'luachon1'   => $r[4],
                                    'luachon2'   => $r[5],
                                    'luachon3'   => $r[6],
                                    'luachon4'   => $r[7],
                                    'dapan'      => $r[8],
                                    'created_by' => Auth::id(),
                                ]);
                            }
                        }
                        DB::commit();
                    } catch (Exception $e) {
                        DB::rollBack();
                        flash('oops...!', 'opps..........!!!', 'error');
                        dd($e);
                    }
                    $record->import = '1';
                    $record->save();
                }
            }
            # end import bai tap loai 4
            DB::commit();
            flash('success', 'record_added_successfully', 'success');
        } catch (Exception $e) {
            dd($e);
            DB::rollBack();
            if (getSetting('show_foreign_key_constraint', 'module')) {
                flash('oops...!', $e->errorInfo, 'error');
            } else {
                flash('oops...!', 'improper_data_file_submitted', 'error');
            }
        }
        return redirect(PREFIX . "lms/" . $request->series_slug . '/content');
    }
    /**
     * Delete Record based on the provided slug
     * @param  [string] $slug [unique slug]
     * @return Boolean
     */
    public function delete($slug)
    {
        if (!checkRole(getUserGrade(2))) {
            prepareBlockUserMessage();
            return back();
        }

        DB::beginTransaction();
        try {
            $record = LmsContent::findOrFail($slug);
            $record->delete_status = 1;
            $record->save();

            LmsContent::where('lmsseries_id', $record->lmsseries_id)
                  ->where('stt','>', $record->stt)
                  ->where('delete_status', 0)
                  ->decrement('stt');
            $response['status']  = 1;
            $response['message'] = 'Xóa thành công';
            DB::commit();

        } catch (Exception $e) {
            DB::rollBack();
            $response['status']  = 0;
            $response['message'] = 'Xóa không thành công';
        }

        return json_encode($response);
    }
    public function isValidRecord($record)
    {
        if ($record === null) {
            flash('Ooops...!', getPhrase("page_not_found"), 'error');
            return $this->getRedirectUrl();
        }
        return false;
    }
    public function getReturnUrl()
    {
        return URL_LMS_CONTENT;
    }
    public function deleteFile($record, $path, $is_array = false)
    {
        if (env('DEMO_MODE')) {
            return;
        }
        $files   = array();
        $files[] = $path . $record;
        File::delete($files);
    }
    /**
     * This method process the image is being refferred
     * by getting the settings from ImageSettings Class
     * @param  Request $request   [Request object from user]
     * @param  [type]  $record    [The saved record which contains the ID]
     * @param  [type]  $file_name [The Name of the file which need to upload]
     * @return [type]             [description]
     */
    public function processUpload(Request $request, $record, $file_name, $is_image = true)
    {

        if ($request->has($file_name)) {
            $settings        = $this->getSettings();
            $destinationPath = $settings->contentImagepath;
            $path            = $_FILES[$file_name]['name'];
            $ext             = pathinfo($path, PATHINFO_EXTENSION);
            $fileName = $record->id . '-' . $file_name . '.' . $ext;
            $request->file($file_name)->move($destinationPath, $fileName);
            if ($is_image) {
                //Save Normal Image with 300x300
                Image::make($destinationPath . $fileName)->fit($settings->imageSize)->save($destinationPath . $fileName);
            }
            return $fileName;
        }
    }

    public function uploadImage(Request $request)
    {
        $request->validate([
            'image' => 'required|file|mimes:jpeg,png,jpg,gif,svg|max:2048', // Only allow specific image types and a maximum size of 2MB
            'record_id' => 'required|exists:lmscontents,id',
        ]);

        $data = $request->only(['image', 'record_id']);
        $imageUrl = config('constant.content.upload_path');
        $name = $data['record_id'] . '-image';
        $filename = $name . '.' . $data['image']->guessClientExtension();
        $result = $this->lmsContentService->update($data['record_id'], ['image' => $imageUrl . $filename]);

        if ($result) {
            $this->imageService->setDestination($imageUrl);
            $this->imageService->uploadImageFile($name, $data['image']);

            return response()->json([
                'message' => 'Image uploaded successfully',
                'imageUrl' => asset($imageUrl . $filename)
            ], 200);
        }

        return response()->json([
            'message' => 'Image upload failed',
        ]);
    }

    public function importExams(Request $request)
    {
        if ($request->hasFile('file')) {
            $path = $request->file('file')->getRealPath();
            config(['excel.import.startRow' => 1]);
            $data = Excel::selectSheetsByIndex(0)->load($path, function ($reader) {
                $reader->noHeading();
            })->get();
            if (!empty($data) && $data->count()) {
                $list_content_q = DB::table('lmscontents')
                    ->select(['lmsseries_data.lmscontent_id', 'lmscontents.stt'])
                    ->join('lmsseries_data', 'lmsseries_data.lmscontent_id', '=', 'lmscontents.id')
                    ->join('lmsseries', 'lmsseries_data.lmsseries_id', '=', 'lmsseries.id')
                    ->where([
                        ['lmsseries.slug', $request->series_slug],
                        ['lmscontents.parent_id', '<>', 0],
                    ])
                    ->orderBy('stt', 'asc')
                    ->get();
                $content = [];
                $i       = 1;
                foreach ($list_content_q as $r) {
                    $content[$i] = $r->lmscontent_id;
                    $i++;
                }
                if ($content == []) {
                    flash('error', 'record_import_error', 'error');
                    return back();
                }
                $ignoreHeading = 'label';
                DB::beginTransaction();
                try {
                    foreach ($data as $r) {
                        if ($r[2] != $ignoreHeading) {
                            $check = DB::table('lms_exams')->insertGetId([
                                'content_id' => $content[$r[1]],
                                'label'      => $r[2],
                                'dang'       => $r[3],
                                'cau'        => $r[4],
                                'mota'       => $r[5],
                                'luachon1'   => $r[6],
                                'luachon2'   => $r[7],
                                'luachon3'   => $r[8],
                                'luachon4'   => $r[9],
                                'dapan'      => $r[10],
                                'created_by' => Auth::id(),
                            ]);
                        }
                    }
                    DB::commit();
                } catch (Exception $e) {
                    DB::rollBack();
                    dd($e);
                }
            }
        }
        flash('success', 'record_import_successfully', 'success');
        return back();
    }

	//Import Mucluc Khóa học/Khóa luyện thi từ file Excel
    public function importMucLuc(Request $request)
    {
        if ($request->hasFile('file')) {
            $path = $request->file('file')->getRealPath();
            config(['excel.import.startRow' => 2]);
            $data = Excel::selectSheetsByIndex(0)->load($path, function ($reader) {
                $reader->noHeading();
            })->get();

            DB::beginTransaction();
            if (!empty($data) && $data->count()) {
                $series_id = DB::table('lmsseries')
                    ->select(['lmsseries.id'])
                    ->where([
                        ['lmsseries.slug', $request->series_slug],
                        ['lmsseries.delete_status', 0],
                    ])
                    ->get()->first();
                if ($series_id == null) {
                    flash('error', 'record_import_error', 'error');
                    return back();
                }
                DB::table('lmscontents')
                    ->where('lmsseries_id', $series_id->id)
                    ->update([
                        'delete_status' => 1,
                    ]);
                $ignoreHeading = 'Type';
                try {
                    $stt = 1;
                    foreach ($data as $r) {
                        $is_empty_row = collect($r)->every(function ($item) {
                            return $item == null;
                        });
                        if ($is_empty_row) continue;

                        // If you have a new data processing, otherwise continue
                        // r[2] is lesson name
                        // r[4] ís type lession
                        if($r[2] != null && $r[4] !== null)
                        {
                            $type = $r[4];

                            $parent_id  = (isset($parent_id) && $type != '0') ? $parent_id : null;
                            $parent_id  = ($type == '0') ? null : $parent_id;
                            $sub_parent = (isset($sub_parent) && $type != '8') ? $sub_parent : null;
                            $sub_parent = ($type == '0') ? null : $sub_parent;
                            if ($sub_parent == null) {
                                $insert_parent = $parent_id;
                            } else {
                                $insert_parent = $sub_parent;
                            }
                            $check = DB::table('lmscontents')->insertGetId([
                                'lmsseries_id' => $series_id->id,
                                'stt'          => $stt,
                                'maucau'       => (isset($r[5])) ? $r[5] : null,
                                'type'         => $type,
                                'bai'          => $r[0] . $r[1] . $r[2],
                                'title'        => $r[3],
                                'parent_id'    => $insert_parent,
                                'created_by'   => Auth::id(),
                            ]);
                            if ($type == '0') {
                                $parent_id  = $check;
                                $sub_parent = null;
                            }
                            if ($type == '8') {
                                $sub_parent = $check;
                            }
                            $stt++;
                        } else {
                            throw new \Exception();
                        }
                    }

                    DB::commit();
                    flash('Thành công', 'Import mục lục thành công', 'success');
                } catch (Exception $e) {
                    DB::rollBack();
                    flashErrorInstruction('Lỗi', 'Format file excel bị sai!', 'import-menu-contents');
                }
            }
        } else {
            flash('Lỗi', 'Vui lòng nhập file excel để import!', 'error');
        }

        return back();
    }
    public function importMucLuc_old(Request $request)
    {
        if ($request->hasFile('file')) {
            $path = $request->file('file')->getRealPath();
            config(['excel.import.startRow' => 2]);
            $data = Excel::selectSheetsByIndex(0)->load($path, function ($reader) {
                $reader->noHeading();
            })->get();
            if (!empty($data) && $data->count()) {
                $series_id = DB::table('lmsseries')
                    ->select(['lmsseries.id'])
                    ->where([
                        ['lmsseries.slug', $request->series_slug],
                        ['lmsseries.delete_status', 0],
                    ])
                    ->get()->first();
                if ($series_id == null) {
                    flash('error', 'record_import_error', 'error');
                    return back();
                }
                $ignoreHeading = 'Type';
                // dd($data);
                DB::beginTransaction();
                try {
                    $stt = 1;
                    foreach ($data as $r) {
                        $par       = ($r[1] == '0' || $r[1] == null) ? '0' : null;
                        $parent_id = (isset($parent_id) && $par != '0') ? $parent_id : null;
                        $check     = DB::table('lmscontents')->insertGetId([
                            'lmsseries_id' => $series_id->id,
                            'stt'          => $stt,
                            'maucau'       => $r[0],
                            'type'         => $r[1],
                            'bai'          => $r[2],
                            'title'        => $r[3],
                            'parent_id'    => $parent_id,
                            'created_by'   => Auth::id(),
                        ]);
                        if ($par == '0') {
                            $parent_id = $check;
                        }
                        $stt++;
                    }
                    DB::commit();
                } catch (Exception $e) {
                    DB::rollBack();
                    dd($e);
                }
                // dd($data);
            }
        }
        flash('success', 'record_import_successfully', 'success');
        return back();
    }
    public function saveStreamVideo()
    {
        die('!!!');
        $ar = DB::table('lmscontents')
            ->select('id')
            ->where([
                ['delete_status', 0],
                ['lmsseries_id', '43'],
            ])
            ->orderBy('id', 'asc')
            ->get();
        $stt = 1;
        foreach ($ar as $r) {
            DB::table('lmscontents')
                ->where('id', $r->id)
                ->update([
                    'stt' => $stt,
                ]);
            $stt++;
        }
    }
    public function view($series, $slug)
    {
        if (!checkRole(getUserGrade(2))) {
            prepareBlockUserMessage();
            return back();
        }
        $data['series_slug']  = $series;
        $data['content_id']   = $slug;
        $data['back_content'] = PREFIX . "lms/$series/content";
        $data['active_class'] = 'lms';
        $data['title']        = 'LMS' . ' ' . getPhrase('content');
        $data['layout']       = getLayout();
        $check = DB::table('lmscontents')
            ->select('type')
            ->where([
                ['id', $slug],
                ['delete_status', 0],
            ])
            ->get();
        if ($check->isEmpty()) {
            flash('error', 'error info', 'error');
            return back();
        } else {
            if ($check[0]->type == '5') {
                $record = DB::table('lms_test')
                    ->select(['dang', 'cau', 'mota', 'luachon1', 'luachon2', 'luachon3', 'luachon4', 'dapan'])
                    ->where([
                        ['content_id', $slug],
                        ['delete_status', 0],
                    ])
                    ->orderBy('dang')
                    ->get();
            } else {
                $record = DB::table('lms_exams')
                    ->select(['dang', 'cau', 'mota', 'luachon1', 'luachon2', 'luachon3', 'luachon4', 'dapan'])
                    ->where([
                        ['content_id', $slug],
                        ['delete_status', 0],
                    ])
                    ->orderBy('dang')
                    ->get();
            }
        }
        $data['tr'] = [];
        foreach ($record as $r) {
            $data['tr'][] = "<tr>
        <td>" . $r->dang . "</td>
        <td>" . $r->cau . "</td>
        <td>" . $r->mota . "</td>
        <td>" . $r->luachon1 . "</td>
        <td>" . $r->luachon2 . "</td>
        <td>" . $r->luachon3 . "</td>
        <td>" . $r->luachon4 . "</td>
        <td>" . $r->dapan . "</td>
      </tr>";
        }
        $view_name = 'admin.lms.lmscontents.view';
        return view($view_name, $data);
    }
    public function update_try(Request $request)
    {
        try {
            $show_status = DB::table('lmscontents')
                ->select('lmscontents.el_try')
                ->where('id', $request->id)
                ->get()->first();
            $up = ($show_status->el_try == '1') ? '0' : '1';
            DB::table('lmscontents')
                ->where('id', $request->id)
                ->update([
                    'el_try' => $up,
                ]);
            return 'success';
        } catch (Exception $e) {
            return $e;
        }
    }
    public function changeposition(Request $request) {

        if($request) {
           $id_lmscontent =  DB::table('lmsseries')
                                ->where('slug',$request->khoahoc)
                                ->value('id');

            $stt_baihoc_hientai = DB::table('lmscontents')
                                ->where('id',$request->baihoc)
                                ->value('stt');

            $stt_saubaihoc = DB::table('lmscontents')
                                ->where('id',$request->saubaihoc)
                                ->value('stt');




            if( (int)$stt_saubaihoc < (int)$stt_baihoc_hientai) {
                $stt = $stt_saubaihoc + 1;
                LmsContent::where('lmsseries_id', $id_lmscontent)
                    ->where('stt','>', $stt_saubaihoc)
                    ->where('stt','<', $stt_baihoc_hientai)
                    ->where('delete_status', 0)
                    ->increment('stt');
                DB::table('lmscontents')
                    ->where('id', $request->baihoc)
                    ->update([
                        'stt' => $stt,
                    ]);
            } else {
                $stt = $stt_saubaihoc;
                LmsContent::where('lmsseries_id', $id_lmscontent)
                    ->where('stt','>', $stt_baihoc_hientai)
                    ->where('stt','<=', $stt_saubaihoc)
                    ->where('delete_status', 0)
                    ->decrement('stt');
                DB::table('lmscontents')
                    ->where('id', $request->baihoc)
                    ->update([
                        'stt' => $stt,
                    ]);
            }

            $data = array('error'=>1, 'message'=>'Cập nhật thành công');
            return json_encode($data);
        }
        $data = array('error'=> 0, 'message'=>'Có lỗi xẩy ra');
            return json_encode($data);



    }

    /**
     * Handle check is exist file
     * @param $request @{link Request}
     */
    public function checkFile(Request $request)
    {
        if (!checkRole(getUserGrade(2))) {
            prepareBlockUserMessage();
            return back();
        }
        $fileName = $request->fileName;
        $this->setSettings();
        $examSettings = $this->getSettings();
        $destination  = $examSettings->contentPDFpath . $request->series_slug ;
        $data = null;
        if (file_exists($destination . '/' . $fileName)) {
            $data = array('error'=>1, 'message'=>'Đã tồn tại');
        } else {
            $data = array('error'=>0, 'message'=>'Chưa tồn tại');
        }
        return json_encode($data);
    }

}
