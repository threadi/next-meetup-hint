<?php
/**
 * Template for output single event as ICS (RFC 5545).
 *
 * Every line is written with next_meetup_hint_ics_line(), every text with next_meetup_hint_ics_text().
 *
 * @param array<string,mixed> $event The event settings.
 *
 * @package next-meetup-hint
 * @version: 3.2.0
 */

// prevent direct access.
defined( 'ABSPATH' ) || exit;

// get the event, also if this template is used without it.
$next_meetup_hint_event = isset( $event ) && is_array( $event ) ? $event : array();

// get the values of the event.
$next_meetup_hint_title    = isset( $next_meetup_hint_event['title'] ) && is_string( $next_meetup_hint_event['title'] ) ? $next_meetup_hint_event['title'] : '';
$next_meetup_hint_url      = isset( $next_meetup_hint_event['url'] ) && is_string( $next_meetup_hint_event['url'] ) ? $next_meetup_hint_event['url'] : '';
$next_meetup_hint_location = isset( $next_meetup_hint_event['location'] ) && is_array( $next_meetup_hint_event['location'] ) && isset( $next_meetup_hint_event['location']['location'] ) && is_string( $next_meetup_hint_event['location']['location'] ) ? $next_meetup_hint_event['location']['location'] : '';

// get start and end.
if ( ! empty( $next_meetup_hint_event['start_unix_timestamp'] ) && is_numeric( $next_meetup_hint_event['start_unix_timestamp'] ) ) {
	// the exact time is known: use UTC.
	$next_meetup_hint_start = absint( $next_meetup_hint_event['start_unix_timestamp'] );
	$next_meetup_hint_end   = ! empty( $next_meetup_hint_event['end_unix_timestamp'] ) && is_numeric( $next_meetup_hint_event['end_unix_timestamp'] ) ? absint( $next_meetup_hint_event['end_unix_timestamp'] ) : 0;
	$next_meetup_hint_form  = 'Ymd\THis\Z';
} else {
	// only the local time at the place of the event is known: use it as "floating" time without time zone.
	$next_meetup_hint_start = isset( $next_meetup_hint_event['date'] ) && is_string( $next_meetup_hint_event['date'] ) ? (int) strtotime( $next_meetup_hint_event['date'] ) : 0;
	$next_meetup_hint_end   = isset( $next_meetup_hint_event['end_date'] ) && is_string( $next_meetup_hint_event['end_date'] ) ? (int) strtotime( $next_meetup_hint_event['end_date'] ) : 0;
	$next_meetup_hint_form  = 'Ymd\THis';
}
if ( $next_meetup_hint_end <= $next_meetup_hint_start ) {
	// assume 2 hours, if the end is unknown.
	$next_meetup_hint_end = $next_meetup_hint_start + ( 2 * HOUR_IN_SECONDS );
}

// output the calendar.
echo next_meetup_hint_ics_line( 'BEGIN:VCALENDAR' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
echo next_meetup_hint_ics_line( 'VERSION:2.0' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
echo next_meetup_hint_ics_line( 'PRODID:-//WordPress Plugin Next Meetup Hint//EN' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
echo next_meetup_hint_ics_line( 'CALSCALE:GREGORIAN' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
echo next_meetup_hint_ics_line( 'METHOD:PUBLISH' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
echo next_meetup_hint_ics_line( 'BEGIN:VEVENT' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
echo next_meetup_hint_ics_line( 'UID:' . md5( $next_meetup_hint_url . '|' . $next_meetup_hint_start ) . '@next-meetup-hint' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
echo next_meetup_hint_ics_line( 'DTSTAMP:' . gmdate( 'Ymd\THis\Z' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
echo next_meetup_hint_ics_line( 'DTSTART:' . gmdate( $next_meetup_hint_form, $next_meetup_hint_start ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
echo next_meetup_hint_ics_line( 'DTEND:' . gmdate( $next_meetup_hint_form, $next_meetup_hint_end ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
echo next_meetup_hint_ics_line( 'SUMMARY:' . next_meetup_hint_ics_text( $next_meetup_hint_title ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
if ( '' !== $next_meetup_hint_location ) {
	echo next_meetup_hint_ics_line( 'LOCATION:' . next_meetup_hint_ics_text( $next_meetup_hint_location ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}
if ( '' !== $next_meetup_hint_url ) {
	echo next_meetup_hint_ics_line( 'URL;VALUE=URI:' . esc_url_raw( $next_meetup_hint_url ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}
echo next_meetup_hint_ics_line( 'END:VEVENT' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
echo next_meetup_hint_ics_line( 'END:VCALENDAR' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
