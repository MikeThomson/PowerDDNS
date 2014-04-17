<?php
/**
 * Created by PhpStorm.
 * User: Mike
 * Date: 4/16/14
 * Time: 4:20 PM
 */

namespace PowerDDNS;


/**
 * Class Response
 * Container class for all of the repsonse codes in the DynDNS protocol
 * @package PowerDDNS
 */
class Response {
	const BADAUTH = 'badauth';
	const NOT_DONATOR = '!donator';
	const GOOD = 'good';
	const NOCHG = 'nochg';
	const NOT_FQDN = 'notfqdn';
	const NO_HOST = 'nohost';
	const NUM_HOST = 'numhost';
	const ABUSE = 'abuse';
	const BAD_AGENT = 'badagent';
	const GOOD_LOCALHOST = 'good 127.0.0.1';
	const DNS_ERROR = 'dnserr';
	const DOWN = '911';
} 