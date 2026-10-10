jQuery( function( $ ) {
  /**
   * Add the review button next to the headline of our settings page.
   *
   * The settings page in WordPress 7.0 or newer renders its headline with React after the page is loaded,
   * so wait for it if it is not there yet.
   */
  if ( document.body.classList.contains( 'settings_page_next-meetup-hint' ) ) {
    const addReviewButton = function() {
      const headline = document.querySelector( '.wrap h1' );
      if ( ! headline || document.querySelector( '.review-hint-button' ) ) {
        return !! headline;
      }
      const button = document.createElement( 'a' );
      button.className = 'review-hint-button page-title-action';
      button.href = nextMeetupHintJsVars.review_url;
      button.textContent = nextMeetupHintJsVars.title_rate_us;
      button.target = '_blank';
      button.rel = 'noopener noreferrer';
      headline.after( button );
      return true;
    };
    if ( ! addReviewButton() ) {
      const observer = new MutationObserver( function() {
        if ( addReviewButton() ) {
          observer.disconnect();
        }
      } );
      observer.observe( document.body, { childList: true, subtree: true } );
    }
  }

  // hide the hint and save this via ajax-request.
  $( '.next-meetup-hint button.notice-dismiss' ).on( 'click', function( event ) {
    event.preventDefault();
    const $notice = $( this ).closest( '.next-meetup-hint' );

    // run ajax request to save this setting.
    const data = {
      action: 'next_meetup_hint_dismiss_admin_notice',
      nonce: nextMeetupHintJsVars.dismiss_nonce
    };
    $.post( nextMeetupHintJsVars.ajax_url, data ).fail( function() {
      // show the hint again, as it would appear again on the next page anyway.
      $notice.show( 'slow' );
    } );
    $notice.hide( 'slow' );
  } );
} );
