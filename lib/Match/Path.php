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

	public static function get_chunks($path) {
		$chunks = explode('/', $path);
		$filtered_chunks = array_filter($chunks, function ($item) {
			return empty($item) !== true;
		});
		return array_values($filtered_chunks);
	}

	private static function match_chunks($current_path_chunks, $pattern_path_chunks, $is_middle = false) {
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

	private static function match_chunks_reverse($current_path_chunks, $pattern_path_chunks, $is_middle = false) {
		$offset = count($current_path_chunks) - count($pattern_path_chunks);
		for ($i = count($current_path_chunks) - 1; $i >= 0; $i--) {
			$current_path_chunk = $current_path_chunks[$i];
			$pattern_path_chunk = $pattern_path_chunks[$i - $offset];

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

	public static function match($current_path, $pattern_paths = []) {
		$current_path_chunks = Path::get_chunks($current_path);

		foreach($pattern_paths as $pattern_path) {
			$pattern_path_chunks = Path::get_chunks($pattern_path);

			// if it does not have wildcard
			if (!Path::has_wildcard($pattern_path)) {
				if (join('/', $current_path_chunks) === join('/', $pattern_path_chunks)) {
					return true;
				};

				continue;
			}

			// if wildcard is at the start
			if (Path::is_wildcard($pattern_path_chunks[0])) {
				if (Path::match_chunks_reverse($current_path_chunks, $pattern_path_chunks)) {
					return true;
				};

				continue;
			}

			// if wildcard is at the end
			if (Path::is_wildcard(end($pattern_path_chunks))) {

				// invalidates if the current path is /path/to/somewhere
				// and the pattern is /path/to/somewhere/*
				if (count($current_path_chunks) < count($pattern_path_chunks)) {
					continue;
				}

				if (Path::match_chunks($current_path_chunks, $pattern_path_chunks)) {
					return true;
				}

				continue;
			}

			// if wildcard is at the middle
			if (Path::match_chunks($current_path_chunks, $pattern_path_chunks, true)) {
				return true;
			};
		}

		return false;
	}
}
