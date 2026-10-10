=== Next Meetup Hint ===
Contributors: threadi
Tags: meetup, meetup event
Requires at least: 6.6
Tested up to: 7.1
Requires PHP: 8.2
License: GPL-2.0-or-later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Stable tag: @@VersionNumber@@

Display the next WordPress meeting in your region as a notice on every backend page.

== Description ==

Display the next WordPress meeting in your region as a notice on every backend page. Specify, which users in your project may see and use the notice. Define how long in advance the notice should be displayed.

= Background =

This plugin was created at the WordPress Meetup in Leipzig on October 1, 2024, as part of a presentation on plugin development.

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

= Which data is sent to wordpress.org? =

The plugin uses the location each user has saved in the dashboard widget "WordPress Events and News". WordPress sends this location to api.wordpress.org to get the events nearby, as the widget itself does. If a user has not saved a location yet, the plugin requests nothing for this user. The result is saved for one hour per user.

== Changelog ==

= @@VersionNumber@@ =
- Events are now cached per user and only requested when a hint can be shown
- Events are only requested for users who have saved a location in the dashboard widget
- ICS file is now valid for all calendars and uses the correct time
- Fixed empty settings page in WordPress 7.0 or newer
- Fixed resetting the user settings when an administrator edits another user
- Fixed loading all users on every request
- Fixed possible error with unexpected values of the settings
- Uninstalling now removes all settings, also in multisites
- WordPress 6.6 is now minimum required

[older changes](https://github.com/threadi/next-meetup-hint/blob/master/changelog.md)
