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

		require_once(dirname(__FILE__).$ds.'Functions'.$ds.'Settings'.$ds.'checkup.php');

		require_once(dirname(__FILE__).$ds.'Functions'.$ds.'Utils'.$ds.'XML.php');

	}

	private function loadHelpers(){
		$ds = DIRECTORY_SEPARATOR;
		require_once(dirname(__FILE__).$ds.'Helper'.$ds.'form.php');

	}

	public function sendPayment(){

		$this->Pivotal_Post = new Pivotal_Post($this->_paymentURL, $this->_paymentParams,$this->Pivotal_Config);


		return $this->Pivotal_Post->sendPayment();
	}

	public function setEnvironment(){
		$this->Pivotal_Config = new Pivotal_Config($this->_mode);

		$this->_config = $this->Pivotal_Config->readMainConfig();
		$this->_paymentURL = $this->_config['url'];
		
		
	}

	public function buildHash(){
		$this->setHash($this->createHash());
		return($this->hash);
	}

	public function setConfig($config = array()){
		$this->config = $config;
	}

	public function getConfig(){
		return $this->config;
	}

	public function setDateTime($dateTime=''){
		$this->dateTime = $dateTime;
	}

	public function getDateTime(){
		return $this->dateTime;
	}

	public function setPaymentURL($URL = ''){
		$this->paymentURL = $URL;
	}

	public function getPaymentURL(){
		return $this->paymentURL;
	}

	public function setHash($hash = ''){
		$this->hash = $hash;
	}

	public function getHash(){
		return $this->hash;
	}

	public function setSecret($secret = ''){
		$this->secret = $secret;
	}

	public function getSecret($secret = ''){
		return $this->secret;
	}

	public function setTerminal($terminalId = ''){
		$this->terminalId = $terminalId;
	}

	public function getTerminal($terminalId = ''){
		return $this->terminalId;
	}

	public function setMode($mode = 'test'){
		$this->mode = $mode;
	}

	public function getMode(){
		return $this->mode;
	}

	public function setPaymentParams($params = array()){
		$this->paymentParams = $params;
	}

	public function getPaymentParams(){
		return $this->paymentParams;
	}

	public function setNormalizedPaymentParams($normalizedPaymentParams){
		$this->normalizedPaymentParams = $normalizedPaymentParams;
	}

	public function getNormalizedPaymentParams(){
		return $this->normalizedPaymentParams;
	}

	public function setResponseParams($responseParams){
		$this->responseParams = $responseParams;
	}

	public function getResponseParams(){
		return $this->responseParams;
	}

	public function setNormalizedResponseParams($responseParams){
		$this->normalizedResponseParams = $responseParams;
	}

	public function getNormalizedResponseParams(){
		return $this->normalizedResponseParams;
	}

	public function setResponseHash($responseHash){
		$this->responseHash = $responseHash;
	}

	public function getResponseHash(){
		return $this->responseHash;
	}

	public function setCurrencyTerminalId($currency){
		$this->setTerminalId($this->readCurrencyTerminal($currency));
	}
	
}