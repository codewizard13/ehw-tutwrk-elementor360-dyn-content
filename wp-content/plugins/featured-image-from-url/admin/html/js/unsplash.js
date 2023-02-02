function fifu_get_unsplash_urls(keywords, limit, size) {
    const urls = [];
    var count = 1;
    var LIMIT = limit;
    var sleepyAlert = setInterval(function () {
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function (e) {
            if (xhr.status == 200 && xhr.readyState == 4) {
                url = xhr.responseURL;
                imageId = url.split('-')[2].split('?')[0];
                if (!idSet.has(imageId)) {
                    idSet.add(imageId);
                    urls.push(url);
                } else
                    LIMIT--;
            }
        };
        xhr.open("GET", "https://source.unsplash.com/" + size + "/?" + keywords + "&" + Math.random() * 10000, true);
        xhr.send();
        if (count++ >= LIMIT) {
            clearInterval(sleepyAlert);
            (async() => {
                // waiting for urls
                while (urls.length < LIMIT)
                    await new Promise(resolve => setTimeout(resolve, 250));
                // ready
                for (i = 0; i < urls.length; i++) {
                    jQuery('div.masonry').append('<div class="mItem" style="max-width:400px;object-fit:content"><img src="' + urls[i] + '" style="width:100%"></div>');
                }
                jQuery('#fifu-loading').remove();
                jQuery('div.masonry').after('<div class="fifu-pro" style="float:right;position:relative;top:-5px;left:-145px"><a class="fifu-pro-link" href="https://fifu.app/" target="_blank" title="Unlock all PRO features"><span class="dashicons dashicons-lock fifu-pro-icon"></span></a></div><center><div id="fifu-loading"><img src="https://cdnjs.cloudflare.com/ajax/libs/jquery.lazyloadxt/1.1.0/loading.gif"><div>Loading more...</div><div></center>');
                fifu_scrolling = false;
            })();
        }
    }, 50);
}

var fifu_scrolling = false;
var idSet = new Set();

function fifu_start_unsplash_lightbox(keywords) {
    idSet = new Set();
    fifu_register_unsplash_click_event();

    size = 'featured';

    jQuery.fancybox.open('<div><div class="masonry"></div></div>');
    jQuery('div.masonry').after('<center><div id="fifu-loading"><img src="https://cdnjs.cloudflare.com/ajax/libs/jquery.lazyloadxt/1.1.0/loading.gif"><div>Loading...</div><div></center>');
    fifu_get_unsplash_urls(keywords, 10, size);
}

function fifu_register_unsplash_click_event() {
    jQuery('body').on('click', 'div.mItem > img', function (evt) {
        evt.stopImmediatePropagation();
        // meta-box
        if (jQuery("#fifu_input_url").length) {
            jQuery("#fifu_input_url").val(jQuery(this).attr('src'));
            previewImage();
        }
        jQuery.fancybox.close();
    });
}
