<?php
add_action('add_meta_boxes', 'fifu_insert_meta_box');

function fifu_insert_meta_box() {
    if (fifu_is_web_story() || fifu_is_search_filter_pro())
        return;

    $fifu = fifu_get_strings_meta_box_php();
    $post_types = fifu_get_post_types();

    foreach ($post_types as $post_type) {
        if ($post_type == 'product') {
            add_meta_box('urlMetaBox', $fifu['title']['product']['image'](), 'fifu_show_elements', $post_type, 'side', 'default');
        } else if ($post_type) {
            add_meta_box('imageUrlMetaBox', $fifu['title']['post']['image'](), 'fifu_show_elements', $post_type, 'side', 'default');
        }
    }
    fifu_register_meta_box_script();
}

add_action('add_meta_boxes', 'remove_metaboxes', 50);

function remove_metaboxes() {
    global $post;

    if (!$post)
        return;

    $url = get_post_meta($post->ID, 'fifu_image_url', true);
    if ($url) {
        if (!fifu_is_rank_math_seo_active())
            remove_meta_box('postimagediv', 'product', 'side');
    }
}

function fifu_register_meta_box_script() {
    $fifu = fifu_get_strings_meta_box_php();
    $fifu_help = fifu_get_strings_help();

    wp_enqueue_script('fifu-cookie', 'https://cdnjs.cloudflare.com/ajax/libs/js-cookie/latest/js.cookie.min.js');

    wp_enqueue_script('jquery-block-ui', 'https://cdnjs.cloudflare.com/ajax/libs/jquery.blockUI/2.70/jquery.blockUI.min.js');
    wp_enqueue_style('fancy-box-css', 'https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.css');
    wp_enqueue_script('fancy-box-js', 'https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js');

    wp_enqueue_script('fifu-rest-route-js', plugins_url('/html/js/rest-route.js', __FILE__), array('jquery'), fifu_version_number());
    wp_enqueue_script('fifu-meta-box-js', plugins_url('/html/js/meta-box.js', __FILE__), array('jquery'), fifu_version_number());
    wp_enqueue_script('fifu-convert-url-js', plugins_url('/html/js/convert-url.js', __FILE__), array('jquery'), fifu_version_number());

    wp_register_style('fifu-unsplash-css', plugins_url('/html/css/unsplash.css', __FILE__), array(), fifu_version_number());
    wp_enqueue_style('fifu-unsplash-css');
    wp_enqueue_script('fifu-unsplash-js', plugins_url('/html/js/unsplash.js', __FILE__), array('jquery'), fifu_version_number());

    // register custom variables for the AJAX script
    wp_localize_script('fifu-rest-route-js', 'fifuScriptVars', [
        'restUrl' => esc_url_raw(rest_url()),
        'homeUrl' => esc_url_raw(home_url()),
        'nonce' => wp_create_nonce('wp_rest'),
    ]);

    if (fifu_is_sirv_active())
        wp_enqueue_script('fifu-sirv-js', 'https://scripts.sirv.com/sirv.js');

    wp_localize_script('fifu-meta-box-js', 'fifuMetaBoxVars', [
        'get_the_ID' => get_the_ID(),
        'is_sirv_active' => fifu_is_sirv_active(),
        'wait' => $fifu['common']['wait'](),
        'is_taxonomy' => get_current_screen()->taxonomy,
        'txt_title_examples' => $fifu_help['title']['examples'](),
        'txt_title_keywords' => $fifu_help['title']['keywords'](),
        'txt_title_more' => $fifu_help['title']['more'](),
        'txt_title_url' => $fifu_help['title']['url'](),
        'txt_desc_empty' => $fifu_help['desc']['empty'](),
        'txt_desc_size' => $fifu_help['desc']['size'](),
        'txt_desc_more' => $fifu_help['desc']['more'](),
    ]);
}

add_action('add_meta_boxes', 'fifu_add_css');

function fifu_add_css() {
    wp_register_style('featured-image-from-url', plugins_url('/html/css/editor.css', __FILE__), array(), fifu_version_number());
    wp_enqueue_style('featured-image-from-url');
}

