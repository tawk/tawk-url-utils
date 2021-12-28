<?php

namespace Tawk\Tests\Helpers\CommonTest;

use PHPUnit\Framework\TestCase;
use Tawk\Helpers\Common;

/**
 * @coversDefaultClass \Tawk\Helpers\Common
 */
class StrStartsWith extends TestCase {
	/**
	 * @test
	 * @group text_starts_with
	 * @covers ::text_starts_with
	 * @small
	 */
	public function should_be_true_if_provided_text_starts_with() {
		$this->assertTrue(Common::text_starts_with('something', 'some'));
	}

	/**
	 * @test
	 * @group text_starts_with
	 * @covers ::text_starts_with
	 * @small
	 */
	public function should_be_false_if_provided_text_does_not_starts_with() {
		$this->assertFalse(Common::text_starts_with('something', 'none'));
	}
}


/**
 * @coversDefaultClass \Tawk\Helpers\Common
 */
class StrEndsWith extends TestCase {
	/**
	 * @test
	 * @group text_ends_with
	 * @covers ::text_ends_with
	 * @small
	 */
	public function should_be_true_if_provided_text_ends_with() {
		$this->assertTrue(Common::text_ends_with('something', 'thing'));
	}

	/**
	 * @test
	 * @group text_ends_with
	 * @covers ::text_ends_with
	 * @small
	 */
	public function should_be_false_if_provided_text_does_not_ends_with() {
		$this->assertFalse(Common::text_ends_with('something', 'none'));
	}
}

