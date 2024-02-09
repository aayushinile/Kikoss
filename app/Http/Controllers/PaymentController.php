<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;

class PaymentController extends Controller
{
    // public function pay(Request $request)
    // {
    //     $successUrl = route('success.payment'); // Replace 'payment.success' with your actual route name for success
    //     $cancelUrl = route('cancel.payment'); // Replace 'payment.cancel' with your actual route name for cancellation

    //     $environment = new SandboxEnvironment(env('PAYPAL_CLIENT_ID'), env('PAYPAL_SECRET'));
    //     $client = new PayPalHttpClient($environment);

    //     $request = new OrdersCreateRequest();
    //     $request->prefer('return=representation');
    //     $request->body = [
    //         "intent" => "CAPTURE",
    //         "application_context" => [
    //             "return_url" => $successUrl . '?transaction_id=' . $transactionId, // Append transaction ID to success URL
    //             "cancel_url" => $cancelUrl
    //         ],
    //         "purchase_units" => [[
    //             "amount" => [
    //                 "currency_code" => "USD",
    //                 "value" => "1.00"
    //             ]
    //         ]]
    //     ];

    //     try {
    //         $response = $client->execute($request);
    //         $transactionId = $response->result->id; // Get transaction ID from the response
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
        $amount = $request->amount;
        $successUrl = route('success.payment'); // Replace 'success.payment' with your actual route name for success
        $cancelUrl = route('cancel.payment'); // Replace 'cancel.payment' with your actual route name for cancellation

        $environment = new SandboxEnvironment(env('PAYPAL_CLIENT_ID'), env('PAYPAL_SECRET'));
        $client = new PayPalHttpClient($environment);

        $request = new OrdersCreateRequest();
        $request->prefer('return=representation');
        $request->body = [
            "intent" => "CAPTURE",
            "application_context" => [
                "return_url" => $successUrl, // Success URL without transaction ID
                "cancel_url" => $cancelUrl
            ],
            "purchase_units" => [
                [
                    "amount" => [
                        "currency_code" => "USD",
                        "value" => $amount
                    ]
                ]
            ]
        ];

        try {
            $response = $client->execute($request);
            $transactionId = $response->result->id; // Get transaction ID from the response

            // Append transaction ID to success URL
            $successUrl .= '?transaction_id=' . $transactionId;
            //Log::info('Success URL: ' . $successUrl); // Log the success URL

            $data['status'] = true;
            $data['message'] = 'Link';
            $data['transactionId'] = $transactionId;
            //$data['response'] = $response;
            $data['data'] = $response->result->links[1]->href;
            return response()->json($data);
        } catch (\Throwable $e) {
            dd($e);
        }
    }

    public function success(Request $request)
    {
        // Log all request data
        //Log::info('Request data:', $request->all());

        $data['status'] = true;
        $data['message'] = 'Payment successful!';
        $data['PayerID'] = $request->input('PayerID');
        $data['token'] = $request->input('token');
        $data['response'] = $request->all();
        return response()->json($data);
        // Further processing logic...
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