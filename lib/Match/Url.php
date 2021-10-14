<?php
namespace Tawk\Match;

use Tawk\Match\Path;
use Tawk\Helper;

// this checks for ip addresses and domains
define('HOST_REGEX', '/^(?:[-A-Za-z0-9]+\.)+[A-Za-z0-9]{1,6}$/');

class Url{
	/**
	 * Validates the provided host.
	 */
	public static function valid_host($host) {
		if ($host === 'localhost') {
			return true;
		}

		return preg_match(HOST_REGEX, $host) === 1;
	}

	/**
	 * Matches the current url and the patterns that are provided
	 */
	public static function match($current_url, $pattern_urls = []) {
		$parsed_current_url = parse_url($current_url);

		foreach($pattern_urls as $pattern_url) {
			$parsed_pattern_url = parse_url(Helper::clean_url($pattern_url));

			// checks if the provided pattern has scheme (http/https)
			// and matches with current url if it has the same scheme
			if (isset($parsed_current_url['scheme']) && isset($parsed_pattern_url['scheme']) &&
				$parsed_current_url['scheme'] !== $parsed_pattern_url['scheme']) {
				continue;
			}

			// checks if the provided pattern has host
			// and matches with current url if it has the same host
			if (isset($parsed_current_url['host']) && isset($parsed_pattern_url['host']) &&
				$parsed_current_url['host'] !== $parsed_pattern_url['host']) {
				continue;
			}

			// checks if the provided pattern has port
			// and matches with current url if it has the same port
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

			// check if host is in the first part of the path.
			// this can happen if the pattern provided is for example
			//
			// www.example.com/path/to/somewhere
			//
			// the parse_url treats this as a path
			if (!isset($parsed_pattern_url['host'])) {
				$pattern_path_chunks = Path::get_chunks($pattern_path);

				// checks for the first chunk if it is a valid host
				if (Url::valid_host($pattern_path_chunks[0])) {
					if (isset($parsed_current_url['host']) && $pattern_path_chunks[0] !== $parsed_current_url['host']) {
						continue;
					}

					$pattern_path = join('/', array_splice($pattern_path_chunks, 1, count($pattern_path_chunks)));
				}
			}

			if (Path::match($current_path, [$pattern_path], true)) {
				return true;
			}
		}

		return false;
	}
}
