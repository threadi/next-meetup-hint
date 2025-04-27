jQuery(document).ready(function($) {
  // add option near to list-headline.
  $('body.settings_page_next-meetup-hint h1').each(function() {
    let button = document.createElement('a');
    button.className = 'review-hint-button page-title-action';
    button.href = nextMeetupHintJsVars.review_url;
    button.innerHTML = nextMeetupHintJsVars.title_rate_us;
    button.target = '_blank';
    this.after(button);
  })

  // save to hide transient-messages via ajax-request.
  $('.next-meetup-hint button.notice-dismiss').on('click',
    function (event) {
      event.preventDefault();
      let $this = $(this);

      // Remove the dismissible length from the attribute value and rejoin the array.
      data = {
        'action': 'next_meetup_hint_dismiss_admin_notice',
        'nonce': nextMeetupHintJsVars.dismiss_nonce
      };

      // run ajax request to save this setting
      $.post(nextMeetupHintJsVars.ajax_url, data);
      $this.closest('.next-meetup-hint').hide('slow');
    }
  );
});
