<?php

namespace Tawk\Helpers;

use Tawk\Enums\WildcardLocation;

define('WILDCARD', '*');

class PathHelper {
	/**
	 * Gets the WILDCARD constant
	 */
	public static function get_wildcard() {
		return WILDCARD;
	}

	/**
	 * Checks if provided path starts with the WILDCARD or /.
	 * If it is, it is considered as a path.
	 */
	public static function is_path($path) {
		return strpos($path, WILDCARD) === 0 || strpos($path, '/') === 0;
	}

	/**
	 * Checks if the pattern chunks provided has a wildcard
	 */
	public static function path_chunks_has_wildcard($path_chunks) {
		return in_array(WILDCARD, $path_chunks, true);
	}

	/**
	 * Checks if the pattern chunk provided is a wildcard
	 */
	public static function is_wildcard($path_chunk) {
		return $path_chunk === WILDCARD;
	}

	/**
	 * Gets the path chunks from the provided path
	 */
	public static function get_chunks($path) {
		if ($path === '/') {
			return array('');
		}

		$chunks = explode('/', $path);
		$filtered_chunks = array_filter($chunks, function ($item) {
			return empty($item) !== true;
		});
		return array_values($filtered_chunks);
	}

	/**
	 * Identifies where the wildcard is located in the provided chunks
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
