<?php

function fifu_woo_zoom() {
    return fifu_is_on('fifu_wc_zoom') ? 'inline' : 'none';
}

function fifu_woo_lbox() {
    return fifu_is_on('fifu_wc_lbox');
}

function fifu_woo_theme() {
    return file_exists(get_template_directory() . '/woocommerce');
}

define('FIFU_FIX_IMAGES_WITHOUT_DIMENSIONS',
        "function fix_images_without_dimensions() {
        jQuery('img[data-large_image_height=0]').each(function () {
            if (jQuery(this)[0].naturalWidth <= 2)
                return;

            jQuery(this)
                .attr('data-large_image_width', jQuery(this)[0].naturalWidth)
                .attr('data-large_image_height', jQuery(this)[0].naturalHeight);

            jQuery('div.flex-viewport').css('height', jQuery(this)[0].clientHeight);
        });
    }
    fix_images_without_dimensions();"
);

function fifu_woocommerce_gallery_image_html_attachment_image_params($params, $attachment_id, $image_size, $main_image) {
    // fix zoom
    if ($params['data-large_image_width'] == 0) {
        $params['data-large_image_width'] = 1920;
        $params['data-large_image_height'] = 0;
    }

    // fix lightbox
    if (is_product())
        $params['onload'] = FIFU_FIX_IMAGES_WITHOUT_DIMENSIONS;

    return $params;
}

add_filter('woocommerce_gallery_image_html_attachment_image_params', 'fifu_woocommerce_gallery_image_html_attachment_image_params', 10, 4);

add_action('woocommerce_product_duplicate', 'fifu_woocommerce_product_duplicate', 10, 1);

function fifu_woocommerce_product_duplicate($array) {
    if (!$array || !$array->get_meta_data())
        return;

    $post_id = $array->get_id();
    foreach ($array->get_meta_data() as $meta_data) {
        $data = $meta_data->get_data();
        if (in_array($data['key'], array('fifu_image_url'))) {
            delete_post_meta($post_id, '_thumbnail_id');
        }
    }
}

function fifu_woocommerce_order_item_thumbnail_filter($image, $item) {
    if (strpos($image, 'data-sizes="auto"') !== false)
        return str_replace('data-src', 'src', $image);

    return $image;
}

add_filter('woocommerce_order_item_thumbnail', 'fifu_woocommerce_order_item_thumbnail_filter', 10, 2);

