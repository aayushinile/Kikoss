<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;
use Braintree\Gateway;
use Braintree\Transaction;
use BaconQrCode\Renderer\Image\Png;
use BaconQrCode\Writer;
//namespace PayPalCheckoutSdk\Core;

class PaymentVenmoController extends Controller
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


    public function paywithVenmo(Request $request)
{
    $successUrl = route('success.payment');
    $cancelUrl = route('cancel.payment');
    $clientId = 'Ac6Sapkr7ApbZpnLER0ytdRQ93YqciynMkqRO5ElvG_OIAi4hw8nvzOtReAUjvOn8jlLi1Y4MdUrFqdb';
    $clientSecret = 'EIfNdYHhtJwfAcm2j4_Oft4sXaIFfYGB0dVlmUW6hwEqSLJ_O_nzwW5DuxYZYxvDveJv8VOh-z-sSA2M';
    $environment = new SandboxEnvironment($clientId, $clientSecret);
    $client = new PayPalHttpClient($environment);

    $request = new OrdersCreateRequest();
    $request->prefer('return=representation');
    $request->body = [
        "intent" => "CAPTURE",
        "application_context" => [
            "return_url" => $successUrl,
            "cancel_url" => $cancelUrl,
            "payment_method" => [
                "payer_selected" => "PAYPAL",
                "payee_preferred" => "VENMO"
            ]
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
    } catch (\Throwable $e) {
        dd($e);
    }
}



    public function processPayment(Request $request)
    {
        $nonceFromTheClient = $request->payment_method_nonce;

        $result = Transaction::sale([
            'amount' => '1.00',
            'paymentMethodNonce' => $nonceFromTheClient,
            'options' => [
                'submitForSettlement' => true,
                'venmo' => [
                    'profileId' => env('BRAINTREE_VENMO_PROFILE_ID'),
                ],
            ],
        ]);

        if ($result->success) {
            // Payment successful
            return redirect()->route('success.payment');
        } else {
            // Payment failed
            return redirect()->route('cancel.payment');
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