<?php
/**
* Plugin Name
*
* @package           PluginPackage
* @author            Michael Gangolf
* @copyright         2022 Michael Gangolf
* @license           GPL-2.0-or-later
*
* @wordpress-plugin
* Plugin Name:       Custom post type templates for Elementor
* Description:       With the help of this plug-in you can link a custom post type to a normal Elementor page. In that Elementor page you can use the included elements (post title, post image, post content) to create a template.
* Version:           1.1.7
* Requires at least: 5.2
* Requires PHP:      7.2
* Author:            Michael Gangolf
* Author URI:        https://www.migaweb.de/
* License:           GPL v2 or later
* License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
* Text Domain:       miga_custom_posts
* Elementor tested up to: 3.10.1
*/

use Elementor\Plugin;

function change_post_type_template($single_template)
{
    $elementor_preview_active = \Elementor\Plugin::$instance->preview->is_preview_mode();
    if (stripos($single_template, "/single.php")>-1 && !$elementor_preview_active) {
        $single_template = plugin_dir_path(__FILE__) . 'templates/post.php';
    }
    return $single_template;
}

function miga_custom_posts_register_settings()
{
    add_option('miga_custom_posts', "");
    register_setting('miga_custom_posts_option_group', 'miga_custom_post_id', 'sanitize_values');
    register_setting('miga_custom_posts_option_group', 'miga_custom_post_type', 'sanitize_values');
    register_setting('miga_custom_posts_option_group', 'miga_custom_posts', 'sanitize_values');
}

function miga_enqueue_style()
{
    wp_register_style('miga_custom_posts_style', plugins_url('styles/main.css', __FILE__));
    wp_register_script('miga_custom_posts_script', plugins_url('scripts/main.js', __FILE__), array( 'wp-i18n' ), '', true);
    wp_enqueue_style('miga_custom_posts_style');
    wp_enqueue_script('miga_custom_posts_script');

    wp_localize_script('miga_custom_posts_script', 'objectL10n', array(
      'postText'  =>  __('The following post type', 'miga_custom_posts'),
      'pageText' =>__('should be renedered in this page template', 'miga_custom_posts'),
    ));
}

function miga_custom_posts_rewrite_rules()
{
    $posts = get_option('miga_custom_posts');
    if (!empty($posts) && is_array($posts)) {
        foreach ((array) $posts as $name => $element) {
            $postId = $element[0];
            $postName = $element[1];
            if ($postName == "post") {
                if (!is_admin()) {
                    add_filter('single_template', 'change_post_type_template');
                }
            }
        }
    }
}

function get_post_id_by_slug($slug, $posttype)
{
    $post = get_page_by_path($slug, OBJECT, $posttype);
    if ($post) {
        return $post->ID;
    } else {
        return null;
    }
}

add_filter('request', function ($query_vars) {
    if (is_admin()) {
        return $query_vars;
    }
    $elementor_preview_active = \Elementor\Plugin::$instance->preview->is_preview_mode();
    if (!$elementor_preview_active) {
        $posts = get_option('miga_custom_posts');
        if (!empty($posts) && is_array($posts)) {
            foreach ((array) $posts as $name => $element) {
                $postId = $element[0];
                $postName = $element[1];
                if (!empty($postId) && !empty($postName) && $postId == (int)$postId
                 && (isset($query_vars["post_type"]) && $query_vars["post_type"]==$postName)) {
                    $id = get_post_id_by_slug($query_vars["name"], $query_vars["post_type"]);
                    if (!empty($id) && stripos($_SERVER["REQUEST_URI"], "elementor-preview")===false) {
                        $query_vars["page"] = "";
                        $query_vars["post_type"] = "";
                        $query_vars["custompost"] = "";
                        $query_vars["name"] = "";
                        $query_vars["pid"] = $id;
                        $query_vars["ptype"] = $postName;
                        $query_vars["pagename"] = get_post_field('post_name', $postId);
                    }
                }
            }
        }
    }
    return $query_vars;
});

function miga_custom_posts_query_vars($vars)
{
    $vars[] = 'pid';
    $vars[] = 'ptype';
    return $vars;
}

