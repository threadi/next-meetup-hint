<?php
/**
 * Template for output single event as notice in backend.
 *
 * @param array $event The event settings.
 *
 * @package next-meetup-hint
 */
?>

<div class="next-meetup-hint updated">
	<h3><?php echo esc_html__( 'Next Meetup in your location', 'next-meetup-hint' ); ?></h3>
	<h4><?php echo esc_html( $event['title'] ); ?></h4>
	<p>
		<?php
		echo esc_html( gmdate( 'd.m.Y H:i', strtotime( $event['date'] ) ) ) . ', ' . esc_html( $event['location']['location'] );
		echo '<br><a href="' . esc_url( $event['url'] ) . '" target="_blank">' . esc_html__( 'get more info', 'next-meetup-hint' ) . '</a>';
		?>
	</p>
	<button type="button" class="notice-dismiss"><?php echo esc_html__( 'Dismiss', 'next-meetup-hint' ); ?><span class="screen-reader-text"><?php echo esc_html__( 'Hide this message for 2 days', 'next-meetup-hint' ); ?></span></button>
</div>
