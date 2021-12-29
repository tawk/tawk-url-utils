<?php

namespace Tawk\Tests\Enums;

use PHPUnit\Framework\TestCase;
use Tawk\Enums\WildcardLocation;

class WildcardLocationTest extends TestCase {
	/**
	 * @test
	 * @group enum_wildcard_location
	 * @covers ::START
	 * @small
	 */
	public function check_wildcard_location_start() {
		$this->assertEquals(WildcardLocation::START, 'start');
	}
	/**
	 * @test
	 * @group enum_wildcard_location
	 * @covers ::END
	 * @small
	 */
	public function check_wildcard_location_end() {
		$this->assertEquals(WildcardLocation::END, 'end');
	}
	/**
	 * @test
	 * @group enum_wildcard_location
	 * @covers ::MIDDLE
	 * @small
	 */
	public function check_wildcard_location_middle() {
		$this->assertEquals(WildcardLocation::MIDDLE, 'middle');
	}
	/**
	 * @test
	 * @group enum_wildcard_location
	 * @covers ::NONE
	 * @small
	 */
	public function check_wildcard_location_none() {
		$this->assertEquals(WildcardLocation::NONE, 'none');
	}
}
