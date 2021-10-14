<?php

use PHPUnit\Framework\TestCase;
use Tawk\Helper;

/**
 * @coversDefaultClass \Tawk\Helper
 */
class HelperTest extends TestCase {
	/**
	 * @test
	 * @group clean_url
	 * @covers ::clean_url()
	 */
	public function should_remove_leading_spaces() {
		$this->assertEquals(Helper::clean_url(' path/to/somewhere'), 'path/to/somewhere');
	}

	/**
	 * @test
	 * @group clean_url
	 * @covers ::clean_url()
	 */
	public function should_remove_trailing_spaces() {
		$this->assertEquals(Helper::clean_url('path/to/somewhere '), 'path/to/somewhere');
	}

	/**
	 * @test
	 * @group clean_url
	 * @covers ::clean_url()
	 */
	public function should_both_leading_and_trailing_spaces() {
		$this->assertEquals(Helper::clean_url(' path/to/somewhere '), 'path/to/somewhere');
	}

	/**
	 * @test
	 * @group clean_url
	 * @covers ::clean_url()
	 */
	public function should_decode_url_encoded_strings() {
		$this->assertEquals(Helper::clean_url(urlencode(' path/to/somewhere ')), 'path/to/somewhere');
	}
}
