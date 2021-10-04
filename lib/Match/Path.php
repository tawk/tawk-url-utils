<?php
namespace Tawk\Match;

define('WILDCARD', '*');

class Path {
	public static function get_wildcard() {
		return WILDCARD;
	}

	private static function check_str_for($str, $val) {
		return strpos($str, $val) !== false;
	}

	private static function has_wildcard($pattern) {
		return Path::check_str_for($pattern, WILDCARD);
	}

	private static function is_wildcard($pattern_chunk) {
		return $pattern_chunk === WILDCARD;
	}

	public static function get_path_chunks($path) {
		$chunks = explode('/', $path);
		$filtered_chunks = array_filter($chunks, function ($item) {
			return empty($item) !== true;
		});
		return array_values($filtered_chunks);
	}

	private static function match_path_chunks($current_path_chunks, $pattern_path_chunks, $is_middle = false) {
		for ($i = 0; $i < count($current_path_chunks); $i++) {
			$current_path_chunk = $current_path_chunks[$i];
			$pattern_path_chunk = $pattern_path_chunks[$i];

			if (Path::is_wildcard($pattern_path_chunk)) {
				if ($is_middle) {
					continue;
				}

				break;
			}

			if ($pattern_path_chunk !== $current_path_chunk) {
				return false;
			}
		}

		return true;
	}

	private static function match_path_chunks_reverse($current_path_chunks, $pattern_path_chunks, $is_middle = false) {
		$offset = count($current_path_chunks) - count($pattern_path_chunks);
		for ($i = count($current_path_chunks); $i >= 0; $i--) {
			$current_path_chunk = $current_path_chunks[$i];
			$pattern_path_chunk = $pattern_path_chunks[$i];

			if ($offset > 0) {
				$pattern_path_chunk = $pattern_path_chunks[$i - $offset];
			}

			if (Path::is_wildcard($pattern_path_chunk)) {
				if ($is_middle) {
					continue;
				}

				break;
			}

			if ($pattern_path_chunk !== $current_path_chunk) {
				return false;
			}
		}

		return true;
	}

	public static function match_path($current_path, $pattern_path) {
		$current_path_chunks = Path::get_path_chunks($current_path);
		$pattern_path_chunks = Path::get_path_chunks($pattern_path);

		if (count($current_path_chunks) < count($pattern_path_chunks)) {
			return false;
		}

		// if it does not have wildcard
		if (!Path::has_wildcard($pattern_path)) {
			return join('/', $current_path_chunks) === join('/', $pattern_path_chunks);
		}

		// if wildcard is at the start
		if (Path::is_wildcard($pattern_path_chunks[0])) {
			return Path::match_path_chunks_reverse($current_path_chunks, $pattern_path_chunks);
		}

		// if wildcard is at the end
		if (Path::is_wildcard(end($pattern_path_chunks))) {
			return Path::match_path_chunks($current_path_chunks, $pattern_path_chunks);
		}

		// if wildcard is at the middle
		return Path::match_path_chunks($current_path_chunks, $pattern_path_chunks, true);
	}
}
