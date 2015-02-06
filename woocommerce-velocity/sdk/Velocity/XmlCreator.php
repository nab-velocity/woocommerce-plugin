<?php

class Velocity_XmlCreator {

	/* 
	 * create verify xml as per the api format .
	 * @param array $data this array hold "avsData, carddata"
	 * @return string $xml xml format in string.
	 */
	public static function verify_XML($data) {

	    if ( isset($data['carddata']) && isset($data['avsdata']) ) {
		
			$xml = new DOMDocument("1.0");

			$root = $xml->createElement("AuthorizeTransaction");

			$xml->appendChild($root);

			$root->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:i', 'http://www.w3.org/2001/XMLSchema-instance');
			$root->setAttribute('xmlns', 'http://schemas.ipcommerce.com/CWS/v2.0/Transactions/Rest');
			$root->setAttributeNS('http://www.w3.org/2001/XMLSchema-instance', 'i:type', 'AuthorizeTransaction');

			$n = $xml->createElement("ApplicationProfileId");
			$idText = $xml->createTextNode(Velocity_Processor::$applicationprofileid);
			$n->appendChild($idText);
			$root->appendChild($n);

			$n = $xml->createElement("MerchantProfileId");
			$idText = $xml->createTextNode(Velocity_Processor::$merchantprofileid);
			$n->appendChild($idText);
			$root->appendChild($n);

			$n = $xml->createElement("Transaction");
			$n->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:ns1', 'http://schemas.ipcommerce.com/CWS/v2.0/Transactions/Bankcard');
			$n->setAttributeNS('http://www.w3.org/2001/XMLSchema-instance', 'i:type', 'ns1:BankcardTransaction');
			$root->appendChild($n);
			
			$n1 = $xml->createElement("ns1:TenderData");
			$n->appendChild($n1);
			
			$n2 = $xml->createElement("ns1:CardData");
			$n1->appendChild($n2);
			
			$n3 = $xml->createElement("ns1:CardType");
			$idText = $xml->createTextNode($data['carddata']['cardtype']);
			$n3->appendChild($idText);
			$n2->appendChild($n3);
			
			$n3 = $xml->createElement("ns1:CardholderName");
			$idText = $xml->createTextNode($data['carddata']['cardowner']);
			$n3->appendChild($idText);
			$n2->appendChild($n3);

			$n3 = $xml->createElement("ns1:PAN");
			$idText = $xml->createTextNode($data['carddata']['pan']);
			$n3->appendChild($idText);
			$n2->appendChild($n3);

			$n3 = $xml->createElement("ns1:Expire");
			$idText = $xml->createTextNode($data['carddata']['expire']);
			$n3->appendChild($idText);
			$n2->appendChild($n3);
			
			if (isset($data['carddata']['track1data']) && $data['carddata']['track1data'] != '') { // check track1data for authorize method.
				
				$n3 = $xml->createElement("ns1:Track1Data");
				$idText = $xml->createTextNode($data['carddata']['track1data']);
				$n3->appendChild($idText);
				$n2->appendChild($n3);
				
			} else if (isset($data['carddata']['track2data']) && $data['carddata']['track2data'] != '') { // check track2data for authorize method.
			
				$n3 = $xml->createElement("ns1:Track2Data");
				$idText = $xml->createTextNode($data['carddata']['track2data']);
				$n3->appendChild($idText);
				$n2->appendChild($n3);
				
			} else {
			
				$n3 = $xml->createElement("ns1:Track1Data");
				$n3->setAttributeNS('http://www.w3.org/2001/XMLSchema-instance', 'i:nil', 'true');
				$n2->appendChild($n3);
				
				$n3 = $xml->createElement("ns1:Track2Data");
				$n3->setAttributeNS('http://www.w3.org/2001/XMLSchema-instance', 'i:nil', 'true');
				$n2->appendChild($n3);
				
			}
			
			$n2 = $xml->createElement("ns1:CardSecurityData");
			$n1->appendChild($n2);
			
			$n3 = $xml->createElement("ns1:AVSData");
			$n2->appendChild($n3);
			
			$n4 = $xml->createElement("ns1:Street");
			$idText = $xml->createTextNode($data['avsdata']['Street']);
			$n4->appendChild($idText);
			$n3->appendChild($n4);

			$n4 = $xml->createElement("ns1:City");
			$idText = $xml->createTextNode($data['avsdata']['City']);
			$n4->appendChild($idText);
			$n3->appendChild($n4);

			$n4 = $xml->createElement("ns1:StateProvince");
			$idText = $xml->createTextNode($data['avsdata']['StateProvince']);
			$n4->appendChild($idText);
			$n3->appendChild($n4);

			$n4 = $xml->createElement("ns1:PostalCode");
			$idText = $xml->createTextNode($data['avsdata']['PostalCode']);
			$n4->appendChild($idText);
			$n3->appendChild($n4);

			$n4 = $xml->createElement("ns1:CountryCode");
			$idText = $xml->createTextNode($data['avsdata']['Country']);
			$n4->appendChild($idText);
			$n3->appendChild($n4);
			
			$n4 = $xml->createElement("ns1:Phone");
			$n4->setAttributeNS('http://www.w3.org/2001/XMLSchema-instance', 'i:nil', 'true');
			$n3->appendChild($n4);
			
			$n4 = $xml->createElement("ns1:Email");
			$n4->setAttributeNS('http://www.w3.org/2001/XMLSchema-instance', 'i:nil', 'true');
			$n3->appendChild($n4);
			
			$n3 = $xml->createElement("ns1:CVDataProvided");
			$idText = $xml->createTextNode('Provided');
			$n3->appendChild($idText);
			$n2->appendChild($n3);
			
			$n3 = $xml->createElement("ns1:CVData");
			$idText = $xml->createTextNode($data['carddata']['cvv']);
			$n3->appendChild($idText);
			$n2->appendChild($n3);
			
			$n3 = $xml->createElement("ns1:KeySerialNumber");
			$n3->setAttributeNS('http://www.w3.org/2001/XMLSchema-instance', 'i:nil', 'true');
			$n2->appendChild($n3);
			
			$n3 = $xml->createElement("ns1:PIN");
			$n3->setAttributeNS('http://www.w3.org/2001/XMLSchema-instance', 'i:nil', 'true');
			$n2->appendChild($n3);
			
			$n3 = $xml->createElement("ns1:IdentificationInformation");
			$n3->setAttributeNS('http://www.w3.org/2001/XMLSchema-instance', 'i:nil', 'true');
			$n2->appendChild($n3);
			
			$n2 = $xml->createElement("ns1:EcommerceSecurityData");
			$n3->setAttributeNS('http://www.w3.org/2001/XMLSchema-instance', 'i:nil', 'true');
			$n1->appendChild($n2);
			
			$n1 = $xml->createElement("ns1:TransactionData");
			$n->appendChild($n1);
			
			$n2 = $xml->createElement("ns1:AccountType");
			$idText = $xml->createTextNode('NotSet');
			$n2->appendChild($idText);
			$n1->appendChild($n2);
			
			$n2 = $xml->createElement("ns1:EntryMode");
			$idText = $xml->createTextNode('Keyed');
			$n2->appendChild($idText);
			$n1->appendChild($n2);
			
			$n2 = $xml->createElement("ns1:IndustryType");
			$idText = $xml->createTextNode('Ecommerce');
			$n2->appendChild($idText);
			$n1->appendChild($n2);

			return $xml;
		} else {
			throw new Exception(Velocity_Message::$descriptions['errauthxml']);
		}	
	}	
			
