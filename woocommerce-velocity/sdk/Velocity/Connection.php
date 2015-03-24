<?php

/* 
 * The `Velocity_Connection` class is responsible for making requests to 
 * the Velocity API and parsing the returned response.
 */
class Velocity_Connection 
{
	private static $instance;
	
	public static function instance() {
		if (!isset(self::$instance)) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	private function __construct() {
	}

	/*
     * Convenience method for making GET requests.
	 *
	 * @param string $path the relative url of request at gateway. 
	 * @param array $data the array holds xml data , session token and gateway request method name.
	 * @return array $this->request('GET', $path, $data) gateway response for both success or failure.
	 */
	public function get($path, $data = array()) {
		if (isset($path) && isset($data)) {	
			return $this->request('GET', $path, $data);
		} else {	
			throw new Exception($data['method'].':'.Velocity_Message::$descriptions['errgetmethod']);
		}
	}

	/*
     * Convenience method for making POST requests.
	 *
	 * @param string $path the relative url of request at gateway. 
	 * @param array $data the array holds xml data , session token and gateway request method name.
	 * @return array $this->request('POST', $path, $data) gateway response for both success or failure.	 
	 */
	public function post($path, $data = array()) { 
		if (isset($path) && isset($data)) {
			return $this->request('POST', $path, $data);
		} else {
			throw new Exception($data['method'].':'.Velocity_Message::$descriptions['errpostmethod']);
		}
	}

	/*
     * Convenience method for making PUT requests.
	 *
	 * @param string $path the relative url of request at gateway. 
	 * @param array $data the array holds xml data , session token and gateway request method name.
	 * @return array $this->request('PUT', $path, $data) gateway response for both success or failure.		 
	 */
	public function put($path, $data = array()) {
		if (isset($path) && isset($data)) { 
			return $this->request('PUT', $path, $data);
		} else {
			throw new Exception($data['method'].':'.Velocity_Message::$descriptions['errputmethod']);
		}
	}

	/*
	 * SignOn method genrate the session token by passing the identitytoken.
	 * @return array $this->handleResponse($error, $response) array of successfull or failure of gateway response. 
	 */
	public function signOn() {
		try { 
														
			list($error, $response) = $this->get('SvcInfo/token', 
													array(
														'sessiontoken' => Velocity_Processor::$identitytoken, 
														'xml' => '', 
														'method' => 'SignOn'
													)
												 ); 
			if ( $error == NULL && $response != '' )
			          
				return $response;
			else
				throw new Exception( Velocity_Message::$descriptions['errsignon'] );
		} catch (Exception $e) {
			throw new Exception( $e->getMessage() );
		}
	}
	
	/*
     * Performs a GET/POST/PUT request to the Velocity API, parses the returned response
	 * and then returns it.
	 *
	 * @param string $method the method of request(Like GET, POST etc).
	 * @param string $path the relative url of request at gateway. 
	 * @param array $data the array holds xml data , session token and gateway request method name.
	 * @return array array($error, $response) gateway response for both success or failure.		 
	 */
	private function request($method, $path, $data = array()) {

		if (isset($data['sessiontoken']) && isset($path)) {
			$body = $data['xml'];	
			$session_token = $data['sessiontoken'];
			$rest_action = $method;
			if ( Velocity_Processor::$isTestAccount ) {
				$api_url = Velocity_Config::$baseurl_test . $path;
			} else {
				$api_url = Velocity_Config::$baseurl_live . $path;
			}
			$timeout = 60;
		} else {
			throw new Exception($data['method'].':'.Velocity_Message::$descriptions['errsessionxmlnotset']);
		}
		
		$user_agent = "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)";
		
		// Parse the full api_url for required pieces. 
		$strpos = strpos($api_url, '/', 8); // 8 denotes look after https://
		$host = mb_substr($api_url, 8, $strpos-8);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $api_url); // set url to post to
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); // return variable
		curl_setopt($ch, CURLOPT_TIMEOUT, $timeout); // connection timeout
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_USERAGENT, $user_agent); 
			
		if ($rest_action == 'POST')
			curl_setopt($ch, CURLOPT_POST, true);
		elseif ($rest_action == 'GET')
			curl_setopt($ch, CURLOPT_HTTPGET, true);
		elseif ($rest_action == 'PUT')
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
		elseif ($rest_action == 'DELETE')
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
		
		// Header setup		
		$header[] = 'Authorization: Basic '. base64_encode($session_token.':');
		
		if($data['method'] == 'SignOn')
			$header[] = 'Content-Type: application/json';
		else
			$header[] = 'Content-Type: application/xml';
			
		$header[] = 'Accept: '; // Known issue: defining this causes server to reply with no content.
		$header[] = 'Expect: 100-continue';
		$header[] = 'Host: '.$host;
		
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		
		//The following 3 will retrieve the header with the response. Remove if you do not want the response to contain the header.
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		//curl_setopt($ch, CURLOPT_VERBOSE, 1); // Will output network information to the Console
		curl_setopt($ch, CURLOPT_HEADER, 1);
		
		if ($rest_action != 'GET')
			curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
			
		if ($rest_action == 'DELETE')
			$expected_response = "204";	
		elseif (($rest_action == 'POST') and (strpos($api_url, 'transactionsSummary') == true))	
			$expected_response = "200";	
		elseif (($rest_action == 'POST') and (strpos($api_url, 'transactionsFamily') == true))	
			$expected_response = "200";
		elseif (($rest_action == 'POST') and (strpos($api_url, 'transactionsDetail') == true))	
			$expected_response = "200";					
		elseif ($rest_action == 'POST')
			$expected_response = "201";
		else
			$expected_response = "200";
		
		$res = curl_exec($ch);

		list($header, $body) = explode("\r\n\r\n", $res, 2);			
		$statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		
		/* if ( $data['method'] == 'SignOn' && $body != '' )
			return $body; */
		
		if ( $statusCode == 5000 ) {  // regenrate the sessiontoken if expired.
		
			$data['sessiontoken'] = $this->signOn();
			$this->request($method, $path, $data);
			
		} else {
			$error = self::errorFromStatus($statusCode); // call exception classes according to error code.
		}
		$match = null;
		preg_match('/Content-Type: ([^;]*);/i', $body, $match);
		$contentType;
		if (isset($match[1])) {
		   $contentType = $match[1]; 
		} else {
		   preg_match('/Content-Type: ([^;]*);/i', $header, $match);
		   $contentType = $match[1];
		}
		
		// Parse response, depending on value of the Content-Type header.
		$response = null;
		if (preg_match('/json/', $contentType)) {
			$response = json_decode($body, true); 
		} elseif (preg_match('/xml/', $contentType)) {
		    $arr = explode('Path=/', $body);
			if(isset($arr[1]))
			   $response = Velocity_XmlParser::parse($arr[1]);
			else
			   $response = Velocity_XmlParser::parse($body);
		}

		return array($error, $response);
	 
	}
	
	/*
     * Returns an error object, corresponding to the HTTP status code returned by Velocity.
	 * @param int $status error code.
	 * @return object (null/Exception child class object) this is error detail of gateway response. 
	 */
	 
	private static function errorFromStatus($status) {
	switch ($status) {
			case '200': 
				return null;
			case '201': 
				return null;
			case '207': 
				return new Velocity_CWSFault();
			case '208': 
				return new Velocity_CWSInvalidOperationFault();
			case '225': 
				return new Velocity_CWSValidationResultFault();
			case '306': 
				return new Velocity_CWSInvalidMessageFormatFault();
			case '312': 
				return new Velocity_CWSDeserializationFault();
			case '313': 
				return new Velocity_CWSExtendedDataNotSupportedFault();
			case '314': 
				return new Velocity_CWSInvalidServiceConfigFault();
			case '317': 
				return new Velocity_CWSOperationNotSupportedFault();
			case '318': 
				return new Velocity_CWSTransactionFailedFault();
			case '327': 
				return new Velocity_CWSTransactionAlreadySettledFault();
			case '328': 
				return new Velocity_CWSConnectionFault();	
			case '400':
				return new Velocity_BadRequestError();
			case '401':
				return new Velocity_SystemFault();
			case '406':
				return new Velocity_AuthenticationFault();
			case '412':
				return new Velocity_STSUnavailableFault();
			case '413':
				return new Velocity_AuthorizationFault();
			case '415':
				return new Velocity_ClaimNotFoundFault();
			case '416':
				return new Velocity_AccessClaimNotFoundFault();
			case '420':
				return new Velocity_DuplicateClaimFault();
			case '421':
				return new Velocity_DuplicateUserFault();
			case '422':
				return new Velocity_ClaimTypeNotAllowedFault();
			case '423':
				return new Velocity_ClaimSecurityDomainMismatchFault();
			case '424':
				return new Velocity_ClaimPropertyValidationFault();
			case '450':
				return new Velocity_RelyingPartyNotAssociatedToSecurityDomainFault();
			case '404':
				return new Velocity_NotFoundError();	
			case '500': 
				return new Velocity_InternalServerError();
			case '503': 
				return new Velocity_ServiceUnavailableError();
			case '504': 
				return new Velocity_GatewayTimeoutError();
            case '5005': 
				return new Velocity_InvalidTokenFault();
			case '9999': 
				return new Velocity_CWSTransactionServiceUnavailableFault();	
			default: 
				return new Velocity_UnexpectedError('Unexpected HTTP response: ' . $status);
		}
	}
	
}
