<?php

namespace Tawk\Modules;

use Tawk\Enums\WildcardLocation;
use Tawk\Helpers\PathHelper;

class PathPatternMatcher {
	/**
	 * Iterates over the current and pattern path chunks and matches them
	 */
	private static function match_chunks($current_path_chunks, $pattern_path_chunks, $is_middle) {
		for ($i = 0; $i < count($current_path_chunks); $i++) {
			$current_path_chunk = $current_path_chunks[$i];
			$pattern_path_chunk = $pattern_path_chunks[$i];

			if (PathHelper::is_wildcard($pattern_path_chunk)) {
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
	private static function match_chunks_reverse($current_path_chunks, $pattern_path_chunks, $is_middle) {
		$offset = count($current_path_chunks) - count($pattern_path_chunks);
		for ($i = count($current_path_chunks) - 1; $i >= 0; $i--) {
			$current_path_chunk = $current_path_chunks[$i];
			$pattern_path_chunk = $pattern_path_chunks[$i - $offset];

			if (PathHelper::is_wildcard($pattern_path_chunk)) {
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
	 * Matches current path to multiple patterns
	 */
	public static function match($current_path_chunks, $pattern_paths_chunks) {
		foreach($pattern_paths_chunks as $pattern_path_chunks) {
			$wildcard_loc = PathHelper::get_wildcard_location_by_chunks($pattern_path_chunks);

			// invalidates if the current path is /path/to/somewhere
			// and the pattern is /path/to/somewhere/*
			if (count($current_path_chunks) < count($pattern_path_chunks)) {
				continue;
			}

			// splitted the paths first before matching them to handle paths that have
			// leading and trailing slashes
			if ($wildcard_loc === WildcardLocation::NONE) {
				if (join('/', $current_path_chunks) === join('/', $pattern_path_chunks)) {
					return true;
				};

				continue;
			}

			// if wildcard is at the start, match in reverse order so that the wildcard is at the end
			if ($wildcard_loc === WildcardLocation::START &&
				self::match_chunks_reverse($current_path_chunks, $pattern_path_chunks, false) === true) {
				return true;
			}

			if ($wildcard_loc === WildcardLocation::END &&
				self::match_chunks($current_path_chunks, $pattern_path_chunks, false) === true) {
				return true;
			}

			// matching with wildcard at the middle
			if (self::match_chunks($current_path_chunks, $pattern_path_chunks, true)) {
				return true;
			}
		}

		return false;
	}
}
