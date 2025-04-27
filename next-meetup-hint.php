<?php
/**
 * Plugin Name:       Next Meetup Hint
 * Description:       Show hint for next meetup.
 * Requires at least: 4.9.24
 * Requires PHP:      8.0
 * Version:           @@VersionNumber@@
 * Author:            Thomas Zwirner
 * Author URI:        https://www.thomaszwirner.de
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       next-meetup-hint
 *
 * @package next-meetup-hint
 */

declare(strict_types = 1);

// prevent direct access.
defined( 'ABSPATH' ) || exit;

// do nothing if PHP-version is not 8.0 or newer.
if ( PHP_VERSION_ID < 80000 ) { // @phpstan-ignore smaller.alwaysFalse
	return;
}

/**
 * Initialize this plugin in backend: load the events and get the event within the next configured days.
 *
 * @return void
 */
function next_meetup_hint_init(): void {
	// get the actual user id.
	$user_id = get_current_user_id();

	// bail if actual user has disabled the hint in its settings.
	if ( 1 === (int) get_user_meta( $user_id, 'hide_next_meet_hint', true ) ) {
		return;
	}

	// bail if user has hidden the hint for 2 days and if the days are not gone.
	$message_hidden = absint( get_user_meta( $user_id, 'hide_next_meetup_hint_for_2_days', true ) );
	if ( $message_hidden > 0 && $message_hidden >= ( time() - ( 2 * 86400 ) ) ) {
		return;
	}

	// embed the required object file for events from WordPress.
	require_once ABSPATH . 'wp-admin/includes/class-wp-community-events.php'; // @phpstan-ignore requireOnce.fileNotFound

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

	// output the template.
	require_once next_meetup_hint_get_template( 'event.php' );

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

	// get dashboard URL.
	$dashboard_url = add_query_arg(
		array(),
		get_admin_url() . 'index.php'
	);

	// output.
	?>
		<h3 id="nextmeetuphint"><?php echo esc_html__( 'Next Meetup Hint', 'next-meetup-hint' ); ?></h3>
		<p>
		<?php
			/* translators: %1$s will be replaced by a URL. */
			echo wp_kses_post( sprintf( __( 'Change your location on the widget "WordPress Events and News" on the <a href="%1$s">Dashboard</a>.', 'next-meetup-hint' ), esc_url( $dashboard_url ) ) );
		?>
		</p>
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
						// create url.
						$url = add_query_arg(
							array(
								'action' => 'next_meet_hint_remove_lock',
								'nonce'  => wp_create_nonce( 'next-meet-hint-remove-lock' ),
							),
							get_admin_url() . 'admin.php'
						);

						/* translators: %1$s will be replaced by a date. */
						echo esc_html( sprintf( __( 'Yes, until %1$s', 'next-meetup-hint' ), date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), $hide_next_meetup_hint_for_2_days ) ) );

						// show link to remove the lock.
						echo ' <a href="' . esc_url( $url ) . '">' . esc_html__( 'show them again', 'next-meetup-hint' ) . '</a>';
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

/**
 * Remove the user specific lock to show the event hint.
 *
 * @return void
 * @noinspection PhpNoReturnAttributeCanBeAddedInspection
 */
function next_meetup_user_remove_lock(): void {
	// check nonce.
	check_admin_referer( 'next-meet-hint-remove-lock', 'nonce' );

	// remove the lock.
	delete_user_meta( get_current_user_id(), 'hide_next_meetup_hint_for_2_days' );

	// get the referer.
	$referer = wp_get_referer();

	// if no referer could be loaded, just get an empty string.
	if ( ! $referer ) {
		$referer = '';
	}

	// forward user.
	wp_safe_redirect( $referer );
	exit;
}
add_action( 'admin_action_next_meet_hint_remove_lock', 'next_meetup_user_remove_lock' );

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
		(string) filemtime( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'styles.css' ),
	);

	// add our own JS.
	wp_enqueue_script(
		'next-meetup-hint',
		trailingslashit( plugin_dir_url( __FILE__ ) ) . 'scripts.js',
		array( 'jquery' ),
		(string) filemtime( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'scripts.js' ),
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

/**
 * Return path to the requested template.
 *
 * @param string $template The file name of the template.
 *
 * @return string
 */
function next_meetup_hint_get_template( string $template ): string {
	// check if requested template exist in theme.
	$theme_template = locate_template( trailingslashit( basename( __DIR__ ) ) . $template );
	if ( $theme_template ) {
		return $theme_template;
	}

	// set the directory for template to use.
	$directory = basename( __DIR__ );

	/**
	 * Set template directory.
	 *
	 * Defaults to our own plugin-directory.
	 *
	 * @since 2.0.0 Available since 2.0.0.
	 *
	 * @param string $directory The directory to use.
	 */
	$plugin_template = plugin_dir_path( apply_filters( 'next_meetup_hint_set_template_directory', $directory ) ) . 'templates/' . $template;
	if ( file_exists( $plugin_template ) ) {
		return $plugin_template;
	}

	// return template of the plugin.
	return plugin_dir_path( __FILE__ ) . 'templates/' . $template;
}

/**
 * Add link to go to the user-specific settings in plugin list.
 *
 * @param array<int,string> $links List of links for this plugin in plugin list.
 *
 * @return array<int,string>
 */
function next_meetup_hint_add_plugin_link( array $links ): array {
	// add the link.
	$links[] = '<a href="' . esc_url( get_edit_profile_url() ) . '#nextmeetuphint">' . __( 'Configure your settings', 'next-meetup-hint' ) . '</a>';

	// return resulting link list.
	return $links;
}
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'next_meetup_hint_add_plugin_link' );