	/* 
	 * create authorize xml as per the api format .
	 * @param array $data this array hold "amount, paymentAccountDataToken, avsData, carddata, invoice no., order no"
	 * @return string $xml xml format in string.
	 */
	public static function auth_XML($data) {
	  
	    if (isset($data['amount']) && isset($data['token']) && isset($data['invoice_no']) && isset($data['order_id'])) {
		
			$xml = new DOMDocument("1.0");

			$root = $xml->createElement("AuthorizeTransaction");

			$xml->appendChild($root);

			$root->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:i', 'http://www.w3.org/2001/XMLSchema-instance');
			$root->setAttribute('xmlns', 'http://schemas.ipcommerce.com/CWS/v2.0/Transactions/Rest');
			$root->setAttributeNS('http://www.w3.org/2001/XMLSchema-instance', 'i:type', 'AuthorizeTransaction');

			$n = $xml->createElement("ApplicationProfileId");
			$idText = $xml->createTextNode(Velocity_Processor::$applicationprofileid);
			$n->appendChild($idText);
			$root->appendChild($n);

			$n = $xml->createElement("MerchantProfileId");
			$idText = $xml->createTextNode(Velocity_Processor::$merchantprofileid);
			$n->appendChild($idText);
			$root->appendChild($n);

			$n = $xml->createElement("Transaction");
			$n->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:ns1', 'http://schemas.ipcommerce.com/CWS/v2.0/Transactions/Bankcard');
			$n->setAttributeNS('http://www.w3.org/2001/XMLSchema-instance', 'i:type', 'ns1:BankcardTransaction');

			$n1 = $xml->createElement("ns2:CustomerData");
			$n1->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:ns2', 'http://schemas.ipcommerce.com/CWS/v2.0/Transactions');
			$n->appendChild($n1);

			$n2 = $xml->createElement("ns2:BillingData");
			$n1->appendChild($n2);

			$n3 = $xml->createElement("ns2:Name");
			$n3->setAttributeNS('http://www.w3.org/2001/XMLSchema-instance', 'i:nil', 'true');
			$n2->appendChild($n3);

			if(isset($data['avsdata']['Street']) && isset($data['avsdata']['City']) && isset($data['avsdata']['StateProvince']) && isset($data['avsdata']['PostalCode']) && isset($data['avsdata']['Country'])) { // check avsdata for authorize method.  
			
				$n3 = $xml->createElement("ns2:Address");
				$n2->appendChild($n3);

				$n4 = $xml->createElement("ns2:Street1");
				$idText = $xml->createTextNode($data['avsdata']['Street']);
				$n4->appendChild($idText);
				$n3->appendChild($n4);

				$n4 = $xml->createElement("ns2:Street2");
				$n4->setAttributeNS('http://www.w3.org/2001/XMLSchema-instance', 'i:nil', 'true');
				$n3->appendChild($n4);

				$n4 = $xml->createElement("ns2:City");
				$idText = $xml->createTextNode($data['avsdata']['City']);
				$n4->appendChild($idText);
				$n3->appendChild($n4);

				$n4 = $xml->createElement("ns2:StateProvince");
				$idText = $xml->createTextNode($data['avsdata']['StateProvince']);
				$n4->appendChild($idText);
				$n3->appendChild($n4);

				$n4 = $xml->createElement("ns2:PostalCode");
				$idText = $xml->createTextNode($data['avsdata']['PostalCode']);
				$n4->appendChild($idText);
				$n3->appendChild($n4);

				$n4 = $xml->createElement("ns2:CountryCode");
				$idText = $xml->createTextNode($data['avsdata']['Country']);
				$n4->appendChild($idText);
				$n3->appendChild($n4);
			
			} else { 
			
				$n3 = $xml->createElement("ns2:Address");
				$n3->setAttributeNS('http://www.w3.org/2001/XMLSchema-instance', 'i:nil', 'true');
				$n2->appendChild($n3);
				
			}

			$n3 = $xml->createElement("ns2:BusinessName");
			$idText = $xml->createTextNode('MomCorp');
			$n3->appendChild($idText);
			$n2->appendChild($n3);

			$n3 = $xml->createElement("ns2:Phone");
			$n3->setAttributeNS('http://www.w3.org/2001/XMLSchema-instance', 'i:nil', 'true');
			$n2->appendChild($n3);

			$n3 = $xml->createElement("ns2:Fax");
			$n3->setAttributeNS('http://www.w3.org/2001/XMLSchema-instance', 'i:nil', 'true');
			$n2->appendChild($n3);

			$n3 = $xml->createElement("ns2:Email");
			$n3->setAttributeNS('http://www.w3.org/2001/XMLSchema-instance', 'i:nil', 'true');
			$n2->appendChild($n3);

			$n2 = $xml->createElement("ns2:CustomerId");
			$idText = $xml->createTextNode('cust123x');
			$n2->appendChild($idText);
			$n1->appendChild($n2);

			$n2 = $xml->createElement("ns2:CustomerTaxId");
			$n2->setAttributeNS('http://www.w3.org/2001/XMLSchema-instance', 'i:nil', 'true');
			$n1->appendChild($n2);

			$n2 = $xml->createElement("ns2:ShippingData");
			$n2->setAttributeNS('http://www.w3.org/2001/XMLSchema-instance', 'i:nil', 'true');
			$n1->appendChild($n2);

			$n1 = $xml->createElement("ns3:ReportingData");
			$n1->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:ns3', 'http://schemas.ipcommerce.com/CWS/v2.0/Transactions');
			$n->appendChild($n1);

			$n2 = $xml->createElement("ns3:Comment");
			$idText = $xml->createTextNode('a test comment');
			$n2->appendChild($idText);
			$n1->appendChild($n2);

			$n2 = $xml->createElement("ns3:Description");
			$idText = $xml->createTextNode('a test description');
			$n2->appendChild($idText);
			$n1->appendChild($n2);

			$n2 = $xml->createElement("ns3:Reference");
			$idText = $xml->createTextNode('001');
			$n2->appendChild($idText);
			$n1->appendChild($n2);
		
			$n1 = $xml->createElement("ns1:TenderData");
			$n->appendChild($n1);

			if ($data['token'] != '') { //check paymentaccountdatatoken for authorize method.
				$n2 = $xml->createElement("ns4:PaymentAccountDataToken");
				$n2->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:ns4', 'http://schemas.ipcommerce.com/CWS/v2.0/Transactions');
				$idText = $xml->createTextNode($data['token']);
				$n2->appendChild($idText);
				$n1->appendChild($n2);
			} else {
				$n2 = $xml->createElement("ns4:PaymentAccountDataToken");
				$n2->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:ns4', 'http://schemas.ipcommerce.com/CWS/v2.0/Transactions');
				$n2->setAttributeNS('http://www.w3.org/2001/XMLSchema-instance', 'i:nil', 'true');
				$n1->appendChild($n2);
			}

			$n2 = $xml->createElement("ns5:SecurePaymentAccountData");
			$n2->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:ns5', 'http://schemas.ipcommerce.com/CWS/v2.0/Transactions');
			$n2->setAttributeNS('http://www.w3.org/2001/XMLSchema-instance', 'i:nil', 'true');
			$n1->appendChild($n2);

			$n2 = $xml->createElement("ns6:EncryptionKeyId");
			$n2->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:ns6', 'http://schemas.ipcommerce.com/CWS/v2.0/Transactions');
			$n2->setAttributeNS('http://www.w3.org/2001/XMLSchema-instance', 'i:nil', 'true');
			$n1->appendChild($n2);

			$n2 = $xml->createElement("ns7:SwipeStatus");
			$n2->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:ns7', 'http://schemas.ipcommerce.com/CWS/v2.0/Transactions');
			$n2->setAttributeNS('http://www.w3.org/2001/XMLSchema-instance', 'i:nil', 'true');
			$n1->appendChild($n2);
			
			/* check card data for authorize method */
			if ( $data['carddata']['cardtype'] != '' && $data['carddata']['pan'] != '' && $data['carddata']['expire'] != '' && $data['carddata']['cvv'] != '' ) {
			
				$n2 = $xml->createElement("ns1:CardData");
				$n1->appendChild($n2);

				$n3 = $xml->createElement("ns1:CardType");
				$idText = $xml->createTextNode($data['carddata']['cardtype']);
				$n3->appendChild($idText);
				$n2->appendChild($n3);


				$n3 = $xml->createElement("ns1:PAN");
				$idText = $xml->createTextNode($data['carddata']['pan']);
				$n3->appendChild($idText);
				$n2->appendChild($n3);

				$n3 = $xml->createElement("ns1:Expire");
				$idText = $xml->createTextNode($data['carddata']['expire']);
				$n3->appendChild($idText);
				$n2->appendChild($n3);
				
				$n3 = $xml->createElement("ns1:CVData");
				$idText = $xml->createTextNode($data['carddata']['cvv']);
				$n3->appendChild($idText);
				$n2->appendChild($n3);

				if (isset($data['carddata']['track1data']) && $data['carddata']['track1data'] != '') { // check track1data for authorize method.
				
					$n3 = $xml->createElement("ns1:Track1Data");
					$idText = $xml->createTextNode($data['carddata']['track1data']);
					$n3->appendChild($idText);
					$n2->appendChild($n3);
					
				} else if (isset($data['carddata']['track2data']) && $data['carddata']['track2data'] != '') { // check track2data for authorize method.
				
					$n3 = $xml->createElement("ns1:Track2Data");
					$idText = $xml->createTextNode($data['carddata']['track2data']);
					$n3->appendChild($idText);
					$n2->appendChild($n3);
					
				} else {
				
					$n3 = $xml->createElement("ns1:Track1Data");
					$n3->setAttributeNS('http://www.w3.org/2001/XMLSchema-instance', 'i:nil', 'true');
					$n2->appendChild($n3);
					
					$n3 = $xml->createElement("ns1:Track2Data");
					$n3->setAttributeNS('http://www.w3.org/2001/XMLSchema-instance', 'i:nil', 'true');
					$n2->appendChild($n3);
					
				}
			
			}
			/* end card data */

			$n2 = $xml->createElement("ns1:EcommerceSecurityData");
			$n2->setAttributeNS('http://www.w3.org/2001/XMLSchema-instance', 'i:nil', 'true');
			$n1->appendChild($n2);

			$n1 = $xml->createElement("ns1:TransactionData");
			$n->appendChild($n1);

			$n2 = $xml->createElement("ns8:Amount");
			$n2->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:ns8', 'http://schemas.ipcommerce.com/CWS/v2.0/Transactions');
			$idText = $xml->createTextNode($data['amount']);
			$n2->appendChild($idText);
			$n1->appendChild($n2);

			$n2 = $xml->createElement("ns9:CurrencyCode");
			$n2->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:ns9', 'http://schemas.ipcommerce.com/CWS/v2.0/Transactions');
			$idText = $xml->createTextNode('USD');
			$n2->appendChild($idText);
			$n1->appendChild($n2);

			$n2 = $xml->createElement("ns10:TransactionDateTime");
			$n2->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:ns10', 'http://schemas.ipcommerce.com/CWS/v2.0/Transactions');
			$idText = $xml->createTextNode('2013-04-03T13:50:16');
			$n2->appendChild($idText);
			$n1->appendChild($n2);

			$n2 = $xml->createElement("ns11:CampaignId");
			$n2->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:ns11', 'http://schemas.ipcommerce.com/CWS/v2.0/Transactions');
			$n2->setAttributeNS('http://www.w3.org/2001/XMLSchema-instance', 'i:nil', 'true');
			$n1->appendChild($n2);

			$n2 = $xml->createElement("ns12:Reference");
			$n2->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:ns12', 'http://schemas.ipcommerce.com/CWS/v2.0/Transactions');
			$idText = $xml->createTextNode('xyt');
			$n2->appendChild($idText);
			$n1->appendChild($n2);

			$n2 = $xml->createElement("ns1:ApprovalCode");
			$n2->setAttributeNS('http://www.w3.org/2001/XMLSchema-instance', 'i:nil', 'true');
			$n1->appendChild($n2);

			$n2 = $xml->createElement("ns1:CashBackAmount");
			$idText = $xml->createTextNode('0.0');
			$n2->appendChild($idText);
			$n1->appendChild($n2);

			$n2 = $xml->createElement("ns1:CustomerPresent");
			$idText = $xml->createTextNode('Present');
			$n2->appendChild($idText);
			$n1->appendChild($n2);

			$n2 = $xml->createElement("ns1:EmployeeId");
			$idText = $xml->createTextNode('11');
			$n2->appendChild($idText);
			$n1->appendChild($n2);

			$n2 = $xml->createElement("ns1:EntryMode");
			$idText = $xml->createTextNode('Keyed');
			$n2->appendChild($idText);
			$n1->appendChild($n2);

			$n2 = $xml->createElement("ns1:GoodsType");
			$idText = $xml->createTextNode('NotSet');
			$n2->appendChild($idText);
			$n1->appendChild($n2);

			$n2 = $xml->createElement("ns1:IndustryType");
			$idText = $xml->createTextNode('Ecommerce');
			$n2->appendChild($idText);
			$n1->appendChild($n2);

			$n2 = $xml->createElement("ns1:InternetTransactionData");
			$n2->setAttributeNS('http://www.w3.org/2001/XMLSchema-instance', 'i:nil', 'true');
			$n1->appendChild($n2);

			$n2 = $xml->createElement("ns1:InvoiceNumber");
			$idText = $xml->createTextNode($data['invoice_no']);
			$n2->appendChild($idText);
			$n1->appendChild($n2);

			$n2 = $xml->createElement("ns1:OrderNumber");
			$idText = $xml->createTextNode($data['order_id']);
			$n2->appendChild($idText);
			$n1->appendChild($n2);

			$n2 = $xml->createElement("ns1:IsPartialShipment");
			$idText = $xml->createTextNode('false');
			$n2->appendChild($idText);
			$n1->appendChild($n2);

			$n2 = $xml->createElement("ns1:SignatureCaptured");
			$idText = $xml->createTextNode('false');
			$n2->appendChild($idText);
			$n1->appendChild($n2);

			$n2 = $xml->createElement("ns1:FeeAmount");
			$idText = $xml->createTextNode('0.0');
			$n2->appendChild($idText);
			$n1->appendChild($n2);

			$n2 = $xml->createElement("ns1:TerminalId");
			$n2->setAttributeNS('http://www.w3.org/2001/XMLSchema-instance', 'i:nil', 'true');
			$n1->appendChild($n2);

			$n2 = $xml->createElement("ns1:LaneId");
			$n2->setAttributeNS('http://www.w3.org/2001/XMLSchema-instance', 'i:nil', 'true');
			$n1->appendChild($n2);

			$n2 = $xml->createElement("ns1:TipAmount");
			$idText = $xml->createTextNode('0.0');
			$n2->appendChild($idText);
			$n1->appendChild($n2);

			$n2 = $xml->createElement("ns1:BatchAssignment");
			$n2->setAttributeNS('http://www.w3.org/2001/XMLSchema-instance', 'i:nil', 'true');
			$n1->appendChild($n2);

			$n2 = $xml->createElement("ns1:PartialApprovalCapable");
			$idText = $xml->createTextNode('NotSet');
			$n2->appendChild($idText);
			$n1->appendChild($n2);

			$n2 = $xml->createElement("ns1:ScoreThreshold");
			$n2->setAttributeNS('http://www.w3.org/2001/XMLSchema-instance', 'i:nil', 'true');
			$n1->appendChild($n2);

			$n2 = $xml->createElement("ns1:IsQuasiCash");
			$idText = $xml->createTextNode('false');
			$n2->appendChild($idText);
			$n1->appendChild($n2);

			$root->appendChild($n);
			
			return $xml;
		} else {
			throw new Exception(Velocity_Message::$descriptions['errauthxml']);
		}
    }
	
