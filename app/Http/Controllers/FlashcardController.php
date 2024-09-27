<?php
namespace App\Http\Controllers;
use App\Flashcard;
use App\FlashcardDetail;
use DB;
use Exception;
use File;
use Google\Cloud\TextToSpeech\V1\AudioConfig;
use Google\Cloud\TextToSpeech\V1\AudioEncoding;
use Google\Cloud\TextToSpeech\V1\SsmlVoiceGender;
use Google\Cloud\TextToSpeech\V1\SynthesisInput;
use Google\Cloud\TextToSpeech\V1\TextToSpeechClient;
use Google\Cloud\TextToSpeech\V1\VoiceSelectionParams;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use \App\Logger;
use Excel;


class FlashcardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Course listing method
     * @return Illuminate\Database\Eloquent\Collection
     */
    /*public function update(Request $request, $slug)
    {


        if ($request->hasFile('file_import')) {
            $path = $request->file('file_import')->getRealPath();
            config(['excel.import.startRow' => 2]);
            $datas = Excel::selectSheetsByIndex(0)->load($path, function ($reader) {
                $reader->noHeading();
            })->get()->toArray();

             // dd($datas);
            foreach($datas as $data){

                DB::table('aaa')->insert([
                    'p_id' =>  $data[0],
                    'cong_ty_tiep_nhan' =>  $data[2],
                    'nganh_nghe' =>  $data[3],
                    'ma_nganh_nghe' =>  $data[4],
                    
                  
                ]);
                
                

            }
        }
        
        flash('success', 'Cập nhật thành công', 'success');
        return redirect('/lms/flashcard/');
    }*/

    public function index()
    {
        if (!checkRole(getUserGrade(2))) {
            prepareBlockUserMessage();
            return back();
        }
        $data['active_class'] = 'flashcard';
        $data['title']        = 'Flashcard';
        $view_name            = 'admin.lms.flashcard.list';
        return view($view_name, $data);
    }
    /**
     * This method returns the datatables data to view
     * @return [type] [description]
     */
	 //List of Flashcards
    public function getDatatable(Request $request)
    {
        if (!checkRole(getUserGrade(2))) {
            prepareBlockUserMessage();
            return back();
        }
        $records = Flashcard::select([
            'id', 'name', 'slug'])
            ->orderBy('updated_at', 'desc');
        $table = DataTables::of($records)
            ->addColumn('action', function ($records) {
                return '<div class="dropdown more">
                        <a id="dLabel" type="button" class="more-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="mdi mdi-dots-vertical"></i>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="dLabel">
                        <li><a href="' . '/lms/flashcard/view/' . $records->id . '"><i class="fa fa-eye"></i>Xem chi tiết</a></li>
                            <li><a href="' . '/lms/flashcard/edit/' . $records->id . '"><i class="fa fa-edit"></i>Chỉnh sửa</a></li>
                        <li><a href="javascript:void(0);" onclick="deleteRecord(\'' . $records->id . '\');"><i class="fa fa-trash"></i>' . getPhrase("delete") . '</a></li>
                        </ul>
                    </div>';
            })
            ->editColumn('subject_title', function ($records) {
                return '<a href="' . URL_QUESTIONBANK_VIEW . $records->slug . '">' . $records->subject_title . '</a>';
            })
            ->removeColumn('id')
            ->removeColumn('slug')
            ->rawColumns(['action', 'subject_title']);
        return $table->make();
    }
    /**
     * Questions listing method
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function show($slug)
    {
        if (!checkRole(getUserGrade(2))) {
            prepareBlockUserMessage();
            return back();
        }
        $flashcard = Flashcard::find($slug);
        $data['active_class'] = 'flashcard';
        $data['title']        = $flashcard->name;
        $data['flashcard']    = $flashcard;
        $view_name            = 'admin.lms.flashcard.show';
        return view($view_name, $data);
    }
    /**
     * This method returns the datatables data to view
     * @return [type] [description]
     */
	 //List of Flashcard Details
    public function getFlashcard($slug)
    {
        if (!checkRole(getUserGrade(2))) {
            prepareBlockUserMessage();
            return back();
        }
        $subject = Flashcard::find($slug);
        $records = FlashcardDetail::select(['id', 'flashcard_id', 'm1tuvung', 'm1vidu', 'm2cachdoc', 'm2amhanviet', 'm2ynghia', 'm2vidu', 'stt', 'mp3'])
            ->where('flashcard_id', $slug)
            ->orderBy('id');
        $table = DataTables::of($records)
            ->addColumn('action', function ($records) {
                return '<div class="dropdown more">
                        <a id="dLabel" type="button" class="more-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="mdi mdi-dots-vertical"></i>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="dLabel">
                       <li><a href="/lms/flashcard-detail/edit/' . $records->id . '"><i class="fa fa-pencil"></i>' . getPhrase("edit") . '</a></li>
                       <li><a href="javascript:void(0);" onclick="deleteRecord(\'' . $records->id . '\');"><i class="fa fa-trash"></i>' . getPhrase("delete") . '</a></li>
                        </ul>
                    </div>';
            })
            ->removeColumn('id')
            ->removeColumn('flashcard_id')
            ->rawColumns(['action']);
        return $table->make();
    }
    /**
     * This method loads the create view
     * @return void
     */
    public function createdetail($slug)
    {
        if (!checkRole(getUserGrade(2))) {
            prepareBlockUserMessage();
            return back();
        }
        $flashcard = Flashcard::find($slug);
        $data['record']       = false;
        $data['active_class'] = 'flashcard';
        $data['title']        = 'Thêm Flashcard';
        $data['flashcard']    = $flashcard;
        $view_name = 'admin.lms.flashcard.detail.add-edit';
        return view($view_name, $data);
    }

	//Edit existing Flashcard detail
    public function editdetail($slug)
    {
        if (!checkRole(getUserGrade(2))) {
            prepareBlockUserMessage();
            return back();
        }
        $record    = FlashcardDetail::find($slug);
        $flashcard = FlashcardDetail::find($slug);
        $data['record']       = $record;
        $data['active_class'] = 'flashcard';
        $data['title']        = 'Cập nhật Flashcard';
        $data['flashcard']    = $flashcard;
        $view_name = 'admin.lms.flashcard.detail.add-edit';
        return view($view_name, $data);
    }

	//Update existing Flashcard detail
    public function updatedetail(Request $request, $slug)
    {
        $record              = FlashcardDetail::find($slug);
        $record->stt         = $request->stt;
        $record->m1tuvung    = $request->m1tuvung;
        $record->m1vidu      = $request->m1vidu;
        $record->m2cachdoc   = $request->m2cachdoc;
        $record->m2amhanviet = $request->m2amhanviet;
        $record->m2ynghia    = $request->m2ynghia;
        $record->m2vidu      = $request->m2vidu;
        $record->save();

		//Upload or create MP3	
        $path_upload = public_path() . '/uploads/flashcard/';
		$file_name = $record->id . '.mp3';

		//Upload own MP3 file
        if ($request->hasFile('mp3'))
        {
            $mp3_fileupload = $request->file('mp3');
            $rules = array( 'mp3' => 'mimes:mp3|max:10000' );
            $this->validate($request, $rules);
            $mp3_fileupload->move($destinationPath, $path_upload.$file_name);
            $record->mp3 = $file_name;
            $record->save();
        }else{  //Use Google Text to Speech
			if ($this->text2Speech($record->m1tuvung, $path_upload.$file_name)){
				$record->mp3 = $file_name;
				$record->save();
			}
		}
        flash('success', 'Cập nhật Flash thành công', 'success');
        return redirect('/lms/flashcard/view/' . $record->flashcard_id);
    }

	//Create new Flashcard detail
    public function storedetail(Request $request)
    {
        
        $record               = new FlashcardDetail();
        $record->flashcard_id = $request->flashcard_id;
        $record->stt		  = $request->stt;
        $record->m1tuvung     = $request->m1tuvung;
        $record->m1vidu       = $request->m1vidu;
        $record->m2cachdoc    = $request->m2cachdoc;
        $record->m2amhanviet  = $request->m2amhanviet;
        $record->m2ynghia     = $request->m2ynghia;
        $record->m2vidu       = $request->m2vidu;
        $record->save();

		//Upload or create MP3	
        $path_upload = public_path() . '/uploads/flashcard/';
		$file_name = $record->id . '.mp3';

		//Upload own MP3 file
        if ($request->hasFile('mp3'))
        {
            $mp3_fileupload = $request->file('mp3');
            $rules = array( 'mp3' => 'mimes:mp3|max:10000' );
            $this->validate($request, $rules);
            $mp3_fileupload->move($destinationPath, $path_upload.$file_name);
            $record->mp3 = $file_name;
            $record->save();
        }else{  //Use Google Text to Speech
			if ($this->text2Speech($record->m1tuvung, $path_upload.$file_name)){
				$record->mp3 = $file_name;
				$record->save();
			}
		}

        flash('success', 'Thêm Flashcard thành công', 'success');
        return redirect('/lms/flashcard/view/' . $request->flashcard_id);
    }


    public function create()
    {
        if (!checkRole(getUserGrade(2))) {
            prepareBlockUserMessage();
            return back();
        }
        $data['record']       = false;
        $data['active_class'] = 'flashcard';
        $data['title']        = 'Thêm Flashcard';
        $data['flashcard']    = '';
        $view_name            = 'admin.lms.flashcard.add-edit';
        return view($view_name, $data);
    }


    public function edit($slug)
    {
        if (!checkRole(getUserGrade(2))) {
            prepareBlockUserMessage();
            return back();
        }
        $record    = Flashcard::find($slug);
        $data['record']       = $record;
        $data['active_class'] = 'flashcard';
        $data['title']        = 'Chỉnh sửa Flashcard';
        $view_name = 'admin.lms.flashcard.add-edit';
        return view($view_name, $data);
    }

	//Update from Excel
    public function update(Request $request, $slug)
    {
        if (!checkRole(getUserGrade(2))) {
            prepareBlockUserMessage();
            return back();
        }

        $record              = Flashcard::find($slug);
        $record->name    = $request->name;
        $record->save();

        if ($request->hasFile('file_import')) {
            $path = $request->file('file_import')->getRealPath();
            config(['excel.import.startRow' => 2]);
            $datas = Excel::selectSheetsByIndex(0)->load($path, function ($reader) {
                $reader->noHeading();
            })->get()->toArray();
			$path_upload = public_path() . '/uploads/flashcard/';
            
			foreach($datas as $data){
                //$nameTrans = time().'.mp3';
                $record = new FlashcardDetail();
				
                $record->flashcard_id   = $slug;
                $record->stt            = $data[0];
                $record->m1tuvung       = $data[1];
                $record->m1vidu         = $data[2];
                $record->m2cachdoc      = $data[3];
                $record->m2amhanviet    = $data[4];
                $record->m2ynghia       = $data[5];
                $record->m2vidu         = $data[6];
				$record->save();
				
				$file_name = $record->id . '.mp3';
				if ($this->text2Speech($record->m1tuvung, $path_upload.$file_name)){
					$record->mp3            = $file_name;
					$record->save();
				}
                
            }
        }
        
        flash('success', 'Cập nhật Flashcard từ file Excel thành công', 'success');
        return redirect('/lms/flashcard/');
    }

	//Import Flashcard from Excel
    public function store(Request $request)
    {
        if (!checkRole(getUserGrade(2))) {
            prepareBlockUserMessage();
            return back();
        }
        $record_f = new Flashcard();
        $record_f->name = $request->name;
        $record_f->save();

        if ($request->hasFile('file_import')) {
            $path = $request->file('file_import')->getRealPath();
            config(['excel.import.startRow' => 2]);
            $datas = Excel::selectSheetsByIndex(0)->load($path, function ($reader) {
                $reader->noHeading();
            })->get()->toArray();
            // dd($datas);
			$path_upload = public_path() . '/uploads/flashcard/';
            
			foreach($datas as $data){
                //$nameTrans = time().'.mp3';
                $record = new FlashcardDetail();
                $record->flashcard_id   = $record_f->id;
                $record->stt            = $data[0];
                $record->m1tuvung       = $data[1];
                $record->m1vidu         = $data[2];
                $record->m2cachdoc      = $data[3];
                $record->m2amhanviet    = $data[4];
                $record->m2ynghia       = $data[5];
                $record->m2vidu         = $data[6];
                $record->save();

				$file_name = $record->id . '.mp3';
				if ($this->text2Speech($record->m1tuvung, $path_upload.$file_name)){
					$record->mp3            = $file_name;
					$record->save();
				}
            }
        }
        flash('Thêm Flashcard từ file Excel thành công', '', 'success');
        return redirect('/lms/flashcard');
    }
    /**
     * Delete Record based on the provided slug
     * @param  [string] $slug [unique slug]
     * @return Boolean
     */
	 //Delete Flashcard Detail
    public function deleteDetail($slug)
    {

        if (!checkRole(getUserGrade(2))) {
            prepareBlockUserMessage();
            return back();
        }
        $record = FlashcardDetail::find($slug);
        try {
			//Delete related MP3 file
			$path_upload = public_path() . '/uploads/flashcard/';
			unlink($path_upload.$record->mp3);   
			
			$record->delete();
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

	//Delete Flashcard
    public function delete($slug)
    {

        if (!checkRole(getUserGrade(2))) {
            prepareBlockUserMessage();
            return back();
        }
        $record = Flashcard::find($slug);
        try {
            $record->delete();
            $response['status']  = 1;
            $response['message'] = 'Xóa thành công';

			//Also delete all related MP3 files
			$records = FlashcardDetail::where('flashcard_id', $slug)->get();
			$path_upload = public_path() . '/uploads/flashcard/';
			
			foreach($records as $data){
				unlink($path_upload.$data->mp3);
			}

			//Also delete all related Flashcard Details
			FlashcardDetail::where('flashcard_id', $slug)->delete();

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

    public function isValidRecord($record)
    {
        if ($record === null) {
            flash('Ooops...!', getPhrase("page_not_found"), 'error');
            return $this->getRedirectUrl();
        }
        return false;
    }

    public function processUpload(Request $request, $record, $file_name){


        if ($request->hasFile($file_name)) {

            $examSettings = getSettings('lms');

            $imageObject = new ImageSettings();

            $destinationPath            = $examSettings->seriesImagepath;

            $destinationPathThumb       = $examSettings->seriesThumbImagepath;

            $fileName = $record->id.'-'.$file_name.'.'.$request->$file_name->guessClientExtension();

            $request->file($file_name)->move($destinationPath, $fileName);

         //Save Normal Image with 300x300

            Image::make($destinationPath.$fileName)->fit($examSettings->imageSize)->save($destinationPath.$fileName);

            Image::make($destinationPath.$fileName)->fit($imageObject->getThumbnailSize())->save($destinationPathThumb.$fileName);

            return $fileName;

        }

    }

	//Text to Speech using Google Text to Speech API
	private function text2Speech($text, $filename){
		try 
		{
			// create client object
			$client = new TextToSpeechClient();
			$input_text = (new SynthesisInput())
				->setText($text);

			// note: the voice can also be specified by name
			// names of voices can be retrieved with $client->listVoices()
			$voice = (new VoiceSelectionParams())
				->setLanguageCode('ja-JP')
				->setSsmlGender(SsmlVoiceGender::FEMALE);

			$audioConfig = (new AudioConfig())
				->setAudioEncoding(AudioEncoding::MP3);

			$response = $client->synthesizeSpeech($input_text, $voice, $audioConfig);
			$audioContent = $response->getAudioContent();

			file_put_contents($filename, $audioContent);
			$client->close();

			return true;

		} 
		catch(Exception $e) {
			$log = new Logger(env('APP_LOG_PATH').'/text2speech.log');
			$log->putLog($e->getMessage());
			return false;
		}
	}
}
