<?php
namespace App\Http\Controllers;
use \App;
use App\Payment;
use App\User;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\LmsCategory;
use App\LmsContent;
use App\LmsSeries;
use App\LmsStudentView;
use App\Role;
use App\Services\UserService;
use Carbon\Carbon;
use mysql_xdevapi\Exception;
use PhpParser\Node\Stmt\If_;
use DB;
use Image;
use ImageSettings;
use File;
use Response;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\Auth;

class StudentLmsController extends Controller
{
    private $finished_content_ids;
    private $viewed_content_ids;
    private $lms_contents;
    private $userService;

    public function __construct(UserService $userService) {
        $this->userService = $userService;
    }

    /**
     * Prepare finished and viewed (unfinished) content id list
     * @param string $series_slug
     * @return void
     */
    private function prepareContentIds($series_slug) {
        $content_view = LmsStudentView::query()
            ->join('lmscontents', 'lmscontents.id', '=', 'lms_student_view.lmscontent_id')
            ->join('lmsseries', 'lmsseries.id', '=', 'lmscontents.lmsseries_id')
            ->where([
                ['users_id', Auth::id()],
                ['lmsseries.slug', $series_slug],
                ['lmscontents.delete_status', 0],
            ])
            ->get();

        $this->finished_content_ids = $content_view->where('finish', 1)
            ->pluck('lmscontent_id')
            ->toArray();
        $this->viewed_content_ids = $content_view->where('finish', 0)
            ->pluck('lmscontent_id')
            ->toArray();
        $this->lms_contents = LmsContent::where([
            'lmsseries_id' => LmsSeries::where('slug', $series_slug)->first()->id,
            'delete_status' => 0,
        ])->get();
    }

    /**
     * Update and insert content views
     * @param string $slug
     * @param int $stt
     * @param array $content_view
     * @return void
     */
    private function updateAndInsertContentView($slug, $stt) {
        $current_content_view = LmsStudentView::query()
            ->join('lmscontents', 'lmscontents.id', '=', 'lms_student_view.lmscontent_id')
            ->join('lmsseries', 'lmsseries.id', '=', 'lmscontents.lmsseries_id')
            ->where([
                ['users_id', Auth::id()],
                ['lmsseries.slug', $slug],
                ['lmscontents.delete_status', 0],
                ['lmscontents.id', $stt],
            ])->get();
        $current_lms_content = LmsContent::where([
            'id' => $stt,
            'delete_status' => 0,
        ])->first();

        // Make sure current content view is empty and its content exists
        if ($current_content_view->isEmpty() && $current_lms_content != null) {
            LmsStudentView::insert([
                'lmscontent_id' => $stt,
                'users_id' => Auth::id(),
                'view_time' => 0,
                'finish' => 0,
                'type' => $current_lms_content->type,
            ]);
        }
    }

  /**
  * Listing method
  * @return Illuminate\Database\Eloquent\Collection
  */
  public function index()
  {
      if(checkRole(getUserGrade(2)))
    {
      return back();
    }
    $data['active_class']       = 'lms';
    $data['title']              = 'Khóa học';
    $data['layout']              = getLayout();
    $data['categories']         = [];
    $user = Auth::user();
    $interested_categories      = null;
    if($user->settings)
    {
      $interested_categories =  json_decode($user->settings)->user_preferences;
    }
    if($interested_categories)    {
      if(count($interested_categories->lms_categories))
        $data['categories']         = Lmscategory::
      whereIn('id',(array) $interested_categories->lms_categories)
      ->paginate(getRecordsPerPage());
    }
    $data['user'] = $user;
    // return view('student.lms.categories', $data);
    $view_name = 'admin.student.lms.categories';
    return view($view_name, $data);
  }
  public function viewCategoryItems($slug)
  {
    $record = LmsCategory::getRecordWithSlug($slug);
    if($isValid = $this->isValidRecord($record))
      return redirect($isValid);
    $data['active_class']       = 'lms';
    $data['user']               = Auth::user();
    $data['title']              = 'Khóa học';
    $data['layout']             = getLayout();
    $data['series']             = LmsSeries::where('lms_category_id','=',$record->id)
    ->where('start_date','<=',date('Y-m-d'))
    ->where('end_date','>=',date('Y-m-d'))
    ->paginate(getRecordsPerPage());
    // return view('student.lms.lms-series-list', $data);
    $view_name = 'admin.student.lms.lms-series-list';
    return view($view_name, $data);
  }
  /**
  * This method displays the list of series available
  * @return [type] [description]
  */
  public function series()
  {
    if(checkRole(getUserGrade(2)))
    {
      return back();
    }
    $data['active_class']       = 'lms';
    $data['title']              = 'Khóa học';
    $data['layout']             = getLayout();
    $data['series']             = [];
    $user = Auth::user();
    $interested_categories      = null;
    if($user->settings)
    {
      $interested_categories =  json_decode($user->settings)->user_preferences;
    }
    if($interested_categories){
      if(count($interested_categories->lms_categories))
        $data['series']             = LmsSeries::
      where('start_date','<=',date('Y-m-d'))
      //->where('end_date','>=',date('Y-m-d'))
      //->whereIn('lms_category_id',(array) $interested_categories->lms_categories)
      ->paginate(getRecordsPerPage());
    }
    $data['user']               = $user;
    // return view('student.lms.lms-series-list', $data);
    $view_name = 'admin.student.lms.lms-series-list';
    return view($view_name, $data);
  }
  /**
  * This method displays all the details of selected exam series
  * @param  [type] $slug [description]
  * @return [type]       [description]
  */
        public function viewItem($slug, $content_slug='')
  {
    $record = LmsSeries::getRecordWithSlug($slug);
    if($isValid = $this->isValidRecord($record))
      return redirect($isValid);
    $content_record = FALSE;
    if($content_slug) {
      $content_record = LmsContent::getRecordWithSlug($content_slug);
      if($isValid = $this->isValidRecord($content_record))
        return redirect($isValid);
    }
    if($content_record){
      if($record->is_paid) {
        if(!isItemPurchased( $record->id, 'lms'))
        {
          prepareBlockUserMessage();
          return back();
        }
      }
    }
    $data['active_class']       = 'lms';
    $data['pay_by']             = '';
    $data['title']              = $record->title;
    $data['item']               = $record;
    $data['content_record']     = $content_record;
    $data['layout']              = getLayout();
    $view_name = 'admin.student.lms.series-view-item';
    return view($view_name, $data);
  }
    /**
     * This method displays the list of series available
     * @return [type] [description]
     */
        public function verifyPaidItem($slug, $content_slug)
  {
    if(!checkRole(getUserGrade(5)))
    {
      prepareBlockUserMessage();
      return back();
    }
    $record = LmsSeries::getRecordWithSlug($slug);
    if($isValid = $this->isValidRecord($record))
      return redirect($isValid);
    $content_record = LmsContent::getRecordWithSlug($content_slug);
    if($isValid = $this->isValidRecord($content_record))
      return redirect($isValid);
    if($content_record){
      if($record->is_paid) {
        if(!isItemPurchased($record->id, 'lms'))
        {
          return back();
        }
        else{
          $pathToFile= "public/uploads/lms/content"."/".$content_record->file_path;
          return Response::download($pathToFile);
        }
      }
      else{
        $pathToFile= "public/uploads/lms/content"."/".$content_record->file_path;
        return Response::download($pathToFile);
      }
    }
    else{
      flash('Ooops','File Does Not Exit','overlay');
      return back();
    }
  }
    /**
     * This method displays the list of series available
     * @return [type] [description]
     */
        public function content(Request $request, $req_content_type)
  {
    $content_type = $this->getRequestContentType($req_content_type);
    $category = FALSE;
    $query = LmsContent::where('content_type', '=', $content_type)
    ->where('is_approved',1);
    if($request->has('category')){
      $category = $request->category;
      $category_record = Lmscategory::getRecordWithSlug($category);
      $query->where('category_id',$category_record->id);
    }
    $data['category'] = $category;
    $data['content_type'] = $req_content_type;
    $data['list'] = $query->get();
    // dd($data['list']);
    $data['active_class']       = 'lms';
    $data['title']              = $req_content_type;
    $data['categories']         = Lmscategory::all();
    // return view('student.lms.content-categories', $data);
    $view_name = 'admin.student.lms.content-categories';
    return view($view_name, $data);
  }
    /**
     * This method displays the list of series available
     * @return [type] [description]
     */
        public function getRequestContentType($type)
  {
    if($type == 'video-course' || $type == 'video-courses')
      return 'vc';
    if($type == 'community-links')
      return 'cl';
    return 'sm';
  }
    /**
     * This method displays the list of series available
     * @return [type] [description]
     */
        public function getContentTypeFullName($type)
  {
    if($type=='sm')
      return 'study-materal';
    if($type=='vc')
      return 'video-courses';
    return 'community-links';
  }
    /**
     * This method displays the list of series available
     * @return [type] [description]
     */
        public function showContent($slug)
  {
    $record = Lmscontent::getRecordWithSlug($slug);
    if($isValid = $this->isValidRecord($record))
      return redirect($isValid);
    $data['active_class']       = 'lms';
    $data['title']              = $record->title;
    $data['category']           = $record->category;
    $data['record']             = $record;
    $data['content_type']     = $this->getContentTypeFullName($record->content_type);
    $data['series']       = array();
    if($record->is_series){
      $parent_id = $record->id;
      if($record->parent_id != 0)
        $parent_id = $record->parent_id;
      $data['series']     = LmsContent::where('parent_id', $parent_id)->get();
    }
    // return view('student.lms.show-content', $data);
    $view_name = 'admin.student.lms.show-content';
    return view($view_name, $data);
  }
    /**
     * This method displays the list of series available
     * @return [type] [description]
     */
    public function dequy_showLesson($data){
        $download ="";
        $combo_slug = $data['combo_slug'];
        $records = $data['content_q'];
        $content_view = $data['content_view'];
        $stt = $data['stt'];
        $slug = $data['slug'];
        $parent_id = $data['parent_id'];
        $lms_type = $data['lms_type'];
        $result = [];
        $array_video = ['1','2','6','9','10'];
        $finished_content_ids = $this->finished_content_ids;
        $viewed_content_ids = $this->viewed_content_ids;

        foreach ($records as $key => $r){
            $is_content_finished = in_array($r->id, $finished_content_ids);
            $is_content_viewed = in_array($r->id, $viewed_content_ids);
            // Check if content is child of current parent content
            if ($r->parent_id == $parent_id){
                $class_color = ($is_content_finished || $lms_type == 1)
                    ? '#28a745'
                    : ($is_content_viewed ? '#e62020' :'#9e9e9e');
                $is_active = ($r->id == $stt) ? 'lesson_active' : null;

                // Get the Icon and video url based on content type and viewed status
                if(in_array($r->type, $array_video)){
                    $i_tag = ($is_content_finished || $lms_type == 1)
                        ? '<img src="/public/assets/images/icon-seriess/play.png" style="width: 20px;margin-right: 5px;" alt="play">'
                        : '<img src="/public/assets/images/icon-seriess/sand-clock.png" style="width: 20px;margin-right: 5px;" alt="sand-clock">' ;
                    $video_url = PREFIX."learning-management/lesson/show/$combo_slug/$slug/".$r->id;
                }else{
                    $i_tag = ($is_content_finished)
                        ? '<img src="/public/assets/images/icon-seriess/checklist.png" style="width: 20px;margin-right: 5px;" alt="checklist">'
                        : '<img src="/public/assets/images/icon-seriess/sand-clock.png" style="width: 20px;margin-right: 5px;" alt="sand-clock">';
                    $video_url = PREFIX."learning-management/lesson/exercise/$combo_slug/$slug/".$r->id;
                }
                if($r->type == 5){
                    $i_tag = ($is_content_finished || $lms_type == 1)
                        ? '<img src="/public/assets/images/icon-seriess/compliant.png" style="width: 20px;margin-right: 5px;" alt="checklist">'
                        : '<img src="/public/assets/images/icon-seriess/sand-clock.png" style="width: 20px;margin-right: 5px;" alt="sand-clock">';
                    $video_url = PREFIX.'learning-management/lesson/audit/'.$combo_slug.'/'.$slug.'/'.$r->id;
                }
                if($r->type == 10){
                    $i_tag = ($is_content_finished || $lms_type == 1)
                        ? '<img src="/public/assets/images/icon-seriess/flash-cards.png" style="width: 20px;margin-right: 5px;" alt="checklist">'
                        : '<img src="/public/assets/images/icon-seriess/sand-clock.png" style="width: 20px;margin-right: 5px;" alt="sand-clock">';
                    $video_url = PREFIX.'learning-management/lesson/flashcard/'.$combo_slug.'/'.$slug.'/'.$r->id;
                }
                if ($r->type == 9) {
                    $class_color = ($is_content_finished || $lms_type == 1)
                        ? '#28a745'
                        : ($is_content_viewed ? '#e62020' : '');
                    $i_tag =
                        '<img src="/public/assets/images/icon-seriess/play.png" style="width: 20px;margin-right: 5px;" alt="play">';
                    $video_url = PREFIX . "learning-management/lesson/show/$combo_slug/$slug/" . $r->id;
                }

                // Download
                $download = "";
                if($r->type == 8){
                    if(isset($r->download_doc) && !empty($r->download_doc)) {
                      $download = '<a href="' . PREFIX . $r->download_doc . '" alt="download tài liệu" class="a_download" target="_blank"><i style="color: #ec296b; position: absolute;bottom: 6px; right: 20px;"  class="fa fa-download float-right pt-2 download_doc" aria-hidden="true"></i></a>';
                    }
                    $i_tag = '<i style="color: '.$class_color.'" class="fa fa-angle-double-right" aria-hidden="true"></i>';
                }

                // Each content item has 3 keys: tag, level, type
                // Check if the main lesson
                if ($r->parent_id == null) {
                    // Check if the compilation video or the audit
                    if($r->type == 9 || $r->type == 5){
                        $result[$r->id]['tag'] =
                            "<li>
                                <h3>
                                    <a class=\"none-after $is_active\" href=\"$video_url\" style=\"color: $class_color\">
                                        $i_tag
                                        {$r->bai}
                                    </a>
                                </h3>
                                <ul>";
                        $result[$r->id]['level'] = '0';
                        $result[$r->id]['type'] = $r->type;
                    }
                    else {
                        $i_tag = ($r->type == 5)
                            ? $i_tag
                            : '<img src="/public/assets/images/icon-seriess/books.png" style="width: 20px;margin-right: 5px;" alt="books">';

                        $video_url = ($r->type == 5)
                            ? $video_url
                            : 'javascript:void(0)';

                        $result[$r->id]['tag'] =
                        "<li>
                            <h3>
                                <a href=\"$video_url\">
                                    $i_tag
                                    {$r->bai}
                                </a>
                            </h3>
                            <ul>";
                        $result[$r->id]['level'] = '0';
                        $result[$r->id]['type'] = $r->type;
                    }
                }
                // If not main lesson
                else {
                    // Check if lesson section
                    if ($r->type == '8') {
                        $finished_child_content = $this->lms_contents
                            ->where('parent_id', $r->id)
                            ->pluck('id')
                            ->toArray();
                        $finished_and_child_content_diff = array_diff(
                            $finished_child_content,
                            $this->finished_content_ids
                        );
                        $class_color = empty($finished_and_child_content_diff)
                            ? '#28a745'
                            : $class_color;

                        $result[$r->id]['tag'] = "
                        <li>
                            <a href=\"javascript:void(0)\" style=\"color: $class_color;\">$i_tag{$r->bai}</a>
                            $download
                            <ul>";
                        $result[$r->id]['level'] = '1';
                        $result[$r->id]['type'] = $r->type;
                    }
                    else {
                        $result[$r->id]['tag'] = "
                            <li class=\"{$is_active}\">
                                <a href=\"{$video_url}\" style=\"color: {$class_color}\">
                                    {$i_tag}{$r->bai}
                                </a>
                            </li>";
                        $result[$r->id]['level'] = '2';
                        $result[$r->id]['type'] = $r->type;
                    }
                }
                unset($records[$key]);
                $child = $this->dequy_showLesson([
                    'content_q' => $records, 'content_view' => $content_view,
                    'stt' => $stt, 'slug' => $slug, 'parent_id' => $r->id,
                    'combo_slug' => $combo_slug,'lms_type' => $lms_type
                ]);
                $result = $result + $child;
            }
        }
        return $result;
    }
    /**
     * This method displays the list of series available
     * @return [type] [description]
     */
    public function dequy_tryshowLesson($data){
        // ['content_q' => $content_q, 'content_view' => $content_view,'stt' => $stt, 'slug' => $slug]
        $combo_slug = $data['combo_slug'];
        $records = $data['content_q'];
        $content_view = $data['content_view'];
        //dump($content_view);
        $stt = $data['stt'];
        $slug = $data['slug'];
        $parent_id = $data['parent_id'];
        $result = [];
        $array_video = ['1','2','6','9'];
        $array_loop = ['1','2','3','4','6','7'];
        $is_loop = false;
        foreach ($records as $key => $r){
            if ($r->parent_id == $parent_id){
                # pre check
                $class_color = ($r->el_try == 1  ) ? '#2a93e2' : null;
                $class_i_color = ($r->el_try == 1 ) ? '#2a93e2' : '#000000';
                $is_active = ($r->id == $stt) ? 'lesson_active' : null;
                if(in_array($r->type, $array_video)){
                    $i_tag = '<img src="/public/assets/images/icon-seriess/play.png" style="width: 20px;margin-right: 5px;" alt="play">';
                    $video_url = ($r->el_try == 1)
                        ? PREFIX."learning-management/lesson/show/$combo_slug/$slug/".$r->id : 'javascript:void(0)';
                    $l_tag = ($r->el_try != 1) ? 'Nội dung bị ẩn <i style="color: #000;position: absolute;top: -2px;right: 20px;" class="fa fa-lock float-right pt-2" aria-hidden="true"></i>' : $r->bai;
                    $o_tag = ($r->el_try != 1) ? 'onclick="showpayment()"' : null;
                }else{
                    $i_tag = '<img src="/public/assets/images/icon-seriess/checklist.png" style="width: 20px;margin-right: 5px;" alt="checklist">';
                    $video_url = ($r->el_try == 1)
                        ? PREFIX."learning-management/lesson/exercise/$combo_slug/$slug/".$r->id : 'javascript:void(0)';
                    $l_tag = ($r->el_try != 1) ? 'Nội dung bị ẩn <i style="color: #000;position: absolute;top: -2px;right: 20px;" class="fa fa-lock float-right pt-2" aria-hidden="true"></i>' : $r->bai;
                    $o_tag = ($r->el_try != 1) ? 'onclick="showpayment()"' : null;
                }
                if($r->type == 5){
                    $i_tag = '<img src="/public/assets/images/icon-seriess/compliant.png" style="width: 20px;margin-right: 5px;" alt="compliant">';
                    $video_url = ($r->el_try == 1)
                        ? PREFIX.'learning-management/lesson/audit/'.$combo_slug.'/'.$slug.'/'.$r->id : 'javascript:void(0)';
                    $l_tag = ($r->el_try != 1) ? 'Nội dung bị ẩn <i style="color: #000;position: absolute;top: -2px;right: 20px;" class="fa fa-lock float-right pt-2" aria-hidden="true"></i>' : $r->bai;
                    $o_tag = ($r->el_try != 1) ? 'onclick="showpayment()"' : null;
                }
                if($r->type == 10){
                    $i_tag = '<img src="/public/assets/images/icon-seriess/flash-cards.png" style="width: 20px;margin-right: 5px;" alt="compliant">';
                    $video_url = ($r->el_try == 1)
                        ? PREFIX.'learning-management/lesson/flashcard/'.$combo_slug.'/'.$slug.'/'.$r->id : 'javascript:void(0)';
                    $l_tag = ($r->el_try != 1) ? 'Nội dung bị ẩn <i style="color: #000;position: absolute;top: -2px;right: 20px;" class="fa fa-lock float-right pt-2" aria-hidden="true"></i>' : $r->bai;
                    $o_tag = ($r->el_try != 1) ? 'onclick="showpayment()"' : null;
                }
                $i_viewed = ($r->el_try == 1)
                    ? '<i class="fa fa-check" style="color: green !important"></i>' : null;
                // if($r->type == 8){
                //     $i_tag = '<i class="fa fa-angle-double-right" aria-hidden="true"></i>';
                // }
                $download = '';
                if($r->type == 8){
                    if(isset($r->download_doc) && !empty($r->download_doc)) {
                      $download = '<a href="'.PREFIX.$r->download_doc.'" alt="download tài liệu" class="a_download" target="_blank"><i style="color: #ec296b; position: absolute;bottom: 6px; right: 20px;"  class="fa fa-download float-right pt-2 download_doc" aria-hidden="true"></i></a>';
                    }
                    $i_tag = '<i class="fa fa-angle-double-right" aria-hidden="true"></i>';
                }
               /* if($r->id == $stt){
                    if(!in_array($r->type,$array_video)){
                        return redirect(PREFIX."learning-management/lesson/show/$combo_slug/$slug/".$pre_lesson);
                    }
                }*/
                # end pre check
                if($r->parent_id == null){
                    $i_tag = ($r->type == 5) ? $i_tag : '<img src="/public/assets/images/icon-seriess/books.png" style="width: 20px;margin-right: 5px;" alt="books">';
                    $video_url = ($r->type == 5) ? $video_url : 'javascript:void(0)';
                    // $open_ul = ($r->type == 5) ? '<ul>' : '<ul>';
                    $result[$r->id]['tag'] = '
                                          <li>
                                          <h3>
                                          <a href="'.$video_url.'">
                                          '.$i_tag.'
                                          '.$r->bai.' 
                                          </a>
                                          </h3><ul>
                                          ';
                    $result[$r->id]['level'] = '0';
                    $result[$r->id]['type'] = $r->type;
                    if($r->type == 9){
                        $o_tag = ($r->el_try != 1) ? 'onclick="showpayment()"' : null;
                        $i_tag = '<img src="/public/assets/images/icon-seriess/play.png" style="width: 20px;margin-right: 5px;" alt="play">';
                        $video_url = ($r->el_try == 1)
                            ? PREFIX."learning-management/lesson/show/$combo_slug/$slug/".$r->id :  'javascript:void(0)';
                        $l_tag = ($r->el_try != 1) ? 'Nội dung bị ẩn <i style="color: #000;position: absolute;top: -2px;right: 20px;" class="fa fa-lock float-right pt-2" aria-hidden="true"></i>' 
                        : $r->bai;
                    if($r->el_try != 1)
                    {
                        $result[$r->id]['tag'] = '
                        <li style ="position:relative; " >
                        <h3>
                        <a '.$o_tag.' class="none-after '.$is_active.'" href="'.$video_url.'">
                        '.$i_tag.'
                        '.$l_tag.'
                        </a>
                        </h3><ul>
                        ';

                    } else {
                        $result[$r->id]['tag'] = '
                        <li style ="position:relative; " >
                        <h3>
                        <a '.$o_tag.' class="none-after '.$is_active.'" href="'.$video_url.'">
                        '.$i_tag.'
                        '.$l_tag.'
                        </a>
                        </h3><ul>
                        ';
                    }

                        $result[$r->id]['level'] = '0';
                        $result[$r->id]['type'] = $r->type;
                    }
                }
                else
                {
                    if($r->type == '8'){
                        
                        // $result[$r->id]['tag'] = '<li>
                        // <a href="javascript:void(0)" style="color: '.$class_color.'">'.$i_tag.$r->bai .'</a>
                        // <ul>';
                        $result[$r->id]['tag'] = '<li>
                        <a href="javascript:void(0)" style="color: '.$class_color.'">'.$i_tag.$r->bai.'</a>'.$download.'<ul>
                        ';
                        $result[$r->id]['level'] = '1';
                        $result[$r->id]['type'] = $r->type;
                    }
                    else
                    {
                        $result[$r->id]['tag'] = '
                        <li style ="position:relative; " class="'.$is_active.'">
                        <a '.$o_tag.' href="'.$video_url.'" >
                        '.$i_tag.'
                        '.$l_tag.'
                        </a>
                        </li>
                        ';
                        $result[$r->id]['level'] = '2';
                        $result[$r->id]['type'] = $r->type;
                    }
                }
                unset($records[$key]);
                $child = $this->dequy_tryshowLesson(['content_q' => $records, 'content_view' => $content_view,
                    'stt' => $stt, 'slug' => $slug, 'parent_id' => $r->id,'combo_slug' => $combo_slug]);
                $result = $result + $child;
            }
        }
        return $result;
    }


