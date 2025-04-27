=== Next Meetup Hint ===
Contributors: threadi
Tags: meetup, meetup event
Requires at least: 4.9.24
Tested up to: 6.8
Requires PHP: 8.0
License: GPL-2.0-or-later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Stable tag: @@VersionNumber@@

Display the next WordPress meeting in your region as a notice on every backend page.

== Description ==

Display the next WordPress meeting in your region as a notice on every backend page. Specify which users in your project may see and use the notice. Define how long in advance the notice should be displayed.

= Background =

This plugin was created at the WordPress Meetup in Leipzig on October 1, 2024 as part of a presentation on plugin development.

Video from the presentation is on [YouTube](https://www.youtube.com/watch?v=8QUesHXOXCs).

= Repository =

The development repository is on [GitHub](https://github.com/threadi/next-meetup-hint).

---

== Installation ==

1. Upload "next-meetup-hint" to the "/wp-content/plugins/" directory.
2. Activate the plugin through the "Plugins" menu in WordPress.

== Frequently Asked Questions ==

= Where does the Meetup data come from? =

The plugin uses the function included in WordPress to retrieve Meetup events from an API provided by wordpress.org.

== Changelog ==

= @@VersionNumber@@ =
- Added settings for user management to define who could see the next meetup hints
- Added link to support forum in plugin list
- Added option for set the global days were hint it shown before the meetup
- Added rate hint on settings page
- Added versioning for templates of this plugin
- Added options to show location on GoogleMap, OpenStreetMap and/or BingMap
- Added info popup to inform user why they see this hint
- Added hooks
- Use of PHP Strict with PhpStan check to prevent any PHP-side errors
- Update GitHub action for simplified release management
- Switched to new changelog format
- Optimizations for more speed
- Appearance of the hint optimized
- Fixed wrong usage of user-specific timeframe

[older changes](https://github.com/threadi/next-meetup-hint/blob/master/changelog.md)
