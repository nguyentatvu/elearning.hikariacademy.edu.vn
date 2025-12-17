<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\LmsSeriesCombo;
use App\PaymentMethod;
use App\Services\LmsSeriesService;
use App\Services\UserRoadmapService;
use App\Services\UserService;
use App\User;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    private const PRODUCTION_URL = "https://buy.itunes.apple.com/verifyReceipt";
    private const SANDBOX_URL = "https://sandbox.itunes.apple.com/verifyReceipt";
    private const VALID_RECEIPT_STATUS = "0";
    private const APPLE_PAY_TYPE = 'apple_purchase';

    private $userService;
    private $userRoadmapService;
    private $lmsSeriesService;

    public function __construct(
        UserService $userService,
        UserRoadmapService $userRoadmapService,
        LmsSeriesService $lmsSeriesService
    )
    {
        $this->userService = $userService;
        $this->userRoadmapService = $userRoadmapService;
        $this->lmsSeriesService = $lmsSeriesService;
    }

    /**
     * @SWG\Post(
     *     tags={"Payment"},
     *     path="/payment/verify-apple-receipt",
     *     summary="Verify Apple receipt and save course payment",
     *     @SWG\Parameter(
     *         name="body",
     *         in="body",
     *         required=true,
     *         description="Receipt data from Apple purchase",
     *         @SWG\Schema(
     *             @SWG\Property(property="receipt_data", type="string", example="MIIUKQYJKoZIhvcNAQcCoIIUGjCCFBYCAQExCzAJBgUrDgMCGgUA..."),
     *         )
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Payment verified successfully",
     *         @SWG\Schema(
     *             type="object",
     *             @SWG\Property(property="success", type="boolean", example=true),
     *             @SWG\Property(property="message", type="string", example="Payment verified and saved successfully!"),
     *         )
     *     ),
     *     @SWG\Response(
     *         response=400,
     *         description="Bad Request - Invalid receipt data",
     *         @SWG\Schema(
     *             type="object",
     *             @SWG\Property(property="success", type="boolean", example=false),
     *             @SWG\Property(property="error", type="string", example="Invalid receipt data")
     *         )
     *     ),
     *     @SWG\Response(
     *         response=400,
     *         description="Bad Request - Course series not found",
     *         @SWG\Schema(
     *             type="object",
     *             @SWG\Property(property="success", type="boolean", example=false),
     *             @SWG\Property(property="error", type="string", example="Course series not found")
     *         )
     *     ),
     *     @SWG\Response(
     *         response=409,
     *         description="Bad Request - Payments already exists",
     *         @SWG\Schema(
     *             type="object",
     *             @SWG\Property(property="success", type="boolean", example=false),
     *             @SWG\Property(property="error", type="string", example="Payment already exists")
     *         )
     *     ),
     *     @SWG\Response(
     *         response=500,
     *         description="Internal Server Error",
     *         @SWG\Schema(
     *             type="object",
     *             @SWG\Property(property="success", type="boolean", example=false),
     *             @SWG\Property(property="error", type="string", example="Error occurred while verifying receipt")
     *         )
     *     ),
     *     security={{"bearer_token":{}}}
     * )
     */
    public function verifyAppleReceipt(Request $request)
    {
        try {
            $params = $request->all();
            Log::info('Verifying Apple receipt', ['params' => $params, 'time' => date("Y-m-d H:i:s")]);

            $appleProductId = $params['product_id'] ?? '';
            $courseId = explode('_', $appleProductId)[1];

            $callInfo = $this->getAppleCallInfo($params);
            $verifyingResponse = $this->callAppleApi($callInfo['callUrl'], $callInfo['requestBody']);
            $appleTransactionId = $this->getAppleTransactionIdFromApplePurchaseVerification($verifyingResponse, $appleProductId);

            if ($appleTransactionId !== null) {
                $this->saveCoursePaymentFromApplePurchase([
                    'courseId' => $courseId,
                    'appleTransactionId' => $appleTransactionId
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Payment verified and saved successfully!',
            ], 200);

        } catch (Exception $e) {
            Log::error("Error occcured while verifying apple receipt: " . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], $e->getCode());
        }
    }

    private function getAppleTransactionIdFromApplePurchaseVerification($responseBody, $appleProductId) {
        $hikariBundleId = 'com.hikari.elearning.app';

        if (
            optional($responseBody)['receipt'] &&
            optional($responseBody['receipt'])['bundle_id'] === $hikariBundleId &&
            count(data_get($responseBody, 'receipt.in_app', [])) > 0
        ) {
            $inAppList = $responseBody['receipt']['in_app'];
            $appleTransaction = collect($inAppList)
                ->where('product_id', $appleProductId)
                ->where('in_app_ownership_type', 'PURCHASED')
                ->first();
            $appleTransactionId = $appleTransaction['transaction_id'] ?? null;

            if ($appleTransactionId !== null && PaymentMethod::where('apple_transaction_id', $appleTransactionId)->exists()) {
                return null;
            }

            return $appleTransaction['transaction_id'] ?? null;
        } else {
            throw new Exception("Invalid receipt data!", 400);
        }
    }

    /**
     * Get Apple API call info
     * @param array $params
     * @return array
     */
    private function getAppleCallInfo(array $params) {
        $sharedSecret = env('APPLE_SHARED_SECRET');
        $receiptData = $params['receipt_data'];

        $requestBody = json_encode([
            "receipt-data" => $receiptData,
            "password" => $sharedSecret
        ]);

        $callUrl = App::environment("production") ? self::PRODUCTION_URL : self::SANDBOX_URL;

        return [
            'callUrl' => $callUrl,
            'requestBody' => $requestBody
        ];
    }

    /**
     * Call Apple API to verify receipt
     * @param string $url
     * @param string $requestBody
     * @return string
     */
    private function callAppleApi($url, $requestBody) {
        $httpClient = new Client(['timeout' => 10]);

        try {
            $response = $httpClient->post($url, [
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
                'json' => json_decode($requestBody, true)
            ]);

            $responseBody = json_decode($response->getBody()->getContents(), true);

            if (!optional($responseBody)->status !== null || !$responseBody->status == self::VALID_RECEIPT_STATUS) {
                new Exception("Invalid receipt!", 400);
            }

            Log::info('Apple API response', ['responseBody' => $responseBody]);

            return $responseBody;

        } catch (RequestException $e) {
            throw new Exception("Apple API request failed: " . $e->getMessage(), 500);
        }
    }

    private function saveCoursePaymentFromApplePurchase($courseIdAndAppleTransactionId) {
        $userId = auth()->guard('api')->user()->id;

        ['courseId' => $courseId, 'appleTransactionId' => $appleTransactionId] = $courseIdAndAppleTransactionId;

        $lmsseriesCombo = LmsSeriesCombo::find($courseId);
        $user = User::find($userId);
        if (!$lmsseriesCombo || !$user) {
            throw new Exception("Course series not found!", 400);
        }

        $requestId = "{$userId}_{$lmsseriesCombo->id}_{$lmsseriesCombo->type}";
        $amount = $lmsseriesCombo->actualCost;

        DB::beginTransaction();
        try {
            $paymentMethod = PaymentMethod::create([
                'user_id' => $userId,
                'item_id' => $lmsseriesCombo->id,
                'item_name' => $lmsseriesCombo->title,
                'amount' => $amount,
                'requestId' => $requestId,
                'orderId' => "HIK" . time(),
                'orderInfo' => $lmsseriesCombo->title,
                'transId' => mt_srand(10),
                'orderType' => self::APPLE_PAY_TYPE,
                'payType' => self::APPLE_PAY_TYPE,
                'extraData' => "merchantName=Hikari Academy",
                'responseTime' => date("Y-m-d H:i:s"),
                'status' => PaymentMethod::PAYMENT_SUCCESS,
                'redeemed_point' => 0,
                'apple_transaction_id' => $appleTransactionId
            ]);

            $relatedSeries = collect([
                $lmsseriesCombo->n1,$lmsseriesCombo->n2,
                $lmsseriesCombo->n3,$lmsseriesCombo->n4,$lmsseriesCombo->n5
            ])->filter(function ($data) {
                return $data > 0;
            });
            foreach($relatedSeries as $itemId) {
                DB::table('payments')->insert([
                    'user_id' => $userId,
                    'item_id' => $itemId,
                    'time' => $lmsseriesCombo->time,
                    'payments_method_id' => $paymentMethod->id,
                ]);
            }

            $purchasedSeriesList = $this->lmsSeriesService->getSeriesListOfSeriesComboSlug($paymentMethod->lmsSeriesCombo->slug);
            foreach ($purchasedSeriesList as $series) {
                $this->userRoadmapService->updateOrCreate([
                    'user_id' => $user->id,
                    'lmsseries_id' => $series->id,
                ]);
            }

            $this->userService->updateSeriesViewsHistory(
                $user->series_views_history ?? [],
                $purchasedSeriesList->pluck('id')->toArray(),
                $user->id
            );

            DB::commit();
            return $paymentMethod;
        } catch(Exception $e) {
            DB::rollBack();
            Log::error("Payment processing failed: " . $e->getMessage(), ['trace' => $e->getTraceAsString()]);

            return response()->json([
                'success' => false,
                'error' => 'An error occurred during payment processing.',
            ], 500);
        }
    }
}