    public function check_first_lesson($slug) {

      $check_viewed = DB::table('lms_student_view')
              ->join('lmscontents','lmscontents.id','=','lms_student_view.lmscontent_id')
              ->join('lmsseries','lmsseries.id','=','lmscontents.lmsseries_id')
              ->where([
                  ['users_id',Auth::id()],
                  ['lmsseries.slug',$slug],
                  ['lms_student_view.finish',1],
                  ['lmscontents.delete_status',0],
                  ['lmsseries.delete_status',0],
              ])
              ->whereNotIn('lmscontents.type', [0,8])
              ->select('lms_student_view.lmscontent_id')
              ->distinct('lms_student_view.lmscontent_id')
              ->count();
      if($check_viewed == 0) {
         $id_content = DB::table('lmscontents')
          ->select('lmscontents.*')
          ->join('lmsseries','lmsseries.id','=','lmscontents.lmsseries_id')
          ->where([
              ['lmsseries.slug',$slug],
              ['lmscontents.delete_status',0],
          ])
          ->whereNotIn('type',['0','8'])
          ->orderby('stt')
          ->first();

        return  array(
            'status'=>true,
            'data'=>$id_content
          );
      }

      return  array(
            'status'=>false,
            'data'=>''
          );
    }
    
    /**
     * This method displays the list of series available
     * @return [type] [description]
     */
  public function showLesson($combo_slug = '',$slug = '',$stt = '') {
    $data['comment_id'] = request()->get('comment_id');
    $data['active_class']       = 'exams';
    $data['title']              = 'Khóa học';
    $data['series']             = false;
    $data['layout']              = getLayout();
    $view_name = 'admin.student.lms.show-lesson';
    $data['current_series'] = DB::table('lmsseries')
    ->where([
      ['slug',$slug],
    ])->first();

    if (Auth::check()){
        $this->prepareContentIds($slug);
          $data['total_course'] = DB::table('lmsseries')
              ->join('lmscontents','lmsseries.id','=','lmscontents.lmsseries_id')
              ->where([
                  ['lmsseries.delete_status',0],
                  ['lmsseries.slug',$slug],
                  ['lmscontents.delete_status',0],
              ])
              ->whereNotIn('lmscontents.type', [0,8])
              ->distinct()
              ->get()->count();
          $data['current_course'] = DB::table('lms_student_view')
              ->join('lmscontents','lmscontents.id','=','lms_student_view.lmscontent_id')
              ->join('lmsseries','lmsseries.id','=','lmscontents.lmsseries_id')
              ->where([
                  ['users_id',Auth::id()],
                  ['lmsseries.slug',$slug],
                  ['lms_student_view.finish',1],
                  ['lmscontents.delete_status',0],
                  ['lmsseries.delete_status',0],
              ])
              ->whereNotIn('lmscontents.type', [0,8])
              ->select('lms_student_view.lmscontent_id')
              ->distinct('lms_student_view.lmscontent_id')
              ->get()->count();
      }
    $data['hi_combo'] = DB::table('lmsseries_combo')
        ->where('slug',$combo_slug)
        ->where('delete_status',0)
        ->first();
    if (!Auth::check()){
        $data['url_categories'] = PREFIX.'lms/exam-categories/list';
        $content_q = DB::table('lmscontents')
            ->select('lmscontents.*')
            ->join('lmsseries','lmsseries.id','=','lmscontents.lmsseries_id')
            ->where([
                ['lmsseries.slug',$slug],
                ['lmscontents.delete_status',0]
            ])
            ->orderBy('stt','asc')
            ->get();

        $check_hocthu  = DB::table('lmscontents')
            ->join('lmsseries','lmsseries.id','=','lmscontents.lmsseries_id')
            ->where([
                ['lmsseries.delete_status',0],
                ['lmsseries.slug',$slug],
                ['lmscontents.delete_status',0],
                ['lmscontents.el_try',1]
            ])
            ->distinct()
            ->count();

        if ($check_hocthu == 0){
            flash('Yêu cầu sở hữu khóa học', 'Vui lòng sở hữu khóa học để xem nội này', 'error');
            return redirect('/home');
        }
        $data['hi_koi'] = DB::table('lmsseries')->select('lmsseries.*')  ->where([
            ['lmsseries.slug',$slug],
        ]) ->get()->first();
        if($data['hi_koi']->type_series == 1){
            $data['title']              = 'Khóa luyện thi';
        }
        # check viewd lesson + time view
        $content_view = DB::table('lmscontents')
            ->select(['lmscontents.*'])
            ->join('lmsseries','lmsseries.id','=','lmscontents.lmsseries_id')
            ->where([
                ['lmsseries.slug',$slug],
                ['lmscontents.delete_status',0]
            ])
            ->whereNotIn('type',['0','8'])
            ->orderBy('stt','asc')
            ->get();
        if ($stt == ''){
            $stt = $content_view[0]->id;
        }
        $cur_stt = $stt;
        $data['current_lesson'] = '';
        $array_video = ['1','2','6','9', '10'];
        $array_loop = ['1','2','3','4','6','7'];

        foreach($content_q as $r){
            if($r->id < $stt && in_array($r->type, $array_video)){
                $pre_lesson = $r->id;
            }
            if($r->id == $cur_stt){
                if(!in_array($r->type,$array_video)){
                    return redirect(PREFIX."learning-management/lesson/show/$slug/".$pre_lesson);
                }
                $class_color = '#e62020';
                $data['current_lesson'] = ($r->title == null) ? $r->bai : $r->bai ;
                $data['current_description'] = $r->description;
                $data['current_video'] = ($r->el_try == 1 ? $r->file_path : null );
                $data['current_poster'] = IMAGE_PATH_UPLOAD_LMS_CONTENTS. $r->image;
                $data['contentslug'] = $r->id;
                $data['url_excer'] = PREFIX.'learning-management/lesson/exercise/'.$slug.'/'.$r->id;
                //dump($r->el_try);
            }
        }
        $new = $this->dequy_tryshowLesson(['content_q' => $content_q,
            'content_view' => $content_view,'stt' => $stt, 'slug' => $slug, 'parent_id' => null,'combo_slug' => $combo_slug,]);
        $lesson = '';
        $is_loop = false;
        $i = 0;
        foreach($new as $r){
            if(!in_array($r['type'], $array_loop) && $is_loop === true){
                $is_loop = 'end';
            }
            if($r['level'] == '0' && $i > 0){
                $lesson .= '</ul>';
            }
            if($is_loop === 'end'){
                $lesson .= '</ul></li>';
                $is_loop = false;
            }
            if($r['type'] != 8){
                $lesson .= $r['tag'];
            }elseif ($r['type'] == 8) {
                $lesson .= $r['tag'];
                $is_loop = true;
            }
            $i++;
        }
        $data['lesson_menu'] = $lesson;
        //die();
    }else{
        if(Auth::user()->role_id == 6){
          
            $_00content = DB::table('lmscontents')
                ->select('lmscontents.*')
                ->join('lmsseries','lmsseries.id','=','lmscontents.lmsseries_id')
                ->where([
                    ['lmsseries.slug',$slug],
                ])
                ->whereNotIn('type',['0','8'])
                ->get();
     
            foreach($_00content as $r){
                $x = DB::table('lms_student_view')
                    ->updateOrInsert(
                        [
                            'lmscontent_id' => $r->id,
                            'users_id'      => Auth::id(),
                        ],
                        [
                            'finish'        => 1,
                            'type'          => $r->type,
                        ]
                    );
            }
        }
        $data['url_categories'] = PREFIX.'lms/exam-categories/list';
        $content_q = DB::table('lmscontents')
            ->select('lmscontents.*')
            ->join('lmsseries','lmsseries.id','=','lmscontents.lmsseries_id')
            ->where([
                ['lmsseries.slug',$slug],
                ['lmscontents.delete_status',0]
            ])
            ->orderBy('stt','asc')
            ->get();

        $data['hi_koi'] = DB::table('lmsseries')->select('lmsseries.*')  ->where([
            ['lmsseries.slug',$slug],
        ]) ->get()->first();
        if($data['hi_koi'] != null && $data['hi_koi']->type_series == 1){
            $data['title']              = 'Khóa luyện thi';
        }
        $data['checkpay'] = DB::table('payment_method')
            ->select(DB::raw("(SELECT COUNT(id)  FROM payment_method WHERE payment_method.item_id 
                  = ".$data['hi_combo']->id."  AND payment_method.user_id = ".Auth::id()."  AND payment_method.status = 1 
                     AND  DATE_ADD(responseTime, INTERVAL IF(".$data['hi_combo']->time." = 0,90,IF(".$data['hi_combo']->time." = 1,180,365)) DAY) > NOW()) as payment"))
            ->distinct()
            ->first();


        if ($data['hi_combo']->cost == 0  || $data['checkpay']->payment > 0 || Auth::user()->role_id == 6){

            if($data['hi_koi']->type_series == 1){
                $data['title']              = 'Khóa luyện thi';
                if ($stt == ''){
                    $id_content = DB::table('lmscontents')
                        ->select('lmscontents.*')
                        ->join('lmsseries','lmsseries.id','=','lmscontents.lmsseries_id')
                        ->where([
                            ['lmsseries.slug',$slug],
                            ['lmscontents.delete_status',0],
                        ])
                        ->whereNotIn('type',['0','8'])
                        ->first();
                }else{
                    $id_content = DB::table('lmscontents')
                        ->select('lmscontents.*')
                        ->where([
                            ['lmscontents.id',$stt],
                            ['lmscontents.delete_status',0],
                        ])
                        ->whereNotIn('type',['0','8'])
                        ->first();
                }
				$checkviews = null;
				if($id_content != null) {
					$checkviews= DB::table('lms_student_view')
						->where([
							['lms_student_view.lmscontent_id',$id_content->id],
							['lms_student_view.users_id',Auth::user()->id],
						])
						->get();
					if ($checkviews->isEmpty()){
						
						$new_id = DB::table('lms_student_view')
							->insertGetId([
								'lmscontent_id'       => $id_content->id,
								'users_id'            => Auth::id(),
								'view_time'           => 0,
								'finish'              => 0,
								'type'                => $id_content->type,
							]);
					}
				}


                $content_view = DB::table('lms_student_view')
                    ->select(['lms_student_view.*','lmscontents.stt','lmscontents.slug'])
                    ->join('lmscontents','lmscontents.id','=','lms_student_view.lmscontent_id')
                    ->join('lmsseries','lmsseries.id','=','lmscontents.lmsseries_id')
                    ->where([
                        ['users_id',Auth::id()],
                        ['lmsseries.slug',$slug],
                       /* ['finish',0],*/
                        ['lmscontents.delete_status',0]
                    ])
                    ->orderBy('stt','desc')
                    ->get();
                $data['viewed_video'] = true;
            }else{
                # check viewd lesson + time view
                $content_view = DB::table('lms_student_view')
                    ->select(['lms_student_view.*','lmscontents.stt','lmscontents.slug'])
                    ->join('lmscontents','lmscontents.id','=','lms_student_view.lmscontent_id')
                    ->join('lmsseries','lmsseries.id','=','lmscontents.lmsseries_id')
                    ->where([
                        ['users_id',Auth::id()],
                        ['lmsseries.slug',$slug],
                        ['finish',0],
                        ['lmscontents.delete_status',0]
                    ])
                    ->orderBy('stt','desc')
                    ->get();

                if(Auth::user()->role_id == 6) {
                    $content_view = DB::table('lms_student_view')
                      ->select(['lms_student_view.*','lmscontents.stt','lmscontents.slug'])
                      ->join('lmscontents','lmscontents.id','=','lms_student_view.lmscontent_id')
                      ->join('lmsseries','lmsseries.id','=','lmscontents.lmsseries_id')
                      ->where([
                          ['users_id',Auth::id()],
                          ['lmsseries.slug',$slug],
                          ['finish',1],
                          ['lmscontents.delete_status',0]
                      ])
                      ->orderBy('stt','desc')
                      ->get();
                    }
                # check first view or next lesson view
                if($content_view->isEmpty()){
                    # next lesson view
                    $viewed_content = DB::table('lms_student_view')
                        ->select(['lms_student_view.*','lmscontents.stt','lmscontents.slug'])
                        ->join('lmscontents','lmscontents.id','=','lms_student_view.lmscontent_id')
                        ->join('lmsseries','lmsseries.id','=','lmscontents.lmsseries_id')
                        ->where([
                            ['users_id',Auth::id()],
                            ['lmsseries.slug',$slug],
                            ['finish',1],
                            ['lmscontents.delete_status',0]
                        ])
                        ->orderBy('lmscontents.stt','desc')
                        ->get();

                        // dd($viewed_content);
                    if($viewed_content->isEmpty()){
                        # check first view
                        $id_content = DB::table('lmscontents')
                            ->select('lmscontents.*')
                            ->join('lmsseries','lmsseries.id','=','lmscontents.lmsseries_id')
                            ->where([
                                ['lmsseries.slug',$slug],
                                ['lmscontents.delete_status',0],
                            ])
                            ->whereNotIn('type',['0','8'])
                            ->orderby('stt')
                            ->first();
                            
                    }else{
                        # next lesson
                        $id_content = DB::table('lmscontents')
                            ->select('lmscontents.*')
                            ->join('lmsseries','lmsseries.id','=','lmscontents.lmsseries_id')
                            ->where([
                                ['lmsseries.slug',$slug],
                                ['lmscontents.stt','>',$viewed_content[0]->stt],
                                ['lmscontents.delete_status',0],
                            ])
                            ->whereNotIn('type',['0','8'])
                            ->get()->first();
                            
                    }
                    //Kiem tra id content
                    if($id_content != null){

                        $new_id = DB::table('lms_student_view')
                            ->insertGetId([
                                'lmscontent_id'       => $id_content->id,
                                'users_id'            => Auth::id(),
                                'view_time'           => 0,
                                'finish'              => 0,
                                'type'                => $id_content->type,
                            ]);
                        $content_view = DB::table('lms_student_view')
                            ->select(['lms_student_view.*','lmscontents.stt','lmscontents.slug'])
                            ->join('lmscontents','lmscontents.id','=','lms_student_view.lmscontent_id')
                            ->join('lmsseries','lmsseries.id','=','lmscontents.lmsseries_id')
                            ->where([
                                ['users_id',Auth::id()],
                                ['lmsseries.slug',$slug],
                                ['finish',0],
                                ['lmscontents.delete_status',0]
                            ])
                            ->orderBy('stt','desc')
                            ->get();
                    }else{
                        $content_view = DB::table('lms_student_view')
                            ->select(['lms_student_view.*','lmscontents.stt','lmscontents.slug'])
                            ->join('lmscontents','lmscontents.id','=','lms_student_view.lmscontent_id')
                            ->join('lmsseries','lmsseries.id','=','lmscontents.lmsseries_id')
                            ->where([
                                ['users_id',Auth::id()],
                                ['lmsseries.slug',$slug],
                                ['finish',1],
                                ['lmscontents.delete_status',0]
                            ])
                            ->orderBy('stt','desc')
                            ->get();
                    }
                }
                else{
                    $this->updateAndInsertContentView($slug, $stt);
                    $data['current_time'] = $content_view[0]->view_time;
                }
            }
            # check if come to page from series
            $pre_stt = $stt;
            if($stt == '' && !$content_view->isEmpty()){
                $stt = $content_view[0]->lmscontent_id;
            }else{
                $check_view = DB::table('lms_student_view')
                    ->select('finish')
                    ->join('lmscontents','lmscontents.id','=','lms_student_view.lmscontent_id')
                    ->where([
                        ['users_id',Auth::id()],
                        ['lmscontents.id',$stt],
                        ['lmscontents.delete_status',0]
                    ])
                    ->get();
            }
            # get lesson
            $check_end_view = DB::table('lmscontents')
                ->select(['lmscontents.stt','lmscontents.id'])
                ->join('lmsseries','lmsseries.id','=','lmscontents.lmsseries_id')
                ->where([
                    ['lmsseries.slug',$slug],
                    ['lmscontents.delete_status',0],
                ])
                ->whereNotIn('type',['0','8'])
                ->orderBy('stt','asc')
                ->get()->first();
            $cur_stt = $stt;
            if($check_end_view->id != null){
                if($stt == $check_end_view->id && $pre_stt == ''){
                    $check_first_view = DB::table('lmscontents')
                        ->select(['lmscontents.stt','lmscontents.id'])
                        ->join('lmsseries','lmsseries.id','=','lmscontents.lmsseries_id')
                        ->where([
                            ['lmsseries.slug',$slug],
                            ['lmscontents.delete_status',0],
                        ])
                        ->whereNotIn('type',['0','8'])
                        ->orderBy('stt','asc')
                        ->get()->first();
                    $cur_stt = $check_first_view->id;
                }
            }
            $lesson = [];
            $i_parent = 0;
            $data['current_lesson'] = '';
            $array_video = ['1','2','6','9'];
            $array_loop = ['1','2','3','4','6','7'];

            //dd($content_q);

            foreach($content_q as $r){
                if($r->id < $stt && in_array($r->type, $array_video)){
                    $pre_lesson = $r->id;
                }
                if($r->id == $cur_stt){
                    if(!in_array($r->type,$array_video)){
                        return redirect(PREFIX."learning-management/lesson/show/$combo_slug/$slug/".$pre_lesson);
                    }
                    $class_color = '#e62020';
                    $data['current_lesson'] = $r->bai ;
                    if(in_array($r->type, array(1,2,6))){
                        $parent_submenu = DB::table('lmscontents')->where('id',$r->parent_id)->first();
                        if($parent_submenu != null)
                        {
                            $parent_menu = DB::table('lmscontents')->where('id',$parent_submenu->parent_id)->first();
                            $data['current_lesson'] = $parent_menu->bai . ' <i style="color: #28a745" class="fa fa-angle-double-right" aria-hidden="true"></i>'. $parent_submenu->bai.' <i style="color: #28a745" class="fa fa-angle-double-right" aria-hidden="true"></i>'. $r->bai;
                        } else {
                            $data['current_lesson'] = $r->bai;
                        }
                    }
                    

                    $data['current_description'] = $r->description;
                    $data['current_video'] = $r->file_path;
                    $data['current_poster'] = IMAGE_PATH_UPLOAD_LMS_CONTENTS. $r->image;
                    $data['contentslug'] = $r->id;
                    $data['url_excer'] = PREFIX.'learning-management/lesson/exercise/'.$slug.'/'.$r->id;
                }
            }

            $new = $this->dequy_showLesson([
                    'content_q' => $content_q, 'content_view' => $content_view,
                    'stt' => $cur_stt, 'slug' => $slug, 'parent_id' => null,
                    'combo_slug' => $combo_slug,'lms_type' => $data['hi_koi']->type_series
                ]);
            $lesson = '';
            $is_loop = false;
            $i = 0;
            foreach($new as $r){
                if(!in_array($r['type'], $array_loop) && $is_loop === true){
                    $is_loop = 'end';
                }
                if($r['level'] == '0' && $i > 0){
                    $lesson .= '</ul>';
                }
                if($is_loop === 'end'){
                    $lesson .= '</ul></li>';
                    $is_loop = false;
                }
                if($r['type'] != 8){
                    $lesson .= $r['tag'];
                }elseif ($r['type'] == 8) {
                    $lesson .= $r['tag'];
                    $is_loop = true;
                }
                $i++;
            }
        }else{
            $check_hocthu  = DB::table('lmscontents')
                ->join('lmsseries','lmsseries.id','=','lmscontents.lmsseries_id')
                ->where([
                    ['lmsseries.delete_status',0],
                    ['lmsseries.slug',$slug],
                    ['lmscontents.delete_status',0],
                    ['lmscontents.el_try',1]
                ])
                ->distinct()
                ->count();
            if ($check_hocthu == 0){
                flash('Yêu cầu sở hữu khóa học', 'Vui lòng sở hữu khóa học để xem nội này', 'error');
                return redirect('/payments/lms/'.$combo_slug);
            }
            # check viewd lesson + time view
            $content_view = DB::table('lmscontents')
                ->select(['lmscontents.*'])
                ->join('lmsseries','lmsseries.id','=','lmscontents.lmsseries_id')
                ->where([
                    ['lmsseries.slug',$slug],
                    ['lmscontents.delete_status',0]
                ])
                ->whereNotIn('type',['0','8'])
                ->orderBy('stt','asc')
                ->get();
            if ($stt == ''){
                $stt = $content_view[0]->id;
            }
            $cur_stt = $stt;
            $data['current_lesson'] = '';
            // $array_video = ['1','2','6','9'];
            // $array_loop = ['1','2','3','4','6','7'];
			$array_video = ['1','2','6','9', '10'];
        	$array_loop = ['1','2','3','4','6','7'];
            foreach($content_q as $r){
                // if($r->id < $stt && in_array($r->type, $array_video)){
                //     $pre_lesson = $r->id;
                // }
                // if($r->id == $cur_stt){
                //     if(!in_array($r->type,$array_video)){
                //         return redirect(PREFIX."learning-management/lesson/show/$combo_slug/$slug/".$pre_lesson);
                //     }
                //     $class_color = '#e62020';
                //     $data['current_lesson'] = ($r->title == null) ? $r->bai : $r->bai ;
                //     $data['current_description'] = $r->description;
                //     $data['current_video'] = ($r->el_try == 1 ? $r->file_path : null );
                //     $data['current_poster'] = IMAGE_PATH_UPLOAD_LMS_CONTENTS. $r->image;
                //     $data['contentslug'] = $r->id;
                //     $data['url_excer'] = PREFIX.'learning-management/lesson/exercise/'.$slug.'/'.$r->id;

					
                // }

				if($r->id < $stt && in_array($r->type, $array_video)){
						$pre_lesson = $r->id;
				}
				if($r->id == $cur_stt){
					if(!in_array($r->type,$array_video)){
						return redirect(PREFIX."learning-management/lesson/show/$slug/".$pre_lesson);
					}
						$class_color = '#e62020';
						$data['current_lesson'] = ($r->title == null) ? $r->bai : $r->bai ;
						$data['current_description'] = $r->description;
						$data['current_video'] = ($r->el_try == 1 ? $r->file_path : null );
						$data['current_poster'] = IMAGE_PATH_UPLOAD_LMS_CONTENTS. $r->image;
						$data['contentslug'] = $r->id;
						$data['url_excer'] = PREFIX.'learning-management/lesson/exercise/'.$slug.'/'.$r->id;
						//dump($r->el_try);
					break;
				}
            }
            $new = $this->dequy_tryshowLesson(['content_q' => $content_q,
                'content_view' => $content_view,'stt' => $stt, 'slug' => $slug, 'parent_id' => null,'combo_slug' => $combo_slug,]);
            $lesson = '';
            $is_loop = false;
            $i = 0;
            foreach($new as $r){
                if(!in_array($r['type'], $array_loop) && $is_loop === true){
                    $is_loop = 'end';
                }
                if($r['level'] == '0' && $i > 0){
                    $lesson .= '</ul>';
                }
                if($is_loop === 'end'){
                    $lesson .= '</ul></li>';
                    $is_loop = false;
                }
                if($r['type'] != 8){
                    $lesson .= $r['tag'];
                }elseif ($r['type'] == 8) {
                    $lesson .= $r['tag'];
                    $is_loop = true;
                }
                $i++;
            }
        }
        // dd($lesson);
        $data['lesson_menu'] = $lesson;
        //print_r($lesson);
    }
    // get commments
      $data['comment'] = DB::table('comments')
          ->where([
              ['user_id',Auth::id()],
              ['lmsseries_id',$data['hi_koi']->id],
              ['lmscombo_id',$data['hi_combo']->id],
              ['lmscontent_id',$stt],
              ['parent_id',0],
          ])
          ->get();
      $data['comment_child'] = DB::table('comments')
          ->where([
              ['user_id',Auth::id()],
              ['lmsseries_id',$data['hi_koi']->id],
              ['lmscombo_id',$data['hi_combo']->id],
              ['lmscontent_id',$stt],
              ['parent_id','!=',0],
          ])
          ->get();

      // end get commments
          $data['slug'] = $stt;
          $data['series'] = $slug;
          $data['combo_slug'] = $combo_slug;
    return view($view_name, $data);
  }
    /**
     * This method displays the list of series available
     * @return [type] [description]
     */
    public function showCombo($slug){
        //$data['series'] =array();
        $record_combo = DB::table('lmsseries_combo')
            ->select('lmsseries_combo.n1','lmsseries_combo.n2','lmsseries_combo.n3','lmsseries_combo.n4','lmsseries_combo.n5')
            ->where('slug',$slug)
            ->distinct()
            ->get();
        $data['record_combo'] = DB::table('lmsseries_combo')
                ->where('slug',$slug)
                ->distinct()
                ->get()->first();
       // dd($data['record_combo']);
        $data_combo = array();
        for ($i = 1; $i <=5 ;$i ++){
            if ($record_combo[0]->{"n$i"} != null){
                $data_combo[] = $record_combo[0]->{"n$i"};
            }
        }
        $data['series'] = DB::table('lmsseries')
            ->select('lmsseries.*',DB::raw("(SELECT COUNT(id)  FROM lmscontents  
        WHERE lmscontents.delete_status = 0 AND type NOT IN(0,8) AND lmscontents.lmsseries_id = lmsseries.id) as lmscontents"),
                DB::raw("(SELECT COUNT(id)  FROM lmscontents  
        WHERE lmscontents.delete_status = 0 AND lmscontents.el_try = 1 
        AND type NOT IN(0,8) AND lmscontents.lmsseries_id = lmsseries.id) as try_lmscontents"),
                DB::raw("(0) as payment"))
            ->where([
                ['lmsseries.delete_status',0],
            ])
            ->whereIn('id',$data_combo)
            ->orderBy('order_by')
            ->distinct()
            ->get();
        if (Auth::check() && Auth::user()->role_id != 6  ){
            //dd(2);
            $data['checkpay'] = DB::table('payment_method')
                ->select(DB::raw("(SELECT COUNT(id)  FROM payment_method WHERE payment_method.item_id 
                  = ".$data['record_combo']->id."  AND payment_method.user_id = ".Auth::id()."  AND payment_method.status = 1
                   AND DATE_ADD(responseTime, INTERVAL IF(".$data['record_combo']->time." = 0,90,IF(".$data['record_combo']->time." = 1,180,365)) DAY) > NOW()) as payment"))
                ->distinct()
                ->get()->first();
            $data['series'] = DB::table('lmsseries')
                    ->select('lmsseries.*',DB::raw("(SELECT COUNT(id)  FROM lmscontents  
            WHERE lmscontents.delete_status = 0 AND type NOT IN(0,8) AND lmscontents.lmsseries_id = lmsseries.id) as lmscontents"),
                        DB::raw("(SELECT COUNT(id)  FROM lmscontents  
            WHERE lmscontents.delete_status = 0 AND lmscontents.el_try = 1 
            AND type NOT IN(0,8) AND lmscontents.lmsseries_id = lmsseries.id) as try_lmscontents"),
                        DB::raw("(SELECT COUNT(id)  FROM payment_method WHERE payment_method.item_id 
                      = ".$data['record_combo']->id." AND payment_method.user_id = ".Auth::id()."  AND payment_method.status = 1
                        AND DATE_ADD(responseTime, INTERVAL IF(".$data['record_combo']->time." = 0,90,IF(".$data['record_combo']->time." = 1,180,365)) DAY) > NOW()) as payment"))
                    ->where([
                        ['lmsseries.delete_status',0],
                    ])
                    ->whereIn('id',$data_combo)
                    ->orderBy('order_by')
                    ->distinct()
                    ->get();
        }
        if (Auth::check() && Auth::user()->role_id == 6){
            $data['series'] = DB::table('lmsseries')
                ->select('lmsseries.*',DB::raw("(SELECT COUNT(id)  FROM lmscontents  
        WHERE lmscontents.delete_status = 0 AND type NOT IN(0,8) AND lmscontents.lmsseries_id = lmsseries.id) as lmscontents"),
                    DB::raw("(SELECT COUNT(id)  FROM lmscontents  
        WHERE lmscontents.delete_status = 0 AND lmscontents.el_try = 1 
        AND type NOT IN(0,8) AND lmscontents.lmsseries_id = lmsseries.id) as try_lmscontents"),
                    DB::raw("(1) as payment"))
                ->where([
                    ['lmsseries.delete_status',0],
                ])
                ->whereIn('id',$data_combo)
                ->orderBy('order_by')
                ->distinct()
                ->get();
        }
        //dd($data['series']);
        $data['title']              = 'Khóa combo';
       // $data['series']             = false;
        $data['key']          = 'Combo';
        $data['active_class'] = 'Combo';
        $data['layout']              = getLayout();
        $view_name = 'admin.student.lms.showcombo';
        return view($view_name, $data);
    }
  /**
  * This method displays the list of series available
  * @return [type] [description]
  */
        public function updateTimeVideo(Request $request){
    if($request->ajax()){
        if (Auth::user() != null) {
            $record = DB::table('lmscontents')
                ->where('id', (string)$request->slug)
                ->select('stt', 'id')
                ->get();
            if (!$record->isEmpty()) {
                $affected = DB::table('lms_student_view')
                    ->where('users_id', Auth::id())
                    ->where('lmscontent_id', $record[0]->id)
                    /*   ->where('view_time','<=',(int)$request->currentTime)*/
                    ->update([
                        'view_time' => (int)$request->currentTime,
                    ]);
            }
        }
    }
  }

    /**
     * This method displays the list of series available
     * @return [type] [description]
     */
    public function finishTimeVideo(Request $request)
    {
        try {
            if ($request->ajax()) {
                DB::beginTransaction();
                if (Auth::user() != null) {
                    $lmsContentId = (int) $request->slug;
                    $rewardPoint = (int) $request->rewardPoint ?? 0;
                    $record = DB::table('lmscontents')
                    ->where('id', $lmsContentId)
                        ->select('stt', 'id')
                        ->get();
                    if (!$record->isEmpty()) {
                        $userId = Auth::id();

                        // Update point
                        if ($rewardPoint != 0) {
                            $this->userService->updatePoint($rewardPoint, $lmsContentId, $userId);

                            DB::table('lms_student_view')
                                ->where('users_id', $userId)
                                ->where('lmscontent_id', $lmsContentId)
                                ->update([
                                    'finish' => 1,
                                ]);
                        }
                        $this->userService->updateLoginStreak();
                    }
                }
                DB::commit();
            }
        } catch (Exception $e) {
            DB::rollBack();
        }
    }

    /**
     * This method displays the list of series available
     * @return [type] [description]
     */
   public function nextUrl(Request $request){
    if($request->ajax()){
      $finish = filter_var($request->finish, FILTER_VALIDATE_BOOLEAN);
      $sendUrl = null;
      $status = 0;
      $message = 'fail';
      $record =DB::table('lmscontents')
      ->where('id',(int) $request->slug)
      ->where('delete_status',0)
      ->select('stt','lmsseries_id')
      ->get();

      if ($finish) {
        DB::table('lms_student_view')
        ->where('users_id', Auth::id())
        ->where('lmscontent_id', $request->slug)
        ->update([
          'finish' => 1,
        ]);
      }

      if (!$record->isEmpty()){
        $recordurl = DB::table('lmscontents')
        ->where('stt','>=',((int)$record[0]->stt +1))
        ->where('lmsseries_id',$record[0]->lmsseries_id)
        ->where('delete_status',0)
        ->whereNotIn('type',[0,8])
        ->select('id','type')
        ->orderby('stt')
        ->first();
        if ($recordurl){
          switch ($recordurl->type) {
            case 1:
              $sendUrl = PREFIX.'learning-management/lesson/show/'.$request->combo.'/'.$request->series.'/'.$recordurl->id;
            case 2:
              /*$sendUrl = PREFIX.'learning-management/lesson/show/'.$request->combo.'/'.$request->series.'/'.$recordurl->id;*/
            case 6:
              $sendUrl = PREFIX.'learning-management/lesson/show/'.$request->combo.'/'.$request->series.'/'.$recordurl->id;
            break;
            case 3:
            case 4:
              $sendUrl = PREFIX.'learning-management/lesson/exercise/'.$request->combo.'/'.$request->series.'/'.$recordurl->id;
            break;
            case 5:
              $sendUrl = PREFIX.'learning-management/lesson/audit/'.$request->combo.'/'.$request->series.'/'.$recordurl->id;
            break;
            case 9:
              $sendUrl = PREFIX.'learning-management/lesson/show/'.$request->combo.'/'.$request->series.'/'.$recordurl->id;
            break;
            default:
              $sendUrl = null;
            break;
          }
          $status = 1;
          $message = 'ok';
        }
      }
      return response()->json([
        'url' => $sendUrl,
        'status' =>(int) $status,
        'message' => $message,
      ]);
    }
  }

    /**
     * This method displays the list of series available
     * @return [type] [description]
     */
  public function studentExercise(Request $request,$combo_slug = '',$series = '',$slug = ''){
      if (Auth::check() && Auth::user()->role_id != 6){
          $check = DB::table('lms_student_view')
              ->select('id')
              ->where('lmscontent_id',$slug)
              ->where('users_id',Auth::id())
              ->get();
          if ($check->isEmpty()){
              return redirect('home');
          }
          $data['hi_combo'] = DB::table('lmsseries_combo')
              ->where('slug',$combo_slug)
              ->where('delete_status',0)
              ->get()->first();
          $data['checkpay'] = DB::table('payment_method')
              ->select(DB::raw("(SELECT COUNT(id)  FROM payment_method WHERE payment_method.item_id 
                  = ".$data['hi_combo']->id."  AND payment_method.user_id = ".Auth::id()."     AND 
                  DATE_ADD(responseTime, INTERVAL IF(".$data['hi_combo']->time." = 0,90,IF(".$data['hi_combo']->time." = 1,180,365)) DAY) > NOW()) as payment"))
              ->distinct()
              ->get()->first();
          if ($data['checkpay']->payment == 0){
              return redirect('home');
          }
      }
    $records = DB::table('lmscontents')
    ->join('lms_exams','lms_exams.content_id','=','lmscontents.id')
    ->where('lmscontents.id',$slug)
    ->where('lmscontents.delete_status',0)
    ->where('lms_exams.delete_status',0)
     /*->where('lms_exams.dang','=',5)*/
     ->whereNotNull('lms_exams.dang')
    ->select('lms_exams.id','label','dang','cau','mota','dapan',DB::raw("CONCAT_WS('-,-',luachon1,luachon2,luachon3,luachon4) AS answers") )
    ->get();
    $data['name'] = DB::table('lmscontents')
    ->select('bai')
    ->where('lmscontents.id',$slug)
    ->first();
//      header('Content-type: text/html; charset=UTF-8') ;
//       foreach($records as $r){
//         //dump($r);
//
//           echo mb_convert_encoding(str_replace('A：','A。',$r->mota),"UTF-8","auto");
//           echo '<br>';
//       }
// die;
    if (!$records->isEmpty()){
      foreach ($records as $key => $value) {
            //$records[$key]->answers_furigana = explode(',', change_furigana(trim($value->answers,'return')));
        $records[$key]->mota = change_furigana( mb_convert_encoding(str_replace('＿＿','__',$value->mota),"UTF-8","auto"),'return');
        $records[$key]->answers = explode('-,-', trim($value->answers));
      }
      foreach ($records as $key => $record) {
        $valueAnswers = array();
        foreach ($record->answers as $answer){
          $valueAnswers[] = change_furigana( $answer,'return');
        }
        $records[$key]->answers = $valueAnswers;
      }
      /*$record5 = array();
      foreach ($records as $key => $record) {
        if ($record->dang == 5){
          if (count($record->answers) >1){
            $record5[] = $records[$key];
          }
          $records->forget($key);
                //unset($records[$key]);
        }
      }
        $reindex = count($records); //normalize index
        $count5 = count($record5);
        $record5 = array_merge(array('dang' => 5,'quest' => $record5));
        $records = $records->toArray();
        if ($count5 > 0 ){
            $records[$reindex] = (object) $record5;
          }*/
          $sendUrl = null;
          $recordback =DB::table('lmscontents')
          ->where('id',(int) $request->slug)
          ->select('stt','lmsseries_id')
          ->get();
          $recordurl = DB::table('lmscontents')
          ->where('stt','<',((int)$recordback[0]->stt))
          ->where('lmsseries_id',$recordback[0]->lmsseries_id)
         // ->where('el_try',1)
          ->whereNotIn('type',[3,4,5,0,8])
          ->select('id','type')
          ->orderBy('stt', 'desc')
          ->get();
            //dd($recordurl);
          if (!$recordurl->isEmpty()){
            $sendUrl = PREFIX.'learning-management/lesson/show/'.$combo_slug.'/'.$request->series.'/'.$recordurl[0]->id;
          }else{
            $sendUrl = PREFIX;
          }
        }
        if(!isset($sendUrl)){
          $sendUrl = PREFIX;
        }

        $data['class']       = 'exams';
        $data['title']              = 'Khóa học';
        $data['series']             = $series;
        $data['slug']                = $slug;
        $data['combo_slug']          = $combo_slug;
        $data['records']            = $records;
        $data['count_records']      = count($records);
        $data['back_url']            = $sendUrl;
        $data['sendUrl']        = $sendUrl;
        $data['layout']              = 'admin.layouts.exercise.exerciselayout';
        $view_name = 'admin.student.exercise.student-exercise';
        return view($view_name, $data);
      }
    /**
     * This method displays the list of series available
     * @return [type] [description]
     */
  public function studentAudit(Request $request,$combo_slug ='',$series = '',$slug = ''){
          if (Auth::check() && Auth::user()->role_id != 6){
              $check = DB::table('lms_student_view')
                  ->select('id')
                  ->where('lmscontent_id',$slug)
                  ->where('users_id',Auth::id())
                  ->get();
              if ($check->isEmpty()){
                  return redirect('home');
              }
              $data['hi_combo'] = DB::table('lmsseries_combo')
                  ->where('slug',$combo_slug)
                  ->where('delete_status',0)
                  ->get()->first();
              $data['checkpay'] = DB::table('payment_method')
                  ->select(DB::raw("(SELECT COUNT(id)  FROM payment_method WHERE payment_method.item_id 
                  = ".$data['hi_combo']->id."  AND payment_method.user_id = ".Auth::id()."     AND 
                  DATE_ADD(responseTime, INTERVAL IF(".$data['hi_combo']->time." = 0,90,IF(".$data['hi_combo']->time." = 1,180,365)) DAY) > NOW()) as payment"))
                  ->distinct()
                  ->get()->first();
              if ($data['checkpay']->payment == 0){
                  return redirect('home');
              }
          }
        $records = DB::table('lmscontents')
        ->join('lms_test','lms_test.content_id','=','lmscontents.id')
        ->where('lmscontents.id',$slug)
        ->where('lmscontents.delete_status',0)
        ->where('lms_test.delete_status',0)
            ->whereNotNull('lms_test.dang')
        //->where('lms_test.dang','=',7)
        ->select('lms_test.id','dang','cau','mota','dapan','display',DB::raw("CONCAT_WS('-,-',luachon1,luachon2,luachon3,luachon4) AS answers") )
       /* ->orderBy('lms_test.cau')
       ->orderBy('lms_test.dang')*/
       ->orderBy('lms_test.id')
       ->get();
       if (!$records->isEmpty()){
        foreach ($records as $key => $value) {
            //$records[$key]->answers_furigana = explode(',', change_furigana(trim($value->answers,'return')));
          $records[$key]->mota = change_furigana( trim($value->mota),'return');
          if($records[$key]->dang == '7'){
            $records[$key]->mota = str_replace("\n", "\n\n", $records[$key]->mota);
          }
          $records[$key]->answers = explode('-,-', trim($value->answers));
        }
        foreach ($records as $key => $record) {
          $valueAnswers = array();
          foreach ($record->answers as $answer){
            $valueAnswers[] = change_furigana( $answer,'return');
          }
          $records[$key]->answers = $valueAnswers;
        }
      /*  $result = array();
        foreach($records as $key => $value){
          $result[$value->dang][] = (object) $value;
        }
        $records = $result;*/
           $sendUrl = null;
           $recordback =DB::table('lmscontents')
               ->where('id',(int) $request->slug)
               ->select('stt','lmsseries_id')
               ->get();
           $recordurl = DB::table('lmscontents')
               ->where('stt','<',((int)$recordback[0]->stt))
               ->where('lmsseries_id',$recordback[0]->lmsseries_id)
               ->whereNotIn('type',[3,4,5,0,8])
               ->select('id','type')
               ->orderBy('stt', 'desc')
               ->get()
           ;
           //dd($recordurl);
           if (!$recordurl->isEmpty()){
               $sendUrl = PREFIX.'learning-management/lesson/show/'.$combo_slug.'/'.$request->series.'/'.$recordurl[0]->id;
           }else{
               $sendUrl = PREFIX;
           }
      }
       if(!isset($sendUrl)){
                $sendUrl = PREFIX;
            }
            $data['name'] = DB::table('lmscontents')
                ->select('bai')
                ->where('lmscontents.id',$slug)
                ->first();
     // flash('Tải hoàn tất đề thi','Thông báo tự đóng sau 1s', 'success');
      $data['class']       = 'exams';
      $data['title']              = 'Khóa học';
      $data['series']             = $series;
      $data['slug']                =$slug;
      $data['combo_slug']                =$combo_slug;
      $data['back_url']            = $sendUrl;
      $data['records']            = $records;
      $data['layout']              = 'admin.layouts.exercise.exerciselayout';
      $view_name = 'admin.student.exercise.student-audit';
      return view($view_name, $data);
    }

    /**
     * This method displays the list of series available
     * @return [type] [description]
     */
  public function storeResut(Request $request,$combo_slug ='',$series = '',$slug = ''){
      if($request->isMethod('post')) {
        try {
         // flash('success','Thông báo tự đóng sau 1s', 'success');
              //$request->session()->flash('status', 'Task was successful!');
          $content_id = $request->content_id;
          $time = $request->time;
          $dataQuest = $request->all();
          unset($dataQuest['_token']);
          unset($dataQuest['content_id']);
          unset($dataQuest['time']);
          $records = DB::table('lmscontents')
          ->join('lms_test','lms_test.content_id','=','lmscontents.id')
          ->where('lmscontents.id',$slug)
          ->where('lmscontents.delete_status',0)
          ->where('lms_test.delete_status',0)
          ->select('lms_test.id','dang','cau','mota','dapan','diem','display',DB::raw("CONCAT_WS(',',luachon1,luachon2,luachon3,luachon4) AS answers") )
          ->orderBy('lms_test.id')
          ->get();
          $totalValue = 0;
          $point = 0;
          foreach ($records as $keyRecord => $valueRecord){
            $point = $point + $valueRecord->diem;
            $correct = 0;
            $check = 999;
            foreach ($dataQuest as $key => $value){
              $idKey = filter_var(str_replace('quest_','',$key),FILTER_SANITIZE_NUMBER_INT);
              if($valueRecord->id == $idKey){
                if ($valueRecord->dapan == $value){
                  $totalValue = (int) $totalValue + (int) $valueRecord->diem;
                  $correct = 1;
                }
                $check = $value;
                unset($dataQuest['$key']);
                break;
              }
            }
            $records[$keyRecord]->correct = $correct;
            $records[$keyRecord]->check = $check;
          }
          foreach ($records as $key => $value) {
            $records[$key]->mota = change_furigana( trim($value->mota),'return');
            $records[$key]->answers = explode(',', trim($value->answers));
          }
          foreach ($records as $key => $record) {
            $valueAnswers = array();
            foreach ($record->answers as $answer){
              $valueAnswers[] = change_furigana( $answer,'return');
            }
            $records[$key]->answers = $valueAnswers;
          }
             /* $result = array();
              foreach($records as $key => $value){
                  $result[$value->dang][] = (object) $value;
              }
              $records = $result;*/
             // dd($records);
              $passed = (int)$totalValue / (int)$point >= 0.6 ? 1 :0;
              $sendUrl = null;
              //$passed = 1;
              if ($passed >= 0.6){
                  if (Auth::user() != null) {
                      try {
                          DB::beginTransaction();
                          DB::table('lms_test_result')->insert([
                              'lmscontent_id' => $content_id,
                              'combo_slug' => $combo_slug,
                              'finish' => 1,
                              'total_point' => $point,
                              'users_id' => Auth::id(),
                              'point' => $totalValue,
                              'time_result' => $time,
                              'created_by' => Auth::id(),
                          ]);
                          DB::table('lms_student_view')
                              ->where('users_id', Auth::id())
                              ->where('lmscontent_id', $content_id)
                              ->update([
                                  'finish' => 1,
                              ]);
                          DB::commit();
                      } catch (Exception $e) {
                          DB::rollBack();
                      }
                  }
                $record =DB::table('lmscontents')
                ->where('id',(int) $request->slug)
                ->select('stt','lmsseries_id')
                ->get();
                if (!$record->isEmpty()){
                  $recordurl = DB::table('lmscontents')
                  ->where('stt','>=',((int)$record[0]->stt +1))
                  ->where('lmsseries_id',$record[0]->lmsseries_id)
                  ->whereNotIn('type',[0,8])
                  ->select('id','type')
                  ->first();
                  if (!empty($recordurl)){
                    switch ($recordurl->type) {
                      case 1:
                      case 2:
                      case 6:
                      $sendUrl = PREFIX.'learning-management/lesson/show/'.$combo_slug.'/'.$request->series.'/'.$recordurl->id;
                      break;
                      case 3:
                      case 4:
                      $sendUrl = PREFIX.'learning-management/lesson/exercise/'.$combo_slug.'/'.$request->series.'/'.$recordurl->id;
                      break;
                      case 5:
                      $sendUrl = PREFIX.'learning-management/lesson/audit/'.$combo_slug.'/'.$request->series.'/'.$recordurl->id;
                      break;
                      default:
                      $sendUrl = null;
                      break;
                    }
                  }
                }
              }
            $data['name'] = DB::table('lmscontents')
                ->select('bai')
                ->where('lmscontents.id',$slug)
                ->first();
              $data['class']              = 'exams';
              $data['title']              = 'Khóa học';
              $data['series']             = $series;
              $data['slug']                =$slug;
              $data['records']            = $records;
              $data['value']              = $totalValue;
              $data['point']              = $point;
              $data['combo_slug']                =$combo_slug;
              $data['back_url']            = $sendUrl;
              $data['sendUrl']            = $sendUrl;
              $data['passed']             = $passed;
              $data['layout']              = 'admin.layouts.exercise.exerciselayout';
              $view_name = 'admin.student.exercise.student-audit';
              //dd($sendUrl);
              return view($view_name, $data);
            }catch (Exception $e){
                 return  redirect(PREFIX.'learning-management/lesson/audit'.$combo_slug.'/'.$series.'/'.$slug);
            }
          }
        }
    /**
     * This method displays the list of series available
     * @return [type] [description]
     */
        public function lms_test_log(Request $request){
          if($request->ajax()){
            $content_id = $request->content_id;
            $time = $request->time;
            $dataQuest = $request->all();
            unset($dataQuest['_token']);
            unset($dataQuest['content_id']);
            unset($dataQuest['time']);
            $dataResult= array();
            foreach ($dataQuest as $key => $value){
              $idKey = filter_var(str_replace('quest_','',$key),FILTER_SANITIZE_NUMBER_INT);
              $dataResult[$idKey] = (int)$value;
            }
              if (Auth::user() != null){
                  try
                  {
                      DB::beginTransaction();
                      DB::table('lms_test_log')
                          ->updateOrInsert(
                              [
                                  'lmscontent_id' => $content_id,
                                  'users_id'       => Auth::id(),
                              ],
                              [
                                  'lmscontent_id' => $content_id,
                                  'users_id'       => Auth::id(),
                                  'time'  => $time,
                                  'result' => json_encode($dataResult),
                              ]
                          );
                      DB::commit();
                  }catch (Exception $e) {
                      DB::rollBack();
                  }
              }
          }
        }
    /**
     * This method displays the list of series available
     * @return [type] [description]
     */
       /* public function isValidRecord($record){
          if ($record === null) {
            flash('Ooops...!', getPhrase("page_not_found"), 'error');
            return $this->getRedirectUrl();
          }
          return FALSE;
        }*/
    /**
     * This method displays the list of series available
     * @return [type] [description]
     */
        public function getReturnUrl(){
          return URL_LMS_CONTENT;
        }
    /**
     * This method displays the list of series available
     * @return [type] [description]
     */
        public function dequy_showLesson_selected($data){
          $records = $data['content_q'];
          $content_view = $data['content_view'];
          $lesson_id = $data['stt'];
          $slug = $data['slug'];
          $parent_id = $data['parent_id'];
          $result = [];
          $array_video = ['1','2','6'];
          $array_loop = ['1','2','3','4','6','7'];
          $is_loop = false;
          foreach ($records as $key => $r){
            if ($r->parent_id == $parent_id){
        # pre check
              $class_color = '#2a93e2' ;
              $class_i_color = '#2a93e2' ;
              $is_active = ($r->id == $lesson_id) ? 'lesson_active' : null;
              if(in_array($r->type, $array_video)){
                $i_tag = '<i class="fa fa-play" aria-hidden="true"></i>';
                $video_url = PREFIX."learning-management/lesson-selected/show/$slug/".$r->id;
              }else{
                $i_tag = '<i class="fa fa-file-text-o" aria-hidden="true"></i>';
                $video_url = PREFIX."learning-management/lesson/exercise/$slug/".$r->id;
              }
              if($r->type == 5){
                $i_tag = '<i class="fa fa-star"></i>';
                $video_url = PREFIX.'learning-management/lesson/audit/'.$slug.'/'.$r->id;
              }
              $i_viewed = '<i class="fa fa-check" style="color: green !important"></i>';
              if($r->type == 8){
                $i_tag = '<i class="fa fa-angle-double-right" aria-hidden="true"></i>';
              }
              if($r->id == $lesson_id){
                if(!in_array($r->type,$array_video)){
                  return redirect(PREFIX."learning-management/lesson-selected/show/$slug/".$pre_lesson);
                }
              }
        # end pre check
              if($r->parent_id == null){
                $i_tag = ($r->type == 5) ? $i_tag : '<i class="fa fa-book" aria-hidden="true"></i>';
                $video_url = ($r->type == 5) ? $video_url : '#';
                $result[$r->id]['tag'] = '
                <li>
                <h3>
                <a href="'.$video_url.'">
                '.$i_tag.'
                '.$r->bai.' 
                </a>
                </h3><ul>
                ';
                $result[$r->id]['level'] = '0';
                $result[$r->id]['type'] = $r->type;
              }else{
                if($r->type == '8'){
                  $result[$r->id]['tag'] = '<li>
                  <a href="#" style="color: '.$class_color.'">'.$i_tag.$r->bai.'</a><ul>
                  ';
                  $result[$r->id]['level'] = '1';
                  $result[$r->id]['type'] = $r->type;
                }else{
                  $result[$r->id]['tag'] = '
                  <li class="'.$is_active.'">
                  <a href="'.$video_url.'" style="color: '.$class_color.'">'
                  .$i_tag.$r->bai.'
                  </a>
                  </li>
                  ';
                  $result[$r->id]['level'] = '2';
                  $result[$r->id]['type'] = $r->type;
                }
              }
              unset($records[$key]);
              $child = $this->dequy_showLesson_selected(['content_q' => $records, 'content_view' => $content_view,'stt' => $lesson_id, 'slug' => $slug, 'parent_id' => $r->id]);
              $result = $result + $child;
            }
          }
          return $result;
        }
    /**
     * This method displays the list of series available
     * @return [type] [description]
     */
        public function showLessonSelected($slug = '',$lesson_id = ''){
          $data['active_class']       = 'exams';
          $data['title']              = 'Khóa luyện thi';
          $data['series']             = false;
          $data['layout']              = getLayout();
          $view_name = 'admin.student.lms.show-lesson-selected';
          $data['current_series'] = DB::table('lmsseries')
          ->where([
            ['slug',$slug],
          ])->first();
          if($slug == ''){
            // flash('Ooops...!', getPhrase("page_not_found"), 'error');
            return back();
          }
          $data['url_categories'] = PREFIX.'lms/exam-categories/list';
          $parent_q = DB::table('lms_class_series_data')
          ->select('lmscontents.*')
          ->join('lmscontents','lmscontents.id','=','lms_class_series_data.content_id')
          ->join('lms_class_series','lms_class_series.id','=','lms_class_series_data.class_series_id')
          ->join('lmsseries','lmsseries.id','=','lmscontents.lmsseries_id')
          ->join('classes','classes.id','=','lms_class_series.class_id')
          ->join('classes_user','classes.id','=','classes_user.classes_id')
          ->where([
            ['lmsseries.slug',$slug],
            ['lmscontents.delete_status',0],
            ['lmsseries.delete_status',0],
            ['classes_user.student_id',Auth::id()],
            // ['lms_class_series_data.show_status',1]
          ])
          ->get();
          $list_parent = [];
          $content_q = [];
          $array_video = ['1','2','6'];
          foreach($parent_q as $r){
            $list_parent[] = $r->id;
          }
          if($list_parent != []){
            $full_content = DB::table('lmscontents')
            ->select('lmscontents.*')
            ->join('lmsseries','lmsseries.id','=','lmscontents.lmsseries_id')
            ->where([
              ['lmsseries.slug',$slug],
              ['lmscontents.delete_status',0]
            ])
            ->orderBy('stt')
            ->get();
            foreach($full_content as $r){
              if(in_array($r->id, $list_parent) || in_array($r->parent_id, $list_parent)){
                $content_q[] = $r;
                $list_parent[] = $r->id;
                if(!isset($content_view[0]) && in_array($r->type, $array_video)){
                  $content_view[0] = $r;
                }
              }
            }
          }else{
            // dd('none list parent');
            // flash('Ooops...!', getPhrase("page_not_found"), 'error');
            return back();
          }
# check empty content
          if($content_q == []){
            // dd('content empty');
            // flash('Ooops...!', getPhrase("page_not_found"), 'error');
            return back();
          }
# check if come to page from series
          if($lesson_id == ''){
            $lesson_id = $content_view[0]->id;
// dump($lesson_id);
          }
          $data['viewed_video'] = true;
# get lesson
          $lesson = [];
          $i_parent = 0;
          $data['current_lesson'] = '';
          $array_loop = ['1','2','3','4','6','7'];
          foreach($content_q as $r){
            if($r->id < $lesson_id && in_array($r->type, $array_video)){
              $pre_lesson = $r->id;
            }
            if($r->id == $lesson_id){
              if(!in_array($r->type,$array_video)){
                return redirect(PREFIX."learning-management/lesson/show/$slug/".$pre_lesson);
              }
              $class_color = '#e62020';
              $data['current_lesson'] = $r->bai ;
              $data['current_description'] = $r->description;
              $data['current_video'] = $r->file_path;
              $data['current_poster'] = IMAGE_PATH_UPLOAD_LMS_CONTENTS. $r->image;
              $data['contentslug'] = $r->id;
              $data['url_excer'] = PREFIX.'learning-management/lesson/exercise/'.$slug.'/'.$r->id;
            }
          }
          $new = $this->dequy_showLesson_selected(['content_q' => $content_q, 'content_view' => $content_view,'stt' => $lesson_id, 'slug' => $slug, 'parent_id' => null]);
          $lesson = '';
          $is_loop = false;
          $i = 0;
          foreach($new as $r){
            if(!in_array($r['type'], $array_loop) && $is_loop === true){
              $is_loop = 'end';
            }
            if($r['level'] == '0' && $i > 0){
              $lesson .= '</ul>';
            }
            if($is_loop === 'end'){
              $lesson .= '</ul></li>';
              $is_loop = false;
            }
            if($r['type'] != 8){
              $lesson .= $r['tag'];
            }elseif ($r['type'] == 8) {
              $lesson .= $r['tag'];
              $is_loop = true;
            }
            $i++;
          }
          $data['lesson_menu'] = $lesson;
          return view($view_name, $data);
        }
    /**
     * This method displays the list of series available
     * @return [type] [description]
     */
    public function ajaxcheckout(Request $request) {
        $data = null;
        $user = getUserRecord();
        $item = LmsSeries::where('id', '=', $request->item)->first();
        $user_record = User::find($user->id);
        if ($item->cost <= $user_record->point) {
            $data = array('error'=>0);
        } else {
            $data = array('error'=>1, 'message'=>'Bạn không đủ số Hi Koi, vui lòng nạp thêm.');
        }
        return json_encode($data);
    }
        public function razorpaySuccess(Request $request) {
            $data = $request->all();
            $user = getUserRecord();
            $item = LmsSeries::where('id', '=', $request->item)->first();
            $user_record = User::find($user->id);
            try {
                DB::beginTransaction();
                if ($item->cost <= $user_record->point) {
                    $payment                  = new Payment();
                    $payment->item_id         = $request->item;
                    $payment->item_name       = $item->title;
                    $payment->plan_type       = 'lms';
                    // $payment->payment_gateway = $payment_method;
                    // $payment->slug            = $payment::makeSlug(getHashCode());
                    $payment->cost            = $item->cost;
                    $payment->user_id         = $user->id;
                    // $payment->paid_by_parent  = $other_details['paid_by_parent'];
                    $payment->payment_status  = 'success';
                    // $payment->other_details   = json_encode($other_details);
                    $payment->save();
                    $user_record->point = $user->point - $item->cost ;
                    $user_record->save();
                    $data = array('error'=>0,'message' =>'');
                } else {
                    $data = array('error'=>1, 'message'=>'Bạn không đủ số Hi Koi, vui lòng nạp thêm.');
                }
                DB::commit();
                return json_encode($data);
            }catch (Exception $e) {
                DB::rollBack();
                $data = array('error'=>2, 'message'=>'Giao dịch thất bại');
                return json_encode($data);
            }
        }
    public function studentExercises(Request $request, $combo_slug = '',$slug = '',$stt = '') {
        $data['layout']              = getLayout();
        $view_name = 'admin.student.exercise.student-exercise';
        try {
            if($slug == ''){
                //flash('Ooops...!', getPhrase("page_not_found"), 'error');
                return back();
            }
            // get Exercise
            if (Auth::check() && Auth::user()->role_id != 6){
                $this->prepareContentIds($slug);
                $check_hocthu  = DB::table('lmscontents')
                    ->join('lmsseries','lmsseries.id','=','lmscontents.lmsseries_id')
                    ->where([
                        ['lmsseries.delete_status',0],
                        ['lmscontents.id',$stt],
                        ['lmscontents.delete_status',0],
                        ['lmscontents.el_try',1]
                    ])
                    ->distinct()
                    ->count();


                if ($check_hocthu == 0){
                    $data['hi_combo'] = DB::table('lmsseries_combo')
                        ->where('slug',$combo_slug)
                        ->where('delete_status',0)
                        ->get()->first();
                    $data['checkpay'] = DB::table('payment_method')
                        ->select(DB::raw("(SELECT COUNT(id)  FROM payment_method WHERE payment_method.item_id 
                  = ".$data['hi_combo']->id."  AND payment_method.user_id = ".Auth::id()."     AND 
                  DATE_ADD(responseTime, INTERVAL IF(".$data['hi_combo']->time." = 0,90,IF(".$data['hi_combo']->time." = 1,180,365)) DAY) > NOW()) as payment"))
                        ->distinct()
                        ->get()->first();

                    if ($data['checkpay']->payment == 0){
                        return redirect('home');
                    }
                }
            }

            $records = DB::table('lmscontents')
                ->join('lms_exams','lms_exams.content_id','=','lmscontents.id')
                ->where('lmscontents.id',$stt)
                ->where('lmscontents.delete_status',0)
                ->where('lms_exams.delete_status',0)
                //->where('lms_exams.dang',5)
                ->whereNotNull('lms_exams.dang')
                ->select('lms_exams.id','label','dang','cau','mota','dapan',DB::raw("CONCAT_WS('-,-',luachon1,luachon2,luachon3,luachon4) AS answers") )
                ->get();
            if (!$records->isEmpty()){
                foreach ($records as $key => $value) {
                    $records[$key]->mota = change_furigana( mb_convert_encoding(str_replace('＿＿','__',$value->mota),"UTF-8","auto"),'return');
                    $records[$key]->answers = explode('-,-', trim($value->answers));
                }
                foreach ($records as $key => $record) {
                    $valueAnswers = array();
                    foreach ($record->answers as $answer){
                        $valueAnswers[] = change_furigana( $answer,'return');
                    }
                    $records[$key]->answers = $valueAnswers;
                }
            }
            // get right menu
            $data['current_series'] = DB::table('lmsseries')
                ->where([
                    ['slug',$slug],
                ])->first();

            if (Auth::check()){
                $data['total_course'] = DB::table('lmsseries')
                    ->join('lmscontents','lmsseries.id','=','lmscontents.lmsseries_id')
                    ->where([
                        ['lmsseries.delete_status',0],
                        ['lmsseries.slug',$slug],
                        ['lmscontents.delete_status',0],
                    ])
                    ->whereNotIn('lmscontents.type', [0,8])
                    ->distinct()
                    ->get()->count();
                //dump($data['total_course'] );
                $data['current_course'] = DB::table('lms_student_view')
                    ->join('lmscontents','lmscontents.id','=','lms_student_view.lmscontent_id')
                    ->join('lmsseries','lmsseries.id','=','lmscontents.lmsseries_id')
                    ->where([
                        ['users_id',Auth::id()],
                        ['lmsseries.slug',$slug],
                        ['lms_student_view.finish',1],
                        ['lmscontents.delete_status',0],
                        ['lmsseries.delete_status',0],
                    ])
                    ->whereNotIn('lmscontents.type', [0,8])
                    ->select('lms_student_view.lmscontent_id')
                    ->distinct('lms_student_view.lmscontent_id')
                    ->get()->count();
                //dump($data['current_course']);
            }
            $data['hi_combo'] = DB::table('lmsseries_combo')
                ->where('slug',$combo_slug)
                ->where('delete_status',0)
                ->get()->first();
            if (!Auth::check()){
                $data['url_categories'] = PREFIX.'lms/exam-categories/list';
                $content_q = DB::table('lmscontents')
                    ->select('lmscontents.*')
                    ->join('lmsseries','lmsseries.id','=','lmscontents.lmsseries_id')
                    ->where([
                        ['lmsseries.slug',$slug],
                        ['lmscontents.delete_status',0]
                    ])
                    ->orderBy('stt','asc')
                    ->get();
                // dd($content_q);
                # check empty content
                if($content_q->isEmpty()){
                    // dd('empty content');
                    //flash('Ooops...!', getPhrase("page_not_found"), 'error');
                    return back();
                }
                $check_hocthu  = DB::table('lmscontents')
                    ->join('lmsseries','lmsseries.id','=','lmscontents.lmsseries_id')
                    ->where([
                        ['lmsseries.delete_status',0],
                        ['lmsseries.slug',$slug],
                        ['lmscontents.delete_status',0],
                        ['lmscontents.el_try',1]
                    ])
                    ->distinct()
                    ->count();
                if ($check_hocthu == 0){
                    flash('Yêu cầu sở hữu khóa học', 'Vui lòng sở hữu khóa học để xem nội này', 'error');
                    return redirect('/home');
                }
                $data['hi_koi'] = DB::table('lmsseries')->select('lmsseries.*')  ->where([
                    ['lmsseries.slug',$slug],
                ]) ->get()->first();
                # check viewd lesson + time view
                $content_view = DB::table('lmscontents')
                    ->select(['lmscontents.*'])
                    ->join('lmsseries','lmsseries.id','=','lmscontents.lmsseries_id')
                    ->where([
                        ['lmsseries.slug',$slug],
                        ['lmscontents.delete_status',0]
                    ])
                    ->whereNotIn('type',['0','8'])
                    ->orderBy('stt','desc')
                    ->get();
                if ($stt == ''){
                    $stt = $content_view[0]->id;
                }
                $cur_stt = $stt;
                $data['current_lesson'] = '';
                $array_video = ['1','2','6','9'];
                $array_loop = ['1','2','3','4','6','7'];
                foreach($content_q as $r){
                    if($r->id < $stt && in_array($r->type, $array_video)){
                        $pre_lesson = $r->id;
                    }
                    if($r->id == $cur_stt){
                        if(!in_array($r->type,$array_video)){
                            //return redirect(PREFIX."learning-management/lesson/show/$slug/".$pre_lesson);
                        }
                        $class_color = '#e62020';
                        $data['current_lesson'] = ($r->title == null) ? $r->bai : $r->bai ;
                        $data['current_description'] = $r->description;
                        $data['current_video'] = ($r->el_try == 1 ? $r->file_path : null );
                        $data['current_poster'] = IMAGE_PATH_UPLOAD_LMS_CONTENTS. $r->image;
                        $data['contentslug'] = $r->id;
                        $data['url_excer'] = PREFIX.'learning-management/lesson/exercise/'.$slug.'/'.$r->id;
                        //dump($r->el_try);
                    }
                }
                $new = $this->dequy_tryshowLesson(['content_q' => $content_q,
                    'content_view' => $content_view,'stt' => $stt, 'slug' => $slug, 'parent_id' => null,'combo_slug' => $combo_slug,]);
                $lesson = '';
                $is_loop = false;
                $i = 0;
                foreach($new as $r){
                    if(!in_array($r['type'], $array_loop) && $is_loop === true){
                        $is_loop = 'end';
                    }
                    if($r['level'] == '0' && $i > 0){
                        $lesson .= '</ul>';
                    }
                    if($is_loop === 'end'){
                        $lesson .= '</ul></li>';
                        $is_loop = false;
                    }
                    if($r['type'] != 8){
                        $lesson .= $r['tag'];
                    }elseif ($r['type'] == 8) {
                        $lesson .= $r['tag'];
                        $is_loop = true;
                    }
                    $i++;
                }
                $data['lesson_menu'] = $lesson;
                //die();
            }else{
                if(Auth::user()->role_id == 6){
                    $_00content = DB::table('lmscontents')
                        ->select('lmscontents.*')
                        ->join('lmsseries','lmsseries.id','=','lmscontents.lmsseries_id')
                        ->where([
                            ['lmsseries.slug',$slug],
                        ])
                        ->whereNotIn('type',['0','8'])
                        ->get();
                    foreach($_00content as $r){
                        $x = DB::table('lms_student_view')
                            ->updateOrInsert(
                                [
                                    'lmscontent_id' => $r->id,
                                    'users_id'      => Auth::id(),
                                ],
                                [
                                    'finish'        => 1,
                                    'type'          => $r->type,
                                ]
                            );
                    }
                }else{
                }
                $data['url_categories'] = PREFIX.'lms/exam-categories/list';
                $content_q = DB::table('lmscontents')
                    ->select('lmscontents.*')
                    ->join('lmsseries','lmsseries.id','=','lmscontents.lmsseries_id')
                    ->where([
                        ['lmsseries.slug',$slug],
                        ['lmscontents.delete_status',0]
                    ])
                    ->orderBy('lmscontents.stt','asc')
                    ->get();
                # check empty content
                    //dd($content_q);

                $data['hi_koi'] = DB::table('lmsseries')->select('lmsseries.*')  ->where([
                    ['lmsseries.slug',$slug],
                ]) ->get()->first();
                /*$data['checkpay'] = DB::table('payments')
                    ->join('lmsseries', 'payments.item_id', '=', 'lmsseries.id')
                    ->select('lmsseries.*')
                    ->where([
                        ['lmsseries.slug',$slug],
                        ['payments.user_id',Auth::id()],
                ])->count();;*/
                $data['checkpay'] = DB::table('payment_method')
                    ->select(DB::raw("(SELECT COUNT(id)  FROM payment_method WHERE payment_method.item_id 
                  = ".$data['hi_combo']->id."  AND payment_method.user_id = ".Auth::id()."  AND payment_method.status = 1 
                     AND  DATE_ADD(responseTime, INTERVAL IF(".$data['hi_combo']->time." = 0,90,IF(".$data['hi_combo']->time." = 1,180,365)) DAY) > NOW()) as payment"))
                    ->distinct()
                    ->get()->first();
                /*$data['checkpays'] = DB::select('select * from payment_method where  payment_method.item_id = :item_id and payment_method.user_id = :user_id and payment_method.status  = 1', ['item_id' => $data['hi_combo']->id,'user_id' => Auth::id()]);
                dump($data['checkpays'] );*/
                if ($data['hi_combo']->cost == 0  || $data['checkpay']->payment > 0 || Auth::user()->role_id == 6){
                    if($data['hi_koi']->type_series == 1){
                        if ($stt == ''){
                            $id_content = DB::table('lmscontents')
                                ->select('lmscontents.*')
                                ->join('lmsseries','lmsseries.id','=','lmscontents.lmsseries_id')
                                ->where([
                                    ['lmsseries.slug',$slug],
                                    ['lmscontents.delete_status',0],
                                ])
                                ->whereNotIn('type',['0','8'])
                                ->get()->first();
                        }else{
                            $id_content = DB::table('lmscontents')
                                ->select('lmscontents.*')
                                ->where([
                                    ['lmscontents.id',$stt],
                                    ['lmscontents.delete_status',0],
                                ])
                                ->whereNotIn('type',['0','8'])
                                ->get()->first();
                        }
                        $checkviews= DB::table('lms_student_view')
                            ->where([
                                ['lms_student_view.lmscontent_id',$id_content->id],
                                ['lms_student_view.users_id',Auth::user()->id],
                            ])
                            ->get();
                        //dd($checkviews);
                        if ($checkviews->isEmpty()){
                            $new_id = DB::table('lms_student_view')
                                ->insertGetId([
                                    'lmscontent_id'       => $id_content->id,
                                    'users_id'            => Auth::id(),
                                    'view_time'           => 0,
                                    'finish'              => 0,
                                    'type'                => $id_content->type,
                                ]);
                        }
                        $content_view = DB::table('lms_student_view')
                            ->select(['lms_student_view.*','lmscontents.stt','lmscontents.slug'])
                            ->join('lmscontents','lmscontents.id','=','lms_student_view.lmscontent_id')
                            ->join('lmsseries','lmsseries.id','=','lmscontents.lmsseries_id')
                            ->where([
                                ['users_id',Auth::id()],
                                ['lmsseries.slug',$slug],
                                ['finish',0],
                                ['lmscontents.delete_status',0]
                            ])
                            ->orderBy('stt','desc')
                            ->get();
                        $data['viewed_video'] = true;
                    }else{
                        # check viewd lesson + time view
                        $content_view = DB::table('lms_student_view')
                            ->select(['lms_student_view.*','lmscontents.stt','lmscontents.slug'])
                            ->join('lmscontents','lmscontents.id','=','lms_student_view.lmscontent_id')
                            ->join('lmsseries','lmsseries.id','=','lmscontents.lmsseries_id')
                            ->where([
                                ['users_id',Auth::id()],
                                ['lmsseries.slug',$slug],
                                ['finish',0],
                                ['lmscontents.delete_status',0]
                            ])
                            ->orderBy('lmscontents.stt','desc')
                            ->get();
                         // dd($content_view);
                        # check first view or next lesson view
                        if($content_view->isEmpty()){
                            # next lesson view
                            $viewed_content = DB::table('lms_student_view')
                                ->select(['lms_student_view.*','lmscontents.stt','lmscontents.slug'])
                                ->join('lmscontents','lmscontents.id','=','lms_student_view.lmscontent_id')
                                ->join('lmsseries','lmsseries.id','=','lmscontents.lmsseries_id')
                                ->where([
                                    ['users_id',Auth::id()],
                                    ['lmsseries.slug',$slug],
                                    ['finish',1],
                                    ['lmscontents.delete_status',0]
                                ])
                                ->orderBy('stt','desc')
                                ->get();
                              
                            if($viewed_content->isEmpty()){
                                # check first view
                                $id_content = DB::table('lmscontents')
                                    ->select('lmscontents.*')
                                    ->join('lmsseries','lmsseries.id','=','lmscontents.lmsseries_id')
                                    ->where([
                                        ['lmsseries.slug',$slug],
                                        ['lmscontents.delete_status',0],
                                    ])
                                    ->whereNotIn('type',['0','8'])
                                    ->get()->first();
                            }else{
                                # next lesson
                                $id_content = DB::table('lmscontents')
                                    ->select('lmscontents.*')
                                    ->join('lmsseries','lmsseries.id','=','lmscontents.lmsseries_id')
                                    ->where([
                                        ['lmsseries.slug',$slug],
                                        ['lmscontents.stt','>',$viewed_content[0]->stt],
                                        ['lmscontents.delete_status',0],
                                    ])
                                    ->whereNotIn('type',['0','8'])
                                    ->get()->first();
                            }
                            if($id_content != null){
                                $new_id = DB::table('lms_student_view')
                                    ->insertGetId([
                                        'lmscontent_id'       => $id_content->id,
                                        'users_id'            => Auth::id(),
                                        'view_time'           => 0,
                                        'finish'              => 0,
                                        'type'                => $id_content->type,
                                    ]);
                                $content_view = DB::table('lms_student_view')
                                    ->select(['lms_student_view.*','lmscontents.stt','lmscontents.slug'])
                                    ->join('lmscontents','lmscontents.id','=','lms_student_view.lmscontent_id')
                                    ->join('lmsseries','lmsseries.id','=','lmscontents.lmsseries_id')
                                    ->where([
                                        ['users_id',Auth::id()],
                                        ['lmsseries.slug',$slug],
                                        ['finish',0],
                                        ['lmscontents.delete_status',0]
                                    ])
                                    ->orderBy('stt','desc')
                                    ->get();
                            }else{
                                $content_view = DB::table('lms_student_view')
                                    ->select(['lms_student_view.*','lmscontents.stt','lmscontents.slug'])
                                    ->join('lmscontents','lmscontents.id','=','lms_student_view.lmscontent_id')
                                    ->join('lmsseries','lmsseries.id','=','lmscontents.lmsseries_id')
                                    ->where([
                                        ['users_id',Auth::id()],
                                        ['lmsseries.slug',$slug],
                                        ['finish',1],
                                        ['lmscontents.delete_status',0]
                                    ])
                                    ->orderBy('stt','desc')
                                    ->get();
                            }
                        }else{
                            $this->updateAndInsertContentView($slug, $stt);
                            $data['current_time'] = $content_view[0]->view_time;
                        }
                    }
                    // dump($content_view);
                    # check if come to page from series
                    $pre_stt = $stt;
                    if($stt == ''){
                        $stt = $content_view[0]->lmscontent_id;
                        // dump($stt);
                    }else{
                        $check_view = DB::table('lms_student_view')
                            ->select('finish')
                            ->join('lmscontents','lmscontents.id','=','lms_student_view.lmscontent_id')
                            ->where([
                                ['users_id',Auth::id()],
                                ['lmscontents.id',$stt],
                                ['lmscontents.delete_status',0]
                            ])
                            ->get();
                        if ($check_view->isNotEmpty() && $check_view[0]->finish == '1') {
                            $data['viewed_video'] = true;
                            unset($data['current_time']);
                        }
                    }
                    # get lesson
                    $check_end_view = DB::table('lmscontents')
                        ->select(['lmscontents.stt','lmscontents.id'])
                        ->join('lmsseries','lmsseries.id','=','lmscontents.lmsseries_id')
                        ->where([
                            ['lmsseries.slug',$slug],
                            ['lmscontents.delete_status',0],
                        ])
                        ->whereNotIn('type',['0','8'])
                        ->orderBy('stt','asc')
                        ->get()->first();
                    $cur_stt = $stt;
                    if($check_end_view->id != null){
                        if($stt == $check_end_view->id && $pre_stt == ''){
                            $check_first_view = DB::table('lmscontents')
                                ->select(['lmscontents.stt','lmscontents.id'])
                                ->join('lmsseries','lmsseries.id','=','lmscontents.lmsseries_id')
                                ->where([
                                    ['lmsseries.slug',$slug],
                                    ['lmscontents.delete_status',0],
                                ])
                                ->whereNotIn('type',['0','8'])
                                ->orderBy('stt','asc')
                                ->get()->first();
                            $cur_stt = $check_first_view->id;
                        }
                    }
                    $lesson = [];
                    $i_parent = 0;
                    $data['current_lesson'] = '';
                    $array_video = ['1','2','6','9'];
                    $array_loop = ['1','2','3','4','6','7'];
                    // dd($content_q);
                    foreach($content_q as $r){
                        if($r->id < $stt && in_array($r->type, $array_video)){
                            $pre_lesson = $r->id;
                        }
                        if($r->id == $cur_stt){
                            if(!in_array($r->type,$array_video)){
                                //return redirect(PREFIX."learning-management/lesson/show/$combo_slug/$slug/".$pre_lesson);
                            }
                            $class_color = '#e62020';
                            $data['current_lesson'] = ($r->title == null) ? $r->bai : $r->bai ;
                            $data['current_description'] = $r->description;
                            $data['current_video'] = $r->file_path;
                            $data['current_poster'] = IMAGE_PATH_UPLOAD_LMS_CONTENTS. $r->image;
                            $data['contentslug'] = $r->id;
                            $data['url_excer'] = PREFIX.'learning-management/lesson/exercise/'.$slug.'/'.$r->id;
                        }
                    }
                    $new = $this->dequy_showLesson(['content_q' => $content_q, 'content_view' => $content_view,
                        'stt' => $cur_stt, 'slug' => $slug, 'parent_id' => null,'combo_slug' => $combo_slug,'lms_type' => $data['hi_koi']->type_series]);
                    $lesson = '';
                    $is_loop = false;
                    $i = 0;
                    foreach($new as $r){
                        if(!in_array($r['type'], $array_loop) && $is_loop === true){
                            $is_loop = 'end';
                        }
                        if($r['level'] == '0' && $i > 0){
                            $lesson .= '</ul>';
                        }
                        if($is_loop === 'end'){
                            $lesson .= '</ul></li>';
                            $is_loop = false;
                        }
                        if($r['type'] != 8){
                            $lesson .= $r['tag'];
                        }elseif ($r['type'] == 8) {
                            $lesson .= $r['tag'];
                            $is_loop = true;
                        }
                        $i++;
                    }
                }
                else{
                    $check_hocthu  = DB::table('lmscontents')
                        ->join('lmsseries','lmsseries.id','=','lmscontents.lmsseries_id')
                        ->where([
                            ['lmsseries.delete_status',0],
                            ['lmsseries.slug',$slug],
                            ['lmscontents.delete_status',0],
                            ['lmscontents.el_try',1]
                        ])
                        ->distinct()
                        ->count();
                    if ($check_hocthu == 0){
                        flash('Yêu cầu sở hữu khóa học', 'Vui lòng sở hữu khóa học để xem nội này', 'error');
                        return redirect('/payments/lms/'.$combo_slug);
                    }
                    # check viewd lesson + time view
                    $content_view = DB::table('lmscontents')
                        ->select(['lmscontents.*'])
                        ->join('lmsseries','lmsseries.id','=','lmscontents.lmsseries_id')
                        ->where([
                            ['lmsseries.slug',$slug],
                            ['lmscontents.delete_status',0]
                        ])
                        ->whereNotIn('type',['0','8'])
                        ->orderBy('stt','desc')
                        ->get();
                    if ($stt == ''){
                        $stt = $content_view[0]->id;
                    }
                    $cur_stt = $stt;
                    $data['current_lesson'] = '';
                    $array_video = ['1','2','6','9'];
                    $array_loop = ['1','2','3','4','6','7'];
                    foreach($content_q as $r){
                        if($r->id < $stt && in_array($r->type, $array_video)){
                            $pre_lesson = $r->id;
                        }
                        if($r->id == $cur_stt){
                            if(!in_array($r->type,$array_video)){
                                //return redirect(PREFIX."learning-management/lesson/show/$combo_slug/$slug/".$pre_lesson);
                            }
                            $class_color = '#e62020';
                            $data['current_lesson'] = ($r->title == null) ? $r->bai : $r->bai;
                            $data['current_description'] = $r->description;
                            $data['current_video'] = ($r->el_try == 1 ? $r->file_path : null );
                            $data['current_poster'] = IMAGE_PATH_UPLOAD_LMS_CONTENTS. $r->image;
                            $data['contentslug'] = $r->id;
                            $data['url_excer'] = PREFIX.'learning-management/lesson/exercise/'.$slug.'/'.$r->id;
                        }
                    }
                    $new = $this->dequy_tryshowLesson(['content_q' => $content_q,
                        'content_view' => $content_view,'stt' => $stt, 'slug' => $slug, 'parent_id' => null,'combo_slug' => $combo_slug,]);
                    $lesson = '';
                    $is_loop = false;
                    $i = 0;
                    foreach($new as $r){
                        if(!in_array($r['type'], $array_loop) && $is_loop === true){
                            $is_loop = 'end';
                        }
                        if($r['level'] == '0' && $i > 0){
                            $lesson .= '</ul>';
                        }
                        if($is_loop === 'end'){
                            $lesson .= '</ul></li>';
                            $is_loop = false;
                        }
                        if($r['type'] != 8){
                            $lesson .= $r['tag'];
                        }elseif ($r['type'] == 8) {
                            $lesson .= $r['tag'];
                            $is_loop = true;
                        }
                        $i++;
                    }
                }
                $data['lesson_menu'] = $lesson;
            }
            // get commments
            $data['comment'] = DB::table('comments')
                ->where([
                    ['user_id',Auth::id()],
                    ['lmsseries_id',$data['hi_koi']->id],
                    ['lmscombo_id',$data['hi_combo']->id],
                    ['lmscontent_id',$stt],
                    ['parent_id',0],
                ])
                ->get();
            $data['comment_child'] = DB::table('comments')
                ->where([
                    ['user_id',Auth::id()],
                    ['lmsseries_id',$data['hi_koi']->id],
                    ['lmscombo_id',$data['hi_combo']->id],
                    ['lmscontent_id',$stt],
                    ['parent_id','!=',0],
                ])
                ->get();
            try {
                DB::table('comments')
                    ->where([
                        ['user_id',Auth::id()],
                        ['lmsseries_id',$data['hi_koi']->id],
                        ['lmscombo_id',$data['hi_combo']->id],
                        ['lmscontent_id',$stt],
                        ['parent_id',0],
                    ])
                    ->update(
                        [
                            'status' => 2,
                            'updated_at' =>date("Y-m-d H:i:s"),
                        ]
                    );
            }catch(Exception $e){
            }
        }catch (\Exception $e) {
            return $e->getMessage();
        }
        $data['class']       = 'exams';
        $data['title']              = isset($data['current_series']->title) ? $data['current_series']->title : 'Khóa học';
        $data['series']             = $slug;;
        $data['slug']                = $stt;
        $data['combo_slug']          = $combo_slug;
        $data['records']            = $records;
        $data['count_records']      = count($records);
       /* $data['back_url']            = $sendUrl;*/
        /*$data['sendUrl']        = $sendUrl;*/
        $data['active_class']       = 'exams';
        //$data['layout']              = 'admin.layouts.exercise.exerciselayout';
        $data['layout']              = getLayout();
        //dd($data['layout']);
        $view_name = 'admin.student.exercise.student-exercise';
        return view($view_name, $data);
    }

    public function flashCard1(Request $request,$combo_slug = '',$slug = '',$lmscontents_id = ''){

      $data['layout']              = getLayout();
      $view_name = 'admin.student.exercise.student-exercise';

      $records = DB::table('lms_flashcard')
                ->join('lms_flashcard_detail','lms_flashcard.id','=','lms_flashcard_detail.flashcard_id')
                ->where('lms_flashcard.id',1)
                ->select('lms_flashcard_detail.*' )
                ->get();




      $combo_slug = "chu-cai-tieng-nhat-8d27fdba41fb7829b4b1b9887ae4ea6df60dd033-6";
      $stt = "1";
      $data['current_series']              = 'exams';
      $data['class']              = 'exams';
      $data['title']              = 'Khóa học Tổng hợp - Luyện thi N4';
      $data['series']             = $slug;;
      $data['slug']               = $stt;
      $data['combo_slug']         = $combo_slug;
      $data['records']            = $records;
      $data['count_records']      = count($records);
      $data['active_class']       = 'exams';
      $data['layout']              = getLayout();
      $view_name = 'admin.student.flashcard.content-flashcard';
      return view($view_name, $data);



    }

    
    public function studentAudittest(Request $request,$combo_slug ='',$series = '',$slug = ''){
        if (Auth::check() && Auth::user()->role_id != 6){
            $this->prepareContentIds($series);
            $data['hi_combo'] = DB::table('lmsseries_combo')
                ->where('slug',$combo_slug)
                ->where('delete_status',0)
                ->get()->first();
            $data['checkpay'] = DB::table('payment_method')
                ->select(DB::raw("(SELECT COUNT(id)  FROM payment_method WHERE payment_method.item_id 
                  = ".$data['hi_combo']->id."  AND payment_method.user_id = ".Auth::id()."     AND 
                  DATE_ADD(responseTime, INTERVAL IF(".$data['hi_combo']->time." = 0,90,IF(".$data['hi_combo']->time." = 1,180,365)) DAY) > NOW()) as payment"))
                ->distinct()
                ->get()->first();
            if ($data['checkpay']->payment == 0){
                return redirect('home');
            }
        }
        if (Auth::check()){
            $data['total_course'] = DB::table('lmsseries')
                ->join('lmscontents','lmsseries.id','=','lmscontents.lmsseries_id')
                ->where([
                    ['lmsseries.delete_status',0],
                    ['lmsseries.slug',$series],
                    ['lmscontents.delete_status',0],
                ])
                ->whereNotIn('lmscontents.type', [0,8])
                ->distinct()
                ->get()->count();
            $data['current_course'] = DB::table('lms_student_view')
                ->join('lmscontents','lmscontents.id','=','lms_student_view.lmscontent_id')
                ->join('lmsseries','lmsseries.id','=','lmscontents.lmsseries_id')
                ->where([
                    ['users_id',Auth::id()],
                    ['lmsseries.slug',$series],
                    ['lms_student_view.finish',1],
                    ['lmscontents.delete_status',0],
                    ['lmsseries.delete_status',0],
                ])
                ->whereNotIn('lmscontents.type', [0,8])
                ->select('lms_student_view.lmscontent_id')
                ->distinct('lms_student_view.lmscontent_id')
                ->get()->count();
        }
        $records = DB::table('lmscontents')
            ->join('lms_test','lms_test.content_id','=','lmscontents.id')
            ->where('lmscontents.id',$slug)
            ->where('lmscontents.delete_status',0)
            ->where('lms_test.delete_status',0)
            ->whereNotNull('lms_test.dang')
            ->select('lms_test.id','dang','cau','mota','dapan','display',DB::raw("CONCAT_WS('-,-',luachon1,luachon2,luachon3,luachon4) AS answers") )
            ->orderBy('lmscontents.stt', 'asc')
            ->get();
        if (!$records->isEmpty()){
            // Insert a new content view
            $this->updateAndInsertContentView($series, $slug);

            foreach ($records as $key => $value) {
                //$records[$key]->answers_furigana = explode(',', change_furigana(trim($value->answers,'return')));
                $records[$key]->mota = change_furigana( trim($value->mota),'return');
                if($records[$key]->dang == '7'){
                    $records[$key]->mota = str_replace("\n", "\n\n", $records[$key]->mota);
                }
                $records[$key]->answers = explode('-,-', trim($value->answers));
            }
            foreach ($records as $key => $record) {
                $valueAnswers = array();
                foreach ($record->answers as $answer){
                    $valueAnswers[] = change_furigana( $answer,'return');
                }
                $records[$key]->answers = $valueAnswers;
            }
            $sendUrl = null;
            $recordback =DB::table('lmscontents')
                ->where('id',(int) $request->slug)
                ->select('stt','lmsseries_id')
                ->orderBy('stt', 'asc')
                ->get();
            $recordurl = DB::table('lmscontents')
                ->where('stt','<',((int)$recordback[0]->stt))
                ->where('lmsseries_id',$recordback[0]->lmsseries_id)
                ->whereNotIn('type',[3,4,5,0,8])
                ->select('id','type')
                ->orderBy('stt', 'asc')
                ->get()
            ;
            if (!$recordurl->isEmpty()){
                $sendUrl = PREFIX.'learning-management/lesson/show/'.$combo_slug.'/'.$request->series.'/'.$recordurl[0]->id;
            }else{
                $sendUrl = PREFIX;
            }
        }
        if(!isset($sendUrl)){
            $sendUrl = PREFIX;
        }

        // right menu
        $rightMenu = $this->rightMenu($combo_slug, $series, $slug);
        $data['lesson_menu'] = $rightMenu->lesson_menu;
           $data['current_series'] =$rightMenu->current_series;
         $data['hi_combo'] = $rightMenu->hi_combo;
          $data['hi_koi'] = $rightMenu->hi_koi;
        $data['current_lesson'] = $rightMenu->current_lesson;

        // get commments
        $data['comment'] = DB::table('comments')
            ->where([
                ['user_id',Auth::id()],
                ['lmsseries_id',$data['hi_koi']->id],
                ['lmscombo_id',$data['hi_combo']->id],
                ['lmscontent_id',$slug],
                ['parent_id',0],
            ])
            ->get();
        $data['comment_child'] = DB::table('comments')
            ->where([
                ['user_id',Auth::id()],
                ['lmsseries_id',$data['hi_koi']->id],
                ['lmscombo_id',$data['hi_combo']->id],
                ['lmscontent_id',$slug],
                ['parent_id','!=',0],
            ])
            ->get();
        try {
            DB::table('comments')
                ->where([
                    ['user_id',Auth::id()],
                    ['lmsseries_id',$data['hi_koi']->id],
                    ['lmscombo_id',$data['hi_combo']->id],
                    ['lmscontent_id',$slug],
                    ['parent_id',0],
                ])
                ->update(
                    [
                        'status' => 2,
                        'updated_at' =>date("Y-m-d H:i:s"),
                    ]
                );
        }catch(Exception $e){
        }
          $data['class']       = 'audit';
        $data['title']              = 'Khóa học';
        $data['series']             = $series;
        $data['slug']                =$slug;
        $data['combo_slug']                =$combo_slug;
        $data['back_url']            = $sendUrl;
        $data['records']            = $records;
       // $data['layout']              = 'admin.layouts.exercise.exerciselayout';
        $data['active_class']       = 'audit';
         $data['layout']              = getLayout();
        $view_name = 'admin.student.exercise.show-audit';
        return view($view_name, $data);
    }

    public function storeResuttest(Request $request,$combo_slug ='',$series = '',$slug = ''){
        if($request->isMethod('post')) {
            try {
                $content_id = $request->content_id;
                $time = $request->time;
                $dataQuest = $request->all();
                unset($dataQuest['_token']);
                unset($dataQuest['content_id']);
                unset($dataQuest['time']);
                $records = DB::table('lmscontents')
                    ->join('lms_test','lms_test.content_id','=','lmscontents.id')
                    ->where('lmscontents.id',$slug)
                    ->where('lmscontents.delete_status',0)
                    ->where('lms_test.delete_status',0)
                    ->select('lms_test.id','dang','cau','mota','dapan','diem','display',DB::raw("CONCAT_WS(',',luachon1,luachon2,luachon3,luachon4) AS answers") )
                    ->orderBy('lms_test.id')
                    ->get();
                $totalValue = 0;
                $point = 0;
                foreach ($records as $keyRecord => $valueRecord){
                    $point = $point + $valueRecord->diem;
                    $correct = 0;
                    $check = 999;
                    foreach ($dataQuest as $key => $value){
                        $idKey = filter_var(str_replace('quest_','',$key),FILTER_SANITIZE_NUMBER_INT);
                        if($valueRecord->id == $idKey){
                            if ($valueRecord->dapan == $value){
                                $totalValue = (int) $totalValue + (int) $valueRecord->diem;
                                $correct = 1;
                            }
                            $check = $value;
                            unset($dataQuest['$key']);
                            break;
                        }
                    }
                    $records[$keyRecord]->correct = $correct;
                    $records[$keyRecord]->check = $check;
                }
                foreach ($records as $key => $value) {
                    $records[$key]->mota = change_furigana( trim($value->mota),'return');
                    $records[$key]->answers = explode(',', trim($value->answers));
                }
                foreach ($records as $key => $record) {
                    $valueAnswers = array();
                    foreach ($record->answers as $answer){
                        $valueAnswers[] = change_furigana( $answer,'return');
                    }
                    $records[$key]->answers = $valueAnswers;
                }
                $passed = (int)$totalValue / (int)$point;
                $sendUrl = null;
                if ($passed > 0.65){
                    if (Auth::user() != null) {
                        $rewardPoint = 1;
                        if ($passed >= 1) {
                            $rewardPoint = 3;
                        } else if ($passed > 0.8) {
                            $rewardPoint = 2;
                        }
                        try {
                            DB::beginTransaction();
                            DB::table('lms_test_result')->insert([
                                'lmscontent_id' => $content_id,
                                'combo_slug' => $combo_slug,
                                'finish' => 1,
                                'total_point' => $point,
                                'users_id' => Auth::id(),
                                'point' => $totalValue,
                                'time_result' => $time,
                                'created_by' => Auth::id(),
                            ]);
                            $this->userService->updatePoint($rewardPoint, $content_id, Auth::id());
                            DB::table('lms_student_view')
                                ->where('users_id', Auth::id())
                                ->where('lmscontent_id', $content_id)
                                ->update([
                                    'finish' => 1,
                                ]);
                            DB::commit();
                        } catch (Exception $e) {
                            DB::rollBack();
                        }
                    }
                    $record =DB::table('lmscontents')
                        ->where('id',(int) $request->slug)
                        ->select('stt','lmsseries_id')
                        ->get();
                    if (!$record->isEmpty()){
                        $recordurl = DB::table('lmscontents')
                            ->where('stt','>=',((int)$record[0]->stt +1))
                            ->where('lmsseries_id',$record[0]->lmsseries_id)
                            ->whereNotIn('type',[0,8])
                            ->select('id','type')
                            ->first();
                        if (!empty($recordurl)){
                            // dd($recordurl);
                            switch ($recordurl->type) {
                                case 1:
                                  $sendUrl = PREFIX.'learning-management/lesson/show/'.$combo_slug.'/'.$request->series.'/'.$recordurl->id;
                                    break;
                                case 2:
                                case 6:
                                    $sendUrl = PREFIX.'learning-management/lesson/show/'.$combo_slug.'/'.$request->series.'/'.$recordurl->id;
                                    break;
                                case 3:
                                case 4:
                                    $sendUrl = PREFIX.'learning-management/lesson/exercise/'.$combo_slug.'/'.$request->series.'/'.$recordurl->id;
                                    break;
                                case 5:
                                    $sendUrl = PREFIX.'learning-management/lesson/audit/'.$combo_slug.'/'.$request->series.'/'.$recordurl->id;
                                    break;
                                default:
                                    $sendUrl = null;
                                    break;
                            }
                        }
                    }
                }
                if (Auth::check() && Auth::user()->role_id != 6){
                    $check = DB::table('lms_student_view')
                        ->select('id')
                        ->where('lmscontent_id',$slug)
                        ->where('users_id',Auth::id())
                        ->get();
                    if ($check->isEmpty()){
                        return redirect('home');
                    }
                    $data['hi_combo'] = DB::table('lmsseries_combo')
                        ->where('slug',$combo_slug)
                        ->where('delete_status',0)
                        ->get()->first();
                    $data['checkpay'] = DB::table('payment_method')
                        ->select(DB::raw("(SELECT COUNT(id)  FROM payment_method WHERE payment_method.item_id 
                  = ".$data['hi_combo']->id."  AND payment_method.user_id = ".Auth::id()."     AND 
                  DATE_ADD(responseTime, INTERVAL IF(".$data['hi_combo']->time." = 0,90,IF(".$data['hi_combo']->time." = 1,180,365)) DAY) > NOW()) as payment"))
                        ->distinct()
                        ->get()->first();
                    if ($data['checkpay']->payment == 0){
                        return redirect('home');
                    }
                }
                if (Auth::check()){
                    $data['total_course'] = DB::table('lmsseries')
                        ->join('lmscontents','lmsseries.id','=','lmscontents.lmsseries_id')
                        ->where([
                            ['lmsseries.delete_status',0],
                            ['lmsseries.slug',$series],
                            ['lmscontents.delete_status',0],
                        ])
                        ->whereNotIn('lmscontents.type', [0,8])
                        ->distinct()
                        ->get()->count();
                    $data['current_course'] = DB::table('lms_student_view')
                        ->join('lmscontents','lmscontents.id','=','lms_student_view.lmscontent_id')
                        ->join('lmsseries','lmsseries.id','=','lmscontents.lmsseries_id')
                        ->where([
                            ['users_id',Auth::id()],
                            ['lmsseries.slug',$series],
                            ['lms_student_view.finish',1],
                            ['lmscontents.delete_status',0],
                            ['lmsseries.delete_status',0],
                        ])
                        ->whereNotIn('lmscontents.type', [0,8])
                        ->select('lms_student_view.lmscontent_id')
                        ->distinct('lms_student_view.lmscontent_id')
                        ->get()->count();
                }
                $rightMenu = $this->rightMenu($combo_slug,$series,$slug) ;
                $data['lesson_menu'] = $rightMenu->lesson_menu;
                $data['current_series'] =$rightMenu->current_series;
                $data['hi_combo'] = $rightMenu->hi_combo;
                $data['hi_koi'] = $rightMenu->hi_koi;
                $data['current_lesson'] = $rightMenu->current_lesson;
// get comment
                // get commments
                $data['comment'] = DB::table('comments')
                    ->where([
                        ['user_id',Auth::id()],
                        ['lmsseries_id',$data['hi_koi']->id],
                        ['lmscombo_id',$data['hi_combo']->id],
                        ['lmscontent_id',$slug],
                        ['parent_id',0],
                    ])
                    ->get();
                $data['comment_child'] = DB::table('comments')
                    ->where([
                        ['user_id',Auth::id()],
                        ['lmsseries_id',$data['hi_koi']->id],
                        ['lmscombo_id',$data['hi_combo']->id],
                        ['lmscontent_id',$slug],
                        ['parent_id','!=',0],
                    ])
                    ->get();
                try {
                    DB::table('comments')
                        ->where([
                            ['user_id',Auth::id()],
                            ['lmsseries_id',$data['hi_koi']->id],
                            ['lmscombo_id',$data['hi_combo']->id],
                            ['lmscontent_id',$slug],
                            ['parent_id',0],
                        ])
                        ->update(
                            [
                                'status' => 2,
                                'updated_at' =>date("Y-m-d H:i:s"),
                            ]
                        );
                }catch(Exception $e){
                }
                $data['class']              = 'exams';
                $data['title']              = 'Khóa học';
                $data['series']             = $series;
                $data['slug']               = $slug;
                $data['records']            = $records;
                $data['totalValue']         = $totalValue;
                $data['point']              = $point;
                $data['combo_slug']         = $combo_slug;
                $data['back_url']           = $sendUrl;
                $data['sendUrl']            = $sendUrl;
                $data['passed']             = $passed;

                // $data['totalValue']         = 100;
                // $data['point']              = 100;
                // $data['passed']             = 1;
                $data['active_class']       = 'audit';
                $data['layout'] = getLayout();


                // dd($data);
                $view_name = 'admin.student.exercise.show-audit';
                //dump( $data['totalValue']);
                //dd($sendUrl);
                return view($view_name, $data);
            }catch (Exception $e){
                return  redirect(PREFIX.'learning-management/lesson/audit'.$combo_slug.'/'.$series.'/'.$slug);
            }
        }
    }
   public function rightMenu($combo_slug = '',$slug = '',$stt = ''){
        $data['lesson_menu'] =null;
        $data['current_series'] = DB::table('lmsseries')
            ->where([
                ['slug',$slug],
            ])->first();
        if (Auth::check()){
            $this->prepareContentIds($slug);
            $data['total_course'] = DB::table('lmsseries')
                ->join('lmscontents','lmsseries.id','=','lmscontents.lmsseries_id')
                ->where([
                    ['lmsseries.delete_status',0],
                    ['lmsseries.slug',$slug],
                    ['lmscontents.delete_status',0],
                ])
                ->whereNotIn('lmscontents.type', [0,8])
                ->distinct()
                ->get()->count();
            //dump($data['total_course'] );
            $data['current_course'] = DB::table('lms_student_view')
                ->join('lmscontents','lmscontents.id','=','lms_student_view.lmscontent_id')
                ->join('lmsseries','lmsseries.id','=','lmscontents.lmsseries_id')
                ->where([
                    ['users_id',Auth::id()],
                    ['lmsseries.slug',$slug],
                    ['lms_student_view.finish',1],
                    ['lmscontents.delete_status',0],
                    ['lmsseries.delete_status',0],
                ])
                ->whereNotIn('lmscontents.type', [0,8])
                ->select('lms_student_view.lmscontent_id')
                ->distinct('lms_student_view.lmscontent_id')
                ->get()->count();
            //dump($data['current_course']);
        }
        $data['hi_combo'] = DB::table('lmsseries_combo')
            ->where('slug',$combo_slug)
            ->where('delete_status',0)
            ->get()->first();
        if (!Auth::check()){
            $data['url_categories'] = PREFIX.'lms/exam-categories/list';
            $content_q = DB::table('lmscontents')
                ->select('lmscontents.*')
                ->join('lmsseries','lmsseries.id','=','lmscontents.lmsseries_id')
                ->where([
                    ['lmsseries.slug',$slug],
                    ['lmscontents.delete_status',0]
                ])
                ->orderBy('stt','asc')
                ->get();
            // dd($content_q);
            # check empty content
            if($content_q->isEmpty()){
                // dd('empty content');
                // flash('Ooops...!', getPhrase("page_not_found"), 'error');
                return back();
            }
            $check_hocthu  = DB::table('lmscontents')
                ->join('lmsseries','lmsseries.id','=','lmscontents.lmsseries_id')
                ->where([
                    ['lmsseries.delete_status',0],
                    ['lmsseries.slug',$slug],
                    ['lmscontents.delete_status',0],
                    ['lmscontents.el_try',1]
                ])
                ->distinct()
                ->count();
            if ($check_hocthu == 0){
                flash('Yêu cầu sở hữu khóa học', 'Vui lòng sở hữu khóa học để xem nội này', 'error');
                return redirect('/home');
            }
            $data['hi_koi'] = DB::table('lmsseries')->select('lmsseries.*')  ->where([
                ['lmsseries.slug',$slug],
            ]) ->get()->first();
            # check viewd lesson + time view
            $content_view = DB::table('lmscontents')
                ->select(['lmscontents.*'])
                ->join('lmsseries','lmsseries.id','=','lmscontents.lmsseries_id')
                ->where([
                    ['lmsseries.slug',$slug],
                    ['lmscontents.delete_status',0]
                ])
                ->whereNotIn('type',['0','8'])
                ->orderBy('stt','desc')
                ->get();
            if ($stt == ''){
                $stt = $content_view[0]->id;
            }
            $cur_stt = $stt;
            $data['current_lesson'] = '';
            $array_video = ['1','2','6','9'];
            $array_loop = ['1','2','3','4','6','7'];
            foreach($content_q as $r){
                if($r->id < $stt && in_array($r->type, $array_video)){
                    $pre_lesson = $r->id;
                }
                if($r->id == $cur_stt){
                    if(!in_array($r->type,$array_video)){
                        //return redirect(PREFIX."learning-management/lesson/show/$slug/".$pre_lesson);
                    }
                    $class_color = '#e62020';
                    $data['current_lesson'] = ($r->title == null) ? $r->bai : $r->bai ;
                    $data['current_description'] = $r->description;
                    $data['current_video'] = ($r->el_try == 1 ? $r->file_path : null );
                    $data['current_poster'] = IMAGE_PATH_UPLOAD_LMS_CONTENTS. $r->image;
                    $data['contentslug'] = $r->id;
                    $data['url_excer'] = PREFIX.'learning-management/lesson/exercise/'.$slug.'/'.$r->id;
                    //dump($r->el_try);
                }
            }
            $new = $this->dequy_tryshowLesson(['content_q' => $content_q,
                'content_view' => $content_view,'stt' => $stt, 'slug' => $slug, 'parent_id' => null,'combo_slug' => $combo_slug,]);
            $lesson = '';
            $is_loop = false;
            $i = 0;
            foreach($new as $r){
                if(!in_array($r['type'], $array_loop) && $is_loop === true){
                    $is_loop = 'end';
                }
                if($r['level'] == '0' && $i > 0){
                    $lesson .= '</ul>';
                }
                if($is_loop === 'end'){
                    $lesson .= '</ul></li>';
                    $is_loop = false;
                }
                if($r['type'] != 8){
                    $lesson .= $r['tag'];
                }elseif ($r['type'] == 8) {
                    $lesson .= $r['tag'];
                    $is_loop = true;
                }
                $i++;
            }
            $data['lesson_menu'] = $lesson;
        }else{
            if(Auth::user()->role_id == 6){
                $_00content = DB::table('lmscontents')
                    ->select('lmscontents.*')
                    ->join('lmsseries','lmsseries.id','=','lmscontents.lmsseries_id')
                    ->where([
                        ['lmsseries.slug',$slug],
                    ])
                    ->whereNotIn('type',['0','8'])
                    ->orderBy('lmscontents.stt','asc')
                    ->get();
                foreach($_00content as $r){
                    $x = DB::table('lms_student_view')
                        ->updateOrInsert(
                            [
                                'lmscontent_id' => $r->id,
                                'users_id'      => Auth::id(),
                            ],
                            [
                                'finish'        => 1,
                                'type'          => $r->type,
                            ]
                        );
                }
            }
            $data['url_categories'] = PREFIX.'lms/exam-categories/list';
            $content_q = DB::table('lmscontents')
                ->select('lmscontents.*')
                ->join('lmsseries','lmsseries.id','=','lmscontents.lmsseries_id')
                ->where([
                    ['lmsseries.slug',$slug],
                    ['lmscontents.delete_status',0]
                ])
                ->orderBy('lmscontents.stt','asc')
                ->get();

            $data['hi_koi'] = DB::table('lmsseries')->select('lmsseries.*')  ->where([
                ['lmsseries.slug',$slug],
            ]) ->get()->first();
            $data['checkpay'] = DB::table('payment_method')
                ->select(DB::raw("(SELECT COUNT(id)  FROM payment_method WHERE payment_method.item_id 
                  = ".$data['hi_combo']->id."  AND payment_method.user_id = ".Auth::id()."  AND payment_method.status = 1 
                     AND  DATE_ADD(responseTime, INTERVAL IF(".$data['hi_combo']->time." = 0,90,IF(".$data['hi_combo']->time." = 1,180,365)) DAY) > NOW()) as payment"))
                ->distinct()
                ->get()->first();
            /*$data['checkpays'] = DB::select('select * from payment_method where  payment_method.item_id = :item_id and payment_method.user_id = :user_id and payment_method.status  = 1', ['item_id' => $data['hi_combo']->id,'user_id' => Auth::id()]);
            dump($data['checkpays'] );*/
            if ($data['hi_combo']->cost == 0  || $data['checkpay']->payment > 0 || Auth::user()->role_id == 6){
                if($data['hi_koi']->type_series == 1){
                    if ($stt == ''){
                        $id_content = DB::table('lmscontents')
                            ->select('lmscontents.*')
                            ->join('lmsseries','lmsseries.id','=','lmscontents.lmsseries_id')
                            ->where([
                                ['lmsseries.slug',$slug],
                                ['lmscontents.delete_status',0],
                            ])
                            ->whereNotIn('type',['0','8'])
                            ->get()->first();
                    }else{
                        $id_content = DB::table('lmscontents')
                            ->select('lmscontents.*')
                            ->where([
                                ['lmscontents.id',$stt],
                                ['lmscontents.delete_status',0],
                            ])
                            ->whereNotIn('type',['0','8'])
                            ->get()->first();
                    }
                    $checkviews= DB::table('lms_student_view')
                        ->where([
                            ['lms_student_view.lmscontent_id',$id_content->id],
                            ['lms_student_view.users_id',Auth::user()->id],
                        ])
                        ->get();
                    //dd($checkviews);
                    if(Auth::user()->role_id == 5) {
                      if ($checkviews->isEmpty()){
                        //echo 1222 ; dd($id_content);
                          $new_id = DB::table('lms_student_view')
                              ->insertGetId([
                                  'lmscontent_id'       => $id_content->id,
                                  'users_id'            => Auth::id(),
                                  'view_time'           => 0,
                                  'finish'              => 0,
                                  'type'                => $id_content->type,
                              ]);
                      }
                    }

                    //review
                    $content_view = DB::table('lms_student_view')
                        ->select(['lms_student_view.*','lmscontents.stt','lmscontents.slug'])
                        ->join('lmscontents','lmscontents.id','=','lms_student_view.lmscontent_id')
                        ->join('lmsseries','lmsseries.id','=','lmscontents.lmsseries_id')
                        ->where([
                            ['users_id',Auth::id()],
                            ['lmsseries.slug',$slug],
                            ['finish',0],
                            ['lmscontents.delete_status',0]
                        ])
                        ->orderBy('stt','desc')
                        ->get();
                        //dd($content_view);
                    $data['viewed_video'] = true;
                }else{
                    # check viewd lesson + time view
                    $content_view = DB::table('lms_student_view')
                        ->select(['lms_student_view.*','lmscontents.stt','lmscontents.slug'])
                        ->join('lmscontents','lmscontents.id','=','lms_student_view.lmscontent_id')
                        ->join('lmsseries','lmsseries.id','=','lmscontents.lmsseries_id')
                        ->where([
                            ['users_id',Auth::id()],
                            ['lmsseries.slug',$slug],
                            ['finish',0],
                            ['lmscontents.delete_status',0]
                        ])
                        ->orderBy('stt','desc')
                        ->get();
                    //  dd($content_view);
                    # check first view or next lesson view
                    if($content_view->isEmpty()){
                        # next lesson view
                        $viewed_content = DB::table('lms_student_view')
                            ->select(['lms_student_view.*','lmscontents.stt','lmscontents.slug'])
                            ->join('lmscontents','lmscontents.id','=','lms_student_view.lmscontent_id')
                            ->join('lmsseries','lmsseries.id','=','lmscontents.lmsseries_id')
                            ->where([
                                ['users_id',Auth::id()],
                                ['lmsseries.slug',$slug],
                                ['finish',1],
                                ['lmscontents.delete_status',0]
                            ])
                            ->orderBy('stt','desc')
                            ->get();
                            //dd($viewed_content);
                        if($viewed_content->isEmpty()){
                            # check first view
                            $id_content = DB::table('lmscontents')
                                ->select('lmscontents.*')
                                ->join('lmsseries','lmsseries.id','=','lmscontents.lmsseries_id')
                                ->where([
                                    ['lmsseries.slug',$slug],
                                    ['lmscontents.delete_status',0],
                                ])
                                ->whereNotIn('type',['0','8'])
                                ->first();
                        }else{
                            # next lesson
                          // dd($viewed_content);
                            $id_content = DB::table('lmscontents')
                                ->select('lmscontents.*')
                                //->join('lmsseries','lmsseries.id','=','lmscontents.lmsseries_id')
                                ->where([
                                    //['lmsseries.slug',$slug],
                                    ['lmscontents.lmsseries_id',$data['current_series']->id],
                                    ['lmscontents.stt','>',$viewed_content[0]->stt],
                                    ['lmscontents.delete_status',0],
                                ])
                                ->whereNotIn('lmscontents.type',['0','8'])
                                ->orderBy('lmscontents.stt','asc')
                                ->first();
                        }
                        if($id_content != null){
                          // echo 2222; dd($id_content);
                            $new_id = DB::table('lms_student_view')
                                ->insertGetId([
                                    'lmscontent_id'       => $id_content->id,
                                    'users_id'            => Auth::id(),
                                    'view_time'           => 0,
                                    'finish'              => 0,
                                    'type'                => $id_content->type,
                                ]);
                            $content_view = DB::table('lms_student_view')
                                ->select(['lms_student_view.*','lmscontents.stt','lmscontents.slug'])
                                ->join('lmscontents','lmscontents.id','=','lms_student_view.lmscontent_id')
                                ->join('lmsseries','lmsseries.id','=','lmscontents.lmsseries_id')
                                ->where([
                                    ['users_id',Auth::id()],
                                    ['lmsseries.slug',$slug],
                                    ['finish',0],
                                    ['lmscontents.delete_status',0]
                                ])
                                ->orderBy('desc','desc')
                                ->get();
                        }else{
                            $content_view = DB::table('lms_student_view')
                                ->select(['lms_student_view.*','lmscontents.stt','lmscontents.slug'])
                                ->join('lmscontents','lmscontents.id','=','lms_student_view.lmscontent_id')
                                ->join('lmsseries','lmsseries.id','=','lmscontents.lmsseries_id')
                                ->where([
                                    ['users_id',Auth::id()],
                                    ['lmsseries.slug',$slug],
                                    ['finish',1],
                                    ['lmscontents.delete_status',0]
                                ])
                                ->orderBy('stt','desc')
                                ->get();
                        }
                    }else{
                        $data['current_time'] = $content_view[0]->view_time;
                    }
                }
                // dump($content_view);
                # check if come to page from series
                $pre_stt = $stt;
                if($stt == ''){
                    $stt = $content_view[0]->lmscontent_id;
                    // dump($stt);
                }else{
                    $check_view = DB::table('lms_student_view')
                        ->select('finish')
                        ->join('lmscontents','lmscontents.id','=','lms_student_view.lmscontent_id')
                        ->where([
                            ['users_id',Auth::id()],
                            ['lmscontents.id',$stt],
                            ['lmscontents.delete_status',0]
                        ])
                        ->get();

                    if ($check_view->isNotEmpty() && $check_view[0]->finish == 1) {
                        $data['viewed_video'] = true;
                        unset($data['current_time']);
                    }
                }
                # get lesson
                $check_end_view = DB::table('lmscontents')
                    ->select(['lmscontents.stt','lmscontents.id'])
                    ->join('lmsseries','lmsseries.id','=','lmscontents.lmsseries_id')
                    ->where([
                        ['lmsseries.slug',$slug],
                        ['lmscontents.delete_status',0],
                    ])
                    ->whereNotIn('type',['0','8'])
                    ->orderBy('stt','asc')
                    ->get()->first();
                $cur_stt = $stt;
                if($check_end_view->id != null){
                    if($stt == $check_end_view->id && $pre_stt == ''){
                        $check_first_view = DB::table('lmscontents')
                            ->select(['lmscontents.stt','lmscontents.id'])
                            ->join('lmsseries','lmsseries.id','=','lmscontents.lmsseries_id')
                            ->where([
                                ['lmsseries.slug',$slug],
                                ['lmscontents.delete_status',0],
                            ])
                            ->whereNotIn('type',['0','8'])
                            ->orderBy('stt','asc')
                            ->get()->first();
                        $cur_stt = $check_first_view->id;
                    }
                }
                $lesson = [];
                $i_parent = 0;
                $data['current_lesson'] = '';
                $array_video = ['1','2','6','9'];
                $array_loop = ['1','2','3','4','6','7'];
                foreach($content_q as $r){
                    if($r->id < $stt && in_array($r->type, $array_video)){
                        $pre_lesson = $r->id;
                    }
                    if($r->id == $cur_stt){
                        if(!in_array($r->type,$array_video)){
                            //return redirect(PREFIX."learning-management/lesson/show/$combo_slug/$slug/".$pre_lesson);
                        }
                        $class_color = '#e62020';
                        $data['current_lesson'] = ($r->title == null) ? $r->bai : $r->bai ;
                        $data['current_description'] = $r->description;
                        $data['current_video'] = $r->file_path;
                        $data['current_poster'] = IMAGE_PATH_UPLOAD_LMS_CONTENTS. $r->image;
                        $data['contentslug'] = $r->id;
                        $data['url_excer'] = PREFIX.'learning-management/lesson/exercise/'.$slug.'/'.$r->id;
                    }
                }
                $new = $this->dequy_showLesson(['content_q' => $content_q, 'content_view' => $content_view,
                    'stt' => $cur_stt, 'slug' => $slug, 'parent_id' => null,'combo_slug' => $combo_slug,'lms_type' => $data['hi_koi']->type_series]);
                $lesson = '';
                $is_loop = false;
                $i = 0;
                foreach($new as $r){
                    if(!in_array($r['type'], $array_loop) && $is_loop === true){
                        $is_loop = 'end';
                    }
                    if($r['level'] == '0' && $i > 0){
                        $lesson .= '</ul>';
                    }
                    if($is_loop === 'end'){
                        $lesson .= '</ul></li>';
                        $is_loop = false;
                    }
                    if($r['type'] != 8){
                        $lesson .= $r['tag'];
                    }elseif ($r['type'] == 8) {
                        $lesson .= $r['tag'];
                        $is_loop = true;
                    }
                    $i++;
                }
            }else{
                $check_hocthu  = DB::table('lmscontents')
                    ->join('lmsseries','lmsseries.id','=','lmscontents.lmsseries_id')
                    ->where([
                        ['lmsseries.delete_status',0],
                        ['lmsseries.slug',$slug],
                        ['lmscontents.delete_status',0],
                        ['lmscontents.el_try',1]
                    ])
                    ->distinct()
                    ->count();
                if ($check_hocthu == 0){
                    flash('Yêu cầu sở hữu khóa học', 'Vui lòng sở hữu khóa học để xem nội này', 'error');
                    return redirect('/payments/lms/'.$combo_slug);
                }
                # check viewd lesson + time view
                $content_view = DB::table('lmscontents')
                    ->select(['lmscontents.*'])
                    ->join('lmsseries','lmsseries.id','=','lmscontents.lmsseries_id')
                    ->where([
                        ['lmsseries.slug',$slug],
                        ['lmscontents.delete_status',0]
                    ])
                    ->whereNotIn('type',['0','8'])
                    ->orderBy('stt','desc')
                    ->get();
                if ($stt == ''){
                    $stt = $content_view[0]->id;
                }
                $cur_stt = $stt;
                $data['current_lesson'] = '';
                $array_video = ['1','2','6','9'];
                $array_loop = ['1','2','3','4','6','7'];
                foreach($content_q as $r){
                    if($r->id < $stt && in_array($r->type, $array_video)){
                        $pre_lesson = $r->id;
                    }
                    if($r->id == $cur_stt){
                        if(!in_array($r->type,$array_video)){
                            //return redirect(PREFIX."learning-management/lesson/show/$combo_slug/$slug/".$pre_lesson);
                        }
                        $class_color = '#e62020';
                        $data['current_lesson'] = ($r->title == null) ? $r->bai : $r->bai ;
                        $data['current_description'] = $r->description;
                        $data['current_video'] = ($r->el_try == 1 ? $r->file_path : null );
                        $data['current_poster'] = IMAGE_PATH_UPLOAD_LMS_CONTENTS. $r->image;
                        $data['contentslug'] = $r->id;
                        $data['url_excer'] = PREFIX.'learning-management/lesson/exercise/'.$slug.'/'.$r->id;
                    }
                }
                $new = $this->dequy_tryshowLesson(['content_q' => $content_q,
                    'content_view' => $content_view,'stt' => $stt, 'slug' => $slug, 'parent_id' => null,'combo_slug' => $combo_slug,]);
                $lesson = '';
                $is_loop = false;
                $i = 0;
                foreach($new as $r){
                    if(!in_array($r['type'], $array_loop) && $is_loop === true){
                        $is_loop = 'end';
                    }
                    if($r['level'] == '0' && $i > 0){
                        $lesson .= '</ul>';
                    }
                    if($is_loop === 'end'){
                        $lesson .= '</ul></li>';
                        $is_loop = false;
                    }
                    if($r['type'] != 8){
                        $lesson .= $r['tag'];
                    }elseif ($r['type'] == 8) {
                        $lesson .= $r['tag'];
                        $is_loop = true;
                    }
                    $i++;
                }
            }
            $data['lesson_menu'] = $lesson;
        }
     //return $data['lesson_menu'];
       return (object)array('lesson_menu' => $data['lesson_menu'],
           'current_series' => $data['current_series'],
           'hi_combo'=>$data['hi_combo'],
           'hi_koi' =>$data['hi_koi'],
           'current_lesson' =>$data['current_lesson'],
           );
    }




    public function flashcard_get_right_menu ($combo_slug, $slug) {
        // get right menu
        $data['current_series'] = DB::table('lmsseries')
            ->where([
                ['slug',$slug],
            ])->first();
        $data['hi_combo'] = DB::table('lmsseries_combo')
                ->where('slug',$combo_slug)
                ->where('delete_status',0)
                ->get()->first();
        if (Auth::check()){
            $data['total_course'] = DB::table('lmsseries')
                ->join('lmscontents','lmsseries.id','=','lmscontents.lmsseries_id')
                ->where([
                    ['lmsseries.delete_status',0],
                    ['lmsseries.slug',$slug],
                    ['lmscontents.delete_status',0],
                ])
                ->whereNotIn('lmscontents.type', [0,8])
                ->distinct()
                ->count();

            $data['current_course'] = DB::table('lms_student_view')
                ->join('lmscontents','lmscontents.id','=','lms_student_view.lmscontent_id')
                ->join('lmsseries','lmsseries.id','=','lmscontents.lmsseries_id')
                ->where([
                    ['users_id',Auth::id()],
                    ['lmsseries.slug',$slug],
                    ['lms_student_view.finish',1],
                    ['lmscontents.delete_status',0],
                    ['lmsseries.delete_status',0],
                ])
                ->whereNotIn('lmscontents.type', [0,8])
                ->distinct()
                ->count();

        }else{
            $data['current_course'] = null;
        }
        return $data;
    }


    public function flashcard_detail_content($id) {
        //check
      // dd($id);
        $lmscontents = $records = DB::table('lmscontents')->where('id', $id)->first();
        if($lmscontents && $lmscontents->type == 10 ) {
          $lms_flashcard_detail = DB::table('lms_flashcard')
            ->join('lms_flashcard_detail','lms_flashcard.id','=','lms_flashcard_detail.flashcard_id')
            ->where('lms_flashcard.id', $lmscontents->flashcard_id)
            ->select('lms_flashcard_detail.*' )
            ->get();
        } else {
          flash('error', 'Không có Flashcard', 'error');
          return back();
        }
        return  $lms_flashcard_detail;
    }

    public function flashCard(Request $request,$combo_slug = '',$slug = '',$stt = ''){
        try {

             $records  = $this->flashcard_detail_content($stt);
             $data = $this->flashcard_get_right_menu($combo_slug, $slug);

            if (!Auth::check()){
                $data['url_categories'] = PREFIX.'lms/exam-categories/list';
                $content_q = DB::table('lmscontents')
                    ->select('lmscontents.*')
                    ->join('lmsseries','lmsseries.id','=','lmscontents.lmsseries_id')
                    ->where([
                        ['lmsseries.slug',$slug],
                        ['lmscontents.delete_status',0]
                    ])
                    ->orderBy('stt','asc')
                    ->get();

                if($content_q->isEmpty()){
                    // dd('empty content');
                    flash('Error', 'Không có bài học', 'error');
                    return back();
                }
                $check_hocthu  = DB::table('lmscontents')
                    ->join('lmsseries','lmsseries.id','=','lmscontents.lmsseries_id')
                    ->where([
                        ['lmsseries.delete_status',0],
                        ['lmsseries.slug',$slug],
                        ['lmscontents.delete_status',0],
                        ['lmscontents.el_try',1]
                    ])
                    ->distinct()
                    ->count();
                if ($check_hocthu == 0){
                    flash('Yêu cầu sở hữu khóa học', 'Vui lòng sở hữu khóa học để xem nội này', 'error');
                    return redirect('/home');
                }
                $data['hi_koi'] = DB::table('lmsseries')->select('lmsseries.*')  ->where([
                    ['lmsseries.slug',$slug],
                ]) ->get()->first();
                # check viewd lesson + time view
                $content_view = DB::table('lmscontents')
                    ->select(['lmscontents.*'])
                    ->join('lmsseries','lmsseries.id','=','lmscontents.lmsseries_id')
                    ->where([
                        ['lmsseries.slug',$slug],
                        ['lmscontents.delete_status',0]
                    ])
                    ->whereNotIn('type',['0','8'])
                    ->orderBy('stt','desc')
                    ->get();
                if ($stt == ''){
                    $stt = $content_view[0]->id;
                }
                $cur_stt = $stt;
                $data['current_lesson'] = '';
                $array_video = [1,2,6,9,10];
                $array_loop = ['1','2','3','4','6','7'];


                foreach($content_q as $r){
                    if($r->id < $stt && in_array($r->type, $array_video)){
                        $pre_lesson = $r->id;
                    }
                    if($r->id == $cur_stt){
                        if(!in_array($r->type,$array_video)){
                            return redirect(PREFIX."learning-management/lesson/show/$slug/".$pre_lesson);
                        }
                        $class_color = '#e62020';
                        $data['current_lesson'] = ($r->title == null) ? $r->bai : $r->bai;
                        $data['current_description'] = $r->description;
                        $data['current_video'] = ($r->el_try == 1 ? $r->file_path : null );
                        $data['current_poster'] = IMAGE_PATH_UPLOAD_LMS_CONTENTS. $r->image;
                        $data['contentslug'] = $r->id;
                        $data['url_excer'] = PREFIX.'learning-management/lesson/exercise/'.$slug.'/'.$r->id;
                        //dump($r->el_try);
                    }
                }
                $new = $this->dequy_tryshowLesson(['content_q' => $content_q,
                    'content_view' => $content_view,'stt' => $stt, 'slug' => $slug, 'parent_id' => null,'combo_slug' => $combo_slug,]);
                $lesson = '';
                $is_loop = false;
                $i = 0;
                foreach($new as $r){
                    if(!in_array($r['type'], $array_loop) && $is_loop === true){
                        $is_loop = 'end';
                    }
                    if($r['level'] == '0' && $i > 0){
                        $lesson .= '</ul>';
                    }
                    if($is_loop === 'end'){
                        $lesson .= '</ul></li>';
                        $is_loop = false;
                    }
                    if($r['type'] != 8){
                        $lesson .= $r['tag'];
                    }elseif ($r['type'] == 8) {
                        $lesson .= $r['tag'];
                        $is_loop = true;
                    }
                    $i++;
                }
                $data['lesson_menu'] = $lesson;
                //die();
            }else{
                $this->prepareContentIds($slug);
                //add finish if role user test
                if(Auth::user()->role_id == 6){
                    $_00content = DB::table('lmscontents')
                        ->select('lmscontents.*')
                        ->join('lmsseries','lmsseries.id','=','lmscontents.lmsseries_id')
                        ->where([
                            ['lmsseries.slug',$slug],
                        ])
                        ->whereNotIn('type',['0','8'])
                        ->get();
                    foreach($_00content as $r){
                        $x = DB::table('lms_student_view')
                            ->updateOrInsert(
                                [
                                    'lmscontent_id' => $r->id,
                                    'users_id'      => Auth::id(),
                                ],
                                [
                                    'finish'        => 1,
                                    'type'          => $r->type,
                                ]
                            );
                    }
                }  //add finish if role user test

                $data['url_categories'] = PREFIX.'lms/exam-categories/list';
                $content_q = DB::table('lmscontents')
                    ->select('lmscontents.*')
                    ->join('lmsseries','lmsseries.id','=','lmscontents.lmsseries_id')
                    ->where([
                        ['lmsseries.slug',$slug],
                        ['lmscontents.delete_status',0]
                    ])
                    ->orderby('stt')
                    ->get();

                # check empty content
                if($content_q->isEmpty()){
                    flash('error', 'Chưa có bài học', 'error');
                    return back();
                }

                $data['hi_koi'] = DB::table('lmsseries')->select('lmsseries.*')  ->where([
                    ['lmsseries.slug',$slug],
                ])->first();

                $data['checkpay'] = DB::table('payment_method')
                    ->select(DB::raw("(SELECT COUNT(id)  FROM payment_method WHERE payment_method.item_id 
                  = ".$data['hi_combo']->id."  AND payment_method.user_id = ".Auth::id()."  AND payment_method.status = 1 
                     AND  DATE_ADD(responseTime, INTERVAL IF(".$data['hi_combo']->time." = 0,90,IF(".$data['hi_combo']->time." = 1,180,365)) DAY) > NOW()) as payment"))
                    ->distinct()
                    ->first();

                if ($data['hi_combo']->cost == 0  || $data['checkpay']->payment > 0 || Auth::user()->role_id == 6){
                    // type_series 1: Khoa luyen thi, 0: Khoa học
                    if($data['hi_koi']->type_series == 1){
                        if ($stt == ''){
                            $id_content = DB::table('lmscontents')
                                ->select('lmscontents.*')
                                ->join('lmsseries','lmsseries.id','=','lmscontents.lmsseries_id')
                                ->where([
                                    ['lmsseries.slug',$slug],
                                    ['lmscontents.delete_status',0],
                                ])
                                ->whereNotIn('type',['0','8'])
                                ->get()->first();
                        }else{
                            $id_content = DB::table('lmscontents')
                                ->select('lmscontents.*')
                                ->where([
                                    ['lmscontents.id',$stt],
                                    ['lmscontents.delete_status',0],
                                ])
                                ->whereNotIn('type',['0','8'])
                                ->get()->first();
                        }
                        $checkviews= DB::table('lms_student_view')
                            ->where([
                                ['lms_student_view.lmscontent_id',$id_content->id],
                                ['lms_student_view.users_id',Auth::user()->id],
                            ])
                            ->get();
                        //dd($checkviews);
                        if ($checkviews->isEmpty()){
                            $new_id = DB::table('lms_student_view')
                                ->insertGetId([
                                    'lmscontent_id'       => $id_content->id,
                                    'users_id'            => Auth::id(),
                                    'view_time'           => 0,
                                    'finish'              => 0,
                                    'type'                => $id_content->type,
                                ]);
                        }
                        $content_view = DB::table('lms_student_view')
                            ->select(['lms_student_view.*','lmscontents.stt','lmscontents.slug'])
                            ->join('lmscontents','lmscontents.id','=','lms_student_view.lmscontent_id')
                            ->join('lmsseries','lmsseries.id','=','lmscontents.lmsseries_id')
                            ->where([
                                ['users_id',Auth::id()],
                                ['lmsseries.slug',$slug],
                                ['finish',0],
                                ['lmscontents.delete_status',0]
                            ])
                            ->orderBy('stt','desc')
                            ->get();
                        $data['viewed_video'] = true;
                    }else{
                        # check viewd lesson + time view
                        //check table lms_student_view da xem hay chua
                        $content_view = DB::table('lms_student_view')
                            ->select(['lms_student_view.*','lmscontents.stt','lmscontents.slug'])
                            ->join('lmscontents','lmscontents.id','=','lms_student_view.lmscontent_id')
                            ->join('lmsseries','lmsseries.id','=','lmscontents.lmsseries_id')
                            ->where([
                                ['users_id',Auth::id()],
                                ['lmsseries.slug',$slug],
                                ['finish',0],
                                ['lmscontents.delete_status',0]
                            ])
                            ->orderBy('stt','desc')
                            ->get();
                            if (Auth::user()->role_id == 5) {
                                    $new_id = DB::table('lms_student_view')
                                    ->where([
                                        'lmscontent_id'       => $stt,
                                        'users_id'            => Auth::id(),
                                    ])
                                    ->update([
                                        'finish'              => 1,
                                    ]);

                            }

                        
                        # check first view or next lesson view
                        if($content_view->isEmpty()){

                            # next lesson view
                            $viewed_content = DB::table('lms_student_view')
                                ->select(['lms_student_view.*','lmscontents.stt','lmscontents.slug'])
                                ->join('lmscontents','lmscontents.id','=','lms_student_view.lmscontent_id')
                                ->join('lmsseries','lmsseries.id','=','lmscontents.lmsseries_id')
                                ->where([
                                    ['users_id',Auth::id()],
                                    ['lmsseries.slug',$slug],
                                    ['finish',1],
                                    ['lmscontents.delete_status',0]
                                ])
                                ->orderBy('stt','desc')
                                ->get();
                            if($viewed_content->isEmpty()){
                                # check first view
                                $id_content = DB::table('lmscontents')
                                    ->select('lmscontents.*')
                                    ->join('lmsseries','lmsseries.id','=','lmscontents.lmsseries_id')
                                    ->where([
                                        ['lmscontents.slug',$stt],
                                        ['lmscontents.delete_status',0],
                                    ])
                                    ->first();

                                if (Auth::user()->role_id == 5) {
                                    $new_id = DB::table('lms_student_view')
                                    ->insertGetId([
                                        'lmscontent_id'       => $stt,
                                        'users_id'            => Auth::id(),
                                        'view_time'           => 0,
                                        'finish'              => 1,
                                        'type'                => 10,
                                    ]);
                                }

                            }else{
                                # next lesson
                                $id_content = DB::table('lmscontents')
                                    ->select('lmscontents.*')
                                    ->join('lmsseries','lmsseries.id','=','lmscontents.lmsseries_id')
                                    ->where([
                                        ['lmsseries.slug',$slug],
                                        ['lmscontents.stt','>',$viewed_content[0]->stt],
                                        ['lmscontents.delete_status',0],
                                    ])
                                    ->whereNotIn('type',['0','8'])
                                    ->orderBy('stt','asc')
                                    ->first();
                                    
                            }
                            if($id_content != null){

                                // Thêm vào view
                                if (Auth::user()->role_id == 5) {
                                  $new_id = DB::table('lms_student_view')
                                      ->insertGetId([
                                          'lmscontent_id'       => $id_content->id,
                                          'users_id'            => Auth::id(),
                                          'view_time'           => 0,
                                          'finish'              => 0,
                                          'type'                => $id_content->type,
                                      ]);
                                }
                                $content_view = DB::table('lms_student_view')
                                    ->select(['lms_student_view.*','lmscontents.stt','lmscontents.slug'])
                                    ->join('lmscontents','lmscontents.id','=','lms_student_view.lmscontent_id')
                                    ->join('lmsseries','lmsseries.id','=','lmscontents.lmsseries_id')
                                    ->where([
                                        ['users_id',Auth::id()],
                                        ['lmsseries.slug',$slug],
                                        ['finish',1],
                                        ['lmscontents.delete_status',0]
                                    ])
                                    ->orderBy('stt','desc')
                                    ->get();
                                    // dd($content_view);
                            }else{
                                $content_view = DB::table('lms_student_view')
                                    ->select(['lms_student_view.*','lmscontents.stt','lmscontents.slug'])
                                    ->join('lmscontents','lmscontents.id','=','lms_student_view.lmscontent_id')
                                    ->join('lmsseries','lmsseries.id','=','lmscontents.lmsseries_id')
                                    ->where([
                                        ['users_id',Auth::id()],
                                        ['lmsseries.slug',$slug],
                                        // ['finish',0],
                                        ['lmscontents.delete_status',0]
                                    ])
                                    ->orderBy('stt','desc')
                                    ->get();
                            }
                        }else{
                            $this->updateAndInsertContentView($slug, $stt);
                            $data['current_time'] = $content_view[0]->view_time;
                        }
                    }
                    # check if come to page from series
                    $pre_stt = $stt;
                    if($stt == ''){
                        $stt = $content_view[0]->lmscontent_id;
                  
                    }else{
                        $existedContent = DB::table('lmscontents')
                            ->where([
                                ['id', $stt],
                                ['delete_status', 0],
                            ])
                            ->get();

                        $check_view = DB::table('lms_student_view')
                            ->select('finish')
                            ->join('lmscontents', 'lmscontents.id', '=', 'lms_student_view.lmscontent_id')
                            ->where([
                                ['users_id', Auth::id()],
                                ['lmscontents.id', $stt],
                                ['lmscontents.delete_status', 0]
                            ])
                            ->get();
                        if ($existedContent->isEmpty()) {
                            flash('', 'Bài học chưa được mở', 'error');
                            return back();
                        } else {
                            if ($check_view->isEmpty()) {
                                if (Auth::user()->role_id == Role::STUDENT) {
                                    DB::table('lms_student_view')
                                        ->insertGetId([
                                            'lmscontent_id'       => $stt,
                                            'users_id'            => Auth::id(),
                                            'view_time'           => 0, // Viewing time
                                            'finish'              => LmsStudentView::FINISH,
                                            'type'                => LmsContent::FLASHCARD,
                                        ]);
                                }
                            } else {
                                if ($check_view[0]->finish == '1') {
                                    $data['viewed_video'] = true;
                                    unset($data['current_time']);
                                }
                            }
                        }
                    }
                    # get lesson
                    $check_end_view = DB::table('lmscontents')
                        ->select(['lmscontents.stt','lmscontents.id'])
                        ->join('lmsseries','lmsseries.id','=','lmscontents.lmsseries_id')
                        ->where([
                            ['lmsseries.slug',$slug],
                            ['lmscontents.delete_status',0],
                        ])
                        ->whereNotIn('type',['0','8'])
                        ->orderBy('stt','desc')
                        ->get()->first();
                        // dd($check_end_view);

                    $cur_stt = $stt;
                    if($check_end_view->id != null){
                        if($stt == $check_end_view->id && $pre_stt == ''){
                            $check_first_view = DB::table('lmscontents')
                                ->select(['lmscontents.stt','lmscontents.id'])
                                ->join('lmsseries','lmsseries.id','=','lmscontents.lmsseries_id')
                                ->where([
                                    ['lmsseries.slug',$slug],
                                    ['lmscontents.delete_status',0],
                                ])
                                ->whereNotIn('type',['0','8'])
                                ->orderBy('stt','asc')
                                ->get()->first();
                            $cur_stt = $check_first_view->id;
                        }
                    }
                    $lesson = [];
                    $i_parent = 0;
                    $data['current_lesson'] = '';
                    $array_video = ['1','2','6','9','10'];
                    $array_loop = ['1','2','3','4','6','7'];
                    foreach($content_q as $r){
                        if($r->id < $stt && in_array($r->type, $array_video)){
                            $pre_lesson = $r->id;
                        }
                        if($r->id == $cur_stt){
                            if(!in_array($r->type,$array_video)){
                                return redirect(PREFIX."learning-management/lesson/show/$combo_slug/$slug/".$pre_lesson);
                            }
                            $class_color = '#e62020';
                            $data['current_lesson'] = ($r->title == null) ? $r->bai : $r->bai ;
                            $data['current_description'] = $r->description;
                            $data['current_video'] = $r->file_path;
                            $data['current_poster'] = IMAGE_PATH_UPLOAD_LMS_CONTENTS. $r->image;
                            $data['contentslug'] = $r->id;
                            $data['url_excer'] = PREFIX.'learning-management/lesson/exercise/'.$slug.'/'.$r->id;
                        }
                    }
                    $new = $this->dequy_showLesson(['content_q' => $content_q, 'content_view' => $content_view,
                        'stt' => $cur_stt, 'slug' => $slug, 'parent_id' => null,'combo_slug' => $combo_slug,'lms_type' => $data['hi_koi']->type_series]);
                    $lesson = '';
                    $is_loop = false;
                    $i = 0;
                    foreach($new as $r){
                        if(!in_array($r['type'], $array_loop) && $is_loop === true){
                            $is_loop = 'end';
                        }
                        if($r['level'] == '0' && $i > 0){
                            $lesson .= '</ul>';
                        }
                        if($is_loop === 'end'){
                            $lesson .= '</ul></li>';
                            $is_loop = false;
                        }
                        if($r['type'] != 8){
                            $lesson .= $r['tag'];
                        }elseif ($r['type'] == 8) {
                            $lesson .= $r['tag'];
                            $is_loop = true;
                        }
                        $i++;
                    }
                }else{
                    $check_hocthu  = DB::table('lmscontents')
                        ->join('lmsseries','lmsseries.id','=','lmscontents.lmsseries_id')
                        ->where([
                            ['lmsseries.delete_status',0],
                            ['lmsseries.slug',$slug],
                            ['lmscontents.delete_status',0],
                            ['lmscontents.el_try',1]
                        ])
                        ->distinct()
                        ->count();
                    if ($check_hocthu == 0){
                        flash('Yêu cầu sở hữu khóa học', 'Vui lòng sở hữu khóa học để xem nội này', 'error');
                        return redirect('/payments/lms/'.$combo_slug);
                    }
                    # check viewd lesson + time view
                    $content_view = DB::table('lmscontents')
                        ->select(['lmscontents.*'])
                        ->join('lmsseries','lmsseries.id','=','lmscontents.lmsseries_id')
                        ->where([
                            ['lmsseries.slug',$slug],
                            ['lmscontents.delete_status',0]
                        ])
                        ->whereNotIn('type',['0','8'])
                        ->orderBy('stt','desc')
                        ->get();
                    if ($stt == ''){
                        $stt = $content_view[0]->id;
                    }
                    $cur_stt = $stt;
                    $data['current_lesson'] = '';
                    $array_video = ['1','2','6','9','10'];
                    $array_loop = ['1','2','3','4','6','7'];
                    foreach($content_q as $r){
                        if($r->id < $stt && in_array($r->type, $array_video)){
                            $pre_lesson = $r->id;
                        }
                        if($r->id == $cur_stt){
                            if(!in_array($r->type,$array_video)){
                                return redirect(PREFIX."learning-management/lesson/show/$combo_slug/$slug/".$pre_lesson);
                            }
                            $class_color = '#e62020';
                            $data['current_lesson'] = ($r->title == null) ? $r->bai : $r->bai ;
                            $data['current_description'] = $r->description;
                            $data['current_video'] = ($r->el_try == 1 ? $r->file_path : null );
                            $data['current_poster'] = IMAGE_PATH_UPLOAD_LMS_CONTENTS. $r->image;
                            $data['contentslug'] = $r->id;
                            $data['url_excer'] = PREFIX.'learning-management/lesson/exercise/'.$slug.'/'.$r->id;
                        }
                    }
                    $new = $this->dequy_tryshowLesson(['content_q' => $content_q,
                        'content_view' => $content_view,'stt' => $stt, 'slug' => $slug, 'parent_id' => null,'combo_slug' => $combo_slug,]);
                    $lesson = '';
                    $is_loop = false;
                    $i = 0;
                    foreach($new as $r){
                        if(!in_array($r['type'], $array_loop) && $is_loop === true){
                            $is_loop = 'end';
                        }
                        if($r['level'] == '0' && $i > 0){
                            $lesson .= '</ul>';
                        }
                        if($is_loop === 'end'){
                            $lesson .= '</ul></li>';
                            $is_loop = false;
                        }
                        if($r['type'] != 8){
                            $lesson .= $r['tag'];
                        }elseif ($r['type'] == 8) {
                            $lesson .= $r['tag'];
                            $is_loop = true;
                        }
                        $i++;
                    }
                }
                $data['lesson_menu'] = $lesson;
            }
            // get commments
            $data['comment'] = DB::table('comments')
                ->where([
                    ['user_id',Auth::id()],
                    ['lmsseries_id',$data['hi_koi']->id],
                    ['lmscombo_id',$data['hi_combo']->id],
                    ['lmscontent_id',$stt],
                    ['parent_id',0],
                ])
                ->get();
            $data['comment_child'] = DB::table('comments')
                ->where([
                    ['user_id',Auth::id()],
                    ['lmsseries_id',$data['hi_koi']->id],
                    ['lmscombo_id',$data['hi_combo']->id],
                    ['lmscontent_id',$stt],
                    ['parent_id','!=',0],
                ])
                ->get();
            try {
                DB::table('comments')
                    ->where([
                        ['user_id',Auth::id()],
                        ['lmsseries_id',$data['hi_koi']->id],
                        ['lmscombo_id',$data['hi_combo']->id],
                        ['lmscontent_id',$stt],
                        ['parent_id',0],
                    ])
                    ->update(
                        [
                            'status' => 2,
                            'updated_at' =>date("Y-m-d H:i:s"),
                        ]
                    );
            }catch(Exception $e){
            }
        }catch (\Exception $e) {
            return $e->getMessage();
        }
        $data['class']              = 'exams';
        $data['title']              = 'Khóa học Tổng hợp - Luyện thi N4';
        $data['series']             = $slug;;
        $data['slug']               = $stt;
        $data['combo_slug']         = $combo_slug;
        $data['records']            = $records;
        $data['count_records']      = count($records);
        $data['active_class']       = 'exams';
        $data['layout']             = getLayout();
        // dd($data);
        $view_name = 'admin.student.flashcard.content-flashcard';
        return view($view_name, $data);
    }

}