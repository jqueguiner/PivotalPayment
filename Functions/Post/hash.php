<?php
Class Pivotal_Hash{

	var $_secret;
	var $_terminal;
	var $_normalizedPaymentReponse;
	var $_postHash;
	var $_postDateTime;
	var $_responseHash;

	public function __construct($paymentParams,$terminal,$postDateTime){
		$this->_paymentParams = $paymentParams;

		$this->_secret = $terminal['SharedSECRET'];
		$this->_terminal = $terminal['TerminalID'];
		$this->_postDateTime = $postDateTime;
		$this->createHash();
	}

	public function setNormalizedPaymentReponse($normalizedPaymentReponse){
		$this->_normalizedPaymentReponse = $normalizedPaymentReponse;
	}

	public function getNormalizedPaymentReponse(){
		return $this->_normalizedPaymentReponse;
	}

	public function setSecret($secret){
		$this->_secret = $secret;
	}

	public function getSecret(){
		return $this->_secret;
	}

	public function setPostHash($hash){
		$this->_postHash = $hash;
	}

	public function getPostHash(){
		return $this->_postHash;
	}
	public function setResponseHash($hash){
		$this->_responseHash = $hash;
	}

	public function getResponseHash(){
		return $this->_responseHash;
	}

	public function createHash(){
		
		$params = $this->_paymentParams;

		$stringToHash = '';
		$stringToHash .= $this->_terminal;
		$stringToHash .= $params['ORDERID'];
		$stringToHash .= $params['AMOUNT'];
		$stringToHash .= $this->_postDateTime;
		$stringToHash .= $this->_secret;
		$this->_postHash = md5($stringToHash);
		return md5($stringToHash);
	}

	public function buildResponseHash(){

		$reponse = $this->_normalizedPaymentReponse;

		$payment = $this->_paymentParams;

		$stringToHash = '';
		$stringToHash .= $this->_terminal;
		$stringToHash .= $reponse['UNIQUEREF'];
		$stringToHash .= $payment['AMOUNT'];
		$stringToHash .= $reponse['DATETIME'];
		$stringToHash .= $reponse['RESPONSECODE'];
		$stringToHash .= $reponse['RESPONSETEXT'];

		$stringToHash .= $this->_secret;

		$this->_responseHash = md5($stringToHash);

		return md5($stringToHash);
	}

	public function controlResponseHash($responseHash){

		$this->_normalizedPaymentReponse=$responseHash;
		return ($this->buildResponseHash() == $responseHash['HASH']);
	}
}