<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use App;
use Auth;
use App\User;
use App\Uid;
use SMS;
use DB;
use Artisan;
use GuzzleHttp\Client;
// use Illuminate\Support\Facades\App;
class DashboardController extends Controller
{
  public function __construct()
  {
      $this->middleware('auth');
}
public function index(Request $request)
{
    $data['active_class']   = 'dashboard';
        /**
         * Check the role of user
         * Redirect the user as per the eligiblity
         */
        $user = getUserRecord();
        $data['layout']         = getLayout();
        $data['title']          = getPhrase('dashboard');
        $role = getRole();
        if($role=='admin' || $role=='owner'){
           Artisan::call('view:clear');
           $roles = App\Role::get()->pluck('id');
           $dataset = [];
           $labels = [];
           $bgcolor = [];
           $border_color = [];
           foreach($roles as $key => $value)
           {
              $color_number = rand(0,999);
              $labels[] = '';
              $dataset[] = App\User::where('role_id', '=', $value)->get()->count();
              $bgcolor[] = getColor('',$color_number);
              $border_color[] = getColor('background',$color_number);
        }
        $dataset_label[] = 'lbl';
        $chart_data['type'] = 'bar'; 
            //horizontalBar, bar, polarArea, line, doughnut, pie
        $chart_data['data']   = (object) array(
          'labels'            => $labels,
          'dataset'           => $dataset,
          'dataset_label'     => $dataset_label,
          'bgcolor'           => $bgcolor,
          'border_color'      => $border_color
    );
        $data['chart_data'][] = (object)$chart_data;
        $dataset = [];
        $labels = [];
        $bgcolor = [];
        $border_color = [];
        $contry = array('VN'=>'Viet Nam','JP'=>'Japan');
        foreach($contry as $contry_key => $contry_value)
        {
              $color_number = rand(0,999);
              $labels[] = $contry_value;
              $dataset[] = DB::table('users')->where('is_register', '=', 1)->where('country_code','=', $contry_key)->get()->count();
              $bgcolor[] = getColor('',$color_number);
              $border_color[] = getColor('background',$color_number);
        }
        $dataset_label[] = '';
        $country_register['type'] = 'pie'; 
            //horizontalBar, bar, polarArea, line, doughnut, pie
        $country_register['data']   = (object) array(
          'labels'            => $labels,
          'dataset'           => $dataset,
          'dataset_label'     => $dataset_label,
          'bgcolor'           => $bgcolor,
          'border_color'      => $border_color
    );
        $data['country_register_data'][] = (object)$country_register;
        // Chart đợt thi
        $exam_free = DB::table('exam_free')->where('start_date','<=',\Carbon\Carbon::now())->get();
        $dataset = [];
        $labels = [];
        $bgcolor = [];
        $border_color = [];
        foreach($exam_free as $exam_free_key => $exam_free_value)
        {
            $color_number = rand(0,999);
            $labels[] = $exam_free_value->name;
            $dataset[] = DB::table('quizresultfinish')->where('exam_free_id','=',$exam_free_value->id)->get()->count();
            $bgcolor[] = getColor('',$color_number);
            $border_color[] = getColor('background',$color_number);
      }
      $dataset_label[] = '';
      $chart_exam_free['type'] = 'line'; 
            //horizontalBar, bar, polarArea, line, doughnut, pie
      $chart_exam_free['data']   = (object) array(
            'labels'            => $labels,
            'dataset'           => $dataset,
            'dataset_label'     => $dataset_label,
            'bgcolor'           => $bgcolor,
            'border_color'      => $border_color
      );
      $data['chart_exam_free_data'][] = (object)$chart_exam_free;
      /*Register Month*/
      $usermonth = DB::table('users')
          ->selectRaw('year(created_at) year, month(created_at) month, count(*) data')
          ->where('is_register','=',1)
          ->whereMonth('created_at','>',9)
          ->whereYear('created_at','>',2018)
          ->groupBy('month', 'year')
          ->orderBy('year')
          ->get();
      $dataset = [];
      $labels = [];
      $bgcolor = [];
      $border_color = [];
      foreach($usermonth as $usermonth_key => $usermonth_value) {
            $color_number = rand(0,999);
            $labels[] = 'Tháng '.$usermonth_value->month.'-'.$usermonth_value->year;
            $dataset[] = $usermonth_value->data;
            $bgcolor[] = getColor('',$color_number);
            $border_color[] = getColor('background',$color_number);
      }
      $dataset_label[] = '';
      $chart_usermonth['type'] = 'line'; 
            //horizontalBar, bar, polarArea, line, doughnut, pie
      $chart_usermonth['data']   = (object) array(
            'labels'            => $labels,
            'dataset'           => $dataset,
            'dataset_label'     => $dataset_label,
            'bgcolor'           => $bgcolor,
            'border_color'      => $border_color
      );
      $data['chart_usermonth_data'][] = (object)$chart_usermonth;
      /*Register City*/
      $usercity = DB::table('users')
              ->selectRaw('state, count(*) count')
              ->where('is_register','=',1)
              ->where('state','!=','')
              ->groupBy('state')
              ->havingRaw("count >= 5")
              ->orderBy('count','asc')
              ->get();
      $dataset = [];
      $labels = [];
      $bgcolor = [];
      $border_color = [];
      foreach($usercity as $usercity_key => $usercity_value) {
            $color_number = rand(0,999);
            $labels[] = $usercity_value->state;
            $dataset[] = $usercity_value->count;
            $bgcolor[] = getColor('',$color_number);
            $border_color[] = getColor('background',$color_number);
      }
      $dataset_label[] = '';
      $chart_usercity['type'] = 'bar'; 
      //horizontalBar, bar, polarArea, line, doughnut, pie
      $chart_usercity['data']   = (object) array(
            'labels'            => $labels,
            'dataset'           => $dataset,
            'dataset_label'     => '',
            'bgcolor'           => $bgcolor,
            'border_color'      => $border_color
      );
      $data['chart_usercity_data'][] = (object)$chart_usercity;
      /*Register City*/
      $usercityexam = DB::table('quizresultfinish')
              ->selectRaw('state, count(*) count')
              ->where('exam_free_id','>=',6)
              ->where('state','!=','')
              ->groupBy('state')
              ->havingRaw("count >= 4")
              ->orderBy('count','asc')
              ->get();
      $dataset = [];
      $labels = [];
      $bgcolor = [];
      $border_color = [];
      foreach($usercityexam as $usercityexam_key => $usercityexam_value) {
            $color_number = rand(0,999);
            $labels[] = $usercityexam_value->state;
            $dataset[] = $usercityexam_value->count;
            $bgcolor[] = getColor('',$color_number);
            $border_color[] = getColor('background',$color_number);
      }
      $dataset_label[] = '';
      $chart_usercityexam['type'] = 'bar'; 
      //horizontalBar, bar, polarArea, line, doughnut, pie
      $chart_usercityexam['data']   = (object) array(
            'labels'            => $labels,
            'dataset'           => $dataset,
            'dataset_label'     => '',
            'bgcolor'           => $bgcolor,
            'border_color'      => $border_color
      );
      $data['chart_usercityexam_data'][] = (object)$chart_usercityexam;
      /*Device*/
      $dataset = [];
      $labels = [];
      $bgcolor = [];
      $border_color = [];
      $device = array(0=>'PC', 1=>'Di động');
      foreach($device as $device_key => $device_value) {
            $usercityexam_count = DB::table('quizresultfinish')
                  ->where('exam_free_id','>=',6)
                  ->where('is_device','=',$device_key)
                  ->get()->count();
            $color_number = rand(0,999);
            $labels[] = $device_value;
            $dataset[] = $usercityexam_count;
            $bgcolor[] = getColor('',$color_number);
            $border_color[] = getColor('background',$color_number);
      }
      $dataset_label[] = '';
      $chart_device['type'] = 'pie'; 
      //horizontalBar, bar, polarArea, line, doughnut, pie
      $chart_device['data']   = (object) array(
            'labels'            => $labels,
            'dataset'           => $dataset,
            'dataset_label'     => '',
            'bgcolor'           => $bgcolor,
            'border_color'      => $border_color
      );
      $data['chart_device_data'][] = (object)$chart_device;
           $data['chart_heading']         = 'Tài khoản';
           $data['payments_chart_data'] = (object)$this->getPaymentStats();
           $data['payments_monthly_data'] = (object)$this->getPaymentMonthlyStats();
           $data['demanding_quizzes'] = (object)$this->getDemandingQuizzes();
           $data['demanding_paid_quizzes'] = (object)$this->getDemandingQuizzes('paid');
           $data['layout']        = getLayout();
      //   $data['right_bar']          = FALSE;
      // $data['right_bar_path']     = 'admin.common.right-bar-chart';
           $data['right_bar_data']     = array('chart_data' => $data['chart_data'] );
           $data['ids'] = array('myChart0' );  
           // return view('admin.dashboard', $data);
           $view_name = 'admin.admin.dashboard';
           return view($view_name, $data);
     }
     else if($role == 'parent')
     {
       $user                   = getUserWithSlug();
       $data['user']           = $user;
       $data['latest_quizzes'] = $this->getLatestQuizzes();
       $data['latest_series'] = $this->getLatestLmsSeries();
           // return view('parent.dashboard', $data);
       $view_name = 'admin.parent.dashboard';
       return view($view_name, $data);
 }else if ($role == 'input') {
      $user = getUserWithSlug();
      $data['user'] = $user;
      $data['latest_quizzes'] = $this -> getLatestQuizzes();
      $data['latest_series'] = $this -> getLatestLmsSeries();
            // return view('parent.dashboard', $data);
      $view_name = 'admin.layouts.input.inputdashboard';
      return view($view_name, $data);
}
else if ($role == 'export') {
      $user = getUserWithSlug();
      $data['user'] = $user;
      $data['latest_quizzes'] = $this -> getLatestQuizzes();
      $data['latest_series'] = $this -> getLatestLmsSeries();
            // return view('parent.dashboard', $data);
      $view_name = 'admin.layouts.export.exportdashboard';
      return view($view_name, $data);
}
else if($role == 'student' || $role == 'test')
{
  $url = '/exams/student-exam-series/list';
  return redirect()->away($url);
  return Redirect::to($url); exit;
  // $data['title'] = "Tổng quan";
  // $view_name = 'admin.student.dashboard';
  // return view($view_name, $data);         
}
}
public function getLatestQuizzes()
{
    $user = Auth::user();
    $interested_categories      = null;
    if($user->settings)
    {
        $interested_categories =  json_decode($user->settings)->user_preferences;
  }
  $quizzes = [];
  if($interested_categories) {
    if(count($interested_categories->quiz_categories))
          $quizzes         = App\Quiz::whereIn('category_id',
            (array) $interested_categories->quiz_categories)
    ->where('start_date','<=', date('Y-m-d'))
    ->where('end_date','>=', date('Y-m-d'))
    ->orderBy('created_at','desc')
    ->limit(5)
    ->get();
}
else {
  $quizzes         = App\Quiz::orderBy('created_at','desc')
  ->limit(5)
  ->get();
}
return $quizzes;
}
public function getLatestLmsSeries()
{
    $user = Auth::user();
    $interested_categories      = null;
    if($user->settings)
    {
        $interested_categories =  json_decode($user->settings)->user_preferences;
  }
  $series = [];
  if($interested_categories) {
    if(count($interested_categories->lms_categories))
          $series         = App\LmsSeries::whereIn('lms_category_id',
            (array) $interested_categories->lms_categories)
    ->orderBy('created_at','desc')
    ->limit(5)
    ->get();
}
else {
  $series         = App\LmsSeries::orderBy('created_at','desc')
  ->limit(5)
  ->get();
}
return $series;
}
public function getPaymentStats()
{
    $paymentObject = new App\Payment();
    $payment_data = (object)$paymentObject->getSuccessFailedCount();
    $payment_dataset = [$payment_data->success_count, $payment_data->cancelled_count, $payment_data->pending_count];
    $payment_labels = [getPhrase('success'), getPhrase('cancelled'), getPhrase('pending')];
    $payment_dataset_labels = [getPhrase('total')];
    $payment_bgcolor = [getColor('',4),getColor('',9),getColor('',18)];
    $payment_border_color = [getColor('background',4),getColor('background',9),getColor('background',18)]; 
    $payments_stats['data']    = (object) array(
        'labels'            => $payment_labels,
        'dataset'           => $payment_dataset,
        'dataset_label'     => $payment_dataset_labels,
        'bgcolor'           => $payment_bgcolor,
        'border_color'      => $payment_border_color
  );
    $payments_stats['type'] = 'bar'; 
    $payments_stats['title'] = getPhrase('overall_statistics');
    return $payments_stats;
}
    /**
     * This method returns the overall monthly summary of the payments made with status success
     * @return [type] [description]
     */
    public function getPaymentMonthlyStats()
    {
        $paymentObject = new App\Payment();
        $payment_data = (object)$paymentObject->getSuccessMonthlyData();
        $payment_dataset = [];
        $payment_labels = [];
        $payment_dataset_labels = [getPhrase('total')];
        $payment_bgcolor = [];
        $payment_border_color = []; 
        foreach($payment_data as $record)
        {
          $color_number = rand(0,999);;
          $payment_dataset[] = $record->total;
          $payment_labels[]  = $record->month;
          $payment_bgcolor[] = getColor('',$color_number);
          $payment_border_color[] = getColor('background', $color_number);
    }
    $payments_stats['data']    = (object) array(
        'labels'            => $payment_labels,
        'dataset'           => $payment_dataset,
        'dataset_label'     => $payment_dataset_labels,
        'bgcolor'           => $payment_bgcolor,
        'border_color'      => $payment_border_color
  );
    $payments_stats['type'] = 'line'; 
    $payments_stats['title'] = getPhrase('payments_reports_in').' '.getCurrencyCode(); 
    return $payments_stats;
}
public function getDemandingQuizzes($type='')
{
      $quizResultObject = new App\QuizResult();
      $usage = $quizResultObject->getQuizzesUsage($type);
      $summary_dataset = [];
      $summary_labels = [];
      $summary_dataset_labels = [getPhrase('total')];
      $summary_bgcolor = [];
      $summary_border_color = []; 
      foreach($usage as $record)
      {
          $color_number = rand(0,999);;
          $summary_dataset[] = $record->total;
          $summary_labels[]  = $record->quiz_title;
          $summary_bgcolor[] = getColor('',$color_number);
          $summary_border_color[] = getColor('background', $color_number);
    }
    $quiz_stats['data']    = (object) array(
        'labels'            => $summary_labels,
        'dataset'           => $summary_dataset,
        'dataset_label'     => $summary_dataset_labels,
        'bgcolor'           => $summary_bgcolor,
        'border_color'      => $summary_border_color
  );
    $quiz_stats['type'] = 'doughnut'; 
    $quiz_stats['title'] = getPhrase('demanding_quizzes'); 
    if($type!='')
       $quiz_stats['title'] = getPhrase('demanding').' '.$type.' '.getPhrase('quizzes'); 
 return $quiz_stats;
}
public function testLanguage($value='')
{ 
      dd( $language_phrases = (array) session('language_phrases'));
      $tr = new TranslateClient(); // Default is from 'auto' to 'en'
      $tr->setSource('en'); // Translate from English
      $tr->setTarget('te'); // Translate to Georgian
      echo $tr->translate('Hello World');
     //  $url = urlencode("https://www.googleapis.com/language/translate/v2?q=Select Role&target=hi&source=en&key=AIzaSyAhYkPKPhQ0MA4iWuU0HuoDUZqQKLU16yY");
     //  $returned_content =  json_decode(file_get_contents($url), true);
     // dd($returned_content);
}
}