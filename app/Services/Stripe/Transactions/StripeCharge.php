<?php namespace App\Services\Stripe\Transactions;

use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Stripe\PaymentIntent;

/**
 * Stripe change api endpoint
 */
trait StripeCharge
{
	/**
	 * Initiate a payment intention to generate a secret
	 *
	 * @param float $amount
	 * @param string $description
	 * @param string $group
	 * @param array $customData
	 * @param string $currency
	 *
	 * @return $this
	 */
	public  function chargeIntent(float $amount, string $description, string $group = "SUPPLIER_ORDER", array $customData = [])
	{
		//Stripe::setApiKey(config('stripe.secret'));
		try {

			$paymentIntents =  PaymentIntent::create([
				'payment_method_types' => ['card'],
				'amount' => $amount*100,
				'currency' => 'gbp',
			]);

			return $paymentIntent = [
				'status'  => 1,
				'message' => 'success',
				'paymentIntent_Id' => $paymentIntents->id,
				'intent_client_secret' => $paymentIntents->client_secret,
			];
		} catch (Exception $th) {
			Log::error($th->getMessage(), $th->getTrace());

			return $paymentIntent = [
				'status'  => 0,
				'message' => $th->getMessage(),
				'paymentIntent_Id' => FALSE,
			];
		}
	}

	/**
	 * Get payment intent information
	 *
	 * @param string $reference
	 *
	 * @return $this
	 */
	public function getChargeIntent(string $reference)
	{
		try {
			return  $paymentIntent = PaymentIntent::retrieve($reference);
			//$this->GET("payment_intents/{$reference}");
		} catch (Exception $th) {
			Log::error($th->getMessage(), $th->getTrace());

			return $this->raise("Oops something technical went wrong!");
		}
	}
}