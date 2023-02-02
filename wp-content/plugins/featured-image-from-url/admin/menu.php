<?php

define('FIFU_SETTINGS', serialize(array('fifu_social', 'fifu_social_image_only', 'fifu_skip', 'fifu_html_cpt', 'fifu_lazy', 'fifu_photon', 'fifu_cdn_social', 'fifu_cdn_crop', 'fifu_cdn_content', 'fifu_reset', 'fifu_content', 'fifu_content_page', 'fifu_content_cpt', 'fifu_enable_default_url', 'fifu_spinner_nth', 'fifu_fake', 'fifu_default_url', 'fifu_default_cpt', 'fifu_wc_lbox', 'fifu_wc_zoom', 'fifu_hide_page', 'fifu_hide_post', 'fifu_hide_cpt', 'fifu_get_first', 'fifu_pop_first', 'fifu_ovw_first', 'fifu_query_strings', 'fifu_run_delete_all', 'fifu_decode', 'fifu_check', 'fifu_auto_alt', 'fifu_dynamic_alt', 'fifu_data_clean', 'fifu_cloud_upload_auto')));
define('FIFU_ACTION_SETTINGS', '/wp-admin/admin.php?page=featured-image-from-url');
define('FIFU_ACTION_CLOUD', '/wp-admin/admin.php?page=fifu-cloud');

define('FIFU_SLUG', 'featured-image-from-url');

add_action('admin_menu', 'fifu_insert_menu');

function fifu_insert_menu() {
    if (strpos($_SERVER['REQUEST_URI'], 'featured-image-from-url') !== false || strpos($_SERVER['REQUEST_URI'], 'fifu') !== false) {
        wp_enqueue_script('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/js/all.min.js');
        wp_enqueue_style('jquery-ui-style1', 'https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css');
        wp_enqueue_style('jquery-ui-style2', 'https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.structure.min.css');
        wp_enqueue_style('jquery-ui-style3', 'https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.theme.min.css');

        wp_enqueue_style('fifu-pro-css', plugins_url('/html/css/pro.css', __FILE__), array(), fifu_version_number());

        wp_enqueue_script('jquery-ui', 'https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js');
        wp_enqueue_script('jquery-block-ui', 'https://cdnjs.cloudflare.com/ajax/libs/jquery.blockUI/2.70/jquery.blockUI.min.js');

        wp_enqueue_style('datatable-css', '//cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css');
        wp_enqueue_style('datatable-select-css', '//cdn.datatables.net/select/1.3.3/css/select.dataTables.min.css');
        wp_enqueue_style('datatable-buttons-css', '//cdn.datatables.net/buttons/2.0.1/css/buttons.dataTables.min.css');
        wp_enqueue_script('datatable-js', '//cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js');
        wp_enqueue_script('datatable-select', '//cdn.datatables.net/select/1.3.3/js/dataTables.select.min.js');
        wp_enqueue_script('datatable-buttons', '//cdn.datatables.net/buttons/2.0.1/js/dataTables.buttons.min.js');

        wp_enqueue_script('lazyload', 'https://cdnjs.cloudflare.com/ajax/libs/jquery.lazyloadxt/1.1.0/jquery.lazyloadxt.min.js');
        wp_enqueue_style('lazyload-spinner', 'https://cdnjs.cloudflare.com/ajax/libs/jquery.lazyloadxt/1.1.0/jquery.lazyloadxt.spinner.min.css');
        wp_enqueue_script('fifu-rest-route-js', plugins_url('/html/js/rest-route.js', __FILE__), array('jquery'), fifu_version_number());

        // register custom variables for the AJAX script
        wp_localize_script('fifu-rest-route-js', 'fifuScriptVars', [
            'restUrl' => esc_url_raw(rest_url()),
            'homeUrl' => esc_url_raw(home_url()),
            'nonce' => wp_create_nonce('wp_rest'),
        ]);
    }

    add_menu_page('Featured Image from URL', 'FIFU', 'manage_options', 'featured-image-from-url', 'fifu_get_menu_html', 'dashicons-camera', 57);
    add_submenu_page('featured-image-from-url', 'FIFU Settings', __('Settings'), 'manage_options', 'featured-image-from-url');
    add_submenu_page('featured-image-from-url', 'FIFU Cloud', __('Cloud'), 'manage_options', 'fifu-cloud', 'fifu_cloud');
    add_submenu_page('featured-image-from-url', 'FIFU Troubleshooting', __('Troubleshooting'), 'manage_options', 'fifu-troubleshooting', 'fifu_troubleshooting');
    add_submenu_page('featured-image-from-url', 'FIFU Status', __('Status'), 'manage_options', 'fifu-support-data', 'fifu_support_data');
    add_submenu_page('featured-image-from-url', 'FIFU Pro', __('<a href="https://fifu.app/" target="_blank"><div style="padding:5px;color:white;background-color:#1da867">Upgrade to <b>PRO</b></div></a>'), 'manage_options', null, null);

    add_action('admin_init', 'fifu_get_menu_settings');
}

