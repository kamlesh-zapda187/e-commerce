<?php namespace App\Utilities;

use BadFunctionCallException;
use Carbon\Carbon;
use DateInterval;
use ErrorException;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use InvalidArgumentException;
use Laminas\Escaper\Escaper;
use NumberFormatter;

if (!function_exists("genRandomInt")) {
	/** Generate random integer numbers */
	function genRandomInt(int $len = 8): int
	{
		$r_numbers = [];
		for ($i = 0; $i < $len; $i++) {
			array_push($r_numbers, random_int(0, 9));
		}

		return (int) implode("", $r_numbers);
	}
}

if (!function_exists("random_string")) {
	/**
	 * Create a Random String
	 *
	 * Useful for generating passwords or hashes.
	 *
	 * @param string  $type Type of random string.  basic, alpha, alnum, numeric, nozero, md5, sha1, and crypto
	 * @param integer $len  Number of characters
	 *
	 * @return string
	 */
	function random_string(string $type = "alnum", int $len = 8): string
	{
		switch ($type) {
			case "alnum":
			case "numeric":
			case "nozero":
			case "alpha":
				switch ($type) {
					case "alpha":
						$pool = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
						break;
					case "alnum":
						$pool = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
						break;
					case "numeric":
						$pool = "0123456789";
						break;
					case "nozero":
						$pool = "123456789";
						break;
				}

				// @phpstan-ignore-next-line
				return substr(str_shuffle(str_repeat($pool, ceil($len / strlen($pool)))), 0, $len);
			case "md5":
				return md5(uniqid((string) mt_rand(), true));
			case "sha1":
				return sha1(uniqid((string) mt_rand(), true));
			case "crypto":
				return bin2hex(random_bytes($len / 2));
		}
		// 'basic' type treated as default
		return (string) mt_rand();
	}
}

if (!function_exists("getChargeAmount")) {
	/** Get the real amount to be used with the charged api */
	function getChargeAmount(float $amount)
	{
		return $amount * 100;
	}
}

// --------------------------------------------------------------------------------------
if (!function_exists("format_number")) {
	/**
	 * A general purpose, locale-aware, number_format method.
	 * Used by all of the functions of the number_helper.
	 *
	 * @param float       $num
	 * @param integer     $precision
	 * @param string|null $locale
	 * @param array       $options
	 *
	 * @return string
	 */
	function format_number(float $num, int $precision = 1, string $locale = null, array $options = []): string
	{
		// Type can be any of the NumberFormatter options, but provide a default.
		$type = (int) ($options["type"] ?? NumberFormatter::DECIMAL);

		$formatter = new NumberFormatter($locale, $type);

		// Try to format it per the locale
		if ($type === NumberFormatter::CURRENCY) {
			$formatter->setAttribute(NumberFormatter::FRACTION_DIGITS, $options["fraction"]);
			$output = $formatter->formatCurrency($num, $options["currency"]);
		} else {
			// In order to specify a precision, we'll have to modify
			// the pattern used by NumberFormatter.
			$pattern = "#,##0." . str_repeat("#", $precision);

			$formatter->setPattern($pattern);
			$output = $formatter->format($num);
		}

		// This might lead a trailing period if $precision == 0
		$output = trim($output, ". ");

		if (intl_is_failure($formatter->getErrorCode())) {
			throw new BadFunctionCallException($formatter->getErrorMessage());
		}

		// Add on any before/after text.
		if (isset($options["before"]) && is_string($options["before"])) {
			$output = $options["before"] . $output;
		}

		if (isset($options["after"]) && is_string($options["after"])) {
			$output .= $options["after"];
		}

		return $output;
	}
}
// --------------------------------------------------------------------------------------

