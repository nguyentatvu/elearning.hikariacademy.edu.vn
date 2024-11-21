<?php

namespace App\Http\Controllers;

use App\Http\Resources\IntonationResource;
use App\Pronunciation;
use App\PronunciationDetail;
use App\Services\IntonationService;
use App\Services\LmsContentService;
use App\Services\Logger;
use App\Services\PronunciationDetailService;
use App\Services\PronunciationService;
use CURLFile;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\UploadedFile;
use Yajra\DataTables\Facades\DataTables;
use Google\Cloud\TextToSpeech\V1\AudioConfig;
use Google\Cloud\TextToSpeech\V1\AudioEncoding;
use Google\Cloud\TextToSpeech\V1\SsmlVoiceGender;
use Google\Cloud\TextToSpeech\V1\SynthesisInput;
use Google\Cloud\TextToSpeech\V1\TextToSpeechClient;
use Google\Cloud\TextToSpeech\V1\VoiceSelectionParams;

class PronunciationController extends Controller
{
    private $pronunciationService;
    private $pronunciationDetailService;
    private $intonationService;
    private $lmsContentService;

    public function __construct(
        PronunciationService $pronunciationService,
        PronunciationDetailService $pronunciationDetailService,
        IntonationService $intonationService,
        LmsContentService $lmsContentService
    ) {
        $this->middleware(function ($request, $next) {
            if (!checkRole(getUserGrade(2))) {
                prepareBlockUserMessage();
                return redirect('/');
            }
            return $next($request);
        })->except('assess');
        $this->pronunciationService = $pronunciationService;
        $this->pronunciationDetailService = $pronunciationDetailService;
        $this->intonationService = $intonationService;
        $this->lmsContentService = $lmsContentService;
    }

    public function assess(Request $request)
    {
        $request->validate([
            'user_file' => 'file|mimes:mp3,wav|max:2048',
            'sample_file' => 'file|mimes:mp3,wav|max:2048'
        ]);

        $data = $request->only(['user_file', 'sample_file', 'pronunciation_detail_id']);
        $assessmentResult = $this->pronunciationDetailService->assess($data);

        if (!$assessmentResult) {
            return response()->json([
                'message' => 'Có lỗi xảy ra'
            ], 500);
        }

        $userResult = $assessmentResult['user_result'];
        $assessmentResult = $assessmentResult['assessment_results'];
        $result = [
            'user_result' => $userResult['words'],
            'assessment_results' => $assessmentResult
        ];

        return response()->json($result);
    }

    /**
     * This method returns the list of pronunciation assessment
     *
     * @return view
     */
    public function index()
    {
        $data['active_class'] = 'pronunciation-assessment';
        $data['title']        = 'Luyện phát âm';
        $view_name            = 'admin.lms.pronunciation_assessment.list';

        return view($view_name, $data);
    }

