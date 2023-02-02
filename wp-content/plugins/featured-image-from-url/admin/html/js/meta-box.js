function removeImage() {
    jQuery("#fifu_input_alt").hide();
    jQuery("#fifu_image").hide();
    jQuery("#fifu_upload").hide();
    jQuery("#fifu_link").hide();

    jQuery("#fifu_input_alt").val("");
    jQuery("#fifu_input_url").val("");
    jQuery("#fifu_keywords").val("");

    jQuery("#fifu_button").show();
    jQuery("#fifu_help").show();

    if (fifuMetaBoxVars.is_sirv_active)
        jQuery("#fifu_sirv_button").show();
}

function previewImage() {
    var $url = jQuery("#fifu_input_url").val();

    if (jQuery("#fifu_input_url").val() && jQuery("#fifu_keywords").val())
        $message = fifuMetaBoxVars.wait;
    else
        $message = '';

    if (!$url.startsWith("http") && !$url.startsWith("//")) {
        jQuery("#fifu_keywords").val($url);
        if (fifuMetaBoxVars.is_taxonomy)
            jQuery('#fifu_button').parent().parent().block({message: $message, css: {backgroundColor: 'none', border: 'none', color: 'white'}});
        else
            jQuery('#fifu_button').parent().parent().parent().block({message: $message, css: {backgroundColor: 'none', border: 'none', color: 'white'}});
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function (e) {
            if (xhr.status == 200 && xhr.readyState == 4) {
                if ($url != xhr.responseURL) {
                    $url = xhr.responseURL;
                    jQuery("#fifu_input_url").val($url);
                    runPreview($url);
                }
                setTimeout(function () {
                    if (fifuMetaBoxVars.is_taxonomy)
                        jQuery('#fifu_button').parent().parent().unblock();
                    else
                        jQuery('#fifu_button').parent().parent().parent().unblock();
                }, 500);
            }
        }
        if (!$url || $url == ' ')
            xhr.open("GET", 'https://source.unsplash.com/random', true);
        else {
            xhr.open("GET", 'https://source.unsplash.com/featured/?' + $url, true);
            fifu_start_unsplash_lightbox($url);
        }
        xhr.send();
        if (!$url)
            jQuery("#fifu_keywords").val(' ');
    } else {
        runPreview($url);
    }
}

function runPreview($url) {
    $url = fifu_convert($url);

    jQuery("#fifu_lightbox").attr('href', $url);

    if ($url) {
        fifu_get_sizes();

        jQuery("#fifu_button").hide();
        jQuery("#fifu_help").hide();
        jQuery("#fifu_premium").hide();

        jQuery("#fifu_image").css('background-image', "url('" + $url + "')");

        jQuery("#fifu_input_alt").show();
        jQuery("#fifu_image").show();
        jQuery("#fifu_upload").show();
        jQuery("#fifu_link").show();

        if (fifuMetaBoxVars.is_sirv_active)
            jQuery("#fifu_sirv_button").hide();

        // hide default featured image field
        fifu_hide_regular_featured_image_field();
    }
}

jQuery(document).ready(function () {
    // help
    fifu_register_help();

    // lightbox
    fifu_open_lightbox();

    // start
    fifu_get_sizes();

    // input
    fifu_type_url();

    jQuery('.fifu-hover').on('mouseover', function (evt) {
        jQuery(this).css('color', '#23282e');
    });
    jQuery('.fifu-hover').on('mouseout', function (evt) {
        jQuery(this).css('color', 'white');
    });

    // title
    jQuery("div#imageUrlMetaBox").find('h2').replaceWith('<h4 style="top:7px;position:relative;"><span class="dashicons dashicons-camera" style="font-size:15px"></span>' + jQuery("div#imageUrlMetaBox").find('h2').text() + '</h4>');
    jQuery("div#urlMetaBox").find('h2').replaceWith('<h4 style="top:5px;position:relative;"><span class="dashicons dashicons-camera" style="font-size:15px"></span>' + jQuery("div#urlMetaBox").find('h2').text() + '</h4>');
});

function fifu_get_sizes() {
    image_url = jQuery("#fifu_input_url").val();
    if (image_url && !image_url.startsWith("http") && !image_url.startsWith("//"))
        return;
    fifu_get_image(image_url);
}

function fifu_get_image(url) {
    var image = new Image();
    jQuery(image).attr('onload', 'fifu_store_sizes(this);');
    jQuery(image).attr('src', url);
}

function fifu_store_sizes($) {
    jQuery("#fifu_input_image_width").val($.naturalWidth);
    jQuery("#fifu_input_image_height").val($.naturalHeight);
}

function fifu_open_lightbox() {
    jQuery("#fifu_image").on('click', function (evt) {
        evt.stopImmediatePropagation();
        jQuery.fancybox.open('<img src="' + fifu_convert(jQuery("#fifu_input_url").val()) + '" style="max-height:600px">');
    });
}

function fifu_type_url() {
    jQuery("#fifu_input_url").on('input', function (evt) {
        evt.stopImmediatePropagation();
        fifu_get_sizes();
    });
}

function fifu_register_help() {
    jQuery('#fifu_help').on('click', function () {
        jQuery.fancybox.open(`
            <div style="color:#1e1e1e">
                <h1 style="background-color:whitesmoke;padding:20px;padding-left:0">${fifuMetaBoxVars.txt_title_examples}</h1>
                <p></p>
                <h3>${fifuMetaBoxVars.txt_title_url}</h3>
                <p style="background-color:#1e1e1e;color:white;padding:10px;border-radius:5px">https://ps.w.org/featured-image-from-url/assets/banner-1544x500.png</p>
                <br>
                <h3>${fifuMetaBoxVars.txt_title_keywords}</h3>
                <p style="background-color:#1e1e1e;color:white;padding:10px;border-radius:5px">sea,sun</p>                
                <div style="padding:10px">
                    <li>${fifuMetaBoxVars.txt_desc_empty}</li>
                    <li>${fifuMetaBoxVars.txt_desc_size}</li>
                </div>
                <br>
                <h1 style="background-color:whitesmoke;padding:20px;padding-left:0">${fifuMetaBoxVars.txt_title_more}</h1>
                <p></p>
                <p>${fifuMetaBoxVars.txt_desc_more}</p>
            </div>`
                );
    });
}

function fifu_hide_regular_featured_image_field() {
    if (fifuMetaBoxVars.is_product)
        return;

    if (wp.data && wp.data.dispatch('core/edit-post') && wp.data.select('core/edit-post').isEditorPanelOpened('featured-image')) {
        wp.data.dispatch('core/edit-post').toggleEditorPanelOpened('featured-image');
    }
}

jQuery(document).ready(function () {
    setTimeout(function () {
        if (jQuery("#fifu_input_url").val() || jQuery("#fifu_image").css('background-image').includes('http')) {
            fifu_hide_regular_featured_image_field();
        }
    }, 100);
});