function fifu_cloud() {
    flush();

    $fifu = fifu_get_strings_settings();
    $fifucloud = fifu_get_strings_cloud();

    // css and js
    wp_enqueue_script('fifu-cookie', 'https://cdnjs.cloudflare.com/ajax/libs/js-cookie/latest/js.cookie.min.js');
    wp_enqueue_style('fifu-menu-su-css', plugins_url('/html/css/menu-su.css', __FILE__), array(), fifu_version_number());
    wp_enqueue_script('fifu-menu-su-js', plugins_url('/html/js/menu-su.js', __FILE__), array('jquery'), fifu_version_number());
    wp_enqueue_script('fifu-qrcode', plugins_url('/html/js/qrcode.js', __FILE__), array('jquery'), fifu_version_number());

    wp_enqueue_style('fifu-base-ui-css', plugins_url('/html/css/base-ui.css', __FILE__), array(), fifu_version_number());
    wp_enqueue_style('fifu-menu-css', plugins_url('/html/css/menu.css', __FILE__), array(), fifu_version_number());
    wp_enqueue_script('fifu-cloud-js', plugins_url('/html/js/cloud.js', __FILE__), array('jquery'), fifu_version_number());

    wp_localize_script('fifu-cloud-js', 'fifuScriptCloudVars', [
        'signUpComplete' => fifu_su_sign_up_complete(),
        'woocommerce' => class_exists('WooCommerce'),
        'availableImages' => fifu_db_count_available_images(),
    ]);

    $enable_cloud_upload_auto = get_option('fifu_cloud_upload_auto');

    include 'html/cloud.html';

    if (fifu_is_valid_nonce('nonce_fifu_form_cloud_upload_auto', FIFU_ACTION_CLOUD))
        fifu_update_option('fifu_input_cloud_upload_auto', 'fifu_cloud_upload_auto');

    // schedule upload
    if (fifu_is_on('fifu_cloud_upload_auto')) {
        if (!wp_next_scheduled('fifu_create_cloud_upload_auto_event')) {
            wp_schedule_event(time(), 'fifu_schedule_cloud_upload_auto', 'fifu_create_cloud_upload_auto_event');
            fifu_run_cron_now();
        }
    } else {
        wp_clear_scheduled_hook('fifu_create_cloud_upload_auto_event');
        delete_transient('fifu_cloud_upload_auto_semaphore');
        fifu_stop_job('fifu_cloud_upload_auto');
    }
}

function fifu_troubleshooting() {
    flush();

    $fifu = fifu_get_strings_settings();

    // css and js
    wp_enqueue_style('fifu-base-ui-css', plugins_url('/html/css/base-ui.css', __FILE__), array(), fifu_version_number());
    wp_enqueue_style('fifu-menu-css', plugins_url('/html/css/menu.css', __FILE__), array(), fifu_version_number());
    wp_enqueue_script('fifu-troubleshooting-js', plugins_url('/html/js/troubleshooting.js', __FILE__), array('jquery'), fifu_version_number());

    include 'html/troubleshooting.html';
}

