<?php
Class Pivotal_Post{

	var $_paymentURL ;
	var $_paymentParams ;
	var $_xml ;
	var $_terminal ;
	var $_postHash ;
	var $_postDateTime ;
	var $_normalizedPaymentParams ;
	var $_normalizedPaymentReponse ;
	var $Pivotal_Config ;

	public function __construct($paymentURL,$paymentParams,$Pivotal_Config){
		$this->_paymentURL = $paymentURL;
		$this->_paymentParams = $paymentParams;
		$this->_postDateTime = date('d-m-Y:H:i:s').':000';
		$this->Pivotal_Config = $Pivotal_Config;
	}

	public function sendPayment(){
		$curl = new Pivotal_Curl();

		$this->_terminal = $this->Pivotal_Config->readCurrencyTerminal($this->_paymentParams['CURRENCY']);

		$hash = new Pivotal_Hash($this->_paymentParams,$this->_terminal,$this->_postDateTime);
		
		$format = new Pivotal_Format($this->_paymentParams,$this->Pivotal_Config,$hash->getPostHash(),$this->_postDateTime);
		$this->_normalizedPaymentParams = $format->getNormalizedPaymentParams();
		$this->_xml = $format->getXML();
		$format->setPaymentResponse($curl->curlXmlRequest($this->_paymentURL,$this->_xml));
		$normalizedPaymentReponse = $format->normalizePaymentReponse();
		$normalizedPaymentReponse['STATUS'] = $hash->controlResponseHash($normalizedPaymentReponse);
		$this->_normalizedPaymentReponse = $normalizedPaymentReponse;
		return $normalizedPaymentReponse;

	}

	public function getXML(){
		return $this->_xml;
	}

	public function getNormalizedPaymentReponse(){
		return $this->_normalizedPaymentReponse;
	}

	public function getNormalizedPaymentParams(){
		return $this->_normalizedPaymentParams;
	}

}
