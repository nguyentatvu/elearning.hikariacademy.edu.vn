<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use \App;
use App\Http\Requests;
use App\Quiz;
use App\Topic;
use App\Subject;
use App\QuestionBank;
// use App\QuestionBank;
use App\QuizCategory;
use App\ExamSeries;
use App\ExamRate;
use App\ExamSeriesfree;
use Yajra\DataTables\DataTables;
use DB;
use Auth;
use Image;
use ImageSettings;
use File;
use Input;
class ExamSeriesfreeController extends Controller
{
    public function __construct()
    {
      $this->middleware('auth');
    }

    public function total($slug)
    {
      if(!checkRole(getUserGrade(2)))
      {
        prepareBlockUserMessage();
        return back();
      }

      $dotthi = DB::table('exam_free')
                      ->where('id', '=', $slug)
                      ->first();

      $data['slug'] = $slug; 
      $data['dotthi'] = $dotthi;
      $data['active_class']       = 'exams';
      $data['title']              = 'Tổng quan: ' . $dotthi->name;
      $view_name = 'admin.exams.examseriesfree.total';
      return view($view_name, $data);
    }

    public function rateresult($slug)
    {
      if(!checkRole(getUserGrade(2)))
      {
        prepareBlockUserMessage();
        return back();
      }

      $dotthi = DB::table('exam_free')
                      ->where('id', '=', $slug)
                      ->first();


      $data['slug'] = $slug; 
      $data['active_class']       = 'exams';
      $data['title']              = 'Kết quả đợt thi: ' . $dotthi->name;
      $view_name = 'admin.exams.examseriesfree.listrateresult';
      return view($view_name, $data);
    }

    public function getrateresultDatatable($slug)
    {
      if(!checkRole(getUserGrade(2)))
      {
        prepareBlockUserMessage();
        return back();
      }
        $records = array();

        $dotthi = DB::table('exam_free')->where('id', '=', $slug)->first();

        $records = DB::table('quizresultfinish')->join('users', 'users.id', '=', 'quizresultfinish.user_id')
                                ->join('examseries', 'examseries.id', '=', 'quizresultfinish.examseri_id')

                                ->select(['users.name', 'examseries.title', 'quizresultfinish.total_marks','quizresultfinish.finish', 'examseries.category_id','quizresultfinish.status'])
                                ->wherein('quizresultfinish.examseri_id',[$dotthi->exam1_1,$dotthi->exam2_1,$dotthi->exam3_1,$dotthi->exam4_1,$dotthi->exam5_1])
                                ->whereBetween('quizresultfinish.created_at', [$dotthi->start_date, $dotthi->end_date])
                                ->orderBy('quizresultfinish.id', 'desc');
            
        return DataTables::of($records)
        ->addColumn('action', function ($records) {
          $link_data = '<div class="dropdown more">
                        <a id="dLabel" type="button" class="more-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="mdi mdi-dots-vertical"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="dLabel">
                            <li><a href="#"><i class="fa fa-pencil"></i>Xem</a></li>';
                           $temp = '';
                           
                    $temp .='</ul></div>';
                    $link_data .=$temp;
          return $link_data;
            })
        ->editColumn('user_id', function($records)
        {
          return $records->name;
        })
        ->editColumn('total_marks', function($records)
         {
          if ($records->finish == 3) {
              $ketqua = $records->total_marks;
              if ($records->category_id == 3) {
                if ($ketqua >= 95) {
                  $ketqua = $ketqua;
                } else {
                  $ketqua = $ketqua;
                }
              } else {
                if ($ketqua >= 90) {
                  $ketqua = $ketqua;
                } else {
                  $ketqua = $ketqua;
                }
              }
           } else {
              $ketqua = '<span class="label label-info">Chưa hoàn thành</span>';
           }
          return $ketqua;
        })
        ->editColumn('finish', function($records)
         {
          if ($records->finish == 3) {

              // $ketqua = $records->total_marks;

              // if ($records->status == 1) {
              //     $ketqua = '<span class="label label-success">Đạt</span>';
              //   } else {
              //     $ketqua = ' <span class="label label-warning">Chưa đạt</span>';
              // }

              // if ($records->category_id == 3) {
              //   if ($ketqua >= 95) {
              //     $ketqua = '<span class="label label-success">Đạt</span>';
              //   } else {
              //     $ketqua = ' <span class="label label-warning">Chưa đạt</span>';
              //   }
              // } else {
              //   if ($ketqua >= 90) {
              //     $ketqua = ' <span class="label label-success">Đạt</span>';
              //   } else {
              //     $ketqua = ' <span class="label label-warning">Chưa đạt</span>';
              //   }
              // }

              if ($records->status == 1) {
                  $ketqua = '<span class="label label-success">Đạt</span>';
                } else {
                  $ketqua = ' <span class="label label-warning">Chưa đạt</span>';
              }
           } else {
              $ketqua = '<span class="label label-info">Chưa hoàn thành</span>';
           }
          return $ketqua;
        })
               
        ->removeColumn('status')
        ->removeColumn('category_id')
        ->rawColumns(['action', 'total_marks', 'finish'])
        ->make();
    }


