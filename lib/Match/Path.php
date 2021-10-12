<?php
namespace Tawk\Match;

define('WILDCARD', '*');

class Path {
	/**
	 * Gets the WILDCARD constant
	 */
	public static function get_wildcard() {
		return WILDCARD;
	}

	/**
	 * Checks if the string contains the provided value
	 */
	private static function check_str_for($str, $val) {
		return strpos($str, $val) !== false;
	}

	/**
	 * Checks if the pattern provided has a wildcard
	 */
	private static function has_wildcard($pattern) {
		return Path::check_str_for($pattern, WILDCARD);
	}

	/**
	 * Checks if the pattern chunk provided is a wildcard
	 */
	private static function is_wildcard($pattern_chunk) {
		return $pattern_chunk === WILDCARD;
	}

	/**
	 * Gets the path chunks from the provided path
	 */
	public static function get_chunks($path) {
		$chunks = explode('/', $path);
		$filtered_chunks = array_filter($chunks, function ($item) {
			return empty($item) !== true;
		});
		return array_values($filtered_chunks);
	}

	/**
	 * Iterates over the current and pattern path chunks and matches them
	 */
	private static function match_chunks($current_path_chunks, $pattern_path_chunks, $is_middle = false) {
		for ($i = 0; $i < count($current_path_chunks); $i++) {
			$current_path_chunk = $current_path_chunks[$i];
			$pattern_path_chunk = $pattern_path_chunks[$i];

			if (Path::is_wildcard($pattern_path_chunk)) {
				// Do not stop the loop if the wildcard's at the middle.
				// Only skip the chunk since it still needs to check the other
				// chunks if they match or not.
				if ($is_middle) {
					continue;
				}

				break;
			}

			// stop matching if one of the chunks doesn't match.
			if ($pattern_path_chunk !== $current_path_chunk) {
				return false;
			}
		}

		return true;
	}

	/**
	 * Iterates over the current and pattern path chunks and matches them in reverse
	 */
	private static function match_chunks_reverse($current_path_chunks, $pattern_path_chunks, $is_middle = false) {
		$offset = count($current_path_chunks) - count($pattern_path_chunks);
		for ($i = count($current_path_chunks) - 1; $i >= 0; $i--) {
			$current_path_chunk = $current_path_chunks[$i];
			$pattern_path_chunk = $pattern_path_chunks[$i - $offset];

			if (Path::is_wildcard($pattern_path_chunk)) {
				// Do not stop the loop if the wildcard's at the middle.
				// Only skip the chunk since it still needs to check the other
				// chunks if they match or not.
				if ($is_middle) {
					continue;
				}

				break;
			}

			// stop matching if one of the chunks doesn't match.
			if ($pattern_path_chunk !== $current_path_chunk) {
				return false;
			}
		}

		return true;
	}

	/**
	 * Matches the current path and the patterns that are provided
	 */
	public static function match($current_path, $pattern_paths = []) {
		$current_path_chunks = Path::get_chunks($current_path);

		foreach($pattern_paths as $pattern_path) {
			$pattern_path_chunks = Path::get_chunks($pattern_path);

			// if it does not have wildcard, match both of the paths provided.
			if (!Path::has_wildcard($pattern_path)) {
				if (join('/', $current_path_chunks) === join('/', $pattern_path_chunks)) {
					return true;
				};

				continue;
			}

			// if wildcard is at the start, match the paths by splitting them into chunks
			// but in reverse order so that the wildcard is at the end
			if (Path::is_wildcard($pattern_path_chunks[0])) {
				if (Path::match_chunks_reverse($current_path_chunks, $pattern_path_chunks)) {
					return true;
				};

				continue;
			}

			// if wildcard is at the end, match the paths by splitting them into chunks
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

			// if wildcard is at the middle, match the paths by splitting them into chunks
			if (Path::match_chunks($current_path_chunks, $pattern_path_chunks, true)) {
				return true;
			};
		}

		return false;
	}
}
