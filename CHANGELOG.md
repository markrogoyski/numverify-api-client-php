# Numverify API Client PHP Change Log

## v3.0.0 - 2026-03-08

### Breaking Changes
* Minimum PHP version updated from 7.2 to 8.2

### Improvements
* Added explicit nullable type to `Api` constructor parameter (PHP 8.2 deprecation)
* Added `mixed` return type to `Collection::key()` iterator method
* Added explicit `psr/http-message` as a direct dependency
* Updated CI to test PHP 8.2, 8.3, 8.4, 8.5
* CI reorganized into separate jobs for unit tests (all PHP versions), static analysis (PHP 8.5), and code coverage (PHP 8.5)

### Static Analysis
* Added Psalm (level 1)
* Added Composer Require Checker
* Added Composer Unused
* Updated PHPStan to ^2.0 (level max)
* Updated PHP_CodeSniffer to ^3.10
* Organized all static analysis configs under `tools/` directory
* Removed deprecated `phploc`