    /**
     * Course listing method
     * @return Illuminate\Database\Eloquent\Collection
     */

    public function rate($slug)
    {
      if(!checkRole(getUserGrade(2)))
      {
        prepareBlockUserMessage();
        return back();
      }

      $data['slug'] = $slug; 
      $data['active_class']       = 'exams';
      $data['title']              = 'Đánh giá';
      $view_name = 'admin.exams.examseriesfree.listrate';
        return view($view_name, $data);
    }

    /*public function getrateDatatable()
    {
      if(!checkRole(getUserGrade(2)))
      {
        prepareBlockUserMessage();
        return back();
      }
        $records = array();
        $records = ExamSeriesfree::select(['name', 'exam3_3', 'exam4_1', 'exam5_1', 'start_date', 'end_date'])
            ->orderBy('id', 'desc');
        return DataTables::of($records)
        ->addColumn('action', function ($records) {
          $link_data = '<div class="dropdown more">
                        <a id="dLabel" type="button" class="more-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="mdi mdi-dots-vertical"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="dLabel">
                            <li><a href="#"><i class="fa fa-pencil"></i>Xem thống kê</a></li>
                           <li><a href="'.URL_EXAM_SERIES_EDIT.$records->slug.'"><i class="fa fa-pencil"></i>Sửa</a></li>';
                           $temp = '';
                           if(checkRole(getUserGrade(1))) {
                            $temp .= ' <li><a href="javascript:void(0);" onclick="deleteRecord(\''.$records->slug.'\');"><i class="fa fa-trash"></i>Xóa</a></li>';
                           }
                    $temp .='</ul></div>';
                    $link_data .=$temp;
          return $link_data;
            })
        ->editColumn('name', function($records)
        {
          return $records->name;
        })
        ->editColumn('exam3_1', function($records)
        {
          $records_title_n3 = ExamSeries::select(['title'])->where('id', '=', $records->exam3_1)->first();
          return 123;
        })
        ->editColumn('exam4_1', function($records)
        {
          $records_title_n4 = ExamSeries::select(['title'])->where('id', '=', $records->exam4_1)->first();
          return 1111;
        })
        ->editColumn('exam5_1', function($records)
        {
          $records_title_n5 = ExamSeries::select(['title'])->where('id', '=', $records->exam5_1)->first();
          return 123123;
        })

      
        ->make();
    }*/