function fifu_support_data() {
    // css
    wp_enqueue_style('fifu-base-ui-css', plugins_url('/html/css/base-ui.css', __FILE__), array(), fifu_version_number());
    wp_enqueue_style('fifu-menu-css', plugins_url('/html/css/menu.css', __FILE__), array(), fifu_version_number());
    wp_enqueue_script('fifu-rest-route-js', plugins_url('/html/js/rest-route.js', __FILE__), array('jquery'), fifu_version_number());

    // register custom variables for the AJAX script
    wp_localize_script('fifu-rest-route-js', 'fifuScriptVars', [
        'restUrl' => esc_url_raw(rest_url()),
        'homeUrl' => esc_url_raw(home_url()),
        'nonce' => wp_create_nonce('wp_rest'),
    ]);

    $enable_social = get_option('fifu_social');
    $enable_social_image_only = get_option('fifu_social_image_only');
    $skip = esc_attr(get_option('fifu_skip'));
    $html_cpt = esc_attr(get_option('fifu_html_cpt'));
    $enable_lazy = get_option('fifu_lazy');
    $enable_photon = get_option('fifu_photon');
    $enable_cdn_social = get_option('fifu_cdn_social');
    $enable_cdn_crop = get_option('fifu_cdn_crop');
    $enable_cdn_content = get_option('fifu_cdn_content');
    $enable_reset = get_option('fifu_reset');
    $enable_content = get_option('fifu_content');
    $enable_content_page = get_option('fifu_content_page');
    $enable_content_cpt = get_option('fifu_content_cpt');
    $enable_fake = get_option('fifu_fake');
    $default_url = esc_url(get_option('fifu_default_url'));
    $default_cpt = esc_attr(get_option('fifu_default_cpt'));
    $enable_default_url = get_option('fifu_enable_default_url');
    $nth_image = get_option('fifu_spinner_nth');
    $enable_wc_lbox = get_option('fifu_wc_lbox');
    $enable_wc_zoom = get_option('fifu_wc_zoom');
    $enable_hide_page = get_option('fifu_hide_page');
    $enable_hide_post = get_option('fifu_hide_post');
    $enable_hide_cpt = get_option('fifu_hide_cpt');
    $enable_get_first = get_option('fifu_get_first');
    $enable_pop_first = get_option('fifu_pop_first');
    $enable_ovw_first = get_option('fifu_ovw_first');
    $enable_query_strings = get_option('fifu_query_strings');
    $enable_run_delete_all = get_option('fifu_run_delete_all');
    $enable_run_delete_all_time = get_option('fifu_run_delete_all_time');
    $enable_decode = get_option('fifu_decode');
    $enable_check = get_option('fifu_check');
    $enable_auto_alt = get_option('fifu_auto_alt');
    $enable_dynamic_alt = get_option('fifu_dynamic_alt');
    $enable_data_clean = 'toggleoff';
    $enable_cloud_upload_auto = get_option('fifu_cloud_upload_auto');

    include 'html/support-data.html';
}

function fifu_get_menu_html() {
    flush();

    $fifu = fifu_get_strings_settings();
    $fifucloud = fifu_get_strings_cloud();

    // css and js
    wp_enqueue_style('fifu-base-ui-css', plugins_url('/html/css/base-ui.css', __FILE__), array(), fifu_version_number());
    wp_enqueue_style('fifu-menu-css', plugins_url('/html/css/menu.css', __FILE__), array(), fifu_version_number());
    wp_enqueue_script('fifu-menu-js', plugins_url('/html/js/menu.js', __FILE__), array('jquery'), fifu_version_number());

    // register custom variables for the AJAX script
    wp_localize_script('fifu-menu-js', 'fifuScriptVars', [
        'restUrl' => esc_url_raw(rest_url()),
        'homeUrl' => esc_url_raw(home_url()),
        'nonce' => wp_create_nonce('wp_rest'),
        'wait' => $fifu['php']['message']['wait'](),
    ]);

    $enable_social = get_option('fifu_social');
    $enable_social_image_only = get_option('fifu_social_image_only');
    $skip = esc_attr(get_option('fifu_skip'));
    $html_cpt = esc_attr(get_option('fifu_html_cpt'));
    $enable_lazy = get_option('fifu_lazy');
    $enable_photon = get_option('fifu_photon');
    $enable_cdn_social = get_option('fifu_cdn_social');
    $enable_cdn_crop = get_option('fifu_cdn_crop');
    $enable_cdn_content = get_option('fifu_cdn_content');
    $enable_reset = get_option('fifu_reset');
    $enable_content = get_option('fifu_content');
    $enable_content_page = get_option('fifu_content_page');
    $enable_content_cpt = get_option('fifu_content_cpt');
    $enable_fake = get_option('fifu_fake');
    $default_url = esc_url(get_option('fifu_default_url'));
    $default_cpt = esc_attr(get_option('fifu_default_cpt'));
    $enable_default_url = get_option('fifu_enable_default_url');
    $nth_image = get_option('fifu_spinner_nth');
    $enable_wc_lbox = get_option('fifu_wc_lbox');
    $enable_wc_zoom = get_option('fifu_wc_zoom');
    $enable_hide_page = get_option('fifu_hide_page');
    $enable_hide_post = get_option('fifu_hide_post');
    $enable_hide_cpt = get_option('fifu_hide_cpt');
    $enable_get_first = get_option('fifu_get_first');
    $enable_pop_first = get_option('fifu_pop_first');
    $enable_ovw_first = get_option('fifu_ovw_first');
    $enable_query_strings = get_option('fifu_query_strings');
    $enable_run_delete_all = get_option('fifu_run_delete_all');
    $enable_run_delete_all_time = get_option('fifu_run_delete_all_time');
    $enable_decode = get_option('fifu_decode');
    $enable_check = get_option('fifu_check');
    $enable_auto_alt = get_option('fifu_auto_alt');
    $enable_dynamic_alt = get_option('fifu_dynamic_alt');
    $enable_data_clean = 'toggleoff';

    include 'html/menu.html';

    $arr = fifu_update_menu_options();

    // default
    if (!$arr['fifu_default_cpt']) { # submit via post type form
        $default_url = $arr['fifu_default_url']; # submit via default url form
        if (!empty($default_url) && fifu_is_on('fifu_enable_default_url') && fifu_is_on('fifu_fake')) {
            if (!wp_get_attachment_url(get_option('fifu_default_attach_id'))) {
                $att_id = fifu_db_create_attachment($default_url);
                update_option('fifu_default_attach_id', $att_id);
                fifu_db_set_default_url();
            } else
                fifu_db_update_default_url($default_url);
        }
    }

    // reset
    if (fifu_is_on('fifu_reset')) {
        fifu_reset_settings();
        update_option('fifu_reset', 'toggleoff', 'no');
    }
}

