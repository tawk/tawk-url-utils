<?php

namespace Tawk\Tests\Modules;

use PHPUnit\Framework\TestCase;
use Tawk\Models\PathPattern;
use Tawk\Modules\PathPatternMatcher;

/**
 * @coversDefaultClass \Tawk\Modules\PathPatternMatcher
 */
class PathMatchTest extends TestCase {
	/**
	 * @test
	 * @group match_path_no_wildcard
	 * @covers ::match
	 * @small
	 */
	public function should_match_similar_path_and_pattern() {
		$path_chunks = array('path', 'to', 'somewhere');
		$pattern_chunks = array(
			PathPattern::create_instance_from_path('/path/to/somewhere')
		);

		$this->assertTrue(PathPatternMatcher::match($path_chunks, $pattern_chunks));
	}

	/**
	 * @test
	 * @group match_path_no_wildcard
	 * @covers ::match
	 * @small
	 */
	public function should_match_both_empty_path_and_pattern() {
		$path_chunks = array();
		$pattern_chunks = array(
			PathPattern::create_instance_from_path('')
		);

		$this->assertTrue(PathPatternMatcher::match($path_chunks, $pattern_chunks));
	}

	/**
	 * @test
	 * @group match_path_no_wildcard
	 * @covers ::match
	 * @small
	 */
	public function should_not_match_different_path_and_pattern() {
		$path_chunks = array('path', 'to', 'somewhere');
		$pattern_chunks = array(
			PathPattern::create_instance_from_path('/path/different/path')
		);

		$this->assertFalse(PathPatternMatcher::match($path_chunks, $pattern_chunks));
	}

	/**
	 * @test
	 * @group match_path_no_wildcard
	 * @covers ::match
	 * @small
	 */
	public function should_not_match_path_longer_than_pattern() {
		$path_chunks = array('path', 'to', 'somewhere', 'longer');
		$pattern_chunks = array(
			PathPattern::create_instance_from_path('/path/to/somewhere')
		);

		$this->assertFalse(PathPatternMatcher::match($path_chunks, $pattern_chunks));
	}

	/**
	 * @test
	 * @group match_path_no_wildcard
	 * @covers ::match
	 * @small
	 */
	public function should_not_match_path_shorter_than_pattern() {
		$path_chunks = array('path', 'to', 'somewhere');
		$pattern_chunks = array(
			PathPattern::create_instance_from_path('/path/to/somewhere/longer')
		);

		$this->assertFalse(PathPatternMatcher::match($path_chunks, $pattern_chunks));
	}

	/**
	 * @test
	 * @group match_path_single_wildcard
	 * @covers ::match
	 * @small
	 */
	public function should_match_path_with_single_wildcard_pattern() {
		$path_chunks = array('path', 'to', 'somewhere');
		$pattern_chunks = array(
			PathPattern::create_instance_from_path('*')
		);

		$this->assertTrue(PathPatternMatcher::match($path_chunks, $pattern_chunks));
	}

	/**
	 * @test
	 * @group test
	 * @covers ::match
	 * @small
	 */
	public function should_match_empty_path_with_single_wildcard_pattern() {
		$path_chunks = array();
		$pattern_chunks = array(
			PathPattern::create_instance_from_path('*')
		);

		$this->assertTrue(PathPatternMatcher::match($path_chunks, $pattern_chunks));
	}

	/**
	 * @test
	 * @group match_path_wildcard_start
	 * @covers ::match()
	 * @small
	 */
	public function should_match_path_with_leading_wildcard_pattern() {
		$path_chunks = array('path', 'to', 'somewhere');
		$pattern_chunks = array(
			PathPattern::create_instance_from_path('*/to/somewhere')
		);

		$this->assertTrue(PathPatternMatcher::match($path_chunks, $pattern_chunks));
	}

	/**
	 * @test
	 * @group match_path_wildcard_start
	 * @covers ::match()
	 * @small
	 */
	public function should_match_path_with_leading_wildcard_pattern_that_covers_multiple_path_chunks() {
		$path_chunks = array('path', 'to', 'somewhere', 'longer');
		$pattern_chunks = array(
			PathPattern::create_instance_from_path('*/longer')
		);

		$this->assertTrue(PathPatternMatcher::match($path_chunks, $pattern_chunks));
	}

