!(function ($) {
    "use strict";
    // Preloader
    $(window).on('load', function () {
        if ($('#preloader').length) {
            $('#preloader').delay(100).fadeOut('slow', function () {
                $(this).remove();
            });
        }
    });
    // Init AOS
    function aos_init() {
        AOS.init({
            duration: 1000
            , once: true
        });
    }
    $(window).on('load', function () {
        aos_init();
    });
    $('.btn-toggle').click(function () {
        $(this).find('.btn').toggleClass('active');
        if ($(this).find('.btn-primary').length > 0) {
            $(this).find('.btn').toggleClass('btn-primary');
        }
        if ($(this).find('.btn-danger').length > 0) {
            $(this).find('.btn').toggleClass('btn-danger');
        }
        if ($(this).find('.btn-success').length > 0) {
            $(this).find('.btn').toggleClass('btn-success');
        }
        if ($(this).find('.btn-info').length > 0) {
            $(this).find('.btn').toggleClass('btn-info');
        }
        $(this).find('.btn').toggleClass('btn-default');
    });
    $('form').submit(function () {
        var radioValue = $("input[name='options']:checked").val();
        if (radioValue) {
            alert("You selected - " + radioValue);
        };
        return false;
    });
})(jQuery);