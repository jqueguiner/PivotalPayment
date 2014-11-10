<?php

if (!function_exists('curl_init')) {
	throw new Exception('Pivotal Payment needs the CURL PHP extension (php_curl).');
}

if (!function_exists('xmlrpc_decode')) {
	throw new Exception('Pivotal Payment needs the XML PHP extension (php_xmlrpc).');
}

if (!function_exists('mb_detect_encoding')) {
  throw new Exception('Pivotal needs the Multibyte String PHP extension.');
}
