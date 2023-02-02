<?php

/*
 * Plugin Name: Featured Image from URL (FIFU)
 * Plugin URI: https://fifu.app/
 * Description: Use an external image/video/audio as featured image of a post or WooCommerce product.
 * Version: 4.2.4
 * Author: fifu.app
 * Author URI: https://fifu.app/
 * WC requires at least: 4.0
 * WC tested up to: 7.3.0
 * Text Domain: featured-image-from-url
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 */

define('FIFU_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('FIFU_INCLUDES_DIR', FIFU_PLUGIN_DIR . 'includes');
define('FIFU_ADMIN_DIR', FIFU_PLUGIN_DIR . 'admin');
define('FIFU_ELEMENTOR_DIR', FIFU_PLUGIN_DIR . 'elementor');
define('FIFU_GRAVITY_DIR', FIFU_PLUGIN_DIR . 'gravity-forms');
define('FIFU_DELETE_ALL_URLS', false);
define('FIFU_CLOUD_DEBUG', false);

require_once (FIFU_INCLUDES_DIR . '/attachment.php');
require_once (FIFU_INCLUDES_DIR . '/convert-url.php');
require_once (FIFU_INCLUDES_DIR . '/external-post.php');
require_once (FIFU_INCLUDES_DIR . '/jetpack.php');
require_once (FIFU_INCLUDES_DIR . '/speedup.php');
require_once (FIFU_INCLUDES_DIR . '/thumbnail.php');
require_once (FIFU_INCLUDES_DIR . '/thumbnail-category.php');
require_once (FIFU_INCLUDES_DIR . '/util.php');
require_once (FIFU_INCLUDES_DIR . '/woo.php');

require_once (FIFU_ADMIN_DIR . '/api.php');
require_once (FIFU_ADMIN_DIR . '/db.php');
require_once (FIFU_ADMIN_DIR . '/category.php');
require_once (FIFU_ADMIN_DIR . '/column.php');
require_once (FIFU_ADMIN_DIR . '/cron.php');
require_once (FIFU_ADMIN_DIR . '/log.php');
require_once (FIFU_ADMIN_DIR . '/menu.php');
require_once (FIFU_ADMIN_DIR . '/meta-box.php');
require_once (FIFU_ADMIN_DIR . '/rsa.php');
require_once (FIFU_ADMIN_DIR . '/strings.php');
require_once (FIFU_ADMIN_DIR . '/widgets.php');

require_once (FIFU_ELEMENTOR_DIR . '/elementor-fifu-extension.php');

if (fifu_is_gravity_forms_active()) {
    require_once (WP_PLUGIN_DIR . '/gravityforms/gravityforms.php');
    if (class_exists('GFForms'))
        require_once (FIFU_GRAVITY_DIR . '/fifufieldaddon.php');
}

if (defined('WP_CLI') && WP_CLI)
    require_once (FIFU_ADMIN_DIR . '/cli-commands.php');

register_activation_hook(__FILE__, 'fifu_activate');

function fifu_activate($network_wide) {
    if (is_multisite() && $network_wide) {
        global $wpdb;
        foreach ($wpdb->get_col("SELECT blog_id FROM $wpdb->blogs") as $blog_id) {
            switch_to_blog($blog_id);
            fifu_activate_actions();
        }
    } else {
        fifu_activate_actions();
    }
}

function fifu_activate_actions() {
    fifu_db_change_url_length();
}

register_deactivation_hook(__FILE__, 'fifu_deactivation');

function fifu_deactivation() {
    wp_clear_scheduled_hook('fifu_create_cloud_upload_auto_event');
}

add_action('upgrader_process_complete', 'fifu_upgrade', 10, 2);

function fifu_upgrade($upgrader_object, $options) {
    $current_plugin_path_name = plugin_basename(__FILE__);
    if ($options['action'] == 'update' && $options['type'] == 'plugin') {
        if (isset($options['plugins'])) {
            foreach ((array) $options['plugins'] as $each_plugin) {
                if ($each_plugin == $current_plugin_path_name) {
                    fifu_activate_actions();
                }
            }
        }
    }
    if ($options['type'] == 'core') {
        fifu_db_change_url_length();
        fifu_db_fix_guid();
    }
}

add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'fifu_action_links');
add_filter('network_admin_plugin_action_links_' . plugin_basename(__FILE__), 'fifu_action_links');

