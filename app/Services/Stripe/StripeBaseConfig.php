<?php namespace App\Services\Stripe;

use App\Services\Config\HttpServiceConfig;

class StripeBaseConfig extends HttpServiceConfig
{
	public function __construct()
	{
		$this->token = config("stripe.secret");
		$this->authorization = "Bearer";
		$this->baseUrl = config("stripe.api");
	}
}