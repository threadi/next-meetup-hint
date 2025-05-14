<?php
/**
 * Template for output single event as ICS.
 *
 * @param array $event The event settings.
 * @param string $timezone The WordPress timezone.
 *
 * @package next-meetup-hint
 * @version : 3.1.0
 */

?>BEGIN:VCALENDAR
VERSION:2.0
PRODID:-//WordPress Plugin Next Meetup Hint//
CALSCALE:GREGORIAN
BEGIN:VEVENT
SUMMARY:<?php echo esc_html( $event['title'] ) . "\n"; ?>
DTSTAMP;TZID=<?php echo esc_html( $timezone ); ?>:<?php echo esc_html( gmdate( 'Ymd\THis', time() ) ) . "\n"; ?>
DTSTART;TZID=<?php echo esc_html( $timezone ); ?>:<?php echo esc_html( gmdate( 'Ymd\THis', $event['start_unix_timestamp'] ) ) . "\n"; ?>
DTEND;TZID=<?php echo esc_html( $timezone ); ?>:<?php echo esc_html( gmdate( 'Ymd\THis', $event['end_unix_timestamp'] ) ) . "\n"; ?>
LOCATION:<?php echo esc_html( $event['location']['location'] ) . "\n"; ?>
URL;VALUE=URI:<?php echo esc_url( $event['url'] ) . "\n"; ?>
END:VEVENT
END:VCALENDAR
