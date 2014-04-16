<?php

namespace spec\PowerDDNS;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class PowerDDNSSpec extends ObjectBehavior
{
	function it_is_initializable()
	{
		$this->shouldHaveType('PowerDDNS\PowerDDNS');
	}

	public function it_should_take_dependencies_for_auth_and_backend() {

	}

	public function it_should_provide_a_routeable_endpoint() {

	}

	public function it_should_allow_domain_updates() {

	}

	public function it_should_reject_unauthorized_changes() {

	}
}
