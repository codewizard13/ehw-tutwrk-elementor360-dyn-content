jQuery(document).ready(function () {
    jQuery('link[href*="jquery-ui.css"]').attr("disabled", "true");
    jQuery('div.wrap div.header-box div.notice').hide();
    jQuery('div.wrap div.header-box div#message').hide();
    jQuery('div.wrap div.header-box div.updated').remove();
});

jQuery(function () {
    jQuery("#tabs-top").tabs();

    window.scrollTo(0, 0);
    jQuery('.wrap').css('opacity', 1);
});
