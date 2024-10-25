<?php namespace App\Services\Stripe;

use App\Services\Stripe\Account\StripeAccountServices;
use App\Services\Stripe\Others\StripeMiscServices;
use App\Services\Stripe\Transactions\StripePaymentService;

/**
 * Stripe Api services
 */
class StripeService
{
	/**
	 * Sertrip account service api endpoints
	 *
	 * @param array $data
	 * @param string $accountId
	 *
	 * @return StripeAccountServices
	 */
	public static function Account(array $data = [], string $accountId = "")
	{
		return new StripeAccountServices($data, $accountId);
	}

	/**
	 * Other stripe api endpoint services
	 *
	 * @param array $data
	 * @param string $accountId
	 *
	 * @return StripeMiscServices
	 */
	public static function Misc(array $data = [], string $accountId = "")
	{
		return new StripeMiscServices($data, $accountId);
	}

	/**
	 * Stripe payment api service.
	 *
	 * @param array $data
	 * @param string $accountId
	 *
	 * @return StripePaymentService
	 */
	public static function Payment(array $data = [], string $accountId = "")
	{
		return new StripePaymentService($data, $accountId);
	}
}