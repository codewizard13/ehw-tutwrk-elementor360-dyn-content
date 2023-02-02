<?php
$this->start_controls_section("post_section", [
    "label" => __("Item layout", "miga_custom_posts"),
    "tab" => \Elementor\Controls_Manager::TAB_CONTENT,
]);

$this->add_control("show_title", [
    "label" => esc_html__("Show title", "miga_custom_posts"),
    "type" => \Elementor\Controls_Manager::SWITCHER,
    "label_on" => esc_html__("Yes", "miga_custom_posts"),
    "label_off" => esc_html__("No", "miga_custom_posts"),
    "return_value" => "yes",
    "default" => "yes",
]);

$this->add_control("show_image", [
    "label" => esc_html__("Show image", "miga_custom_posts"),
    "type" => \Elementor\Controls_Manager::SWITCHER,
    "label_on" => esc_html__("Yes", "miga_custom_posts"),
    "label_off" => esc_html__("No", "miga_custom_posts"),
    "return_value" => "yes",
    "default" => "yes",
]);

$this->add_responsive_control("image_height", [
    "type" => \Elementor\Controls_Manager::SLIDER,
    "label" => esc_html__("Image height", "miga_custom_posts"),
    "range" => [
        "px" => [
            "min" => 0,
            "max" => 1000,
        ],
    ],
    "default" => [
        "unit" => "px",
        "size" => 300,
    ],
    "size_units" => ["px", "em", "%"],
    "devices" => ["desktop", "tablet", "mobile"],

    "selectors" => [
        "{{WRAPPER}} .miga_custom_posts_post_thumb" =>
            "height: {{SIZE}}{{UNIT}};",
    ],
    "condition" => [
        "show_image" => "yes",
    ],
]);

$this->add_responsive_control("image_width", [
    "type" => \Elementor\Controls_Manager::SLIDER,
    "label" => esc_html__("Image width", "miga_custom_posts"),
    "range" => [
        "px" => [
            "min" => 0,
            "max" => 1000,
        ],
    ],
    "default" => [
        "unit" => "%",
        "size" => 100,
    ],
    "size_units" => ["px", "em", "%"],
    "devices" => ["desktop", "tablet", "mobile"],

    "selectors" => [
        "{{WRAPPER}} .miga_custom_posts_row a" =>
            "max-width: {{SIZE}}{{UNIT}};",
    ],
    "condition" => [
        "show_image" => "yes",
    ],
]);

$this->add_control("show_date", [
    "label" => esc_html__("Show date", "miga_custom_posts"),
    "type" => \Elementor\Controls_Manager::SWITCHER,
    "label_on" => esc_html__("Yes", "miga_custom_posts"),
    "label_off" => esc_html__("No", "miga_custom_posts"),
    "return_value" => "yes",
    "default" => "yes",
]);

$this->add_control("show_excerpt", [
    "label" => esc_html__("Show excerpt", "miga_custom_posts"),
    "type" => \Elementor\Controls_Manager::SWITCHER,
    "label_on" => esc_html__("Yes", "miga_custom_posts"),
    "label_off" => esc_html__("No", "miga_custom_posts"),
    "return_value" => "yes",
    "default" => "yes",
]);

$this->add_control("show_tags", [
    "label" => esc_html__("Show taxonomies", "miga_custom_posts"),
    "type" => \Elementor\Controls_Manager::SWITCHER,
    "label_on" => esc_html__("Yes", "miga_custom_posts"),
    "label_off" => esc_html__("No", "miga_custom_posts"),
    "return_value" => "yes",
    "default" => "no",
]);

$this->add_control("show_next", [
    "label" => esc_html__("Show details button", "miga_custom_posts"),
    "type" => \Elementor\Controls_Manager::SWITCHER,
    "label_on" => esc_html__("Yes", "miga_custom_posts"),
    "label_off" => esc_html__("No", "miga_custom_posts"),
    "return_value" => "yes",
    "default" => "no",
]);

$this->add_control("flex_wrap", [
    "label" => esc_html__("Wrap content", "miga_custom_posts"),
    "type" => \Elementor\Controls_Manager::SWITCHER,
    "label_on" => esc_html__("Yes", "miga_custom_posts"),
    "label_off" => esc_html__("No", "miga_custom_posts"),
    "return_value" => "yes",
    "default" => "yes",
]);

$this->add_responsive_control("image_padding", [
    "type" => \Elementor\Controls_Manager::DIMENSIONS,
    "label" => esc_html__("Padding", "miga_custom_posts"),
    "size_units" => ["px", "em", "%"],
    "selectors" => [
        "{{WRAPPER}} .miga_custom_posts_row a" =>
            "padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};",
    ],
]);

$this->end_controls_section();
