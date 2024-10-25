<?php namespace App\Services\Stripe\Others;

use App\Services\Stripe\StripeBaseConfig;
use Exception;
use Illuminate\Support\Facades\Log;

class StripeMiscServices extends StripeBaseConfig
{
	public function __construct(array $data = [], string $accountId = "")
	{
		$this->data = $data;
		if (!empty($accountId)) {
			$this->headers["Stripe-Account"] = $accountId;
		}
		parent::__construct();
	}

	public function getBalance()
	{
		try {
			return $this->GET("balance");
		} catch (Exception $th) {
			Log::error($th->getMessage(), $th->getTrace());

			return $this->raise("Oops something technical went wrong!");
		}
	}
}