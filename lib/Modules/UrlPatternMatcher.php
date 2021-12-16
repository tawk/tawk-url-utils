<?php

namespace Tawk\Modules;

use Tawk\Helpers\PathHelper;
use Tawk\Helpers\UrlHelper;
use Tawk\Modules\PathPatternMatcher;

class UrlPatternMatcher {
	/**
	 * Matches current url to multiple patterns
	 *
	 * @param  string $current_url - Current URL.
	 * @param  string[] $patterns - List of patterns to match.
	 * @return boolean Returns `true` if current url matches with one of the provided patterns. Otherwise, `false`.
	 */
	public static function match($current_url, $patterns) {
		$parsed_current_url = UrlHelper::parse_url($current_url);
		$current_path_chunks = PathHelper::get_chunks($parsed_current_url['path']);

		foreach($patterns as $pattern) {
			$parsed_pattern = UrlHelper::parse_url($pattern);

			// checks if the provided pattern has scheme (http/https)
			// and matches with current url if it has the same scheme
			if (isset($parsed_current_url['scheme']) && isset($parsed_pattern['scheme'])) {
				if ($parsed_current_url['scheme'] !== $parsed_pattern['scheme']) {
					continue;
				}
			}

			// checks if the provided pattern has host
			// and matches with current url if it has the same host
			if (isset($parsed_current_url['host']) && isset($parsed_pattern['host'])) {
				if ($parsed_current_url['host'] !== $parsed_pattern['host']) {
					continue;
				}
			}

			// checks if the provided pattern has port
			// and matches with current url if it has the same port
			if (isset($parsed_current_url['port']) && isset($parsed_pattern['port'])) {
				if ($parsed_current_url['port'] !== $parsed_pattern['port']) {
					continue;
				}
			}

			$pattern_path_chunks = PathHelper::get_chunks($parsed_pattern['path']);

			if (PathPatternMatcher::match($current_path_chunks, array($pattern_path_chunks)) === true) {
				return true;
			}
		}

		return false;
	}
}