function fifu_show_elements($post) {
    $margin = 'margin-top:5px;margin-left:3px;';
    $width = 'width:100%;';
    $height = 'height:200px;';
    $align = 'text-align:left;';

    $url = get_post_meta($post->ID, 'fifu_image_url', true);
    $alt = get_post_meta($post->ID, 'fifu_image_alt', true);

    if ($url) {
        $show_button = 'display:none;';
        $show_alt = $show_image = $show_link = '';
    } else {
        $show_alt = $show_image = $show_link = 'display:none;';
        $show_button = '';
    }

    $show_ignore = fifu_is_on('fifu_get_first') || fifu_is_on('fifu_pop_first') || fifu_is_on('fifu_ovw_first') ? '' : 'display:none;';

    $check_ignore = fifu_is_on('fifu_check') ? 'checked' : '';

    $fifu = fifu_get_strings_meta_box();
    include 'html/meta-box.html';
}

add_filter('wp_insert_post_data', 'fifu_remove_first_image', 10, 2);

function fifu_remove_first_image($data, $postarr) {
    /* invalid or external or ignore */
    if (!$_POST || !isset($_POST['fifu_input_url']) || isset($_POST['fifu_ignore_auto_set']))
        return $data;

    $post_id = $postarr['ID'];
    if (fifu_has_local_featured_image($post_id) || !fifu_is_valid_cpt($post_id))
        return $data;

    $content = $postarr['post_content'];
    if (!$content)
        return $data;

    $contentClean = fifu_show_all_images($content);
    $data = str_replace($content, $contentClean, $data);

    $img = fifu_first_img_in_content($contentClean);
    if (!$img)
        return $data;

    if (fifu_is_off('fifu_pop_first'))
        return str_replace($img, fifu_show_media($img), $data);

    return str_replace($img, fifu_hide_media($img), $data);
}

// for wp all import: avoid duplicated images
function fifu_has_properties() {
    if (fifu_is_ol_scrapes_active())
        return true;

    foreach ($_POST as $key => $value) {
        if (strpos($key, 'fifu') !== false)
            return true;
    }
    return false;
}

add_action('save_post', 'fifu_save_properties');

function fifu_save_properties($post_id) {
    if (!$_POST || get_post_type($post_id) == 'nav_menu_item' || get_post_type($post_id) == 'revision')
        return;

    if (isset($_POST['action']) && $_POST['action'] == 'woocommerce_do_ajax_product_import')
        return;

    if (isset($_POST['dokan_edit_product_nonce']))
        return;

    /* image url from wcfm */
    if (isset($_POST['action']) && $_POST['action'] == 'wcfm_ajax_controller') {
        if (fifu_is_wcfm_active() && isset($_POST['wcfm_products_manage_form'])) {
            $image_url = esc_url_raw(rtrim(fifu_get_wcfm_url($_POST['wcfm_products_manage_form'])));
            fifu_dev_set_image($post_id, $image_url);
            return;
        }
    }

    if (!fifu_has_properties())
        return;

    $ignore = false;
    if (isset($_POST['fifu_ignore_auto_set']))
        $ignore = $_POST['fifu_ignore_auto_set'] == 'on';

    /* image url */
    $url = null;
    if (isset($_POST['fifu_input_url'])) {
        $url = esc_url_raw(rtrim($_POST['fifu_input_url']));
        if (!$ignore) {
            $first = fifu_first_url_in_content($post_id);
            if ($first && fifu_is_on('fifu_get_first') && (!$url || fifu_is_on('fifu_ovw_first')) && !fifu_has_local_featured_image($post_id) && fifu_is_valid_cpt($post_id))
                $url = $first;
        }
        fifu_update_or_delete($post_id, 'fifu_image_url', $url);
    }

    /* image url from toolset forms */
    if (fifu_is_toolset_active() && isset($_POST['wpcf-fifu_image_url'])) {
        $url = esc_url_raw(rtrim($_POST['wpcf-fifu_image_url']));
        if ($url)
            fifu_update_or_delete($post_id, 'fifu_image_url', $url);
    }

    /* image url from aliplugin */
    if (fifu_is_aliplugin_active() && isset($_POST['imageUrl'])) {
        $url = esc_url_raw(rtrim($_POST['imageUrl']));
        if ($url)
            fifu_update_or_delete($post_id, 'fifu_image_url', $url);
    }

    /* alt */
    if (isset($_POST['fifu_input_alt'])) {
        $alt = wp_strip_all_tags($_POST['fifu_input_alt']);
        $post_title = get_the_title();
        $alt = !$alt && $url && fifu_is_on('fifu_auto_alt') && $post_title != 'AUTO-DRAFT' ? $post_title : $alt;
        fifu_update_or_delete_value($post_id, 'fifu_image_alt', $alt);
    }

    fifu_save($post_id, $ignore);

    /* dimensions featured */
    $width = fifu_get_width_meta($_POST);
    $height = fifu_get_height_meta($_POST);
    $att_id = get_post_thumbnail_id($post_id);
    fifu_save_dimensions($att_id, $width, $height);
}

