<?php

namespace App\Models;

use App\Services\Stripe\StripeCharge;
use App\Services\Stripe\StripeService;
use App\Services\Stripe\Transactions\StripePaymentService;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use SebastianBergmann\Template\Template;

class Product extends Model
{
    use HasFactory;

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public static  function getCartProducts(array $cartItem){
        $cartProducts = [];
        if(!empty($cartItem)){
            foreach($cartItem as $cartKey => $cartItem){
                if($product = self::where(['id'=>$cartItem->product_id])->first()){
                    $product->qty = $cartItem->qty;
                    $cartProducts[$cartKey] = $product;    
                }    
            }
        }

        return $cartProducts;
        
    }

    public function response($response = null, $message = "Process completed successfully")
	{
		$this->RESPONSE = $response;
		$this->MESSAGE = $message;
		$this->STATE = true;

		return $this;
	}

    public function init_order_charge($params){
        try {
			

			/* create payment secret first  */
            $chargeIntent =  (new StripeCharge)->chargeIntent($params["total_amount"], "Client order.", "CLIENT_ORDER", $params["products"]);

           
			if (!$chargeIntent['status'] && empty($chargeIntent['status'])) {
                throw new \InvalidArgumentException("couldn't complete order at the moment, please try again later!");
			}

            if (!($orderId = TempOrder::__saveTempOrder($chargeIntent['paymentIntent_Id'], $paymentService->RESPONSE->id, $params))) {
				return $this->raise("Oops! Couldn't create order.");
			}

			return $this->response(
				[
					"client_secret" => $chargeIntent['paymentIntent_Id'],
					"config" => [
						"pub_key" => config('stripe.public'),
					],
				],
				"Please complete your order."
			);
		} catch (Exception $th) {
			Log::error($th->getMessage(), $th->getTrace());

			return $this->raise();
		}
    }
}