function fifu_get_menu_settings() {
    foreach (unserialize(FIFU_SETTINGS) as $i)
        fifu_get_setting($i);
}

function fifu_reset_settings() {
    foreach (unserialize(FIFU_SETTINGS) as $i) {
        if ($i != 'fifu_key' &&
                $i != 'fifu_email' &&
                $i != 'fifu_default_url' &&
                $i != 'fifu_enable_default_url')
            delete_option($i);
    }
}

function fifu_get_setting($type) {
    register_setting('settings-group', $type);

    $arr1 = array('fifu_spinner_nth');
    $arrEmpty = array('fifu_default_url', 'fifu_skip', 'fifu_html_cpt');
    $arrDefaultType = array('fifu_default_cpt');
    $arrOn = array('fifu_wc_zoom', 'fifu_wc_lbox');
    $arrOnNo = array('fifu_fake', 'fifu_social');
    $arrOffNo = array('fifu_data_clean', 'fifu_run_delete_all', 'fifu_reset', 'fifu_social_image_only');

    if (get_option($type) === false) {
        if (in_array($type, $arrEmpty))
            update_option($type, '');
        else if (in_array($type, $arr1))
            update_option($type, 1);
        else if (in_array($type, $arrDefaultType))
            update_option($type, "post,page,product", 'no');
        else if (in_array($type, $arrOn))
            update_option($type, 'toggleon');
        else if (in_array($type, $arrOnNo))
            update_option($type, 'toggleon', 'no');
        else if (in_array($type, $arrOffNo))
            update_option($type, 'toggleoff', 'no');
        else
            update_option($type, 'toggleoff');
    }
}

