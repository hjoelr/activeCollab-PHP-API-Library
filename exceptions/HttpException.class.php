<?php

class HttpException extends Exception
{
	private $httpErrorCode = null;
	
	public function __construct($message, $httpErrorCode)
	{
		parent::__construct('HTTP error code: '. $httpErrorCode . (!empty($message) ? '. Message: '. $message : ''));
		
		$this->httpErrorCode = $httpErrorCode;
	}
	
	public function getHttpErrorCode()
	{
		return $this->httpErrorCode;
	}
	
	public function __toString()
	{
		return $this->getMessage();
	}
}