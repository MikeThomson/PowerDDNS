<?php

namespace spec\PowerDDNS;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ResponseSpec extends ObjectBehavior
{
	function it_is_initializable()
	{
		$this->shouldHaveType('PowerDDNS\Response');
	}

}
