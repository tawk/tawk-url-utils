<?php

namespace Tawk\Helpers\UrlHelperTest;

use PHPUnit\Framework\TestCase;

use Tawk\Helpers\UrlHelper;

/**
 * @coversDefaultClass \Tawk\Modules\UrlHelper
 */
class ParsePatternUrl extends Testcase {
	/**
	 * @test
	 * @group parse_pattern_url
	 * @covers ::parse_pattern_url()
	 */
	public function should_parse_provided_pattern_url() {
		$pattern_url = 'http://www.example.com:80/path/to/somewhere';
		$this->assertEquals(UrlHelper::parse_pattern_url($pattern_url), array(
			'scheme' => 'http',
			'host' => 'www.example.com',
			'port' => '80',
			'path' => '/path/to/somewhere'
		));
	}

	/**
	 * @test
	 * @group parse_pattern_url
	 * @covers ::parse_pattern_url()
	 */
	public function should_parse_provided_pattern_url_without_scheme() {
		$pattern_url = 'www.example.com:80/path/to/somewhere';
		$this->assertEquals(UrlHelper::parse_pattern_url($pattern_url), array(
			'host' => 'www.example.com',
			'port' => '80',
			'path' => '/path/to/somewhere'
		));
	}

	/**
	 * @test
	 * @group parse_pattern_url
	 * @covers ::parse_pattern_url()
	 */
	public function should_parse_provided_pattern_url_without_port() {
		$pattern_url = 'http://www.example.com/path/to/somewhere';
		$this->assertEquals(UrlHelper::parse_pattern_url($pattern_url), array(
			'scheme' => 'http',
			'host' => 'www.example.com',
			'path' => '/path/to/somewhere'
		));
	}

	/**
	 * @test
	 * @group parse_pattern_url
	 * @covers ::parse_pattern_url()
	 */
	public function should_parse_provided_pattern_url_without_path() {
		$pattern_url = 'http://www.example.com:80/';
		$this->assertEquals(UrlHelper::parse_pattern_url($pattern_url), array(
			'scheme' => 'http',
			'host' => 'www.example.com',
			'port' => '80',
			'path' => '/'
		));
	}

	/**
	 * @test
	 * @group parse_pattern_url
	 * @covers ::parse_pattern_url()
	 */
	public function should_parse_provided_pattern_path() {
		$pattern_path = '/path/to/somewhere';
		$this->assertEquals(UrlHelper::parse_pattern_url($pattern_path), array(
			'path' => '/path/to/somewhere'
		));
	}

	/**
	 * @test
	 * @group parse_pattern_url
	 * @covers ::parse_pattern_url()
	 */
	public function should_parse_set_host_if_provided_pattern_path_has_no_leading_slash() {
		$pattern_path = 'path/to/somewhere';
		$this->assertEquals(UrlHelper::parse_pattern_url($pattern_path), array(
			'host' => 'path',
			'path' => '/to/somewhere'
		));
	}
}
