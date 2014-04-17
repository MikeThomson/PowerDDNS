<?php

namespace PowerDDNS\Helper;

use PowerDDNS\Response;

/**
 * Class EndpointHelper
 * A helper class mainly to interface with the PHP superglobals and print response codes / headers
 * @package PowerDDNS\Helper
 */
class EndpointHelper
{
	const HTTP_GET = 'GET';
	const HTTP_POST = 'POST';
	const HTTP_UNKNOWN = 'UNKNOWN';

	/**
	 * Get the HTTP(GET, POST, etc) verb used to make this request
	 * @return string
	 */
	public static function getVerb()
	{
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

	/**
	 * Whether or not the request was made with BasicAuth credentials supplied
	 * @return bool
	 */
	public static function hasAuth()
	{
		return isset($_SERVER['PHP_AUTH_USER']);
	}

	/**
	 * Accessor for the username provided to BasicAuth
	 * @return string username
	 */
	public static function getUser()
	{
		return $_SERVER['PHP_AUTH_USER'];
	}

	/**
	 * Accessor for the password provided to BasicAuth
	 * @return string password
	 */
	public static function getPass() {
		return $_SERVER['PHP_AUTH_PW'];
	}

	/**
	 * Prints out a response (with modified headers if necessary) based on the DynDNS protocol code result and exits
	 * @param $code the DynDNS reponse code to send
	 */
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

	/**
	 * Sets HTTP headers to ask the client for BasicAuth and exits
	 */
	public static function requestAuth() {
		header('WWW-Authenticate: Basic realm="DynDNS API Access"');
		header('HTTP/1.0 401 Unauthorized');
		echo 'badauth';
		die;
	}

	/**
	 * Gets the domains the client requested be updated from the 'hostname' request variable
	 * @return array domains requested to update
	 */
	public static function getDomains() {
		$ret = array();
		if (array_key_exists('hostname', $_REQUEST) && ($_REQUEST['hostname'] != '')) {
			$ret = explode(',', strtolower($_REQUEST['hostname']));
		}
		return $ret;
	}

	/**
	 * Returns the IP to use for the update, either provided by the 'myip' request variable or the IP the connection
	 * is coming from
	 * @return string ip address
	 */
	public static function getIp() {
		$ip = $_REQUEST['myip'];
		if(filter_var($ip, FILTER_VALIDATE_IP)) {
			return $ip;
		} else {
			return $_SERVER['REMOTE_ADDR'];
		}
	}
}