	/* 
     * create capture xml as per the api format.
	 * @param string $TransactionId this is get from response of authorize.
	 * @param float $amount amount for capture	
	 * @return string $xml xml format in string.
	 */
	public static function cap_XML($TransactionId, $amount){
	    
		if (isset($TransactionId) && isset($amount)) {
			$xml = new DOMDocument("1.0", "UTF-8");

			$root = $xml->createElement("ChangeTransaction");

			$xml->appendChild($root);
			$root->setAttribute('xmlns', 'http://schemas.ipcommerce.com/CWS/v2.0/Transactions/Rest');
			$root->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:i', 'http://www.w3.org/2001/XMLSchema-instance');
			$root->setAttributeNS('http://www.w3.org/2001/XMLSchema-instance', 'i:type', 'Capture');
			
			$n = $xml->createElement("ApplicationProfileId");
			$idText = $xml->createTextNode(Velocity_Processor::$applicationprofileid);
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
			throw new Exception(Velocity_Message::$descriptions['errcaptransidamount']);
		}
	}
	
	/* 
     * create Adjust xml as per the api format.
	 * @param string $TransactionId this is get from response of authorize.
	 * @param float $amount amount for adjustment after capture.
	 * @return string $xml xml format in string.
	 */
	public static function adjust_XML($TransactionId, $amount){
	    
		if (isset($TransactionId) && isset($amount)) {
			$xml = new DOMDocument("1.0");

			$root = $xml->createElement("Adjust");

			$xml->appendChild($root);
			$root->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:i', 'http://www.w3.org/2001/XMLSchema-instance');
			$root->setAttribute('xmlns', 'http://schemas.ipcommerce.com/CWS/v2.0/Transactions/Rest');
			$root->setAttributeNS('http://www.w3.org/2001/XMLSchema-instance', 'i:type', 'Adjust');
			
			$n = $xml->createElement("ApplicationProfileId");
			$idText = $xml->createTextNode(Velocity_Processor::$applicationprofileid);
			$n->appendChild($idText);
			$root->appendChild($n);
			
			$n = $xml->createElement("BatchIds");
			$n->setAttribute('xmlns:d2p1', 'http://schemas.microsoft.com/2003/10/Serialization/Arrays');
			$n->setAttributeNS('http://www.w3.org/2001/XMLSchema-instance', 'i:nil', 'true');
			$root->appendChild($n);
			
			$n = $xml->createElement("MerchantProfileId");
			$idText = $xml->createTextNode(Velocity_Processor::$merchantprofileid);
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
			throw new Exception(Velocity_Message::$descriptions['erradjtransidamount']);
		}
	}
	
