<?php
/*
 * This class represents a Velocity Transaction.
 * It can be used to query and verify/authorize/authorizeandcapture/capture/undo/adjust/returnbyid/returnunlinked transactions.
 */

class VelocityProcessor 
{
	/* -- Properties -- */

	private $isNew;
	private $connection;
	public $sessionToken ;
	public static $Txn_method = array('verify', 'authorize', 'authorizeandcapture', 'capture', 'adjust', 'undo', 'returnbyid', 'returnunlinked', 'querytransactionsdetail', 'captureall'); // array of method name to identify method request for common process
	public static $identitytoken;
	public static $applicationprofileid;
	public static $merchantprofileid;
	public static $workflowid;
	public static $isTestAccount;
        private $transactionDetailFormat = 'CWSTransaction';
        private $includeRelated = false;

	public function __construct($applicationprofileid, $merchantprofileid, $workflowid, $isTestAccount, $identitytoken = null, $sessiontoken = null ) {
		$this->connection = VelocityConnection::instance(); // VelocityConnection class object store in private data member $connection. 
		self::$identitytoken = $identitytoken;
		self::$applicationprofileid = $applicationprofileid;
		self::$merchantprofileid = $merchantprofileid;
		self::$workflowid = $workflowid;
		self::$isTestAccount = $isTestAccount;
		if(empty($sessiontoken) && !empty($identitytoken)){
			$this->sessionToken = $this->connection->signOn();
		} else {
			$this->sessionToken = $sessiontoken; 
		}
		
	}

	/* -- Class Methods -- */
	
	/*
	* Verify the card detail and address detail of customer.
	* This Method create corresponding xml for gateway request.
	* This Method Reqest send to gateway and handle the response.
	* @param array $options this array hold "avsData, carddata"
	* @return array $this->handleResponse($error, $response) array of successfull or failure of gateway response. 
	*/
	
	public function verify($options = array()) {
		
		try { 
																
			$xml = VelocityXmlCreator::verifyXML($options);  // got Verify xml object.
			$xml->formatOutput = TRUE;
			$body = $xml->saveXML();
			//echo '<xmp>'.$body.'</xmp>'; die;
			list($error, $response) = $this->connection->post(
                                                                            $this->path(
                                                                                    self::$workflowid, 
                                                                                    self::$Txn_method[0], 
                                                                                    self::$Txn_method[0]
                                                                            ), 
                                                                            array(
                                                                                    'sessiontoken' => $this->sessionToken, 
                                                                                    'xml' => $body, 
                                                                                    'method' => self::$Txn_method[0]
                                                                            )
                                                                     );
			return $this->handleResponse($error, $response);
			//return $response;
		} catch (Exception $e) {
			throw new Exception( $e->getMessage() );
		}
	
	}
	
	/*
	 * Authorizeandcapture operation is used to authorize transactions by performing a check on cardholder's funds and reserves.  
	 * The authorization amount if sufficient funds are available.  
	 * @param array $options this array hold "amount, paymentAccountDataToken, avsData, carddata, invoice no., order no"
	 * @return array $this->handleResponse($error, $response) array of successfull or failure of gateway response. 	 
	 */
	public function authorizeAndCapture($options = array()) { 

		try {
		
			$xml = VelocityXmlCreator::authorizeandcaptureXML($options);  // got authorizeandcapture xml object. 
			$xml->formatOutput = TRUE;
			$body = $xml->saveXML();
			//echo '<xmp>'.$body.'</xmp>';
			list($error, $response) = $this->connection->post(
                                                                            $this->path(
                                                                                    self::$workflowid, 
                                                                                    null, 
                                                                                    self::$Txn_method[2]
                                                                            ), 
                                                                            array(
                                                                                    'sessiontoken' => $this->sessionToken, 
                                                                                    'xml' => $body, 
                                                                                    'method' => self::$Txn_method[2]
                                                                                    )
                                                                     );
			return $this->handleResponse($error, $response);
			//return $response;
		} catch(Exception $e) {
			throw new Exception($e->getMessage());
		}
		
	}
	
	/*
	* Authorize a payment_method for a particular amount.
	* This Method create corresponding xml for gateway request.
	* This Method Reqest send to gateway and handle the response.
	* @param array $options this array hold "amount, paymentAccountDataToken, avsData, carddata, invoice no., order no"
	* @return array $this->handleResponse($error, $response) array of successfull or failure of gateway response. 
	*/
	
