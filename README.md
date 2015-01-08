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

Live Usage
-----------
  	//$DIR see section installation below
  	require_once($DIR.DIRECTORY_SEPARATOR.'Pivotal.php');	

	$paymentParams['ORDERID'] = $orderId;
	$paymentParams['AMOUNT'] = $amount;
	$paymentParams['CURRENCY'] = $currency;
	$paymentParams['CARDNUMBER'] = $cardNumber;
	$paymentParams['CARDHOLDERNAME'] = $cardHolderName;
	//month two digits (09 for september)
	$paymentParams['MONTH'] = $cardMonth;
	//year two digits (16 for 2016)
	$paymentParams['YEAR'] = $cardYear;
	//CVV 3 or 4 Digits depending on vendor
  	$paymentParams['CVC'] = $cardCVC;

	//'live' for live environment
	//'test' for test environment
  	$pivotal = new Pivotal('live',$paymentParams);

  	$response = $pivotal->sendPayment();
	

Test Usage
-----------
Test cards are included in the library:

  	//$DIR see section installation below
  	require_once($DIR.DIRECTORY_SEPARATOR.'Pivotal.php');	
	
	$pivotal_config = new Pivotal_Config('test');
	
	//get test card number for the selected vendor (Visa)
	//get the holdername and CVV too
	//all test variables are under the Data Directory (TestCards.json)
	//live and Test URL are in Data Directory (MainConfig.json)
	//live and Test terminals are in Data Directory (Terminals.json)

	$card = $pivotal_config->readVendorTestCard('visa');
	
	$paymentParams['ORDERID'] = rand(10,10000);
	$paymentParams['AMOUNT'] = 1000;
	$paymentParams['CURRENCY'] = 'CAD';
	$paymentParams['CARDNUMBER'] = $card['CardNumber'];
	$paymentParams['CARDHOLDERNAME'] = $card['CardHolderName'];
	$paymentParams['MONTH'] = '09';
	$paymentParams['YEAR'] = '16';
  	$paymentParams['CVC'] = $card['CVC'];

  	$pivotal = new Pivotal('test',$paymentParams);

  	$response = $pivotal->sendPayment();
 
Payment validation Output
-----------
	//if success
	array(
		'UNIQUEREF' => 'GW5CWTXWIW',
		'RESPONSECODE' => 'A',
		'RESPONSETEXT' => 'APPROVAL',
		'APPROVALCODE' => '475318',
		'DATETIME' => '2014-11-10T17:25:58',
		'AVSRESPONSE' => 'X',
		'CVVRESPONSE' => 'M',
		'HASH' => 'b035f8f72f4be9df404d6268b55c02b0',
		'STATUS' => true
	)
	
	or
	//if error
	array(
		'ERRORSTRING' => 'The error description',
		'STATUS' => false
	)

Building Payment Form
-----------
Payment form helper is included in the library and is based on https://github.com/jessepollak/card plugin

![card](http://i.imgur.com/qG3TenO.gif)

  	//$DIR see section installation below
  	require_once($DIR.DIRECTORY_SEPARATOR.'Helper'.DIRECTORY_SEPARATOR.'form.php');	
  	
	//$action = formAction see http://www.w3schools.com/tags/att_form_action.asp for more info
	$action = 'PaymentProcessURL.php';
	
	$pivotal_form = new Pivotal_Form($action);
  	echo $pivotal_form->buildForm();

Extra
-----------
This Lib also provide Regex to detect card vendor (REGEX are located in Data/CardTypes.json)
and you can read the vendor based on the Card Number

 
	//$DIR see section installation below
  	require_once($DIR.DIRECTORY_SEPARATOR.'Pivotal.php');	
	
	$pivotal_config = new Pivotal_Config('test');

	//this clean and compare the card number to regular expression config located under Data/CardTypes.json
	//please feel free to add new credit card type but keep in mind the the order in the CardTypes.json is important and that last pattern found = output
	//so be sure to have VISA, MASTERCARD before resellers (VISA DEBIT is part of VISA for instance which means that VISA should be before VISA DEBIT)

	$vendor = $pivotal_config->getCardType('1234567890123456');
	
Credit Card Regex
-----------
Here are the credit card regex known so far, please feel free to contribute to make this lib even more accurate.
* Maestro
* JCB
* Diners
* American
* Express
* Visa
* MasterCard
* Debit MasterCard
* UK Domestic Maestro
* Solo
