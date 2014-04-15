<?php
namespace spec\PowerDDNS\Auth;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class DbAuthSpec extends ObjectBehavior 
{
	public function let() {
		
	}
	
	function it_is_initializable_with_PDO_object(\PDO $pdo) {
		$this->beConstructedWith($pdo);
		$this -> shouldHaveType('PowerDDNS\Auth\DbAuth');
	}

	public function it_should_authenticate_a_user(\PDO $pdo, \PDOStatement $statement) {
		//@TODO Deeper testing, make sure the query is correct
		
		$statement->execute()->willReturn(true);
		$statement->fetch()->willReturn(array(1));
		$statement->bindParam(Argument::any(), Argument::any())->willReturn($statement);
		$pdo->prepare(Argument::any())->willReturn($statement);
		$this->beConstructedWith($pdo);
		$this->authenticate('myuser', 'mypassword')->shouldReturn(true);
	}
	
	public function it_should_not_authenticate_an_invalid_user(\PDO $pdo, \PDOStatement $statement) {
		//@TODO Deeper testing, make sure the query is correct
		
		$statement->execute()->willReturn(true);
		$statement->fetch()->willReturn(array(0));
		$statement->bindParam(Argument::any(), Argument::any())->willReturn($statement);
		$pdo->prepare(Argument::any())->willReturn($statement);
		$this->beConstructedWith($pdo);
		$this->authenticate('myuser', 'notmypassword')->shouldReturn(false);
	}
	
	public function it_should_authorize_a_user_for_domain() {
		$this->authorize('myuser', 'test.example.com')->shouldReturn(true);
	}
}
