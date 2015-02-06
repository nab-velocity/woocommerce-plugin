<?php

class Velocity_Message
{
  public function __construct(){
  }

  public static $descriptions = array(
    'errtransparentjs' => 'Post data is not set from transparent redirect.',
	'errsessionxmlnotset' => 'Before curl request session and/or xml not set!',
	'errcredentialnotset' => 'Error one or credential not like applicationid, merchantprofileid etc.',
	'errpostmethod' => 'Error in path and/or data array in post method.',
	'errgetmethod' => 'Error in path and/or data array in get method.',
	'errputmethod' => 'Error in path and/or data array in put method.',
	'errattributearrnotset' => 'attributes array returned from authorize response is not set!',
	'errunknown' => 'unknown error in response data!',
	'errsignon' => 'invalid identity token',
	'errmrchtid' => 'Invalid MerchantProfileid',
	'errpannum' => 'Invalid credit card number',
	'errexpire' => 'Invalid expiry month and/or year',
	'errcvdata' => 'Invalid CVV data',
	'errunknown' => 'some unknown error',
	'erstatecode' => 'Invalid state code or sevice not available or some unknown error',
	// verify
	'errverfsesstoken' => 'session token is not set for verify request!',
	'errverfavswflid' => 'PaymentAccountDataToken and/or workflowid are not set!',
	'errverfattraray' => 'After authorization attribute array is not!',
	'errverftrandata' => 'transaction data array not set for verify request',
	'errverfxml' => 'Some value not set in xml for verify!',
	// authorize
	'errauthsesstoken' => 'session token is not set for authorize request!',
	'erraurhavswflid' => 'PaymentAccountDataToken and/or workflowid are not set!',
	'erraurhattraray' => 'After authorization attribute array is not!',
	'errauthtrandata' => 'transaction data array not set for authorize request',
	'errauthxml' => 'Some value not set in xml for authorize!',
	// capture
	'errcapsesswfltransid' => 'for capture sessiontoken, workflowid and/or transaction id are not set!',
	'errcaptransidamount' => 'transaction id and/or amount not set!',
	'errcapxml' => 'Some value not set in xml for capture!',
	// adjust
	'erradjustsesswfltransid' => 'for adjust sessiontoken, workflowid and/or transaction id are not set!',
	'errverauthcappath' => 'verify or authorize or authorizeandcapture request path not set proper!',
	'erradjtransidamount' => 'adjust request transaction id and/or amount not set!',
	'erradjxml' => 'Some value not set in xml for adjust!',
	// authorizeandcapture
	'errauthncapdataarray' => 'authorizeandcapture data array not set!',
	'errauthncapxml' => 'authorizeandcapture reqest xml object is null!',
	'erraurhncapavswflid' => 'PaymentAccountDataToken, avsdata and/or workflowid are not set!',
	// undo
	'errundotransid' => 'transaction id is in undo request not set!',
	'errundoxml' => 'Some value not set in xml for undo!',
	'errundosesswfltransid' => 'for undo sessiontoken, workflowid and/or transaction id are not set!',
	// returnbyid
	'errreturndataarray' => 'transaction id and/or not in returnById request!',
	'errreturnncapxml' => 'Some value not set in xml for returnById!',
	'errreturntranidwid' => 'for returnbyId sessiontoken, workflowid and/or transaction id are not set!',
	'errreturnunlinkedamnt' => 'for returnunlinked sessiontoken, workflowid and/or transaction id are not set!',
	'errreturnundataarray' => 'transaction id and/or not in returnunlinked request!',
  );
}