	/* 
	 * create authorizeandcapture xml as per the api format .
	 * @param array $data this array hold "amount, paymentAccountDataToken, avsData, carddata, invoice no., order no"
	 * @return string $xml xml format in string.
	 */
	public static function authandcap_XML($data) {
	
	    if (isset($data['amount']) && isset($data['token']) && isset($data['invoice_no']) && isset($data['order_id'])) {
		
			$xml = new DOMDocument("1.0");

			$root = $xml->createElement("AuthorizeAndCaptureTransaction");

			$xml->appendChild($root);

			$root->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:i', 'http://www.w3.org/2001/XMLSchema-instance');
			$root->setAttribute('xmlns', 'http://schemas.ipcommerce.com/CWS/v2.0/Transactions/Rest');
			$root->setAttributeNS('http://www.w3.org/2001/XMLSchema-instance', 'i:type', 'AuthorizeAndCaptureTransaction');

			$n = $xml->createElement("ApplicationProfileId");
			$idText = $xml->createTextNode(Velocity_Processor::$applicationprofileid);
			$n->appendChild($idText);
			$root->appendChild($n);

			$n = $xml->createElement("MerchantProfileId");
			$idText = $xml->createTextNode(Velocity_Processor::$merchantprofileid);
			$n->appendChild($idText);
			$root->appendChild($n);

			$n = $xml->createElement("Transaction");
			$n->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:ns1', 'http://schemas.ipcommerce.com/CWS/v2.0/Transactions/Bankcard');
			$n->setAttributeNS('http://www.w3.org/2001/XMLSchema-instance', 'i:type', 'ns1:BankcardTransaction');

			$n1 = $xml->createElement("ns2:CustomerData");
			$n1->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:ns2', 'http://schemas.ipcommerce.com/CWS/v2.0/Transactions');
			$n->appendChild($n1);

			$n2 = $xml->createElement("ns2:BillingData");
			$n1->appendChild($n2);

			$n3 = $xml->createElement("ns2:Name");
			$n3->setAttributeNS('http://www.w3.org/2001/XMLSchema-instance', 'i:nil', 'true');
			$n2->appendChild($n3);

			if(isset($data['avsdata']['Street']) && isset($data['avsdata']['City']) && isset($data['avsdata']['StateProvince']) && isset($data['avsdata']['PostalCode']) && isset($data['avsdata']['Country'])) { // check avsdata for authandcap method.
			
				$n3 = $xml->createElement("ns2:Address");
				$n2->appendChild($n3);

				$n4 = $xml->createElement("ns2:Street1");
				$idText = $xml->createTextNode($data['avsdata']['Street']);
				$n4->appendChild($idText);
				$n3->appendChild($n4);

				$n4 = $xml->createElement("ns2:Street2");
				$n4->setAttributeNS('http://www.w3.org/2001/XMLSchema-instance', 'i:nil', 'true');
				$n3->appendChild($n4);

				$n4 = $xml->createElement("ns2:City");
				$idText = $xml->createTextNode($data['avsdata']['City']);
				$n4->appendChild($idText);
				$n3->appendChild($n4);

				$n4 = $xml->createElement("ns2:StateProvince");
				$idText = $xml->createTextNode($data['avsdata']['StateProvince']);
				$n4->appendChild($idText);
				$n3->appendChild($n4);

				$n4 = $xml->createElement("ns2:PostalCode");
				$idText = $xml->createTextNode($data['avsdata']['PostalCode']);
				$n4->appendChild($idText);
				$n3->appendChild($n4);

				$n4 = $xml->createElement("ns2:CountryCode");
				$idText = $xml->createTextNode($data['avsdata']['Country']);
				$n4->appendChild($idText);
				$n3->appendChild($n4);
			
			} else {
			
				$n3 = $xml->createElement("ns2:Address");
				$n3->setAttributeNS('http://www.w3.org/2001/XMLSchema-instance', 'i:nil', 'true');
				$n2->appendChild($n3);
				
			}

			$n3 = $xml->createElement("ns2:BusinessName");
			$idText = $xml->createTextNode('MomCorp');
			$n3->appendChild($idText);
			$n2->appendChild($n3);

			$n3 = $xml->createElement("ns2:Phone");
			$n3->setAttributeNS('http://www.w3.org/2001/XMLSchema-instance', 'i:nil', 'true');
			$n2->appendChild($n3);

			$n3 = $xml->createElement("ns2:Fax");
			$n3->setAttributeNS('http://www.w3.org/2001/XMLSchema-instance', 'i:nil', 'true');
			$n2->appendChild($n3);

			$n3 = $xml->createElement("ns2:Email");
			$n3->setAttributeNS('http://www.w3.org/2001/XMLSchema-instance', 'i:nil', 'true');
			$n2->appendChild($n3);

			$n2 = $xml->createElement("ns2:CustomerId");
			$idText = $xml->createTextNode('cust123x');
			$n2->appendChild($idText);
			$n1->appendChild($n2);

			$n2 = $xml->createElement("ns2:CustomerTaxId");
			$n2->setAttributeNS('http://www.w3.org/2001/XMLSchema-instance', 'i:nil', 'true');
			$n1->appendChild($n2);

			$n2 = $xml->createElement("ns2:ShippingData");
			$n2->setAttributeNS('http://www.w3.org/2001/XMLSchema-instance', 'i:nil', 'true');
			$n1->appendChild($n2);

			$n1 = $xml->createElement("ns3:ReportingData");
			$n1->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:ns3', 'http://schemas.ipcommerce.com/CWS/v2.0/Transactions');
			$n->appendChild($n1);

			$n2 = $xml->createElement("ns3:Comment");
			$idText = $xml->createTextNode('a test comment');
			$n2->appendChild($idText);
			$n1->appendChild($n2);

			$n2 = $xml->createElement("ns3:Description");
			$idText = $xml->createTextNode('a test description');
			$n2->appendChild($idText);
			$n1->appendChild($n2);

			$n2 = $xml->createElement("ns3:Reference");
			$idText = $xml->createTextNode('001');
			$n2->appendChild($idText);
			$n1->appendChild($n2);
		
			$n1 = $xml->createElement("ns1:TenderData");
			$n->appendChild($n1);

			if ($data['token'] != '') { // check PaymentAccountDataToken for authandcap method.
				$n2 = $xml->createElement("ns4:PaymentAccountDataToken");
				$n2->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:ns4', 'http://schemas.ipcommerce.com/CWS/v2.0/Transactions');
				$idText = $xml->createTextNode($data['token']);
				$n2->appendChild($idText);
				$n1->appendChild($n2);
			} else {
				$n2 = $xml->createElement("ns4:PaymentAccountDataToken");
				$n2->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:ns4', 'http://schemas.ipcommerce.com/CWS/v2.0/Transactions');
				$n2->setAttributeNS('http://www.w3.org/2001/XMLSchema-instance', 'i:nil', 'true');
				$n1->appendChild($n2);
			}

			$n2 = $xml->createElement("ns5:SecurePaymentAccountData");
			$n2->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:ns5', 'http://schemas.ipcommerce.com/CWS/v2.0/Transactions');
			$n2->setAttributeNS('http://www.w3.org/2001/XMLSchema-instance', 'i:nil', 'true');
			$n1->appendChild($n2);

			$n2 = $xml->createElement("ns6:EncryptionKeyId");
			$n2->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:ns6', 'http://schemas.ipcommerce.com/CWS/v2.0/Transactions');
			$n2->setAttributeNS('http://www.w3.org/2001/XMLSchema-instance', 'i:nil', 'true');
			$n1->appendChild($n2);

			$n2 = $xml->createElement("ns7:SwipeStatus");
			$n2->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:ns7', 'http://schemas.ipcommerce.com/CWS/v2.0/Transactions');
			$n2->setAttributeNS('http://www.w3.org/2001/XMLSchema-instance', 'i:nil', 'true');
			$n1->appendChild($n2);
			
			/*  check card data for authandcap method. */
			if ( $data['carddata']['cardtype'] != '' && $data['carddata']['pan'] != '' && $data['carddata']['expire'] != '' && $data['carddata']['cvv'] != '' ) {
			
				$n2 = $xml->createElement("ns1:CardData");
				$n1->appendChild($n2);

				$n3 = $xml->createElement("ns1:CardType");
				$idText = $xml->createTextNode($data['carddata']['cardtype']);
				$n3->appendChild($idText);
				$n2->appendChild($n3);


				$n3 = $xml->createElement("ns1:PAN");
				$idText = $xml->createTextNode($data['carddata']['pan']);
				$n3->appendChild($idText);
				$n2->appendChild($n3);

				$n3 = $xml->createElement("ns1:Expire");
				$idText = $xml->createTextNode($data['carddata']['expire']);
				$n3->appendChild($idText);
				$n2->appendChild($n3);
				
				$n3 = $xml->createElement("ns1:CVData");
				$idText = $xml->createTextNode($data['carddata']['cvv']);
				$n3->appendChild($idText);
				$n2->appendChild($n3);

				if (isset($data['carddata']['track1data']) && $data['carddata']['track1data'] != '') { // check track1data for authandcap method.
				
					$n3 = $xml->createElement("ns1:Track1Data");
					$idText = $xml->createTextNode($data['carddata']['track1data']);
					$n3->appendChild($idText);
					$n2->appendChild($n3);
					
				} else if (isset($data['carddata']['track2data']) && $data['carddata']['track2data'] != '') { // check track1data for authandcap method.
				
					$n3 = $xml->createElement("ns1:Track2Data");
					$idText = $xml->createTextNode($data['carddata']['track2data']);
					$n3->appendChild($idText);
					$n2->appendChild($n3);
					
				} else {
				
					$n3 = $xml->createElement("ns1:Track1Data");
					$n3->setAttributeNS('http://www.w3.org/2001/XMLSchema-instance', 'i:nil', 'true');
					$n2->appendChild($n3);
					
					$n3 = $xml->createElement("ns1:Track2Data");
					$n3->setAttributeNS('http://www.w3.org/2001/XMLSchema-instance', 'i:nil', 'true');
					$n2->appendChild($n3);
					
				}
			
			}
			/* end card data */

			$n2 = $xml->createElement("ns1:EcommerceSecurityData");
			$n2->setAttributeNS('http://www.w3.org/2001/XMLSchema-instance', 'i:nil', 'true');
			$n1->appendChild($n2);

			$n1 = $xml->createElement("ns1:TransactionData");
			$n->appendChild($n1);

			$n2 = $xml->createElement("ns8:Amount");
			$n2->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:ns8', 'http://schemas.ipcommerce.com/CWS/v2.0/Transactions');
			$idText = $xml->createTextNode($data['amount']);
			$n2->appendChild($idText);
			$n1->appendChild($n2);

			$n2 = $xml->createElement("ns9:CurrencyCode");
			$n2->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:ns9', 'http://schemas.ipcommerce.com/CWS/v2.0/Transactions');
			$idText = $xml->createTextNode('USD');
			$n2->appendChild($idText);
			$n1->appendChild($n2);

			$n2 = $xml->createElement("ns10:TransactionDateTime");
			$n2->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:ns10', 'http://schemas.ipcommerce.com/CWS/v2.0/Transactions');
			$idText = $xml->createTextNode('2013-04-03T13:50:16');
			$n2->appendChild($idText);
			$n1->appendChild($n2);

			$n2 = $xml->createElement("ns11:CampaignId");
			$n2->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:ns11', 'http://schemas.ipcommerce.com/CWS/v2.0/Transactions');
			$n2->setAttributeNS('http://www.w3.org/2001/XMLSchema-instance', 'i:nil', 'true');
			$n1->appendChild($n2);

			$n2 = $xml->createElement("ns12:Reference");
			$n2->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:ns12', 'http://schemas.ipcommerce.com/CWS/v2.0/Transactions');
			$idText = $xml->createTextNode('xyt');
			$n2->appendChild($idText);
			$n1->appendChild($n2);

			$n2 = $xml->createElement("ns1:ApprovalCode");
			$n2->setAttributeNS('http://www.w3.org/2001/XMLSchema-instance', 'i:nil', 'true');
			$n1->appendChild($n2);

			$n2 = $xml->createElement("ns1:CashBackAmount");
			$idText = $xml->createTextNode('0.0');
			$n2->appendChild($idText);
			$n1->appendChild($n2);

			$n2 = $xml->createElement("ns1:CustomerPresent");
			$idText = $xml->createTextNode('Present');
			$n2->appendChild($idText);
			$n1->appendChild($n2);

			$n2 = $xml->createElement("ns1:EmployeeId");
			$idText = $xml->createTextNode('11');
			$n2->appendChild($idText);
			$n1->appendChild($n2);

			$n2 = $xml->createElement("ns1:EntryMode");
			$idText = $xml->createTextNode('Keyed');
			$n2->appendChild($idText);
			$n1->appendChild($n2);

			$n2 = $xml->createElement("ns1:GoodsType");
			$idText = $xml->createTextNode('NotSet');
			$n2->appendChild($idText);
			$n1->appendChild($n2);

			$n2 = $xml->createElement("ns1:IndustryType");
			$idText = $xml->createTextNode('Ecommerce');
			$n2->appendChild($idText);
			$n1->appendChild($n2);

			$n2 = $xml->createElement("ns1:InternetTransactionData");
			$n2->setAttributeNS('http://www.w3.org/2001/XMLSchema-instance', 'i:nil', 'true');
			$n1->appendChild($n2);

			$n2 = $xml->createElement("ns1:InvoiceNumber");
			$idText = $xml->createTextNode($data['invoice_no']);
			$n2->appendChild($idText);
			$n1->appendChild($n2);

			$n2 = $xml->createElement("ns1:OrderNumber");
			$idText = $xml->createTextNode($data['order_id']);
			$n2->appendChild($idText);
			$n1->appendChild($n2);

			$n2 = $xml->createElement("ns1:IsPartialShipment");
			$idText = $xml->createTextNode('false');
			$n2->appendChild($idText);
			$n1->appendChild($n2);

			$n2 = $xml->createElement("ns1:SignatureCaptured");
			$idText = $xml->createTextNode('false');
			$n2->appendChild($idText);
			$n1->appendChild($n2);

			$n2 = $xml->createElement("ns1:FeeAmount");
			$idText = $xml->createTextNode('0.0');
			$n2->appendChild($idText);
			$n1->appendChild($n2);

			$n2 = $xml->createElement("ns1:TerminalId");
			$n2->setAttributeNS('http://www.w3.org/2001/XMLSchema-instance', 'i:nil', 'true');
			$n1->appendChild($n2);

			$n2 = $xml->createElement("ns1:LaneId");
			$n2->setAttributeNS('http://www.w3.org/2001/XMLSchema-instance', 'i:nil', 'true');
			$n1->appendChild($n2);

			$n2 = $xml->createElement("ns1:TipAmount");
			$idText = $xml->createTextNode('0.0');
			$n2->appendChild($idText);
			$n1->appendChild($n2);

			$n2 = $xml->createElement("ns1:BatchAssignment");
			$n2->setAttributeNS('http://www.w3.org/2001/XMLSchema-instance', 'i:nil', 'true');
			$n1->appendChild($n2);

			$n2 = $xml->createElement("ns1:PartialApprovalCapable");
			$idText = $xml->createTextNode('NotSet');
			$n2->appendChild($idText);
			$n1->appendChild($n2);

			$n2 = $xml->createElement("ns1:ScoreThreshold");
			$n2->setAttributeNS('http://www.w3.org/2001/XMLSchema-instance', 'i:nil', 'true');
			$n1->appendChild($n2);

			$n2 = $xml->createElement("ns1:IsQuasiCash");
			$idText = $xml->createTextNode('false');
			$n2->appendChild($idText);
			$n1->appendChild($n2);

			$root->appendChild($n);
			
			return $xml;
		} else {
			throw new Exception(Velocity_Message::$descriptions['errauthncapdataarray']);
		}
    }
	
