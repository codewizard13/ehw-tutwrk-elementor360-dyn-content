<?php

function fifu_category_scripts() {
    wp_enqueue_style('fifu-category-css', plugins_url('/html/css/category.css', __FILE__), array(), fifu_version_number());

    fifu_register_meta_box_script();
}

add_action('product_cat_edit_form_fields', 'fifu_ctgr_edit_box');
add_action('product_cat_add_form_fields', 'fifu_ctgr_add_box');

function fifu_ctgr_edit_box($term) {
    fifu_category_scripts();

    $margin = 'margin-top:10px;';
    $width = 'width:100%;';
    $height = 'height:200px;';
    $align = 'text-align:left;';
    $url = $alt = null;

    if (is_object($term)) {
        $url = get_term_meta($term->term_id, 'fifu_image_url', true);
        $alt = get_term_meta($term->term_id, 'fifu_image_alt', true);
    }

    if ($url) {
        $show_button = 'display:none;';
        $show_alt = $show_image = $show_link = '';
    } else {
        $show_button = '';
        $show_alt = $show_image = $show_link = 'display:none;';
    }
    $show_ignore = 'display:none;';

    $check_ignore = fifu_is_on('fifu_check') ? 'checked' : '';

    $fifu = fifu_get_strings_meta_box();
    include 'html/category.html';
}

function fifu_ctgr_add_box() {
    fifu_category_scripts();

    $margin = 'margin-top:10px;';
    $width = 'width:100%;';
    $height = 'height:200px;';
    $align = 'text-align:left;';

    $show_button = $url = $alt = '';
    $show_alt = $show_image = $show_link = 'display:none;';
    $show_ignore = 'display:none;';

    $check_ignore = fifu_is_on('fifu_check') ? 'checked' : '';

    $fifu = fifu_get_strings_meta_box();
    include 'html/category.html';
}

add_action('edited_product_cat', 'fifu_ctgr_save_properties', 10, 1);
add_action('created_product_cat', 'fifu_ctgr_save_properties', 10, 1);

function fifu_ctgr_save_properties($term_id) {
    if (isset($_POST['fifu_input_alt'])) {
        if (empty($_POST['fifu_input_alt']))
            delete_term_meta($term_id, 'fifu_image_alt');
        else
            update_term_meta($term_id, 'fifu_image_alt', wp_strip_all_tags($_POST['fifu_input_alt']));
    }

    if (isset($_POST['fifu_input_url'])) {
        $url = esc_url_raw(rtrim($_POST['fifu_input_url']));
        update_term_meta($term_id, 'fifu_image_url', fifu_convert($url));
        if (empty($url)) {
            delete_term_meta($term_id, 'fifu_image_url');
            fifu_db_ctgr_update_fake_attach_id($term_id);
        } else {
            fifu_db_ctgr_update_fake_attach_id($term_id);
            /* dimensions */
            $width = fifu_get_width_meta($_POST);
            $height = fifu_get_height_meta($_POST);
            $att_id = get_term_meta($term_id, 'thumbnail_id', true);
            fifu_save_dimensions($att_id, $width, $height);
        }
    }
}

