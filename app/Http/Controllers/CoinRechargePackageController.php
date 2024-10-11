<?php

namespace App\Http\Controllers;

use App\Services\CoinRechargePackageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CoinRechargePackageController extends Controller
{
    private $coinRechargeService;

    /**
     * Create a new controller instance.
     */
    public function __construct(CoinRechargePackageService $coinRechargeService){
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (!checkRole(getUserGrade(2))) {
                prepareBlockUserMessage();
                return redirect('/');
            }
            return $next($request);
        });
        $this->coinRechargeService = $coinRechargeService;
    }

    /**
     * List coin recharge packages
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index() {
        $active_class = 'coin-packages';
        $page_title = 'Danh sách các gói nạp HiCoin';

        $coin_packages = $this->coinRechargeService->getAllSorted();

        return view('admin.payments-order.coin-recharge-packages.index', compact('coin_packages', 'active_class', 'page_title'));
    }

    /**
     * Update or create a coin recharge package
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function updateOrCreate(Request $request) {
        $validationRules = [
            'price' => ['bail', 'required', 'numeric', 'min:0'],
            'coin' => ['bail', 'required', 'numeric', 'min:0'],
            'bonus_percentage' => ['bail', 'required', 'numeric', 'between:0,100'],
            'is_active' => ['bail', 'required', Rule::in(array_keys(config('constant.coin_recharge_package.status')))],
        ];

        if ($request->isMethod('put')) {
            $validationRules['id'] = ['required', 'integer', 'exists:coin_recharge_packages,id'];
            $validationRules['price'][] = Rule::unique('coin_recharge_packages', 'price')->ignore($request->id);
        }
        if ($request->isMethod('post')) {
            $validationRules['price'][] = Rule::unique('coin_recharge_packages', 'price');
        }

        $validator = Validator::make(
            $request->all(),
            $validationRules,
            [],
            [
                'price' => "Số tiền",
                'coin' => "Số lượng HiCoin",
                'bonus_percentage' => "Phần trăm bonus",
                'is_active' => "Trạng thái",
                'id' => "Id gói nạp HiCoin",
            ]
        );
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
                'inputs' => $request->all(),
            ], 422);
        }

        $data = [
            'price' => $request->price,
            'coin' => $request->coin,
            'bonus_percentage' => $request->bonus_percentage,
            'is_active' => $request->is_active,
            'updated_at' => now(),
        ];
        if ($request->isMethod('post')) {
            $data['created_at'] = now();
        }

        $this->coinRechargeService->updateOrInsert(['id' => $request->id], $data);
    }

    /**
     * Delete a coin recharge package
     * @param  string $coin_package_id
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function delete(string $coin_package_id)
    {
        $coin_package_id = (int) $coin_package_id;
        $coin_package = $this->coinRechargeService->findById($coin_package_id);
        if ($coin_package) {
            $coin_package->delete();
            flash2('Thông báo', 'Gói nạp đã được xóa!', 'success');
        }
        else {
            flash2('Thông báo', 'Gói nạp đã bị xoá trước đó!', 'error');
        }

        return redirect()->route('payments-order.coin-recharge-packages.index');
    }
}
