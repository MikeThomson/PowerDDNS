<?php

namespace PowerDDNS;

class Record
{
	public $zone;
	public $domain;
	public $type;
	public $content;

	public function __construct($params)
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