    public function getrateDatatable($slug)
    {
      if(!checkRole(getUserGrade(2)))
      {
        prepareBlockUserMessage();
        return back();
      }
        $records = array();

        $dotthi = DB::table('exam_free')->where('id', '=', $slug)->first();

        if ($dotthi) {
          $records = DB::table('examseries_rate')
                  ->join('users', 'users.id', '=', 'examseries_rate.user_id')
                  ->join('examseries', 'examseries.id', '=', 'examseries_rate.examseries_id')
                  ->select(['users.name', 'examseries.title', 'dethi', 'giaodien', 'thaotac','amthanh', 'tocdo', 'gopy'])
                  ->wherein('examseries_rate.examseries_id',[$dotthi->exam3_1,$dotthi->exam4_1,$dotthi->exam5_1])
                  ->whereBetween('examseries_rate.created_at', [$dotthi->start_date, $dotthi->end_date])
                  ->orderBy('examseries_rate.id', 'desc');
        }

        return DataTables::of($records)
        ->addColumn('action', function ($records) {
          $link_data = '<div class="dropdown more">
                        <a id="dLabel" type="button" class="more-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="mdi mdi-dots-vertical"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="dLabel">
                            <li><a href="#"><i class="fa fa-pencil"></i>Xem</a></li>';
                           $temp = '';
                           
                    $temp .='</ul></div>';
                    $link_data .=$temp;
          return $link_data;
            })
        ->editColumn('user_id', function($records)
        {
          return $records->name;
        })
         ->editColumn('dethi', function($records)
        {
          switch ($records->dethi) {
              case 1:
                  $dg = "Rất dễ";
                  break;
              case 2:
                  $dg = "Dễ";
                  break;
              case 3:
                  $dg = "Bình thường";
                  break;
              case 4:
                  $dg = "Khó";
                  break;
              case 5:
                  $dg = "Rất khó";
                  break;
              default:
                  $dg = "Bình thường";
          }
          return $dg;
        })
         ->editColumn('giaodien', function($records)
         {
           switch ($records->giaodien) {
               case 1:
                   $dg = "Rất khó nhìn";
                   break;
               case 2:
                   $dg = "Khó nhìn";
                   break;
               case 3:
                   $dg = "Bình thường";
                   break;
               case 4:
                   $dg = "Dễ nhìn";
                   break;
               case 5:
                   $dg = "Rất dễ nhìn";
                   break;
               default:
                   $dg = "Bình thường";
           }
           return $dg;
         })
         ->editColumn('amthanh', function($records)
         {
           switch ($records->amthanh) {
               case 1:
                   $dg = "Rất khó nghe";
                   break;
               case 2:
                   $dg = "Khó nghe";
                   break;
               case 3:
                   $dg = "Bình thường";
                   break;
               case 4:
                   $dg = "Dễ nghe";
                   break;
               case 5:
                   $dg = "Rất dễ nghe";
                   break;
               default:
                   $dg = "Bình thường";
           }
           return $dg;
         })
         ->editColumn('thaotac', function($records)
         {
           switch ($records->amthanh) {
               case 1:
                   $dg = "Rất khó";
                   break;
               case 2:
                   $dg = "Khó";
                   break;
               case 3:
                   $dg = "Bình thường";
                   break;
               case 4:
                   $dg = "Dễ";
                   break;
               case 5:
                   $dg = "Rất dễ";
                   break;
               default:
                   $dg = "Bình thường";
           }
           return $dg;
         })
         ->editColumn('tocdo', function($records)
         {
           switch ($records->tocdo) {
               case 1:
                   $dg = "Rất Chậm";
                   break;
               case 2:
                   $dg = "Chậm";
                   break;
               case 3:
                   $dg = "Bình thường";
                   break;
               case 4:
                   $dg = "Nhanh";
                   break;
               case 5:
                   $dg = "Rất nhanh";
                   break;
               default:
                   $dg = "Bình thường";
           }
           return $dg;
         })
         ->rawColumns(['action'])
     
        ->make();
    }


    public function index()
    {
      if(!checkRole(getUserGrade(2)))
      {
        prepareBlockUserMessage();
        return back();
      }

      $records_title_n3 = ExamSeries::select(['title'])->where('id', '=', 6)->first();

      $data['active_class']       = 'exams';
      $data['title']              = 'Đợt thi online';
        // return view('exams.examseries.list', $data);
       $view_name = 'admin.exams.examseriesfree.list';
        return view($view_name, $data);
    }
    
