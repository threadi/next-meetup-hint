=== Next Meetup Hint ===
Contributors: threadi
Tags: meetup, meetup event
Requires at least: 6.6
Tested up to: 7.2
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
- Set compatibility for WordPress 7.2
- Updated dependencies

[older changes](https://github.com/threadi/next-meetup-hint/blob/master/changelog.md)
