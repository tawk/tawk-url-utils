<?php

namespace Tawk\Helpers;

use Tawk\Helpers\PathHelper;

class UrlHelper {
	/**
	 * Parses pattern url
	 */
	public static function parse_pattern_url($pattern) {
		$is_path = PathHelper::is_path($pattern);
		$has_protocol = strpos($pattern, 'http') === 0;

		if ($is_path === false && $has_protocol === false) {
			// add http:// in front of the string
			$pattern = 'http://'.$pattern;
		}

		$parsed_url = parse_url($pattern);

		$url_data = array(
			'path' => $parsed_url['path'],
		);

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