if (!function_exists("number_to_size")) {
	/**
	 * Formats a numbers as bytes, based on size, and adds the appropriate suffix
	 *
	 * @param mixed   $num       Will be cast as int
	 * @param integer $precision
	 * @param string  $locale
	 *
	 * @return boolean|string
	 */
	function number_to_size($num, int $precision = 1, string $locale = null)
	{
		// Strip any formatting & ensure numeric input
		try {
			$num = 0 + str_replace(",", "", $num); // @phpstan-ignore-line
		} catch (ErrorException $ee) {
			return false;
		}

		// ignore sub part
		$generalLocale = $locale;
		if (!empty($locale) && ($underscorePos = strpos($locale, "_"))) {
			$generalLocale = substr($locale, 0, $underscorePos);
		}

		if ($num >= 1000000000000) {
			$num = round($num / 1099511627776, $precision);
			$unit = __("number.terabyteAbbr", [], $generalLocale);
		} elseif ($num >= 1000000000) {
			$num = round($num / 1073741824, $precision);
			$unit = __("number.gigabyteAbbr", [], $generalLocale);
		} elseif ($num >= 1000000) {
			$num = round($num / 1048576, $precision);
			$unit = __("number.megabyteAbbr", [], $generalLocale);
		} elseif ($num >= 1000) {
			$num = round($num / 1024, $precision);
			$unit = __("number.kilobyteAbbr", [], $generalLocale);
		} else {
			$unit = __("number.bytes", [], $generalLocale);
		}

		return format_number($num, $precision, $locale, ["after" => " " . $unit]);
	}
}

//--------------------------------------------------------------------

if (!function_exists("number_to_currency")) {
	/**
	 * @param float   $num
	 * @param string  $currency
	 * @param string  $locale
	 * @param integer $fraction
	 *
	 * @return string
	 */
	function number_to_currency(float $num, string $currency, string $locale = null, int $fraction = null): string
	{
		return format_number($num, 1, $locale, [
			"type" => NumberFormatter::CURRENCY,
			"currency" => $currency,
			"fraction" => $fraction,
		]);
	}
}

//--------------------------------------------------------------------

if (!function_exists("number_to_amount")) {
	/**
	 * Converts numbers to a more readable representation
	 * when dealing with very large numbers (in the thousands or above),
	 * up to the quadrillions, because you won't often deal with numbers
	 * larger than that.
	 *
	 * It uses the "short form" numbering system as this is most commonly
	 * used within most English-speaking countries today.
	 *
	 * @see https://simple.wikipedia.org/wiki/Names_for_large_numbers
	 *
	 * @param string      $num
	 * @param integer     $precision
	 * @param string|null $locale
	 *
	 * @return boolean|string
	 */
	function number_to_amount($num, int $precision = 0, string $locale = null)
	{
		// Strip any formatting & ensure numeric input
		try {
			$num = 0 + str_replace(",", "", $num); // @phpstan-ignore-line
		} catch (ErrorException $ee) {
			return false;
		}

		$suffix = "";

		// ignore sub part
		$generalLocale = $locale;
		if (!empty($locale) && ($underscorePos = strpos($locale, "_"))) {
			$generalLocale = substr($locale, 0, $underscorePos);
		}

		if ($num > 1000000000000000) {
			$suffix = __("number.quadrillion", [], $generalLocale);
			$num = round($num / 1000000000000000, $precision);
		} elseif ($num > 1000000000000) {
			$suffix = __("number.trillion", [], $generalLocale);
			$num = round($num / 1000000000000, $precision);
		} elseif ($num > 1000000000) {
			$suffix = __("number.billion", [], $generalLocale);
			$num = round($num / 1000000000, $precision);
		} elseif ($num > 1000000) {
			$suffix = __("number.million", [], $generalLocale);
			$num = round($num / 1000000, $precision);
		} elseif ($num > 1000) {
			$suffix = __("number.thousand", [], $generalLocale);
			$num = round($num / 1000, $precision);
		} elseif ($num > 100) {
			$suffix = __("number.naira", [], $generalLocale);
			$num = round($num / 100);
		}

		return format_number($num, $precision, $locale, ["after" => $suffix]);
	}
}

//--------------------------------------------------------------------

