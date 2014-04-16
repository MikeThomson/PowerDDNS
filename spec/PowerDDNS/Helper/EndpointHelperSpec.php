<?php

namespace spec\PowerDDNS\Helper;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class EndpointHelperSpec extends ObjectBehavior
{

	public function it_can_check_http_verb() {
		$this::getVerb()->shouldReturn($this::HTTP_GET);
	}

	public function it_can_ensure_auth_is_passed() {
		$this::hasAuth()->shouldReturn(false);
	}

	public function it_can_get_passed_auth() {
		$this::getUser()->shouldReturn('mike');
		$this::getPass()->shouldReturn('password');
	}

	public function it_can_terminate_for_bad_verb() {
		$this::sendResponse(\PowerDDNS\Response::BAD_AGENT);
	}

	public function it_can_terminate_when_auth_not_provided() {

	}

	public function it_can_terminate_with_code() {

	}

	public function it_can_terminate_with_invalid_params() {

	}

}
