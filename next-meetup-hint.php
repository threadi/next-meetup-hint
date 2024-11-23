<?php
/**
 * Plugin Name:       Next Meetup Hint
 * Description:       Show hint for next meetup.
 * Requires at least: 4.9.24
 * Requires PHP:      8.0
 * Version:           1.1.0
 * Author:            Thomas Zwirner
 * Author URI:        https://www.thomaszwirner.de
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       next-meetup-hint
 *
 * @package next-meetup-hint
 */

/**
 * Initialize this plugin in backend: load the events and get the event within the next configured days.
 *
 * @return void
 */
function next_meetup_hint_init(): void {
	// get the actual user id.
	$user_id = get_current_user_id();

	// bail if actual user has disabled the hint in its settings.
	if ( 1 === absint( get_user_meta( $user_id, 'hide_next_meet_hint', true ) ) ) {
		return;
	}

	// bail if user has hidden the hint for 2 days and if the days are not gone.
	$message_hidden = absint( get_user_meta( $user_id, 'hide_next_meetup_hint_for_2_days', true ) );
	if ( $message_hidden > 0 && $message_hidden >= ( time() - ( 2 * 86400 ) ) ) {
		return;
	}

	// embed the required object file for events from WordPress.
	require_once ABSPATH . 'wp-admin/includes/class-wp-community-events.php';

	// get the location the user has configured.
	$saved_location = get_user_option( 'community-events-location', $user_id );

	// get the event object with data of the user.
	$events_client = new WP_Community_Events( $user_id, $saved_location );

	// get the list of events for this user.
	$events = $events_client->get_events();

	// bail if no events have been found.
	if ( empty( $events ) ) {
		return;
	}

	// bail if it is a wp error.
	if ( is_wp_error( $events ) ) {
		return;
	}

	// bail if no event list is found.
	if ( empty( $events['events'] ) ) {
		return;
	}

	// get the days from user settings.
	$days = absint( get_user_meta( $user_id, 'next_meet_hint_days', true ) );
	if ( 0 <= $days ) {
		// if no setting is given, use 14 days.
		$days = 14;
	}

	// marker for next date.
	$next_date = false;

	// check if next date is in 14 days.
	foreach ( $events['events'] as $event ) {
		// bail if no date given.
		if ( empty( $event['date'] ) ) {
			continue;
		}

		// will the event take place in the next days depending on setting?
		if ( strtotime( $event['date'] ) < ( time() + ( $days * 86400 ) ) && ! $next_date ) {
			$next_date = $event;
		}
	}

	// if next date has been found, add it to the configuration which triggers the hint.
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
	// get the user id.
	$user_id = get_current_user_id();

	// bail if actual user has disabled the hint in its settings.
	if ( 1 === absint( get_user_meta( $user_id, 'hide_next_meet_hint', true ) ) ) {
		return;
	}

	// bail if user has hidden the hint for 2 days and if the days are not gone.
	$message_hidden = absint( get_user_meta( $user_id, 'hide_next_meetup_hint_for_2_days', true ) );
	if ( $message_hidden > 0 && $message_hidden >= ( time() - ( 2 * 86400 ) ) ) {
		return;
	}

	// get the saved event from transient.
	$event = get_transient( 'next_meetup_hint' );

	// bail if no event is saved.
	if ( ! $event ) {
		return;
	}

	// output.
	?>
	<div class="next-meetup-hint updated">
		<h3><?php echo esc_html__( 'Next Meetup in your location', 'next-meetup-hint' ); ?></h3>
		<h4><?php echo esc_html( $event['title'] ); ?></h4>
		<p>
			<?php
			echo esc_html( gmdate( 'd.m.Y H:i', strtotime( $event['date'] ) ) );
			echo '<br><a href="' . esc_url( $event['url'] ) . '" target="_blank">' . esc_html__( 'get more info', 'next-meetup-hint' ) . '</a>';
			?>
		</p>
		<button type="button" class="notice-dismiss"><?php echo esc_html__( 'Dismiss', 'next-meetup-hint' ); ?><span class="screen-reader-text"><?php echo esc_html__( 'Hide this message for 2 days', 'next-meetup-hint' ); ?></span></button>
	</div>
	<?php

	// delete the trigger.
	delete_transient( 'next_meetup_hint' );
}
add_action( 'admin_notices', 'next_meetup_hint_notice' );

/**
 * Add fields in user edit profil where he can configure the hint.
 *
 * @param WP_User $user The user object.
 *
 * @return void
 */
