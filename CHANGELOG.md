# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

---

## [1.0.0] – 2025-05-28

### Added
- Initial public release
- Declarative schema validation for `.env` files
- Supported types: `string`, `int`, `bool`, `float`
- Support for `required`, `default`, `allowed`, `pattern` rules
- Typed error handling via exceptions
- PSR-4 autoloading via Composer
- PHPUnit 12 support
- PHPStan configuration
- README with installation and usage guide

---

### Documentation-only updates (not versioned)
- Added CLI usage example in `examples/validate-env.php`
- Updated README to include CLI instructions
