# Tawk URL Utils Module

## Overview

A tawk.to utility library for URLs.

## Modules

### Url Pattern Matcher

This module handles matching of the provided URL to the provided patterns.

#### match(string $current_url, array $patterns)

Matches the provided url and patterns. Returns `true` if it matches. Otherwise, `false`.

##### Example

```php
<?php
use Tawk\Modules\UrlPatternMatcher;

$current_url = 'http://www.example.com/path/to/somewhere';
$patterns = array('http://www.example.com/path/to/somewhere');

$match_result = UrlPatternMatcher::match($current_url, $patterns);
```

### Path Pattern Matcher

This module handles matching of the provided URL path to the provided patterns by matching them per chunk.

#### match(array $current_path_chunks, array $pattern_paths_chunks)

Matches the provided url path and patterns. Returns `true` if it matches. Otherwise, `false`.

```php
<?php
use Tawk\Helpers\PathHelper;
use Tawk\Modules\PathPatternMatcher;

$current_url = PathHelper::get_chunks('/path/to/somewhere'); // or array('path', 'to', 'somewhere');
$patterns = array(
	PathHelper::get_chunks('/path/to/somewhere') // or array('path', 'to', 'somewhere')
);

$match_result = PathPatternMatcher::match($current_url, $patterns);
```

### Additional Info

#### Valid Patterns for Pattern Matchers

- `*`
- `*/to/somewhere`
- `/path/*/somewhere`
- `/path/*/lead/*/somewhere`
- `/path/*/*/somewhere`
- `/path/to/*`
- `http://www.example.com/`
- `http://www.example.com/*`
- `http://www.example.com/*/to/somewhere`
- `http://www.example.com/path/*/somewhere`
- `http://www.example.com/path/*/lead/*/somewhere`
- `http://www.example.com/path/*/*/somewhere`
- `http://www.example.com/path/to/*`

#### Invalid Patterns for Pattern Matchers

- `path/*/somewhere` - "path" will be considered as a host and not a start of a path.
- `*/should/*/to/*` - wildcards at multiple positions are not valid.