function fifu_action_links($links) {
    $links[] = '<a href="' . esc_url(get_admin_url(null, 'admin.php?page=featured-image-from-url')) . '">' . __('Settings') . '</a>';
    // $links[] = '<a style="color:black">' . __('Support') . ':</a>';
    return $links;
}

add_filter('plugin_row_meta', 'fifu_row_meta', 10, 4);

function fifu_row_meta($plugin_meta, $plugin_file, $plugin_data, $status) {
    if (strpos($plugin_file, 'featured-image-from-url.php') !== false) {
        $tag_review = '<a title="If you are enjoying FIFU, please give it a 5-star rating =]" href="https://wordpress.org/support/plugin/featured-image-from-url/reviews/?filter=5" target="_blank"><span class="dashicons dashicons-star-filled"></span><span class="dashicons dashicons-star-filled"></span><span class="dashicons dashicons-star-filled"></span><span class="dashicons dashicons-star-filled"></span><span class="dashicons dashicons-star-filled"></span></a>';
        $tag_pro = '<a href="https://fifu.app/" target="_blank"><span style="padding:5px;color:white;background-color:#1da867">Upgrade to <b>PRO</b></span></a>';
        $email = '<a style="width:184px;padding:5px;color:white;background-color:#02a0d2"><b>Support</b>: marcel@fifu.app</a>';
        $ref = '<a href="https://referral.fifu.app" target="_blank">Affiliate program</a>';
        $new_links = array(
            'email' => $email,
            'pro' => $tag_pro,
            'review' => $tag_review,
            'affiliate' => $ref,
        );
        $plugin_meta = array_merge($plugin_meta, $new_links);
    }
    return $plugin_meta;
}

function fifu_uninstall() {
    // buddyboss app
    if (isset($_REQUEST['page']) && strpos($_REQUEST['page'], 'bbapp') !== false)
        return;

    $strings = fifu_get_strings_uninstall();

    wp_enqueue_script('jquery-block-ui', 'https://cdnjs.cloudflare.com/ajax/libs/jquery.blockUI/2.70/jquery.blockUI.min.js');
    wp_enqueue_style('fancy-box-css', 'https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.css');
    wp_enqueue_script('fancy-box-js', 'https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js');
    wp_enqueue_style('fifu-uninstall-css', plugins_url('includes/html/css/uninstall.css', __FILE__), array(), fifu_version_number());
    wp_enqueue_script('fifu-uninstall-js', plugins_url('includes/html/js/uninstall.js', __FILE__), array('jquery'), fifu_version_number());
    wp_localize_script('fifu-uninstall-js', 'fifuUninstallVars', [
        'restUrl' => esc_url_raw(rest_url()),
        'nonce' => wp_create_nonce('wp_rest'),
        'buttonTextClean' => $strings['button']['text']['clean'](),
        'buttonTextDeactivate' => $strings['button']['text']['deactivate'](),
        'buttonDescriptionClean' => $strings['button']['description']['clean'](),
        'buttonDescriptionDeactivate' => $strings['button']['description']['deactivate'](),
        'textWhy' => $strings['text']['why'](),
        'textOptional' => $strings['text']['optional'](),
        'textEmail' => $strings['text']['email'](),
        'textReasonConflict' => $strings['text']['reason']['conflict'](),
        'textReasonPro' => $strings['text']['reason']['pro'](),
        'textReasonSeo' => $strings['text']['reason']['seo'](),
        'textReasonLocal' => $strings['text']['reason']['local'](),
        'textReasonUndestand' => $strings['text']['reason']['undestand'](),
        'textReasonOthers' => $strings['text']['reason']['others'](),
    ]);
}

add_action('admin_footer', 'fifu_uninstall');

// https://github.com/woocommerce/woocommerce/wiki/High-Performance-Order-Storage-Upgrade-Recipe-Book
add_action('before_woocommerce_init', function () {
    if (class_exists('\Automattic\WooCommerce\Utilities\FeaturesUtil')) {
        \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility('custom_order_tables', __FILE__, true);
    }
});

