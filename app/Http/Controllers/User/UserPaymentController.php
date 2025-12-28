<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\District;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class UserPaymentController extends Controller
{
    public function initiate(Request $request)
    {
        $merchant_id = env('PAYHERE_MERCHANT_ID');
        $secret = env('PAYHERE_SECRET');

        $paymentData = [
            'merchant_id' => env('PAYHERE_MERCHANT_ID'),
            'return_url' => env('PAYHERE_REDIRECT_URL'),
            'cancel_url' => env('PAYHERE_CANCEL_URL'),
            'notify_url' => env('PAYHERE_NOTIFY_URL'),
            'order_id' => uniqid(),
            'items' => $request->input('items','add two seat'),
            'currency' => 'LKR',
            'amount' => $request->input('amount'),
        ];

        $hash = strtoupper(
            md5(
                $merchant_id .
                $paymentData['order_id'] .
                number_format($paymentData['amount'], 2, '.', '') .
                $paymentData['currency'] .
                strtoupper(md5($secret))
            )
        );

        $paymentData['hash'] = $hash;

        return response()->json([
            'payment_url' => 'https://sandbox.payhere.lk/pay/checkout',
            'payment_data' => $paymentData,
        ]);
    }

    //can't test this part localhost need to hosting
    public function verify(Request $request)
    {
        Log::info("Received payment verification request:", $request->all());

        $orderId = $request->input('order_id');
        if (!$orderId) {
            return response()->json(['success' => false, 'message' => 'Invalid order ID'], 400);
        }

        $payment = \App\Models\Booking::where('transaction_id', $orderId)->first();
        if (!$payment) {
            return response()->json(['success' => false, 'message' => 'Order not found'], 404);
        }

        $payhereResponse = json_decode(file_get_contents("php://input"), true);
        Log::info("PayHere Notification:", $payhereResponse);

        if ($payhereResponse['status'] === 'SUCCESS') {
            $payment->update(['status' => 'paid']); // Update DB
            return response()->json(['success' => true, 'message' => 'Payment verified']);
        }

        return response()->json(['success' => false, 'message' => 'Payment verification failed']);
    }
}
