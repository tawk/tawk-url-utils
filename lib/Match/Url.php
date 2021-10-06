<?php
namespace Tawk\Match;

use Tawk\Match\Path;

define('HOST_REGEX', '/^(?:[-A-Za-z0-9]+\.)+[A-Za-z0-9]{1,6}$/');

class Url{
	// made this public for unit testing
	public static function valid_host($host) {
		if ($host === 'localhost') {
			return true;
		}

		return preg_match(HOST_REGEX, $host) === 1;
	}

	public static function match($current_url, $pattern_urls = []) {
		$parsed_current_url = parse_url($current_url);

		foreach($pattern_urls as $pattern_url) {
			$parsed_pattern_url = parse_url($pattern_url);

			if (isset($parsed_current_url['scheme']) && isset($parsed_pattern_url['scheme']) &&
				$parsed_current_url['scheme'] !== $parsed_pattern_url['scheme']) {
				continue;
			}

			if (isset($parsed_current_url['host']) && isset($parsed_pattern_url['host']) &&
				$parsed_current_url['host'] !== $parsed_pattern_url['host']) {
				continue;
			}

			if (isset($parsed_current_url['port']) && isset($parsed_pattern_url['port']) &&
				$parsed_current_url['port'] !== $parsed_pattern_url['port']) {
				continue;
			}

			// if there's no host, check if the host is in the path
			$pattern_path = '';
			$current_path = '';
			if (isset($parsed_current_url['path'])) {
				$current_path = $parsed_current_url['path'];
			}

			if (isset($parsed_pattern_url['path'])) {
				$pattern_path = $parsed_pattern_url['path'];
			}

			if (!isset($parsed_pattern_url['host'])) {
				$pattern_path_chunks = Path::get_chunks($pattern_path);

				if (Url::valid_host($pattern_path_chunks[0])) {
					if (isset($parsed_current_url['host']) && $pattern_path_chunks[0] !== $parsed_current_url['host']) {
						continue;
					}

					$pattern_path = join('/', array_splice($pattern_path_chunks, 1, count($pattern_path_chunks)));
				}
			}

			if (Path::match($current_path, [$pattern_path])) {
				return true;
			}
		}

		return false;
	}
}