function fifu_save_dimensions($att_id, $width, $height) {
    if (!$att_id || !$width || !$height)
        return;

    $metadata = null;
    $metadata['width'] = $width;
    $metadata['height'] = $height;

    wp_update_attachment_metadata($att_id, $metadata);
}

function fifu_save($post_id, $ignore) {
    fifu_update_fake_attach_id($post_id);
}

function fifu_update_or_delete($post_id, $field, $url) {
    if ($url) {
        update_post_meta($post_id, $field, fifu_convert($url));
    } else
        delete_post_meta($post_id, $field, $url);
}

function fifu_update_or_delete_value($post_id, $field, $value) {
    if ($value)
        update_post_meta($post_id, $field, $value);
    else
        delete_post_meta($post_id, $field, $value);
}

function fifu_update_or_delete_ctgr($post_id, $field, $url) {
    if ($url) {
        update_term_meta($post_id, $field, fifu_convert($url));
    } else
        delete_term_meta($post_id, $field, $url);
}

add_action('before_delete_post', 'fifu_db_before_delete_post');

/* plugin: wcfm */

function fifu_is_wcfm_active() {
    return is_plugin_active('wc-frontend-manager/wc_frontend_manager.php');
}

function fifu_get_wcfm_url($content) {
    $url = explode('fifu_image_url=', $content)[1];
    return $url ? urldecode(explode('&', $url)[0]) : null;
}

/* plugin: toolset forms */

function fifu_is_toolset_active() {
    return is_plugin_active('cred-frontend-editor/plugin.php');
}

/* plugin: aliplugin */

function fifu_is_aliplugin_active() {
    return is_plugin_active('aliplugin/aliplugin.php');
}

/* plugin: sirv */

function fifu_is_sirv_active() {
    return is_plugin_active('sirv/sirv.php');
}

/* woocommerce variation elements */

// add_action('woocommerce_product_after_variable_attributes', 'fifu_variation_settings_fields', 10, 3);

function fifu_variation_settings_fields($loop, $variation_data, $variation) {
    $fifu = fifu_get_strings_meta_box_php();

    // variation
    woocommerce_wp_text_input(
            array(
                'id' => "fifu_image_url{$loop}",
                'name' => "fifu_image_url[{$loop}]",
                'value' => get_post_meta($variation->ID, 'fifu_image_url', true),
                'label' => '<span class="dashicons dashicons-camera" style="font-size:20px"></span>' . $fifu['variation']['field'](),
                'desc_tip' => true,
                'description' => $fifu['variation']['info'](),
                'placeholder' => $fifu['variation']['image'](),
                'wrapper_class' => 'form-row form-row-full',
            )
    );
    // variation gallery
    for ($i = 0; $i < 3; $i++) {
        woocommerce_wp_text_input(
                array(
                    'id' => "fifu_image_url_" . $i . "{$loop}",
                    'name' => "fifu_image_url_" . $i . "[{$loop}]",
                    'value' => get_post_meta($variation->ID, 'fifu_image_url_' . $i, true),
                    'label' => '<span class="dashicons dashicons-format-gallery" style="font-size:20px"></span>' . $fifu['variation']['images']() . ' #' . ($i + 1),
                    'desc_tip' => true,
                    'placeholder' => $fifu['variation']['image'](),
                    'wrapper_class' => 'form-row form-row-full',
                )
        );
    }
}

/* dimensions */

