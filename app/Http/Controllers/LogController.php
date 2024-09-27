<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App;
use App\Http\Requests;
use App\Lmscategory;
use App\LmsContent;
use DB;
use Auth;
use Image;
use ImageSettings;
use File;
use Exception;
use Excel;
use App\Http\Controllers\CURLFile;

class LogController extends Controller{

  public function __construct(){
    // $this->middleware('auth');
  }


  /**
  * Course listing method
  * @return Illuminate\Database\Eloquent\Collection
  */
  public function checkSendMail(){
    $user = DB::table('users')
      ->select(['id','email','email_exits'])
      ->where([
        ['is_register',1],
      ])
      ->limit(1)
      ->get();
    $count = 0;
    foreach($user as $r){
      dump($r->email);
      if(checkEmail($r->email) != 1){
        $count++;
      }
    }
    dd($count);
    // $check = checkEmail('zxckkh123123@gmail.com');
    // sendEmail('registration', array('name'=>'user_name', 'username'=>'username', 'to_email' => 'viecvuimailtest123@gmail.com', 'password'=>'111'));
    
    dd('end');
  }

  public function test(){
    echo phpinfo();
  }

}
