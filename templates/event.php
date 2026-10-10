<?php
/**
 * Template for output single event as notice in backend.
 *
 * @param array<string,mixed> $event The event settings.
 * @version: 3.2.0
 * @package next-meetup-hint
 */

// prevent direct access.
defined( 'ABSPATH' ) || exit;

// get the event, also if this template is used without it.
$next_meetup_hint_event = isset( $event ) && is_array( $event ) ? $event : array();

// get the values of the event.
$next_meetup_hint_title    = isset( $next_meetup_hint_event['title'] ) && is_string( $next_meetup_hint_event['title'] ) ? $next_meetup_hint_event['title'] : '';
$next_meetup_hint_url      = isset( $next_meetup_hint_event['url'] ) && is_string( $next_meetup_hint_event['url'] ) ? $next_meetup_hint_event['url'] : '';
$next_meetup_hint_location = isset( $next_meetup_hint_event['location'] ) && is_array( $next_meetup_hint_event['location'] ) && isset( $next_meetup_hint_event['location']['location'] ) && is_string( $next_meetup_hint_event['location']['location'] ) ? $next_meetup_hint_event['location']['location'] : '';
$next_meetup_hint_date     = isset( $next_meetup_hint_event['date'] ) && is_string( $next_meetup_hint_event['date'] ) ? strtotime( $next_meetup_hint_event['date'] ) : false;
$next_meetup_hint_days     = absint( get_option( 'nmh_hide_days', 2 ) );

?>
<div class="next-meetup-hint updated">
	<h2><?php echo esc_html__( 'Next Meetup near you:', 'next-meetup-hint' ); ?> <i><?php echo esc_html( $next_meetup_hint_title ); ?></i></h2>
	<p>
		<?php
		// the date is the local time at the place of the event, so it is shown without time zone conversion.
		if ( false !== $next_meetup_hint_date ) {
			echo '<strong>' . esc_html__( 'When:', 'next-meetup-hint' ) . '</strong> ' . esc_html( date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), $next_meetup_hint_date ) ) . '<br>';
		}
		if ( '' !== $next_meetup_hint_location ) {
			echo '<strong>' . esc_html__( 'Where:', 'next-meetup-hint' ) . '</strong> ' . esc_html( $next_meetup_hint_location );
		}
		?>
	</p>
	<div class="next-meetup-hint-options">
		<?php
		if ( '' !== $next_meetup_hint_url ) {
			echo '<a href="' . esc_url( $next_meetup_hint_url ) . '" target="_blank" rel="noopener noreferrer" class="button button-primary">' . esc_html__( 'Show event details', 'next-meetup-hint' ) . '</a>';
		}

		/**
		 * Run actions to show additional information for this event.
		 *
		 * @since 3.0.0 Available since 3.0.0.
		 * @param array<string,mixed> $event The event settings.
		 */
		do_action( 'next_meetup_hint_event_options', $next_meetup_hint_event );
		?>
	</div>
	<button type="button" class="notice-dismiss"><?php echo esc_html__( 'Dismiss', 'next-meetup-hint' ); ?><span class="screen-reader-text">
	<?php
		/* translators: %1$d will be replaced by a number of days. */
		echo esc_html( sprintf( _n( 'Hide this message for %1$d day', 'Hide this message for %1$d days', $next_meetup_hint_days, 'next-meetup-hint' ), $next_meetup_hint_days ) );
	?>
	</span></button>
</div>
