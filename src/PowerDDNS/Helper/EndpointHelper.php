<?php

namespace PowerDDNS\Helper;

use PowerDDNS\Response;

class EndpointHelper
{
	const HTTP_GET = 'GET';
	const HTTP_POST = 'POST';
	const HTTP_UNKNOWN = 'UNKNOWN';

	public static function getVerb()
	{
		// TODO: write logic here
		$verb = self::HTTP_UNKNOWN;
		switch($_SERVER['REQUEST_METHOD']) {
			case 'GET':
				$verb = self::HTTP_GET;
				break;
			case 'POST':
				$verb = self::HTTP_POST;
				break;
		}
		return $verb;
	}

	public static function hasAuth()
	{
		return isset($_SERVER['PHP_AUTH_USER']);
	}

	public static function getUser()
	{
		return $_SERVER['PHP_AUTH_USER'];
	}

	public static function getPass() {
		return $_SERVER['PHP_AUTH_PW'];
	}

	public static function sendResponse($code)
	{
		switch($code) {
			case Response::BADAUTH :
				header('HTTP/1.0 403 Forbidden');
				echo $code;
				die;
			case Response::BAD_AGENT :
				header('HTTP/1.0 405 Method Not Allowed');
				echo $code;
				die;
			default:
				echo $code;
				die;
		}
	}

	public static function requestAuth() {
		header('WWW-Authenticate: Basic realm="DynDNS API Access"');
		header('HTTP/1.0 401 Unauthorized');
		echo 'badauth';
		die;
	}

	public static function getDomains() {
		$ret = array();
		if (array_key_exists('hostname', $_REQUEST) && ($_REQUEST['hostname'] != '')) {
			$ret = explode(',', strtolower($_REQUEST['hostname']));
		}
		return $ret;
	}

	public static function getIp() {
		$ip = $_REQUEST['myip'];
		if(filter_var($ip, FILTER_VALIDATE_IP)) {
			return $ip;
		} else {
			return $_SERVER['REMOTE_ADDR'];
		}
	}
}
