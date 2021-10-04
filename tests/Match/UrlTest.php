<?php

use PHPUnit\Framework\TestCase;
use Tawk\Match\Url;

/**
 * @coversDefaultClass \Tawk\Url
 */
class UrlTest extends TestCase {
	/**
	 * @test
	 * @group valid_host
	 * @covers ::valid_host()
	 */
	public function should_match_valid_host() {
		$host = 'www.example.com';
		$this->assertTrue(Url::valid_host($host));
	}

	/**
	 * @test
	 * @group valid_host
	 * @covers ::valid_host()
	 */
	public function should_match_localhost() {
		$host = 'localhost';
		$this->assertTrue(Url::valid_host($host));
	}

	/**
	 * @test
	 * @group valid_host
	 * @covers ::valid_host()
	 */
	public function should_match_ip_address() {
		$host = '192.168.0.1';
		$this->assertTrue(Url::valid_host($host));
	}

	/**
	 * @test
	 * @group valid_host
	 * @covers ::valid_host()
	 */
	public function should_be_false_if_url_does_not_have_host() {
		$host = 'not-host';
		$this->assertFalse(Url::valid_host($host));
	}

	//--------------------------------------------------------------------------------------------------

	/**
	 * @test
	 * @group match_url
	 * @covers ::match_url()
	 */
	public function should_match_similar_url_and_pattern() {
		$url = 'http://www.example.com/this/path/should/lead/to/somewhere';
		$pattern = 'http://www.example.com/this/path/should/lead/to/somewhere';
		$result = Url::match_url($url, $pattern);

		$this->assertTrue($result);
	}

	/**
	 * @test
	 * @group match_url
	 * @covers ::match_url()
	 */
	public function should_match_pattern_without_protocol() {
		$url = 'http://www.example.com/this/path/should/lead/to/somewhere';
		$pattern = 'www.example.com/this/path/should/lead/to/somewhere';
		$result = Url::match_url($url, $pattern);

		$this->assertTrue($result);
	}

	/**
	 * @test
	 * @group match_url
	 * @covers ::match_url()
	 */
	public function should_match_pattern_with_localhost() {
		$url = 'http://localhost/this/path/should/lead/to/somewhere';
		$pattern = 'localhost/this/path/should/lead/to/somewhere';
		$result = Url::match_url($url, $pattern);

		$this->assertTrue($result);
	}

	/**
	 * @test
	 * @group match_url
	 * @covers ::match_url()
	 */
	public function should_match_pattern_with_port() {
		$url = 'http://localhost:8000/this/path/should/lead/to/somewhere';
		$pattern = 'localhost:8000/this/path/should/lead/to/somewhere';
		$result = Url::match_url($url, $pattern);

		$this->assertTrue($result);
	}

	/**
	 * @test
	 * @group match_url
	 * @covers ::match_url()
	 */
	public function should_match_pattern_with_ip_address() {
		$url = 'http://192.168.0.1/this/path/should/lead/to/somewhere';
		$pattern = '192.168.0.1/this/path/should/lead/to/somewhere';
		$result = Url::match_url($url, $pattern);

		$this->assertTrue($result);
	}

	/**
	 * @test
	 * @group match_url
	 * @covers ::match_url()
	 */
	public function should_match_pattern_with_wildcard_at_path_start() {
		$url = 'http://www.example.com/this/path/should/lead/to/somewhere';
		$pattern = 'www.example.com/*/path/should/lead/to/somewhere';
		$result = Url::match_url($url, $pattern);

		$this->assertTrue($result);
	}

	/**
	 * @test
	 * @group match_url
	 * @covers ::match_url()
	 */
	public function should_match_pattern_with_wildcard_at_path_end() {
		$url = 'http://www.example.com/this/path/should/lead/to/somewhere';
		$pattern = 'www.example.com/this/path/should/lead/to/*';
		$result = Url::match_url($url, $pattern);

		$this->assertTrue($result);
	}

	/**
	 * @test
	 * @group match_url
	 * @covers ::match_url()
	 */
	public function should_match_pattern_with_wildcard_at_path_middle() {
		$url = 'http://www.example.com/this/path/should/lead/to/somewhere';
		$pattern = 'www.example.com/this/path/should/*/to/somewhere';
		$result = Url::match_url($url, $pattern);

		$this->assertTrue($result);
	}

	/**
	 * @test
	 * @group match_url
	 * @covers ::match_url()
	 */
	public function should_match_if_pattern_is_only_a_path() {
		$url = 'http://www.example.com/this/path/should/lead/to/somewhere';
		$pattern = 'this/path/should/lead/to/somewhere';
		$result = Url::match_url($url, $pattern);

		$this->assertTrue($result);
	}

	/**
	 * @test
	 * @group match_url
	 * @covers ::match_url()
	 */
	public function should_not_match_if_pattern_and_url_differs() {
		$url = 'http://www.example.com/this/path/should/lead/to/somewhere';
		$pattern = 'www.example.com/this/path/should/different/to/somewhere';
		$result = Url::match_url($url, $pattern);

		$this->assertFalse($result);
	}
}
