jQuery(document).ready(function($) {
    const sttButton = $('#stt-button');
    const buttonImage = sttOptions.buttonImage || '';

    if (buttonImage) {
        sttButton.css('background-image', `url(${buttonImage})`);
    }

    $(window).scroll(function() {
        if ($(this).scrollTop() > 300) {
            sttButton.fadeIn();
        } else {
            sttButton.fadeOut();
        }
    });

    sttButton.click(function() {
        $('html, body').animate({ scrollTop: 0 }, 600);
        return false;
    });
});