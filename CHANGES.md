# Changelog

All notable changes to `mod_methodos` will be documented in this file.

## v1.1.5 - 2026-09-21

### Fixed
- Fixed XMLDB schema namespace definition in `db/install.xml` for strict Moodle Plugin CI (`moodle-plugin-ci validate`) table parser compliance.
- Added explicit capability language strings `methodos:addinstance` and `methodos:view` in `lang/en/methodos.php`.
- Added localized configuration warning string `configwarning`.
- Bumped plugin release to `v1.1.5` (version `2026092105`).

## v1.1.4 - 2026-09-21

### Fixed
- Implemented full Moodle 2 Course Backup & Restore handlers in `backup/moodle2/` (`backup_methodos_activity_task.class.php`, `backup_methodos_stepslib.php`, `restore_methodos_activity_task.class.php`, `restore_methodos_stepslib.php`) resolving course backup crashes.
- Fixed LTI tool preconfiguration and shadow LTI mapping for project token parameters.
- Broadened Moodle compatibility support to Moodle 4.1.0 LTS through 4.5+.
- Added vector and high-resolution activity icons in `pix/icon.svg` and `pix/icon.png`.
- Updated Moodle privacy subsystem metadata strings.

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
