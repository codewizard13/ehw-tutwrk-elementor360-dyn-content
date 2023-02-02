<?php

if (!defined("ABSPATH")) {
    exit();
}

$posts = get_option("miga_custom_posts");

$postId = "";
if (!empty($posts) && is_array($posts)) {
    foreach ((array) $posts as $name => $element) {
        $postName = $element[1];
        if ($postName == "post") {
            $postId = $element[0];
        }
    }
}

if ($postId != "") {
    \Elementor\Plugin::$instance->frontend->add_body_class(
        "ast-page-builder-template"
    );
    get_header();
    echo Elementor\Plugin::instance()->frontend->get_builder_content_for_display(
        $postId
    );
    get_footer();
}
