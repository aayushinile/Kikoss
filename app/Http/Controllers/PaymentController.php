<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;
//namespace PayPalCheckoutSdk\Core;

class PaymentController extends Controller
{
    // public function pay(Request $request)
    // {
    //     $environment = new SandboxEnvironment(env('PAYPAL_CLIENT_ID'), env('PAYPAL_SECRET'));
    //     $client = new PayPalHttpClient($environment);

    //     $request = new OrdersCreateRequest();
    //     $request->prefer('return=representation');
    //     $request->body = [
    //         "intent" => "CAPTURE",
    //         "purchase_units" => [[
    //             "amount" => [
    //                 "currency_code" => "USD",
    //                 "value" => "31.00"
    //             ]
    //         ]]
    //     ];

    //     try {
    //         $response = $client->execute($request);
    //         $data['status'] = true;
    //         $data['message'] = 'Link';
    //         $data['data'] = $response->result->links[1]->href;
    //         return response()->json($data);
    //         //return redirect($response->result->links[1]->href);
    //     } catch (\Throwable $e) {
    //         dd($e);
    //     }
    // }
    public function pay(Request $request)
    {
        $successUrl = route('success.payment'); // Replace 'payment.success' with your actual route name for success
        $cancelUrl = route('cancel.payment'); // Replace 'payment.cancel' with your actual route name for cancellation

        $environment = new SandboxEnvironment(env('PAYPAL_CLIENT_ID'), env('PAYPAL_SECRET'));
        $client = new PayPalHttpClient($environment);

        $request = new OrdersCreateRequest();
        $request->prefer('return=representation');
        $request->body = [
            "intent" => "CAPTURE",
            "application_context" => [
                "return_url" => $successUrl,
                "cancel_url" => $cancelUrl
            ],
            "purchase_units" => [[
                "amount" => [
                    "currency_code" => "USD",
                    "value" => "1.00"
                ]
            ]]
        ];

        try {
            $response = $client->execute($request);
            $data['status'] = true;
            $data['message'] = 'Link';
            $data['data'] = $response->result->links[1]->href;
            return response()->json($data);
            //return redirect($response->result->links[1]->href);
        } catch (\Throwable $e) {
            dd($e);
        }
    }



    public function success(Request $request)
    {
        // Payment success logic
        //{"status":true,"message":"Payment successful!","response":{"token":"60Y92069YH7381032","PayerID":"TCFFB7L3FMKXS"},"paymentId":null}
        $data['status'] = true;
        $data['message'] = 'Payment successful!';
        $data['PayerID'] = $request->input('PayerID');
        $data['token'] = $request->input('token');
        
        $data['response'] = $request->all();
        //$data['paymentId'] = $paymentId;
        
        return response()->json($data);
        //return "Payment successful!";
    }

    public function cancel(Request $request)
    {
        $data['status'] = true;
        $data['message'] = 'Payment canceled!';
        return response()->json($data);
        // Payment cancelation logic
        //return "Payment canceled!";
    }
}