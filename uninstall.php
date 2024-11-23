<?php
/**
 * Tasks to run during uninstallation of this plugin.
 *
 * @package next-meetup-hint
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// if uninstall.php is not called by WordPress, die.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	die;
}

// delete our transient, if set.
delete_transient( 'next_meetup_hint' );

// delete settings on user profiles.
foreach( get_users() as $user ) {
	delete_user_meta( $user->ID, 'hide_next_meet_hint' );
	delete_user_meta( $user->ID, 'next_meet_hint_days' );
}
