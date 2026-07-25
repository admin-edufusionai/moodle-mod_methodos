# Changelog

All notable changes to `mod_methodos` will be documented in this file.

## v1.0.1 - 2026-07-15

### Fixed
- Added missing GNU GPL v3 `COPYING.txt` package-level license file.
- Added required `backup/moodle2/` backup and restore class stubs.
- Injected Moodle GPL boilerplate (`// This file is part of Moodle`) into all PHP source files.
- Renamed `$errorMsg` to `$errormsg` (Moodle snake_case naming convention).
- Replaced hardcoded `'Configuration warning:'` string with `get_string('configwarning', 'mod_methodos', ...)`.
- Added missing capability language strings: `methodos:addinstance`, `methodos:view`, `configwarning`.
- Refactored N+1 database query in `lti_helper.php` to use a single bulk `get_records()` call.
- Updated `@copyright` tags across all source files to include contact email address.

## v1.0.0 - 2026-06-25

### Added
- Initial release of the Methodos Peer Review activity module.
- LTI 1.3 integration with automatic tool registration via `lti_helper.php`.
- Admin settings page with live credential display (Client ID, Deployment ID, Moodle endpoints).
- Copy-to-clipboard buttons for all LTI credentials.
- Privacy API provider stub (`classes/privacy/provider.php`).
- Course module viewed event (`classes/event/course_module_viewed.php`).
- Site-level and module-level capability definitions (`mod/methodos:addinstance`, `mod/methodos:view`).
- English language strings with full admin guide descriptions.
