<?php

function fifu_get_attribute($attribute, $html) {
    $attribute = $attribute . '=';
    if (strpos($html, $attribute) === false)
        return null;

    $aux = explode($attribute, $html);
    if ($aux)
        $aux = $aux[1];

    $quote = $aux[0];

    if ($quote == '&') {
        preg_match('/^&[^;]+;/', $aux, $matches);
        if ($matches)
            $quote = $matches[0];
    }

    $aux = explode($quote, $aux);
    if ($aux)
        return $aux[1];

    return null;
}

function fifu_is_on($option) {
    return get_option($option) == 'toggleon';
}

function fifu_is_off($option) {
    return get_option($option) == 'toggleoff';
}

function fifu_get_post_types() {
    $arr = array();
    foreach (get_post_types() as $post_type) {
        if (post_type_supports($post_type, 'thumbnail'))
            array_push($arr, $post_type);
    }
    if (fifu_is_bbpress_active())
        array_push($arr, 'forum', 'topic', 'reply');
    return $arr;
}

function fifu_get_post_types_str() {
    $str = '';
    $i = 0;
    foreach (fifu_get_post_types() as $type)
        $str = ($i++ == 0) ? $type : $str . ', ' . $type;
    return $str;
}

function fifu_has_local_featured_image($post_id) {
    $att_id = get_post_thumbnail_id($post_id);
    if (!$att_id)
        return false;

    $att_post = get_post($att_id);
    if (!$att_post)
        return false;

    return $att_post->post_author != FIFU_AUTHOR;
}

function fifu_get_delimiter($property, $html) {
    $delimiter = explode($property . '=', $html);
    return $delimiter ? substr($delimiter[1], 0, 1) : null;
}

function fifu_is_ajax_call() {
    return (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') || wp_doing_ajax();
}

function fifu_normalize($tag) {
    $tag = str_replace('amp;', '', $tag);
    $tag = str_replace('#038;', '', $tag);
    return $tag;
}

function fifu_starts_with($text, $substr) {
    return substr($text, 0, strlen($substr)) === $substr;
}

function fifu_ends_with($text, $substr) {
    return substr($text, -strlen($substr)) === $substr;
}

function fifu_get_tags($post_id) {
    $tags = get_the_tags($post_id);
    if (!$tags)
        return null;

    $names = null;
    foreach ($tags as $tag)
        $names .= $tag->name . ' ';
    return $names ? rtrim($names) : null;
}

function fifu_get_home_url() {
    return explode('//', get_home_url())[1];
}

function fifu_dashboard() {
    return !is_home() &&
            !is_singular('post') &&
            !is_author() &&
            !is_search() &&
            !is_singular('page') &&
            !is_singular('product') &&
            !is_archive() &&
            (!class_exists('WooCommerce') || (class_exists('WooCommerce') && (!is_shop() && !is_product_category() && !is_cart())));
}

function fifu_get_default_cpt_arr() {
    $cpts = get_option('fifu_default_cpt');
    if (!$cpts)
        return null;
    return explode(',', str_replace(' ', '', $cpts));
}

function fifu_is_valid_default_cpt($post_id) {
    $cpts = fifu_get_default_cpt_arr();
    if (!$cpts)
        return false;
    $type = get_post_type($post_id);
    return in_array($type, $cpts);
}

function fifu_get_placeholder($width, $height) {
    $text = '...';
    return "https://images.placeholders.dev/?width={$width}&height={$height}&text={$text}";
}

function fifu_is_portrait($width, $height) {
    return $height > $width;
}

function fifu_is_landscape($width, $height) {
    return $width >= $height;
}

function fifu_is_amp_request() {
    return function_exists('amp_is_request') && amp_is_request();
}

function fifu_is_valid_cpt($post_id) {
    $types = get_option('fifu_html_cpt');
    if (!$types)
        return true;

    $types = explode(',', $types);
    $type = get_post_type($post_id);

    foreach ($types as $t) {
        if ($t == $type)
            return true;
    }
    return false;
}

// developers

function fifu_dev_set_image($post_id, $image_url) {
    try {
        fifu_update_or_delete($post_id, 'fifu_image_url', esc_url_raw(rtrim($image_url)));
        fifu_update_fake_attach_id($post_id);
        return true;
    } catch (Exception $e) {
        error_log($e->getMessage());
        return false;
    }
}

// active plugins

function fifu_is_elementor_active() {
    return is_plugin_active('elementor/elementor.php') || is_plugin_active('elementor-pro/elementor-pro.php');
}

function fifu_is_elementor_editor() {
    if (!fifu_is_elementor_active())
        return false;
    return \Elementor\Plugin::$instance->editor->is_edit_mode() || \Elementor\Plugin::$instance->preview->is_preview_mode();
}

function fifu_is_bbpress_active() {
    return is_plugin_active('bbpress/bbpress.php');
}

function fifu_is_amp_active() {
    return is_plugin_active('amp/amp.php');
}

function fifu_is_ol_scrapes_active() {
    return is_plugin_active('ol_scrapes/ol_scrapes.php');
}

function fifu_is_rank_math_seo_active() {
    return is_plugin_active('seo-by-rank-math/rank-math.php');
}

function fifu_is_gravity_forms_active() {
    return is_plugin_active('gravityforms/gravityforms.php');
}

function fifu_is_multisite_global_media_active() {
    return class_exists('\MultisiteGlobalMedia\Plugin');
}

// active themes

function fifu_is_flatsome_active() {
    return 'flatsome' == get_option('template');
}

function fifu_is_avada_active() {
    return 'avada' == strtolower(get_option('template'));
}

// plugin: accelerated-mobile-pages

function fifu_amp_url($url, $width, $height) {
    return array(0 => $url, 1 => $width, 2 => $height);
}

// plugin: web-stories

function fifu_is_web_story() {
    if (function_exists('get_current_screen')) {
        $screen = get_current_screen();
        $is_web_story = isset($screen->post_type) && strpos($screen->post_type, 'web-story') !== false;
        if ($is_web_story)
            return true;
    }
    if (isset($_REQUEST['_web_stories_envelope']))
        return true;

    return false;
}

// plugin: filter-search-pro

function fifu_is_search_filter_pro() {
    if (function_exists('get_current_screen')) {
        $screen = get_current_screen();
        return (isset($screen->post_type) && strpos($screen->post_type, 'search-filter') !== false);
    }
    return false;
}