function fifu_update_menu_options() {
    if (fifu_is_valid_nonce('nonce_fifu_form_social'))
        fifu_update_option('fifu_input_social', 'fifu_social');

    if (fifu_is_valid_nonce('nonce_fifu_form_social_image_only'))
        fifu_update_option('fifu_input_social_image_only', 'fifu_social_image_only');

    if (fifu_is_valid_nonce('nonce_fifu_form_skip'))
        fifu_update_option('fifu_input_skip', 'fifu_skip');

    if (fifu_is_valid_nonce('nonce_fifu_form_html_cpt'))
        fifu_update_option('fifu_input_html_cpt', 'fifu_html_cpt');

    if (fifu_is_valid_nonce('nonce_fifu_form_lazy'))
        fifu_update_option('fifu_input_lazy', 'fifu_lazy');

    if (fifu_is_valid_nonce('nonce_fifu_form_photon'))
        fifu_update_option('fifu_input_photon', 'fifu_photon');

    if (fifu_is_valid_nonce('nonce_fifu_form_cdn_social'))
        fifu_update_option('fifu_input_cdn_social', 'fifu_cdn_social');

    if (fifu_is_valid_nonce('nonce_fifu_form_cdn_crop'))
        fifu_update_option('fifu_input_cdn_crop', 'fifu_cdn_crop');

    if (fifu_is_valid_nonce('nonce_fifu_form_cdn_content'))
        fifu_update_option('fifu_input_cdn_content', 'fifu_cdn_content');

    if (fifu_is_valid_nonce('nonce_fifu_form_reset'))
        fifu_update_option('fifu_input_reset', 'fifu_reset');

    if (fifu_is_valid_nonce('nonce_fifu_form_content'))
        fifu_update_option('fifu_input_content', 'fifu_content');

    if (fifu_is_valid_nonce('nonce_fifu_form_content_page'))
        fifu_update_option('fifu_input_content_page', 'fifu_content_page');

    if (fifu_is_valid_nonce('nonce_fifu_form_content_cpt'))
        fifu_update_option('fifu_input_content_cpt', 'fifu_content_cpt');

    if (fifu_is_valid_nonce('nonce_fifu_form_fake'))
        fifu_update_option('fifu_input_fake', 'fifu_fake');

    if (fifu_is_valid_nonce('nonce_fifu_form_default_url'))
        fifu_update_option('fifu_input_default_url', 'fifu_default_url');

    if (fifu_is_valid_nonce('nonce_fifu_form_default_cpt'))
        fifu_update_option('fifu_input_default_cpt', 'fifu_default_cpt');

    if (fifu_is_valid_nonce('nonce_fifu_form_enable_default_url'))
        fifu_update_option('fifu_input_enable_default_url', 'fifu_enable_default_url');

    if (fifu_is_valid_nonce('nonce_fifu_form_spinner_nth'))
        fifu_update_option('fifu_input_spinner_nth', 'fifu_spinner_nth');

    if (fifu_is_valid_nonce('nonce_fifu_form_wc_lbox'))
        fifu_update_option('fifu_input_wc_lbox', 'fifu_wc_lbox');

    if (fifu_is_valid_nonce('nonce_fifu_form_wc_zoom'))
        fifu_update_option('fifu_input_wc_zoom', 'fifu_wc_zoom');

    if (fifu_is_valid_nonce('nonce_fifu_form_hide_page'))
        fifu_update_option('fifu_input_hide_page', 'fifu_hide_page');

    if (fifu_is_valid_nonce('nonce_fifu_form_hide_post'))
        fifu_update_option('fifu_input_hide_post', 'fifu_hide_post');

    if (fifu_is_valid_nonce('nonce_fifu_form_hide_cpt'))
        fifu_update_option('fifu_input_hide_cpt', 'fifu_hide_cpt');

    if (fifu_is_valid_nonce('nonce_fifu_form_get_first'))
        fifu_update_option('fifu_input_get_first', 'fifu_get_first');

    if (fifu_is_valid_nonce('nonce_fifu_form_pop_first'))
        fifu_update_option('fifu_input_pop_first', 'fifu_pop_first');

    if (fifu_is_valid_nonce('nonce_fifu_form_ovw_first'))
        fifu_update_option('fifu_input_ovw_first', 'fifu_ovw_first');

    if (fifu_is_valid_nonce('nonce_fifu_form_query_strings'))
        fifu_update_option('fifu_input_query_strings', 'fifu_query_strings');

    if (fifu_is_valid_nonce('nonce_fifu_form_run_delete_all'))
        fifu_update_option('fifu_input_run_delete_all', 'fifu_run_delete_all');

    if (fifu_is_valid_nonce('nonce_fifu_form_decode'))
        fifu_update_option('fifu_input_decode', 'fifu_decode');

    if (fifu_is_valid_nonce('nonce_fifu_form_check'))
        fifu_update_option('fifu_input_check', 'fifu_check');

    if (fifu_is_valid_nonce('nonce_fifu_form_auto_alt'))
        fifu_update_option('fifu_input_auto_alt', 'fifu_auto_alt');

    if (fifu_is_valid_nonce('nonce_fifu_form_dynamic_alt'))
        fifu_update_option('fifu_input_dynamic_alt', 'fifu_dynamic_alt');

    if (fifu_is_valid_nonce('nonce_fifu_form_data_clean'))
        fifu_update_option('fifu_input_data_clean', 'fifu_data_clean');

    // delete all run log
    if (fifu_is_on('fifu_run_delete_all'))
        update_option('fifu_run_delete_all_time', current_time('mysql'), 'no');

    // urgent updates
    $arr = array();
    if (isset($_POST['fifu_input_default_url'])) {
        $arr['fifu_default_url'] = wp_strip_all_tags($_POST['fifu_input_default_url']);
    } else {
        $default_url = get_option('fifu_default_url');
        $arr['fifu_default_url'] = $default_url ? $default_url : '';
    }

    if (isset($_POST['fifu_input_default_cpt'])) {
        $arr['fifu_default_cpt'] = wp_strip_all_tags($_POST['fifu_input_default_cpt']);
    } else
        $arr['fifu_default_cpt'] = null;

    return $arr;
}

