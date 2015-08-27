<?php

class VelocityXmlCreator {

	public static function populate_XML_element_if_array_value_isset($array_key, $element_name, $xml, $parent_element, $array){	
		if (isset($array[$array_key]) && $array[$array_key] != '') { 
			$n = $xml->createElement($element_name);
			$idText = $xml->createTextNode($array[$array_key]);
			$n->appendChild($idText);
			$parent_element->appendChild($n);
		}
	}
	
	public static function populate_XML_element_with_amount_if_array_value_isset($array_key, $element_name, $xml, $parent_element, $array){	
		if (isset($array[$array_key]) && $array[$array_key] != '') { 
			$array[$array_key] = number_format($array[$array_key], 2, '.', '');
			
			$n = $xml->createElement($element_name);
			$idText = $xml->createTextNode($array[$array_key]);
			$n->appendChild($idText);
			$parent_element->appendChild($n);
		}
	}

	/* 
	 * create verify xml as per the api format .
	 * @param array $data this array hold "avsData, carddata"
	 * @return string $xml xml format in string.
	 */
	public static function verifyXML($data) {
		$xml = new DOMDocument("1.0");

		$root = $xml->createElement("AuthorizeTransaction");

		$xml->appendChild($root);

		$root->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:i', 'http://www.w3.org/2001/XMLSchema-instance');
		$root->setAttribute('xmlns', 'http://schemas.ipcommerce.com/CWS/v2.0/Transactions/Rest');
		$root->setAttributeNS('http://www.w3.org/2001/XMLSchema-instance', 'i:type', 'AuthorizeTransaction');

		if (empty($data['amount'])) {
			$data['amount'] = '0.00';
		}
		
		return VelocityXmlCreator::transaction_XML($xml, $root, $data);
	}	
		
