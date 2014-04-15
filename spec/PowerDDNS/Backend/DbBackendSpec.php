<?php

namespace spec\PowerDDNS\Backend;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class DbBackendSpec extends ObjectBehavior
{
	function it_is_initializable()
	{
		$this->shouldHaveType('PowerDDNS\Backend\DbBackend');
	}
	
	public function it_should_update_records() {
		// @TODO mocks
		$this->updateRecord('test.example.com', '8.8.4.4');
	}
	
	public function it_should_update_domain_serial() {
		$this->updateSerial();
	}
	
}