	/**
	 * @test
	 * @group match_path_wildcard_start
	 * @covers ::match()
	 * @small
	 */
	public function should_not_match_path_longer_than_the_pattern_with_leading_wildcard() {
		$path_chunks = array('path', 'to', 'somewhere', 'longer');
		$pattern_chunks = array(
			PathPattern::create_instance_from_path('*/to/somewhere')
		);

		$this->assertFalse(PathPatternMatcher::match($path_chunks, $pattern_chunks));
	}

	/**
	 * @test
	 * @group match_path_wildcard_start
	 * @covers ::match()
	 * @small
	 */
	public function should_not_match_shorter_path_with_leading_wildcard_pattern_that_has_different_ending_path() {
		$path_chunks = array('path', 'to', 'somewhere');
		$pattern_chunks = array(
			PathPattern::create_instance_from_path('*/to/somewhere/longer')
		);

		$this->assertFalse(PathPatternMatcher::match($path_chunks, $pattern_chunks));
	}

	/**
	 * @test
	 * @group match_path_wildcard_start
	 * @covers ::match()
	 * @small
	 */
	public function should_not_match_different_ending_path_with_leading_wildcard_pattern_that_covers_multiple_path_chunks() {
		$path_chunks = array('path', 'to', 'somewhere', 'longer', 'and', 'different');
		$pattern_chunks = array(
			PathPattern::create_instance_from_path('*/longer')
		);

		$this->assertFalse(PathPatternMatcher::match($path_chunks, $pattern_chunks));
	}

	/**
	 * @test
	 * @group match_path_wildcard_start
	 * @covers ::match()
	 * @small
	 */
	public function should_not_match_path_with_leading_wildcard_pattern_that_covers_multiple_path_chunks_and_has_different_ending_path() {
		$path_chunks = array('path', 'to', 'somewhere', 'longer');
		$pattern_chunks = array(
			PathPattern::create_instance_from_path('*/longer/and/different')
		);

		$this->assertFalse(PathPatternMatcher::match($path_chunks, $pattern_chunks));
	}

	/**
	 * @test
	 * @group match_path_wildcard_start
	 * @covers ::match()
	 * @small
	 */
	public function should_not_match_path_with_leading_wildcard_pattern_that_has_no_similarities() {
		$path_chunks = array('path', 'to', 'somewhere', 'longer', 'and', 'different');
		$pattern_chunks = array(
			PathPattern::create_instance_from_path('*/longer')
		);

		$this->assertFalse(PathPatternMatcher::match($path_chunks, $pattern_chunks));
	}

	/**
	 * @test
	 * @group match_path_wildcard_start
	 * @covers ::match()
	 * @small
	 */
	public function should_not_match_empty_path_with_leading_wildcard_pattern() {
		$path_chunks = array();
		$pattern_chunks = array(
			PathPattern::create_instance_from_path('*/to/somewhere')
		);

		$this->assertFalse(PathPatternMatcher::match($path_chunks, $pattern_chunks));
	}

	/**
	 * @test
	 * @group match_path_wildcard_end
	 * @covers ::match()
	 * @small
	 */
	public function should_match_path_and_trailing_wildcard_pattern() {
		$path_chunks = array('path', 'to', 'somewhere');
		$pattern_chunks = array(
			PathPattern::create_instance_from_path('/path/to/*')
		);

		$this->assertTrue(PathPatternMatcher::match($path_chunks, $pattern_chunks));
	}

	/**
	 * @test
	 * @group match_path_wildcard_end
	 * @covers ::match()
	 * @small
	 */
	public function should_match_path_and_trailing_wildcard_pattern_that_covers_multiple_path_chunks() {
		$path_chunks = array('path', 'to', 'somewhere');
		$pattern_chunks = array(
			PathPattern::create_instance_from_path('/path/*')
		);

		$this->assertTrue(PathPatternMatcher::match($path_chunks, $pattern_chunks));
	}

	/**
	 * @test
	 * @group match_path_wildcard_end
	 * @covers ::match()
	 * @small
	 */
	public function should_not_match_path_and_trailing_wildcard_pattern_that_contains_the_full_path() {
		$path_chunks = array('path', 'to', 'somewhere');
		$pattern_chunks = array(
			PathPattern::create_instance_from_path('/path/to/somewhere/*')
		);

		$this->assertFalse(PathPatternMatcher::match($path_chunks, $pattern_chunks));
	}