    /**
     * This method returns the datatables data to view
     * @return [type] [description]
     */
    public function getDatatable()
    {
      if(!checkRole(getUserGrade(2)))
      {
        prepareBlockUserMessage();
        return back();
      }
        $records = array();
        $records = ExamSeriesfree::select(['id','name', 'exam1_1','exam2_1','exam3_1', 'exam4_1', 'exam5_1', 'start_date', 'end_date'])
            ->orderBy('id', 'desc');
        return DataTables::of($records)
        ->addColumn('action', function ($records) {
          $link_data = '<div class="dropdown more">
                        <a id="dLabel" type="button" class="more-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="mdi mdi-dots-vertical"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="dLabel">
                            <li><a href="/exams/exam-series-free/total/'.$records->id.'"><i class="fa fa-cube"></i>Tổng quan</a></li>
                            <li><a href="/exams/exam-series-free/rate/'.$records->id.'"><i class="fa fa-search"></i>Xem đánh giá</a></li>
                            <li><a href="/exams/exam-series-free/rate-result/'.$records->id.'"><i class="fa fa-search"></i>Xem kết quả</a></li>
                            <li><a href="/exams/exam-series-free/edit/'.$records->id.'"><i class="fa fa-edit"></i>Sửa</a></li>
							<!-- <li><a href="/email/thongbaothithu/'.$records->id.'"><i class="fa fa-send"></i>Gửi Email</a></li> -->
							<li><a href="/email/danhsachthithu/'.$records->id.'"><i class="fa fa-send"></i>Gửi Email</a></li>
						   ';
                           $temp = '';
                           if(checkRole(getUserGrade(1))) {
                            $temp .= ' <li><a href="javascript:void(0);" onclick="deleteRecord(\''.$records->id.'\');"><i class="fa fa-trash"></i>Xóa</a></li>';
                           }
                    $temp .='</ul></div>';
                    $link_data .=$temp;
          return $link_data;
            })
        ->editColumn('name', function($records)
        {
          return $records->name;
        })
        ->editColumn('exam1_1', function($records)
        {
          $records_title_n1 = ExamSeries::select(['title'])->where('id', '=', $records->exam1_1)->first();
          return $records_title_n1['title'];
        })
        ->editColumn('exam2_1', function($records)
        {
          $records_title_n2 = ExamSeries::select(['title'])->where('id', '=', $records->exam2_1)->first();
          return $records_title_n2['title'];
        })
        ->editColumn('exam3_1', function($records)
        {
          $records_title_n3 = ExamSeries::select(['title'])->where('id', '=', $records->exam3_1)->first();
          return $records_title_n3['title'];
        })
        ->editColumn('exam4_1', function($records)
        {
          $records_title_n4 = ExamSeries::select(['title'])->where('id', '=', $records->exam4_1)->first();
          return $records_title_n4['title'];
        })
        ->editColumn('exam5_1', function($records)
        {
          $records_title_n5 = ExamSeries::select(['title'])->where('id', '=', $records->exam5_1)->first();
          return $records_title_n5['title'];
        })
        ->removeColumn('id')
        ->rawColumns(['action'])
      
        ->make();
    }
    /**
     * This method loads the create view
     * @return void
     */
    public function create()
    {
      if(!checkRole(getUserGrade(2)))
      {
        prepareBlockUserMessage();
        return back();
      }
		$data['record']     = array();
		$data['n1']         = array_pluck(ExamSeries::where('category_id','=', 1)->get(), 'title', 'id');
		$data['n2']         = array_pluck(ExamSeries::where('category_id','=', 2)->get(), 'title', 'id');
		$data['n3']         = array_pluck(ExamSeries::where('category_id','=', 3)->get(), 'title', 'id');
		$data['n4']         = array_pluck(ExamSeries::where('category_id','=', 4)->get(), 'title', 'id');
		$data['n5']         = array_pluck(ExamSeries::where('category_id','=', 5)->get(), 'title', 'id');
		$data['start_date'] = date('Y-m-d H:i');
		$data['end_date']   = date('Y-m-d H:i');
		$data['active_class']       = 'exams';
		$data['title']              = 'Thêm đợt thi thử';
      // return view('exams.examseries.add-edit', $data);
        $view_name = 'admin.exams.examseriesfree.add-edit';
        return view($view_name, $data);
    }

