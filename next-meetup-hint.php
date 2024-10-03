<?php
/**
 * Plugin Name:       Next Meetup Hint
 * Description:       Show hint for next meetup.
 * Requires at least: 4.9.24
 * Requires PHP:      8.0
 * Version:           1.0.2
 * Author:            Thomas Zwirner
 * Author URI:        https://www.thomaszwirner.de
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       next-meetup-hint
 *
 * @package next-meetup-hint
 */

/**
 * Initialize this plugin in backend: load the events and get the event within the next 14 days.
 *
 * @return void
 */
function next_meetup_hint_init(): void {
	require_once ABSPATH . 'wp-admin/includes/class-wp-community-events.php';

	$user_id        = get_current_user_id();
	$saved_location = get_user_option( 'community-events-location', $user_id );
	$events_client  = new WP_Community_Events( $user_id, $saved_location );
	$events         = $events_client->get_events();

	// bail if no events found.
	if ( empty( $events ) ) {
		return;
	}

    // bail if it is a wp error.
    if( is_wp_error( $events ) ) {
        return;
    }

	// bail if no event list is found.
	if ( empty( $events['events'] ) ) {
		return;
	}

	// marker for next date.
	$next_date = false;

	// check if next date is in 14 days.
	foreach ( $events['events'] as $event ) {
		// bail if no date given.
		if ( empty( $event['date'] ) ) {
			continue;
		}

		// will the event take place in the next 14 days?
		if ( strtotime( $event['date'] ) < ( time() * 14 * 86400 ) && ! $next_date ) {
			$next_date = $event;
		}
	}

	// if next date has been found, show hint.
	if ( $next_date ) {
		set_transient( 'next_meetup_hint', $next_date );
	}
}
add_action( 'admin_init', 'next_meetup_hint_init' );

/**
 * Show hint for next meetup.
 *
 * @return void
 */
function next_meetup_hint_notice(): void {
	$event = get_transient( 'next_meetup_hint' );
	if ( $event ) {
		?>
		<div class="next_meetup_hint updated">
			<h3><?php echo esc_html__( 'Next Meetup in your location', 'next-meetup-hint' ); ?></h3>
			<h4><?php echo esc_html( $event['title'] ); ?></h4>
			<p>
				<?php
				echo esc_html( gmdate( 'd.m.Y H:i', strtotime( $event['date'] ) ) );
				echo '<br><a href="' . esc_url( $event['url'] ) . '" target="_blank">' . esc_html__( 'get more info', 'next-meetup-hint' ) . '</a>';
				?>
			</p>
		</div>
		<?php

		delete_transient( 'next_meetup_hint' );
	}
}
add_action( 'admin_notices', 'next_meetup_hint_notice' );
