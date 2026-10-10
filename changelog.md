# Changelog

## [3.2.0] - 10.10.2026

### Added

- Added GitHub workflows for code checks, smoke test in WordPress Playground and hook documentation
- Added SBOM to each release

### Changed

- Events are now cached per user for one hour and only requested when a hint can be shown, not on every AJAX request
- Events are only requested for users who have saved a location in the dashboard widget
- Scripts and styles are only loaded where they are used
- The filter "next_meetup_hint_set_template_directory" now also accepts absolute paths
- Release build now uses pinned actions, minimal permissions and an explicit list of files
- WordPress 6.6 is now minimum required

### Fixed

- Fixed empty settings page in WordPress 7.0 or newer: the settings were not available in the REST API
- Fixed resetting the user settings when an administrator edits the profile of another user
- Fixed loading all users on every request
- Fixed the hint and ICS of one user being shown to other users
- Fixed possible error with unexpected values in the settings "Roles" and "Users"
- Fixed missing default settings after an update
- Fixed wrong time and missing UID in the ICS file, now valid according to RFC 5545
- Fixed displayed end of the hidden period in the user profile
- Fixed blank page if the ICS download, or the "show again" link could not be used
- Fixed invalid HTML of the hint
- Fixed uninstall, which did not remove the settings

## [3.1.3] - 25.08.2026

### Changed

- Now using DataView for the settings for WordPress >= 7.0
- Updated settings library

## [3.1.2] - 02.08.2026

### Changed

- Updated settings lib to 3.0.0
- PHP 8.2 is now minimum required
- Updated the usage of WordPress Coding Standard checks during the release
- Set compatibility with WordPress 7.1

## [3.1.1] - 26.04.2026

### Changed

- Optimized composer settings
- Updated dependencies

## [3.1.0] - 14.05.2025

### Added

- Added ICS download for the meetup

## [3.0.0] - 27.04.2025

### Added

- Added settings for user management to define who could see the next meetup hints
- Added link to support forum in plugin list
- Added option for set the global days were hint it shown before the meetup
- Added rate hint on settings page
- Added versioning for templates of this plugin
- Added options to show location on GoogleMap, OpenStreetMap and/or BingMap
- Added info popup to inform user why they see this hint
- Added hooks

### Changed

- Use of PHP Strict with PhpStan check to prevent any PHP-side errors
- Update GitHub action for simplified release management
- Switched to new changelog format
- Optimizations for more speed
- Appearance of the hint optimized

### Fixed

- Fixed wrong usage of user-specific timeframe

## [2.0.0] - 24.11.2024

### Added

- Added option on user profile to hide the hints
- Added option on user profile to set the days before the hint is visible
- Added option to user-specific hide the hint for 2 days
- Added template for the hint
- Added location to the hint

### Fixed

- Fixed next date calculation

## [1.0.2] - 03.10.2024

### Fixed

- Fixed potential error from event list

## [1.0.1] - 02.10.2024

### Changed

- WCS compatible
- WordPress-VIP-Go compatible
- Changed required WP-version tag to 4.9.24

## [1.0.0] - 01.10.2024

### Added

- Initial release
