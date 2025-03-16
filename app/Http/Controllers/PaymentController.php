<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
USE Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use App\Models\Payment;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    // for development
    public function create(Request $request)
    {
        $params = array (
            'transaction_details' => array(
                'order_id' => Str::uuid(),
                'gross_amount' => $request->price,
            ),
            'item_details' => array(
                array(
                    'price' => $request->price,
                    'quantity' => 1,
                    'name' => $request->item_name
                )
            ),
            'customer_details' => array(
                'first_name' => $request->customer_first_name,
                'email' => $request->customer_email
            ),
            'enabled_payments' => array('credit_card', 'bca_va', 'bni_va', 'bri_va')
        );

        $auth = base64_encode(env('MIDTRANS_SERVER_KEY'));

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => "Basic $auth",
        ])->post('https://app.sandbox.midtrans.com/snap/v1/transactions', $params);

        $response = json_decode($response->body());

        $payment = new Payment();
        $payment->order_id = $params['transaction_details']['order_id'];
        $payment->status = 'pending';
        // $payment->price = $request->price;
        $payment->customer_first_name = $request->customer_first_name;
        $payment->customer_email = $request->customer_email;
        $payment->item_name = $request->item_name;
        $payment->price = $request->price;
        $payment->checkout_link = $response->redirect_url;
        $payment->save();

        // Member::create([
        //     'student_id' => $request->student_id, 
        //     'mentor_id' => $request->mentor_id, 
        //     'memberType' => 'bulanan', 
        //     'startDate' => '2024/06/20', 
        //     'endDate' => '2024/06/30'
        // ]);

        return response()->json($response);
    }

    public function webHook(Request $request)
    {
        $auth = base64_encode(env('MIDTRANS_SERVER_KEY'));   

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => "Basic $auth",
        ])->get("https://api.sandbox.midtrans.com/v2/$request->order_id/status");
        
        $response = json_decode($response->body());

        // Log::info($response);

        // cek db
        $payment = Payment::where('order_id', $request->order_id)->first();

        if (!$payment) {
            return response()->json('Payment not found', 404);
        }

        if ($payment->status == 'settlement' || $payment->status == 'capture') {
            return response()->json('Payment has been already processed');
        }

        if ($response->transaction_status == 'capture') {
            $payment->status = 'capture';
        } elseif ($response->transaction_status == 'settlement') {
            $payment->status = 'settlement';
        } elseif ($response->transaction_status == 'pending') {
            $payment->status = 'pending';
        } elseif ($response->transaction_status == 'deny') {
            $payment->status = 'deny';
        } elseif ($response->transaction_status == 'expire') {
            $payment->status = 'expire';
        }

        $payment->save();
        return response()->json('Payment has been already processed');
    }
}
