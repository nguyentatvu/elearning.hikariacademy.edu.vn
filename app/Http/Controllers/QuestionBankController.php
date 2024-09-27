<?php
namespace App\Http\Controllers;
use \App;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Subject;
use App\QuestionBank;
// use App\QuestionBankTable;
use Yajra\DataTables\DataTables;
use DB;
use Image;
use ImageSettings;
use File;
use Illuminate\Support\Facades\Validator;
use Input;
use Excel;
use Exception;
class QuestionBankController extends Controller
{
 public $excel_data = array();
  public $columns = '';
     public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Course listing method
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function index()
    {
        /*$select = QuestionBank::select(['id','answers', 'total_answers'])->where('id','>',6902)->get()->toArray();
        $update = array();
        foreach ($select as $key => $value) {
            $answer = json_decode($value['answers']);
            foreach ($answer as $key_1 => $value_1) {
                // echo
            }
            $update = QuestionBank::find($value['id']);
            $update->answers_1 = $answer[0]->option_value;
            $update->answers_2 = $answer[1]->option_value;
            $update->answers_3 = $answer[2]->option_value;
            if ($value['total_answers'] == 4) {
                $update->answers_4 = $answer[3]->option_value;    
            }
            $update->save();
        }
       echo 'xong'; exit;*/
      if(!checkRole(getUserGrade(8)))
      {
        prepareBlockUserMessage();
        return back();
      }
        $data['active_class']       = 'exams';
        $data['title']              = 'Ngân hàng câu hỏi';
        //$data['title']              = getPhrase('question_subjects');
        // return view('exams.questionbank.list', $data);
         $view_name = 'admin.exams.questionbank.list';
        return view($view_name, $data);
    }
    /**
     * This method returns the datatables data to view
     * @return [type] [description]
     */
    public function getDatatable(Request $request)
    {
      if(!checkRole(getUserGrade(8)))
      {
        prepareBlockUserMessage();
        return back();
      }
         $records = Subject::select([ 
            'subject_title', 'subject_code', 'id','slug', 'is_lab', 'updated_at'])
              ->orderBy('updated_at', 'desc');
        $table = DataTables::of($records)
        ->addColumn('action', function ($records) {
            return '<div class="dropdown more">
                        <a id="dLabel" type="button" class="more-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="mdi mdi-dots-vertical"></i>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="dLabel">
                        <li><a href="'.URL_QUESTIONBANK_VIEW.$records->slug.'"><i class="fa fa-eye"></i>Xem câu hỏi</a></li>
                            <li><a href="'.URL_QUESTIONBANK_ADD_QUESTION.$records->slug.'"><i class="fa fa-plus-circle"></i>Thêm câu hỏi</a></li>
                        </ul>
                    </div>';
            })
        ->editColumn('subject_title', function($records) {
            return '<a href="'.URL_QUESTIONBANK_VIEW.$records->slug.'">'.$records->subject_title.'</a></li>';
        })
        ->editColumn('subject_code', function($records) {
            return change_furigana_admin($records->subject_code);
        })
        ->rawColumns(['action', 'subject_title', 'subject_code'])
        ->removeColumn('id')
        ->removeColumn('slug')
        ->removeColumn('is_lab')
        ->removeColumn('updated_at');
        return $table->make();
    }
      /**
     * Questions listing method
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function show($slug)
    {
      if(!checkRole(getUserGrade(8)))
      {
        prepareBlockUserMessage();
        return back();
      }
        $subject = Subject::getRecordWithSlug($slug);
        if($isValid = $this->isValidRecord($subject))
            return redirect($isValid);
        $data['active_class'] = 'exams';
        $data['title']        = $subject->subject_title;
        $data['subject']            = $subject;
        // return view('exams.questionbank.questions', $data);
         $view_name = 'admin.exams.questionbank.questions';
        return view($view_name, $data);
    }
    /**
     * This method returns the datatables data to view
     * @return [type] [description]
     */
    public function getQuestions($slug)
    {
      if(!checkRole(getUserGrade(8)))
      {
        prepareBlockUserMessage();
        return back();
      }
        $subject = Subject::getRecordWithSlug($slug);
        $isValid = $this->isValidRecord($subject);
        if($isValid)
            return redirect($isValid);
        $records = QuestionBank::join(
            'subjects', 'questionbank.subject_id', '=', 'subjects.id')
            ->join('topics', 'questionbank.topic_id', '=', 'topics.id')
            ->select(['questionbank.id', 'topics.topic_name', 'questionbank.question', 'questionbank.explanation','questionbank.slug', 'questionbank.answers', 'questionbank.answers_1','questionbank.answers_2','questionbank.answers_3','questionbank.answers_4', 'questionbank.correct_answers', 'questionbank.updated_at'])
            ->where('questionbank.subject_id','=', $subject->id)
            ->orderBy('questionbank.topic_id','desc');
        $table = DataTables::of($records)->removeColumn('slug')
            ->addColumn('action', function ($records) {
            $question = $records->question;
            return '<div class="dropdown more">
                        <a id="dLabel" type="button" class="more-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="mdi mdi-dots-vertical"></i>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="dLabel">
                       <li><a href="'.URL_QUESTIONBANK_EDIT_QUESTION.$records->slug.'"><i class="fa fa-pencil"></i>'.getPhrase("edit").'</a></li>
                       <li><a href="javascript:void(0);" onclick="deleteRecord(\''.$records->slug.'\');"><i class="fa fa-trash"></i>'. getPhrase("delete").'</a></li>
                        </ul>
                    </div>';
            })
        ->removeColumn('id')
        ->removeColumn('updated_at')
        ->removeColumn('answers')
        ->editColumn('question_type', function($results){
            return ucfirst($results->question_type);
         })
        ->editColumn('question', function($results){
            $results->question = preg_replace("/<img[^>]+\>/i", " ", $results->question);
            $results->question = change_furigana_admin($results->question);
            return $results->question;
        })
        ->editColumn('explanation', function($results){
            $results->explanation = preg_replace("/<img[^>]+\>/i", " ", $results->explanation);
            $results->explanation = change_furigana_admin($results->explanation);
            return $results->explanation;
        })
        ->editColumn('answers_1', function($results){
            //$answers = change_furigana_admin($results->answers_1);
          $answers = json_decode($results->answers);
          $answers1= change_furigana_admin($answers[0]->option_value);
          // $answers = $results->answers_1;
            return $answers1;
        })
        ->editColumn('answers_2', function($results){
            // $answers = change_furigana_admin($results->answers_2);
            $answers = json_decode($results->answers);
            $answers2= change_furigana_admin($answers[1]->option_value);
            return $answers2;
        })
        ->editColumn('answers_3', function($results){
            // $answers = change_furigana_admin($results->answers_3);
            $answers = json_decode($results->answers);
            $answers3= change_furigana_admin($answers[2]->option_value);
            return $answers3;
        })
        ->editColumn('answers_4', function($results){
            // $answers = change_furigana_admin($results->answers_4);
            // return $answers;
            $answers = json_decode($results->answers);
            $answers4= change_furigana_admin($answers[3]->option_value);
            return $answers4;
        })
        ->rawColumns(['action','question','explanation','answers_1','answers_2','answers_3','answers_4']);
       /* ->editColumn('answers',function($results){
            $answer_question = json_decode( $results->answers );
            $i = 1;
            $answers = "";
            foreach ($answer_question as $key => $value) {
               // $option = htmlentities( change_furigana($value->option_value, 'return')); 
               $option = $value->option_value; 
               $answers .= $i .'.' . change_furigana_admin($option) . '<br>';
              // $answers .= "";
              $i++;
            }
            // return $results->answers;
            return $answers;
        });*/
        return $table->make();
    }
    /**
     * This method loads the create view
     * @return void
     */
    public function create($slug)
    {
      if(!checkRole(getUserGrade(9)))
      {
        prepareBlockUserMessage();
        return back();
      }
        $subject = Subject::getRecordWithSlug($slug);
        if($isValid = $this->isValidRecord($subject))
            return redirect($isValid);
         // $topics = $subject->topics()->where('parent_id','=','0')->get();
        $topics = $subject->topics()->get();
        // echo "<pre>";
        // print_r ($topics);
        // echo "</pre>"; exit;
        if(!$topics->count()) {
        /**
         * If no topics available in selected subject, 
         * redirect back with message to update topics
         */
            $message = 
            $subject->subject_title.'  không có, vui lòng thêm topic để nhập câu hỏi';
            flash($message, $message, 'overlay');   
            return back();
        }
        // $settings['total_answers'] = 0;
        // $settings['question_type'] = '';
        $data['topics']             = array_pluck($topics, 'topic_name', 'id');
        $data['record']             = FALSE;
        $data['active_class']     = 'exams';
        $data['title']            = 'Thêm câu hỏi';
        $data['subject']                = $subject;
        // $data['settings']            = json_encode($settings);
        // return view('exams.questionbank.add-edit', $data);
         $view_name = 'admin.exams.questionbank.add-edit';
        return view($view_name, $data);
    }
    /**
     * This method loads the edit view based on unique slug provided by user
     * @param  [string] $slug [unique slug of the record]
     * @return [view with record]       
     */
    public function edit($slug)
    {
       if(!checkRole(getUserGrade(9)))
      {
        prepareBlockUserMessage();
        return back();
      }
        $record = QuestionBank::getRecordWithSlug($slug);
        if($isValid = $this->isValidRecord($record))
            return redirect($isValid);
        $subject = $record->subject()->first();
        //$topics = $subject->topics()->where('parent_id','=','0')->get();
        $topics = $subject->topics()->get();
        if(!$topics->count()) {
        /**
         * If no topics available in selected subject, 
         * redirect back with message to update topics
         */
            $message = $subject->subject_title.'  chưa cho chủ đề';
            flash('Thêm chủ đề', $message, 'overlay');    
            return back();
        }
        // dd($record);
        $settings['total_answers']         = $record->total_answers;
        $settings['total_correct_answers'] = $record->total_correct_answers;
        $settings['correct_answers']       = json_decode($record->correct_answers);
        $settings['question_type']         = $record->question_type;
        $settings['question_show_type']    = $record->question_show_type;
        $settings['answers']                 = json_decode($record->answers);
        // dd($settings);
        $data['topics']             = array_pluck($topics, 'topic_name', 'id');
        $data['record']             = $record;
        $data['active_class']     = 'master_settings';
        $data['title']            = 'Chỉnh sửa câu hỏi';
        $data['subject']                = $subject;
      $settings                 = (object)$settings;
        $data['settings']               = json_encode($settings);
      $view_name = 'admin.exams.questionbank.add-edit';
      return view($view_name, $data);
    }
    /**
     * Update record based on slug and reuqest
     * @param  Request $request [Request Object]
     * @param  [type]  $slug    [Unique Slug]
     * @return void
     */
    public function update(Request $request, $slug)
    {
        if(!checkRole(getUserGrade(9)))
      {
        prepareBlockUserMessage();
        return back();
      }
        $record                 = QuestionBank::where('slug', $slug)->get()->first();
        $rules['topic_id']         = 'bail|required|integer';
        $rules['question']          = 'bail|required';
        $rules['marks']             = 'bail|required|integer';
         DB::beginTransaction();
      try{
        /**
         * As we are disableing the question type in edit, 
         * we need to get the type of the question for existing record
         * Assign the question type to a varable $current_question_type
         */
        $current_question_type = $record->question_type;
       // if($current_question_type == 'radio') {
       //      $rules['total_answers']     = 'bail|required|integer';
       //      $rules = $this->validateRadioQuestions($request, $rules);
       //  }
       //  if($current_question_type == 'checkbox') {
       //      $rules['total_answers']     = 'bail|required|integer';
       //      $rules = $this->validateCheckboxQuestions($request, $rules);
       //  }
       //   if($current_question_type == 'match') {
       //      $rules['total_answers']     = 'bail|required|integer';
       //      $rules = $this->validateMatchQuestions($request, $rules);
       //  }
        /**
         * As it is fill in the blanks type of question
         * there are no no. of options fields, it only contains the answers
         * so ignore the validation for total_answers
         */
        // if($current_question_type == 'blanks') {
        //     $rules = $this->validateBlankQuestions($request, $rules);
        // }
        $this->validate($request, $rules);
        $name                     = $request->question;
       /**
        * As we are maintaining unique slug for each question, 
        * if the question is changed no need worry, 
        * we can continue with the existing old slug
        */
        $request->question_type = $record->question_type;
        $record->question               = $name;
        $record->subject_id             = $request->subject_id;
        $record->topic_id               = $request->topic_id;
        $record->question               = $request->question;
        $record->book                   = $request->book;
        $record->page                   = $request->page;
        $record->cau                   = $request->cau;
        $record->difficulty_level       = $request->difficulty_level;
        $record->hint                   = $request->hint;
        $record->explanation            = $request->explanation;
        $record->question_show_type     = $request->question_show_type;
        $record->marks                  = $request->marks;
        $record->time_to_spend          = $request->time_to_spend;
        $record->question_file          = $request->question_file;
        // if($request->has('question_l2'))
        // $record->question_l2            = $request->question_l2;
        // if($request->has('explanation_l2'))
        // $record->explanation_l2         = $request->explanation_l2;
         if($current_question_type == 'radio'){
             $record->total_answers          = $request->total_answers;
            $record->correct_answers        = $request->correct_answers;
            $record->total_correct_answers  = $request->total_correct_answers;
         }
        // Save data with no images
        $record->save();
        /*$update_question_table = QuestionBankTable::where('slug', '=', $slug)
            ->update([
                'question' => $request->question,
                'book' => $request->book, 
                'page'=>$request->page,
                'cau'=>$request->cau,
                'explanation' => $request->explanation,
                'question_file'=>$request->question_file,
                'question_show_type'=>$request->question_show_type,
                'total_correct_answers'=>$request->total_correct_answers
            ]);*/
        if($current_question_type == 'match'){
            $record->answers     = $this->prepareMatchQuestionOptions($request, $record);
        }
        else if($current_question_type == 'para' || $current_question_type == 'video'|| $current_question_type == 'audio' )
            $record->answers     = $this->prepareParaQuestionOptions($request, $record);   
        else
          $record->answers              = $this->prepareOptions($request, $record);
        $record->save();
        flash('Sửa câu hỏi thành công','', 'success');
         DB::commit();
      }
     catch(Exception $e)
     {
      DB::rollBack();
       if(getSetting('show_foreign_key_constraint','module'))
       {
          flash('oops...!',$e->errorInfo, 'error');
       }
       else {
          flash('oops...!','improper_data_in_the_question', 'error');
       }
     }
       return redirect(URL_QUIZ_QUESTIONBANK);
    }
    /**
     * This method adds record to DB with the following steps
     * 1 Validate Request
     * 2 Save Data and get ID of that record
     * 3 Process question image and upload if exists
     * 4 Process Option images and upload if exists
     * 5 Update the same record as files got uploaded uploaded
     * @param  Request $request [Request Object]
     * @return void
     */
    public function store(Request $request)
    {
     // dd($request);
        /**
         * Validation for the Master Data of a question
         */
        if(!checkRole(getUserGrade(9)))
      {
        prepareBlockUserMessage();
        return back();
      }
      DB::beginTransaction();
      try{
        $rules['topic_id']          = 'bail|required|integer';
        $rules['question']          = 'bail|required';
        $rules['marks']             = 'bail|required|integer';
        $rules['question_type']     = 'bail|required';
        if($request->question_type == 'radio') {
            $rules['total_answers']     = 'bail|required|integer|min:1';
            $rules = $this->validateRadioQuestions($request, $rules);
        }
        if($request->question_type == 'checkbox') {
            $rules['total_answers']     = 'bail|required|integer|min:1';
            $rules = $this->validateCheckboxQuestions($request, $rules);
        }
        if($request->question_type == 'match') {
            $rules['total_answers']     = 'bail|required|integer|min:1';
            $rules = $this->validateMatchQuestions($request, $rules);
        }
       if($request->question_type == 'para') {
            $rules['total_answers']     = 'bail|required|integer|min:1';
            $rules['total_para_options']     = 'bail|required|integer|min:1';
        }
        if($request->question_type == 'video' || 
          $request->question_type == 'audio') {
            $rules['total_answers']     = 'bail|required|integer|min:1';
            $rules['total_para_options'] = 'bail|required|integer|min:1';
        }
        /**
         * As it is fill in the blanks type of question
         * there are no no. of options fields, it only contains the answers
         * so ignore the validation for total_answers
         */
        if($request->question_type == 'blanks') {
            $rules = $this->validateBlankQuestions($request, $rules);
             $rules['total_correct_answers']     = 'bail|required|integer|min:1';
        }
        $this->validate($request, $rules);
        $record = new QuestionBank();
        $name                           = $request->question;
        $record->question           = $name;
        $record->slug               = createSlug(QuestionBank::class, getHashCode());
        $record->subject_id         = $request->subject_id;
        $record->topic_id           = $request->topic_id;
        $record->question           = $request->question;
        $record->book               = $request->book;
        $record->page               = $request->page;
        $record->cau                = $request->cau;
        $record->difficulty_level   = $request->difficulty_level;
        $record->hint               = $request->hint;
        $record->explanation        = $request->explanation;
        $record->marks              = $request->marks;
        $record->question_show_type = $request->question_show_type;
        $record->question_type      = $request->question_type;
        $record->time_to_spend      = $request->time_to_spend;
        if($request->has('question_l2'))
        $record->question_l2        = $request->question_l2;
        if($request->has('explanation_l2'))
        $record->explanation_l2     = $request->explanation_l2;
        $record->question_file      = $request->question_file;
        /**
         * Prepare answers data based on the type of question
         */
        if($request->question_type == 'radio'){
            $record->total_answers          = $request->total_answers;
            $record->correct_answers            = $request->correct_answers;
            $record->total_correct_answers  = $request->total_correct_answers;
        }
        /**
         * As it is descriptive question, there will be 
         * no total_answers, total_correct_answers and correct_answers
         * and 
         *
         */
        if($request->question_type == 'descriptive') {
            $record->total_answers           = 0;
            $record->total_correct_answers   = 0;
            $record->correct_answers         = '';
        }
            // Save data with no images
            $record->save();
        // Update data with images
        $record->question_file          = $record->question_file;
        if($request->question_type == 'match'){
            $record->answers     = $this->prepareMatchQuestionOptions($request, $record);
        }
        else if($request->question_type == 'para' || $request->question_type == 'video' || $request->question_type == 'audio'){
           $record->answers     = $this->prepareParaQuestionOptions($request, $record);   
        }     
        else{
           $record->answers                 = $this->prepareOptions($request, $record);
        }
        $record->save();
         flash('Thêm câu hỏi thành công','', 'success');
         DB::commit();
      }
     catch(Exception $e)
     {
       dd($e->getMessage());
      DB::rollBack();
       if(getSetting('show_foreign_key_constraint','module'))
       {
          flash('oops...!',$e->errorInfo, 'error');
       }
       else {
          flash('oops...!','improper_data_in_the_question', 'error');
       }
     }
         return redirect(URL_QUIZ_QUESTIONBANK);
    }
    /**
     * This method prepares the json data to be inserted in place of options
     * by processing the image information and other attributes
     * @param  [type] $request [request sent by the user]
     * @param  [type] $record  [the record which was saved to DB]
     * @return [type]          [description]
     */
    public function prepareOptions($request, $record)
    {
        $options    = $request->options;
      $optionsl2  = $request->optionsl2;
        $list       = array();
        /**
         * Get the image path from ImageSettings class    
         * This destinationPath variable will be used 
         * to delete an image if user edits any question and changes an image
         */
         $imageObject = new App\ImageSettings();
         $destinationPath      = $imageObject->getExamImagePath();
         /**
          * Loop the total options selected by user 
          * and process each option by checking wether the image
          * has been uploaded or not
          * After this loop multiple objects will be created based on 
          * the no. of options(total_answers) selected by user
          * Each object contains 3 properties
          * 1) option_value : stores the text submitted as option
          * 2) has_file     : stores if this particular option has any file
          * 3) file_name    : stores the name of file uploaded   
          */
        for($index = 0; $index < $request->total_answers; $index++)
        {
            /**
             * The $answers variable is used when user edit any question
             * It will contain the previous option values
             * As it is under for loop, every option property will be checked
             * by comparing wether the file is submitted for this particular object
             * If submitted it will delete the old file and overwrite with new file 
             * @var [type]
             */
            $answers = json_decode($record->answers);
            $old_has_file = isset($answers[$index]->has_file) ? $answers[$index]->has_file : 0;
            $old_file_name = isset($answers[$index]->file_name) ? $answers[$index]->file_name : '';
         $spl_char   = ['\t','\n','\b','\c','\r','\'','\\','\$','\"',"'"];
        $list[$index]['option_value']   = str_replace($spl_char,'',$options[$index]);
        $list[$index]['optionl2_value']   = str_replace($spl_char,'',$optionsl2[$index]);
            // $list[$index]['option_value']    = $options[$index];
            $list[$index]['has_file']       = $old_has_file;
            $list[$index]['file_name']      = $old_file_name;
            $file_name                      = 'upload_'.$index;
            if ($request->hasFile($file_name))
            {
                $rules = array($file_name => 'mimes:jpeg,jpg,png,gif|max:10000');
                $validator = Validator::make($request->options, $rules);
                if($validator->fails())
                    return '';
                  //Delete Old Files
                if($old_file_name)
                    $this->deleteExamFile($old_file_name, $destinationPath);
                // This option has the image to be uploaded, 
                // so process image and update the fields
                $list[$index]['has_file']       = 1;
                $list[$index]['file_name']      = $this
                                                ->processUpload($request, $record,$file_name, 'option');
            }
        }
        return json_encode($list);
    }
    /**
     * The Multi Answers will be prepared in this method
     * The format will be multiple objects with property answer
     * Need to reference this as $CorrectAnswers->answer in foreach loop
     * @param  [type] $request [description]
     * @return [type] json     [description]
     */
    public function prepareMultiAnswers($request)
    {   
        $correct_answers = $request->correct_answers;
        $list = array();
        for($index = 0; $index < $request->total_correct_answers; $index++)
        {
            $list[$index]['answer']   = $correct_answers[$index];
        }
        return json_encode($list);
    }
    /**
     * In this method, the options are divided to multi dimentional array
     * Each object will have left and right as root
     * Each will have the title and options as properties
     * @param  [type] $request [description]
     * @param  [type] $record  [description]
     * @return [type]          [description]
     */
    public function prepareMatchQuestionOptions($request, $record)
    {
        $options_left   = $request->options_left;
        $options_right  = $request->options_right;
        $optionsl2_left   = $request->optionsl2_left;
        $optionsl2_right  = $request->optionsl2_right;
        $list = array();
        $list['left']['title']       = $request->title_left;
        $list['right']['title']      = $request->title_right;
        $list['left']['titlel2']     = $request->title_left_l2;
        $list['right']['titlel2']    = $request->title_right_l2;
        $list['left']['options']     = array();
        $list['right']['options']    = array();
        $list['left']['optionsl2']   = array();
        $list['right']['optionsl2']  = array();
        for($index = 0; $index < $request->total_answers; $index++)
        {
            $list['left']['options'][$index]     = $options_left[$index];
            $list['right']['options'][$index]    = $options_right[$index];
            $list['left']['optionsl2'][$index]   = $optionsl2_left[$index];
            $list['right']['optionsl2'][$index]  = $optionsl2_right[$index];
        }
        return json_encode($list);
    }
    /**
     * This method will prepare the list of answers provided with
     * match the following questions
     * These answers will directly point to the options object in the above method
     * @param  [type] $request [description]
     * @return [type]          [description]
     */
    public function prepareMatchAnswers($request)
    {
        $correct_answers = $request->correct_answers;
        $list = array();
        for($index = 0; $index < $request->total_answers; $index++)
        {
            $list[$index]['answer']   = $correct_answers[$index];
        }
        return json_encode($list);
    }
     /**
     * In this method, the options are divided to multi dimentional array
     * Each object will have index number as the question block
     * Each index will have the question, total_options, 
     * array of options associated with that
     * Each will have the title and options as properties
     * @param  [type] $request [description]
     * @param  [type] $record  [description]
     * @return [type]          [description]
     */
    public function prepareParaQuestionOptions($request, $record)
    {  
        $total_options  = $request->total_para_options; 
        $questions      = $request->questions_list; 
        // $questionsl2    = $request->questions_listl2; 
        $list = array();
        for($index = 0; $index < $request->total_answers; $index++)
        {
            $options      = $request->options[$index];
            // $optionsl2    = $request->optionsl2[$index];
            $list_options = array();
            $list_optionsl2 = array();
            $list[$index]['question']       = $questions[$index];
            // $list[$index]['questionl2']     = $questionsl2[$index];
            $list[$index]['total_options']  = $total_options;
            for($option_number = 0; $option_number < $total_options; $option_number++){
                $list_options[$index][$option_number] = $options[$option_number];
                // $list_optionsl2[$index][$option_number] = $optionsl2[$option_number];
            }
            $list[$index]['options'] = $list_options;
            // $list[$index]['optionsl2'] = $list_optionsl2;
        }
        // dd($list);
        return json_encode($list);
    }
    /**
     * This method will prepare the list of answers provided with
     * Paragraph or video type of questions
     * These answers will directly associated with the question number order
     * @param  [type] $request [description]
     * @return [type]          [description]
     */
    public function prepareParaAnswers($request)
    {
        $correct_answers = $request->correct_answers;
        $list = array();
        for($index = 0; $index < $request->total_answers; $index++)
        {
            $list[$index]['answer']   = $correct_answers[$index];
            // dd($list);
        }
        return json_encode($list);
    }
    /**
     * This method process the image is being refferred
     * by getting the settings from ImageSettings Class
     * @param  Request $request   [Request object from user]
     * @param  [type]  $record    [The saved record which contains the ID]
     * @param  [type]  $file_name [The Name of the file which need to upload]
     * @param  string  $type      [Identify if it is question or an option image]
     * @return [type]             [description]
     */
     public function processUpload(Request $request, $record, $file_name, $type = 'option')
     {
       // if(env('DEMO_MODE')) {
       //  return ;
       // }
        // echo "<pre>";
        // print_r ($request->question_file);
        // echo "</pre>";
        // echo $file_name; 
        // echo $request->question_file->guessClientExtension();
        // exit;
         if ($request->hasFile($file_name)) {
          $imageObject = new App\ImageSettings();
          $destinationPath      = $imageObject->getExamImagePath();
          $fileName = $record->id.'-'.$file_name.'.'.$request->$file_name->getClientOriginalExtension();
          if($type!='option')
          {
          $fileName = $record->id.$type.'.'.$request->$file_name->getClientOriginalExtension();
          }
          // echo $request->$file_name->getClientOriginalExtension();
          // echo $fileName; exit;
          // $fileName = $record->id.'question.mp3';
          $request->file($file_name)->move($destinationPath, $fileName);
         return $fileName;
        }
     }
    /**
     * Validates the single answer type of questions and returs a validation rules
     * @param  [type] $request [Object of Request class]
     * @param  [type] $rules   [array of rules]
     * @return [array] $rules  [array of extended rules]
     */
    public function validateRadioQuestions($request, $rules)
    {
        $fileSize = (new ImageSettings())->getExamMaxFilesize();
        for($i=0; $i<$request->total_answers; $i++)
        {
            $file_name = 'upload_'.$i;
            if($request->hasFile($file_name))
            {
                $rules[$file_name] = 'mimes:jpeg,jpg,png,gif|max:'.$fileSize;
            }
        }
        $rules['correct_answers'] = 'bail|required|integer';
        return $rules;
    }
    public function validateCheckboxQuestions($request, $rules)
    {
        return $rules;
    }
    public function validateMatchQuestions($request, $rules)
    {
        $rules['title_left']    = 'required|max:30';
        $rules['title_right']   = 'required|max:30';
        return $rules;
    }
    public function validateBlankQuestions($request, $rules)
    {
        for($i=0; $i < $request->total_correct_answers; $i++)
        {
             $rules['correct_answers.'.$i] = 'required|max:30';
        }
        return $rules;
    }
    /**
     * Delete Record based on the provided slug
     * @param  [string] $slug [unique slug]
     * @return Boolean 
     */
    public function delete($slug)
    {
      if(!checkRole(getUserGrade(2)))
      {
        prepareBlockUserMessage();
        return back();
      }
        $qbObject = new QuestionBank();
        $record = $qbObject->getRecordWithSlug( $slug);
     try {
        if(!env('DEMO_MODE')) {
            $path   = (new App\ImageSettings())->getExamImagePath();
            $options = json_decode($record->answers);
            $this->deleteExamFile($options, $path, TRUE);
            $this->deleteExamFile($record->question_file, $path, FALSE);
            $record->delete();
        }
            $response['status'] = 1;
            $response['message'] = getPhrase('record_deleted_successfully');
            return json_encode($response);
        } catch ( \Illuminate\Database\QueryException $e) {
                 $response['status'] = 0;
           if(getSetting('show_foreign_key_constraint','module'))
            $response['message'] =  $e->errorInfo;
           else
            $response['message'] =  getPhrase('this_record_is_in_use_in_other_modules');
       }
       return json_encode($response);
    }
    public function deleteExamFile($record, $path, $is_array = FALSE)
    {
       if(env('DEMO_MODE')) {
        return ;
       }
        $files = array();
       $has_files = FALSE;
       if($is_array) {
           foreach($record as $option) {
          if(isset($option->has_file)){
                if($option->has_file) 
          {
                    $has_files = TRUE;
                    $files[] = $path.$option->file_name;
                }
        }
             }
        }
        else {
            $has_files = TRUE;
            $files[] = $path.$record;
        }
         if($has_files)
        {
             File::delete($files);
        }
    }
    public function isValidRecord($record)
    {
        if ($record === null) {
            flash('Ooops...!', getPhrase("page_not_found"), 'error');
            return $this->getRedirectUrl();
        }
        return FALSE;
    }
    public function getReturnUrl()
    {
        return URL_QUIZ_QUESTIONBANK;
    }
      /**
    * Display a Import Questions page
    *
    * @return Response
    */
     public function import()
     {
        if(!checkRole(getUserGrade(2)))
        {
          prepareBlockUserMessage();
          return back();
        }
        $data['records']      = FALSE;
        $data['active_class'] = 'exams';
        $data['heading']      = getPhrase('import_questions');
        $data['title']        = getPhrase('import_questions');
        $data['layout']        = getLayout();
        // return view('exams.questionbank.import.import', $data);
         $view_name = 'admin.exams.questionbank.import.import';
        return view($view_name, $data);
     }
    /**
     * Validates the subject
     * @param  [type]  $subject_id [description]
     * @return boolean             [description]
     */
    public function isValidSubject($subject_id)
    {
      $subject_id = (int) $subject_id;
       return Subject::where('id','=',$subject_id)->get()->count();
    }
      public function readExcel(Request $request)
     {
       if(!checkRole(getUserGrade(2)))
        {
          prepareBlockUserMessage();
          return back();
        }
       $success_list = [];
       $failed_list = [];
        $summary = [];
         try{
        if(Input::hasFile('excel')){
          $path = Input::file('excel')->getRealPath();
          $data = Excel::load($path, function($reader) {
          })->get();
          $all_records  = array();
          $excel_record = array();
          $final_records =array();
          $isHavingDuplicate = 0;
          if(!empty($data) && $data->count()){
            foreach ($data as $key => $value) {
              if(array_has($value,'subject_id'))
              {
                $all_records[] = $value;
              }
              else {
              foreach($value as $record)
              {
                $all_records[] = $record;
              }
            }
            }
             $questionbank = new QuestionBank();
            $summary = (object)$this->processExcelQuestions($request, $all_records);
        }
      }
       if(isset($summary->failed_list) || isset($summary->success_list)) {
       $data['failed_list']   =   $summary->failed_list;
       $data['success_list']  =    $summary->success_list;
       $this->excel_data['failed'] = $summary->failed_list;
       $this->excel_data['success'] = $summary->success_list;
       $this->excel_data['columns'] = $summary->columns_list;
         $this->downloadExcel();
       }
       else {
        flash('oops...!','improper_sheet_uploaded', 'error');
       }
      }
     catch(Exception $e)
     {
      // \Illuminate\Database\QueryException
       if(getSetting('show_foreign_key_constraint','module'))
       {
          flash('oops...!',$e->errorInfo, 'error');
       }
       else {
          flash('oops...!','improper_sheet_uploaded', 'error');
       }
       return back();
     }
        // URL_USERS_IMPORT_REPORT
       $data['failed_list']   =   $failed_list;
       $data['success_list']  =    $success_list;
       $data['records']      = FALSE;
       $data['layout']       = getLayout();
       $data['active_class'] = 'exams';
       $data['heading']      = getPhrase('upload_questions');
       $data['title']        = getPhrase('report');
       // return view('exams.questionbank.import.import-result', $data);
        $view_name = 'admin.exams.questionbank.import.import-result';
        return view($view_name, $data);
     }
public function getFailedData()
{
  return $this->excel_data;
}
public function downloadExcel()
{
    Excel::create('questions_report', function($excel) {
      $excel->sheet('Failed', function($sheet) {
        $data = $this->getFailedData();
        array_unshift($data['columns'], "Reason");
      $sheet->row(1, $data['columns']);
      $data = $this->getFailedData();
      $cnt = 2;
      foreach ($data['failed'] as $data_item) {
        $item = $data_item->record;
        $record_data = [];
        $record_data[] = $data_item->type;
        foreach($data['columns'] as $key=>$value)
         $record_data[] = $item->$value;
        $sheet->appendRow($cnt++, $record_data);
      }
    });
     $excel->sheet('Success', function($sheet) {
        $data = $this->getFailedData();
      $sheet->row(1, $data['columns']);
      $cnt = 2;
      foreach ($data['success'] as $data_item) {
        $item = (object)collect($data_item)->all();
        $record_data = [];
        foreach($data['columns'] as $key=>$value)
        {
          $item_value = $item->$value;
          if(isset($item_value)) {
            if($item_value!=NULL)
            $record_data[] = $item_value;
          }
        }
        $sheet->appendRow($cnt++, $record_data);
      }
    });
    })->download('xlsx');
}
     public function processExcelQuestions(Request $request, $data)
     {
       if(!count($data))
        return false;
     $questionbank = new QuestionBank();
      switch($request->question_type)
      {
        case 'radio': 
                   $questionbank->uploadRadioQuestions($data);
                   return array(
                                'failed_list'   => $questionbank->failed_list,
                                'success_list'  => $questionbank->success_list,
                                'columns_list'  => $questionbank->getAllColumnsList($data)
                                );
          break;
        case 'checkbox':
                   $questionbank->uploadCheckboxQuestions($data);
                   return array(
                                'failed_list'   => $questionbank->failed_list,
                                'success_list'  => $questionbank->success_list,
                                 'columns_list'  => $questionbank->getAllColumnsList($data)
                                );
          break;
        case 'blanks':
                   $questionbank->uploadBlankQuestions($data);
                   return array(
                                'failed_list'   => $questionbank->failed_list,
                                'success_list'  => $questionbank->success_list,
                                 'columns_list'  => $questionbank->getAllColumnsList($data)
                                );
          break;
      }
     }
    public static function hello() {
        return "Hello World!";
    }
    public function ajax_return_furigana(Request $request) {
             $data = $request->all();
            foreach ($data as $key => $value) {
              $data[$key] = change_furigana($value, 'return');
            }
            return json_encode($data);
    }
}