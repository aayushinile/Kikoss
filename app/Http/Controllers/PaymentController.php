<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;
use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;
use PayPalCheckoutSdk\Payments\CapturesRefundRequest;

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
        $amount = '0.01';
        $successUrl = route('success.payment'); // Replace 'success.payment' with your actual route name for success
        $cancelUrl = route('cancel.payment'); // Replace 'cancel.payment' with your actual route name for cancellation
        $id = 'Ac6Sapkr7ApbZpnLER0ytdRQ93YqciynMkqRO5ElvG_OIAi4hw8nvzOtReAUjvOn8jlLi1Y4MdUrFqdb';
        $id_2 = 'EIfNdYHhtJwfAcm2j4_Oft4sXaIFfYGB0dVlmUW6hwEqSLJ_O_nzwW5DuxYZYxvDveJv8VOh-z-sSA2M';
        $environment = new SandboxEnvironment($id,$id_2);
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

        // PayPal API credentials
        $clientId = 'Ac6Sapkr7ApbZpnLER0ytdRQ93YqciynMkqRO5ElvG_OIAi4hw8nvzOtReAUjvOn8jlLi1Y4MdUrFqdb';
        $secret = 'EIfNdYHhtJwfAcm2j4_Oft4sXaIFfYGB0dVlmUW6hwEqSLJ_O_nzwW5DuxYZYxvDveJv8VOh-z-sSA2M';
        $transactionId = '4CD16313GA452650M';

        try {

            $environment = new SandboxEnvironment($clientId, $secret);
            $client = new PayPalHttpClient($environment);
            $captureRequest = new OrdersCaptureRequest($transactionId);
            $captureRequest->prefer('return=representation');
            $captureResponse = $client->execute($captureRequest);
            $captureId = $captureResponse->result->purchase_units[0]->payments->captures[0]->id;

            $data['status'] = true;
            $data['message'] = 'Payment successful!';
            $data['captureId'] = $captureId;
            $data['response'] = $request->all();

            return response()->json($data);
        } catch (\Throwable $e) {
            dd($e);
        }
    }




    public function cancel(Request $request)
    {
        $data['status'] = true;
        $data['message'] = 'Payment canceled!';
        return response()->json($data);
        // Payment cancelation logic
        //return "Payment canceled!";
    }



    public function refund(Request $request)
    {
        // dd($request->all());die;
        $clientId = 'Ac6Sapkr7ApbZpnLER0ytdRQ93YqciynMkqRO5ElvG_OIAi4hw8nvzOtReAUjvOn8jlLi1Y4MdUrFqdb';
        $clientSecret ='EIfNdYHhtJwfAcm2j4_Oft4sXaIFfYGB0dVlmUW6hwEqSLJ_O_nzwW5DuxYZYxvDveJv8VOh-z-sSA2M';

        // Get the capture ID from the request
        $captureId = $request->input('captureId');
        $refundAmount = $request->input('amount');
        $currency = 'USD'; 

        try {
            $environment = new SandboxEnvironment($clientId, $clientSecret);
            $client = new PayPalHttpClient($environment);

            // Create a request to refund the capture
            $refundRequest = new CapturesRefundRequest($captureId);
            $refundRequest->prefer('return=representation');
            $refundRequest->body = [
                'amount' => [
                    'value' => $refundAmount,
                    'currency_code' => $currency,
                ],
            ];

            // Execute the refund request
            $refundResponse = $client->execute($refundRequest);

            // Check if refund was successful
            if ($refundResponse->statusCode === 201) {
                // Refund successful
                return 'Refund successful';
            } else {
                // Refund failed
                return 'Refund failed: ' . $refundResponse->result->message;
            }
        } catch (\Throwable $e) {
            dd($e);
        }
    }

}