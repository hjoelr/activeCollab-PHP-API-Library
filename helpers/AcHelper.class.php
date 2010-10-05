<?php

require_once '../lib/Snoopy.class.php';
require_once '../exceptions/HttpException.class.php';

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
	
/*======================================================================*\
	Function:	sendPostRequest
	Purpose:	Sends a post request to the specified URL with any given
				parameters.
	Input:		$url			the URL to which to send the post request.
				$postParameters	an array of post parameters in the
								format array('name' => 'value').
				$responseType	indicates what should be done with the
								response.  Valid values are {raw, json}.
									raw: 	will return just
											$snoopy->results
									json:	will convert the response
											into an object using
											json_decode().
	Output:		see $responseType under the Input section above.
\*======================================================================*/
	
	public static function sendPostRequest($url, $postParameters, $responseType='raw')
	{	
		$snoopy = new Snoopy();
		$snoopy->accept = 'application/json';
		$snoopy->set_submit_normal();

		$snoopy->submit($url, $postParameters);
		
		$response = null;
		
		switch ($responseType)
		{
			case 'json':
				$response = json_decode($snoopy->results);
				break;
			case 'raw':
				$response = $snoopy->results;
				break;
			default:
				$response = $snoopy->results;
		}
		
		// on failure, throw an exception
		if ($snoopy->status != 200) {
			
			if ($responseType == 'raw')
			{
				throw new HttpException($response, $snoopy->status);
			} elseif ($responseType == 'json')
			{
				if (isset($response->field_errors) && !empty($response->field_errors))
				{
					$errorMsg = "[";
					$i = 0;
					foreach ($response->field_errors as $field_error) {
						$errorMsg .= ($i!=0) ? ' | ' . $field_error : $field_error;
						++$i;
					}
					$errorMsg .= "]";
					
					throw new HttpException($response->message . ' => ' . $errorMsg, $snoopy->status);
				} else {
					throw new HttpException($response, $snoopy->status);
				}
			}
		}
			
		return $response;
	}
}

?>