(function () {
    window.lazySizesConfig = window.lazySizesConfig || {};
    window.lazySizesConfig.loadMode = 1;
    window.lazySizesConfig.expand = 1;
    window.lazySizesConfig.expFactor = 0.1;
    window.lazySizesConfig.hFac = 0.1;
    window.lazySizesConfig.throttleDelay = 0;
    window.lazySizesConfig.lazyClass = 'lazyload';
    window.lazySizesConfig.loadingClass = 'lazyloading';
    window.lazySizesConfig.loadedClass = 'lazyloaded';
})();

// 1920x1: https://png-pixel.com/
const FIFU_PLACEHOLDER = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAJxAAAAABCAQAAADS13yAAAAAK0lEQVR42u3BIQEAAAACIP1/2hsGoAEAAAAAAAAAAAAAAAAAAAAAAADgwgCcQgACWbaCCgAAAABJRU5ErkJggg==';

function fifu_lazy(selector = 'img') {

    jQuery(selector).each(function (index) {
        // solves "Preload Largest Contentful Paint image" for top images
        if (jQuery(this).offset().top < jQuery(window).height()) {
            datasrcset = jQuery(this).attr('data-srcset');
            datasrc = jQuery(this).attr('data-src');
            width = jQuery(this).width();
            if (datasrc && datasrcset && width)
                jQuery('head').append(`<link rel="preload" as="image" href="${datasrc}" imagesizes="${width}px" imagesrcset="${datasrcset}">`);
        }

        // fix some lazy load themes: sober
        src = jQuery(this).attr('src');
        if (src && src.includes('cdn.fifu.app'))
            jQuery(this).removeClass('lazyloading');

        if (jQuery(this).hasClass('lazyload') || jQuery(this).hasClass('lazyloaded') || jQuery(this).hasClass('lazyloading'))
            return;

        // remove wp lazy load
        jQuery(this).removeAttr('loading');

        if (!jQuery(this).hasClass('fifu'))
            fifu_add_placeholder(this);

        // dont touch on slider (lazyload class added on back-end)
        if (!jQuery(this).hasClass('fifu'))
            fifu_add_lazyload(this);
    });
    fifu_add_srcset(selector);
}

function fifu_add_lazyload($) {
    jQuery($).addClass('lazyload');
}

function fifu_add_placeholder($) {
    src = jQuery($).attr('src');
    datasrc = jQuery($).attr('data-src');

    if (src && src.includes('cdn.fifu.app')) {
        jQuery($).attr('data-src', src);
        jQuery($).removeAttr('src');
        datasrc = src;
        src = null;
    }

    if (!src && datasrc) {
        if (datasrc.includes('cdn.fifu.app')) {
            parameters = datasrc.split(/.resize=/)[1];

            if (!parameters)
                parameters = datasrc.split('?theme-size=')[1];

            if (!parameters) {
                id = datasrc.split('/')[4];
                width = parseInt(id.split('-')[1]);
                height = parseInt(id.split('-')[2]);
            } else {
                width = parseInt(parameters.split(',')[0]);
                height = parseInt(parameters.split(',')[1]);
            }

            if (width && height) {
                jQuery($).attr('src', `https://images.placeholders.dev/?width=${width}&height=${height}&text=${'...'}`);
            }
        } else {
            if (fifuLazyVars.fifu_is_product && !datasrc.includes('.fifu.app') && jQuery($).hasClass('lazyload')) {
                if (jQuery($).parents('.woocommerce-product-gallery__wrapper').length == 0)
                    jQuery($).attr('src', FIFU_PLACEHOLDER);
            }
        }
    }
}

