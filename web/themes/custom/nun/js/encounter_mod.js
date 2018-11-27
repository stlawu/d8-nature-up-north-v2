(function($){
    $(document).ready(function() {
        // the drupal datetime field requires time but we just care about
        // the date, if the time field is found then set its value to midnight
        // and hide it.
        var input = $('input#edit-field-encounter-date-0-value-time');
        if(input.length) {
            input[0].value = '00:00:00';
            input.css('display','none');
        }
    });
})(jQuery);
