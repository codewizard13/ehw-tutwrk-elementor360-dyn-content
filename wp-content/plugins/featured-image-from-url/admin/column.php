<?php

define('FIFU_COLUMN_HEIGHT', 40);

add_action('admin_init', 'fifu_column');
add_filter('admin_head', 'fifu_admin_add_css_js');

function fifu_column() {
    add_filter('manage_posts_columns', 'fifu_column_head');
    add_filter('manage_pages_columns', 'fifu_column_head');
    add_filter('manage_edit-product_cat_columns', 'fifu_column_head');
    fifu_column_custom_post_type();
    add_action('manage_posts_custom_column', 'fifu_column_content', 10, 2);
    add_action('manage_pages_custom_column', 'fifu_column_content', 10, 2);
    add_action('manage_product_cat_custom_column', 'fifu_ctgr_column_content', 10, 3);
}

function fifu_admin_add_css_js() {
    // buddyboss app
    if (isset($_REQUEST['page']) && strpos($_REQUEST['page'], 'bbapp') !== false)
        return;

    wp_enqueue_style('fifu-pro-css', plugins_url('/html/css/pro.css', __FILE__), array(), fifu_version_number());
    wp_enqueue_style('fancy-box-css', 'https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.css');
    wp_enqueue_script('fancy-box-js', 'https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js');
    wp_enqueue_style('fifu-column-css', plugins_url('/html/css/column.css', __FILE__), array(), fifu_version_number());
    wp_enqueue_script('fifu-column-js', plugins_url('/html/js/column.js', __FILE__), array('jquery'), fifu_version_number());

    $fifu = fifu_get_strings_quick_edit();

    wp_localize_script('fifu-column-js', 'fifuColumnVars', [
        'restUrl' => esc_url_raw(rest_url()),
        'homeUrl' => esc_url_raw(home_url()),
        'nonce' => wp_create_nonce('wp_rest'),
        'labelImage' => $fifu['title']['image'](),
        'labelVideo' => $fifu['title']['video'](),
        'labelSearch' => $fifu['title']['search'](),
        'labelImageGallery' => $fifu['title']['gallery']['image'](),
        'labelVideoGallery' => $fifu['title']['gallery']['video'](),
        'labelSlider' => $fifu['title']['slider'](),
        'tipImage' => $fifu['tip']['image'](),
        'tipVideo' => $fifu['tip']['video'](),
        'tipSearch' => $fifu['tip']['search'](),
        'urlImage' => $fifu['url']['image'](),
        'urlVideo' => $fifu['url']['video'](),
        'keywords' => $fifu['image']['keywords'](),
        'isProduct' => isset($_REQUEST['post_type']) && $_REQUEST['post_type'] == 'product',
    ]);
}

function fifu_column_head($default) {
    $fifu = fifu_get_strings_quick_edit();
    $height = FIFU_COLUMN_HEIGHT;
    $default['featured_image'] = "<center style='max-width:{$height}px;min-width:{$height}px'><span class='dashicons dashicons-camera' style='font-size:20px; cursor:help;' title='{$fifu['tip']['column']()}'></span><div style='display:none'>FIFU</div></center>";
    return $default;
}

function fifu_ctgr_column_content($internal_image, $column, $term_id) {
    if ($column == 'featured_image') {
        $border = '';
        $height = FIFU_COLUMN_HEIGHT;
        $width = $height * 1.;

        $is_ctgr = true;
        $post_id = $term_id;
        $image_url = null;

        $image_url = get_term_meta($term_id, 'fifu_image_url', true);
        if ($image_url == '') {
            $thumb_id = get_term_meta($term_id, 'thumbnail_id', true);
            $image_url = wp_get_attachment_url($thumb_id);
            $border = 'border-color: #ca4a1f !important; border: 2px; border-style: dashed;';
        }
        $url = fifu_optimized_column_image($image_url);
        include 'html/column.html';
    } else
        echo $internal_image;
}

function fifu_column_content($column, $post_id) {
    if ($column == 'featured_image') {
        $border = '';
        $height = FIFU_COLUMN_HEIGHT;
        $width = $height * 1.;

        $is_ctgr = false;
        $image_url = null;

        $image_url = fifu_main_image_url($post_id, true);
        if ($image_url == '') {
            $image_url = wp_get_attachment_url(get_post_thumbnail_id());
            $border = 'border-color: #ca4a1f !important; border: 2px; border-style: dashed;';
        }
        $url = fifu_optimized_column_image($image_url);
        include 'html/column.html';
    }
}

function fifu_column_custom_post_type() {
    foreach (fifu_get_post_types() as $post_type)
        add_filter('manage_edit-' . $post_type . '_columns', 'fifu_column_head');
}

function fifu_optimized_column_image($url) {
    if (fifu_is_from_speedup($url)) {
        $url = explode('?', $url)[0];
        return fifu_speedup_get_signed_url($url, 128, 128, null, null, false);
    }

    if (fifu_is_on('fifu_photon')) {
        $height = FIFU_COLUMN_HEIGHT;
        return fifu_jetpack_photon_url($url, fifu_get_photon_args($height, $height));
    }

    return $url;
}