function fifu_add_srcset(selector) {
    // speedup
    new_selector = selector != 'img' ? selector : 'img[data-src^="https://cdn.fifu.app/"]';
    jQuery(new_selector).each(function (index) {
        src = jQuery(this).attr('data-src');
        srcset = jQuery(this).attr('data-srcset');

        if (!srcset && src && src.includes('cdn.fifu.app')) {
            srcset = fifuCloudVars.srcsets[src];
            srcset = srcset ? srcset : fifuCloudVars.srcsets[src.split('?')[0]];
            if (srcset) {
                jQuery(this).attr('data-srcset', srcset);
                jQuery(this).attr('data-sizes', 'auto');
            }
        }
    });

    types = ['src', 'data-src'];
    for (i = 0; i < types.length; i++) {
        // jetpack
        jQuery('img[' + types[i] + '*=".wp.com/"]').each(function (index) {
            if (jQuery(this).attr('srcset') && jQuery(this).attr('data-srcset'))
                return;

            isMain = jQuery(this).parents('.woocommerce-product-gallery__image').length == 1;
            src = jQuery(this).attr(types[i])
            srcset = jQuery(this).attr(types[i] + 'set');

            if (!srcset && !isMain) {
                srcset = '';
                sizes = [75, 100, 150, 240, 320, 500, 640, 800, 1024, 1280, 1600];
                for (j = 0; j < sizes.length; j++) {
                    ssl = src.includes('ssl=1') ? '&ssl=1' : '';

                    match = src.match(/\?resize=[0-9,]+/);
                    if (!match)
                        continue;

                    resize = match[0].split('=')[1].split(',');
                    resizeW = Number(resize[0]);
                    resizeH = Number(resize[1]);
                    newResizeW = sizes[j];
                    if (resizeH) {
                        newResizeH = Math.trunc(sizes[j] * resizeH / resizeW);
                        newResize = `${newResizeW},${newResizeH}`;
                    } else
                        newResize = newResizeW;

                    srcset += ((j != 0) ? ', ' : '') + src.replace(src.split('?')[1], 'w=' + sizes[j] + '&resize=' + newResize + ssl) + ' ' + sizes[j] + 'w';
                }
                jQuery(this).attr(types[i] + 'set', srcset);
                jQuery(this).attr('data-sizes', 'auto');
            }
        });
    }
}

document.addEventListener('lazybeforeunveil', function (e) {
    // background-image    
    var url = jQuery(e.target).attr('data-bg');
    if (url) {
        delimiter = fifu_get_delimiter(jQuery(e.target), 'data-bg');
        jQuery(e.target).css('background-image', 'url(' + fifu_get_delimited_url(url, delimiter) + ')');
    }

    // width & height
    // jQuery(e.target).attr('fifu-width', e.srcElement.clientWidth);
    // jQuery(e.target).attr('fifu-height', e.srcElement.clientHeight);
});

document.addEventListener('lazyunveilread', function (e) {
});

function fifu_get_delimiter($, attr) {
    return $[0].outerHTML.split(attr + '=')[1][0];
}

function fifu_get_delimited_url(url, delimiter) {
    return delimiter + url + delimiter;
}

jQuery(document).on('lazybeforesizes', function (e) {
    // dont touch on fifu gallery
    if (jQuery(e.target).parent().hasClass('lslide'))
        return;

    // fix width and height fields, for SEO reasons
    dataSrc = jQuery(e.target).attr('data-src');
    if (dataSrc && dataSrc.includes('cdn.fifu.app')) {
        lsWidth = jQuery(e.target)[0].width;
        urlWidth = dataSrc.split('-')[1];
        urlHeight = dataSrc.split('-')[2].split('?')[0];
        newHeight = parseInt(lsWidth * urlHeight / urlWidth);
        jQuery(e.target).attr('width', lsWidth);
        jQuery(e.target).attr('height', newHeight);
    }
});

function fifu_lazy_ajax(selector = 'img') {
    jQuery(selector).each(function () {
        if (jQuery(this).hasClass('lazyload') || jQuery(this).hasClass('lazyloaded') || jQuery(this).hasClass('lazyloading'))
            return;

        // "Flickr Album Gallery" plugin
        if (jQuery(this).hasClass('flickr-img-responsive') || jQuery(this).hasClass('gall-img-responsive'))
            return;

        // "Image and video gallery from Google Drive" plugin
        if (jQuery(this).hasClass('sgdg-grid-img'))
            return;

        // "Slider Revolution" plugin
        if (jQuery(this).hasClass('tp-rs-img'))
            return;

        src = jQuery(this).attr('src');
        if (src && src.includes('.fifu.app'))
            return;

        if (jQuery(this).hasClass('fifu'))
            return;

        jQuery(this).attr('data-src', jQuery(this).attr('src'));
        jQuery(this).removeAttr('src');
    });
    fifu_lazy(selector);
}
