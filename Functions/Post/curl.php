<?php
class Pivotal_Curl{

	public function curlXmlRequest($URL,$xml){

		$ch = curl_init($URL);

		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
		curl_setopt($ch, CURLOPT_POSTFIELDS, "$xml");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		
		$output = curl_exec($ch);

		curl_close($ch);
		
		return $output;
	}

	public function handleCurlError($errno, $message){
		
		switch ($errno) {
			case CURLE_COULDNT_CONNECT:
			case CURLE_COULDNT_RESOLVE_HOST:
			case CURLE_OPERATION_TIMEOUTED:
				$msg = "Could not connect to Pivotal.  Please check your internet connection and try again.  If this problem persists, you should contact the site admin.";
			break;
			case CURLE_SSL_CACERT:
			default:
				$msg = "Error while communicating with Pivotal.  Please check your internet connection and try again.  If this problem persists, you should contact the site admin.";
			}

		$msg .= "\n\n(Network error [errno $errno]: $message)";

		return $msg;
	}
}