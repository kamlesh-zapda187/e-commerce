<?php

namespace App\Models;

use App\Services\Stripe\StripeCharge;
use App\Services\Stripe\StripeService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StripePayment extends Model
{
    use HasFactory;

    /**
     * create payment intent
     * $data ( array ) all post data 
     */
    public static function create_payment_intent($orderdata)
    {
        // create charge Intent
        $paymentIntent = StripeService::Payment()->chargeIntent($orderdata["total_amount"], "Client order.", "CLIENT_ORDER", $orderdata["products"]);

        if(empty($paymentIntent['status']))
        {
            return  ['status' => false, 'message' => $paymentIntent['message'],'data' =>  [] ];
        }    

        if(!$temOrder = TempOrder::saveTempOrder($paymentIntent['intent_client_secret'], $paymentIntent['paymentIntent_Id'] ,$orderdata))
        {
            return  ['status' => false, 'message' => 'something went to wrong, missing order data please try again !','data' =>  [] ];   
        }

        $response = [
            'status' => true,
            'message' => 'Please complete your order',
            'data' =>  [
                "client_secret" => $paymentIntent['intent_client_secret'],
                "config" => [
                    "pub_key" => config('stripe.public'),
                ],
            ],
        ];

        return $response;
    }
}