	  /**
     * This method adds record to DB
     * @param  Request $request [Request Object]
     * @return void
     */
    public function store(Request $request)
    {
      if(!checkRole(getUserGrade(2)))
      {
        prepareBlockUserMessage();
        return back();
      }
		$rules = [
		'name'              => 'bail|required|max:400' ,
		];
		$this->validate($request, $rules);

		$record               = new ExamSeriesfree();
		$record->name         = $request->name;
		$record->exam1_1      = $request->exam1_1;
		$record->exam2_1      = $request->exam2_1;
		$record->exam3_1      = $request->exam3_1;
		$record->exam4_1      = $request->exam4_1;
		$record->exam5_1      = $request->exam5_1;

		$record->start_date   = date('Y-m-d H:i',(strtotime($request->start_date)));
		$record->end_date     = date('Y-m-d H:i',(strtotime($request->end_date)));
		$record->save();

      flash('Thêm Đợt thi thử thành công','', 'success');
      return redirect(URL_EXAM_SERIES_FREE);
    }

    /**
     * This method loads the edit view based on unique slug provided by user
     * @param  [string] $slug [unique slug of the record]
     * @return [view with record]       
     */
    public function edit($slug)
    {
      if(!checkRole(getUserGrade(2)))
      {
        prepareBlockUserMessage();
        return back();
      }

      $record = ExamSeriesfree::getRecordWithSlug($slug);
      if($isValid = $this->isValidRecord($record))
        return redirect($isValid);
      // dd($record);
		$data['record']     = $record;
		$data['n1']         = array_pluck(ExamSeries::where('category_id','=', 1)->get(), 'title', 'id');
		$data['n2']         = array_pluck(ExamSeries::where('category_id','=', 2)->get(), 'title', 'id');
		$data['n3']         = array_pluck(ExamSeries::where('category_id','=', 3)->get(), 'title', 'id');
		$data['n4']         = array_pluck(ExamSeries::where('category_id','=', 4)->get(), 'title', 'id');
		$data['n5']         = array_pluck(ExamSeries::where('category_id','=', 5)->get(), 'title', 'id');
		$data['start_date'] =  date('Y-m-d H:i',(strtotime($record->start_date)));
		$data['end_date']   =  date('Y-m-d H:i',(strtotime($record->end_date)));
		$data['active_class']     = 'exams';
		$data['title']            = 'Chỉnh sửa đợt thi thử';
      // return view('exams.examseries.add-edit', $data);
      $view_name = 'admin.exams.examseriesfree.add-edit';
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
      if(!checkRole(getUserGrade(2)))
      {
        prepareBlockUserMessage();
        return back();
      }
		$rules = [
		'name'              => 'bail|required|max:400' ,
		];
		$this->validate($request, $rules);

        $record = ExamSeriesfree::getRecordWithSlug($slug);
		if($isValid = $this->isValidRecord($record))
			return redirect($isValid);

		$record->name         = $request->name;
		$record->exam1_1      = $request->exam1_1;
		$record->exam2_1      = $request->exam2_1;
		$record->exam3_1      = $request->exam3_1;
		$record->exam4_1      = $request->exam4_1;
		$record->exam5_1      = $request->exam5_1;

		$record->start_date   = date('Y-m-d H:i',(strtotime($request->start_date)));
		$record->end_date     = date('Y-m-d H:i',(strtotime($request->end_date)));
		$record->save();

      flash('success','Cập nhật Đợt thi thử thành công', 'success');
      return redirect(URL_EXAM_SERIES_FREE);
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

	  try{
		DB::table('exam_free')->where('id', '=', $slug)->delete();
        $response['status'] = 1;
        $response['message'] = 'Đã xóa Đợt thi thử';
      } catch ( \Illuminate\Database\QueryException $e) {
            $response['status'] = 0;
            $response['message'] =  $e->errorInfo;
       }
       return json_encode($response);
    }