function miga_custom_posts_addMenu()
{
    add_submenu_page(
        'elementor',
        __('Custom post pages', 'miga_custom_posts'),
        __('Custom post pages', 'miga_custom_posts'),
        'manage_options',
        'miga-custom-posts-detailpage',
        'miga_custom_posts_detailpage',
        100
    );
}

function miga_custom_posts_detailpage()
{
    echo '<form class="miga_custom_posts" method="post" action="options.php">';

    $posts = get_option('miga_custom_posts');

    printf("<h3>%s</h3>", __('Custom post pages:', 'miga_custom_posts'));
    printf("<p>%s</p>", __('Link custom post types to show up in normal Elementor pages. You can use the included elements to output the posts title, content or image.', 'miga_custom_posts'));
    printf("<h4>%s</h4>", __('How to use it', 'miga_custom_posts'));
    echo '<ol>';
    printf("<li>%s</li>", __('create a custom post type', 'miga_custom_posts'));
    printf("<li>%s</li>", __('create an Elementor page', 'miga_custom_posts'));
    printf("<li>%s</li>", __('use the included elements to output title, text or image', 'miga_custom_posts'));
    printf("<li>%s</li>", __('create a connection between the post type and the page below', 'miga_custom_posts'));
    printf("<li>%s</li>", __('save it and open the post detail page', 'miga_custom_posts'));
    echo '</ol>';

    printf("<h4>%s</h4>", __('Create the connections', 'miga_custom_posts'));
    echo '<div class="boxes">';
    $i=0;

    $custom_post_ids = get_posts([
      'fields'          => 'ids',
      'post_type' => 'elementor_library',
      'post_status' => 'publish',
      'numberposts' => -1,
      'elementor_library_type' => 'page',
      'order'    => 'ASC'
    ]);

    // get all pages
    $page_ids= get_all_page_ids();
    $page_ids = array_merge($page_ids,$custom_post_ids);
    // get all post types
    $args = array('public'=> true);
    $output = 'names';
    $operator = 'and';
    $post_types = get_post_types($args, $output, $operator);

    $emptyOptions1 = "";
    $emptyOptions2 = "";
    $runOnce = false;
    if (!empty($posts) && is_array($posts)) {
        foreach ($posts as $key => $post) {
            // pages
            $options = '';
            foreach ($page_ids as $id) {
                $sel = "";
                if ($id == $post[0]) {
                    $sel = 'selected="selected"';
                }
                $options .= '<option value="'.$id.'" '.$sel.'>'.get_the_title($id).'</option>';
                if (!$runOnce) {
                    $emptyOptions1 .= '<option value="'.$id.'">'.get_the_title($id).'</option>';
                }
            }

            // post types
            $options_pt = "";
            if ($post_types) {
                foreach ($post_types  as $post_type) {
                    $sel = "";
                    if ($post_type == $post[1]) {
                        $sel = 'selected="selected"';
                    }
                    $options_pt .= '<option value="'.$post_type.'" '.$sel.'>'.$post_type.'</option>';
                    if (!$runOnce) {
                        $emptyOptions2 .= '<option value="'.$post_type.'">'.$post_type.'</option>';
                    }
                }
            }

            echo '<div class="box" id="box_'.esc_attr($i).'">';
            printf("<p>%s</p>", __('The following post type', 'miga_custom_posts'));
            echo '<select id="miga_custom_post_type_'.esc_attr($i).'" name="miga_custom_posts['.esc_attr($i).'][1]">'.$options_pt.'</select>';
            printf("<p>%s</p>", __('should be renedered in this page template', 'miga_custom_posts'));
            echo '<select id="miga_custom_post_id_'.esc_attr($i).'" name="miga_custom_posts['.esc_attr($i).'][0]">'.$options.'</select>';
            echo '<button onclick="miga_custom_posts_remove('.esc_attr($i).')" class="remove"><i class="eicon-trash-o"></i>';
            echo '</button></div>';

            $i++;
            $runOnce = true;
        }
    } else {
        // pages
        foreach ($page_ids as $id) {
            $emptyOptions1 .= '<option value="'.$id.'">'.get_the_title($id).'</option>';
        }

        // post types
        if ($post_types) {
            foreach ($post_types  as $post_type) {
                $emptyOptions2 .= '<option value="'.$post_type.'">'.$post_type.'</option>';
            }
        }
    }
    echo '</div>';
    settings_fields('miga_custom_posts_option_group');
    echo '<button class="add" onclick="miga_custom_posts_addElement(); return false;"><i class="eicon-plus-square"></i>';
    _e("add", 'miga_custom_posts');
    echo '</button>';
    submit_button();

    echo '</form>';

    echo '<select id="selectPages" class="hidden">'.wp_kses($emptyOptions1, ["option"=>array("value"=>array())]).'</select>';
    echo '<select id="selectPosts" class="hidden">'.wp_kses($emptyOptions2, ["option"=>array("value"=>array())]).'</select>';
    flush_rewrite_rules();
}


