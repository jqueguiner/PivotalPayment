<?php
class Pivotal_Format{
	protected $_paymentParams;
	protected $_paymentResponse;
	protected $_normalizedPaymentReponse;
	protected $_normalizedPaymentParams;
	protected $_dateTime;
	protected $_terminal;
	protected $_postedHash;
	protected $_postDateTime;
	protected $_xml;
	protected $Pivotal_Config;

	public function __construct($paymentParams,$Pivotal_Config,$postHash,$postDateTime){
		$this->_paymentParams = $paymentParams;
		$this->_postHash = $postHash;
		$this->Pivotal_Config = $Pivotal_Config;
		$this->_terminal = $this->Pivotal_Config->readCurrencyTerminal($this->_paymentParams['CURRENCY']);
		$this->_postDateTime = $postDateTime;
		$this->preparePaymentParameter();
		$this->preparePaymentXML();
		

	}

	public function setPaymentParams($paymentParams){
		$this->_paymentParams = $paymentParams;
	}

	public function getPaymentParams(){
		return $this->_paymentParams;
	}
	public function setXML($xml){
		$this->_xml = $xml;
	}

	public function getXML(){
		return $this->_xml;
	}

	public function setNormalizedPaymentParams($paymentParams){
		$this->_normalizedPaymentParams = $paymentParams;
	}

	public function getNormalizedPaymentParams(){
		return $this->_normalizedPaymentParams;
	}

	public function setTerminal($terminal){
		$this->_terminal = $terminal;
	}

	public function getTerminal(){
		return $this->_terminal;
	}

	public function setPostHash($hash){
		$this->_postHash = $hash;
	}
	public function getPostHash($hash){
		return $this->_postHash;
	}

	public function setDateTime($dateTime){
		$this->_dateTime = $dateTime;
	}

	public function getDateTime(){
		return $this->_dateTime;
	}

	public function setPaymentResponse($paymentResponse){
		$this->_paymentResponse = $paymentResponse;
	}

	public function getPaymentResponse(){
		return $this->_paymentResponse;
	}

	public function normalizePaymentReponse(){
		$this->_normalizedPaymentReponse = $this->XMLToArray($this->_paymentResponse);
		return $this->_normalizedPaymentReponse;
	}

	private function preparePaymentXML(){
		$xmlStructure = $this->Pivotal_Config->readFields();
		
		$out = '<?xml version="'.$xmlStructure['XMLHeader']['version'].'" encoding="'.$xmlStructure['XMLHeader']['encoding'].'"?>';
		
		$out .= '<'.$xmlStructure['XMLEnclosureTag'].'>';
		
		$params = $this->_normalizedPaymentParams;

		foreach($params as $key=>$param):
			$tag = strtoupper($key);
			$out .= '<'.$tag.'>'.$param.'</'.$tag.'>';
		endforeach;
		$out .= '</'.$xmlStructure['XMLEnclosureTag'].'>';
		$this->_xml = $out;
		return $out;
	}

	private function cleanExpiryDate($month = '',$year = ''){
		if(strlen($year)>2):
			$year = substr($year, 2,2);
		endif;

		$date = $month.$year;

		return $date;
	}

	private function cleanCardNumber($cardNumber = ''){
		$cardNumber = str_replace('-' , '', $cardNumber);
		$cardNumber = str_replace(' ' , '', $cardNumber);

		return $cardNumber;
	}


	public function getCardType($cardNumber){
		$cardNumber = $this->cleanCardNumber($cardNumber);

		$cardsPatterns = $this->Pivotal_Config->readConfigData('CardTypes');
		
		$rcardtype = '';

		foreach($cardsPatterns as $cardPattern):
			
			$pattern = $cardPattern['Pattern'];
			
			if($pattern != 'unknown'):	
				$pattern = '/'.$pattern.'/';
				if(preg_match($pattern, $cardNumber)):
					$rcardtype = $cardPattern['Vendor'];
				endif;
			endif;

		endforeach;
		
		return strtoupper($rcardtype);
		
	}
	

	private function preparePaymentParameter(){
		$params = $this->_paymentParams;
		$this->_terminal = $this->Pivotal_Config->readCurrencyTerminal($params['CURRENCY']);
		$out = array();

		$out['ORDERID'] = $params['ORDERID'];
		$out['TERMINALID'] = $this->_terminal['TerminalID'];
		$out['AMOUNT'] = $params['AMOUNT'];
		$out['DATETIME'] = $this->_postDateTime;
		$out['CARDNUMBER'] = $this->cleanCardNumber($params['CARDNUMBER']);
		$out['CARDTYPE'] = $this->getCardType($params['CARDNUMBER']);
		$out['CARDEXPIRY'] = $this->cleanExpiryDate($params['MONTH'],$params['YEAR']);
		$out['CARDHOLDERNAME'] = $params['CARDHOLDERNAME'];
		$out['HASH'] = $this->_postHash;
		$out['CURRENCY'] = $params['CURRENCY'];
		$out['TERMINALTYPE'] = 2;
		$out['TRANSACTIONTYPE'] = 7;
		$out['CVV'] = $params['CVV'];

		$this->_normalizedPaymentParams = $out;

		return $out;
	}

	public function XMLToArray($xml,$main_heading = '') {

		$deXml = simplexml_load_string($xml);
		$deJson = json_encode($deXml);
		$xml_array = json_decode($deJson,TRUE);

		if (! empty($main_heading)):
			$returned = $xml_array[$main_heading];
			return $returned;
		else:
			return $xml_array;
		endif;

	}

}