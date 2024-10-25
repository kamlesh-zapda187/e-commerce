<?php

return [
	"return_url" => env("APP_URL", "http://localhost/laravel/e-commerce") . "/api/account/stripe/done_onboarding",
	"re_onboard" => env("APP_URL", "http://localhost/laravel/e-commerce") . "/api/account/stripe/reauth",

	"secret" => env("STRIP_SECRET_KEY"),
	"public" => env("STRIP_PULBIC_KEY"),
	"api" => env("STRIP_ENDPOINT"),
	"currency" => env("STRIP_CURRENCY", "gbp"),
];