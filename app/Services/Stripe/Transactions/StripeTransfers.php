<?php namespace App\Services\Stripe\Transactions;

use Exception;
use Illuminate\Support\Facades\Log;

/**
 * Stripe transfer api service
 */
trait StripeTransfers
{
	/**
	 * transfer funds from parant account to sub-account
	 *
	 * @param string $accountId
	 * @param float $amount
	 * @param string $group
	 * @param string $currency
	 *
	 * @return $this
	 */
	public function transferToAccount(string $accountId, float $amount, string $group = null, string $currency = "gbp")
	{
		try {
			return $this->POST("transfers", [
				"amount" => $amount * 100,
				"currency" => config("stripe.currency"),
				"transfer_group" => $group,
				"destination" => $accountId,
			]);
		} catch (Exception $th) {
			Log::error($th->getMessage(), $th->getTrace());

			return $this->raise("Oops something technical went wrong!");
		}
	}
}