<?php

$this->start_controls_section("details_section", [
    "label" => __("Show details", "miga_custom_posts"),
    "tab" => \Elementor\Controls_Manager::TAB_STYLE,
]);

$this->add_control("show_next_text", [
    "label" => esc_html__("Next button text", "miga_custom_posts"),
    "type" => \Elementor\Controls_Manager::TEXT,
    "default" => esc_html__("Show details", "miga_custom_posts"),
    "placeholder" => esc_html__(
        "Type your post type here",
        "miga_custom_posts"
    ),
]);

addFontUI([
    "this" => $this,
    "id" => "next",
    "class" => ".miga_custom_posts_next",
]);

$this->add_responsive_control("show_next_alignment", [
    "type" => \Elementor\Controls_Manager::CHOOSE,
    "label" => esc_html__("Alignment", "miga_custom_posts"),
    "options" => [
        "miga_pos_left" => [
            "title" => esc_html__("Left", "miga_custom_posts"),
            "icon" => "eicon-text-align-left",
        ],
        "miga_pos_center" => [
            "title" => esc_html__("Center", "miga_custom_posts"),
            "icon" => "eicon-text-align-center",
        ],
        "miga_pos_right" => [
            "title" => esc_html__("Right", "miga_custom_posts"),
            "icon" => "eicon-text-align-right",
        ],
    ],
    "devices" => ["desktop", "tablet", "mobile"],
]);

$this->add_control("show_next_margin", [
    "label" => esc_html__("Margin to border (left/right)", "miga_custom_posts"),
    "type" => \Elementor\Controls_Manager::NUMBER,
    "min" => 0,
    "max" => 100,
    "step" => 1,
    "default" => 0,
]);
$this->add_control("show_next_margin_bottom", [
    "label" => esc_html__("Margin to bottom", "miga_custom_posts"),
    "type" => \Elementor\Controls_Manager::NUMBER,
    "min" => 0,
    "max" => 100,
    "step" => 1,
    "default" => 0,
]);

$this->end_controls_section();
