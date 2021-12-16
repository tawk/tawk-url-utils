<?php

namespace Tawk\Modules;

use Tawk\Enums\WildcardLocation;
use Tawk\Helpers\PathHelper;

class PathPatternMatcher {
	/**
	 * Matches provided path to multiple patterns
	 *
	 * @param  string[] $current_path_chunks - Current path in chunks
	 * @param  string[][] $pattern_paths_chunks - Multiple pattern paths in chunks
	 * @return boolean Returns `true` if current path matches with one of the patterns. Otherwise, `false`.
	 */
	public static function match($current_path_chunks, $pattern_paths_chunks) {
		foreach($pattern_paths_chunks as $pattern_path_chunks) {
			$wildcard_loc = PathHelper::get_wildcard_location_by_chunks($pattern_path_chunks);

			if ($wildcard_loc === WildcardLocation::NONE) {
				if (join('/', $current_path_chunks) === join('/', $pattern_path_chunks)) {
					return true;
				};
			} else if ($wildcard_loc === WildcardLocation::START) {
				// if wildcard is at the start, match in reverse order so that the wildcard is at the end
				if (self::match_chunks_reverse($current_path_chunks, $pattern_path_chunks) === true) {
					return true;
				}
			} else {
				if (self::match_chunks($current_path_chunks, $pattern_path_chunks) === true) {
					return true;
				}
			}
		}

		return false;
	}

	/**
	 * Iterates over the current and pattern path chunks and matches them
	 *
	 * @param  string[] $current_path_chunks - Current path in chunks
	 * @param  string[] $pattern_path_chunks - Pattern path in chunks
	 * @return boolean Returns `true` if current path matches with the pattern. Otherwise, `false`.
	 */
	private static function match_chunks($current_path_chunks, $pattern_path_chunks) {
		$wildcard_loc = PathHelper::get_wildcard_location_by_chunks($pattern_path_chunks);

		$current_path_len = count($current_path_chunks);
		$pattern_path_len = count($pattern_path_chunks);

		// handles empty current path
		if ($current_path_len === 0) {
			// match if pattern path is also empty
			if ($pattern_path_len === 0) {
				return true;
			}

			// match if pattern path is only a wildcard
			if ($pattern_path_len === 1 && PathHelper::is_wildcard($pattern_path_chunks[0])) {
				return true;
			}

			// else, do not match since there's nothing to match with
			return false;
		}

		for ($i = 0; $i < $current_path_len; $i++) {
			$current_path_chunk = $current_path_chunks[$i];

			// handles current paths that are longer than the pattern
			// ex.
			//   path - /path/to/somewhere/longer
			//   pattern - /path/*/somewhere
			if (isset($pattern_path_chunks[$i]) === false) {
				return false;
			}

			$pattern_path_chunk = $pattern_path_chunks[$i];

			if (PathHelper::is_wildcard($pattern_path_chunk)) {
				// Do not stop the loop if the wildcard's at the middle.
				// Only skip the chunk since it still needs to check the other
				// chunks if they match or not.
				if ($wildcard_loc === WildcardLocation::MIDDLE) {
					continue;
				}

				return true;
			}

			// stop matching if one of the chunks doesn't match.
			if ($pattern_path_chunk !== $current_path_chunk) {
				return false;
			}

			// handle similar current and pattern paths but pattern has
			// ending wildcard
			// ex.
			//   path - /path/to/somewhere
			//   pattern - /path/to/somewhere/*
			//
			// check the next pattern chunk if it's a wildcard
			if ($i + 1 === $current_path_len && isset($pattern_path_chunks[$i + 1])) {
				return PathHelper::is_wildcard($pattern_path_chunks[$i + 1]);
			}
		}

		return true;
	}

	/**
	 * Iterates over the current and pattern path chunks and matches them in reverse
	 *
	 * @param  string[] $current_path_chunks - Current path in chunks
	 * @param  string[] $pattern_path_chunks - Pattern path in chunks
	 * @return boolean Returns `true` if current path matches with the pattern. Otherwise, `false`.
	 */
	private static function match_chunks_reverse($current_path_chunks, $pattern_path_chunks) {
		$wildcard_loc = PathHelper::get_wildcard_location_by_chunks($pattern_path_chunks);
		$current_path_len = count($current_path_chunks);
		$pattern_path_len = count($pattern_path_chunks);

		// handles empty current path
		if ($current_path_len === 0) {
			// match if pattern path is also empty
			if ($pattern_path_len === 0) {
				return true;
			}

			// match if pattern path is only a wildcard
			if ($pattern_path_len === 1 && PathHelper::is_wildcard($pattern_path_chunks[0])) {
				return true;
			}

			// else, do not match since there's nothing to match with
			return false;
		}

		// handles pattern paths that are longer than the current path
		// ex.
		//   path - /path/to/somewhere
		//   pattern - */to/somewhere/longer
		if ($pattern_path_len > $current_path_len) {
			return false;
		}

		$offset = $current_path_len - $pattern_path_len;
		for ($i = $current_path_len - 1; $i >= 0; $i--) {
			$current_path_chunk = $current_path_chunks[$i];

			// handles current paths that are longer than the pattern
			// ex.
			//   path - /path/to/somewhere/longer
			//   pattern - */somewhere
			if (isset($pattern_path_chunks[$i - $offset]) === false) {
				return false;
			}

			$pattern_path_chunk = $pattern_path_chunks[$i - $offset];

			if (PathHelper::is_wildcard($pattern_path_chunk)) {
				// Do not stop the loop if the wildcard's at the middle.
				// Only skip the chunk since it still needs to check the other
				// chunks if they match or not.
				if ($wildcard_loc === WildcardLocation::MIDDLE) {
					continue;
				}

				return true;
			}

			// stop matching if one of the chunks doesn't match.
			if ($pattern_path_chunk !== $current_path_chunk) {
				return false;
			}
		}

		return true;
	}
}