function next_meetup_user_profile_fields( WP_User $user ): void {
	// get the settings.
	$hide_next_meet_hint              = absint( get_user_meta( $user->ID, 'hide_next_meet_hint', true ) );
	$next_meet_hint_days              = absint( get_user_meta( $user->ID, 'next_meet_hint_days', true ) );
	$hide_next_meetup_hint_for_2_days = absint( get_user_meta( $user->ID, 'hide_next_meetup_hint_for_2_days', true ) );

	// output.
	?>
		<h3><?php echo esc_html__( 'Next Meetup Hint', 'next-meetup-hint' ); ?></h3>
		<table class="form-table">
			<tr>
				<th><label for="hide_next_meet_hint"><?php echo esc_html__( 'Hide next meetup hint', 'next-meetup-hint' ); ?></label></th>
				<td>
					<input type="checkbox" name="hide_next_meet_hint" id="hide_next_meet_hint" value="1"<?php echo ( 1 === $hide_next_meet_hint ? ' checked="checked"' : '' ); ?> />
					<p><?php echo esc_html__( 'This will disable any hint for next meetups for you.', 'next-meetup-hint' ); ?></p>
				</td>
			</tr>
			<tr>
				<th><label for="next_meet_hint_days"><?php echo esc_html__( 'Show hint days before the event', 'next-meetup-hint' ); ?></label></th>
				<td>
					<input type="number" name="next_meet_hint_days" id="next_meet_hint_days" value="<?php echo absint( $next_meet_hint_days ); ?>" />
					<p><?php echo esc_html__( 'If set to 0 the global setting of 14 days is used.', 'next-meetup-hint' ); ?></p>
				</td>
			</tr>
			<tr>
				<th><label><?php echo esc_html__( 'Is the hint hidden?', 'next-meetup-hint' ); ?></label></th>
				<td>
					<?php
					if ( 1 === $hide_next_meet_hint ) {
						echo esc_html__( 'Yes, generally hidden', 'next-meetup-hint' );
					} elseif ( $hide_next_meetup_hint_for_2_days > 0 && $hide_next_meetup_hint_for_2_days >= ( time() - ( 2 * 86400 ) ) ) {
						/* translators: %1$s will be replaced by a date. */
						echo esc_html( sprintf( __( 'Yes, until %1$s', 'next-meetup-hint' ), date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), $hide_next_meetup_hint_for_2_days ) ) );
					} else {
						echo esc_html__( 'No, it is visible.', 'next-meetup-hint' );
					}
					?>
				</td>
			</tr>
		</table>
	<?php
}
add_action( 'show_user_profile', 'next_meetup_user_profile_fields' );
add_action( 'edit_user_profile', 'next_meetup_user_profile_fields' );

/**
 * Save fields in user edit profil where he can configure the hint.
 *
 * @param int $user_id The user ID.
 *
 * @return void
 */
function next_meetup_save_user_profile_fields( int $user_id ): void {
	// check nonce.
	if ( empty( $_POST['_wpnonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['_wpnonce'] ) ), 'update-user_' . $user_id ) ) {
		return;
	}

	// bail if user is not allowed to edit this data.
	if ( ! current_user_can( 'edit_user', $user_id ) ) {
		return;
	}

	// save the hide setting.
	if ( isset( $_POST['hide_next_meet_hint'] ) ) {
		update_user_meta( $user_id, 'hide_next_meet_hint', absint( wp_unslash( $_POST['hide_next_meet_hint'] ) ) );
	} else {
		delete_user_meta( $user_id, 'hide_next_meet_hint' );
	}

	// save the days setting.
	if ( isset( $_POST['next_meet_hint_days'] ) ) {
		update_user_meta( $user_id, 'next_meet_hint_days', absint( wp_unslash( $_POST['next_meet_hint_days'] ) ) );
	}
}
add_action( 'personal_options_update', 'next_meetup_save_user_profile_fields' );
add_action( 'edit_user_profile_update', 'next_meetup_save_user_profile_fields' );

/**
 * Add our own styles and JS.
 *
 * @return void
 */
function next_meetup_add_styles_and_js(): void {
	// add our own CSS.
	wp_enqueue_style(
		'next-meetup-hint',
		trailingslashit( plugin_dir_url( __FILE__ ) ) . 'styles.css',
		array(),
		filemtime( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'styles.css' ),
	);

	// add our own JS.
	wp_enqueue_script(
		'next-meetup-hint',
		trailingslashit( plugin_dir_url( __FILE__ ) ) . 'scripts.js',
		array( 'jquery' ),
		filemtime( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'scripts.js' ),
		true
	);

	// add php-vars to our js-script.
	wp_localize_script(
		'next-meetup-hint',
		'nextMeetupHintJsVars',
		array(
			'ajax_url'      => admin_url( 'admin-ajax.php' ),
			'dismiss_nonce' => wp_create_nonce( 'next-meetup-hint-hide-hint' ),
		)
	);
}
add_action( 'admin_enqueue_scripts', 'next_meetup_add_styles_and_js' );

/**
 * Hide hint via AJAX for 2 days.
 *
 * @return void
 */
function next_meetup_hint_hide_via_ajax(): void {
	// check nonce.
	check_ajax_referer( 'next-meetup-hint-hide-hint', 'nonce' );

	// save setting.
	update_user_meta( get_current_user_id(), 'hide_next_meetup_hint_for_2_days', time() );

	// return empty result.
	wp_send_json_success();
}
add_action( 'wp_ajax_next_meetup_hint_dismiss_admin_notice', 'next_meetup_hint_hide_via_ajax' );