	/* 
     * create Undo xml as per the api format.
	 * @param string $TransactionId this is get from response of authorize.
	 * @return string $xml xml format in string.
	 */
	public static function undo_XML($TransactionId) {
	    
		if (isset($TransactionId)) {
			$xml = new DOMDocument("1.0");

			$root = $xml->createElement("Undo");

			$xml->appendChild($root);
			$root->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:i', 'http://www.w3.org/2001/XMLSchema-instance');
			$root->setAttribute('xmlns', 'http://schemas.ipcommerce.com/CWS/v2.0/Transactions/Rest');
			$root->setAttributeNS('http://www.w3.org/2001/XMLSchema-instance', 'i:type', 'Undo');
			
			$n = $xml->createElement("ApplicationProfileId");
			$idText = $xml->createTextNode(Velocity_Processor::$applicationprofileid);
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
			$idText = $xml->createTextNode(Velocity_Processor::$merchantprofileid);
			$n->appendChild($idText);
			$root->appendChild($n);
			
			$n = $xml->createElement("TransactionId");
			$idText = $xml->createTextNode($TransactionId);
			$n->appendChild($idText);
			$root->appendChild($n);
			
			return $xml;
		} else {
			throw new Exception(Velocity_Message::$descriptions['errundotransid']);
		}
	}
 
