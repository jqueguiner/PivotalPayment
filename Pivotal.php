<?php
	$ds=DIRECTORY_SEPARATOR;
	require_once(dirname(__FILE__).$ds.'Functions'.$ds.'Post'.$ds.'config.php');

class Pivotal {

	var $_mode = 'test';
	var $_config = array();
	var $_paymentParams = array();
	var $_normalizedPaymentParams = array();
	var $_responseParams = array();
	var $_normalizedResponseParams = array();
	var $_paymentURL = '';
	var $_secret = '';
	var $_hash = '';
	var $_responseHash = '';
	var $_terminal = '';
	var $_dateTime = '';
	var $Pivotal_Config;
	var $Pivotal_Post;
	var $Pivotal_Format;

	var $_dbConfig = array(
		'server' => 'localhost',
		'login' => 'root',
		'password' => 'root',
		'database' => 'myDB'
		);

	var $_txnConfig = array(
		'table' => 'transactions',
		'fieldMapping' => array(
			'TXNID' => 'id',
			'UNIQUEREF' => 'uniqueRef',
			'RESPONSECODE' => 'responseCode',
			'RESPONSETEXT' => 'responseText',
			'APPROVALCODE' => 'approvalCode',
			'DATETIME' => 'dateTime',
			'AVSRESPONSE' => 'AVSResponse',
			'CVVRESPONSE' => 'CVVResponse',
			'AMOUNT' => 'amount',
			'CURRENCY' => 'currency',
			'HASH' => 'hash',
			'STATUS' => 'status',
			'ERRORSTRING' => 'error',
			'ORDERID' => 'order_id',
			'CARDHOLDERNAME' => 'payer'
			)
		);

	var $_orderConfig = array(
		'table' => 'orders',
		'fieldMapping' => array(
			'ORDERID' => 'id',
			'TXNID' => 'txn_id',
			'AMOUNT' => 'amountPaid',
			'CURRENCY' => 'currencyPaid',
			'DATETIME' => 'paiementDate',
			'STATUS' => 'paiementStatus'
			)
		);
	
	
	
	public function __construct($mode = 'test',$params){

		// config checkups
		$this->loadLibs();
		$this->loadHelpers();

		// set mode
		$this->_mode = $mode;
		$this->_paymentParams = $params;

		$this->setEnvironment();

	}


	private function loadLibs(){
		$ds = DIRECTORY_SEPARATOR;
		require_once(dirname(__FILE__).$ds.'Functions'.$ds.'Settings'.$ds.'checkup.php');


		require_once(dirname(__FILE__).$ds.'Functions'.$ds.'Post'.$ds.'curl.php');
		require_once(dirname(__FILE__).$ds.'Functions'.$ds.'Post'.$ds.'format.php');
		require_once(dirname(__FILE__).$ds.'Functions'.$ds.'Post'.$ds.'hash.php');
		require_once(dirname(__FILE__).$ds.'Functions'.$ds.'Post'.$ds.'post.php');
		require_once(dirname(__FILE__).$ds.'Functions'.$ds.'Post'.$ds.'db.php');

		require_once(dirname(__FILE__).$ds.'Functions'.$ds.'Settings'.$ds.'checkup.php');

		require_once(dirname(__FILE__).$ds.'Functions'.$ds.'Utils'.$ds.'XML.php');
		

	}

	private function loadHelpers(){
		$ds = DIRECTORY_SEPARATOR;
		require_once(dirname(__FILE__).$ds.'Helper'.$ds.'form.php');

	}

	public function sendPayment($saveToDB = TRUE){

		$this->Pivotal_Post = new Pivotal_Post($this->_paymentURL, $this->_paymentParams,$this->Pivotal_Config);
		$out = $this->Pivotal_Post->sendPayment();

		if($out['STATUS'] == false && !strpos($out['ERRORSTRING'],'#')===false):
			$error = $out['ERRORSTRING'];

			if(!strpos($error,'#AnonType_CARDNUMBER')===false):
				$out['ERRORSTRING'] = 'wrong card number (must be at least 10 digits)';
			endif;
		
		endif;

		if($saveToDB):
			$this->Pivotal_Db = new Pivotal_Db($this->_dbConfig, $this->_txnConfig, $this->_orderConfig);
			$this->Pivotal_Db->saveTxnAndOrder($this->Pivotal_Post->getNormalizedPaymentParams()+$out);
			$out['TXNID'] = $this->Pivotal_Db->_txnId;
		endif;

		return $out;
	}

	public function setEnvironment(){
		$this->Pivotal_Config = new Pivotal_Config($this->_mode);

		$this->_config = $this->Pivotal_Config->readMainConfig();
		$this->_paymentURL = $this->_config['url'];
	}

	public function buildHash(){
		$this->setHash($this->createHash());
		return($this->_hash);
	}

	public function setConfig($config = array()){
		$this->_config = $config;
	}

	public function getConfig(){
		return $this->_config;
	}

	public function setDateTime($dateTime=''){
		$this->_dateTime = $dateTime;
	}

	public function getDateTime(){
		return $this->_dateTime;
	}

	public function setPaymentURL($URL = ''){
		$this->_paymentURL = $URL;
	}

	public function getPaymentURL(){
		return $this->_paymentURL;
	}

	public function setHash($hash = ''){
		$this->_hash = $hash;
	}

	public function getHash(){
		return $this->_hash;
	}

	public function setSecret($secret = ''){
		$this->_secret = $secret;
	}

	public function getSecret($secret = ''){
		return $this->_secret;
	}

	public function setTerminal($terminalId = ''){
		$this->_terminalId = $terminalId;
	}

	public function getTerminal($terminalId = ''){
		return $this->_terminalId;
	}

	public function setMode($mode = 'test'){
		$this->_mode = $mode;
	}

	public function getMode(){
		return $this->_mode;
	}

	public function setPaymentParams($params = array()){
		$this->_paymentParams = $params;
	}

	public function getPaymentParams(){
		return $this->_paymentParams;
	}

	public function setNormalizedPaymentParams($normalizedPaymentParams){
		$this->_normalizedPaymentParams = $normalizedPaymentParams;
	}

	public function getNormalizedPaymentParams(){
		return $this->_normalizedPaymentParams;
	}

	public function setResponseParams($responseParams){
		$this->_responseParams = $responseParams;
	}

	public function getResponseParams(){
		return $this->_responseParams;
	}

	public function setNormalizedResponseParams($responseParams){
		$this->_normalizedResponseParams = $responseParams;
	}

	public function getNormalizedResponseParams(){
		return $this->_normalizedResponseParams;
	}

	public function setResponseHash($responseHash){
		$this->_responseHash = $responseHash;
	}

	public function getResponseHash(){
		return $this->_responseHash;
	}

	public function setCurrencyTerminalId($currency){
		$this->setTerminalId($this->readCurrencyTerminal($currency));
	}

	public function setDbConfig($dbConfig){
		$this->_dbConfig = $dbConfig + $this->_dbConfig;
	}

	public function getDbConfig(){
		$this->_dbConfig;
	}
	public function setTxnConfig($txnConfig){
		$this->_txnConfig = $txnConfig + $this->_txnConfig;
	}

	public function getTxnConfig(){
		$this->_txnConfig;
	}

	public function setOrderConfig($orderConfig){
		$this->_orderConfig = $orderConfig + $this->_orderConfig;
	}

	public function getOrderConfig(){
		$this->_orderConfig;
	}

}