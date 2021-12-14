<?php

use PHPUnit\Framework\TestCase;
use Tawk\Modules\UrlPatternMatcher;

/**
 * @coversDefaultClass \Tawk\Modules\UrlPatternMatcher
 */
class UrlMatchTest extends TestCase {
	/**
	 * @test
	 * @group match_url
	 * @covers ::match()
	 */
	public function should_match_similar_url_and_pattern() {
		$url = 'http://www.example.com/this/path/should/lead/to/somewhere';
		$pattern = array('http://www.example.com/this/path/should/lead/to/somewhere');
		$result = UrlPatternMatcher::match($url, $pattern);

		$this->assertTrue($result);
	}

	/**
	 * @test
	 * @group match_url
	 * @covers ::match()
	 */
	public function should_match_url_and_pattern_without_protocol() {
		$url = 'http://www.example.com/this/path/should/lead/to/somewhere';
		$pattern = array('www.example.com/this/path/should/lead/to/somewhere');
		$result = UrlPatternMatcher::match($url, $pattern);

		$this->assertTrue($result);
	}

	/**
	 * @test
	 * @group match_url
	 * @covers ::match()
	 */
	public function should_match_url_and_pattern_with_localhost() {
		$url = 'http://localhost/this/path/should/lead/to/somewhere';
		$pattern = array('localhost/this/path/should/lead/to/somewhere');
		$result = UrlPatternMatcher::match($url, $pattern);

		$this->assertTrue($result);
	}

	/**
	 * @test
	 * @group match_url
	 * @covers ::match()
	 */
	public function should_match_url_and_pattern_with_ip_address() {
		$url = 'http://192.168.0.1/this/path/should/lead/to/somewhere';
		$pattern = array('http://192.168.0.1/this/path/should/lead/to/somewhere');
		$result = UrlPatternMatcher::match($url, $pattern);

		$this->assertTrue($result);
	}

	/**
	 * @test
	 * @group match_url
	 * @covers ::match()
	 */
	public function should_match_url_and_pattern_wildcard_at_the_start() {
		$url = 'http://www.example.com/this/path/should/lead/to/somewhere';
		$pattern = array('*/path/should/lead/to/somewhere');
		$result = UrlPatternMatcher::match($url, $pattern);

		$this->assertTrue($result);
	}

	/**
	 * @test
	 * @group match_url
	 * @covers ::match()
	 */
	public function should_match_url_and_pattern_with_wildcard_at_the_end() {
		$url = 'http://www.example.com/this/path/should/lead/to/somewhere';
		$pattern = array('www.example.com/this/path/should/lead/to/*');
		$result = UrlPatternMatcher::match($url, $pattern);

		$this->assertTrue($result);
	}

	/**
	 * @test
	 * @group match_url
	 * @covers ::match()
	 */
	public function should_match_pattern_with_wildcard_at_the_middle() {
		$url = 'http://www.example.com/this/path/should/lead/to/somewhere';
		$pattern = array('www.example.com/this/path/should/*/to/somewhere');
		$result = UrlPatternMatcher::match($url, $pattern);

		$this->assertTrue($result);
	}

	/**
	 * @test
	 * @group match_url
	 * @covers ::match()
	 */
	public function should_match_url_and_pattern_if_only_path_is_provided() {
		$url = 'http://www.example.com/this/path/should/lead/to/somewhere';
		$pattern = array('/this/path/should/lead/to/somewhere');
		$result = UrlPatternMatcher::match($url, $pattern);

		$this->assertTrue($result);
	}

	/**
	 * @test
	 * @group match_url
	 * @covers ::match()
	 */
	public function should_match_url_and_pattern_if_only_path_is_provided_with_leading_wildcard() {
		$url = 'http://www.example.com/this/path/should/lead/to/somewhere';
		$pattern = array('*/path/should/lead/to/somewhere');
		$result = UrlPatternMatcher::match($url, $pattern);

		$this->assertTrue($result);
	}

	/**
	 * @test
	 * @group match_url
	 * @covers ::match()
	 */
	public function should_match_url_with_multiple_patterns() {
		$url = 'http://www.example.com/this/path/should/lead/to/somewhere';
		$pattern = array(
			'this/path/should/lead/to/somewhere',
			'http://www.example.com/this/path/should/lead/to/*',
			'http://www.example.com/*/path/should/lead/to/somewhere',
			'this/*/should/lead/to/somewhere'
		);
		$result = UrlPatternMatcher::match($url, $pattern);

		$this->assertTrue($result);
	}

	/**
	 * @test
	 * @group match_url
	 * @covers ::match()
	 */
	public function should_match_url_with_multiple_patterns_that_has_one_similar_pattern() {
		$url = 'http://www.example.com/this/path/should/lead/to/somewhere';
		$pattern = array(
			'/this/path/should/lead/to/elsewhere',
			'http://www.example.com/this/path/should/lead/in/*',
			'http://www.example.com/*/path/should/lead/to/me',
			'/this/*/should/lead/to/somewhere' // valid
		);
		$result = UrlPatternMatcher::match($url, $pattern);

		$this->assertTrue($result);
	}

	/**
	 * @test
	 * @group match_url
	 * @covers ::match()
	 */
	public function should_match_url_and_pattern_with_slashes_only() {
		$path = 'http://www.example.com/';
		$pattern = array('http://www.example.com/');

		$this->assertTrue(UrlPatternMatcher::match($path, $pattern));
	}

	/**
	 * @test
	 * @group match_url
	 * @covers ::match()
	 */
	public function should_not_match_url_with_different_pattern() {
		$url = 'http://www.example.com/this/path/should/lead/to/somewhere';
		$pattern = array('www.example.com/this/path/should/different/to/somewhere');
		$result = UrlPatternMatcher::match($url, $pattern);

		$this->assertFalse($result);
	}

	/**
	 * @test
	 * @group match_url
	 * @covers ::match()
	 */
	public function should_not_match_url_with_multiple_patterns_that_are_all_different() {
		$url = 'http://www.example.com/this/path/should/lead/to/somewhere';
		$pattern = array(
			'this/path/should/lead/to/elsewhere',
			'http://www.example.com/this/path/should/lead/in/*',
			'http://www.example.com/*/path/should/lead/to/me',
			'this/*/shouldnot/lead/to/somewhere'
		);
		$result = UrlPatternMatcher::match($url, $pattern);

		$this->assertFalse($result);
	}

	/**
	 * @test
	 * @group match_url
	 * @covers ::match()
	 */
	public function should_not_match_url_and_pattern_if_only_path_is_provided_without_leading_slash() {
		$url = 'http://www.example.com/this/path/should/lead/to/somewhere';
		$pattern = array('this/path/should/lead/to/somewhere');
		$result = UrlPatternMatcher::match($url, $pattern);

		$this->assertFalse($result);
	}
}
