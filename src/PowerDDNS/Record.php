<?php

namespace PowerDDNS;

/**
 * Class Record
 * Container for information about a DNS record
 * @package PowerDDNS
 */
class Record
{
	/**
	 * Owning Zone
	 * @var string
	 */
	public $zone;
	/**
	 * FQDN entry
	 * @var string
	 */
	public $domain;
	/**
	 * Record type(A, MX, etc)
	 * @var string
	 */
	public $type;
	/**
	 * Value of the record
	 * @var string
	 */
	public $content;

	/**
	 * Build Record from a keyed array of the properties this object can have
	 * @param array $params
	 */
	public function __construct($params = array())
	{
		if(array_key_exists('zone', $params))
			$this->zone = $params['zone'];
		if(array_key_exists('domain', $params))
			$this->domain = $params['domain'];
		if(array_key_exists('type', $params))
			$this->type = $params['type'];
		if(array_key_exists('content', $params))
			$this->content = $params['content'];
	}
}
