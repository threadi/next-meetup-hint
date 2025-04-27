<?php
/**
 * Template for output single event as notice in backend.
 *
 * @param array $event The event settings.
 * @version: 3.0.0
 * @package next-meetup-hint
 */

?>
<div class="next-meetup-hint updated">
	<h2><?php echo esc_html__( 'Next Meetup near you:', 'next-meetup-hint' ); ?> <i><?php echo esc_html( $event['title'] ); ?></i></h2>
	<p>
		<?php
		echo '<strong>' . esc_html__( 'When:', 'next-meetup-hint' ) . '</strong> ' . esc_html( gmdate( 'd.m.Y H:i', strtotime( $event['date'] ) ) ) . '<br>';
		echo '<strong>' . esc_html__( 'Where:', 'next-meetup-hint' ) . '</strong> ' . esc_html( $event['location']['location'] );
		?>
	</p>
	<p>
		<?php
		echo '<a href="' . esc_url( $event['url'] ) . '" target="_blank" class="button button-primary">' . esc_html__( 'Show event details', 'next-meetup-hint' ) . '</a>';

		/**
		 * Run actions to show additional information for this event.
		 *
		 * @since 3.0.0 Available since 3.0.0.
		 * @param array<string> $event The event settings.
		 */
		do_action( 'next_meetup_hint_event_options', $event );
		?>
	<button type="button" class="notice-dismiss"><?php echo esc_html__( 'Dismiss', 'next-meetup-hint' ); ?><span class="screen-reader-text"><?php echo esc_html__( 'Hide this message for 2 days', 'next-meetup-hint' ); ?></span></button>
</div>
