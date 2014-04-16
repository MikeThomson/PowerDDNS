<?php

namespace spec\PowerDDNS;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class RecordSpec extends ObjectBehavior
{

	public function it_holds_zone_domain_content_type() {
		$this->beConstructedWith(array(
			'zone' => 'example.com',
			'domain' => 'test.example.com',
			'content' => '8.8.4.4',
			'type' => 'A'
		));

		$this->zone->shouldBe('example.com');
		$this->domain->shouldBe('test.example.com');
		$this->content->shouldBe('8.8.4.4');
		$this->type->shouldBe('A');
}
}
