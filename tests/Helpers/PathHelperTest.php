<?php

namespace Tawk\Tests\Helpers\PathHelperTest;

use PHPUnit\Framework\TestCase;
use Tawk\Enums\WildcardLocation;
use Tawk\Helpers\PathHelper;

/**
 * @coversDefaultClass \Tawk\Helpers\PathHelper
 */
class GetWildcardTest extends TestCase {
	/**
	 * @test
	 * @group get_wildcard
	 * @covers ::get_wildcard
	 * @small
	 */
	public function should_return_wildcard_constant() {
		$this->assertEquals(PathHelper::get_wildcard(), '*');
	}
}

/**
 * @coversDefaultClass \Tawk\Helpers\PathHelper
 */
class IsPathTest extends TestCase {
	/**
	 * @test
	 * @group is_path
	 * @covers ::is_path
	 * @small
	 */
	public function should_return_true_if_path_has_leading_slash() {
		$this->assertTrue(PathHelper::is_path('/path/to/somewhere'));
	}

	/**
	 * @test
	 * @group is_path
	 * @covers ::is_path
	 * @small
	 */
	public function should_return_true_if_path_has_leading_wildcard() {
		$this->assertTrue(PathHelper::is_path('*/to/somewhere'));
	}

	/**
	 * @test
	 * @group is_path
	 * @covers ::is_path
	 * @small
	 */
	public function should_return_false_if_path_has_no_leading_slash_and_wildcard() {
		$this->assertFalse(PathHelper::is_path('path/to/somewhere'));
	}
}

/**
 * @coversDefaultClass \Tawk\Helpers\PathHelper
 */
class PathChunksHasWildcardTest extends TestCase {
	/**
	 * @test
	 * @group path_chunks_has_wildcard
	 * @covers ::path_chunks_has_wildcard
	 * @small
	 */
	public function should_return_true_if_path_chunks_contains_wildcard() {
		$path_chunks = array('*', 'to', 'somewhere');
		$this->assertTrue(PathHelper::path_chunks_has_wildcard($path_chunks));
	}

	/**
	 * @test
	 * @group path_chunks_has_wildcard
	 * @covers ::path_chunks_has_wildcard
	 * @small
	 */
	public function should_return_false_if_path_chunks_does_not_contain_wildcard() {
		$path_chunks = array('path', 'to', 'somewhere');
		$this->assertFalse(PathHelper::path_chunks_has_wildcard($path_chunks));
	}
}

/**
 * @coversDefaultClass \Tawk\Helpers\PathHelper
 */
class IsWildcardTest extends TestCase {
	/**
	 * @test
	 * @group is_wildcard
	 * @covers ::is_wildcard
	 * @small
	 */
	public function should_return_true_if_wildcard() {
		$this->assertTrue(PathHelper::is_wildcard('*'));
	}

	/**
	 * @test
	 * @group is_wildcard
	 * @covers ::is_wildcard
	 * @small
	 */
	public function should_return_false_if_not_wildcard() {
		$this->assertFalse(PathHelper::is_wildcard('not-wildcard'));
	}
}

/**
 * @coversDefaultClass \Tawk\Helpers\PathHelper
 */
class GetChunksTest extends TestCase {
	/**
	 * @test
	 * @group get_chunks
	 * @covers ::get_chunks
	 * @small
	 */
	public function should_get_chunks_from_provided_path() {
		$this->assertEquals(PathHelper::get_chunks('/path/to/somewhere'), array('path', 'to', 'somewhere'));
	}

	/**
	 * @test
	 * @group get_chunks
	 * @covers ::get_chunks
	 * @small
	 */
	public function should_get_empty_string_array_if_only_provided_with_single_slash() {
		$this->assertEquals(PathHelper::get_chunks('/'), array());
	}
}

/**
 * @coversDefaultClass \Tawk\Helpers\PathHelper
 */