function fifu_get_width_meta($req) {
    if (isset($req['fifu_input_url']) && isset($req['fifu_input_image_width']) && $req['fifu_input_url'])
        return wp_strip_all_tags($req['fifu_input_image_width']);

    return null;
}

function fifu_get_height_meta($req) {
    if (isset($req['fifu_input_url']) && isset($req['fifu_input_image_height']) && $req['fifu_input_url'])
        return wp_strip_all_tags($req['fifu_input_image_height']);

    return null;
}

/* plugin: wordpress importer */

add_action('import_end', 'fifu_import_end', 10, 0);

function fifu_import_end() {
    if ($_POST['action'] == "woocommerce_csv_import_request" && !isset($_POST['mapping']))
        return;
    fifu_db_delete_thumbnail_id_without_attachment();
    fifu_db_insert_attachment();
    fifu_db_insert_attachment_category();
}

/* plugin: yoast duplicate post */

function fifu_duplicate_post_meta_keys_filter($meta_keys) {
    $remove_thumbnail = false;
    $thumbnail_id = null;

    for ($i = 0; $i < count($meta_keys); $i++) {
        if (fifu_starts_with($meta_keys[$i], 'fifu'))
            $remove_thumbnail = true;
        elseif ($meta_keys[$i] == '_thumbnail_id')
            $thumbnail_id = $i;
    }

    if ($remove_thumbnail)
        unset($meta_keys[$thumbnail_id]);

    return $meta_keys;
}

add_filter('duplicate_post_meta_keys_filter', 'fifu_duplicate_post_meta_keys_filter');

/* plugin: bear - bulk editor and products manager professional for woocommerce */

add_filter('woobe_before_update_product_field', 'fifu_woobe_bulk_finished', 10, 3);

function fifu_woobe_bulk_finished($value, $product_id, $field_key) {
    if ($field_key == 'fifu_image_url')
        fifu_dev_set_image($product_id, $value);

    return $value;
}

/* plugin: dokan */

add_action('dokan_new_product_after_product_tags', 'fifu_dokan_new_product_after_product_tags', 10);

function fifu_dokan_new_product_after_product_tags() {
    $fifu = fifu_get_strings_dokan();
    ?>

    <div class="dokan-form-group">
        <label for="fifu_input_url" class="form-label"><span class="dashicons dashicons-camera" style="font-size:20px"></span> <?php $fifu['title']['product']['image'](); ?></label>
        <input type="text" class="dokan-form-control" name="fifu_input_url" placeholder="<?php $fifu['placeholder']['product']['image'](); ?>">
    </div>

    <?php
}

add_action('dokan_product_edit_after_product_tags', 'fifu_dokan_product_edit_after_product_tags', 99, 2);

function fifu_dokan_product_edit_after_product_tags($post, $post_id) {
    $fifu = fifu_get_strings_dokan();
    $url = get_post_meta($post_id, 'fifu_image_url', true);
    ?>

    <div class="dokan-form-group">
        <label for="fifu_input_url" class="form-label"><span class="dashicons dashicons-camera" style="font-size:20px"></span> <?php $fifu['title']['product']['image'](); ?></label>
        <input type="text" class="dokan-form-control" name="fifu_input_url" value="<?php echo $url; ?>" placeholder="<?php $fifu['placeholder']['product']['image'](); ?>">
    </div>

    <?php
}

add_action('dokan_new_product_added', 'fifu_dokan_save_meta', 10, 2);
add_action('dokan_product_updated', 'fifu_dokan_save_meta', 10, 2);

function fifu_dokan_save_meta($post_id, $data) {
    if (!dokan_is_user_seller(get_current_user_id()))
        return;

    /* featured image */

    $url = esc_url_raw(rtrim($data['fifu_input_url']));
    fifu_update_or_delete($post_id, 'fifu_image_url', $url);

    fifu_update_fake_attach_id($post_id);
}

/* plugin: datafeedr */

add_filter('dfrps_do_import_product_thumbnail/do_import', function (bool $do_import, WP_Post $post, array $product) {
    if (!isset($product['image']))
        return $do_import;

    $do_import = false;
    fifu_dev_set_image($post->ID, $product['image']);

    return $do_import;
}, 10, 3);