	public static function transaction_XML($xml, $root, $data) {
            
		if (empty($data['amount'])) {
			throw new Exception(VelocityMessage::$descriptions['erramtnotset']);	
		}else{
			$data['amount'] = number_format($data['amount'], 2, '.', '');
		}
		
		if (empty($data['token']) && empty($data['carddata']) && empty($data['p2pedata']) ) {
			throw new Exception(VelocityMessage::$descriptions['errcarddatatokennotset']);	
		}
	
		$n = $xml->createElement("ApplicationProfileId");
		$idText = $xml->createTextNode(VelocityProcessor::$applicationprofileid);
		$n->appendChild($idText);
		$root->appendChild($n);

		$n = $xml->createElement("MerchantProfileId");
		$idText = $xml->createTextNode(VelocityProcessor::$merchantprofileid);
		$n->appendChild($idText);
		$root->appendChild($n);

		$n = $xml->createElement("Transaction");
		$n->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:bcp', 'http://schemas.ipcommerce.com/CWS/v2.0/Transactions/Bankcard');
		$n->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:txn', 'http://schemas.ipcommerce.com/CWS/v2.0/Transactions');
		$n->setAttributeNS('http://www.w3.org/2001/XMLSchema-instance', 'i:type', 'bcp:BankcardTransaction');
		
		$n1 = $xml->createElement("temp");

		if( isset($data['billingdata'])	) {	
			$n2 = $xml->createElement("ns2:BillingData");
			$n1->appendChild($n2);

			VelocityXmlCreator::populate_XML_element_if_array_value_isset('name', "ns2:Name", $xml, $n1, $data['billingdata']);
			if(isset($data['billingdata']['address'])) { 
				$n3 = $xml->createElement("ns2:Address");
				$n2->appendChild($n3);
				VelocityXmlCreator::populate_XML_element_if_array_value_isset('street', "ns2:Street1", $xml, $n3, $data['billingdata']['address']);
				VelocityXmlCreator::populate_XML_element_if_array_value_isset('Street2', "ns2:Street2", $xml, $n3, $data['billingdata']['address']);
				VelocityXmlCreator::populate_XML_element_if_array_value_isset('City', "ns2:City", $xml, $n3, $data['billingdata']['address']);
				VelocityXmlCreator::populate_XML_element_if_array_value_isset('StateProvince', "ns2:StateProvince", $xml, $n3, $data['billingdata']['address']);
				VelocityXmlCreator::populate_XML_element_if_array_value_isset('PostalCode', "ns2:PostalCode", $xml, $n3, $data['billingdata']['address']);
				VelocityXmlCreator::populate_XML_element_if_array_value_isset('Country', "ns2:CountryCode", $xml, $n3, $data['billingdata']['address']);
			}
			VelocityXmlCreator::populate_XML_element_if_array_value_isset('business_name', "ns2:BusinessName", $xml, $n1, $data['billingdata']);
			VelocityXmlCreator::populate_XML_element_if_array_value_isset('phone', "ns2:Phone", $xml, $n1, $data['billingdata']);
			VelocityXmlCreator::populate_XML_element_if_array_value_isset('fax', "ns2:Fax", $xml, $n1, $data['billingdata']);
			VelocityXmlCreator::populate_XML_element_if_array_value_isset('email', "ns2:Email", $xml, $n1, $data['billingdata']);
			VelocityXmlCreator::populate_XML_element_if_array_value_isset('customer_id', "ns2:CustomerId", $xml, $n1, $data);
			VelocityXmlCreator::populate_XML_element_if_array_value_isset('customer_tax_id', "ns2:CustomerTaxId", $xml, $n1, $data);
		}
		

		if ( isset($data['reportingdata']) ){
			$n1 = $xml->createElement("ns2:ReportingData");
			$n->appendChild($n1);

			VelocityXmlCreator::populate_XML_element_if_array_value_isset('comment', "ns2:Comment", $xml, $n1, $data['reportingdata']);
			VelocityXmlCreator::populate_XML_element_if_array_value_isset('description', "ns2:Description", $xml, $n1, $data['reportingdata']);
			VelocityXmlCreator::populate_XML_element_if_array_value_isset('reference', "ns2:Reference", $xml, $n1, $data['reportingdata']);
		}
	
		$n1 = $xml->createElement("bcp:TenderData");
		$n->appendChild($n1);

		VelocityXmlCreator::populate_XML_element_if_array_value_isset('token', "txn:PaymentAccountDataToken", $xml, $n1, $data);
                
                if ( isset($data['p2pedata'])) {
                    VelocityXmlCreator::populate_XML_element_if_array_value_isset('SecurePaymentAccountData', "txn:SecurePaymentAccountData", $xml, $n1, $data['p2pedata']);
                    VelocityXmlCreator::populate_XML_element_if_array_value_isset('EncryptionKeyId', "txn:EncryptionKeyId", $xml, $n1, $data['p2pedata']);
                }
                
		if ( isset($data['carddata']) ) {
			$n2 = $xml->createElement("bcp:CardData");
			$n1->appendChild($n2);
			VelocityXmlCreator::populate_XML_element_if_array_value_isset('cardtype', "bcp:CardType", $xml, $n2, $data['carddata']);
                        if( $data['carddata']['track1data'] != '' || $data['carddata']['track2data'] != '') {
                            VelocityXmlCreator::populate_XML_element_if_array_value_isset('track1data', "bcp:Track1Data", $xml, $n2, $data['carddata']);
                            VelocityXmlCreator::populate_XML_element_if_array_value_isset('track2data', "bcp:Track2Data", $xml, $n2, $data['carddata']);
                        } else {
                            VelocityXmlCreator::populate_XML_element_if_array_value_isset('pan', "bcp:PAN", $xml, $n2, $data['carddata']);
                            VelocityXmlCreator::populate_XML_element_if_array_value_isset('expire', "bcp:Expire", $xml, $n2, $data['carddata']);
                        }    
		}

		if (isset($data['avsdata']) || (isset($data['carddata']) && isset($data['cvdata']['cvv']))){
			$n2 = $xml->createElement("bcp:CardSecurityData");
			$n1->appendChild($n2);

			if(isset($data['avsdata'])) { // check avsdata for authorize method.  			
				$avsdataclass = $data['avsdata'];
				$avsdataarray = array();
				
				foreach($avsdataclass as $key => $value) {
					$avsdataarray[$key] = $value; 
				}
				
				$data['avsdata'] = $avsdataarray;
				
				$n3 = $xml->createElement("bcp:AVSData");
				$n2->appendChild($n3);
				
				VelocityXmlCreator::populate_XML_element_if_array_value_isset('Street', "bcp:Street", $xml, $n3, $data['avsdata']);			
				VelocityXmlCreator::populate_XML_element_if_array_value_isset('City', "bcp:City", $xml, $n3, $data['avsdata']);
				VelocityXmlCreator::populate_XML_element_if_array_value_isset('StateProvince', "bcp:StateProvince", $xml, $n3, $data['avsdata']);
				VelocityXmlCreator::populate_XML_element_if_array_value_isset('PostalCode', "bcp:PostalCode", $xml, $n3, $data['avsdata']);
				VelocityXmlCreator::populate_XML_element_if_array_value_isset('Country', "bcp:CountryCode", $xml, $n3, $data['avsdata']);	
			} 
			
			if (isset($data['carddata']) && isset($data['carddata']['cvv'])) {
                            
                            if( $data['carddata']['track1data'] == '' && $data['carddata']['track2data'] == '') {
				$n3 = $xml->createElement("bcp:CVDataProvided");
				$idText = $xml->createTextNode('Provided');
				$n3->appendChild($idText);
				$n2->appendChild($n3);
				
				VelocityXmlCreator::populate_XML_element_if_array_value_isset('cvv', "bcp:CVData", $xml, $n2, $data['carddata']);
                            }    
			}
		}

		$n1 = $xml->createElement("bcp:TransactionData");
		$n->appendChild($n1);

		VelocityXmlCreator::populate_XML_element_with_amount_if_array_value_isset('amount', "txn:Amount", $xml, $n1, $data);
		VelocityXmlCreator::populate_XML_element_if_array_value_isset('currency_code', "txn:CurrencyCode", $xml, $n1, $data);
		VelocityXmlCreator::populate_XML_element_if_array_value_isset('transaction_datetime', "txn:TransactionDateTime", $xml, $n1, $data);
		VelocityXmlCreator::populate_XML_element_if_array_value_isset('campaign_id', "txn:CampaignId", $xml, $n1, $data);
		VelocityXmlCreator::populate_XML_element_if_array_value_isset('Reference', "txn:Reference", $xml, $n1, $data);
		VelocityXmlCreator::populate_XML_element_if_array_value_isset('approval_code', "bcp:ApprovalCode", $xml, $n1, $data);
		VelocityXmlCreator::populate_XML_element_with_amount_if_array_value_isset('cash_back_amount', "bcp:CashBackAmount", $xml, $n1, $data);
		VelocityXmlCreator::populate_XML_element_if_array_value_isset('customer_present', "bcp:CustomerPresent", $xml, $n1, $data);
		VelocityXmlCreator::populate_XML_element_if_array_value_isset('EmployeeId', "bcp:EmployeeId", $xml, $n1, $data);
		VelocityXmlCreator::populate_XML_element_if_array_value_isset('entry_mode', "bcp:EntryMode", $xml, $n1, $data);
		VelocityXmlCreator::populate_XML_element_if_array_value_isset('goods_type', "bcp:GoodsType", $xml, $n1, $data);
		VelocityXmlCreator::populate_XML_element_if_array_value_isset('IndustryType', "bcp:IndustryType", $xml, $n1, $data);
		
		VelocityXmlCreator::populate_XML_element_if_array_value_isset('invoice_no', "bcp:InvoiceNumber", $xml, $n1, $data);
		VelocityXmlCreator::populate_XML_element_if_array_value_isset('order_id', "bcp:OrderNumber", $xml, $n1, $data);
		VelocityXmlCreator::populate_XML_element_if_array_value_isset('is_partial_shipment', "bcp:IsPartialShipment", $xml, $n1, $data);
		VelocityXmlCreator::populate_XML_element_if_array_value_isset('signature_captured', "bcp:SignatureCaptured", $xml, $n1, $data);
		VelocityXmlCreator::populate_XML_element_with_amount_if_array_value_isset('fee_amount', "bcp:FeeAmount", $xml, $n1, $data);
		VelocityXmlCreator::populate_XML_element_if_array_value_isset('terminal_id', "bcp:TerminalId", $xml, $n1, $data);
		VelocityXmlCreator::populate_XML_element_if_array_value_isset('lane_id', "bcp:LaneId", $xml, $n1, $data);
		VelocityXmlCreator::populate_XML_element_with_amount_if_array_value_isset('tip_amount', "bcp:TipAmount", $xml, $n1, $data);
		VelocityXmlCreator::populate_XML_element_if_array_value_isset('batch_assignment', "bcp:BatchAssignment", $xml, $n1, $data);
		VelocityXmlCreator::populate_XML_element_if_array_value_isset('partial_approval_capable', "bcp:PartialApprovalCapable", $xml, $n1, $data);
		VelocityXmlCreator::populate_XML_element_if_array_value_isset('score_threshold', "bcp:ScoreThreshold", $xml, $n1, $data);
		VelocityXmlCreator::populate_XML_element_if_array_value_isset('is_quasi_cash', "bcp:IsQuasiCash", $xml, $n1, $data);

		$root->appendChild($n);
		
		return $xml;
	}
		
