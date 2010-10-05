<?php

require_once '../lib/Snoopy.class.php';

class AcHelper
{
	public static function makeApiUrl($url, $userAPIKey) {
		if (strpos($url, '?')) {
			$url .= '&';
		} else {
			$url .= '?';
		}
		return $url . "token=" . $userAPIKey;
	}
	
	// sends makes the actual post requests to the server
	public static function sendPostRequest($relativeUrl, $postParameters, $acBaseUrl, $userApiKey)
	{	
		$snoopy = new Snoopy();
		$snoopy->accept = 'application/json';
		$snoopy->set_submit_normal();
		
		$url = AcHelper::makeApiUrl($acBaseUrl . $relativeUrl, $userApiKey);

		$snoopy->submit($url, $postParameters);
		
		// on failure, throw an exception
		if ($snoopy->status != 200) {
			$rawError = json_decode($snoopy->results);
			
			$errorMsg = "[";
			$i = 0;
			foreach ($rawError->field_errors as $field_error) {
				$errorMsg .= ($i!=0) ? ' | ' . $field_error : $field_error;
				++$i;
			}
			$errorMsg .= "]";
			
			throw new Exception($rawError->message . ' => ' . $errorMsg);
		}
		
		$rawTicket = json_decode($snoopy->results);
			
		return $rawTicket;
	}
}

?>