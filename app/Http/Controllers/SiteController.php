<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use \App;
use \App\UserSubscription;
use \App\Quiz;
use App\User;
use \App\LmsSeries;
use App\LmsSeriesCombo;
use \App\PaymentMethod;
use Illuminate\Support\Facades\Auth;
use Response;
use Exception;
use App\Logger;
use App\Services\LmsSeriesComboService;
use DB;
class SiteController extends Controller
{
    private $lmsSeriesComboService;

    public function __construct(LmsSeriesComboService $lmsSeriesComboService)
    {
        $this->lmsSeriesComboService = $lmsSeriesComboService;
    }

  public function index()
  {
    $current_theme            = getDefaultTheme();
    $data['home_title']       = getThemeSetting('home_page_title',$current_theme);
    $data['home_link']        = getThemeSetting('home_page_link',$current_theme);
    $data['home_image']       = getThemeSetting('home_page_image',$current_theme);
    $data['home_back_image']  = getThemeSetting('home_page_background_image',$current_theme);
    $data['key'] = 'home';
    $data['active_class'] = 'home';
    $firstSeries  = LmsSeries::where('is_paid', 1)
    ->where('total_items','>',0)
    ->limit(3)
    ->get();
     // sendEmail('taodonhang', array('name'=>'123', 'donhang'=>'123', 'gia'=>'123', 'thongtinchuyenkhoan'=> '123','to_email' => 'phuocpham1988@gmail.com'));


    $data['lms_series'] = $firstSeries;

    ///// KHóa luyện thi
    /*$data['series'] = DB::table('lmsseries')
        ->select('lmsseries.*',DB::raw("(SELECT COUNT(id)  FROM lmscontents
        WHERE lmscontents.delete_status = 0 AND type NOT IN(0,8) AND lmscontents.lmsseries_id = lmsseries.id) as lmscontents"),
            DB::raw("(SELECT COUNT(id)  FROM lmscontents
        WHERE lmscontents.delete_status = 0 AND lmscontents.el_try = 1 AND type NOT IN(0,8) AND lmscontents.lmsseries_id = lmsseries.id) as try_lmscontents"))
    ->where([
      ['lmsseries.delete_status',0],
      ['type_series',1],
  ])
    ->orderBy('order_by')
        ->distinct()
    ->get();*/

      //dd(Auth::user()->role_id);

      $data['series'] = DB::table('lmsseries_combo')
          ->select('lmsseries_combo.*',DB::raw("(SELECT COUNT(id)  FROM lmscontents
        WHERE lmscontents.delete_status = 0 AND type NOT IN(0,8) AND lmscontents.lmsseries_id IN (lmsseries_combo.n1,lmsseries_combo.n2,lmsseries_combo.n3,lmsseries_combo.n4,lmsseries_combo.n5)) as lmscontents")
              ,DB::raw("(SELECT COUNT(id)  FROM lmscontents
        WHERE lmscontents.delete_status = 0 AND lmscontents.el_try = 1 AND type NOT IN(0,8) AND
            lmscontents.lmsseries_id IN (lmsseries_combo.n1,lmsseries_combo.n2,lmsseries_combo.n3,lmsseries_combo.n4,lmsseries_combo.n5)) as try_lmscontents"),
              DB::raw("(SELECT slug  FROM lmsseries WHERE lmsseries.id IN (lmsseries_combo.n1,lmsseries_combo.n2,lmsseries_combo.n3,lmsseries_combo.n4,lmsseries_combo.n5)
              AND lmsseries_combo.total_items = 1 ) as slug_lmscontents")
          )
          ->where([
              ['lmsseries_combo.delete_status',0],
              ['type',1],
          ])
          ->distinct()
          ->get();

      if (Auth::check() && (Auth::check() && Auth::user()->role_id != 6)){
          $data['series'] = DB::table('lmsseries_combo')
              ->select('lmsseries_combo.*',DB::raw("(SELECT COUNT(id)  FROM lmscontents
        WHERE lmscontents.delete_status = 0 AND type NOT IN(0,8) AND lmscontents.lmsseries_id IN (lmsseries_combo.n1,lmsseries_combo.n2,lmsseries_combo.n3,lmsseries_combo.n4,lmsseries_combo.n5)) as lmscontents")
                  ,DB::raw("(SELECT COUNT(id)  FROM lmscontents
        WHERE lmscontents.delete_status = 0 AND lmscontents.el_try = 1 AND type NOT IN(0,8) AND
            lmscontents.lmsseries_id IN (lmsseries_combo.n1,lmsseries_combo.n2,lmsseries_combo.n3,lmsseries_combo.n4,lmsseries_combo.n5)) as try_lmscontents"),
                  DB::raw("(SELECT slug  FROM lmsseries WHERE lmsseries.id IN (lmsseries_combo.n1,lmsseries_combo.n2,lmsseries_combo.n3,lmsseries_combo.n4,lmsseries_combo.n5)
              AND lmsseries_combo.total_items = 1 ) as slug_lmscontents"),
                  DB::raw("(SELECT COUNT(id)  FROM payment_method WHERE payment_method.item_id
                  = lmsseries_combo.id AND payment_method.user_id = ".Auth::id()."
                  AND payment_method.status = 1
                  AND DATE_ADD(responseTime, INTERVAL IF(lmsseries_combo.time = 0,90,IF(lmsseries_combo.time = 1,180,365)) DAY) > NOW()) as payment")
              )
              ->where([
                  ['lmsseries_combo.delete_status',0],
                  ['type',1]
              ])
              ->distinct()
              ->get();

      }

 // N5
      /*$data['series_5'] = DB::table('lmsseries')
          ->select('lmsseries.*',DB::raw("(SELECT COUNT(id)  FROM lmscontents
           WHERE lmscontents.delete_status = 0 AND type NOT IN(0,8) AND lmscontents.lmsseries_id = lmsseries.id) as lmscontents"),
              DB::raw("(SELECT COUNT(id)  FROM lmscontents
        WHERE lmscontents.delete_status = 0 AND lmscontents.el_try = 1 AND type NOT IN(0,8) AND lmscontents.lmsseries_id = lmsseries.id) as try_lmscontents"))
          ->where([
              ['lmsseries.delete_status',0],
              ['lmsseries.lms_category_id',5],
              ['type_series',1]
          ])
          ->orderBy('order_by')
          ->distinct()
          ->get();*/
      $data['series_5'] = DB::table('lmsseries_combo')
          ->select('lmsseries_combo.*',DB::raw("(SELECT COUNT(id)  FROM lmscontents
        WHERE lmscontents.delete_status = 0 AND type NOT IN(0,8) AND lmscontents.lmsseries_id IN (lmsseries_combo.n1,lmsseries_combo.n2,lmsseries_combo.n3,lmsseries_combo.n4,lmsseries_combo.n5)) as lmscontents")
              ,DB::raw("(SELECT COUNT(id)  FROM lmscontents
        WHERE lmscontents.delete_status = 0 AND lmscontents.el_try = 1 AND type NOT IN(0,8) AND
            lmscontents.lmsseries_id IN (lmsseries_combo.n1,lmsseries_combo.n2,lmsseries_combo.n3,lmsseries_combo.n4,lmsseries_combo.n5)) as try_lmscontents"),
              DB::raw("(SELECT slug  FROM lmsseries WHERE lmsseries.id IN (lmsseries_combo.n1,lmsseries_combo.n2,lmsseries_combo.n3,lmsseries_combo.n4,lmsseries_combo.n5)
              AND lmsseries_combo.total_items = 1 ) as slug_lmscontents" )
          )
          ->where([
              ['lmsseries_combo.delete_status',0],
              ['type',1]
          ])
          ->whereNotNull('lmsseries_combo.n5')
          ->distinct()
          ->get();


      if (Auth::check() && (Auth::check() && Auth::user()->role_id != 6)){
          $data['series_5'] = DB::table('lmsseries_combo')
              ->select('lmsseries_combo.*',DB::raw("(SELECT COUNT(id)  FROM lmscontents
        WHERE lmscontents.delete_status = 0 AND type NOT IN(0,8) AND lmscontents.lmsseries_id IN (lmsseries_combo.n1,lmsseries_combo.n2,lmsseries_combo.n3,lmsseries_combo.n4,lmsseries_combo.n5)) as lmscontents")
                  ,DB::raw("(SELECT COUNT(id)  FROM lmscontents
        WHERE lmscontents.delete_status = 0 AND lmscontents.el_try = 1 AND type NOT IN(0,8) AND
            lmscontents.lmsseries_id IN (lmsseries_combo.n1,lmsseries_combo.n2,lmsseries_combo.n3,lmsseries_combo.n4,lmsseries_combo.n5)) as try_lmscontents"),
                  DB::raw("(SELECT slug  FROM lmsseries WHERE lmsseries.id IN (lmsseries_combo.n1,lmsseries_combo.n2,lmsseries_combo.n3,lmsseries_combo.n4,lmsseries_combo.n5)
              AND lmsseries_combo.total_items = 1 ) as slug_lmscontents"),
                  DB::raw("(SELECT COUNT(id)  FROM payment_method WHERE payment_method.item_id
                  = lmsseries_combo.id AND payment_method.user_id = ".Auth::id()."
                   AND payment_method.status = 1
                   AND DATE_ADD(responseTime, INTERVAL IF(lmsseries_combo.time = 0,90,IF(lmsseries_combo.time = 1,180,365)) DAY) > NOW()) as payment")
              )
              ->where([
                  ['lmsseries_combo.delete_status',0],
                  ['type',1]
              ])
              ->whereNotNull('lmsseries_combo.n5')
              ->distinct()
              ->get();

      }

      // N4
      /*$data['series_4'] = DB::table('lmsseries')
          ->select('lmsseries.*',DB::raw("(SELECT COUNT(id)  FROM lmscontents
            WHERE lmscontents.delete_status = 0 AND type NOT IN(0,8) AND lmscontents.lmsseries_id = lmsseries.id) as lmscontents"),
              DB::raw("(SELECT COUNT(id)  FROM lmscontents
        WHERE lmscontents.delete_status = 0 AND lmscontents.el_try = 1 AND type NOT IN(0,8) AND lmscontents.lmsseries_id = lmsseries.id) as try_lmscontents"))
          ->where([
              ['lmsseries.delete_status',0],
              ['lmsseries.lms_category_id',4],
              ['type_series',1]
          ])
          ->orderBy('order_by')
          ->distinct()
          ->get();*/
      $data['series_4'] = DB::table('lmsseries_combo')
          ->select('lmsseries_combo.*',DB::raw("(SELECT COUNT(id)  FROM lmscontents
        WHERE lmscontents.delete_status = 0 AND type NOT IN(0,8) AND lmscontents.lmsseries_id IN (lmsseries_combo.n1,lmsseries_combo.n2,lmsseries_combo.n3,lmsseries_combo.n4,lmsseries_combo.n5)) as lmscontents")
              ,DB::raw("(SELECT COUNT(id)  FROM lmscontents
        WHERE lmscontents.delete_status = 0 AND lmscontents.el_try = 1 AND type NOT IN(0,8) AND
            lmscontents.lmsseries_id IN (lmsseries_combo.n1,lmsseries_combo.n2,lmsseries_combo.n3,lmsseries_combo.n4,lmsseries_combo.n5)) as try_lmscontents"),
              DB::raw("(SELECT slug  FROM lmsseries WHERE lmsseries.id IN (lmsseries_combo.n1,lmsseries_combo.n2,lmsseries_combo.n3,lmsseries_combo.n4,lmsseries_combo.n5)
              AND lmsseries_combo.total_items = 1 ) as slug_lmscontents" )
          )
          ->where([
              ['lmsseries_combo.delete_status',0],
              ['type',1]
          ])
          ->whereNotNull('lmsseries_combo.n4')
          ->distinct()
          ->get();

      if (Auth::check() && (Auth::check() && Auth::user()->role_id != 6)){
          $data['series_4'] = DB::table('lmsseries_combo')
              ->select('lmsseries_combo.*',DB::raw("(SELECT COUNT(id)  FROM lmscontents
        WHERE lmscontents.delete_status = 0 AND type NOT IN(0,8) AND lmscontents.lmsseries_id IN (lmsseries_combo.n1,lmsseries_combo.n2,lmsseries_combo.n3,lmsseries_combo.n4,lmsseries_combo.n5)) as lmscontents")
                  ,DB::raw("(SELECT COUNT(id)  FROM lmscontents
        WHERE lmscontents.delete_status = 0 AND lmscontents.el_try = 1 AND type NOT IN(0,8) AND
            lmscontents.lmsseries_id IN (lmsseries_combo.n1,lmsseries_combo.n2,lmsseries_combo.n3,lmsseries_combo.n4,lmsseries_combo.n5)) as try_lmscontents"),
                  DB::raw("(SELECT slug  FROM lmsseries WHERE lmsseries.id IN (lmsseries_combo.n1,lmsseries_combo.n2,lmsseries_combo.n3,lmsseries_combo.n4,lmsseries_combo.n5)
              AND lmsseries_combo.total_items = 1 ) as slug_lmscontents"),
                  DB::raw("(SELECT COUNT(id)  FROM payment_method WHERE payment_method.item_id
                  = lmsseries_combo.id  AND payment_method.user_id = ".Auth::id()."
                     AND payment_method.status = 1
                     AND DATE_ADD(responseTime, INTERVAL IF(lmsseries_combo.time = 0,90,IF(lmsseries_combo.time = 1,180,365)) DAY) > NOW()) as payment")
              )
              ->where([
                  ['lmsseries_combo.delete_status',0],
                  ['type',1]
              ])
              ->whereNotNull('lmsseries_combo.n4')
              ->distinct()
              ->get();

      }
      // N3
      /*$data['series_3'] = DB::table('lmsseries')
          ->select('lmsseries.*',DB::raw("(SELECT COUNT(id)  FROM lmscontents
           WHERE lmscontents.delete_status = 0 AND type NOT IN(0,8) AND lmscontents.lmsseries_id = lmsseries.id) as lmscontents"),
              DB::raw("(SELECT COUNT(id)  FROM lmscontents
        WHERE lmscontents.delete_status = 0 AND lmscontents.el_try = 1 AND type NOT IN(0,8) AND lmscontents.lmsseries_id = lmsseries.id) as try_lmscontents"))
          ->where([
              ['lmsseries.delete_status',0],
              ['lmsseries.lms_category_id',3],
              ['type_series',1]
          ])
          ->orderBy('order_by')
          ->distinct()
          ->get();*/

      $data['series_3'] = DB::table('lmsseries_combo')
          ->select('lmsseries_combo.*',DB::raw("(SELECT COUNT(id)  FROM lmscontents
        WHERE lmscontents.delete_status = 0 AND type NOT IN(0,8) AND lmscontents.lmsseries_id IN (lmsseries_combo.n1,lmsseries_combo.n2,lmsseries_combo.n3,lmsseries_combo.n4,lmsseries_combo.n5)) as lmscontents")
              ,DB::raw("(SELECT COUNT(id)  FROM lmscontents
        WHERE lmscontents.delete_status = 0 AND lmscontents.el_try = 1 AND type NOT IN(0,8) AND
            lmscontents.lmsseries_id IN (lmsseries_combo.n1,lmsseries_combo.n2,lmsseries_combo.n3,lmsseries_combo.n4,lmsseries_combo.n5)) as try_lmscontents"),
              DB::raw("(SELECT slug  FROM lmsseries WHERE lmsseries.id IN (lmsseries_combo.n1,lmsseries_combo.n2,lmsseries_combo.n3,lmsseries_combo.n4,lmsseries_combo.n5)
              AND lmsseries_combo.total_items = 1 ) as slug_lmscontents" )
          )
          ->where([
              ['lmsseries_combo.delete_status',0],
              ['type',1]
          ])
          ->whereNotNull('lmsseries_combo.n3')
          ->distinct()
          ->get();
      if (Auth::check() && (Auth::check() && Auth::user()->role_id != 6)){
          $data['series_3'] = DB::table('lmsseries_combo')
              ->select('lmsseries_combo.*',DB::raw("(SELECT COUNT(id)  FROM lmscontents
        WHERE lmscontents.delete_status = 0 AND type NOT IN(0,8) AND lmscontents.lmsseries_id IN (lmsseries_combo.n1,lmsseries_combo.n2,lmsseries_combo.n3,lmsseries_combo.n4,lmsseries_combo.n5)) as lmscontents")
                  ,DB::raw("(SELECT COUNT(id)  FROM lmscontents
        WHERE lmscontents.delete_status = 0 AND lmscontents.el_try = 1 AND type NOT IN(0,8) AND
            lmscontents.lmsseries_id IN (lmsseries_combo.n1,lmsseries_combo.n2,lmsseries_combo.n3,lmsseries_combo.n4,lmsseries_combo.n5)) as try_lmscontents"),
                  DB::raw("(SELECT slug  FROM lmsseries WHERE lmsseries.id IN (lmsseries_combo.n1,lmsseries_combo.n2,lmsseries_combo.n3,lmsseries_combo.n4,lmsseries_combo.n5)
              AND lmsseries_combo.total_items = 1 ) as slug_lmscontents"),
                  DB::raw("(SELECT COUNT(id)  FROM payment_method WHERE payment_method.item_id
                  = lmsseries_combo.id  AND payment_method.user_id = ".Auth::id()."  AND payment_method.status = 1
                     AND DATE_ADD(responseTime, INTERVAL IF(lmsseries_combo.time = 0,90,IF(lmsseries_combo.time = 1,180,365)) DAY) > NOW()) as payment")
              )
              ->where([
                  ['lmsseries_combo.delete_status',0],
                  ['type',1]
              ])
              ->whereNotNull('lmsseries_combo.n3')
              ->distinct()
              ->get();

      }


////// Khóa học

      $data['series_el'] = DB::table('lmsseries_combo')
          ->select('lmsseries_combo.*',DB::raw("(SELECT COUNT(id)  FROM lmscontents
        WHERE lmscontents.delete_status = 0 AND type NOT IN(0,8) AND lmscontents.lmsseries_id IN (lmsseries_combo.n1,lmsseries_combo.n2,lmsseries_combo.n3,lmsseries_combo.n4,lmsseries_combo.n5)) as lmscontents")
              ,DB::raw("(SELECT COUNT(id)  FROM lmscontents
        WHERE lmscontents.delete_status = 0 AND lmscontents.el_try = 1 AND type NOT IN(0,8) AND
            lmscontents.lmsseries_id IN (lmsseries_combo.n1,lmsseries_combo.n2,lmsseries_combo.n3,lmsseries_combo.n4,lmsseries_combo.n5)) as try_lmscontents"),
              DB::raw("(SELECT slug  FROM lmsseries WHERE lmsseries.id IN (lmsseries_combo.n1,lmsseries_combo.n2,lmsseries_combo.n3,lmsseries_combo.n4,lmsseries_combo.n5)
              AND lmsseries_combo.total_items = 1 ) as slug_lmscontents" )
          )
          ->where([
              ['lmsseries_combo.delete_status',0],
              ['type',0]
          ])

          ->distinct()
          ->get();

      if (Auth::check() && (Auth::check() && Auth::user()->role_id != 6)){
          $data['series_el'] = DB::table('lmsseries_combo')
              ->select('lmsseries_combo.*',DB::raw("(SELECT COUNT(id)  FROM lmscontents
        WHERE lmscontents.delete_status = 0 AND type NOT IN(0,8) AND lmscontents.lmsseries_id IN (lmsseries_combo.n1,lmsseries_combo.n2,lmsseries_combo.n3,lmsseries_combo.n4,lmsseries_combo.n5)) as lmscontents")
                  ,DB::raw("(SELECT COUNT(id)  FROM lmscontents
        WHERE lmscontents.delete_status = 0 AND lmscontents.el_try = 1 AND type NOT IN(0,8) AND
            lmscontents.lmsseries_id IN (lmsseries_combo.n1,lmsseries_combo.n2,lmsseries_combo.n3,lmsseries_combo.n4,lmsseries_combo.n5)) as try_lmscontents"),
                  DB::raw("(SELECT slug  FROM lmsseries WHERE lmsseries.id IN (lmsseries_combo.n1,lmsseries_combo.n2,lmsseries_combo.n3,lmsseries_combo.n4,lmsseries_combo.n5)
              AND lmsseries_combo.total_items = 1 ) as slug_lmscontents"),
                  DB::raw("(SELECT COUNT(id)  FROM payment_method WHERE payment_method.item_id
                  = lmsseries_combo.id  AND payment_method.user_id = ".Auth::id()."  AND payment_method.status = 1
                     AND DATE_ADD(responseTime, INTERVAL IF(lmsseries_combo.time = 0,90,IF(lmsseries_combo.time = 1,180,365)) DAY) > NOW()) as payment")
              )
              ->where([
                  ['lmsseries_combo.delete_status',0],
                  ['type',0],

              ])
              ->distinct()
              ->get();

      }

      $data['series_el5'] = DB::table('lmsseries_combo')
          ->select('lmsseries_combo.*',DB::raw("(SELECT COUNT(id)  FROM lmscontents
        WHERE lmscontents.delete_status = 0 AND type NOT IN(0,8) AND lmscontents.lmsseries_id IN (lmsseries_combo.n1,lmsseries_combo.n2,lmsseries_combo.n3,lmsseries_combo.n4,lmsseries_combo.n5)) as lmscontents")
              ,DB::raw("(SELECT COUNT(id)  FROM lmscontents
        WHERE lmscontents.delete_status = 0 AND lmscontents.el_try = 1 AND type NOT IN(0,8) AND
            lmscontents.lmsseries_id IN (lmsseries_combo.n1,lmsseries_combo.n2,lmsseries_combo.n3,lmsseries_combo.n4,lmsseries_combo.n5)) as try_lmscontents"),
              DB::raw("(SELECT slug  FROM lmsseries WHERE lmsseries.id IN (lmsseries_combo.n1,lmsseries_combo.n2,lmsseries_combo.n3,lmsseries_combo.n4,lmsseries_combo.n5)
              AND lmsseries_combo.total_items = 1 ) as slug_lmscontents" )
          )
          ->where([
              ['lmsseries_combo.delete_status',0],
              ['type',0]
          ])
          ->whereNotNull('lmsseries_combo.n5')
          ->distinct()
          ->get();

      if (Auth::check() && (Auth::check() && Auth::user()->role_id != 6)){
          $data['series_el5'] = DB::table('lmsseries_combo')
              ->select('lmsseries_combo.*',DB::raw("(SELECT COUNT(id)  FROM lmscontents
        WHERE lmscontents.delete_status = 0 AND type NOT IN(0,8) AND lmscontents.lmsseries_id IN (lmsseries_combo.n1,lmsseries_combo.n2,lmsseries_combo.n3,lmsseries_combo.n4,lmsseries_combo.n5)) as lmscontents")
                  ,DB::raw("(SELECT COUNT(id)  FROM lmscontents
        WHERE lmscontents.delete_status = 0 AND lmscontents.el_try = 1 AND type NOT IN(0,8) AND
            lmscontents.lmsseries_id IN (lmsseries_combo.n1,lmsseries_combo.n2,lmsseries_combo.n3,lmsseries_combo.n4,lmsseries_combo.n5)) as try_lmscontents"),
                  DB::raw("(SELECT slug  FROM lmsseries WHERE lmsseries.id IN (lmsseries_combo.n1,lmsseries_combo.n2,lmsseries_combo.n3,lmsseries_combo.n4,lmsseries_combo.n5)
              AND lmsseries_combo.total_items = 1 ) as slug_lmscontents"),
                  DB::raw("(SELECT COUNT(id)  FROM payment_method WHERE payment_method.item_id
                  = lmsseries_combo.id  AND payment_method.user_id = ".Auth::id()."   AND payment_method.status = 1
                    AND DATE_ADD(responseTime, INTERVAL IF(lmsseries_combo.time = 0,90,IF(lmsseries_combo.time = 1,180,365)) DAY) > NOW()) as payment")
              )
              ->where([
                  ['lmsseries_combo.delete_status',0],
                  ['type',0]
              ])
              ->whereNotNull('lmsseries_combo.n5')
              ->distinct()
              ->get();

      }


      $data['series_el4'] = DB::table('lmsseries_combo')
          ->select('lmsseries_combo.*',DB::raw("(SELECT COUNT(id)  FROM lmscontents
        WHERE lmscontents.delete_status = 0 AND type NOT IN(0,8) AND lmscontents.lmsseries_id IN (lmsseries_combo.n1,lmsseries_combo.n2,lmsseries_combo.n3,lmsseries_combo.n4,lmsseries_combo.n5)) as lmscontents")
              ,DB::raw("(SELECT COUNT(id)  FROM lmscontents
        WHERE lmscontents.delete_status = 0 AND lmscontents.el_try = 1 AND type NOT IN(0,8) AND
            lmscontents.lmsseries_id IN (lmsseries_combo.n1,lmsseries_combo.n2,lmsseries_combo.n3,lmsseries_combo.n4,lmsseries_combo.n5)) as try_lmscontents"),
              DB::raw("(SELECT slug  FROM lmsseries WHERE lmsseries.id IN (lmsseries_combo.n1,lmsseries_combo.n2,lmsseries_combo.n3,lmsseries_combo.n4,lmsseries_combo.n5)
              AND lmsseries_combo.total_items = 1 ) as slug_lmscontents" )
          )
          ->where([
              ['lmsseries_combo.delete_status',0],
              ['type',0]
          ])
          ->whereNotNull('lmsseries_combo.n4')
          ->distinct()
          ->get();

      if (Auth::check() && (Auth::check() && Auth::user()->role_id != 6)){
          $data['series_el4'] = DB::table('lmsseries_combo')
              ->select('lmsseries_combo.*',DB::raw("(SELECT COUNT(id)  FROM lmscontents
        WHERE lmscontents.delete_status = 0 AND type NOT IN(0,8) AND lmscontents.lmsseries_id IN (lmsseries_combo.n1,lmsseries_combo.n2,lmsseries_combo.n3,lmsseries_combo.n4,lmsseries_combo.n5)) as lmscontents")
                  ,DB::raw("(SELECT COUNT(id)  FROM lmscontents
        WHERE lmscontents.delete_status = 0 AND lmscontents.el_try = 1 AND type NOT IN(0,8) AND
            lmscontents.lmsseries_id IN (lmsseries_combo.n1,lmsseries_combo.n2,lmsseries_combo.n3,lmsseries_combo.n4,lmsseries_combo.n5)) as try_lmscontents"),
                  DB::raw("(SELECT slug  FROM lmsseries WHERE lmsseries.id IN (lmsseries_combo.n1,lmsseries_combo.n2,lmsseries_combo.n3,lmsseries_combo.n4,lmsseries_combo.n5)
              AND lmsseries_combo.total_items = 1 ) as slug_lmscontents"),
                  DB::raw("(SELECT COUNT(id)  FROM payment_method WHERE payment_method.item_id
                  = lmsseries_combo.id  AND payment_method.user_id = ".Auth::id()."   AND payment_method.status = 1
                     AND DATE_ADD(responseTime, INTERVAL IF(lmsseries_combo.time = 0,90,IF(lmsseries_combo.time = 1,180,365)) DAY) > NOW()) as payment")
              )
              ->where([
                  ['lmsseries_combo.delete_status',0],
                  ['type',0]
              ])
              ->whereNotNull('lmsseries_combo.n4')
              ->distinct()
              ->get();

      }

      $data['series_el3'] = DB::table('lmsseries_combo')
          ->select('lmsseries_combo.*',DB::raw("(SELECT COUNT(id)  FROM lmscontents
        WHERE lmscontents.delete_status = 0 AND type NOT IN(0,8) AND lmscontents.lmsseries_id IN (lmsseries_combo.n1,lmsseries_combo.n2,lmsseries_combo.n3,lmsseries_combo.n4,lmsseries_combo.n5)) as lmscontents")
              ,DB::raw("(SELECT COUNT(id)  FROM lmscontents
        WHERE lmscontents.delete_status = 0 AND lmscontents.el_try = 1 AND type NOT IN(0,8) AND
            lmscontents.lmsseries_id IN (lmsseries_combo.n1,lmsseries_combo.n2,lmsseries_combo.n3,lmsseries_combo.n4,lmsseries_combo.n5)) as try_lmscontents"),
              DB::raw("(SELECT slug  FROM lmsseries WHERE lmsseries.id IN (lmsseries_combo.n1,lmsseries_combo.n2,lmsseries_combo.n3,lmsseries_combo.n4,lmsseries_combo.n5)
              AND lmsseries_combo.total_items = 1 ) as slug_lmscontents" )
          )
          ->where([
              ['lmsseries_combo.delete_status',0],
              ['type',0]
          ])
          ->whereNotNull('lmsseries_combo.n3')
          ->distinct()
          ->get();

      if (Auth::check() && (Auth::check() && Auth::user()->role_id != 6)){
          $data['series_el5'] = DB::table('lmsseries_combo')
              ->select('lmsseries_combo.*',DB::raw("(SELECT COUNT(id)  FROM lmscontents
        WHERE lmscontents.delete_status = 0 AND type NOT IN(0,8) AND lmscontents.lmsseries_id IN (lmsseries_combo.n1,lmsseries_combo.n2,lmsseries_combo.n3,lmsseries_combo.n4,lmsseries_combo.n5)) as lmscontents")
                  ,DB::raw("(SELECT COUNT(id)  FROM lmscontents
        WHERE lmscontents.delete_status = 0 AND lmscontents.el_try = 1 AND type NOT IN(0,8) AND
            lmscontents.lmsseries_id IN (lmsseries_combo.n1,lmsseries_combo.n2,lmsseries_combo.n3,lmsseries_combo.n4,lmsseries_combo.n5)) as try_lmscontents"),
                  DB::raw("(SELECT slug  FROM lmsseries WHERE lmsseries.id IN (lmsseries_combo.n1,lmsseries_combo.n2,lmsseries_combo.n3,lmsseries_combo.n4,lmsseries_combo.n5)
              AND lmsseries_combo.total_items = 1 ) as slug_lmscontents"),
                  DB::raw("(SELECT COUNT(id)  FROM payment_method WHERE payment_method.item_id
                  = lmsseries_combo.id  AND payment_method.user_id = ".Auth::id()."  AND payment_method.status = 1
                     AND DATE_ADD(responseTime, INTERVAL IF(lmsseries_combo.time = 0,90,IF(lmsseries_combo.time = 1,180,365)) DAY) > NOW()) as payment")
              )
              ->where([
                  ['lmsseries_combo.delete_status',0],
                  ['type',0]
              ])
              ->whereNotNull('lmsseries_combo.n5')
              ->distinct()
              ->get();

      }

      /*$data['series_el'] = DB::table('lmsseries')
          ->select('lmsseries.*',DB::raw("(SELECT COUNT(id)  FROM lmscontents
          WHERE lmscontents.delete_status = 0 AND type NOT IN(0,8) AND lmscontents.lmsseries_id = lmsseries.id) as lmscontents"),
              DB::raw("(SELECT COUNT(id)  FROM lmscontents
        WHERE lmscontents.delete_status = 0 AND lmscontents.el_try = 1 AND type NOT IN(0,8) AND lmscontents.lmsseries_id = lmsseries.id) as try_lmscontents"))
          ->where([
              ['lmsseries.delete_status',0],
              ['type_series',0],
          ])
          ->orderBy('order_by')
          ->distinct()
          ->get();
      dd($data['series_el'] );*/
      /*
            //dd($data['series'] );
            // N5
            $data['series_el5'] = DB::table('lmsseries')
                ->select('lmsseries.*',DB::raw("(SELECT COUNT(id)  FROM lmscontents
                 WHERE lmscontents.delete_status = 0 AND type NOT IN(0,8) AND lmscontents.lmsseries_id = lmsseries.id) as lmscontents"),
                    DB::raw("(SELECT COUNT(id)  FROM lmscontents
              WHERE lmscontents.delete_status = 0 AND lmscontents.el_try = 1 AND type NOT IN(0,8) AND lmscontents.lmsseries_id = lmsseries.id) as try_lmscontents"))
                ->where([
                    ['lmsseries.delete_status',0],
                    ['lmsseries.lms_category_id',5],
                    ['type_series',0]
                ])
                ->orderBy('order_by')
                ->distinct()
                ->get();

            // N4
            $data['series_el4'] = DB::table('lmsseries')
                ->select('lmsseries.*',DB::raw("(SELECT COUNT(id)  FROM lmscontents
                 WHERE lmscontents.delete_status = 0 AND type NOT IN(0,8) AND lmscontents.lmsseries_id = lmsseries.id) as lmscontents"),
                    DB::raw("(SELECT COUNT(id)  FROM lmscontents
              WHERE lmscontents.delete_status = 0 AND lmscontents.el_try = 1 AND type NOT IN(0,8) AND lmscontents.lmsseries_id = lmsseries.id) as try_lmscontents"))
                ->where([
                    ['lmsseries.delete_status',0],
                    ['lmsseries.lms_category_id',4],
                    ['type_series',0]
                ])
                ->orderBy('order_by')
                ->distinct()
                ->get();

            // N3
            $data['series_el3'] = DB::table('lmsseries')
                ->select('lmsseries.*',DB::raw("(SELECT COUNT(id)  FROM lmscontents
                 WHERE lmscontents.delete_status = 0 AND type NOT IN(0,8) AND lmscontents.lmsseries_id = lmsseries.id) as lmscontents"),
                    DB::raw("(SELECT COUNT(id)  FROM lmscontents
              WHERE lmscontents.delete_status = 0 AND lmscontents.el_try = 1 AND type NOT IN(0,8) AND lmscontents.lmsseries_id = lmsseries.id) as try_lmscontents"))
                ->where([
                    ['lmsseries.delete_status',0],
                    ['lmsseries.lms_category_id',3],
                    ['type_series',3]
                ])
                ->orderBy('order_by')
                ->distinct()
                ->get();*/
    $view_name = 'admin.site.index';
    return view($view_name, $data);
}
/**
* This method will load the static pages
* @param  string $key [description]
* @return [type]      [description]
*/
public function sitePages($key='privacy-policy')
{
  $available_pages = ['privacy-policy', 'terms-conditions', 'payment-method', 'about-us','courses','study','pattren','pricing','syllabus','page-exam', 'shop', 'contact'];
  if(!in_array($key, $available_pages))
  {
    pageNotFound();
    return back();
}
$data['title']        = '';
switch ($key) {
    case 'about-us':
    $data['title'] = 'Giới thiệu';
    break;
    case 'privacy-policy':
    $data['title'] = 'Bảo mật thông tin';
    break;
    case 'page-exam':
    $data['title'] = 'Bảo mật thông tin';
    break;
    case 'terms-conditions':
    $data['title'] = 'Chính sách hoàn phí';
    break;
    case 'payment-method':
    $data['title'] = 'Hình thức thanh toán';
    break;
    case 'courses':
    $data['title'] = 'Khóa Học';
    $firstSeries  = LmsSeries::where('is_paid', 1)
//->where('total_items','>',0)
    ->get();
    $data['lms_series'] = $firstSeries;
    $firstSeries  = LmsSeries::where('is_paid', 1)
    ->where('total_items','>',0)
    ->limit(3)
    ->get();
    $data['lms_series'] = $firstSeries;




////// Khóa học
        $data['series_el'] = DB::table('lmsseries_combo')
            ->select('lmsseries_combo.*',DB::raw("(SELECT COUNT(id)  FROM lmscontents
        WHERE lmscontents.delete_status = 0 AND type NOT IN(0,8) AND lmscontents.lmsseries_id IN (lmsseries_combo.n1,lmsseries_combo.n2,lmsseries_combo.n3,lmsseries_combo.n4,lmsseries_combo.n5)) as lmscontents")
                ,DB::raw("(SELECT COUNT(id)  FROM lmscontents
        WHERE lmscontents.delete_status = 0 AND lmscontents.el_try = 1 AND type NOT IN(0,8) AND
            lmscontents.lmsseries_id IN (lmsseries_combo.n1,lmsseries_combo.n2,lmsseries_combo.n3,lmsseries_combo.n4,lmsseries_combo.n5)) as try_lmscontents"),
                DB::raw("(SELECT slug  FROM lmsseries WHERE lmsseries.id IN (lmsseries_combo.n1,lmsseries_combo.n2,lmsseries_combo.n3,lmsseries_combo.n4,lmsseries_combo.n5)
              AND lmsseries_combo.total_items = 1 ) as slug_lmscontents" )
            )
            ->where([
                ['lmsseries_combo.delete_status',0],
                ['type',0]
            ])

            ->distinct()
            ->get();

        if (Auth::check() && (Auth::check() && Auth::user()->role_id != 6)){
            $data['series_el'] = DB::table('lmsseries_combo')
                ->select('lmsseries_combo.*',DB::raw("(SELECT COUNT(id)  FROM lmscontents
        WHERE lmscontents.delete_status = 0 AND type NOT IN(0,8) AND lmscontents.lmsseries_id IN (lmsseries_combo.n1,lmsseries_combo.n2,lmsseries_combo.n3,lmsseries_combo.n4,lmsseries_combo.n5)) as lmscontents")
                    ,DB::raw("(SELECT COUNT(id)  FROM lmscontents
        WHERE lmscontents.delete_status = 0 AND lmscontents.el_try = 1 AND type NOT IN(0,8) AND
            lmscontents.lmsseries_id IN (lmsseries_combo.n1,lmsseries_combo.n2,lmsseries_combo.n3,lmsseries_combo.n4,lmsseries_combo.n5)) as try_lmscontents"),
                    DB::raw("(SELECT slug  FROM lmsseries WHERE lmsseries.id IN (lmsseries_combo.n1,lmsseries_combo.n2,lmsseries_combo.n3,lmsseries_combo.n4,lmsseries_combo.n5)
              AND lmsseries_combo.total_items = 1 ) as slug_lmscontents"),
                    DB::raw("(SELECT COUNT(id)  FROM payment_method WHERE payment_method.item_id
                  = lmsseries_combo.id  AND payment_method.user_id = ".Auth::id()."  AND payment_method.status = 1
                     AND DATE_ADD(responseTime, INTERVAL IF(lmsseries_combo.time = 0,90,IF(lmsseries_combo.time = 1,180,365)) DAY) > NOW()) as payment")
                )
                ->where([
                    ['lmsseries_combo.delete_status',0],
                    ['type',0],

                ])
                ->distinct()
                ->get();

        }

        $data['series_el5'] = DB::table('lmsseries_combo')
            ->select('lmsseries_combo.*',DB::raw("(SELECT COUNT(id)  FROM lmscontents
        WHERE lmscontents.delete_status = 0 AND type NOT IN(0,8) AND lmscontents.lmsseries_id IN (lmsseries_combo.n1,lmsseries_combo.n2,lmsseries_combo.n3,lmsseries_combo.n4,lmsseries_combo.n5)) as lmscontents")
                ,DB::raw("(SELECT COUNT(id)  FROM lmscontents
        WHERE lmscontents.delete_status = 0 AND lmscontents.el_try = 1 AND type NOT IN(0,8) AND
            lmscontents.lmsseries_id IN (lmsseries_combo.n1,lmsseries_combo.n2,lmsseries_combo.n3,lmsseries_combo.n4,lmsseries_combo.n5)) as try_lmscontents"),
                DB::raw("(SELECT slug  FROM lmsseries WHERE lmsseries.id IN (lmsseries_combo.n1,lmsseries_combo.n2,lmsseries_combo.n3,lmsseries_combo.n4,lmsseries_combo.n5)
              AND lmsseries_combo.total_items = 1 ) as slug_lmscontents" )
            )
            ->where([
                ['lmsseries_combo.delete_status',0],
                ['type',0]
            ])
            ->whereNotNull('lmsseries_combo.n5')
            ->distinct()
            ->get();

        if (Auth::check() && (Auth::check() && Auth::user()->role_id != 6)){
            $data['series_el5'] = DB::table('lmsseries_combo')
                ->select('lmsseries_combo.*',DB::raw("(SELECT COUNT(id)  FROM lmscontents
        WHERE lmscontents.delete_status = 0 AND type NOT IN(0,8) AND lmscontents.lmsseries_id IN (lmsseries_combo.n1,lmsseries_combo.n2,lmsseries_combo.n3,lmsseries_combo.n4,lmsseries_combo.n5)) as lmscontents")
                    ,DB::raw("(SELECT COUNT(id)  FROM lmscontents
        WHERE lmscontents.delete_status = 0 AND lmscontents.el_try = 1 AND type NOT IN(0,8) AND
            lmscontents.lmsseries_id IN (lmsseries_combo.n1,lmsseries_combo.n2,lmsseries_combo.n3,lmsseries_combo.n4,lmsseries_combo.n5)) as try_lmscontents"),
                    DB::raw("(SELECT slug  FROM lmsseries WHERE lmsseries.id IN (lmsseries_combo.n1,lmsseries_combo.n2,lmsseries_combo.n3,lmsseries_combo.n4,lmsseries_combo.n5)
              AND lmsseries_combo.total_items = 1 ) as slug_lmscontents"),
                    DB::raw("(SELECT COUNT(id)  FROM payment_method WHERE payment_method.item_id
                  = lmsseries_combo.id  AND payment_method.user_id = ".Auth::id()."  AND payment_method.status = 1
                     AND DATE_ADD(responseTime, INTERVAL IF(lmsseries_combo.time = 0,90,IF(lmsseries_combo.time = 1,180,365)) DAY) > NOW()) as payment")
                )
                ->where([
                    ['lmsseries_combo.delete_status',0],
                    ['type',0]
                ])
                ->whereNotNull('lmsseries_combo.n5')
                ->distinct()
                ->get();

        }


        $data['series_el4'] = DB::table('lmsseries_combo')
            ->select('lmsseries_combo.*',DB::raw("(SELECT COUNT(id)  FROM lmscontents
        WHERE lmscontents.delete_status = 0 AND type NOT IN(0,8) AND lmscontents.lmsseries_id IN (lmsseries_combo.n1,lmsseries_combo.n2,lmsseries_combo.n3,lmsseries_combo.n4,lmsseries_combo.n5)) as lmscontents")
                ,DB::raw("(SELECT COUNT(id)  FROM lmscontents
        WHERE lmscontents.delete_status = 0 AND lmscontents.el_try = 1 AND type NOT IN(0,8) AND
            lmscontents.lmsseries_id IN (lmsseries_combo.n1,lmsseries_combo.n2,lmsseries_combo.n3,lmsseries_combo.n4,lmsseries_combo.n5)) as try_lmscontents"),
                DB::raw("(SELECT slug  FROM lmsseries WHERE lmsseries.id IN (lmsseries_combo.n1,lmsseries_combo.n2,lmsseries_combo.n3,lmsseries_combo.n4,lmsseries_combo.n5)
              AND lmsseries_combo.total_items = 1 ) as slug_lmscontents" )
            )
            ->where([
                ['lmsseries_combo.delete_status',0],
                ['type',0]
            ])
            ->whereNotNull('lmsseries_combo.n4')
            ->distinct()
            ->get();

        if (Auth::check() && (Auth::check() && Auth::user()->role_id != 6)){
            $data['series_el4'] = DB::table('lmsseries_combo')
                ->select('lmsseries_combo.*',DB::raw("(SELECT COUNT(id)  FROM lmscontents
        WHERE lmscontents.delete_status = 0 AND type NOT IN(0,8) AND lmscontents.lmsseries_id IN (lmsseries_combo.n1,lmsseries_combo.n2,lmsseries_combo.n3,lmsseries_combo.n4,lmsseries_combo.n5)) as lmscontents")
                    ,DB::raw("(SELECT COUNT(id)  FROM lmscontents
        WHERE lmscontents.delete_status = 0 AND lmscontents.el_try = 1 AND type NOT IN(0,8) AND
            lmscontents.lmsseries_id IN (lmsseries_combo.n1,lmsseries_combo.n2,lmsseries_combo.n3,lmsseries_combo.n4,lmsseries_combo.n5)) as try_lmscontents"),
                    DB::raw("(SELECT slug  FROM lmsseries WHERE lmsseries.id IN (lmsseries_combo.n1,lmsseries_combo.n2,lmsseries_combo.n3,lmsseries_combo.n4,lmsseries_combo.n5)
              AND lmsseries_combo.total_items = 1 ) as slug_lmscontents"),
                    DB::raw("(SELECT COUNT(id)  FROM payment_method WHERE payment_method.item_id
                  = lmsseries_combo.id  AND payment_method.user_id = ".Auth::id()."   AND payment_method.status = 1
                     AND DATE_ADD(responseTime, INTERVAL IF(lmsseries_combo.time = 0,90,IF(lmsseries_combo.time = 1,180,365)) DAY) > NOW()) as payment")
                )
                ->where([
                    ['lmsseries_combo.delete_status',0],
                    ['type',0]
                ])
                ->whereNotNull('lmsseries_combo.n4')
                ->distinct()
                ->get();

        }

        $data['series_el3'] = DB::table('lmsseries_combo')
            ->select('lmsseries_combo.*',DB::raw("(SELECT COUNT(id)  FROM lmscontents
        WHERE lmscontents.delete_status = 0 AND type NOT IN(0,8) AND lmscontents.lmsseries_id IN (lmsseries_combo.n1,lmsseries_combo.n2,lmsseries_combo.n3,lmsseries_combo.n4,lmsseries_combo.n5)) as lmscontents")
                ,DB::raw("(SELECT COUNT(id)  FROM lmscontents
        WHERE lmscontents.delete_status = 0 AND lmscontents.el_try = 1 AND type NOT IN(0,8) AND
            lmscontents.lmsseries_id IN (lmsseries_combo.n1,lmsseries_combo.n2,lmsseries_combo.n3,lmsseries_combo.n4,lmsseries_combo.n5)) as try_lmscontents"),
                DB::raw("(SELECT slug  FROM lmsseries WHERE lmsseries.id IN (lmsseries_combo.n1,lmsseries_combo.n2,lmsseries_combo.n3,lmsseries_combo.n4,lmsseries_combo.n5)
              AND lmsseries_combo.total_items = 1 ) as slug_lmscontents" )
            )
            ->where([
                ['lmsseries_combo.delete_status',0],
                ['type',0]
            ])
            ->whereNotNull('lmsseries_combo.n3')
            ->distinct()
            ->get();

        if (Auth::check() && (Auth::check() && Auth::user()->role_id != 6)){
            $data['series_el5'] = DB::table('lmsseries_combo')
                ->select('lmsseries_combo.*',DB::raw("(SELECT COUNT(id)  FROM lmscontents
        WHERE lmscontents.delete_status = 0 AND type NOT IN(0,8) AND lmscontents.lmsseries_id IN (lmsseries_combo.n1,lmsseries_combo.n2,lmsseries_combo.n3,lmsseries_combo.n4,lmsseries_combo.n5)) as lmscontents")
                    ,DB::raw("(SELECT COUNT(id)  FROM lmscontents
        WHERE lmscontents.delete_status = 0 AND lmscontents.el_try = 1 AND type NOT IN(0,8) AND
            lmscontents.lmsseries_id IN (lmsseries_combo.n1,lmsseries_combo.n2,lmsseries_combo.n3,lmsseries_combo.n4,lmsseries_combo.n5)) as try_lmscontents"),
                    DB::raw("(SELECT slug  FROM lmsseries WHERE lmsseries.id IN (lmsseries_combo.n1,lmsseries_combo.n2,lmsseries_combo.n3,lmsseries_combo.n4,lmsseries_combo.n5)
              AND lmsseries_combo.total_items = 1 ) as slug_lmscontents"),
                    DB::raw("(SELECT COUNT(id)  FROM payment_method WHERE payment_method.item_id
                  = lmsseries_combo.id  AND payment_method.user_id = ".Auth::id()."   AND payment_method.status = 1
                    AND DATE_ADD(responseTime, INTERVAL IF(lmsseries_combo.time = 0,90,IF(lmsseries_combo.time = 1,180,365)) DAY) > NOW()) as payment")
                )
                ->where([
                    ['lmsseries_combo.delete_status',0],
                    ['type',0]
                ])
                ->whereNotNull('lmsseries_combo.n5')
                ->distinct()
                ->get();

        }

        break;
    case 'study':
        $data['title'] = 'Khóa luyện thi';
        $firstSeries  = LmsSeries::where('is_paid', 1)
//->where('total_items','>',0)
            ->get();
        $data['lms_series'] = $firstSeries;
        $firstSeries  = LmsSeries::where('is_paid', 1)
            ->where('total_items','>',0)
            ->limit(3)
            ->get();
        $data['lms_series'] = $firstSeries;
        ///// KHóa luyện thi
        ///// KHóa luyện thi
        /*$data['series'] = DB::table('lmsseries')
            ->select('lmsseries.*',DB::raw("(SELECT COUNT(id)  FROM lmscontents
            WHERE lmscontents.delete_status = 0 AND type NOT IN(0,8) AND lmscontents.lmsseries_id = lmsseries.id) as lmscontents"),
                DB::raw("(SELECT COUNT(id)  FROM lmscontents
            WHERE lmscontents.delete_status = 0 AND lmscontents.el_try = 1 AND type NOT IN(0,8) AND lmscontents.lmsseries_id = lmsseries.id) as try_lmscontents"))
        ->where([
          ['lmsseries.delete_status',0],
          ['type_series',1],
      ])
        ->orderBy('order_by')
            ->distinct()
        ->get();*/

        //dd(Auth::user()->role_id);

        $data['series'] = DB::table('lmsseries_combo')
            ->select('lmsseries_combo.*',DB::raw("(SELECT COUNT(id)  FROM lmscontents
        WHERE lmscontents.delete_status = 0 AND type NOT IN(0,8) AND lmscontents.lmsseries_id IN (lmsseries_combo.n1,lmsseries_combo.n2,lmsseries_combo.n3,lmsseries_combo.n4,lmsseries_combo.n5)) as lmscontents")
                ,DB::raw("(SELECT COUNT(id)  FROM lmscontents
        WHERE lmscontents.delete_status = 0 AND lmscontents.el_try = 1 AND type NOT IN(0,8) AND
            lmscontents.lmsseries_id IN (lmsseries_combo.n1,lmsseries_combo.n2,lmsseries_combo.n3,lmsseries_combo.n4,lmsseries_combo.n5)) as try_lmscontents"),
                DB::raw("(SELECT slug  FROM lmsseries WHERE lmsseries.id IN (lmsseries_combo.n1,lmsseries_combo.n2,lmsseries_combo.n3,lmsseries_combo.n4,lmsseries_combo.n5)
              AND lmsseries_combo.total_items = 1 ) as slug_lmscontents")
            )
            ->where([
                ['lmsseries_combo.delete_status',0],
                ['type',1],
            ])
            ->distinct()
            ->get();

        if (Auth::check() && (Auth::check() && Auth::user()->role_id != 6)){
            $data['series'] = DB::table('lmsseries_combo')
                ->select('lmsseries_combo.*',DB::raw("(SELECT COUNT(id)  FROM lmscontents
        WHERE lmscontents.delete_status = 0 AND type NOT IN(0,8) AND lmscontents.lmsseries_id IN (lmsseries_combo.n1,lmsseries_combo.n2,lmsseries_combo.n3,lmsseries_combo.n4,lmsseries_combo.n5)) as lmscontents")
                    ,DB::raw("(SELECT COUNT(id)  FROM lmscontents
        WHERE lmscontents.delete_status = 0 AND lmscontents.el_try = 1 AND type NOT IN(0,8) AND
            lmscontents.lmsseries_id IN (lmsseries_combo.n1,lmsseries_combo.n2,lmsseries_combo.n3,lmsseries_combo.n4,lmsseries_combo.n5)) as try_lmscontents"),
                    DB::raw("(SELECT slug  FROM lmsseries WHERE lmsseries.id IN (lmsseries_combo.n1,lmsseries_combo.n2,lmsseries_combo.n3,lmsseries_combo.n4,lmsseries_combo.n5)
              AND lmsseries_combo.total_items = 1 ) as slug_lmscontents"),
                    DB::raw("(SELECT COUNT(id)  FROM payment_method WHERE payment_method.item_id
                  = lmsseries_combo.id AND payment_method.user_id = ".Auth::id()."  AND payment_method.status = 1
                  AND DATE_ADD(responseTime, INTERVAL IF(lmsseries_combo.time = 0,90,IF(lmsseries_combo.time = 1,180,365)) DAY) > NOW()) as payment")
                )
                ->where([
                    ['lmsseries_combo.delete_status',0],
                    ['type',1]
                ])
                ->distinct()
                ->get();

        }

        // N5
        /*$data['series_5'] = DB::table('lmsseries')
            ->select('lmsseries.*',DB::raw("(SELECT COUNT(id)  FROM lmscontents
             WHERE lmscontents.delete_status = 0 AND type NOT IN(0,8) AND lmscontents.lmsseries_id = lmsseries.id) as lmscontents"),
                DB::raw("(SELECT COUNT(id)  FROM lmscontents
          WHERE lmscontents.delete_status = 0 AND lmscontents.el_try = 1 AND type NOT IN(0,8) AND lmscontents.lmsseries_id = lmsseries.id) as try_lmscontents"))
            ->where([
                ['lmsseries.delete_status',0],
                ['lmsseries.lms_category_id',5],
                ['type_series',1]
            ])
            ->orderBy('order_by')
            ->distinct()
            ->get();*/
        $data['series_5'] = DB::table('lmsseries_combo')
            ->select('lmsseries_combo.*',DB::raw("(SELECT COUNT(id)  FROM lmscontents
        WHERE lmscontents.delete_status = 0 AND type NOT IN(0,8) AND lmscontents.lmsseries_id IN (lmsseries_combo.n1,lmsseries_combo.n2,lmsseries_combo.n3,lmsseries_combo.n4,lmsseries_combo.n5)) as lmscontents")
                ,DB::raw("(SELECT COUNT(id)  FROM lmscontents
        WHERE lmscontents.delete_status = 0 AND lmscontents.el_try = 1 AND type NOT IN(0,8) AND
            lmscontents.lmsseries_id IN (lmsseries_combo.n1,lmsseries_combo.n2,lmsseries_combo.n3,lmsseries_combo.n4,lmsseries_combo.n5)) as try_lmscontents"),
                DB::raw("(SELECT slug  FROM lmsseries WHERE lmsseries.id IN (lmsseries_combo.n1,lmsseries_combo.n2,lmsseries_combo.n3,lmsseries_combo.n4,lmsseries_combo.n5)
              AND lmsseries_combo.total_items = 1 ) as slug_lmscontents" )
            )
            ->where([
                ['lmsseries_combo.delete_status',0],
                ['type',1]
            ])
            ->whereNotNull('lmsseries_combo.n5')
            ->distinct()
            ->get();


        if (Auth::check() && (Auth::check() && Auth::user()->role_id != 6)){
            $data['series_5'] = DB::table('lmsseries_combo')
                ->select('lmsseries_combo.*',DB::raw("(SELECT COUNT(id)  FROM lmscontents
        WHERE lmscontents.delete_status = 0 AND type NOT IN(0,8) AND lmscontents.lmsseries_id IN (lmsseries_combo.n1,lmsseries_combo.n2,lmsseries_combo.n3,lmsseries_combo.n4,lmsseries_combo.n5)) as lmscontents")
                    ,DB::raw("(SELECT COUNT(id)  FROM lmscontents
        WHERE lmscontents.delete_status = 0 AND lmscontents.el_try = 1 AND type NOT IN(0,8) AND
            lmscontents.lmsseries_id IN (lmsseries_combo.n1,lmsseries_combo.n2,lmsseries_combo.n3,lmsseries_combo.n4,lmsseries_combo.n5)) as try_lmscontents"),
                    DB::raw("(SELECT slug  FROM lmsseries WHERE lmsseries.id IN (lmsseries_combo.n1,lmsseries_combo.n2,lmsseries_combo.n3,lmsseries_combo.n4,lmsseries_combo.n5)
              AND lmsseries_combo.total_items = 1 ) as slug_lmscontents"),
                    DB::raw("(SELECT COUNT(id)  FROM payment_method WHERE payment_method.item_id
                  = lmsseries_combo.id AND payment_method.user_id = ".Auth::id()."  AND payment_method.status = 1
                    AND DATE_ADD(responseTime, INTERVAL IF(lmsseries_combo.time = 0,90,IF(lmsseries_combo.time = 1,180,365)) DAY) > NOW()) as payment")
                )
                ->where([
                    ['lmsseries_combo.delete_status',0],
                    ['type',1]
                ])
                ->whereNotNull('lmsseries_combo.n5')
                ->distinct()
                ->get();

        }

        // N4
        /*$data['series_4'] = DB::table('lmsseries')
            ->select('lmsseries.*',DB::raw("(SELECT COUNT(id)  FROM lmscontents
              WHERE lmscontents.delete_status = 0 AND type NOT IN(0,8) AND lmscontents.lmsseries_id = lmsseries.id) as lmscontents"),
                DB::raw("(SELECT COUNT(id)  FROM lmscontents
          WHERE lmscontents.delete_status = 0 AND lmscontents.el_try = 1 AND type NOT IN(0,8) AND lmscontents.lmsseries_id = lmsseries.id) as try_lmscontents"))
            ->where([
                ['lmsseries.delete_status',0],
                ['lmsseries.lms_category_id',4],
                ['type_series',1]
            ])
            ->orderBy('order_by')
            ->distinct()
            ->get();*/
        $data['series_4'] = DB::table('lmsseries_combo')
            ->select('lmsseries_combo.*',DB::raw("(SELECT COUNT(id)  FROM lmscontents
        WHERE lmscontents.delete_status = 0 AND type NOT IN(0,8) AND lmscontents.lmsseries_id IN (lmsseries_combo.n1,lmsseries_combo.n2,lmsseries_combo.n3,lmsseries_combo.n4,lmsseries_combo.n5)) as lmscontents")
                ,DB::raw("(SELECT COUNT(id)  FROM lmscontents
        WHERE lmscontents.delete_status = 0 AND lmscontents.el_try = 1 AND type NOT IN(0,8) AND
            lmscontents.lmsseries_id IN (lmsseries_combo.n1,lmsseries_combo.n2,lmsseries_combo.n3,lmsseries_combo.n4,lmsseries_combo.n5)) as try_lmscontents"),
                DB::raw("(SELECT slug  FROM lmsseries WHERE lmsseries.id IN (lmsseries_combo.n1,lmsseries_combo.n2,lmsseries_combo.n3,lmsseries_combo.n4,lmsseries_combo.n5)
              AND lmsseries_combo.total_items = 1 ) as slug_lmscontents" )
            )
            ->where([
                ['lmsseries_combo.delete_status',0],
                ['type',1]
            ])
            ->whereNotNull('lmsseries_combo.n4')
            ->distinct()
            ->get();

        if (Auth::check() && (Auth::check() && Auth::user()->role_id != 6)){
            $data['series_4'] = DB::table('lmsseries_combo')
                ->select('lmsseries_combo.*',DB::raw("(SELECT COUNT(id)  FROM lmscontents
        WHERE lmscontents.delete_status = 0 AND type NOT IN(0,8) AND lmscontents.lmsseries_id IN (lmsseries_combo.n1,lmsseries_combo.n2,lmsseries_combo.n3,lmsseries_combo.n4,lmsseries_combo.n5)) as lmscontents")
                    ,DB::raw("(SELECT COUNT(id)  FROM lmscontents
        WHERE lmscontents.delete_status = 0 AND lmscontents.el_try = 1 AND type NOT IN(0,8) AND
            lmscontents.lmsseries_id IN (lmsseries_combo.n1,lmsseries_combo.n2,lmsseries_combo.n3,lmsseries_combo.n4,lmsseries_combo.n5)) as try_lmscontents"),
                    DB::raw("(SELECT slug  FROM lmsseries WHERE lmsseries.id IN (lmsseries_combo.n1,lmsseries_combo.n2,lmsseries_combo.n3,lmsseries_combo.n4,lmsseries_combo.n5)
              AND lmsseries_combo.total_items = 1 ) as slug_lmscontents"),
                    DB::raw("(SELECT COUNT(id)  FROM payment_method WHERE payment_method.item_id
                  = lmsseries_combo.id  AND payment_method.user_id = ".Auth::id()."
                     AND payment_method.status = 1
                     AND DATE_ADD(responseTime, INTERVAL IF(lmsseries_combo.time = 0,90,IF(lmsseries_combo.time = 1,180,365)) DAY) > NOW()) as payment")
                )
                ->where([
                    ['lmsseries_combo.delete_status',0],
                    ['type',1]
                ])
                ->whereNotNull('lmsseries_combo.n4')
                ->distinct()
                ->get();

        }
        // N3
        /*$data['series_3'] = DB::table('lmsseries')
            ->select('lmsseries.*',DB::raw("(SELECT COUNT(id)  FROM lmscontents
             WHERE lmscontents.delete_status = 0 AND type NOT IN(0,8) AND lmscontents.lmsseries_id = lmsseries.id) as lmscontents"),
                DB::raw("(SELECT COUNT(id)  FROM lmscontents
          WHERE lmscontents.delete_status = 0 AND lmscontents.el_try = 1 AND type NOT IN(0,8) AND lmscontents.lmsseries_id = lmsseries.id) as try_lmscontents"))
            ->where([
                ['lmsseries.delete_status',0],
                ['lmsseries.lms_category_id',3],
                ['type_series',1]
            ])
            ->orderBy('order_by')
            ->distinct()
            ->get();*/

        $data['series_3'] = DB::table('lmsseries_combo')
            ->select('lmsseries_combo.*',DB::raw("(SELECT COUNT(id)  FROM lmscontents
        WHERE lmscontents.delete_status = 0 AND type NOT IN(0,8) AND lmscontents.lmsseries_id IN (lmsseries_combo.n1,lmsseries_combo.n2,lmsseries_combo.n3,lmsseries_combo.n4,lmsseries_combo.n5)) as lmscontents")
                ,DB::raw("(SELECT COUNT(id)  FROM lmscontents
        WHERE lmscontents.delete_status = 0 AND lmscontents.el_try = 1 AND type NOT IN(0,8) AND
            lmscontents.lmsseries_id IN (lmsseries_combo.n1,lmsseries_combo.n2,lmsseries_combo.n3,lmsseries_combo.n4,lmsseries_combo.n5)) as try_lmscontents"),
                DB::raw("(SELECT slug  FROM lmsseries WHERE lmsseries.id IN (lmsseries_combo.n1,lmsseries_combo.n2,lmsseries_combo.n3,lmsseries_combo.n4,lmsseries_combo.n5)
              AND lmsseries_combo.total_items = 1 ) as slug_lmscontents" )
            )
            ->where([
                ['lmsseries_combo.delete_status',0],
                ['type',1]
            ])
            ->whereNotNull('lmsseries_combo.n3')
            ->distinct()
            ->get();
        if (Auth::check() && (Auth::check() && Auth::user()->role_id != 6)){
            $data['series_3'] = DB::table('lmsseries_combo')
                ->select('lmsseries_combo.*',DB::raw("(SELECT COUNT(id)  FROM lmscontents
        WHERE lmscontents.delete_status = 0 AND type NOT IN(0,8) AND lmscontents.lmsseries_id IN (lmsseries_combo.n1,lmsseries_combo.n2,lmsseries_combo.n3,lmsseries_combo.n4,lmsseries_combo.n5)) as lmscontents")
                    ,DB::raw("(SELECT COUNT(id)  FROM lmscontents
        WHERE lmscontents.delete_status = 0 AND lmscontents.el_try = 1 AND type NOT IN(0,8) AND
            lmscontents.lmsseries_id IN (lmsseries_combo.n1,lmsseries_combo.n2,lmsseries_combo.n3,lmsseries_combo.n4,lmsseries_combo.n5)) as try_lmscontents"),
                    DB::raw("(SELECT slug  FROM lmsseries WHERE lmsseries.id IN (lmsseries_combo.n1,lmsseries_combo.n2,lmsseries_combo.n3,lmsseries_combo.n4,lmsseries_combo.n5)
              AND lmsseries_combo.total_items = 1 ) as slug_lmscontents"),
                    DB::raw("(SELECT COUNT(id)  FROM payment_method WHERE payment_method.item_id
                  = lmsseries_combo.id  AND payment_method.user_id = ".Auth::id()."  AND payment_method.status = 1
                     AND DATE_ADD(responseTime, INTERVAL IF(lmsseries_combo.time = 0,90,IF(lmsseries_combo.time = 1,180,365)) DAY) > NOW()) as payment")
                )
                ->where([
                    ['lmsseries_combo.delete_status',0],
                    ['type',1]
                ])
                ->whereNotNull('lmsseries_combo.n3')
                ->distinct()
                ->get();

        }

        break;
    case 'shop':
    $data['title'] = 'Cửa hàng';
    break;
    case 'contact':
    $data['title'] = 'Liên hệ';
    break;
    default:
# code...
    break;
}
// if($key == 'about-us'){
// $data['title']        = getPhrase('about_us');
// }
// elseif($key == 'privacy-policy'){
// $data['title']        = 'Bảo mật thông tin';
// }
// elseif($key == 'page-exam'){
// $data['title']        = 'Page exam';
// }
// elseif($key == 'terms-conditions'){
//   $data['title']        = 'Chính sách hoàn phí';
// }
// elseif($key == 'payment-method'){
//   $data['title']        = 'Hình thức thanh toán';
// }
// elseif($key == 'courses'){
//   $data['title']        = 'Khóa luyện thi';
//   $firstSeries  = LmsSeries::where('is_paid', 1)
//                              ->where('total_items','>',0)
//                              ->get();
//   $data['lms_series'] = $firstSeries;
// }
// elseif($key == 'shop'){
//   $data['title']        = 'Cửa hàng';
// }
// if($key == 'contact'){
//   $data['title']        = 'Liên hệ';
// }
$data['key']          = $key;
$data['active_class'] = $key;
$view_name = 'admin.site.dynamic-view';
return view($view_name, $data);
}
/**
* This method save the subscription email
* @param  Request $request [description]
* @return [type]           [description]
*/
public function saveSubscription(Request $request)
{
  $email  = $request->useremail;
  $record   = UserSubscription::where('email',$email)->first();
  if(!$record){
    $new_record   = new UserSubscription();
    $new_record->email  = $email;
    $new_record->save();
    echo json_encode(array('status'=>'ok'));
}
else{
    echo json_encode(array('status'=>'existed'));
}
}
public function confirmRegister($slug='')
{
// echo $slug; exit;
  $data['key'] = 'no';
  $data['active_class'] = 'confirmRegister';
  $data['title']  = 'Xác thực tài khoản';
  $data['status'] = '';
  $user_confirm         = User::where('confirmation_code',$slug)
  ->first();
  if ($user_confirm) {
    $data['key'] = 'confirm';
    if ($user_confirm->login_enabled == 0 ){
      $user_confirm->login_enabled = 1;
      $user_confirm->save();
      $data['status'] = '<div class="alert alert-success">
      <strong> Tài khoản của bạn đã kích hoạt </strong>
      </div>';
  } else {
      $data['status'] = '<div class="alert alert-warning">
      <strong> Tài khoản đã được kích hoạt trước đó </strong>
      </div>';
  }
} else {
    $data['status'] = '<div class="alert alert-warning">
    <strong> Mã xác thực tài khoản không tồn tại </strong>
    </div>';
}
$view_name = 'admin.site.confirm';
return view($view_name, $data);
}
/**
* This method display the all fornt end exam categories
* and exams
* @param  string $value [description]
* @return [type]        [description]
*/
public function frontAllExamCats($slug='')
{
  $data['key'] = 'home';
  $data['active_class'] = 'practice_exams';
  $categories           = App\QuizCategory::getShowFrontCategories();
  $data['categories']   = $categories;
  $quizzes  = array();
  if($categories && !$slug)
  {
    $firstOne        = $categories[0];
    $quizzes         = Quiz::where('category_id',$firstOne->id)
    ->where('show_in_front',1)
    ->where('total_marks','>',0)
    ->paginate(9);
    $data['title']  = ucfirst($firstOne->category);
}
if($categories && $slug){
    $category  = App\QuizCategory::where('slug',$slug)->first();
    $quizzes   = Quiz::where('category_id',$category->id)
    ->where('show_in_front',1)
    ->where('total_marks','>',0)
    ->paginate(9);
    $data['title']  = ucfirst($category->category);
}
$data['quizzes']   = $quizzes;
$data['quiz_slug'] = $slug;
$view_name = 'admin.site.allexam_categories';
return view($view_name, $data);
}
/**
* View all front end lms categories and series
* @param  string $slug [description]
* @return [type]       [description]
*/
public function forntAllLMSCats($slug='')
{
  $data['key'] = 'home';
  $data['active_class'] = 'lms';
  $lms_cates            = array();
  $lms_cates            = LmsSeries::getFreeSeries();
  $data['lms_cates']    = $lms_cates;
  $all_series           = array();
  if(count($lms_cates) && !$slug)
  {
    $firstOne        = $lms_cates[0];
    $all_series      = LmsSeries::where('lms_category_id',$firstOne->id)
    ->where('show_in_front',1)
    ->where('total_items','>',0)
    ->paginate(9);
    $data['title']  = ucfirst($firstOne->category);
}
if($lms_cates && $slug)
{
    $category     = App\LmsCategory::where('slug',$slug)->first();
    $all_series   = LmsSeries::where('lms_category_id',$category->id)
    ->where('show_in_front',1)
    ->where('total_items','>',0)
    ->paginate(9);
    $data['title']  = ucfirst($category->category);
}
$data['all_series']   = $all_series;
$data['lms_cat_slug'] = $slug;
$view_name = 'admin.site.alllms_categories';
return view($view_name, $data);
}
/**
* View all contents in specific lms series
* @param  [type] $slug [description]
* @return [type]       [description]
*/
public function forntLMSContents($slug)
{
  $data['key'] = 'home';
  $data['active_class'] = 'lms';
  $lms_series   = LmsSeries::where('slug',$slug)->first();
  $lms_category = App\LmsCategory::where('id',$lms_series->lms_category_id)->first();
  $contents     = $lms_series->viewContents(9);
  $data['contents']     = $contents;
  $data['lms_series']   = $lms_series;
  $data['title']        = ucfirst($lms_series->title);
  $lms_cates            = LmsSeries::getFreeSeries();
  $data['lms_cates']    = $lms_cates;
  $data['lms_cat_slug'] = $lms_category->slug;
  $view_name = 'admin.site.lms-contents';
  return view($view_name, $data);
}
/**
* Downlaod lms file type contents
* @return [type] [description]
*/
public function downloadLMSContent($content_slug){
  $content_record = App\LmsContent::getRecordWithSlug($content_slug);
// dd($content_record);
  try {
    $pathToFile= "public/uploads/lms/content"."/".$content_record->file_path;
    return Response::download($pathToFile);
} catch (Exception $e) {
    flash('Ooops','file_is_not_found','error');
    return back();
}
}
/**
* View video type lms contents
* @param  [type] $content_slug [description]
* @return [type]               [description]
*/
public function viewVideo($content_slug,$series_id='')
{
// dd($series_id);
  $content_record = App\LmsContent::getRecordWithSlug($content_slug);
  $data['key'] = 'home';
  $data['active_class']    = 'lms';
  $data['title']           = ucfirst($content_record->title);
  $data['content_record']  = $content_record;
  $data['video_src']       =  $video_src = $content_record->file_path;
  if($series_id!=''){
    $first_series   = LmsSeries::where('id',$series_id)->first();
    $all_series   = LmsSeries::where('lms_category_id',$first_series->lms_category_id)
    ->where('id','!=',$first_series->id)
    ->where('show_in_front',1)
    ->where('total_items','>',0)
    ->get();
// dd($all_series);
}
$data['first_series']  = $first_series;
$data['all_series']    = $all_series;
$view_name = 'admin.site.lms-content-video';
return view($view_name, $data);
}
/**
* Send a email to super admin with user contact us details
* @param Request $request [description]
*/
public function ContactUs(Request $request)
{
// dd($request);
  $data  = array();
  $data['name']     = $request->name;
  $data['email']    = $request->email;
  $data['number']   = $request->phone;
  $data['subject']  = $request->subject;
  $data['message']  = $request->message;
  try {
    $super_admin  = App\User::where('role_id',1)->first();
    $super_admin->notify(new \App\Notifications\UserContactUs($super_admin, $data));
    sendEmail('usercontactus', array('name'=> $request->name,
      'to_email' => $request->email ));
} catch (Exception $e) {
// dd($e->getMessage());
}
flash('congratulations','our_team_will_contact_you_soon','success');
return redirect(URL_SITE_CONTACTUS);
}
public function getSeriesContents(Request $request)
{
  $lms_series   = LmsSeries::find($request->lms_series_id);
  $contents     = $lms_series->viewContents();
  return json_encode(array('contents'=>$contents));
}