    public function deleteFile($record, $path, $is_array = FALSE)
    {
      if(env('DEMO_MODE')) {
        return '';
      }
        $files = array();
        $files[] = $path.$record;
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
     public function processUpload(Request $request, $record, $file_name)
     {
      if(env('DEMO_MODE')) {
        return 'demo';
      }
         if ($request->hasFile($file_name)) {
          $examSettings = getExamSettings();
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
 

    public function isValidRecord($record)
    {
		if ($record === null) {
			flash('Ooops...!', getPhrase("page_not_found"), 'error');
			return $this->getReturnUrl();
		}
		return FALSE;
    }
    public function getReturnUrl()
    {
      return URL_EXAM_SERIES_FREE;
    }
    /**
     * Returns the list of subjects based on the requested subject
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function getExams(Request $request)
    {
      $category_id = $request->category_id;
      $is_paid     = $request->series_type;
      $exams = Quiz::where('category_id','=',$category_id)
                    ->where('total_marks','!=','0')
                    ->where('is_paid',$is_paid)
                    ->get();
      return json_encode(array('exams'=>$exams));
    }
    /**
     * Updates the questions in a selected quiz
     * @param  [type] $slug [description]
     * @return [type]       [description]
     */
    public function updateSeries($slug)
    {
       if(!checkRole(getUserGrade(2)))
       {
            prepareBlockUserMessage();
            return back();
        }
      /**
       * Get the Quiz Id with the slug
       * Get the available questions from questionbank_quizzes table
       * Load view with this data
       */
    $record = ExamSeries::getRecordWithSlug($slug); 
      $data['record']           = $record;
      $data['active_class']       = 'exams';
        $data['right_bar']          = TRUE;
        $data['right_bar_path']     = 'exams.examseries.right-bar-update-questions';
        $data['settings']           = FALSE;
        $previous_records = array();
        if($record->total_exams > 0)
        {
            $quizzes = DB::table('examseries_data')
                            ->where('examseries_id', '=', $record->id)
                            ->get();
            foreach($quizzes as $quiz)
            {
                $temp = array();
                $temp['id'] = $quiz->quiz_id;
                $quiz_details = Quiz::where('id', '=', $quiz->quiz_id)->first();
                $temp['dueration'] = $quiz_details->dueration;
                $temp['total_marks'] = $quiz_details->total_marks;
                $temp['total_questions'] = $quiz_details->total_questions;
                $temp['title'] = $quiz_details->title;
                array_push($previous_records, $temp);
            }
            $settings['exams'] = $previous_records;
            $settings['total_questions'] = $record->total_questions;
        $data['settings']           = json_encode($settings);
        }
      $data['exam_categories']        = array_pluck(App\QuizCategory::all(), 
                      'category', 'id');
      $data['title']  = 'Cập nhật '.$record->title;
      // return view('exams.examseries.update-questions', $data);
       $view_name = 'admin.exams.examseries.update-questions';
        return view($view_name, $data);
    }
    public function storeSeries(Request $request, $slug)
    { 
        if(!checkRole(getUserGrade(2)))
        {
            prepareBlockUserMessage();
            return back();
        }
        $exam_series = ExamSeries::getRecordWithSlug($slug); 
        $series_id  = $exam_series->id;
        $quizzes    = json_decode($request->saved_series);
        $questions  = 0;
        $exams    = 0;
        $quizzes_to_update = array();
        foreach ($quizzes as $record) {
            $temp = array();
            $temp['quiz_id'] = $record->id;
            $temp['examseries_id'] = $series_id;
            array_push($quizzes_to_update, $temp);
            $questions += $record->total_questions;
        }
        $exam_series->total_questions = $questions;
        $exam_series->total_exams = count($quizzes);
        if(!env('DEMO_MODE')) {
          //Clear all previous questions
          DB::table('examseries_data')->where('examseries_id', '=', $series_id)->delete();
          //Insert New Questions
          DB::table('examseries_data')->insert($quizzes_to_update);
          $exam_series->save();
        }
        flash('success','record_updated_successfully', 'success');
        return redirect(URL_EXAM_SERIES_FREE);
    }
    /**
     * This method lists all the available exam series for students
     * 
     * @return [type] [description]
     */
    public function listSeries()
    {

      /*echo "<pre>";
      print_r (getUserGrade(6));
      echo "</pre>";exit;*/
      if(checkRole(getUserGrade(2)))
      {
        return back();
      }
        $data['active_class']       = 'exams';
        $data['title']              = 'Bộ đề thi';
        $data['series']             = [];
        $user = Auth::user();
        $interested_categories      = null;
        $data['series_cd'] = array();
        $classes_user =  DB::table('classes_user')
                     ->where('student_id','=',$user->id)
                     ->orderBy('id', 'desc')
                     ->first();
        if (!empty($classes_user)) {
          $classes_exam =  ExamSeries::join('classes_exam', 'classes_exam.exam_id', '=', 'examseries.id')
                       ->where('classes_exam.classes_id','=',$classes_user->classes_id)
                       ->where('classes_exam.start_date','<=',date('Y-m-d H:i:s'))
                       ->where('classes_exam.end_date','>=',date('Y-m-d H:i:s'))
                       -> where('is_paid','=','2')
                       ->get();
          if ($classes_exam->count() > 0) {
            $data['series_cd'] = $classes_exam;
          }
        }

        /*$exam_free             = DB::table('exam_free')
                                                    ->where('start_date','<=',date('Y-m-d H:i:s'))
                                                    ->where('end_date','>=',date('Y-m-d H:i:s'))
                                                    ->first();

        $exam_free_n3 = '['.$exam_free->exam3_1.','.$exam_free->exam3_2.','.$exam_free->exam3_3.']';
        
        

        $exam_result             = DB::table('quizresultfinish')->where('user_id','=',$user->id)->where('finish','=',3)->get()->keyBy('examseri_id')->toArray();
        $data['exam_result'] = $exam_result;
        $data['series_n3']             = ExamSeries::wherein('id',[$exam_free->exam3_1,$exam_free->exam3_2,$exam_free->exam3_3])->get();
        $data['series_n4']             = ExamSeries::wherein('id',[$exam_free->exam4_1,$exam_free->exam4_2,$exam_free->exam4_3])->get();
        $data['series_n5']             = ExamSeries::wherein('id',[$exam_free->exam5_1,$exam_free->exam5_2,$exam_free->exam5_3])->get();*/


        $data['series_n3']  = array();           
        $data['series_n4']  = array();           
        $data['series_n5']  = array();         
        
        if(checkRole(getUserGrade(6)))
        {
          $data['series_n3']             = ExamSeries::where('category_id','=','3')
                                                      ->get();
          $data['series_n4']             = ExamSeries::where('category_id','=','4')
                                              ->get();
          $data['series_n5']             = ExamSeries::where('category_id','=','5')
                                                      ->get();
        }
        
        $data['layout']             = getLayout();
        $data['user']             = $user;
        $view_name = 'admin.student.exams.exam-series-list';
        return view($view_name, $data);
    }
    /**
     * This method displays all the details of selected exam series
     * @param  [type] $slug [description]
     * @return [type]       [description]
     */
    public function viewItem($slug)
    {
        $record = ExamSeries::getRecordWithSlug($slug); 
        $examseries_id = $record->id; 
        // Get quizresultfinish
       $quizresultfinish_data = DB::table('quizresultfinish')
                ->where('examseri_id', '=', $examseries_id)
                ->where('user_id', '=', Auth::user()->id)
                ->orderBy('id', 'desc')
                ->first();
        if ($quizresultfinish_data && $quizresultfinish_data->finish < 3) {
          $finish_current = $quizresultfinish_data->finish + 1;
        } else {
          $finish_current = 1;
        }
        $data['finish_current'] = $finish_current;
        /*if($isValid = $this->isValidRecord($record))
          return redirect($isValid);*/  
        $data['active_class']       = 'exams';
        $data['pay_by']             = '';
        $data['content_record']     = FALSE;
        $data['title']              = change_furigana_admin( $record->title );
        $data['item']               = $record;
        $data['right_bar']         = TRUE;
        $data['right_bar_path']   = 'student.exams.exam-series-item-view-right-bar';
        $data['right_bar_data']     = array(
                                            'item' => $record,
                                            );
        $data['layout']              = getLayout();
       // return view('student.exams.series.series-view-item', $data);
         $view_name = 'admin.student.exams.series.series-view-item';
        return view($view_name, $data);
    }
}