if (!function_exists("number_to_roman")) {
	/**
	 * Convert a number to a roman numeral.
	 *
	 * @param string $num it will convert to int
	 *
	 * @return string|null
	 */
	function number_to_roman(string $num): ?string
	{
		$num = (int) $num;
		if ($num < 1 || $num > 3999) {
			return null;
		}

		$_number_to_roman = function ($num, $th) use (&$_number_to_roman) {
			$return = "";
			$key1 = null;
			$key2 = null;
			switch ($th) {
				case 1:
					$key1 = "I";
					$key2 = "V";
					$keyF = "X";
					break;
				case 2:
					$key1 = "X";
					$key2 = "L";
					$keyF = "C";
					break;
				case 3:
					$key1 = "C";
					$key2 = "D";
					$keyF = "M";
					break;
				case 4:
					$key1 = "M";
					break;
			}
			$n = $num % 10;
			switch ($n) {
				case 1:
				case 2:
				case 3:
					$return = str_repeat($key1, $n);
					break;
				case 4:
					$return = $key1 . $key2;
					break;
				case 5:
					$return = $key2;
					break;
				case 6:
				case 7:
				case 8:
					$return = $key2 . str_repeat($key1, $n - 5);
					break;
				case 9:
					$return = $key1 . $keyF; // @phpstan-ignore-line
					break;
			}
			switch ($num) {
				case 10:
					$return = $keyF; // @phpstan-ignore-line
					break;
			}
			if ($num > 10) {
				$return = $_number_to_roman($num / 10, ++$th) . $return;
			}
			return $return;
		};
		return $_number_to_roman($num, 1);
	}
}

//--------------------------------------------------------------------

if (!function_exists("build_img_for_networktransport")) {
	/**
	 * Build and image base64 encode for safe network transport
	 */
	function build_img_for_networktransport(string $filePath)
	{
		if (!Storage::exists($filePath)) {
			return $filePath;
		}

		$ext = Storage::mimeType($filePath);
		$type = "base64";
		$content = Storage::get($filePath);
		$file = base64_encode($content);
		$data = "data:{$ext};{$type},$file";

		return $data;
	}
}

if (!function_exists("strastrik")) {
	/**
	 * add astrik to a string and return
	 */
	function strastrik($string)
	{
		if (!empty($string)) {
			if (Str::contains($string, "@")) {
				$extr = explode("@", $string)[0];

				$str = substr($extr, 3, round(strlen($extr) / 2));

				$str_stared = [];
				for ($i = 0; $i < strlen($str); $i++) {
					array_push($str_stared, "*");
				}

				return Str::replace($str, implode("", $str_stared), $string);
			} else {
				$str = substr($string, 3, round(strlen($string) / 2));

				$str_stared = [];
				for ($i = 0; $i < strlen($str); $i++) {
					array_push($str_stared, "*");
				}

				return Str::replace($str, implode("", $str_stared), $string);
			}
		}

		return $string;
	}
}

//--------------------------------------------------------------------
/**
 * USSD Bank names
 * ____
 * You can get the bank name by passing the bank code as the array key
 */
defined("USSDBANKS") ||
	define("USSDBANKS", [
		737 => "Guaranty Trust Bank",
		919 => "United Bank of Africa",

		822 => "Sterling Bank",

		966 => "Zenith Bank",

		770 => "Fidelity Bank",
	]);

/**
 * Payve charges token
 */
defined("CHARGE_TOKENS") ||
	define("CHARGE_TOKENS", [
		"Withdrawal" => 5,
		"Deposit" => 5,
		"Transfer" => 1,
		"Snap Pay" => 5,
		"Wallet Transfer" => 0,
		"Payout" => 5,
		"Top Up" => 0,
		"Donation" => 1.5, // 1%
		"Event" => 2, //2 %
		"Contribution" => 100,
	]);

// ------------------------------------------------------------------------------