	public function vnPayIPN(Request $request)
	{

		$orderId        = $request->vnp_TxnRef;
		$log = new Logger(env('VNPAY_LOG_PATH'));
		$log->putLog('Response call back from api VNPAY, orderId: ' .$orderId);
		$inputData = array();
		foreach ($_GET as $key => $value) {
			if (substr($key, 0, 4) == "vnp_") {
				$inputData[$key] = $value;
			}
		}
		$vnp_SecureHash = $inputData['vnp_SecureHash'];
		unset($inputData['vnp_SecureHash']);
		ksort($inputData);
		$i = 0;
		$hashData = "";
		foreach ($inputData as $key => $value) {
			if ($i == 1) {
				$hashData = $hashData . '&' . urlencode($key) . "=" . urlencode($value);
			} else {
				$hashData = $hashData . urlencode($key) . "=" . urlencode($value);
				$i = 1;
			}
		}
		$vnp_HashSecret = env('VNP_HASHSECRET');
		$secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);
		try
		{
			if ($secureHash == $vnp_SecureHash) {
				// Get payment method
				$paymentMethod = PaymentMethod::where('orderId', '=', $orderId)->first();
				if ($paymentMethod == null) {
					$returnData['RspCode'] = '01';
					$returnData['Message'] = 'Order not found';
					$log->putLog(json_encode($returnData));
					return json_encode($returnData);
				}
				else {
					$amount = (int) $_GET['vnp_Amount'] / 100;
					if($amount == $paymentMethod->amount)
					{
						if($paymentMethod->status == 0) {
							if ($inputData['vnp_ResponseCode'] == '00' || $inputData['vnp_TransactionStatus'] == '00') {
								$paymentMethod->status = 1; //Update Giao dich thanh công 0=>1
								$paymentMethod->save();
							}
							$returnData['RspCode'] = '00';
							$returnData['Message'] = 'Confirm Success';
							$log->putLog(json_encode($returnData));
						} else {
							$returnData['RspCode'] = '02';
							$returnData['Message'] = 'Order already confirmed';
							$log->putLog(json_encode($returnData));
						}
					} else {
						$returnData['RspCode'] = '04';
						$returnData['Message'] = 'invalid amount';
						$log->putLog(json_encode($returnData));
					}
				}
			} else {
				$returnData['RspCode'] = '97';
				$returnData['Message'] = 'Invalid signature';
				$log->putLog(json_encode($returnData));
			}
		} catch (Exception $e) {
			$returnData['RspCode'] = '99';
			$returnData['Message'] = 'Unknow error';
			$log->putLog(json_encode($returnData));
		}
		return json_encode($returnData);
	}

    /**
     * Redirect to home page of each user role
     *
     * @return \Illuminate\Http\Response
     */
    function redirectHomePage() {
        if (session()->has('logout_successful')) {
            session()->flash('logout_successful', true);
            return redirect()->to('/home');
        }

        if (session()->has('logout_successful')) {
            session()->flash('logout_successful', true);
            return redirect()->to('/home');
        }
        if (!Auth::check()) {
            return redirect()->to('/home');
        }

        if (checkRole(getUserGrade(9))) {
            return redirect(PREFIX . 'exams/questionbank');
        }
        if (checkRole(getUserGrade(7))) {
            return redirect(PREFIX . 'exams/questionbank');
        }
        if (checkRole(getUserGrade(6))) {
            return redirect(PREFIX . 'home');
        }
        if (checkRole(getUserGrade(5))) {
            return redirect(PREFIX . 'home');
        }
        if (checkRole(getUserGrade(4))) {
            return redirect(PREFIX . 'parent/class');
        }
        if (checkRole(getUserGrade(1))) {
            return redirect(PREFIX);
        }
        if (checkRole(getUserGrade(11))) {
            return redirect(PREFIX . 'payments-report/online');
        }

        return redirect(PREFIX . 'home');
    }

    /**
     * Redirect to home page
     *
     * @return \Illuminate\Http\Response
     */
    public function homePage() {
        $data = [];
        $data['learning_series_list'] = $this->lmsSeriesComboService->getAllSeriesByType(LmsSeriesCombo::LEARNING_TYPE);
        $data['exam_series_list'] = $this->lmsSeriesComboService->getAllSeriesByType(LmsSeriesCombo::EXAM_TYPE);

        return view('client.pages.home', $data);
    }
}