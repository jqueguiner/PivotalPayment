PivotalPayment
==============
PHP Library for Pivotal Payment
http://www.pivotalpayments.com/ca/fr/



Requirements
-----------
CURL (php_curl)
XMLRPC (php_xmlrpc)
Multibyte String PHP extension (php_mbstring)

Installation
-----------
  Place the content of the github repo into the desired directory in your projet ($DIR)

Usage
-----
  //$DIR see section installation below
  require_once($DIR.DIRECTORY_SEPARATOR.'Pivotal.php');	
	
	$pivotal_config = new Pivotal_Config('test');
	
	$card = $pivotal_config->readVendorTestCard('visa');

	$paymentParams['ORDERID'] = rand(10,10000);
	$paymentParams['AMOUNT'] = 1000;
	$paymentParams['CURRENCY'] = 'CAD';
	$paymentParams['CARDNUMBER'] = $card['CardNumber'];
	$paymentParams['CARDHOLDERNAME'] = $card['CardHolderName'];
	$paymentParams['MONTH'] = '09';
	$paymentParams['YEAR'] = '16';
  $paymentParams['CVV'] = $card['CVV'];

  $pivotal = new Pivotal('test',$params);

  $response = $pivotal->sendPayment();
	


 
