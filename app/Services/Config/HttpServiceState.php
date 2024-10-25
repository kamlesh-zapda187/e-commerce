<?php namespace App\Services\Config;

/**
 * http service request state
 */
trait HttpServiceState
{
	public $STATE = false;
	public $ERROR = "";
	public $MESSAGE = "";
	public $RESPONSE;

	/**
	 * Raise an error
	 *
	 * @param string $error
	 * @param mixed $response
	 *
	 */
	protected function raise($error = "Oops something went wrong!", $response = null)
	{
		$this->RESPONSE = $response;
		$this->ERROR = $error;
		$this->STATE = false;

		return $this;
	}

	/**
	 * Return operation response
	 *
	 * @param mixed $response
	 * @param string $message
	 *
	 */
	protected function response($response = null, $message = "Process completed successfully")
	{
		$this->RESPONSE = $response;
		$this->MESSAGE = $message;
		$this->STATE = true;

		return $this;
	}
}