<?php namespace App\Services\Stripe\Transactions;

use App\Services\Stripe\StripeBaseConfig;
use Stripe\Stripe;

class StripePaymentService extends StripeBaseConfig
{
	use StripeCharge, StripeTransfers, StripePayouts, StripeRefunds;

	public function __construct()
	{
		Stripe::setApiKey(config('stripe.secret'));
	}
}