class GetWildcardLocationByChunks extends TestCase {
	/**
	 * @test
	 * @group get_wildcard_location_by_chunks
	 * @covers ::get_wildcard_location_by_chunks
	 * @small
	 */
	public function should_return_start_if_provided_chunks_has_leading_wildcard() {
		$path_chunks = array('*', 'to', 'somewhere');
		$this->assertEquals(PathHelper::get_wildcard_location_by_chunks($path_chunks), WildcardLocation::START);
	}

	/**
	 * @test
	 * @group get_wildcard_location_by_chunks
	 * @covers ::get_wildcard_location_by_chunks
	 * @small
	 */
	public function should_return_middle_if_provided_chunks_has_wildcard_at_the_middle() {
		$path_chunks = array('path', '*', 'somewhere');
		$this->assertEquals(PathHelper::get_wildcard_location_by_chunks($path_chunks), WildcardLocation::MIDDLE);
	}

	/**
	 * @test
	 * @group get_wildcard_location_by_chunks
	 * @covers ::get_wildcard_location_by_chunks
	 * @small
	 */
	public function should_return_end_if_provided_chunks_has_trailing_wildcard() {
		$path_chunks = array('path', 'to', '*');
		$this->assertEquals(PathHelper::get_wildcard_location_by_chunks($path_chunks), WildcardLocation::END);
	}

	/**
	 * @test
	 * @group get_wildcard_location_by_chunks
	 * @covers ::get_wildcard_location_by_chunks
	 * @small
	 */
	public function should_return_none_if_provided_chunks_has_no_wildcard() {
		$path_chunks = array('path', 'to', 'somewhere');
		$this->assertEquals(PathHelper::get_wildcard_location_by_chunks($path_chunks), WildcardLocation::NONE);
	}
}

/**
 * @coversDefaultClass \Tawk\Helpers\PathHelper
 */
class CheckStrictLength extends TestCase {
	/**
	 * @test
	 * @group path_has_strict_length
	 * @covers ::path_has_strict_length
	 * @small
	 */
	public function should_be_true_if_provided_path_has_leading_slash_and_wildcard() {
		$path = '/*/to/somewhere';
		$this->assertTrue(PathHelper::path_has_strict_length($path, WildcardLocation::START));
	}

	/**
	 * @test
	 * @group path_has_strict_length
	 * @covers ::path_has_strict_length
	 * @small
	 */
	public function should_be_true_if_provided_path_has_trailing_wildcard_and_slash() {
		$path = '/path/to/*/';
		$this->assertTrue(PathHelper::path_has_strict_length($path, WildcardLocation::END));
	}

	/**
	 * @test
	 * @group path_has_strict_length
	 * @covers ::path_has_strict_length
	 * @small
	 */
	public function should_be_false_if_provided_path_has_leading_wildcard_and_slash() {
		$path = '*/to/somewhere';
		$this->assertFalse(PathHelper::path_has_strict_length($path, WildcardLocation::START));
	}

	/**
	 * @test
	 * @group path_has_strict_length
	 * @covers ::path_has_strict_length
	 * @small
	 */
	public function should_be_false_if_provided_path_has_trailing_slash_and_wildcard() {
		$path = '/path/to/*';
		$this->assertFalse(PathHelper::path_has_strict_length($path, WildcardLocation::END));
	}

	/**
	 * @test
	 * @group path_has_strict_length
	 * @covers ::path_has_strict_length
	 * @small
	 */
	public function should_be_false_if_provided_path_has_no_leading_or_trailing_slash_and_wildcard() {
		$path = '/path/*/somewhere';
		$this->assertFalse(PathHelper::path_has_strict_length($path, WildcardLocation::MIDDLE));
	}

	/**
	 * @test
	 * @group path_has_strict_length
	 * @covers ::path_has_strict_length
	 * @small
	 */
	public function should_be_false_if_provided_path_has_different_wildcard_location_on_path() {
		$path = '/path/somewhere/*/';
		$this->assertFalse(PathHelper::path_has_strict_length($path, WildcardLocation::START));
	}
}
