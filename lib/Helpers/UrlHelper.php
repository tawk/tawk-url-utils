<?php

namespace Tawk\Helpers;

use Tawk\Helpers\PathHelper;

define('PROTOCOL_REGEX', '/^(http|https):\/\//');

class UrlHelper {
	/**
	 * Parses provided url
	 *
	 * @param  string $url - URL
	 * @return array{
	 *  path: string,
	 *  host?: string,
	 *  port?: string,
	 *  scheme?: string
	 * } Parsed url data
	 */
	public static function parse_url($url) {
		$is_path = PathHelper::is_path($url);
		$has_protocol = preg_match(PROTOCOL_REGEX, $url) === 1;

		if ($is_path === false && $has_protocol === false) {
			// add http:// in front of the string
			$url = 'http://'.$url;
		}

		$parsed_url = parse_url($url);

		$url_data = array(
			'path' => '/'
		);

		if (isset($parsed_url['path'])) {
			$url_data['path'] = $parsed_url['path'];
		}

		if (isset($parsed_url['host'])) {
			$url_data['host'] = $parsed_url['host'];
		}

		if (isset($parsed_url['port'])) {
			$url_data['port'] = $parsed_url['port'];
		}

		if ($is_path === false && $has_protocol === true) {
			$url_data['scheme'] = $parsed_url['scheme'];
		}

		return $url_data;
	}
}