if (!function_exists("esc")) {
	/**
	 * Performs simple auto-escaping of data for security reasons.
	 * Might consider making this more complex at a later date.
	 *
	 * If $data is a string, then it simply escapes and returns it.
	 * If $data is an array, then it loops over it, escaping each
	 * 'value' of the key/value pairs.
	 *
	 * Valid context values: html, js, css, url, attr, raw, null
	 *
	 * @param string|array $data
	 * @param string       $context
	 * @param string       $encoding
	 *
	 * @return string|array
	 * @throws InvalidArgumentException
	 */
	function esc($data, string $context = "html", string $encoding = null)
	{
		if (is_array($data)) {
			foreach ($data as &$value) {
				$value = esc($value, $context);
			}
		}

		if (is_string($data)) {
			$context = strtolower($context);

			// Provide a way to NOT escape data since
			// this could be called automatically by
			// the View library.
			if (empty($context) || $context === "raw") {
				return $data;
			}

			if (!in_array($context, ["html", "js", "css", "url", "attr"], true)) {
				throw new InvalidArgumentException("Invalid escape context provided.");
			}

			$method = $context === "attr" ? "escapeHtmlAttr" : "escape" . ucfirst($context);

			static $escaper;
			if (!$escaper) {
				$escaper = new Escaper($encoding);
			}

			if ($encoding && $escaper->getEncoding() !== $encoding) {
				$escaper = new Escaper($encoding);
			}

			$data = $escaper->$method($data);
		}

		return $data;
	}
}

// -------------------------------------------------------------------------------

if (!function_exists("http_build_url")) {
	define("HTTP_URL_REPLACE", 1); // Replace every part of the first URL when there's one of the second URL
	define("HTTP_URL_JOIN_PATH", 2); // Join relative paths
	define("HTTP_URL_JOIN_QUERY", 4); // Join query strings
	define("HTTP_URL_STRIP_USER", 8); // Strip any user authentication information
	define("HTTP_URL_STRIP_PASS", 16); // Strip any password authentication information
	define("HTTP_URL_STRIP_AUTH", 32); // Strip any authentication information
	define("HTTP_URL_STRIP_PORT", 64); // Strip explicit port numbers
	define("HTTP_URL_STRIP_PATH", 128); // Strip complete path
	define("HTTP_URL_STRIP_QUERY", 256); // Strip query string
	define("HTTP_URL_STRIP_FRAGMENT", 512); // Strip any fragments (#identifier)
	define("HTTP_URL_STRIP_ALL", 1024); // Strip anything but scheme and host

	// Build an URL
	// The parts of the second URL will be merged into the first according to the flags argument.
	//
	// @param	mixed			(Part(s) of) an URL in form of a string or associative array like parse_url() returns
	// @param	mixed			Same as the first argument
	// @param	int				A bitmask of binary or'ed HTTP_URL constants (Optional)HTTP_URL_REPLACE is the default
	// @param	array			If set, it will be filled with the parts of the composed url like parse_url() would return
	function http_build_url($url, $parts = [], $flags = HTTP_URL_REPLACE, &$new_url = false)
	{
		$keys = ["user", "pass", "port", "path", "query", "fragment"];

		// HTTP_URL_STRIP_ALL becomes all the HTTP_URL_STRIP_Xs
		if ($flags & HTTP_URL_STRIP_ALL) {
			$flags |= HTTP_URL_STRIP_USER;
			$flags |= HTTP_URL_STRIP_PASS;
			$flags |= HTTP_URL_STRIP_PORT;
			$flags |= HTTP_URL_STRIP_PATH;
			$flags |= HTTP_URL_STRIP_QUERY;
			$flags |= HTTP_URL_STRIP_FRAGMENT;
		}
		// HTTP_URL_STRIP_AUTH becomes HTTP_URL_STRIP_USER and HTTP_URL_STRIP_PASS
		elseif ($flags & HTTP_URL_STRIP_AUTH) {
			$flags |= HTTP_URL_STRIP_USER;
			$flags |= HTTP_URL_STRIP_PASS;
		}

		// Parse the original URL,
		// assuming it's a valid url or an array that parse_url returns
		if (is_string($url)) {
			$parse_url = parse_url($url);
		} else {
			$parse_url = (array) $url;
		}

		// Scheme and Host are always replaced
		if (isset($parts["scheme"])) {
			$parse_url["scheme"] = $parts["scheme"];
		}
		if (isset($parts["host"])) {
			$parse_url["host"] = $parts["host"];
		}

		// (If applicable) Replace the original URL with it's new parts
		if ($flags & HTTP_URL_REPLACE) {
			foreach ($keys as $key) {
				if (isset($parts[$key])) {
					$parse_url[$key] = $parts[$key];
				}
			}
		} else {
			// Join the original URL path with the new path
			if (isset($parts["path"]) && $flags & HTTP_URL_JOIN_PATH) {
				if (isset($parse_url["path"])) {
					$parse_url["path"] = rtrim(str_replace(basename($parse_url["path"]), "", $parse_url["path"]), "/") . "/" . ltrim($parts["path"], "/");
				} else {
					$parse_url["path"] = $parts["path"];
				}
			}

			// Join the original query string with the new query string
			if (isset($parts["query"]) && $flags & HTTP_URL_JOIN_QUERY) {
				if (isset($parse_url["query"])) {
					$parse_url["query"] .= "&" . $parts["query"];
				} else {
					$parse_url["query"] = $parts["query"];
				}
			}
		}

		// Strips all the applicable sections of the URL
		// Note: Scheme and Host are never stripped
		foreach ($keys as $key) {
			if ($flags & (int) constant("HTTP_URL_STRIP_" . strtoupper($key))) {
				unset($parse_url[$key]);
			}
		}

		$new_url = $parse_url;

		return (isset($parse_url["scheme"]) ? $parse_url["scheme"] . "://" : "") . (isset($parse_url["user"]) ? $parse_url["user"] . (isset($parse_url["pass"]) ? ":" . $parse_url["pass"] : "") . "@" : "") . (isset($parse_url["host"]) ? $parse_url["host"] : "") . (isset($parse_url["port"]) ? ":" . $parse_url["port"] : "") . (isset($parse_url["path"]) ? $parse_url["path"] : "") . (isset($parse_url["query"]) ? "?" . $parse_url["query"] : "") . (isset($parse_url["fragment"]) ? "#" . $parse_url["fragment"] : "");
	}
}

