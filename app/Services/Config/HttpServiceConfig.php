<?php namespace App\Services\Config;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Api http service configurations options
 */
class HttpServiceConfig
{
	use HttpServiceState;
	/**
	 * api services base url
	 *
	 * @var string
	 */
	protected $baseUrl = "";
	/**
	 * The api services authorization type
	 *
	 * @var string
	 */
	protected $authorization = "Bearer";
	/**
	 * The api services authorization token
	 *
	 * @var string
	 */
	protected $token = "";
	/**
	 * The api services request params
	 *
	 * @var array
	 */
	protected $data = [];
	/**
	 * The api services request content type that
	 * should be returned
	 *
	 * @var string
	 */
	protected $accept = "application/json";

	/**
	 * Request headers
	 *
	 * @var array
	 */
	protected $headers = [];

	/**
	 * Initiate a post request
	 *
	 * @param string $url
	 * @param array $data
	 * @param array $configHeaders
	 *
	 * @return $this
	 */
	public function POST(string $url, array $data = [], array $configHeaders = [])
	{
		try {
			$response = Http::accept($this->accept)
				->withHeaders(!empty($configHeaders) ? $configHeaders : $this->headers)
				->withToken($this->token, $this->authorization)
				->asForm()
				->retry(2, 1)
				->post("{$this->baseUrl}{$url}", !empty($data) ? $data : $this->data);

			/* determin error level */

			if (!$response->successful()) {
				throw new Exception("Oops something went wrong", $response->body());
			}
			/* return response  */
			return $this->response($response->object(), "service request succeeded");
		} catch (Exception $th) {
			Log::error($th->getMessage(), $th->getTrace());

			return $this->raise("Stripe service error.");
		}
	}

	/**
	 * Initiate a get request.
	 *
	 * @param string $url
	 * @param array $data
	 * @param array $configHeaders
	 *
	 * @return $this
	 */
	public function GET(string $url, array $data = [], array $configHeaders = [])
	{
		try {
			$response = Http::accept($this->accept)
				->withHeaders(!empty($configHeaders) ? $configHeaders : $this->headers)
				->withToken($this->token, $this->authorization)
				->asForm()
				->retry(2, 1)
				->get("{$this->baseUrl}{$url}", !empty($data) ? $data : $this->data);

			/* determin error level */

			if (!$response->successful()) {
				throw new Exception("Oops something went wrong", $response->body());
			}
			/* return response  */
			return $this->response($response->object(), "service request succeeded");
		} catch (Exception $th) {
			Log::error($th->getMessage(), $th->getTrace());

			return $this->raise("Stripe service error.");
		}
	}

	/**
	 * Initiate a put request.
	 *
	 * @param string $url
	 * @param array $data
	 * @param array $configHeaders
	 *
	 * @return $this
	 */
	public function PUT(string $url, array $data = [], array $configHeaders = [])
	{
		try {
			$response = Http::accept($this->accept)
				->withHeaders(!empty($configHeaders) ? $configHeaders : $this->headers)
				->withToken($this->token, $this->authorization)
				->asForm()
				->retry(2, 1)
				->put("{$this->baseUrl}{$url}", !empty($data) ? $data : $this->data);

			/* determin error level */

			if (!$response->successful()) {
				throw new Exception("Oops something went wrong", $response->body());
			}
			/* return response  */
			return $this->response($response->object(), "service request succeeded");
		} catch (Exception $th) {
			Log::error($th->getMessage(), $th->getTrace());

			return $this->raise("Stripe service error.");
		}
	}

	/**
	 * Initiate a delete request.
	 *
	 * @param string $url
	 * @param array $data
	 * @param array $configHeaders
	 *
	 * @return $this
	 */
	public function DELETE(string $url, array $data = [], array $configHeaders = [])
	{
		try {
			$response = Http::accept($this->accept)
				->withHeaders(!empty($configHeaders) ? $configHeaders : $this->headers)
				->withToken($this->token, $this->authorization)
				->asForm()
				->retry(2, 1)
				->delete("{$this->baseUrl}{$url}", !empty($data) ? $data : $this->data);

			/* determin error level */

			if (!$response->successful()) {
				throw new Exception("Oops something went wrong", $response->body());
			}
			/* return response  */
			return $this->response($response->object(), "service request succeeded");
		} catch (Exception $th) {
			Log::error($th->getMessage(), $th->getTrace());

			return $this->raise("Stripe service error.");
		}
	}
}