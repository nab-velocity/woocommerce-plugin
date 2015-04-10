<?php

/*
 * This class represents a TransactionClassTypePair.
 * It can be used to set Transactionsclass & TransactionType as single object.
 */
class TransactionClassTypePair {
    
		public $TransactionClass; // int
		public $TransactionType; // int
                /*
                 * TransactionClassPairType set after call the construtor
                 * @param array $tctp holds the 'TransactionClass' & 'TransactionType'.
                 */
                public function __construct($tctp) {
                    try {
                        $this->TransactionClass = $tctp['TransactionClass'];
                        $this->TransactionType = $tctp['TransactionType'];
                    } catch (Exception $ex) {
                        throw new Exception(VelocityMessage::$descriptions['errqtdtctp']);
                    }
                }
                
}

/*
 * This class represents transaction detail between start date & end date.
 * t can be used to set EndDateTime & StartDateTime as single object.
 */
class DateRange {
		public $EndDateTime; // dateTime
		public $StartDateTime; // dateTime
                public function __construct($daterange) {
                    try {
                        date_default_timezone_set('UTC'); 
                        $EndDateTime = date('Y-m-d\TH:i:s.u\Z',strtotime($daterange['EndDateTime']));
                        $StartDateTime = date('Y-m-d\TH:i:s.u\Z',strtotime($daterange['StartDateTime']));
                    } catch (Exception $ex) {
                        throw new Exception(VelocityMessage::$descriptions['errqtpdaterange']);
                    }
                }
}

/*
 * This class represents a Velocity QueryTransactionsParameters.
 * It can be used to set Query Transactions Parameters and use as single object.
 */
 
class QueryTransactionsParameters {
    
		public $Amounts; // ArrayOfdecimal
		public $ApprovalCodes; // ArrayOfstring
		public $BatchIds; // ArrayOfstring
		public $CaptureDateRange; // DateRange
		public $CaptureStates; // ArrayOfCaptureState
		public $CardTypes; // ArrayOfTypeCardType
		public $IsAcknowledged = 'false'; // BooleanParameter
		public $MerchantProfileIds; // ArrayOfstring
		public $OrderNumbers; // ArrayOfstring
		public $QueryType = 'OR'; // QueryType
		public $ServiceIds; // ArrayOfstring
		public $ServiceKeys; // ArrayOfstring
		public $TransactionClassTypePairs; // ArrayOfTransactionClassTypePair
		public $TransactionDateRange; // DateRange
		public $TransactionIds; // ArrayOfstring
		public $TransactionStates; // ArrayOfTransactionState
                
                /*
                 * QueryTransactionsParameters set after call the construtor
                 * @param array $qtp holds the Transactionparameters.
                 */
                public function __construct($qtp) {
                    
                    if (isset($qtp['TransactionClassTypePairs']) && $qtp['TransactionClassTypePairs'] != NULL){
                        $tctp = array(); // set TransactionClass & TransactionType in object
                        foreach ($qtp['TransactionClassTypePairs'] as $key => $vartctp) {
                           $tctp[$key] = new TransactionClassTypePair($vartctp);
                        }
                    } else {
                        $tctp = NULL;
                    }
                    
                    try { // set CaptureDateRange in object
                        $CaptureDateRange = new DateRange($qtp['CaptureDateRange']);
                    } catch (Exception $ex) {
                        throw new Exception($ex->getMessage());
                    }
                    
                    try { // set TransactionDateRange in object
                        $TransactionDateRange = new DateRange($qtp['TransactionDateRange']);
                    } catch (Exception $ex) {
                        throw new Exception($ex->getMessage());
                    }
                    
                    try {
                        $this->Amounts = $qtp['Amounts']; // ArrayOfdecimal
                        $this->ApprovalCodes = $qtp['ApprovalCodes']; // ArrayOfstring
                        $this->BatchIds = $qtp['BatchIds']; // ArrayOfstring
                        $this->CaptureDateRange = $qtp['CaptureDateRange']; // DateRange
                        $this->CaptureStates = $qtp['CaptureStates'];
                        $this->CardTypes = $qtp['CardTypes']; // ArrayOfTypeCardType
                        $this->MerchantProfileIds = $qtp['MerchantProfileIds']; // ArrayOfstring
                        $this->OrderNumbers = $qtp['OrderNumbers']; // ArrayOfstring
                        $this->ServiceIds = $qtp['ServiceIds']; // ArrayOfstring
                        $this->ServiceKeys = $qtp['ServiceKeys']; // ArrayOfstring
                        $this->TransactionClassTypePairs = $tctp; // ArrayOfTransactionClassTypePair*/
                        $this->TransactionDateRange = $qtp['TransactionDateRange'];//$txnDateRange; // DateRange
                        $this->TransactionIds = $qtp['TransactionIds'];
                        $this->TransactionStates = $qtp['TransactionStates']; // ArrayOfTransactionState
                    } catch (Exception $ex) {
                        throw new Exception(VelocityMessage::$descriptions['errqtp']);
                    }
                    
                }
}

/*
 * This class represents PagingParameters.
 * It can be used to set Pages & PageSize as single object.
 */
class PagingParameters {
    
		public $Page; // int
		public $PageSize; // int
                /*
                 * PagingParameters set page number & pagesize after call the construtor
                 * @param array $pp holds the page & page size.
                 */
                public function __construct($pp) {
                    try {
                        $this->Page = $pp['page'];
                        $this->PageSize = $pp['pagesize'];
                    } catch (Exception $ex) {
                        throw new Exception(VelocityMessage::$descriptions['errpp']);
                    }
                }
                
}

/*
 * This class represents Query Transactions Detail.
 * It can be used to set different parameters with different datatype.
 */
class QueryTransactionsDetail {
    
		public $IncludeRelated; // boolean
		public $PagingParameters; // PagingParameters
		public $QueryTransactionsParameters; // QueryTransactionsParameters
		public $TransactionDetailFormat; // TransactionDetailFormat
                
                /*
                 * QueryTransactionsDetail set the different parameter.
                 * @param boolean $includeRelated hold true/false.
                 * @param object $pagingParameters holds page & page size.
                 * @param object $Qtxnparams holds transactionid, ammount, order number, capturestate etc.
                 * @param string $transactionDetailFormat holds CWSTransaction/SerializedCWS.
                 */
                public function __construct($includeRelated, $pagingParameters, $Qtxnparams, $transactionDetailFormat) {
                    
                    try {
                        $this->IncludeRelated = $includeRelated;
                        $this->PagingParameters = $pagingParameters;
                        $this->QueryTransactionsParameters = $Qtxnparams;
                        $this->TransactionDetailFormat = $transactionDetailFormat;
                    } catch (Exception $ex) {
                        throw new Exception(VelocityMessage::$descriptions['errqtd']);
                    }
                  
                }

}