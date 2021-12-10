<?php

use PHPUnit\Framework\TestCase;
use Tawk\Enums\WildcardLocation;

class WildcardLocationTest extends TestCase {
	/**
	 * @test
	 * @group enum_wildcard_location
	 */
	public function check_wildcard_location_start() {
		$this->assertEquals(WildcardLocation::START, 'start');
	}
	/**
	 * @test
	 * @group enum_wildcard_location
	 */
	public function check_wildcard_location_end() {
		$this->assertEquals(WildcardLocation::END, 'end');
	}
	/**
	 * @test
	 * @group enum_wildcard_location
	 */
	public function check_wildcard_location_middle() {
		$this->assertEquals(WildcardLocation::MIDDLE, 'middle');
	}
	/**
	 * @test
	 * @group enum_wildcard_location
	 */
	public function check_wildcard_location_none() {
		$this->assertEquals(WildcardLocation::NONE, 'none');
	}
}