add_action('init', static function () {
    if (! did_action('elementor/loaded')) {
        return false;
    }

    require_once(__DIR__ . '/includes/search.php');
    require_once(__DIR__ . '/widgets/PostTitle.php');
    require_once(__DIR__ . '/widgets/PostDate.php');
    require_once(__DIR__ . '/widgets/PostImage.php');
    require_once(__DIR__ . '/widgets/PostContent.php');
    require_once(__DIR__ . '/widgets/PostACF.php');
    require_once(__DIR__ . '/widgets/PostsList.php');
    \Elementor\Plugin::instance()->widgets_manager->register(new \Elementor_Widget_miga_post_title());
    \Elementor\Plugin::instance()->widgets_manager->register(new \Elementor_Widget_miga_post_image());
    \Elementor\Plugin::instance()->widgets_manager->register(new \Elementor_Widget_miga_post_content());
    \Elementor\Plugin::instance()->widgets_manager->register(new \Elementor_Widget_miga_post_acf());
    \Elementor\Plugin::instance()->widgets_manager->register(new \Elementor_Widget_miga_posts());
    \Elementor\Plugin::instance()->widgets_manager->register(new \Elementor_Widget_miga_post_date());
});


function miga_custom_posts_add_category($elements_manager)
{
    $elements_manager->add_category(
        'miga_custom_posts',
        [
          'title' => __('Custom post page', 'miga_custom_posts'),
          'icon' => 'fa fa-plug',
        ]
    );
}

function sanitize_values($input)
{
    $new_input = array();
    foreach ((array) $input as $name => $element) {
        foreach ($element as $index => $value) {
            if (! empty($value)) {
                $new_input[ $name ][ $index ] = esc_attr($value);
            }
        }
    }
    return $new_input;
}



function ctmfe_scripts()
{
    wp_register_style('ctmfe_styles', plugins_url('styles/frontend.css', __FILE__));
    wp_enqueue_style('ctmfe_styles');
}


add_action('wp_enqueue_scripts', 'ctmfe_scripts');
add_action('admin_init', 'miga_custom_posts_register_settings');
add_action('init', 'miga_custom_posts_rewrite_rules');
add_action('admin_menu', 'miga_custom_posts_addMenu', 999);
add_action('admin_enqueue_scripts', 'miga_enqueue_style');
add_action('elementor/elements/categories_registered', 'miga_custom_posts_add_category');
add_filter('query_vars', 'miga_custom_posts_query_vars');
// ajax search
add_action('wp_ajax_miga_custom_post_filter', 'miga_custom_posts_ajax_functions');
add_action('wp_ajax_nopriv_miga_custom_post_filter', 'miga_custom_posts_ajax_functions');


function custom_title($title_parts)
{
    if (
        !empty(get_query_var("ptype")) &&
        !empty(get_query_var("pid"))
    ) {
        $args = [
            "post_type" => get_query_var("ptype"),
            "name" => get_query_var("pid"),
        ];
        $query = get_posts($args);
        if (sizeOf($query) > 0) {
            $id = $query[0]->ID;
            $title_parts['title'] = esc_attr(get_the_title($id));
        }
    }
    return $title_parts;
}
add_filter('document_title_parts', 'custom_title');