	/* 
     * create ReturnById xml as per the api format.
	 * @param string $TransactionId this is get from response of authorize.
	 * @param float $amount amount for return.	
	 * @return string $xml xml format in string.
	 */
	public static function returnById_XML($amount, $TransactionId){
	    
		if (isset($TransactionId) && isset($amount)) {
			$xml = new DOMDocument("1.0");

			$root = $xml->createElement("ReturnById");

			$xml->appendChild($root);
			$root->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:i', 'http://www.w3.org/2001/XMLSchema-instance');
			$root->setAttribute('xmlns', 'http://schemas.ipcommerce.com/CWS/v2.0/Transactions/Rest');
			$root->setAttributeNS('http://www.w3.org/2001/XMLSchema-instance', 'i:type', 'ReturnById');
			
			$n = $xml->createElement("ApplicationProfileId");
			$idText = $xml->createTextNode(Velocity_Processor::$applicationprofileid);
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
			$idText = $xml->createTextNode(Velocity_Processor::$merchantprofileid);
			$n->appendChild($idText);
			$root->appendChild($n);
			
			return $xml;
		} else {
			throw new Exception(Velocity_Message::$descriptions['errreturndataarray']);
		}
	}
	
	/* 
     * create ReturnUnlinked xml as per the api format.
	 * @param array $data this array hold "amount, paymentAccountDataToken, avsData, carddata, invoice no., order no"
	 * @return string $xml xml format in string.
	 */
	public static function returnunlinked_XML($data){

		if (isset($data['amount']) && isset($data['token']) && isset($data['invoice_no']) && isset($data['order_id'])) {
		
			$xml = new DOMDocument("1.0");

			$root = $xml->createElement("AuthorizeAndCaptureTransaction");

			$xml->appendChild($root);

			$root->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:i', 'http://www.w3.org/2001/XMLSchema-instance');
			$root->setAttribute('xmlns', 'http://schemas.ipcommerce.com/CWS/v2.0/Transactions/Rest');
			$root->setAttributeNS('http://www.w3.org/2001/XMLSchema-instance', 'i:type', 'AuthorizeAndCaptureTransaction');

			$n = $xml->createElement("ApplicationProfileId");
			$idText = $xml->createTextNode(Velocity_Processor::$applicationprofileid);
			$n->appendChild($idText);
			$root->appendChild($n);

			$n = $xml->createElement("MerchantProfileId");
			$idText = $xml->createTextNode(Velocity_Processor::$merchantprofileid);
			$n->appendChild($idText);
			$root->appendChild($n);

			$n = $xml->createElement("Transaction");
			$n->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:ns1', 'http://schemas.ipcommerce.com/CWS/v2.0/Transactions/Bankcard');
			$n->setAttributeNS('http://www.w3.org/2001/XMLSchema-instance', 'i:type', 'ns1:BankcardTransaction');

			$n1 = $xml->createElement("ns2:CustomerData");
			$n1->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:ns2', 'http://schemas.ipcommerce.com/CWS/v2.0/Transactions');
			$n->appendChild($n1);

			$n2 = $xml->createElement("ns2:BillingData");
			$n1->appendChild($n2);

			$n3 = $xml->createElement("ns2:Name");
			$n3->setAttributeNS('http://www.w3.org/2001/XMLSchema-instance', 'i:nil', 'true');
			$n2->appendChild($n3);

			if(isset($data['avsdata']['Street']) && isset($data['avsdata']['City']) && isset($data['avsdata']['StateProvince']) && isset($data['avsdata']['PostalCode']) && isset($data['avsdata']['Country'])) { // check avsdata for authorize method.
			
				$n3 = $xml->createElement("ns2:Address");
				$n2->appendChild($n3);

				$n4 = $xml->createElement("ns2:Street1");
				$idText = $xml->createTextNode($data['avsdata']['Street']);
				$n4->appendChild($idText);
				$n3->appendChild($n4);

				$n4 = $xml->createElement("ns2:Street2");
				$n4->setAttributeNS('http://www.w3.org/2001/XMLSchema-instance', 'i:nil', 'true');
				$n3->appendChild($n4);

				$n4 = $xml->createElement("ns2:City");
				$idText = $xml->createTextNode($data['avsdata']['City']);
				$n4->appendChild($idText);
				$n3->appendChild($n4);

				$n4 = $xml->createElement("ns2:StateProvince");
				$idText = $xml->createTextNode($data['avsdata']['StateProvince']);
				$n4->appendChild($idText);
				$n3->appendChild($n4);

				$n4 = $xml->createElement("ns2:PostalCode");
				$idText = $xml->createTextNode($data['avsdata']['PostalCode']);
				$n4->appendChild($idText);
				$n3->appendChild($n4);

				$n4 = $xml->createElement("ns2:CountryCode");
				$idText = $xml->createTextNode($data['avsdata']['Country']);
				$n4->appendChild($idText);
				$n3->appendChild($n4);
			
			} else {
			
				$n3 = $xml->createElement("ns2:Address");
				$n3->setAttributeNS('http://www.w3.org/2001/XMLSchema-instance', 'i:nil', 'true');
				$n2->appendChild($n3);
				
			}

			$n3 = $xml->createElement("ns2:BusinessName");
			$idText = $xml->createTextNode('MomCorp');
			$n3->appendChild($idText);
			$n2->appendChild($n3);

			$n3 = $xml->createElement("ns2:Phone");
			$n3->setAttributeNS('http://www.w3.org/2001/XMLSchema-instance', 'i:nil', 'true');
			$n2->appendChild($n3);

			$n3 = $xml->createElement("ns2:Fax");
			$n3->setAttributeNS('http://www.w3.org/2001/XMLSchema-instance', 'i:nil', 'true');
			$n2->appendChild($n3);

			$n3 = $xml->createElement("ns2:Email");
			$n3->setAttributeNS('http://www.w3.org/2001/XMLSchema-instance', 'i:nil', 'true');
			$n2->appendChild($n3);

			$n2 = $xml->createElement("ns2:CustomerId");
			$idText = $xml->createTextNode('cust123x');
			$n2->appendChild($idText);
			$n1->appendChild($n2);

			$n2 = $xml->createElement("ns2:CustomerTaxId");
			$n2->setAttributeNS('http://www.w3.org/2001/XMLSchema-instance', 'i:nil', 'true');
			$n1->appendChild($n2);

			$n2 = $xml->createElement("ns2:ShippingData");
			$n2->setAttributeNS('http://www.w3.org/2001/XMLSchema-instance', 'i:nil', 'true');
			$n1->appendChild($n2);

			$n1 = $xml->createElement("ns3:ReportingData");
			$n1->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:ns3', 'http://schemas.ipcommerce.com/CWS/v2.0/Transactions');
			$n->appendChild($n1);

			$n2 = $xml->createElement("ns3:Comment");
			$idText = $xml->createTextNode('a test comment');
			$n2->appendChild($idText);
			$n1->appendChild($n2);

			$n2 = $xml->createElement("ns3:Description");
			$idText = $xml->createTextNode('a test description');
			$n2->appendChild($idText);
			$n1->appendChild($n2);

			$n2 = $xml->createElement("ns3:Reference");
			$idText = $xml->createTextNode('001');
			$n2->appendChild($idText);
			$n1->appendChild($n2);
		
			$n1 = $xml->createElement("ns1:TenderData");
			$n->appendChild($n1);

			if ($data['token'] != '') { // check PaymentAccountDataToken for returnunlinked method.
				$n2 = $xml->createElement("ns4:PaymentAccountDataToken");
				$n2->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:ns4', 'http://schemas.ipcommerce.com/CWS/v2.0/Transactions');
				$idText = $xml->createTextNode($data['token']);
				$n2->appendChild($idText);
				$n1->appendChild($n2);
			} else {
				$n2 = $xml->createElement("ns4:PaymentAccountDataToken");
				$n2->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:ns4', 'http://schemas.ipcommerce.com/CWS/v2.0/Transactions');
				$n2->setAttributeNS('http://www.w3.org/2001/XMLSchema-instance', 'i:nil', 'true');
				$n1->appendChild($n2);
			}

			$n2 = $xml->createElement("ns5:SecurePaymentAccountData");
			$n2->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:ns5', 'http://schemas.ipcommerce.com/CWS/v2.0/Transactions');
			$n2->setAttributeNS('http://www.w3.org/2001/XMLSchema-instance', 'i:nil', 'true');
			$n1->appendChild($n2);

			$n2 = $xml->createElement("ns6:EncryptionKeyId");
			$n2->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:ns6', 'http://schemas.ipcommerce.com/CWS/v2.0/Transactions');
			$n2->setAttributeNS('http://www.w3.org/2001/XMLSchema-instance', 'i:nil', 'true');
			$n1->appendChild($n2);

			$n2 = $xml->createElement("ns7:SwipeStatus");
			$n2->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:ns7', 'http://schemas.ipcommerce.com/CWS/v2.0/Transactions');
			$n2->setAttributeNS('http://www.w3.org/2001/XMLSchema-instance', 'i:nil', 'true');
			$n1->appendChild($n2);
			
			
			if ( $data['carddata']['cardtype'] != '' && $data['carddata']['pan'] != '' && $data['carddata']['expire'] != '' && $data['carddata']['cvv'] != '' ) { // check card data for returnunlinked method.
			
				$n2 = $xml->createElement("ns1:CardData");
				$n1->appendChild($n2);

				$n3 = $xml->createElement("ns1:CardType");
				$idText = $xml->createTextNode($data['carddata']['cardtype']);
				$n3->appendChild($idText);
				$n2->appendChild($n3);

				$n3 = $xml->createElement("ns1:PAN");
				$idText = $xml->createTextNode($data['carddata']['pan']);
				$n3->appendChild($idText);
				$n2->appendChild($n3);

				$n3 = $xml->createElement("ns1:Expire");
				$idText = $xml->createTextNode($data['carddata']['expire']);
				$n3->appendChild($idText);
				$n2->appendChild($n3);
				
				$n3 = $xml->createElement("ns1:CVData");
				$idText = $xml->createTextNode($data['carddata']['cvv']);
				$n3->appendChild($idText);
				$n2->appendChild($n3);

				if (isset($data['carddata']['track1data']) && $data['carddata']['track1data'] != '') { // check track1data for returnunlinked method.
				
					$n3 = $xml->createElement("ns1:Track1Data");
					$idText = $xml->createTextNode($data['carddata']['track1data']);
					$n3->appendChild($idText);
					$n2->appendChild($n3);
					
				} else if (isset($data['carddata']['track2data']) && $data['carddata']['track2data'] != '') { // check track2data for returnunlinked method.
				
					$n3 = $xml->createElement("ns1:Track2Data");
					$idText = $xml->createTextNode($data['carddata']['track2data']);
					$n3->appendChild($idText);
					$n2->appendChild($n3);
					
				} else {
				
					$n3 = $xml->createElement("ns1:Track1Data");
					$n3->setAttributeNS('http://www.w3.org/2001/XMLSchema-instance', 'i:nil', 'true');
					$n2->appendChild($n3);
					
					$n3 = $xml->createElement("ns1:Track2Data");
					$n3->setAttributeNS('http://www.w3.org/2001/XMLSchema-instance', 'i:nil', 'true');
					$n2->appendChild($n3);
					
				}
			
			}
			/* end card data */

			$n2 = $xml->createElement("ns1:EcommerceSecurityData");
			$n2->setAttributeNS('http://www.w3.org/2001/XMLSchema-instance', 'i:nil', 'true');
			$n1->appendChild($n2);

			$n1 = $xml->createElement("ns1:TransactionData");
			$n->appendChild($n1);

			$n2 = $xml->createElement("ns8:Amount");
			$n2->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:ns8', 'http://schemas.ipcommerce.com/CWS/v2.0/Transactions');
			$idText = $xml->createTextNode($data['amount']);
			$n2->appendChild($idText);
			$n1->appendChild($n2);

			$n2 = $xml->createElement("ns9:CurrencyCode");
			$n2->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:ns9', 'http://schemas.ipcommerce.com/CWS/v2.0/Transactions');
			$idText = $xml->createTextNode('USD');
			$n2->appendChild($idText);
			$n1->appendChild($n2);

			$n2 = $xml->createElement("ns10:TransactionDateTime");
			$n2->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:ns10', 'http://schemas.ipcommerce.com/CWS/v2.0/Transactions');
			$idText = $xml->createTextNode('2013-04-03T13:50:16');
			$n2->appendChild($idText);
			$n1->appendChild($n2);

			$n2 = $xml->createElement("ns11:CampaignId");
			$n2->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:ns11', 'http://schemas.ipcommerce.com/CWS/v2.0/Transactions');
			$n2->setAttributeNS('http://www.w3.org/2001/XMLSchema-instance', 'i:nil', 'true');
			$n1->appendChild($n2);

			$n2 = $xml->createElement("ns12:Reference");
			$n2->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:ns12', 'http://schemas.ipcommerce.com/CWS/v2.0/Transactions');
			$idText = $xml->createTextNode('xyt');
			$n2->appendChild($idText);
			$n1->appendChild($n2);

			$n2 = $xml->createElement("ns1:ApprovalCode");
			$n2->setAttributeNS('http://www.w3.org/2001/XMLSchema-instance', 'i:nil', 'true');
			$n1->appendChild($n2);

			$n2 = $xml->createElement("ns1:CashBackAmount");
			$idText = $xml->createTextNode('0.0');
			$n2->appendChild($idText);
			$n1->appendChild($n2);

			$n2 = $xml->createElement("ns1:CustomerPresent");
			$idText = $xml->createTextNode('Present');
			$n2->appendChild($idText);
			$n1->appendChild($n2);

			$n2 = $xml->createElement("ns1:EmployeeId");
			$idText = $xml->createTextNode('11');
			$n2->appendChild($idText);
			$n1->appendChild($n2);

			$n2 = $xml->createElement("ns1:EntryMode");
			$idText = $xml->createTextNode('Keyed');
			$n2->appendChild($idText);
			$n1->appendChild($n2);

			$n2 = $xml->createElement("ns1:GoodsType");
			$idText = $xml->createTextNode('NotSet');
			$n2->appendChild($idText);
			$n1->appendChild($n2);

			$n2 = $xml->createElement("ns1:IndustryType");
			$idText = $xml->createTextNode('Ecommerce');
			$n2->appendChild($idText);
			$n1->appendChild($n2);

			$n2 = $xml->createElement("ns1:InternetTransactionData");
			$n2->setAttributeNS('http://www.w3.org/2001/XMLSchema-instance', 'i:nil', 'true');
			$n1->appendChild($n2);

			$n2 = $xml->createElement("ns1:InvoiceNumber");
			$idText = $xml->createTextNode($data['invoice_no']);
			$n2->appendChild($idText);
			$n1->appendChild($n2);

			$n2 = $xml->createElement("ns1:OrderNumber");
			$idText = $xml->createTextNode($data['order_id']);
			$n2->appendChild($idText);
			$n1->appendChild($n2);

			$n2 = $xml->createElement("ns1:IsPartialShipment");
			$idText = $xml->createTextNode('false');
			$n2->appendChild($idText);
			$n1->appendChild($n2);

			$n2 = $xml->createElement("ns1:SignatureCaptured");
			$idText = $xml->createTextNode('false');
			$n2->appendChild($idText);
			$n1->appendChild($n2);

			$n2 = $xml->createElement("ns1:FeeAmount");
			$idText = $xml->createTextNode('0.0');
			$n2->appendChild($idText);
			$n1->appendChild($n2);

			$n2 = $xml->createElement("ns1:TerminalId");
			$n2->setAttributeNS('http://www.w3.org/2001/XMLSchema-instance', 'i:nil', 'true');
			$n1->appendChild($n2);

			$n2 = $xml->createElement("ns1:LaneId");
			$n2->setAttributeNS('http://www.w3.org/2001/XMLSchema-instance', 'i:nil', 'true');
			$n1->appendChild($n2);

			$n2 = $xml->createElement("ns1:TipAmount");
			$idText = $xml->createTextNode('0.0');
			$n2->appendChild($idText);
			$n1->appendChild($n2);

			$n2 = $xml->createElement("ns1:BatchAssignment");
			$n2->setAttributeNS('http://www.w3.org/2001/XMLSchema-instance', 'i:nil', 'true');
			$n1->appendChild($n2);

			$n2 = $xml->createElement("ns1:PartialApprovalCapable");
			$idText = $xml->createTextNode('NotSet');
			$n2->appendChild($idText);
			$n1->appendChild($n2);

			$n2 = $xml->createElement("ns1:ScoreThreshold");
			$n2->setAttributeNS('http://www.w3.org/2001/XMLSchema-instance', 'i:nil', 'true');
			$n1->appendChild($n2);

			$n2 = $xml->createElement("ns1:IsQuasiCash");
			$idText = $xml->createTextNode('false');
			$n2->appendChild($idText);
			$n1->appendChild($n2);

			$root->appendChild($n);
			
			return $xml;
		} else {
			throw new Exception(Velocity_Message::$descriptions['errreturnundataarray']);
		}
	}		
}