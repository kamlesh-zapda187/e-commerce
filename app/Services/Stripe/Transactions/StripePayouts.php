<?php namespace App\Services\Stripe\Transactions;

use Exception;
use Illuminate\Support\Facades\Log;

/**
 * Stripe transfer api service
 *
 */
trait StripePayouts
{
	/**
	 * Initiate a payout request
	 *
	 * @param float $amount
	 * @param string $description
	 * @param array $customData
	 * @param bool $instantPayout
	 * @param string $currency
	 * @param string $source
	 *
	 * @return $this
	 */
	public function payout(float $amount, string $description = "", array $customData = [], bool $instantPayout = false, string $currency = "gbp", string $source = "card")
	{
		try {
			return $this->POST("payouts", [
				"amount" => $amount * 100,
				"currency" => $currency,
				"method" => $instantPayout ? "instant" : "standard",
				"description" => !empty($description) ? $description : "Manual payout request.",
				"source_type" => $source,
				"metadata" => $customData,
			]);
		} catch (Exception $th) {
			Log::error($th->getMessage(), $th->getTrace());

			return $this->raise("Oops something technical went wrong!");
		}
	}

	/**
	 * Fetch payouts that has been paid to a particular account.
	 *
	 * @param int $limit The number of items to return in the pagination list.
	 *
	 * @return $this
	 */
	public function fetchPayouts(int $limit)
	{
		try {
			return $this->GET("payouts", ["limit" => $limit]);
		} catch (Exception $th) {
			Log::error($th->getMessage(), $th->getTrace());

			return $this->raise("Oops something technical went wrong!");
		}
	}
}
