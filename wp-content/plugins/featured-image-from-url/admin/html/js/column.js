jQuery(document).ready(function () {
    fifu_open_quick_lightbox();
});

var currentLightbox = null;

function fifu_open_quick_lightbox() {
    jQuery("div.fifu-quick").on('click', function (evt) {
        evt.stopImmediatePropagation();
        post_id = jQuery(this).attr('post-id');
        image_url = jQuery(this).attr('image-url');
        is_ctgr = jQuery(this).attr('is-ctgr');

        currentLightbox = post_id;

        // display
        DISPLAY_NONE = 'display:none';
        EMPTY = '';
        showVideo = EMPTY;
        showImageGallery = fifuColumnVars.isProduct ? EMPTY : DISPLAY_NONE;
        showSlider = EMPTY;
        showVideoGallery = fifuColumnVars.isProduct ? EMPTY : DISPLAY_NONE;

        url = image_url;
        url = (url == 'about:invalid' ? '' : url);
        media = `<img id="fifu-quick-preview" src="" post-id="${post_id}" style="max-height:600px; width:100%;">`;
        box = `
            <table>
                <tr>
                    <td id="fifu-left-column" style="background-color:#f6f7f7">${media}</td>
                    <td style="vertical-align:top; padding: 10px; background-color:#f6f7f7; width:250px">
                    <div class="fifu-pro" style="float:right;position:relative;top:-30px;left:44px"><a class="fifu-pro-link" href="https://fifu.app/" target="_blank" title="Unlock all PRO features"><span class="dashicons dashicons-lock fifu-pro-icon"></span></a></div>
                        <div>
                            <div style="padding-bottom:5px">
                                <span class="dashicons dashicons-camera" style="font-size:20px;cursor:auto;" title="${fifuColumnVars.tipImage}"></span>
                                <b>${fifuColumnVars.labelImage}</b>
                            </div>
                            <input id="fifu-quick-input-url" type="text" placeholder="${fifuColumnVars.urlImage}" value="" style="width:98%"/>
                            <br><br>

                            <div style="${showImageGallery}">
                                <div style="padding-bottom:5px">
                                    <span class="dashicons dashicons-format-gallery" style="font-size:20px;cursor:auto;"></span>
                                    <b>${fifuColumnVars.labelImageGallery}</b>
                                </div>
                                <div id="gridDemoImage"></div>
                                <table>
                                    <tr>
                                        <th><img src="https://storage.googleapis.com/featuredimagefromurl/icons/image.png" style="opacity: 0.3; width: 55px"></th>
                                        <th><img src="https://storage.googleapis.com/featuredimagefromurl/icons/image.png" style="opacity: 0.3; width: 55px"></th>
                                        <th><img src="https://storage.googleapis.com/featuredimagefromurl/icons/image.png" style="opacity: 0.3; width: 55px"></th>
                                        <th><img src="https://storage.googleapis.com/featuredimagefromurl/icons/add.png" style="opacity: 0.3; width: 55px"></th>
                                    <tr>
                                </table>
                                <br>
                            </div>

                            <div style="${showVideo}">
                                <div style="padding-bottom:5px">
                                    <span class="dashicons dashicons-video-alt3" style="font-size:20px;cursor:auto;" title="${fifuColumnVars.tipVideo}"></span>
                                    <b>${fifuColumnVars.labelVideo}</b>
                                </div>
                                <input id="fifu-quick-video-input-url" type="text" placeholder="${fifuColumnVars.urlVideo}" value="" style="width:98%"/>
                                <br><br>
                            </div>

                            <div style="${showVideoGallery}">
                                <div style="padding-bottom:5px">
                                    <span class="dashicons dashicons-format-video" style="font-size:20px;cursor:auto;"></span>
                                    <b>${fifuColumnVars.labelVideoGallery}</b>
                                </div>
                                <div id="gridDemoVideo"></div>
                                <table>
                                    <tr>
                                        <th><img src="https://storage.googleapis.com/featuredimagefromurl/icons/video.png" style="opacity: 0.3; width: 55px"></th>
                                        <th><img src="https://storage.googleapis.com/featuredimagefromurl/icons/video.png" style="opacity: 0.3; width: 55px"></th>
                                        <th><img src="https://storage.googleapis.com/featuredimagefromurl/icons/video.png" style="opacity: 0.3; width: 55px"></th>
                                        <th><img src="https://storage.googleapis.com/featuredimagefromurl/icons/add.png" style="opacity: 0.3; width: 55px"></th>
                                    <tr>
                                </table>
                                <br>
                            </div>

                            <div style="${showSlider}">
                                <div style="padding-bottom:5px">
                                    <span class="dashicons dashicons-images-alt2" style="font-size:20px;cursor:auto;"></span>
                                    <b>${fifuColumnVars.labelSlider}</b>
                                </div>
                                <div id="gridDemoSlider"></div>
                                <table>
                                    <tr>
                                        <th><img src="https://storage.googleapis.com/featuredimagefromurl/icons/image.png" style="opacity: 0.3; width: 55px"></th>
                                        <th><img src="https://storage.googleapis.com/featuredimagefromurl/icons/image.png" style="opacity: 0.3; width: 55px"></th>
                                        <th><img src="https://storage.googleapis.com/featuredimagefromurl/icons/image.png" style="opacity: 0.3; width: 55px"></th>
                                        <th><img src="https://storage.googleapis.com/featuredimagefromurl/icons/add.png" style="opacity: 0.3; width: 55px"></th>
                                    <tr>
                                </table>
                                <br>
                            </div>

                            <div style="padding-bottom:5px">
                                <span class="dashicons dashicons-search" style="font-size:20px;cursor:auto" title="${fifuColumnVars.tipSearch}"></span>
                                <b>${fifuColumnVars.labelSearch}</b>
                            </div>
                            <div>
                                <input id="fifu-quick-search-input-keywords" type="text" placeholder="${fifuColumnVars.keywords}" value="" style="width:75%"/>
                                <button id="fifu-search-button" class="fifu-quick-button" type="button" style="width:50px;border-radius:5px;height:30px;position:absolute;background-color:#3c434a"><span class="dashicons dashicons-search" style="font-size:16px;cursor:auto"></span></button>
                            </div>
                            <br><br>
                        </div>
                        <div style="width:100%">
                            <button id="fifu-clean-button" class="fifu-quick-button" type="button" style="background-color: #e7e7e7; color: black;">Clean</button>
                            <button id="fifu-save-button" post-id="${post_id}" is-ctgr="${is_ctgr}" class="fifu-quick-button" type="button">Save</button>
                        </div>
                    </td>
                </tr>
            </table>
        `;
        jQuery.fancybox.open(box, {
            touch: false,
            afterShow: function () {
                if (currentLightbox) {
                    fifu_get_image_info(currentLightbox);
                }
            },
        });
        jQuery('#fifu-left-column').css('display', url ? 'table-cell' : 'none');
        jQuery('#fifu-quick-input-url').select();
        fifu_keypress_event();
    });
}

function fifu_keypress_event() {
    jQuery('div.fancybox-container.fancybox-is-open').keyup(function (e) {
        switch (e.which) {
            case 27:
                // esc
                jQuery.fancybox.close();
                break;
            default:
                break;
        }
    });
}

function fifu_get_image_info(post_id) {
    jQuery.ajax({
        method: "POST",
        url: fifuColumnVars.restUrl + 'featured-image-from-url/v2/quick_edit_image_info_api/',
        data: {
            "post_id": post_id,
        },
        async: true,
        beforeSend: function (xhr) {
            xhr.setRequestHeader("X-WP-Nonce", fifuColumnVars.nonce);
        },
        success: function (data) {
            json = JSON.parse(data);
            image_url = json['image_url'];
            if (image_url) {
                jQuery('input#fifu-quick-input-url').val(image_url);
                jQuery('#fifu-quick-input-url').select();
                jQuery('img#fifu-quick-preview').attr('src', image_url);
            }
        },
        complete: function (data) {
        },
    });
}
