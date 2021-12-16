<?php

namespace Tawk\Helpers;

use Tawk\Enums\WildcardLocation;

define('WILDCARD', '*');

class PathHelper {
	/**
	 * Gets the WILDCARD constant
	 *
	 * @return string `*` wildcard
	 */
	public static function get_wildcard() {
		return WILDCARD;
	}

	/**
	 * Checks if provided path starts with the WILDCARD or /.
	 * If it is, it is considered as a path.
	 *
	 * @param  string $path - URL path
	 * @return boolean Returns `true` if it's a path. Otherwise, `false`.
	 */
	public static function is_path($path) {
		return strpos($path, WILDCARD) === 0 || strpos($path, '/') === 0;
	}

	/**
	 * Checks if the pattern chunks provided has a wildcard
	 *
	 * @param  string[] $path_chunks - URL path chunks
	 * @return boolean Returns `true` if wildcard is in path chunks. Otherwise, `false`.
	 */
	public static function path_chunks_has_wildcard($path_chunks) {
		return in_array(WILDCARD, $path_chunks, true);
	}

	/**
	 * Checks if the pattern chunk provided is a wildcard
	 *
	 * @param  string $path_chunk - URL path chunk
	 * @return boolean Returns `true` if current path chunk is a wildcard. Otherwise, `false`.
	 */
	public static function is_wildcard($path_chunk) {
		return $path_chunk === WILDCARD;
	}

	/**
	 * Gets the path chunks from the provided path
	 *
	 * @param  string $path - URL path
	 * @return string[] Returns path in chunks
	 */
	public static function get_chunks($path) {
		$chunks = explode('/', $path);
		$filtered_chunks = array_filter($chunks, function ($item) {
			return empty($item) !== true;
		});
		return array_values($filtered_chunks);
	}

	/**
	 * Identifies where the wildcard is located in the provided chunks
	 *
	 * @param string[] $path_chunks - URL path chunks
	 * @return WildcardLocation Returns where the wildcard is located
	 */
	public static function get_wildcard_location_by_chunks($path_chunks) {
		if (is_array($path_chunks) === false) {
			return WildcardLocation::NONE;
		}

		if (self::path_chunks_has_wildcard($path_chunks) === false) {
			return WildcardLocation::NONE;
		}

		if (self::is_wildcard($path_chunks[0])) {
			return WildcardLocation::START;
		}

		if (self::is_wildcard(end($path_chunks))) {
			return WildcardLocation::END;
		}

		return WildcardLocation::MIDDLE;
	}
}