	/* 
	 * create authorize xml as per the api format .
	 * @param array $data this array hold "amount, paymentAccountDataToken, avsData, carddata, invoice no., order no"
	 * @return string $xml xml format in string.
	 */
	public static function authorizeXML($data) {
		$xml = new DOMDocument("1.0");

		$root = $xml->createElement("AuthorizeTransaction");

		$xml->appendChild($root);

		$root->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:i', 'http://www.w3.org/2001/XMLSchema-instance');
		$root->setAttribute('xmlns', 'http://schemas.ipcommerce.com/CWS/v2.0/Transactions/Rest');
		$root->setAttributeNS('http://www.w3.org/2001/XMLSchema-instance', 'i:type', 'AuthorizeTransaction');

		return VelocityXmlCreator::transaction_XML($xml, $root, $data);
    }
	
	/* 
         * create capture xml as per the api format.
	 * @param string $TransactionId this is get from response of authorize.
	 * @param float $amount amount for capture	
	 * @return string $xml xml format in string.
	 */
	public static function captureXML($TransactionId, $amount){
	    
		if (isset($TransactionId) && isset($amount)) {
			$xml = new DOMDocument("1.0", "UTF-8");

			$root = $xml->createElement("ChangeTransaction");

			$xml->appendChild($root);
			$root->setAttribute('xmlns', 'http://schemas.ipcommerce.com/CWS/v2.0/Transactions/Rest');
			$root->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:i', 'http://www.w3.org/2001/XMLSchema-instance');
			$root->setAttributeNS('http://www.w3.org/2001/XMLSchema-instance', 'i:type', 'Capture');
			
			$n = $xml->createElement("ApplicationProfileId");
			$idText = $xml->createTextNode(VelocityProcessor::$applicationprofileid);
			$n->appendChild($idText);
			$root->appendChild($n);
			
			$n = $xml->createElement("DifferenceData");
			$n->setAttribute('xmlns:d2p1', 'http://schemas.ipcommerce.com/CWS/v2.0/Transactions');
			$n->setAttribute('xmlns:d2p2', 'http://schemas.ipcommerce.com/CWS/v2.0/Transactions/Bankcard');
			$n->setAttribute('xmlns:d2p3', 'http://schemas.ipcommerce.com/CWS/v2.0/TransactionProcessing');
			$n->setAttributeNS('http://www.w3.org/2001/XMLSchema-instance', 'i:type', 'd2p2:BankcardCapture');
			$root->appendChild($n);
			
			$n1 = $xml->createElement("d2p1:TransactionId");
			$idText = $xml->createTextNode($TransactionId);
			$n1->appendChild($idText);
			$n->appendChild($n1);
			
			$n1 = $xml->createElement("d2p2:Amount");
			$idText = $xml->createTextNode($amount);
			$n1->appendChild($idText);
			$n->appendChild($n1);
			
			$n1 = $xml->createElement("d2p2:TipAmount");
			$idText = $xml->createTextNode(0.0);
			$n1->appendChild($idText);
			$n->appendChild($n1);
			
			return $xml;
		} else {
			throw new Exception(VelocityMessage::$descriptions['errcaptransidamount']);
		}
	}
        
        
        /* 
         * create captureAll xml as per the api format.	
	 * @return string $xml xml format in string.
	 */
	public static function captureAllXML(){
	    
		try {
			$xml = new DOMDocument("1.0", "UTF-8");

			$root = $xml->createElement("ChangeTransaction");

			$xml->appendChild($root);
			$root->setAttribute('xmlns', 'http://schemas.ipcommerce.com/CWS/v2.0/Transactions/Rest');
			$root->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:i', 'http://www.w3.org/2001/XMLSchema-instance');
			$root->setAttributeNS('http://www.w3.org/2001/XMLSchema-instance', 'i:type', 'CaptureAll');
			
			$n = $xml->createElement("ApplicationProfileId");
			$idText = $xml->createTextNode(VelocityProcessor::$applicationprofileid);
			$n->appendChild($idText);
			$root->appendChild($n);
                        
                        $n = $xml->createElement("BatchIds");
			$n->setAttribute('xmlns:d2p1', 'http://schemas.microsoft.com/2003/10/Serialization/Arrays');
			$n->setAttributeNS('http://www.w3.org/2001/XMLSchema-instance', 'i:nil', 'true');
			$root->appendChild($n);
			
			$n = $xml->createElement("DifferenceData");
			$n->setAttribute('xmlns:d2p1', 'http://schemas.ipcommerce.com/CWS/v2.0/Transactions');
                        $n->setAttributeNS('http://www.w3.org/2001/XMLSchema-instance', 'i:nil', 'true');
			$root->appendChild($n);
                        
                        $n = $xml->createElement("MerchantProfileId");
			$idText = $xml->createTextNode(VelocityProcessor::$merchantprofileid);
			$n->appendChild($idText);
			$root->appendChild($n);
			
			return $xml;
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}
	
	/* 
         * create Adjust xml as per the api format.
	 * @param string $TransactionId this is get from response of authorize.
	 * @param float $amount amount for adjustment after capture.
	 * @return string $xml xml format in string.
	 */
	public static function adjustXML($TransactionId, $amount){
	    
		if (isset($TransactionId) && isset($amount)) {
			$xml = new DOMDocument("1.0");

			$root = $xml->createElement("Adjust");

			$xml->appendChild($root);
			$root->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:i', 'http://www.w3.org/2001/XMLSchema-instance');
			$root->setAttribute('xmlns', 'http://schemas.ipcommerce.com/CWS/v2.0/Transactions/Rest');
			$root->setAttributeNS('http://www.w3.org/2001/XMLSchema-instance', 'i:type', 'Adjust');
			
			$n = $xml->createElement("ApplicationProfileId");
			$idText = $xml->createTextNode(VelocityProcessor::$applicationprofileid);
			$n->appendChild($idText);
			$root->appendChild($n);
			
			$n = $xml->createElement("BatchIds");
			$n->setAttribute('xmlns:d2p1', 'http://schemas.microsoft.com/2003/10/Serialization/Arrays');
			$n->setAttributeNS('http://www.w3.org/2001/XMLSchema-instance', 'i:nil', 'true');
			$root->appendChild($n);
			
			$n = $xml->createElement("MerchantProfileId");
			$idText = $xml->createTextNode(VelocityProcessor::$merchantprofileid);
			$n->appendChild($idText);
			$root->appendChild($n);
			
			$n = $xml->createElement("DifferenceData");
			$n->setAttribute('xmlns:ns1', 'http://schemas.ipcommerce.com/CWS/v2.0/Transactions');
			$root->appendChild($n);
		
			
			$n1 = $xml->createElement("ns2:Amount");
			$n1->setAttribute('xmlns:ns2', 'http://schemas.ipcommerce.com/CWS/v2.0/Transactions');
			$idText = $xml->createTextNode($amount);
			$n1->appendChild($idText);
			$n->appendChild($n1);
			
			$n1 = $xml->createElement("ns3:TransactionId");
			$n1->setAttribute('xmlns:ns3', 'http://schemas.ipcommerce.com/CWS/v2.0/Transactions');
			$idText = $xml->createTextNode($TransactionId);
			$n1->appendChild($idText);
			$n->appendChild($n1);
			
			
			return $xml;
		} else {
			throw new Exception(VelocityMessage::$descriptions['erradjtransidamount']);
		}
	}
	
	/* 
	 * create authorizeandcapture xml as per the api format .
	 * @param array $data this array hold "amount, paymentAccountDataToken, avsData, carddata, invoice no., order no"
	 * @return string $xml xml format in string.
	 */
	public static function authorizeandcaptureXML($data) {
		$xml = new DOMDocument("1.0");

		$root = $xml->createElement("AuthorizeAndCaptureTransaction");

		$xml->appendChild($root);

		$root->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:i', 'http://www.w3.org/2001/XMLSchema-instance');
		$root->setAttribute('xmlns', 'http://schemas.ipcommerce.com/CWS/v2.0/Transactions/Rest');
		$root->setAttributeNS('http://www.w3.org/2001/XMLSchema-instance', 'i:type', 'AuthorizeAndCaptureTransaction');

		return VelocityXmlCreator::transaction_XML($xml, $root, $data);
        }
	
	/* 
         * create Undo xml as per the api format.
	 * @param string $TransactionId this is get from response of authorize.
	 * @return string $xml xml format in string.
	 */
	public static function undoXML($TransactionId) {
	    
		if (isset($TransactionId)) {
			$xml = new DOMDocument("1.0");

			$root = $xml->createElement("Undo");

			$xml->appendChild($root);
			$root->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:i', 'http://www.w3.org/2001/XMLSchema-instance');
			$root->setAttribute('xmlns', 'http://schemas.ipcommerce.com/CWS/v2.0/Transactions/Rest');
			$root->setAttributeNS('http://www.w3.org/2001/XMLSchema-instance', 'i:type', 'Undo');
			
			$n = $xml->createElement("ApplicationProfileId");
			$idText = $xml->createTextNode(VelocityProcessor::$applicationprofileid);
			$n->appendChild($idText);
			$root->appendChild($n);
			
			$n = $xml->createElement("BatchIds");
			$n->setAttribute('xmlns:d2p1', 'http://schemas.microsoft.com/2003/10/Serialization/Arrays');
			$n->setAttributeNS('http://www.w3.org/2001/XMLSchema-instance', 'i:nil', 'true');
			$root->appendChild($n);
			
			$n = $xml->createElement("DifferenceData");
			$n->setAttribute('xmlns:d2p1', 'http://schemas.ipcommerce.com/CWS/v2.0/Transactions');
			$n->setAttributeNS('http://www.w3.org/2001/XMLSchema-instance', 'i:nil', 'true');
			$root->appendChild($n);
		
			$n = $xml->createElement("MerchantProfileId");
			$idText = $xml->createTextNode(VelocityProcessor::$merchantprofileid);
			$n->appendChild($idText);
			$root->appendChild($n);
			
			$n = $xml->createElement("TransactionId");
			$idText = $xml->createTextNode($TransactionId);
			$n->appendChild($idText);
			$root->appendChild($n);
			
			return $xml;
		} else {
			throw new Exception(VelocityMessage::$descriptions['errundotransid']);
		}
	}
 
	/* 
         * create ReturnById xml as per the api format.
	 * @param string $TransactionId this is get from response of authorize.
	 * @param float $amount amount for return.	
	 * @return string $xml xml format in string.
	 */
	public static function returnByIdXML($amount, $TransactionId){
	    
		if (isset($TransactionId) && isset($amount)) {
			$xml = new DOMDocument("1.0");

			$root = $xml->createElement("ReturnById");

			$xml->appendChild($root);
			$root->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:i', 'http://www.w3.org/2001/XMLSchema-instance');
			$root->setAttribute('xmlns', 'http://schemas.ipcommerce.com/CWS/v2.0/Transactions/Rest');
			$root->setAttributeNS('http://www.w3.org/2001/XMLSchema-instance', 'i:type', 'ReturnById');
			
			$n = $xml->createElement("ApplicationProfileId");
			$idText = $xml->createTextNode(VelocityProcessor::$applicationprofileid);
			$n->appendChild($idText);
			$root->appendChild($n);
			
			$n = $xml->createElement("BatchIds");
			$n->setAttribute('xmlns:d2p1', 'http://schemas.microsoft.com/2003/10/Serialization/Arrays');
			$n->setAttributeNS('http://www.w3.org/2001/XMLSchema-instance', 'i:nil', 'true');
			$root->appendChild($n);
			
			$n = $xml->createElement("DifferenceData");
			$n->setAttribute('xmlns:ns1', 'http://schemas.ipcommerce.com/CWS/v2.0/Transactions/Bankcard');
			$n->setAttributeNS('http://www.w3.org/2001/XMLSchema-instance', 'i:type', 'ns1:BankcardReturn');
			$root->appendChild($n);
					
			$n1 = $xml->createElement("ns2:TransactionId");
			$n1->setAttribute('xmlns:ns2', 'http://schemas.ipcommerce.com/CWS/v2.0/Transactions');
			$idText = $xml->createTextNode($TransactionId);
			$n1->appendChild($idText);
			$n->appendChild($n1);
			
			$n1 = $xml->createElement("ns1:Amount");
			$idText = $xml->createTextNode($amount);
			$n1->appendChild($idText);
			$n->appendChild($n1);

			$n = $xml->createElement("MerchantProfileId");
			$idText = $xml->createTextNode(VelocityProcessor::$merchantprofileid);
			$n->appendChild($idText);
			$root->appendChild($n);
			
			return $xml;
		} else {
			throw new Exception(VelocityMessage::$descriptions['errreturndataarray']);
		}
	}
	
	/* 
         * create ReturnUnlinked xml as per the api format.
	 * @param array $data this array hold "amount, paymentAccountDataToken, avsData, carddata, invoice no., order no"
	 * @return string $xml xml format in string.
	 */
	public static function returnUnlinkedXML($data){
		$xml = new DOMDocument("1.0");

		$root = $xml->createElement("ReturnTransaction");

		$xml->appendChild($root);

		$root->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:i', 'http://www.w3.org/2001/XMLSchema-instance');
		$root->setAttribute('xmlns', 'http://schemas.ipcommerce.com/CWS/v2.0/Transactions/Rest');
		$root->setAttributeNS('http://www.w3.org/2001/XMLSchema-instance', 'i:type', 'ReturnTransaction');

		return VelocityXmlCreator::transaction_XML($xml, $root, $data);
	}
}