    /**
     * This method returns the datatables data to view
     *
     * @return mixed
     */
    public function getDatatable()
    {
        $records = $this->pronunciationService->getAllWithOrderBy('updated_at', 'desc');

        $table = DataTables::of($records)
            ->addColumn('action', function ($records) {
                return '<div class="dropdown more">
                         <a id="dLabel" type="button" class="more-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                             <i class="mdi mdi-dots-vertical"></i>
                         </a>
                         <ul class="dropdown-menu" aria-labelledby="dLabel">
                         <li><a href="' . '/lms/pronunciation-assessment/' . $records->id . '/view"><i class="fa fa-eye"></i>Xem chi tiết</a></li>
                             <li><a href="' . '/lms/pronunciation-assessment/' . $records->id . '/edit"><i class="fa fa-edit"></i>Chỉnh sửa</a></li>
                         <li><a href="javascript:void(0);" onclick="deleteRecord(\'' . $records->id . '\');"><i class="fa fa-trash"></i>' . getPhrase("delete") . '</a></li>
                         </ul>
                     </div>';
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
        $pronunciation = $this->pronunciationService->findById($id);
        $data['active_class'] = 'pronunciation-assessment';
        $data['title']        = $pronunciation->title;
        $data['pronunciation_assessment']    = $pronunciation;
        $data['pronunciationId'] = $id;
        $view_name            = 'admin.lms.pronunciation_assessment.show';

        return view($view_name, $data);
    }

    /**
     * show datatable of a resource
     *
     * @param int $id
     * @return mixed
     */
    public function getPronunciation(int $id)
    {
        $pronunciation = $this->pronunciationService->findById($id);
        $pronunciationId = $pronunciation->id;
        $records = null;

        $records = $this->pronunciationDetailService->getByConditionsWithOrderBy(
            ['pronunciation_id' => $id],
            ['id', 'text', 'audio', 'recognized_text'],
            'id'
        );

        if ($records) {
            $table = DataTables::of($records)
                ->addColumn('action', function ($record) use ($pronunciationId) {
                    return '<div class="dropdown more">
                        <a id="dLabel" type="button" class="more-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="mdi mdi-dots-vertical"></i>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="dLabel">
                       <li><a href="/lms/pronunciation-assessment/' . $pronunciationId . '/detail/' . $record->id . '/edit"><i class="fa fa-pencil"></i>' . getPhrase("edit") . '</a></li>
                       <li><a href="javascript:void(0);" onclick="deleteRecord(\'' . $record->id . '\');"><i class="fa fa-trash"></i>' . getPhrase("delete") . '</a></li>
                        </ul>
                    </div>';
                })
                ->editColumn('audio', function ($record) use ($pronunciationId) {
                    $audioHtml = '<div class="audio-cell">';

                    if (!empty($record->audio)) {
                        $fileExtension = pathinfo($record->audio, PATHINFO_EXTENSION);

                        if (in_array($fileExtension, ['mp3', 'wav'])) {
                            $audioHtml .= '<audio controls><source src="' . asset($record->audio) . '" type="audio/' . $fileExtension . '"></audio>';
                        }
                    }

                    $audioHtml .= '
                        <input type="file" name="audio" class="audio-upload-input" accept="audio/*" style="display: none;" data-id="' . $record->id . '">
                        <button type="button" class="btn btn-primary btn-sm upload-audio" data-id="' . $record->id . '">Upload Audio</button>
                    ';

                    $audioHtml .= '</div>';

                    return $audioHtml;
                })
                ->editColumn('status', function ($record) {
                    return (($record->audio != null && $record->audio != "")) ? '<span class="label label-success">Đã import audio</span> ' : '<span class="label label-warning">Chưa import audio</span>';
                })
                ->editColumn('text', function ($record) {
                    return "<div class=\"td-text\">{$record->text}</div>";
                })
                ->removeColumn('id')
                ->rawColumns(['text', 'recognized_text', 'audio', 'status', 'action']);

            return $table->make(true);
        }

        return null;
    }

    /**
     * This method returns the create view
     * @return [type] [description]
     */
    public function create()
    {
        $data['record']       = false;
        $data['active_class'] = 'pronunciation-assessment';
        $data['title']        = 'Thêm bài luyện phát âm';
        $data['pronunciation_assessment']    = '';
        $view_name            = 'admin.lms.pronunciation_assessment.add-edit';

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
        $file = $request->file('file_import');

        if ($file) {
            $extension = strtolower($file->getClientOriginalExtension());

            if (!in_array($extension, ['xlsx', 'xls'])) {
                return back()->withErrors(['file_import' => 'File phải là 1 file excel']);
            }
        }

        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $data = $request->only('title', 'file_import');

        DB::beginTransaction();
        try {

            $pronunciation = $this->pronunciationService->create([
                'title' => $data['title'],
            ]);

            if (isset($data['file_import'])) {
                $file = $data['file_import'];
                $result = $this->handleDataFromExcel($pronunciation->id, $file);

                if (!$result) {
                    return redirect()
                        ->back()
                        ->withErrors(['error' => 'File không hợp lệ'])
                        ->withInput($request->all());
                }
            }

            DB::commit();
            flash('Thêm bài luyện phát âm thành công', '', 'success');

            return redirect()->route('lms.pronunciation_assessment.index');
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
        $pronunciation = $this->pronunciationService->findById($id);
        $data['record']       = false;
        $data['active_class'] = 'pronunciation-assessment';
        $data['title']        = 'Thêm câu luyện phát âm vào ' . $pronunciation->title;
        $data['pronunciation_assessment']    = $pronunciation;
        $view_name = 'admin.lms.pronunciation_assessment.detail.add-edit';

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
        $request->validate([
            'text' => 'required|string|max:255',
            'audio' => 'file|mimes:mp3,wav|max:10240' // 10MB max
        ]);

        $data = $request->only('pronunciation_id', 'text', 'audio');

        DB::beginTransaction();
        try {
            $pronunciationDetail = $this->pronunciationDetailService->create([
                'pronunciation_id' => $data['pronunciation_id'],
                'text' => $data['text']
            ]);

            if (isset($data['audio'])) {
                $file = $data['audio'];
                $extension = $file->getClientOriginalExtension();
                $filename = sprintf(
                    '%d_%d_%s.%s',
                    $data['pronunciation_id'],
                    $pronunciationDetail->id,
                    date('YmdHis'),
                    $extension
                );

                $path = public_path('uploads/pronunciation');
                $newFilePath = $path . '/' . $filename;

                // CAll Api Speech to text
                $result = $this->pronunciationDetailService->uploadAudioFileToGetIntonations($file, $pronunciationDetail->id);

                if (!$result) {
                    return redirect()
                        ->back()
                        ->withErrors(['error' => 'Hệ thống không nhận diện được file audio, xin vui lòng ghi âm lại hoặc đổi file khác.'])
                        ->withInput($request->all());
                }

                $relativePath = $this->storeAudio($file, $filename, $path);

                $this->pronunciationDetailService->update($pronunciationDetail->id, [
                    'audio' => $relativePath
                ]);
            } else {
                $pronunciationUploadPath = public_path('uploads/pronunciation');
                $resultTTS = $this->storeAudioFileFromTextToSpeech($pronunciationDetail, $pronunciationDetail->pronunciation_id, $pronunciationUploadPath);
            }

            DB::commit();
            flash('success', 'Thêm thành công', 'success');
            return redirect(route('lms.pronunciation_assessment.view', $data['pronunciation_id']));
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Audio upload error: ' . $e->getMessage());

            if ($newFilePath && file_exists($newFilePath)) {
                unlink($newFilePath);
            }

            return redirect()
                ->back()
                ->withErrors(['error' => 'File không hợp lệ'])
                ->withInput($request->all());
        }
    }

    /**
     * Upload audio
     *
     * @param  Request $request
     */
    public function uploadAudio(Request $request, int $pronunciationId, int $detailId)
    {
        DB::beginTransaction();
        try {
            $request->validate([
                'audio' => 'required|file|mimes:mp3,wav|max:2048'
            ]);

            if (!$request->hasFile('audio')) {
                return response()->json([
                    'success' => false,
                    'message' => 'No audio file uploaded'
                ], 400);
            }

            $file = $request->file('audio');
            $extension = $file->getClientOriginalExtension();
            $filename = sprintf(
                '%d_%d_%s.%s',
                $pronunciationId,
                $detailId,
                date('YmdHis'),
                $extension
            );

            $path = public_path('uploads/pronunciation');
            $newFilePath = $path . '/' . $filename;

            $detail = $this->pronunciationDetailService->findById($detailId);

            // Remove old file if has
            if ($detail->audio && file_exists(public_path($detail->audio))) {
                unlink(public_path($detail->audio));
            }

            // CAll Api Speech to text
            $result = $this->pronunciationDetailService->uploadAudioFileToGetIntonations($file, $detailId);

            if (!$result) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to upload audio: ' . $e->getMessage()
                ], 500);
            }

            $relativePath = $this->storeAudio($file, $filename, $path);

            $detail->audio = $relativePath;
            $detail->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Audio uploaded successfully',
            ]);
        } catch (\Exception $e) {
            Log::error('Audio upload error: ' . $e->getMessage());
            DB::rollBack();
            if ($newFilePath && file_exists($newFilePath)) {
                unlink($newFilePath);
            }

            return response()->json([
                'success' => false,
                'message' => 'Failed to upload audio: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * This method loads the edit view
     *
     * @param int $id
     * @return view
     */
    public function edit(int $id)
    {
        $record    = $this->pronunciationService->findById($id);
        $data['record']       = $record;
        $data['active_class'] = 'pronunciation-assessment';
        $data['title']        = 'Chỉnh sửa bài luyện phát âm';
        $view_name = 'admin.lms.pronunciation_assessment.add-edit';

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
        $file = $request->file('file_import');

        if ($file) {
            $extension = strtolower($file->getClientOriginalExtension());

            if (!in_array($extension, ['xlsx', 'xls'])) {
                return back()->withErrors(['file_import' => 'File phải là 1 file excel']);
            }
        }

        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $data = $request->only('title', 'file_import');

        DB::beginTransaction();
        try {
            $pronunciationDetails = $this->pronunciationDetailService->getAllByConditions([
                'pronunciation_id' => $id
            ]);

            foreach ($pronunciationDetails as $pronunciationDetail) {
                $audio = $pronunciationDetail->audio;

                // Remove file if has
                if ($audio && file_exists(public_path($audio))) {
                    unlink(public_path($audio));
                }
            }

            $pronunciation = $this->pronunciationService->findById($id);
            $pronunciation->pronunciationDetails()->delete();

            $upatedResult = $this->pronunciationService->update($id, [
                'title' => $data['title'],
            ]);

            if ($upatedResult && isset($data['file_import'])) {
                $file = $data['file_import'];
                $result = $this->handleDataFromExcel($id, $file);

                if (!$result) {
                    return redirect()
                        ->back()
                        ->withErrors(['error' => 'File không hợp lệ'])
                        ->withInput($request->all());
                }
            }

            DB::commit();
            flash('success', 'Cập nhật bài luyện phát âm thành công', 'success');

            return redirect()->route('lms.pronunciation_assessment.index');
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
        $pronunciation = $this->pronunciationService->findById($id);
        $record = null;

        $record = $this->pronunciationDetailService->getByConditions([
            'id' => $detailId,
            'pronunciation_id' => $id
        ]);

        $data['record']       = $record;
        $data['pronunciation_assessment']  = $pronunciation;
        $data['active_class'] = 'pronunciation-assessment';
        $data['title']        = 'Cập nhật câu luyện phát âm của ' . $pronunciation->title;
        $view_name = 'admin.lms.pronunciation_assessment.detail.add-edit';

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
        $data = $request->only('text', 'audio');
        $pronunciationDetail = $this->pronunciationDetailService->findById($detailId);

        try {
            if (isset($data['audio'])) {
                $file = $data['audio'];
                $extension = $file->getClientOriginalExtension();
                $filename = sprintf(
                    '%d_%d_%s.%s',
                    $id,
                    $detailId,
                    date('YmdHis'),
                    $extension
                );

                $path = public_path('uploads/pronunciation');
                $newFilePath = $path . '/' . $filename;

                // CAll Api Speech to text
                $result = $this->pronunciationDetailService->uploadAudioFileToGetIntonations($file, $detailId);

                if (!$result) {
                    $this->pronunciationDetailService->update($detailId, [
                        'text' => $data['text'],
                        'audio' => null,
                        'katakana_text' => null,
                        'words' => null,
                        'recognized_text' => null
                    ]);

                    return redirect()
                        ->back()
                        ->withErrors(['error' => 'Hệ thống không nhận diện được file audio, xin vui lòng ghi âm lại hoặc đổi file khác.'])
                        ->withInput($request->all());
                } else {
                    $relativePath = $this->storeAudio($file, $filename, $path);

                    // Remove old file if has
                    if ($pronunciationDetail->audio && file_exists(public_path($pronunciationDetail->audio))) {
                        unlink(public_path($pronunciationDetail->audio));
                    }

                    if (isset($relativePath)) {
                        $dataToUpdate['audio'] = $relativePath;
                        $dataToUpdate['text'] = $data['text'];
                    }

                    $this->pronunciationDetailService->update($detailId, $dataToUpdate);
                }
            } else {
                $pronunciationDetail->text = $data['text'];
                $pronunciationDetail->save();

                if (!($pronunciationDetail->audio && file_exists(public_path($pronunciationDetail->audio)))) {
                    $pronunciationUploadPath = public_path('uploads/pronunciation');
                    $resultTTS = $this->storeAudioFileFromTextToSpeech($pronunciationDetail, $id, $pronunciationUploadPath);
                }

                // if (!$resultTTS) {
                //     flash('Lỗi', 'Hệ thống không nhận diện được file audio, xin vui lòng ghi âm lại hoặc đổi file khác.', 'error');
                //     return redirect(route('lms.pronunciation_assessment.view', $id));
                // }
            }
        } catch (Exception $e) {
            Log::error('Audio upload error: ' . $e->getMessage());

            if (isset($newFilePath) && file_exists($newFilePath)) {
                unlink($newFilePath);
            }

            return redirect()
                ->back()
                ->withErrors(['error' => 'File không hợp lệ'])
                ->withInput($request->all());
        }

        flash('success', 'Cập nhật thành công', 'success');

        return redirect(route('lms.pronunciation_assessment.view', $id));
    }

    /**
     * Delete the record
     *
     * @param int $id
     * @return Boolean
     */
    public function delete(int $id)
    {
        DB::beginTransaction();
        try {
            $pronunciationDetails = $this->pronunciationDetailService->getAllByConditions([
                'pronunciation_id' => $id
            ]);

            foreach ($pronunciationDetails as $pronunciationDetail) {
                $audio = $pronunciationDetail->audio;

                // Remove file if has
                if ($audio && file_exists(public_path($audio))) {
                    unlink(public_path($audio));
                }
            }

            $this->pronunciationDetailService->deleteByKeyValueConditions([
                'pronunciation_id' => $id
            ]);
            $this->pronunciationService->delete($id);
            $this->lmsContentService->deleteByKeyValueConditions([
                'pronunciation_id' => $id
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
        $pronunciationDetail = $this->pronunciationDetailService->findById($detailId);
        $audio = $pronunciationDetail->audio;

        try {
            $this->pronunciationDetailService->delete($detailId);

            // Remove file if has
            if ($audio && file_exists(public_path($audio))) {
                unlink(public_path($audio));
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
     * @param int $pronunciationId
     * @param UploadedFile $file
     * @return bool
     */
    protected function handleDataFromExcel(int $pronunciationId, UploadedFile $file)
    {
        $path = $file->getRealPath();
        config(['excel.import.startRow' => 2]);
        $data = Excel::selectSheetsByIndex(0)->load($path, function ($reader) {
            $reader->noHeading();
            $reader->select([0]);
        })->get()->toArray();

        $pronunciationUploadPath = public_path('uploads/pronunciation');

        foreach ($data as $rowData) {
            if (empty($rowData[0])) {
                return true;
            }

            if (count($rowData) != 1) {
                return false;
            }

            $pronunciationDetail = $this->pronunciationDetailService->create([
                'pronunciation_id' => $pronunciationId,
                'text'      => $rowData[0],
            ]);

            $this->storeAudioFileFromTextToSpeech($pronunciationDetail, $pronunciationId, $pronunciationUploadPath);
        }

        return true;
    }

    /**
     * Store audio
     *
     * @param UploadedFile $file
     * @param string $filename
     * @param string $fullPath
     * @return string
     */
    protected function storeAudio(UploadedFile $file, string $filename, string $fullPath)
    {
        if (!file_exists($fullPath)) {
            mkdir($fullPath, 0755, true);
        }

        $file->move($fullPath, $filename);
        $relativePath = 'uploads/pronunciation/' . $filename;

        return $relativePath;
    }

    protected function storeAudioFileFromTextToSpeech(PronunciationDetail $pronunciationDetail, int $pronunciationId, string $pronunciationUploadPath)
    {
        $filename = sprintf(
            '%d_%d_%s.%s',
            $pronunciationId,
            $pronunciationDetail->id,
            date('YmdHis'),
            'mp3'
        );

        $pronunciationFilePath = $pronunciationUploadPath . '/' . $filename;
        $audioFile =  $this->text2Speech($pronunciationDetail->text, $pronunciationFilePath);

        if ($audioFile) {
            // CAll Api Speech to text
            $result = $this->pronunciationDetailService->uploadAudioFileToGetIntonations($audioFile, $pronunciationDetail->id);

            if ($result) {
                $this->pronunciationDetailService->update($pronunciationDetail->id, [
                    'audio' => 'uploads/pronunciation/' . $filename
                ]);

                return true;

            } else {
                // Remove file if has
                if (file_exists($pronunciationFilePath)) {
                    unlink($pronunciationFilePath);
                }

                $this->pronunciationDetailService->update($pronunciationDetail->id, [
                    'audio' => null,
                    'katakana_text' => null,
                    'words' => null,
                    'recognized_text' => null
                ]);

                return false;
            }
        }

        return false;
    }

    //Text to Speech using Google Text to Speech API
    protected function text2Speech(string $text, string $filename)
    {
        try {
            // create client object
            $client = new TextToSpeechClient();
            $input_text = (new SynthesisInput())
                ->setText($text);

            // note: the voice can also be specified by name
            // names of voices can be retrieved with $client->listVoices()
            $voice = (new VoiceSelectionParams())
                ->setLanguageCode('ja-JP')
                ->setSsmlGender(SsmlVoiceGender::NEUTRAL);

            $audioConfig = (new AudioConfig())
                ->setAudioEncoding(AudioEncoding::MP3);

            $response = $client->synthesizeSpeech($input_text, $voice, $audioConfig);
            $audioContent = $response->getAudioContent();

            file_put_contents($filename, $audioContent);
            $client->close();

            $uploadedFile = new UploadedFile(
                $filename,
                basename($filename),
                'audio/mpeg',
                null,
                true
            );

            return $uploadedFile;
        } catch (Exception $e) {
            if (env('APP_ENV') == 'local') {
                Log::error('Text2Speech Error: ' . $e->getMessage());
            } else {
                $log = new Logger(env('APP_LOG_PATH') . '/text2speech.log');
                $log->putLog($e->getMessage());
            }

            return null;
        }
    }
}
