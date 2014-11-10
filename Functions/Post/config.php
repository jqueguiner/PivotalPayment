<?php
class Pivotal_Config{

 	var $mode = 'test';


	public function __construct($mode){
		//parent::__construct($mode);
		$this->mode = $mode;
	}

	public function readConfigData($configFile){
		$ds = DIRECTORY_SEPARATOR;	
		$json = file_get_contents(dirname(dirname(dirname(__FILE__))).$ds.'Data'.$ds.$configFile.'.json');
		$data = json_decode($json,1);
		return $data;
	}

	public function readMainConfig(){
		$config = $this->readConfigData('MainConfig');
		$config = $config[$this->mode];
		return $config;
	}

	public function readTestCards(){
		$cards = $this->readConfigData('TestCards');
		return $cards;
	}

	public function readTerminals(){
		$terminals = $this->readConfigData('Terminals');
		return $terminals;
	}

	public function readFields(){
		$fields = $this->readConfigData('Fields');
		return $fields;
	}

	public function readCurrencyTerminal($currency = 'USD'){
		
		$currency = strtolower($currency);

		$terminals = $this->readTerminals();
		
		$terminals = $terminals[$this->mode];

		$rterminal = array();

		$multiCurrencyTerminal = array();
		
		foreach($terminals as $terminal):
			if(strtolower($terminal['Currency']) == $currency):
				$rterminal = $terminal;
			endif;

			if(strtolower($terminal['Currency']) == 'MCP'):
				$multiCurrencyTerminal = $terminal;
			endif;


		endforeach;

		if($rterminal==''):
			$rterminal = $multiCurrencyTerminal;
		endif;

		return $rterminal;
	}

	public function readVendorTestCard($vendor = ''){
		
		$vendor = strtolower($vendor);

		$cards = $this->readTestCards();
		
		$rcard = array();
		
		foreach($cards as $card):
			if(strtolower($card['Vendor']) == $vendor):
				$rcard = $card;
			endif;
		endforeach;

		return $rcard;
	}
}