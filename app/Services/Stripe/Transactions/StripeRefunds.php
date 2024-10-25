<?php namespace App\Services\Stripe\Transactions;

use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Stripe\Refund;

/**
 * Stripe change api endpoint
 */
trait StripeRefunds
{
	

	/**
	 * Refund a charge transaction to the customer
	 *
	 * @param string $paymentIntentId
	 * @param string $reason
	 *
	 * @return $this
	 */
			
	public function refundChargeIntent(string $paymentIntentId, string $reason = "requested_by_customer")
	{
		try {
			
			$refund = Refund::create([
				'payment_intent' => $paymentIntentId,
				'reason' => $reason
			]);

			return $paymentIntent = [
				'status' => true,
				'message' => 'Payment refunded successfully.',
				'refund' => ['id' => $refund->id,'amount' => $refund->amount],
			];

		} catch (Exception $th) {
			Log::error($th->getMessage(), $th->getTrace());

			return $paymentIntent = [
				'status'  => 0,
				'message' => $th->getMessage(),
				'paymentIntent_Id' => $paymentIntentId,
			];
		}
	}

	/**
	 * Refund a charge intent transaction to customer
	 *
	 * @param float $amount
	 * @param string $intentId
	 * @param array $customData
	 * @param string $reason
	 *
	 * @return $this
	 */
	public function refundCharge(float $amount, string $intentId, array $customData = [], string $reason = "requested_by_customer")
	{
		try {
			\Stripe\Refund::create([
				'payment_intent' => $intentId,
			]);

			return $paymentIntent = [
				'status'  => 1,
				'message' => 'success',
			];

		} catch (Exception $th) {
			Log::error($th->getMessage(), $th->getTrace());
		}
	}
}