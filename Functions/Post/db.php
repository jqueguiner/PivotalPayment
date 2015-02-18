<?php
Class Pivotal_Db{

	var $_dbConfig = array(
		'server' => 'localhost',
		'login' => 'root',
		'password' => 'root',
		'database' => 'mydb'
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
			'DATETIME' => 'paymentDate',
			'STATUS' => 'paymentStatus'
			)
		);

	var $_txnId = NULL;

	public function __construct($dbConfig = array(), $txnConfig = array(), $orderConfig = array()){
		$this->setDbConfig($dbConfig);
		$this->setTxnConfig($txnConfig);
		$this->setOrderConfig($orderConfig);
	}

	public function setDbConfig($dbConfig = array()){
		$this->_dbConfig = $dbConfig + $this->_dbConfig;
	}

	public function getDbConfig(){
		return $this->_dbConfig;
	}

	public function setTxnConfig($txnConfig = array()){
		$this->_txnConfig = $txnConfig + $this->_txnConfig;
	}

	public function getTxnConfig(){
		return $this->_txnConfig;
	}

	public function getTxnId(){
		return $this->_txnId;
	}

	public function setOrderConfig($orderConfig = array()){
		$this->_orderConfig = $orderConfig + $this->_orderConfig;
	}

	public function getOrderConfig(){
		return $this->_txnConfig;
	}

	private function openConnection(){
		$db = mysql_connect($this->_dbConfig['server'], $this->_dbConfig['login'], $this->_dbConfig['password']);		
		mysql_select_db($this->_dbConfig['database'],$db);
	}
	
	private function executeQuery($sql = ''){
		$req = mysql_query($sql) or die('SQL Error!<br>'.$sql.'<br>'.mysql_error());
		return $req;	
	}

	private function closeConnection(){
		mysql_close(); 
	}

	public function insertTxnIntoDB($txnArrayToSave = array()){
		$this->openConnection();

		$table = $this->_txnConfig['table'];
		$fieldMapping = $this->_txnConfig['fieldMapping'];
		$sqlPrefix = "INSERT INTO $table ";
		$sqlSuffix = ";";
		$fieldsToSave = '';
		$dataToSave = '';

		foreach($txnArrayToSave as $fieldToMap => $valueToSave):
			if(isset($fieldMapping[$fieldToMap])):
				if($fieldToMap == 'CVVRESPONSE' && is_array($valueToSave)):
					$valueToSave = '';
				endif;
				$fieldsToSave .= ', '.$fieldMapping[$fieldToMap];
				$dataToSave .= ", '".$valueToSave."'";
			endif;
		endforeach;

		//removing first commas and space
		$fieldsToSave = "(".substr($fieldsToSave, 2).")";
		$dataToSave = " VALUES (".substr($dataToSave, 2).")";
		
		$sql = $sqlPrefix.$fieldsToSave.$dataToSave.$sqlSuffix;
		
		$this->executeQuery($sql);

		$this->_txnId=mysql_fetch_row($this->executeQuery('SELECT LAST_INSERT_ID();'))[0];

		$this->closeConnection();
	}

	public function updateOrderDB($txnArrayToSave = array()){
		$this->openConnection();

		$table = $this->_orderConfig['table'];
		$fieldMapping = $this->_orderConfig['fieldMapping'];
		
		$sqlPrefix = "UPDATE $table SET ";
		$sqlSuffix = " WHERE ".$fieldMapping['ORDERID']."=".$txnArrayToSave['ORDERID'].";";
		
		$sqlUpdate = $fieldMapping['TXNID'].' = '.$this->_txnId;
		
		foreach($txnArrayToSave as $fielToMap => $valueToSave):
			if(isset($fieldMapping[$fielToMap]) && $fielToMap!='ORDERID'):
				if(is_bool($valueToSave)):
					$valueToSave = $valueToSave ? 1 : 0;
				endif;
				$sqlUpdate .= ', '.$fieldMapping[$fielToMap]."='".$valueToSave."'";
			endif;
		endforeach;
		
		$sql = $sqlPrefix.$sqlUpdate.$sqlSuffix;

		if(!$txnArrayToSave['STATUS']):
			$this->executeQuery($sql);
		endif;
			
		$this->closeConnection();
	}

	public function saveTxnAndOrder($txnArrayToSave = array()){
		$this->insertTxnIntoDB($txnArrayToSave);
		$this->updateOrderDB($txnArrayToSave);
	}
}