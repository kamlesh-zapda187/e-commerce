<?php namespace App\Services\Stripe\Account;

use App\Services\Stripe\StripeBaseConfig;
use Exception;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;

/**
 *
 */
class StripeAccountServices extends StripeBaseConfig
{
	public function __construct(array $data = [], string $accountId = "")
	{
		$this->data = $data;
		if (!empty($accountId)) {
			$this->headers["Stripe-Account"] = $accountId;
		}
		parent::__construct();
	}

	/**
	 * Create new stripe connect account
	 *
	 * @param array $data
	 *
	 * @return $this
	 */
	public function createNewAccount(array $data = [])
	{
		try {
			return $this->POST("accounts", $data);
		} catch (Exception $th) {
			Log::error($th->getMessage(), $th->getTrace());

			return $this->raise("Oops something technical went wrong!");
		}
	}

	public function getAccount(string $accountId)
	{
		try {
			return $this->GET("accounts/{$accountId}");
		} catch (Exception $th) {
			Log::error($th->getMessage(), $th->getTrace());

			return $this->raise("Oops something technical went wrong!");
		}
	}

	public function createOnboardingLink(string $accountId, string $authToken, string $reauthUrl = null, string $returnUrl = null)
	{
		try {
			return $this->POST("account_links", [
				"account" => $accountId,
				"refresh_url" => config("stripe.re_onboard", $returnUrl) . "?token={$authToken}",
				"return_url" => config("stripe.return_url", $reauthUrl) . "?token={$authToken}",
				"type" => "account_onboarding",
			]);
		} catch (Exception $th) {
			Log::error($th->getMessage(), $th->getTrace());

			return $this->raise("Oops something technical went wrong!");
		}
	}

	public function updateAccount(string $accountId, array $updateData)
	{
		try {
			return $this->POST("accounts/{$accountId}", $updateData);
		} catch (Exception $th) {
			Log::error($th->getMessage(), $th->getTrace());

			return $this->raise("Oops something technical went wrong!");
		}
	}

	public function schedulePayoutFor7Days(string $accountId)
	{
		try {
			$data = [
				"settings" => [
					"payouts" => [
						"schedule" => [
							"delay_days" => 7,
							"interval" => "weekly",
							"weekly_anchor" => "monday",
						],
						"statement_descriptor" => "Legacy Marketplace",
					],
				],
			];

			return $this->updateAccount($accountId, $data);
		} catch (Exception $th) {
			Log::error($th->getMessage(), $th->getTrace());

			return $this->raise("Oops something technical went wrong!");
		}
	}

	public function scheduleManualPayout(string $accountId)
	{
		try {
			$data = [
				"settings" => [
					"payouts" => [
						"schedule" => [
							"interval" => "manual",
						],
						"statement_descriptor" => "Legacy Marketplace",
					],
				],
			];

			return $this->updateAccount($accountId, $data);
		} catch (Exception $th) {
			Log::error($th->getMessage(), $th->getTrace());

			return $this->raise("Oops something technical went wrong!");
		}
	}
}