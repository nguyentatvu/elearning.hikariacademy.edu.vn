<?php

namespace App\Http\Controllers;

use App\PointRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PointManagementController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct() {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (!checkRole(getUserGrade(2))) {
                prepareBlockUserMessage();
                return redirect('/');
            }
            return $next($request);
        });
    }

    /**
     * List of all point rules.
     *
     * @return \Illuminate\Http\Response
     */
    public function list() {
        $data['active_class'] = 'point-management';
        $data['page_title'] = 'Danh sách các gói điểm';
        $data['rules'] = PointRule::first()->rules;

        return view('admin.point-management.list', $data);
    }

    /**
     * Save point rules.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function savePointRules(Request $request) {
        PointRule::first()->update([
            'rules' => json_decode($request->rules ?? "{}")
        ]);
    }
}
