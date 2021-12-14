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
	 * @covers ::match
	 * @small
	 */
	public function should_match_similar_url() {
		$url = 'http://www.example.com';
		$pattern = array('http://www.example.com');
		$result = UrlPatternMatcher::match($url, $pattern);

		$this->assertTrue($result);
	}

	/**
	 * @test
	 * @group match_url_scheme
	 * @covers ::match
	 * @small
	 */
	public function should_match_url_with_pattern_that_has_no_scheme() {
		$url = 'http://www.example.com';
		$pattern = array('www.example.com');
		$result = UrlPatternMatcher::match($url, $pattern);

		$this->assertTrue($result);
	}

	/**
	 * @test
	 * @group match_url_scheme
	 * @covers ::match
	 * @small
	 */
	public function should_not_match_url_with_different_pattern_scheme() {
		$url = 'http://www.example.com';
		$pattern = array('https://www.example.com');
		$result = UrlPatternMatcher::match($url, $pattern);

		$this->assertFalse($result);
	}

	/**
	 * @test
	 * @group match_url_host
	 * @covers ::match
	 * @small
	 */
	public function should_not_match_different_url_host() {
		$url = 'http://www.example.com';
		$pattern = array('http://www.tawk.to');
		$result = UrlPatternMatcher::match($url, $pattern);

		$this->assertFalse($result);
	}

	/**
	 * @test
	 * @group match_url_host
	 * @covers ::match
	 */
	public function should_match_url_with_ip_address_as_host() {
		$url = 'http://192.168.0.1/path/to/somewhere';
		$pattern = array('http://192.168.0.1/path/to/somewhere');
		$result = UrlPatternMatcher::match($url, $pattern);

		$this->assertTrue($result);
	}

	/**
	 * @test
	 * @group match_url_host
	 * @covers ::match
	 * @small
	 */
	public function should_match_localhost() {
		$url = 'http://localhost';
		$pattern = array('http://localhost');
		$result = UrlPatternMatcher::match($url, $pattern);

		$this->assertTrue($result);
	}

	/**
	 * @test
	 * @group match_url_host
	 * @covers ::match
	 * @small
	 */
	public function should_match_localhost_without_scheme() {
		$url = 'http://localhost';
		$pattern = array('localhost');
		$result = UrlPatternMatcher::match($url, $pattern);

		$this->assertTrue($result);
	}

	/**
	 * @test
	 * @group match_url_port
	 * @covers ::match
	 * @small
	 */
	public function should_match_similar_url_port() {
		$url = 'http://www.example.com:80';
		$pattern = array('http://www.example.com:80');
		$result = UrlPatternMatcher::match($url, $pattern);

		$this->assertTrue($result);
	}

	/**
	 * @test
	 * @group match_url_port
	 * @covers ::match
	 * @small
	 */
	public function should_not_match_different_url_port() {
		$url = 'http://www.example.com:80';
		$pattern = array('http://www.example.com:8000');
		$result = UrlPatternMatcher::match($url, $pattern);

		$this->assertFalse($result);
	}

	/**
	 * @test
	 * @group match_url_with_full_url_and_path
	 * @covers ::match
	 * @small
	 */
	public function should_match_similar_url_and_pattern() {
		$url = 'http://www.example.com/path/to/somewhere';
		$pattern = array('http://www.example.com/path/to/somewhere');
		$result = UrlPatternMatcher::match($url, $pattern);

		$this->assertTrue($result);
	}

	/**
	 * @test
	 * @group match_url_with_full_url_and_path
	 * @covers ::match
	 * @small
	 */
	public function should_match_url_with_full_url_and_leading_wildcard_path_pattern() {
		$url = 'http://www.example.com/path/to/somewhere';
		$pattern = array('http://www.example.com/*/to/somewhere');
		$result = UrlPatternMatcher::match($url, $pattern);

		$this->assertTrue($result);
	}

	/**
	 * @test
	 * @group match_url_with_full_url_and_path
	 * @covers ::match
	 * @small
	 */
	public function should_match_url_with_full_url_and_trailing_wildcard_path_pattern() {
		$url = 'http://www.example.com/path/to/somewhere';
		$pattern = array('http://www.example.com/path/to/*');
		$result = UrlPatternMatcher::match($url, $pattern);

		$this->assertTrue($result);
	}

	/**
	 * @test
	 * @group match_url_with_full_url_and_path
	 * @covers ::match()
	 * @small
	 */
	public function should_match_url_with_full_url_and_middle_wildcard_path_pattern() {
		$url = 'http://www.example.com/path/to/somewhere';
		$pattern = array('http://www.example.com/path/*/somewhere');
		$result = UrlPatternMatcher::match($url, $pattern);

		$this->assertTrue($result);
	}

	/**
	 * @test
	 * @group match_url_with_full_url_and_path
	 * @covers ::match()
	 * @small
	 */
	public function should_match_url_with_full_url_and_only_wildcard_path_pattern() {
		$url = 'http://www.example.com/path/to/somewhere';
		$pattern = array('http://www.example.com/*');
		$result = UrlPatternMatcher::match($url, $pattern);

		$this->assertTrue($result);
	}

	/**
	 * @test
	 * @group match_url_with_full_url_and_path
	 * @covers ::match()
	 * @small
	 */
	public function should_match_url_and_empty_path_with_full_url_and_only_wildcard_path_pattern() {
		$url = 'http://www.example.com/';
		$pattern = array('http://www.example.com/*');
		$result = UrlPatternMatcher::match($url, $pattern);

		$this->assertTrue($result);
	}

	/**
	 * @test
	 * @group match_url_with_path_only
	 * @covers ::match
	 * @small
	 */
	public function should_match_url_with_path_only_pattern() {
		$url = 'http://www.example.com/path/to/somewhere';
		$pattern = array('/path/to/somewhere');
		$result = UrlPatternMatcher::match($url, $pattern);

		$this->assertTrue($result);
	}

	/**
	 * @test
	 * @group match_url_with_path_only
	 * @covers ::match
	 * @small
	 */
	public function should_match_url_with_leading_wildcard_pattern_path() {
		$url = 'http://www.example.com/path/to/somewhere';
		$pattern = array('*/to/somewhere');
		$result = UrlPatternMatcher::match($url, $pattern);

		$this->assertTrue($result);
	}

	/**
	 * @test
	 * @group match_url_with_path_only
	 * @covers ::match
	 * @small
	 */
	public function should_match_url_with_trailing_wildcard_pattern_path() {
		$url = 'http://www.example.com/path/to/somewhere';
		$pattern = array('/path/to/*');
		$result = UrlPatternMatcher::match($url, $pattern);

		$this->assertTrue($result);
	}

	/**
	 * @test
	 * @group match_url_with_path_only
	 * @covers ::match
	 * @small
	 */
	public function should_match_url_with_middle_wildcard_pattern_path() {
		$url = 'http://www.example.com/path/to/somewhere';
		$pattern = array('/path/*/somewhere');
		$result = UrlPatternMatcher::match($url, $pattern);

		$this->assertTrue($result);
	}

	/**
	 * @test
	 * @group match_url_with_path_only
	 * @covers ::match
	 * @small
	 */
	public function should_match_url_and_empty_path_with_wildcard_only_pattern_path() {
		$url = 'http://www.example.com';
		$pattern = array('*');
		$result = UrlPatternMatcher::match($url, $pattern);

		$this->assertTrue($result);
	}

	/**
	 * @test
	 * @group match_url_with_path_only
	 * @covers ::match
	 * @small
	 */
	public function should_match_url_with_wildcard_only_pattern_path() {
		$url = 'http://www.example.com/path/to/somewhere';
		$pattern = array('*');
		$result = UrlPatternMatcher::match($url, $pattern);

		$this->assertTrue($result);
	}

	/**
	 * @test
	 * @group match_url_with_path_only
	 * @covers ::match
	 * @small
	 */
	public function should_not_match_url_with_pattern_not_having_leading_slash_or_wildcard() {
		$url = 'http://www.example.com/path/to/somewhere';
		$pattern = array('path/to/somewhere');
		$result = UrlPatternMatcher::match($url, $pattern);

		$this->assertFalse($result);
	}

	/**
	 * @test
	 * @group match_url_multiple
	 * @covers ::match
	 * @small
	 */
	public function should_match_url_with_multiple_patterns() {
		$url = 'http://www.example.com/path/to/somewhere';
		$pattern = array(
			'path/to/somewhere',
			'http://www.example.com/path/to/*',
			'http://www.example.com/*/path/should/lead/to/somewhere',
			'this/*/should/lead/to/somewhere'
		);
		$result = UrlPatternMatcher::match($url, $pattern);

		$this->assertTrue($result);
	}

	/**
	 * @test
	 * @group match_url_multiple
	 * @covers ::match
	 * @small
	 */
	public function should_match_url_with_multiple_patterns_that_has_one_similar_pattern() {
		$url = 'http://www.example.com/path/to/somewhere';
		$pattern = array(
			'/this/path/should/lead/to/elsewhere',
			'http://www.example.com/this/path/should/lead/in/*',
			'http://www.example.com/*/path/should/lead/to/me',
			'/path/*/somewhere' // valid
		);
		$result = UrlPatternMatcher::match($url, $pattern);

		$this->assertTrue($result);
	}

	/**
	 * @test
	 * @group match_url_multiple
	 * @covers ::match
	 * @small
	 */
	public function should_not_match_url_with_multiple_patterns_that_are_all_different() {
		$url = 'http://www.example.com/path/to/somewhere';
		$pattern = array(
			'this/path/should/lead/to/elsewhere',
			'http://www.example.com/this/path/should/lead/in/*',
			'http://www.example.com/*/path/should/lead/to/me',
			'this/*/shouldnot/lead/to/somewhere'
		);
		$result = UrlPatternMatcher::match($url, $pattern);

		$this->assertFalse($result);
	}
}
