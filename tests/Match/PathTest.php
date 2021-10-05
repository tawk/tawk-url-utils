<?php

use PHPUnit\Framework\TestCase;
use Tawk\Match\Path;

/**
 * @coversDefaultClass \Tawk\Path
 */
class PathTest extends TestCase {
	/**
	 * @test
	 * @group get_wildcard
	 * @covers ::get_wildcard()
	 */
	public function should_get_wildcard() {
		$this->assertEquals(Path::get_wildcard(), '*');
	}

	//--------------------------------------------------------------------------------------------------

	/**
	 * @test
	 * @group match_path
	 * @covers ::match()
	 */
	public function should_match_similar_path_and_pattern() {
		$path = '/path/to/somewhere';
		$pattern = ['/path/to/somewhere'];

		$this->assertTrue(Path::match($path, $pattern));
	}

	/**
	 * @test
	 * @group match_path
	 * @covers ::match()
	 */
	public function should_match_if_path_or_pattern_has_no_leading_slash() {
		$path = '/path/to/somewhere';
		$pattern = ['path/to/somewhere'];

		$this->assertTrue(Path::match($path, $pattern));
	}

	/**
	 * @test
	 * @group match_path
	 * @covers ::match()
	 */
	public function should_match_path_with_wildcard_at_pattern_start() {
		$path = '/this/path/should/lead/to/somewhere';
		$pattern = ['*/to/somewhere'];

		$this->assertTrue(Path::match($path, $pattern));
	}

	/**
	 * @test
	 * @group match_path
	 * @covers ::match()
	 */
	public function should_match_path_with_wildcard_at_pattern_end() {
		$path = '/this/path/should/lead/to/somewhere';
		$pattern = ['/this/*'];

		$this->assertTrue(Path::match($path, $pattern));
	}

	/**
	 * @test
	 * @group match_path
	 * @covers ::match()
	 */
	public function should_match_path_with_wildcard_at_pattern_middle() {
		$path = '/this/path/should/lead/to/somewhere';
		$pattern = ['/this/path/*/lead/to/somewhere'];

		$this->assertTrue(Path::match($path, $pattern));
	}

	/**
	 * @test
	 * @group match_path
	 * @covers ::match()
	 */
	public function should_match_path_with_multiple_succeeding_wildcards_at_pattern_middle() {
		$path = '/this/path/should/lead/to/somewhere';
		$pattern = ['/this/path/*/*/*/somewhere'];

		$this->assertTrue(Path::match($path, $pattern));
	}

	/**
	 * @test
	 * @group match_path
	 * @covers ::match()
	 */
	public function should_match_multiple_pattern_for_path() {
		$path = '/path/to/somewhere';
		$pattern = [
			'/path/to/somewhere',
			'/path/to/*',
			'/path/*',
			'*/to/somewhere',
			'*/somewhere',
			'*'
		];

		$this->assertTrue(Path::match($path, $pattern));
	}

	/**
	 * @test
	 * @group match_path
	 * @covers ::match()
	 */
	public function should_not_match_path_if_pattern_chunk_differs_from_path() {
		$path = '/this/path/should/lead/to/somewhere';
		$pattern = ['/this/path/should/different/to/somewhere'];

		$this->assertFalse(Path::match($path, $pattern));
	}

	/**
	 * @test
	 * @group match_path
	 * @covers ::match()
	 */
	public function should_not_match_path_if_current_path_length_is_less_than_pattern_path() {
		$path = '/this/path/should/lead/to';
		$pattern = ['/this/path/should/lead/to/somewhere'];

		$this->assertFalse(Path::match($path, $pattern));
	}

	/**
	 * @test
	 * @group match_path
	 * @covers ::match()
	 */
	public function should_not_match_path_if_current_path_length_is_less_than_pattern_path_with_wildcard() {
		$path = '/this/path/should/lead/to';
		$pattern = ['/this/path/should/lead/to/*'];

		$this->assertFalse(Path::match($path, $pattern));
	}

	/**
	 * @test
	 * @group match_path
	 * @covers ::match()
	 */
	public function should_not_match_path_if_current_path_has_nothing_similar_to_pattern() {
		$path = '/this/path/should/lead/to';
		$pattern = ['*/somewhere'];

		$this->assertFalse(Path::match($path, $pattern));
	}

	/**
	 * @test
	 * @group match_path
	 * @covers ::match()
	 */
	public function should_not_match_multiple_pattern_for_path_if_one_differs() {
		$path = '/path/to/somewhere';
		$pattern = [
			'/path/to/somewhere',
			'/path/to/*',
			'/path/nowhere',
			'*/to/somewhere',
			'*/somewhere',
			'*'
		];

		$this->assertFalse(Path::match($path, $pattern));
	}
}