	public function authorize($options = array()) {

		try {
			$xml = VelocityXmlCreator::authorizeXML($options); // got authorize xml object.
			$xml->formatOutput = TRUE;
			$body = $xml->saveXML();
			//echo '<xmp>'.$body.'</xmp>'; 
			list($error, $response) = $this->connection->post(
                                                                            $this->path(
                                                                                    self::$workflowid, 
                                                                                    null, 
                                                                                    self::$Txn_method[1]
                                                                            ), 
                                                                            array(
                                                                                    'sessiontoken' => $this->sessionToken, 
                                                                                    'xml' => $body, 
                                                                                    'method' => self::$Txn_method[1]
                                                                            )
                                                                     );
			return $this->handleResponse($error, $response);
			//return $response;
		} catch (Exception $e) {
			throw new Exception( $e->getMessage() );
		}

	}

	/*
	* Captures an authorization. Optionally specify an `$amount` to do a partial capture of the initial
	* authorization. The default is to capture the full amount of the authorization.
	* @param array $options this is hold the amount, transactionid, method name. 
	* @return array $this->handleResponse($error, $response) array of successfull or failure of gateway response.
	*/
	public function capture($options = array()) {
		
		if(isset($options['amount']) && isset($options['TransactionId'])) {
			$amount = number_format($options['amount'], 2, '.', '');
			try {
				$xml = VelocityXmlCreator::captureXML($options['TransactionId'], $amount);  // got capture xml object.  
				$xml->formatOutput = TRUE;
				$body = $xml->saveXML();
				//echo '<xmp>'.$body.'</xmp>'; die;
				list($error, $response) = $this->connection->put(
                                                                                $this->path(
                                                                                            self::$workflowid, 
                                                                                            $options['TransactionId'], 
                                                                                            self::$Txn_method[3]
                                                                                    ), 
                                                                                 array(
                                                                                        'sessiontoken' => $this->sessionToken, 
                                                                                        'xml' => $body, 
                                                                                        'method' => self::$Txn_method[3]
                                                                                      )
                                                                        );
				//return $response;
				return $this->handleResponse($error, $response);
			} catch(Exception $e) {
				throw new Exception($e->getMessage());
			}
			
		} else {
		    throw new Exception(VelocityMessage::$descriptions['errcapsesswfltransid']);
		}
	}
        
        /*
	* Captures all authorization. Optionally specify an `$amount` to do a partial capture of the initial
	* authorization. The default is to capture the full amount of the authorization.
	* @param array $options this is hold the null array. 
	* @return array $this->handleResponse($error, $response) array of successfull or failure of gateway response.
	*/
	public function captureAll($options = array()) {
		
            try {
                    $xml = VelocityXmlCreator::captureAllXML();  // got capture xml object.  
                    $xml->formatOutput = TRUE;
                    $body = $xml->saveXML();
                    //echo '<xmp>'.$body.'</xmp>'; die;
                    list($error, $response) = $this->connection->put(
                                                                    'Txn/'.self::$workflowid, 
                                                                     array(
                                                                            'sessiontoken' => $this->sessionToken, 
                                                                            'xml' => $body, 
                                                                            'method' => self::$Txn_method[9]
                                                                           )
                                                            );
                    return $response;
                    //return $this->handleResponse($error, $response);
            } catch(Exception $e) {
                    throw new Exception($e->getMessage());
            }
			
	}

	/*
	* Adjust this transaction. If the transaction has not yet been captured and settled it can be Adjust to 
	* A previously authorized amount (incremental or reversal) prior to capture and settlement. 
	* @param array $options this is hold the amount, transactionid, method name.
	* @return array $this->handleResponse($error, $response) array of successfull or failure of gateway response.
	*/
	public function adjust($options = array()) {
		
		if( isset($options['amount']) && isset($options['TransactionId']) ) {
			$amount = number_format($options['amount'], 2, '.', '');
			try {
				$xml = VelocityXmlCreator::adjustXML($options['TransactionId'], $amount);  // got adjust xml object.  
				$xml->formatOutput = TRUE;
				$body = $xml->saveXML();
				//echo '<xmp>'.$body.'</xmp>'; die;
				list($error, $response) = $this->connection->put(
                                                                                $this->path(
                                                                                        self::$workflowid, 
                                                                                        $options['TransactionId'], 
                                                                                        self::$Txn_method[4]
                                                                                ), 
                                                                                array(
                                                                                        'sessiontoken' => $this->sessionToken, 
                                                                                        'xml' => $body, 
                                                                                        'method' => self::$Txn_method[4]
                                                                                )
                                                                        );
				return $this->handleResponse($error, $response);
		        //return $response;
			} catch (Exception $e) {
				throw new Exception($e->getMessage());
			}	
			
		} else {
			throw new Exception(VelocityMessage::$descriptions['erradjustsesswfltransid']);
		}
	}
	