	/**
	 * @test
	 * @group match_path_wildcard_end
	 * @covers ::match()
	 * @small
	 */
	public function should_not_match_path_with_trailing_wildcard_pattern_that_has_different_starting_path() {
		$path_chunks = array('path', 'to', 'somewhere');
		$pattern_chunks = array(
			PathPattern::create_instance_from_path('/different/path/*')
		);

		$this->assertFalse(PathPatternMatcher::match($path_chunks, $pattern_chunks));
	}

	/**
	 * @test
	 * @group match_path_wildcard_end
	 * @covers ::match()
	 * @small
	 */
	public function should_not_match_empty_path_with_trailing_wildcard_pattern() {
		$path_chunks = array();
		$pattern_chunks = array(
			PathPattern::create_instance_from_path('/different/path/*')
		);

		$this->assertFalse(PathPatternMatcher::match($path_chunks, $pattern_chunks));
	}

	/**
	 * @test
	 * @group match_path_wildcard_end
	 * @covers ::match()
	 * @small
	 */
	public function should_not_match_path_with_trailing_wildcard_pattern_that_contains_the_full_path_but_has_additional_path() {
		$path_chunks = array('path', 'to', 'somewhere');
		$pattern_chunks = array(
			PathPattern::create_instance_from_path('/path/to/somewhere/longer/*')
		);

		$this->assertFalse(PathPatternMatcher::match($path_chunks, $pattern_chunks));
	}

	/**
	 * @test
	 * @group match_path_wildcard_middle
	 * @covers ::match()
	 * @small
	 */
	public function should_match_path_with_middle_wildcard_pattern() {
		$path_chunks = array('path', 'to', 'somewhere');
		$pattern_chunks = array(
			PathPattern::create_instance_from_path('/path/*/somewhere')
		);

		$this->assertTrue(PathPatternMatcher::match($path_chunks, $pattern_chunks));
	}

	/**
	 * @test
	 * @group match_path_wildcard_middle
	 * @covers ::match()
	 * @small
	 */
	public function should_match_path_with_multiple_middle_wildcard_pattern() {
		$path_chunks = array('path', 'to', 'some', 'different', 'place');
		$pattern_chunks = array(
			PathPattern::create_instance_from_path('/path/*/some/*/place')
		);

		$this->assertTrue(PathPatternMatcher::match($path_chunks, $pattern_chunks));
	}

	/**
	 * @test
	 * @group match_path_wildcard_middle
	 * @covers ::match()
	 * @small
	 */
	public function should_match_path_with_multiple_succeeding_middle_wildcard_pattern() {
		$path_chunks = array('path', 'to', 'somewhere', 'different');
		$pattern_chunks = array(
			PathPattern::create_instance_from_path('/path/*/*/different')
		);

		$this->assertTrue(PathPatternMatcher::match($path_chunks, $pattern_chunks));
	}

	/**
	 * @test
	 * @group match_path_wildcard_middle
	 * @covers ::match()
	 * @small
	 */
	public function should_not_match_path_with_middle_wildcard_pattern_that_has_different_start_and_end_paths() {
		$path_chunks = array('path', 'to', 'somewhere');
		$pattern_chunks = array(
			PathPattern::create_instance_from_path('/different/*/path')
		);

		$this->assertFalse(PathPatternMatcher::match($path_chunks, $pattern_chunks));
	}

	/**
	 * @test
	 * @group match_path_with_leading_slash_and_wildcard
	 * @covers ::match()
	 * @small
	 */
	public function should_match_path_with_leading_slash_and_wildcard_pattern() {
		$path_chunks = array('path', 'to', 'somewhere');
		$pattern_chunks = array(
			PathPattern::create_instance_from_path('/*/to/somewhere')
		);

		$this->assertTrue(PathPatternMatcher::match($path_chunks, $pattern_chunks));
	}

	/**
	 * @test
	 * @group match_path_with_leading_slash_and_wildcard
	 * @covers ::match()
	 * @small
	 */
	public function should_not_match_multiple_leading_path_with_leading_slash_and_wildcard_pattern() {
		$path_chunks = array('more', 'path', 'to', 'somewhere');
		$pattern_chunks = array(
			PathPattern::create_instance_from_path('/*/to/somewhere')
		);

		$this->assertFalse(PathPatternMatcher::match($path_chunks, $pattern_chunks));
	}