function fifu_update_option($input, $field) {
    if (!isset($_POST[$input]))
        return;

    $value = $_POST[$input];

    $arr_boolean = array('fifu_auto_alt', 'fifu_cdn_content', 'fifu_cdn_crop', 'fifu_cdn_social', 'fifu_check', 'fifu_content', 'fifu_content_cpt', 'fifu_content_page', 'fifu_data_clean', 'fifu_decode', 'fifu_dynamic_alt', 'fifu_enable_default_url', 'fifu_fake', 'fifu_get_first', 'fifu_hide_cpt', 'fifu_hide_page', 'fifu_hide_post', 'fifu_lazy', 'fifu_ovw_first', 'fifu_photon', 'fifu_pop_first', 'fifu_query_strings', 'fifu_reset', 'fifu_run_delete_all', 'fifu_social', 'fifu_social_image_only', 'fifu_wc_lbox', 'fifu_wc_zoom', 'fifu_cloud_upload_auto');
    if (in_array($field, $arr_boolean)) {
        if (in_array($value, array('on', 'off')))
            update_option($field, 'toggle' . $value);
        return;
    }

    $arr_int = array('fifu_fake_created', 'fifu_spinner_nth');
    if (in_array($field, $arr_int)) {
        if (filter_var($value, FILTER_VALIDATE_INT))
            update_option($field, $value);
        return;
    }

    $arr_url = array('fifu_default_url');
    if (in_array($field, $arr_url)) {
        if (empty($value) || filter_var($value, FILTER_VALIDATE_URL))
            update_option($field, esc_url_raw($value));
        return;
    }

    $arr_text = array('fifu_default_cpt', 'fifu_skip', 'fifu_html_cpt');
    if (in_array($field, $arr_text))
        update_option($field, sanitize_text_field($value));
}

function fifu_enable_fake() {
    if (get_option('fifu_fake_created') && get_option('fifu_fake_created') != null)
        return;
    update_option('fifu_fake_created', true, 'no');

    fifu_db_change_url_length();
    fifu_db_insert_attachment();
    fifu_db_insert_attachment_category();
}

function fifu_disable_fake() {
    if (!get_option('fifu_fake_created') && get_option('fifu_fake_created') != null)
        return;
    update_option('fifu_fake_created', false, 'no');

    fifu_db_delete_default_url();
    fifu_db_delete_attachment();
    fifu_db_delete_attachment_category();
}

function fifu_version() {
    $plugin_data = get_plugin_data(FIFU_PLUGIN_DIR . 'featured-image-from-url.php');
    return $plugin_data ? $plugin_data['Name'] . ':' . $plugin_data['Version'] : '';
}

function fifu_version_number() {
    return get_plugin_data(FIFU_PLUGIN_DIR . 'featured-image-from-url.php')['Version'];
}

function fifu_su_sign_up_complete() {
    return isset(get_option('fifu_su_privkey')[0]) ? true : false;
}

function fifu_su_get_email() {
    return base64_decode(get_option('fifu_su_email')[0]);
}

function fifu_get_last($meta_key) {
    $list = '';
    foreach (fifu_db_get_last($meta_key) as $key => $row) {
        $aux = $row->meta_value . ' &#10; |__ ' . get_permalink($row->id);
        $list .= '&#10; | ' . $aux;
    }
    return $list;
}

function fifu_get_plugins_list() {
    $list = '';
    foreach (get_plugins() as $key => $domain) {
        $name = $domain['Name'] . ' (' . $domain['TextDomain'] . ')';
        $list .= '&#10; - ' . $name;
    }
    return $list;
}

function fifu_get_active_plugins_list() {
    $list = '';
    foreach (get_option('active_plugins') as $key) {
        $name = explode('/', $key)[0];
        $list .= '&#10; - ' . $name;
    }
    return $list;
}

function fifu_has_curl() {
    return function_exists('curl_version');
}

function fifu_number_of_users() {
    return count_users()['total_users'];
}

function fifu_is_valid_nonce($nonce, $action = FIFU_ACTION_SETTINGS) {
    return isset($_POST[$nonce]) && wp_verify_nonce($_POST[$nonce], $action);
}

