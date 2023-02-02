<?php

$this->start_controls_section("style_error_section", [
    "label" => __("Error message", "miga_custom_posts"),
    "tab" => \Elementor\Controls_Manager::TAB_STYLE,
]);

$this->add_control("error_margin", [
    "label" => esc_html__("Margin error container", "miga_custom_posts"),
    "type" => \Elementor\Controls_Manager::DIMENSIONS,
    "size_units" => ["px", "%", "em"],
    "selectors" => [
        "{{WRAPPER}} .miga_custom_posts_error" =>
            "margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};",
    ],
]);

$this->add_control("hr_error1", [
    "type" => \Elementor\Controls_Manager::DIVIDER,
]);

$this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [
    "name" => "error_font",
    "selector" => "{{WRAPPER}} .miga_custom_posts_error",
]);

$this->add_control("error_color", [
    "label" => esc_html__("Text color", "miga_custom_posts"),
    "type" => \Elementor\Controls_Manager::COLOR,
    "selectors" => [
        "{{WRAPPER}} .miga_custom_posts_error" => "color: {{VALUE}}",
    ],
]);

$this->add_control("hr_error2", [
    "type" => \Elementor\Controls_Manager::DIVIDER,
]);

$this->add_group_control(\Elementor\Group_Control_Background::get_type(), [
    "name" => "error_background",
    "label" => esc_html__("Background", "miga_custom_posts"),
    "types" => ["classic", "gradient", "video"],
    "selector" => "{{WRAPPER}} .miga_custom_posts_error",
]);

$this->add_control("hr_error3", [
    "type" => \Elementor\Controls_Manager::DIVIDER,
]);

$this->add_group_control(\Elementor\Group_Control_Border::get_type(), [
    "name" => "error_border",
    "label" => esc_html__("Border", "miga_custom_posts"),
    "selector" => "{{WRAPPER}} .miga_custom_posts_error",
]);

$this->add_control("error_radius", [
    "label" => esc_html__("Border radius", "miga_custom_posts"),
    "type" => \Elementor\Controls_Manager::DIMENSIONS,
    "size_units" => ["px", "%", "em"],
    "selectors" => [
        "{{WRAPPER}} .miga_custom_posts_error" =>
            "border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};",
    ],
]);

$this->add_responsive_control("error_alignment", [
    "type" => \Elementor\Controls_Manager::CHOOSE,
    "label" => esc_html__("Alignment", "miga_custom_posts"),
    "options" => [
        "left" => [
            "title" => esc_html__("Left", "miga_custom_posts"),
            "icon" => "eicon-text-align-left",
        ],
        "center" => [
            "title" => esc_html__("Center", "miga_custom_posts"),
            "icon" => "eicon-text-align-center",
        ],
        "right" => [
            "title" => esc_html__("Right", "miga_custom_posts"),
            "icon" => "eicon-text-align-right",
        ],
    ],
    "devices" => ["desktop", "tablet", "mobile"],
    "prefix_class" => "content-align-%s",
]);
$this->end_controls_section();
