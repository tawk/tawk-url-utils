<?php
namespace Tawk;

class Helper {
	/**
	 * Decodes and removes leading and trailing spaces for provided url.
	 */
	public static function clean_url($url) {
		return trim(urldecode($url));
	}
}
