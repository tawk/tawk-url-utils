<?php

use PHPUnit\Framework\TestCase;
use Tawk\Modules\PathPatternMatcher;

/**
 * @coversDefaultClass \Tawk\Modules\PathPatternMatcher
 */
class PathMatchTest extends TestCase {
	/**
	 * @test
	 * @group match_path
	 * @covers ::match()
	 */
	public function should_match_similar_path_and_pattern() {
		$path_chunks = array('path', 'to', 'somewhere');
		$pattern_chunks = array(
			array('path', 'to', 'somewhere')
		);

		$this->assertTrue(PathPatternMatcher::match($path_chunks, $pattern_chunks));
	}

	/**
	 * @test
	 * @group match_path
	 * @covers ::match()
	 */
	public function should_match_path_and_pattern_with_wildcard_at_the_start() {
		$path_chunks = array('path', 'to', 'somewhere');
		$pattern_chunks = array(
			array('*', 'to', 'somewhere')
		);

		$this->assertTrue(PathPatternMatcher::match($path_chunks, $pattern_chunks));
	}

	/**
	 * @test
	 * @group match_path
	 * @covers ::match()
	 */
	public function should_match_path_and_pattern_with_wildcard_at_the_end() {
		$path_chunks = array('path', 'to', 'somewhere');
		$pattern_chunks = array(
			array('path', 'to', '*')
		);

		$this->assertTrue(PathPatternMatcher::match($path_chunks, $pattern_chunks));
	}

	/**
	 * @test
	 * @group match_path
	 * @covers ::match()
	 */
	public function should_match_path_and_pattern_with_wildcard_at_the_middle() {
		$path_chunks = array('path', 'to', 'somewhere');
		$pattern_chunks = array(
			array('path', '*', 'somewhere')
		);

		$this->assertTrue(PathPatternMatcher::match($path_chunks, $pattern_chunks));
	}

	/**
	 * @test
	 * @group match_path
	 * @covers ::match()
	 */
	public function should_match_path_and_pattern_with_multiple_succeeding_wildcards_at_the_middle() {
		$path_chunks = array('path', 'lead', 'to', 'somewhere');
		$pattern_chunks = array(
			array('path', '*', '*', 'somewhere')
		);

		$this->assertTrue(PathPatternMatcher::match($path_chunks, $pattern_chunks));
	}

	/**
	 * @test
	 * @group match_path
	 * @covers ::match()
	 */
	public function should_match_path_with_multiple_similar_Patterns_with_wildcards() {
		$path_chunks = array('path', 'to', 'somewhere');
		$pattern_chunks = array(
			array('path', 'to', 'somewhere'),
			array('path', 'to', '*'),
			array('*', 'somewhere'),
		);

		$this->assertTrue(PathPatternMatcher::match($path_chunks, $pattern_chunks));
	}

	/**
	 * @test
	 * @group match_path
	 * @covers ::match()
	 */
	public function should_match_path_with_multiple_patterns_that_has_one_similar_pattern() {
		$path_chunks = array('path', 'to', 'somewhere');
		$pattern_chunks = array(
			array('path', 'to', 'elsewhere'),
			array('path', 'on', '*'),
			array('*', 'to', 'somewhere'),
		);

		$this->assertTrue(PathPatternMatcher::match($path_chunks, $pattern_chunks));
	}

	/**
	 * @test
	 * @group match_path
	 * @covers ::match()
	 */
	public function should_not_match_path_contains_empty_string_and_pattern_contains_leading_wildcard() {
		$path_chunks = array('');
		$pattern_chunks = array(
			array('*', 'to', 'somewhere'),
		);

		$this->assertFalse(PathPatternMatcher::match($path_chunks, $pattern_chunks));
	}

	/**
	 * @test
	 * @group match_path
	 * @covers ::match()
	 */
	public function should_not_match_path_with_different_pattern() {
		$path_chunks = array('path', 'to', 'somewhere');
		$pattern_chunks = array(
			array('path', 'to', 'different'),
		);

		$this->assertFalse(PathPatternMatcher::match($path_chunks, $pattern_chunks));
	}

	/**
	 * @test
	 * @group match_path
	 * @covers ::match()
	 */
	public function should_not_match_path_with_a_longer_pattern() {
		$path_chunks = array('path', 'to', 'somewhere');
		$pattern_chunks = array(
			array('path', 'to', 'somewhere', 'longer'),
		);

		$this->assertFalse(PathPatternMatcher::match($path_chunks, $pattern_chunks));
	}

	/**
	 * @test
	 * @group match_path
	 * @covers ::match()
	 */
	public function should_not_match_path_with_similar_pattern_but_has_extra_wildcard_at_the_end() {
		$path_chunks = array('path', 'to', 'somewhere');
		$pattern_chunks = array(
			array('path', 'to', 'somehwhere', '*'),
		);

		$this->assertFalse(PathPatternMatcher::match($path_chunks, $pattern_chunks));
	}

	/**
	 * @test
	 * @group match_path
	 * @covers ::match()
	 */
	public function should_not_match_path_with_multiple_patterns_that_are_all_different() {
		$path_chunks = array('path', 'to', 'somewhere');
		$pattern_chunks = array(
			array('path', 'to', 'elsewhere'),
			array('*', 'is', 'somewhere'),
			array('*', 'others'),
		);

		$this->assertFalse(PathPatternMatcher::match($path_chunks, $pattern_chunks));
	}
}