// -------------------------------------------------------------------------------

if (!function_exists("url_shortener_domain")) {
	/**
	 * Get url shortener domain
	 *
	 * @return string
	 */
	function url_shortener_domain()
	{
		if (env("APP_ENV") === "local" || env("APP_ENV") === "development") {
			return env("SHORTENER_DEV_DOMAIN", "http://localhost:3000");
		} else {
			return env("SHORTENER_PROD_DOMAIN", "https://mypayve.com");
		}
	}
}

if (!function_exists("filterAssocArray")) {
	/**
	 * Filter element in an array and return the elem that pass the test in
	 * the filter function.
	 *
	 * @param array $array
	 * @param mixed $filterCallback
	 *
	 * @return array
	 */
	function filterAssocArray(array $array, $filterCallback)
	{
		$result = [];

		if (count($array) <= 1) {
			array_push($result, $array[0]);

			return $result;
		}

		for ($i = 0; $i < count($array); ) {
			if (is_callable($filterCallback)) {
				if (isset($array[$i + 1])) {
					if ($return = $filterCallback($array[$i], $array[$i + 1])) {
						array_push($result, $return);
					}
				}
			}
			$i++;
		}
		return $result;
	}
}

if (!function_exists("getTimeDifference")) {
	/**
	 * Get the difference between the start and end time
	 *
	 * @param string $starttime
	 * @param string $endtime
	 *
	 * @return DateInterval
	 */
	function getTimeDifference(string $starttime, string $endtime)
	{
		$start = Carbon::createFromTimestamp(strtotime(date($starttime)));
		$end = Carbon::createFromTimestamp(strtotime(date($endtime)));

		return $end->diff($start);
	}
}