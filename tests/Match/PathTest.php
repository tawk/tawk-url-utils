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
		$pattern = ['path/to/somewhere/'];

		$this->assertTrue(Path::match($path, $pattern));
	}

	/**
	 * @test
	 * @group match_path
	 * @covers ::match()
	 */
	public function should_match_path_and_pattern_with_wildcard_at_the_start() {
		$path = '/this/path/should/lead/to/somewhere';
		$pattern = ['*/to/somewhere'];

		$this->assertTrue(Path::match($path, $pattern));
	}

	/**
	 * @test
	 * @group match_path
	 * @covers ::match()
	 */
	public function should_match_path_and_pattern_with_wildcard_at_the_end() {
		$path = '/this/path/should/lead/to/somewhere';
		$pattern = ['/this/*'];

		$this->assertTrue(Path::match($path, $pattern));
	}

	/**
	 * @test
	 * @group match_path
	 * @covers ::match()
	 */
	public function should_match_path_and_pattern_with_wildcard_at_the_middle() {
		$path = '/this/path/should/lead/to/somewhere';
		$pattern = ['/this/path/*/lead/to/somewhere'];

		$this->assertTrue(Path::match($path, $pattern));
	}

	/**
	 * @test
	 * @group match_path
	 * @covers ::match()
	 */
	public function should_match_path_and_pattern_with_multiple_succeeding_wildcards_at_the_middle() {
		$path = '/this/path/should/lead/to/somewhere';
		$pattern = ['/this/path/*/*/*/somewhere'];

		$this->assertTrue(Path::match($path, $pattern));
	}

	/**
	 * @test
	 * @group match_path
	 * @covers ::match()
	 */
	public function should_match_path_with_multiple_similar_Patterns_with_wildcards() {
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
	public function should_match_path_with_multiple_patterns_that_has_one_similar_pattern() {
		$path = '/path/to/somewhere';
		$pattern = [
			'/path/to/elsewhere',
			'/path/on/*',
			'/path/nowhere',
			'*/to/somewhere', // valid
			'*/others'
		];

		$this->assertTrue(Path::match($path, $pattern));
	}

	/**
	 * @test
	 * @group match_path
	 * @covers ::match()
	 */
	public function should_not_match_path_with_different_pattern() {
		$path = '/this/path/should/lead/to/somewhere';
		$pattern = ['/this/path/should/different/to/somewhere'];

		$this->assertFalse(Path::match($path, $pattern));
	}

	/**
	 * @test
	 * @group match_path
	 * @covers ::match()
	 */
	public function should_not_match_path_with_a_longer_pattern() {
		$path = '/this/path/should/lead/to';
		$pattern = ['/this/path/should/lead/to/somewhere'];

		$this->assertFalse(Path::match($path, $pattern));
	}

	/**
	 * @test
	 * @group match_path
	 * @covers ::match()
	 */
	public function it_should_not_match_path_with_similar_pattern_but_has_extra_wildcard_at_the_end() {
		$path = '/this/path/should/lead/to';
		$pattern = ['/this/path/should/lead/to/*'];

		$this->assertFalse(Path::match($path, $pattern));
	}

	/**
	 * @test
	 * @group match_path
	 * @covers ::match()
	 */
	public function should_not_match_path_with_multiple_patterns_that_are_all_different() {
		$path = '/path/to/somewhere';
		$pattern = [
			'/path/to/elsewhere',
			'/path/on/*',
			'/path/nowhere',
			'*/is/somewhere',
			'*/others'
		];

		$this->assertFalse(Path::match($path, $pattern));
	}
}