	/**
	 * @test
	 * @group match_path_with_trailing_slash_and_wildcard
	 * @covers ::match()
	 * @small
	 */
	public function should_match_path_with_trailing_slash_and_wildcard_pattern() {
		$path_chunks = array('path', 'to', 'somewhere');
		$pattern_chunks = array(
			PathPattern::create_instance_from_path('/path/to/*/')
		);

		$this->assertTrue(PathPatternMatcher::match($path_chunks, $pattern_chunks));
	}

	/**
	 * @test
	 * @group match_path_with_trailing_slash_and_wildcard
	 * @covers ::match()
	 * @small
	 */
	public function should_not_match_multiple_trailing_path_with_trailing_slash_and_wildcard_pattern() {
		$path_chunks = array('path', 'to', 'somewhere', 'safe');
		$pattern_chunks = array(
			PathPattern::create_instance_from_path('/path/to/*/')
		);

		$this->assertFalse(PathPatternMatcher::match($path_chunks, $pattern_chunks));
	}


	/**
	 * @test
	 * @group match_path_with_leading_and_middle_wildcard
	 * @covers ::match()
	 * @small
	 */
	public function should_match_path_with_leading_and_middle_wildcard_pattern() {
		$path_chunks = array('some', 'other', 'path', 'to', 'somewhere');
		$pattern_chunks = array(
			PathPattern::create_instance_from_path('*/path/*/somewhere')
		);

		$this->assertTrue(PathPatternMatcher::match($path_chunks, $pattern_chunks));
	}

	/**
	 * @test
	 * @group match_path_with_leading_and_middle_wildcard
	 * @covers ::match()
	 * @small
	 */
	public function should_not_match_multiple_middle_path_with_leading_and_middle_wildcard_pattern() {
		$path_chunks = array('some', 'other', 'path', 'leads', 'to', 'somewhere');
		$pattern_chunks = array(
			PathPattern::create_instance_from_path('*/path/*/somewhere')
		);

		$this->assertFalse(PathPatternMatcher::match($path_chunks, $pattern_chunks));
	}


	/**
	 * @test
	 * @group match_path_with_trailing_and_middle_wildcard
	 * @covers ::match()
	 * @small
	 */
	public function should_match_path_with_trailing_and_middle_wildcard_pattern() {
		$path_chunks = array('path', 'to', 'another', 'place');
		$pattern_chunks = array(
			PathPattern::create_instance_from_path('/path/*/another/*')
		);

		$this->assertTrue(PathPatternMatcher::match($path_chunks, $pattern_chunks));
	}

	/**
	 * @test
	 * @group match_path_with_trailing_and_middle_wildcard
	 * @covers ::match()
	 * @small
	 */
	public function should_not_match_multiple_middle_path_with_trailing_and_middle_wildcard_pattern() {
		$path_chunks = array('path', 'leads', 'to', 'another', 'place');
		$pattern_chunks = array(
			PathPattern::create_instance_from_path('/path/*/another/*')
		);

		$this->assertFalse(PathPatternMatcher::match($path_chunks, $pattern_chunks));
	}

	/**
	 * @test
	 * @group match_path_multiple
	 * @covers ::match()
	 * @small
	 */
	public function should_match_path_with_one_of_the_provided_patterns() {
		$path_chunks = array('path', 'to', 'somewhere');
		$pattern_chunks = array(
			PathPattern::create_instance_from_path('/path/*/elsewhere'),
			PathPattern::create_instance_from_path('/path/on/*'),
			PathPattern::create_instance_from_path('*/to/somewhere')
		);

		$this->assertTrue(PathPatternMatcher::match($path_chunks, $pattern_chunks));
	}

	/**
	 * @test
	 * @group match_path_multiple
	 * @covers ::match()
	 * @small
	 */
	public function should_not_match_path_with_any_of_the_provided_patterns_that_are_different() {
		$path_chunks = array('path', 'to', 'somewhere');
		$pattern_chunks = array(
			PathPattern::create_instance_from_path('/path/to/elsewhere'),
			PathPattern::create_instance_from_path('/path/is/somewhere'),
			PathPattern::create_instance_from_path('*/others')
		);

		$this->assertFalse(PathPatternMatcher::match($path_chunks, $pattern_chunks));
	}
}