	/*
	 * The Undo operation is used to release cardholder funds by performing a void (Credit Card) or reversal (PIN Debit) on a previously 
	 * authorized transaction that has not been captured (flagged) for settlement.
	 * @param array $options this is hold the amount, transactionid, method name.
 	 * @return array $this->handleResponse($error, $response) array of successfull or failure of gateway response.
	 */
	public function undo($options = array()) {
		
		if ( isset($options['TransactionId']) ) {
		
			try {
				$xml = VelocityXmlCreator::undoXML($options['TransactionId']);  // got undo xml object.  
				$xml->formatOutput = TRUE;
				$body = $xml->saveXML();
				list($error, $response) = $this->connection->put( 
                                                                                $this->path(
                                                                                        self::$workflowid, 
                                                                                        $options['TransactionId'], 
                                                                                        self::$Txn_method[5]
                                                                                ), 
                                                                                array(
                                                                                        'sessiontoken' => $this->sessionToken, 
                                                                                        'xml' => $body, 
                                                                                        'method' => self::$Txn_method[5]
                                                                                ) 
                                                                        );
				//return $response;
				return $this->handleResponse($error, $response);
			} catch (Exception $e) {
				throw new Exception($e->getMessage());
			}
			
		} else {
			throw new Exception(VelocityMessage::$descriptions['errundosesswfltransid']);
		}
	}
	
	
	/*
	 * The ReturnById operation is used to perform a linked credit to a cardholder�s account from the merchant�s account based on a
	 * previously authorized and settled transaction.
	 * @param array $options this is hold the transactionid, method name.
	 * @return array $this->handleResponse($error, $response) array of successfull or failure of gateway response. 
	 */
	public function returnById($options = array()) {
		
		if(isset($options['amount']) && isset($options['TransactionId'])) {
			$amount = number_format($options['amount'], 2, '.', '');
			try {
				$xml = VelocityXmlCreator::returnByIdXML($amount, $options['TransactionId']);  // got ReturnById xml object. 
				$xml->formatOutput = TRUE;
				$body = $xml->saveXML();
				//echo '<xmp>'.$body.'</xmp>'; die;
				list($error, $response) = $this->connection->post(
                                                                                    $this->path(
                                                                                            self::$workflowid, 
                                                                                            null, 
                                                                                            self::$Txn_method[6]
                                                                                    ), 
                                                                                    array(
                                                                                            'sessiontoken' => $this->sessionToken, 
                                                                                            'xml' => $body, 
                                                                                            'method' => self::$Txn_method[6]
                                                                                    )
                                                                             );
				return $this->handleResponse($error, $response);
				//return $response;
			} catch (Exception $e) {
				throw new Exception($e->getMessage());
			}
			
		} else {
			throw new Exception(VelocityMessage::$descriptions['errreturntranidwid']);
		}  
	}

	
	/*
	 * The ReturnUnlinked operation is used to perform an "unlinked", or standalone, credit to a cardholder�s account from the merchant�s account.
	 * This operation is useful when a return transaction is not associated with a previously authorized and settled transaction.
	 * @param array $options this array hold "amount, paymentAccountDataToken, avsData, carddata, invoice no., order no"
	 * @return array $this->handleResponse($error, $response) array of successfull or failure of gateway response. 
	 */
	public function returnUnlinked($options = array()) {
		
		try {
			$xml = VelocityXmlCreator::returnUnlinkedXML($options);  // got ReturnById xml object. 
			$xml->formatOutput = TRUE;
			$body = $xml->saveXML();
			//echo '<xmp>'.$body.'</xmp>';
			list($error, $response) = $this->connection->post(
                                                                            $this->path(
                                                                                    self::$workflowid, 
                                                                                    null, 
                                                                                    self::$Txn_method[7]
                                                                            ), 
                                                                            array(
                                                                                    'sessiontoken' =>  $this->sessionToken, 
                                                                                    'xml' => $body, 
                                                                                    'method' => self::$Txn_method[7]
                                                                            )
                                                                     );
			return $this->handleResponse($error, $response);
			//return $response;
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
		  
	}

        /*
	 * The QueryTransactionsDetail operation is used to display the transaction detail on the behalf of some parameter(txtid, ammount etc).
	 * This operation is useful to show the detail of multiple transaction detail as request parameter.
	 * @param array $guerytxndetail this array hold "querytransactionparam" array and PagingParameters array.
	 * @return array $response array of successfull. 
	 */
	public function queryTransactionsDetail($guerytxndetail) {

		try {

                        $Qtxnparams = new QueryTransactionsParameters($guerytxndetail['querytransactionparam']);
                        $pagingParameters = new PagingParameters($guerytxndetail['PagingParameters']);
                        $qtd = new QueryTransactionsDetail($this->includeRelated, $pagingParameters, $Qtxnparams, $this->transactionDetailFormat);
                        $body = (string)json_encode($qtd);
			//echo '<pre>'.$body.'</pre>'; die;
			list($error, $response) = $this->connection->post('DataServices/TMS/transactionsDetail',
                                                                            array(
                                                                                    'sessiontoken' =>  $this->sessionToken, 
                                                                                    'xml' => $body, 
                                                                                    'method' => self::$Txn_method[8]
                                                                            )
                                                                     );

			return $response;
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
		  
	}
	
	/* path for according to request needed 
	 * @param string $arg1 part of url for request.
	 * @param string $arg2 part of url for request.
	 * @param string $arg3 name of method.
	 * @return array $this->handleResponse($error, $response) array of successfull or failure of gateway response.	 
	 */
	private function path($arg1, $arg2, $rtype) {
		if(isset($arg1) && isset($arg2) && isset($rtype) && ( $rtype == self::$Txn_method[3] || $rtype == self::$Txn_method[4] || $rtype == self::$Txn_method[5] || $rtype == self::$Txn_method[0] ) ) {
			$path = 'Txn/'.$arg1.'/'.$arg2;
			return $path;
		} else if(isset($arg1) && isset($rtype) && ($rtype == self::$Txn_method[2] || $rtype == self::$Txn_method[6] || $rtype == self::$Txn_method[7] || $rtype == self::$Txn_method[1]) ) {
			$path = 'Txn/'.$arg1;
			return $path;
		} else {
			throw new Exception(VelocityMessage::$descriptions['errcapadjpath']);
		}
	}
	
	
	/*
	* Parses the Velocity response for messages (info or error) and updates 
	* the current transaction's information. If an HTTP error is 
	* encountered, it will be thrown from this method.
	* @param object $error error message created on the basis of gateway error status. 
	* @param array $response gateway response deatil. 
	* @return object $error error detail of gateway response.
        * @return array $response successfull/failure response of gateway.
	*/
	public function handleResponse($error, $response) {
                        
		if ($error) {
			  return $this->processError($error, $response);
		} else {
		    if(!empty($response)) {
				if ( isset($response['BankcardTransactionResponsePro']) ) {
					return $response['BankcardTransactionResponsePro'];
				} else if ( isset($response['BankcardCaptureResponse']) ) {
					return $response['BankcardCaptureResponse'];
				} else {
					return $this->processError($error, $response);
				}
                    }
		}
	}
	
       /*
	* Parses the Velocity response for error messages
	* @param object $error error message created on the basis of gateway error status. 
	* @param array $response gateway error response detail. 
	* @return object $error detail created on the basis of gateway error status.
	*/
	private function processError($error, $response) {
		if ( isset($response) ) {
                        $valerr = '';
			$reson = isset($response['ErrorResponse']['Reason']) ? $response['ErrorResponse']['Reason'] : NULL;
                        $valError = is_array($response['ErrorResponse']['ValidationErrors']) ? $response['ErrorResponse']['ValidationErrors'] : array();
                        $valerr .= $reson . '<br>';
                        foreach ($valError as $key => $value) {
                            $valerr .= $value['RuleMessage'] . '<br>';
                        }
                        return $valerr;
                } else
			return $error;
		
	}
}