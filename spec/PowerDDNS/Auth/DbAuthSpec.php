<?php
namespace spec\PowerDDNS\Auth;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class DbAuthSpec extends ObjectBehavior 
{
	function it_is_initializable() {
		$this -> shouldHaveType('PowerDDNS\Auth\DbAuth');
	}

	public function it_should_authenticate_a_user() {
		//@TODO implement mocks
		$this->authenticate('myuser', 'mypassword')->shouldReturn(true);
		$this->authenticate('myuser', 'notmypassword')->shouldReturn(false);
	}
	
	public function it_should_authorize_a_user_for_domain() {
		$this->authorize('myuser', 'test.example.com')->shouldReturn(true);
		$this->authorize('myuser', 'test2.example.com')->shouldReturn(false);
	}
}
