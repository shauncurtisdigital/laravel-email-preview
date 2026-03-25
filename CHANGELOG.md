# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [2.0.0] - 2026-03-25

### Added
- **Class-based preview architecture** - Define previews using classes that implement `EmailPreview` interface
- **Laravel Mailable support** - Preview and send Laravel Mailables directly (including Markdown mail)
- **PreviewResult data object** - Type-safe preview result handling for both views and mailables
- **GenericPreviewMailable** - Internal package mailable for sending view-based previews
- **Config caching support** - Config is now fully serializable (no closure support)
- **Better error messages** - Clear error messages for missing classes, interface violations, etc.
- **Comprehensive documentation** - New README, UPGRADE guide, and examples
- **Example preview classes** - Three example classes showing different use cases
- **Enhanced test suite** - Tests for both class-based and legacy array formats

### Changed
- **Config structure** - Previews now reference class names instead of inline arrays
- **Controller refactored** - Complete rewrite to support both preview types
- **Rendering logic** - Different rendering approaches for views vs mailables
- **Config key renamed** - `test_recipient` → `default_to`
- **Environment variable renamed** - `MAIL_PREVIEW_ENABLED` → `EMAIL_PREVIEW_ENABLED`
- **Route parameter renamed** - `{type}` → `{preview}` for clarity

### Deprecated
- **Closures in config** - No longer supported; use preview classes for dynamic data
- **Legacy array format** - Still works but class-based approach is recommended

### Removed
- **Closure support** - Config must be serializable for Laravel config caching

### Fixed
- **Config caching compatibility** - Package now works with `php artisan config:cache`
- **Mailable rendering** - Mailables now render correctly with all Laravel mail components

### Security
- Maintained environment-based access control
- Routes only registered in allowed environments
- Emails only sent to configured test recipient

## [1.0.0] - Previous Release

### Added
- Initial release
- Basic Blade view preview support
- Send functionality
- Environment-based access control
- Configurable routes and middleware

[2.0.0]: https://github.com/shauncurtisdigital/laravel-email-preview/compare/v1.0.0...v2.0.0
[1.0.0]: https://github.com/shauncurtisdigital/laravel-email-preview/releases/tag/v1.0.0
