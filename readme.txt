=== Next Meetup Hint ===
Contributors: threadi
Tags: meetup, meetup event
Requires at least: 4.9.24
Tested up to: 6.8
Requires PHP: 8.0
License: GPL-2.0-or-later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Stable tag: 2.0.0

Show the next meetup in your region as hint in every backend page.

== Description ==

Show the next meetup in your region as hint in every backend page.

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

== Screenshots ==

1.

== Changelog ==

= 1.0.0 =
* Initial release

= 1.0.1 =
* WCS compatible
* WordPress-VIP-Go compatible
* Fixed required WP-version tag to 4.9.24

= 1.0.2 =
* Fixed potential error from event list

= 2.0.0 =
* Added option on user profile to hide the hints
* Added option on user profile to set the days before the hint is visible
* Added option to user-specific hide the hint for 2 days
* Added template for the hint
* Added location to the hint
* Fixed next date calculation

= 2.1.0 =
* Use of PHP Strict with PhpStan check to prevent any PHP-side errors
