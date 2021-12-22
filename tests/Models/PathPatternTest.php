<?php

namespace Tawk\Tests\Models\PathPatternTest;

use PHPUnit\Framework\TestCase;
use Tawk\Enums\WildcardLocation;
use Tawk\Models\PathPattern;

/**
 * @coversDefaultClass \Tawk\Models\PathPatternTest
 */
class CreateInstanceFromPath extends TestCase {
	/**
	 * @test
	 * @group create_instance_from_path
	 * @covers ::create_instance_from_path
	 * @small
	 */
	public function should_create_path_pattern_instance_from_provided_path() {
		$path_pattern = PathPattern::create_instance_from_path('/path/to/somewhere');
		$this->assertInstanceOf(PathPattern::class, $path_pattern);
	}
}

/**
 * @coversDefaultClass \Tawk\Models\PathPatternTest
 */
class HasWildcard extends TestCase {
	/**
	 * @test
	 * @group has_wildcard
	 * @covers ::has_wildcard
	 * @small
	 */
	public function should_be_true_if_path_provided_has_wildcard() {
		$path_pattern = PathPattern::create_instance_from_path('/path/to/*');
		$this->assertTrue($path_pattern->has_wildcard());
	}

	/**
	 * @test
	 * @group has_wildcard
	 * @covers ::has_wildcard
	 * @small
	 */
	public function should_be_false_if_path_provided_does_not_have_wildcard() {
		$path_pattern = PathPattern::create_instance_from_path('/path/to/somewhere');
		$this->assertFalse($path_pattern->has_wildcard());
	}
}

/**
 * @coversDefaultClass \Tawk\Models\PathPatternTest
 */
class HasStrictLength extends TestCase {
	/**
	 * @test
	 * @group has_strict_length
	 * @covers ::has_strict_length
	 * @small
	 */
	public function should_be_true_if_pattern_provided_has_leading_slash_and_wildcard() {
		$path_pattern = PathPattern::create_instance_from_path('/*/to/somewhere');
		$this->assertTrue($path_pattern->has_strict_length());
	}

	/**
	 * @test
	 * @group has_strict_length
	 * @covers ::has_strict_length
	 * @small
	 */
	public function should_be_true_if_pattern_provided_has_trailing_wildcard_and_slash() {
		$path_pattern = PathPattern::create_instance_from_path('/path/to/*/');
		$this->assertTrue($path_pattern->has_strict_length());
	}

	/**
	 * @test
	 * @group has_strict_length
	 * @covers ::has_strict_length
	 * @small
	 */
	public function should_be_false_if_pattern_provided_has_no_leading_or_trailing_slash_and_wildcard() {
		$path_pattern = PathPattern::create_instance_from_path('/path/to/somewhere');
		$this->assertFalse($path_pattern->has_strict_length());
	}
}

/**
 * @coversDefaultClass \Tawk\Models\PathPatternTest
 */
class GetChunks extends TestCase {
	/**
	 * @test
	 * @group get_chunks
	 * @covers ::get_chunks
	 * @small
	 */
	public function should_provide_path_in_chunks() {
		$path_pattern = PathPattern::create_instance_from_path('/path/to/somewhere');
		$this->assertEquals($path_pattern->get_chunks(), array('path', 'to', 'somewhere'));
	}
}

/**
 * @coversDefaultClass \Tawk\Models\PathPatternTest
 */
class GetWildcardLocation extends TestCase {
	/**
	 * @test
	 * @group get_wildcard_location
	 * @covers ::get_wildcard_location
	 * @small
	 */
	public function should_provide_wildcard_location() {
		$path_pattern = PathPattern::create_instance_from_path('/path/*/somewhere');
		$this->assertEquals($path_pattern->get_wildcard_location(), WildcardLocation::MIDDLE);
	}
}
