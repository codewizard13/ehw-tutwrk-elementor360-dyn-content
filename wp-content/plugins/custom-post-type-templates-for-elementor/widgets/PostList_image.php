<?php

$this->start_controls_section("style_img_section", [
    "label" => __("Image", "miga_custom_posts"),
    "tab" => \Elementor\Controls_Manager::TAB_STYLE,
]);

$this->add_control("img_margin", [
    "label" => esc_html__("Margin tags container", "miga_custom_posts"),
    "type" => \Elementor\Controls_Manager::DIMENSIONS,
    "size_units" => ["px", "%", "em"],
    "selectors" => [
        "{{WRAPPER}} .miga_custom_posts_post_thumb" =>
            "margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};",
    ],
]);

$this->add_control("hr_img1", ["type" => \Elementor\Controls_Manager::DIVIDER]);

$this->add_control("img_radius", [
    "label" => esc_html__("Border radius", "miga_custom_posts"),
    "type" => \Elementor\Controls_Manager::DIMENSIONS,
    "size_units" => ["px", "%", "em"],
    "selectors" => [
        "{{WRAPPER}} .miga_custom_posts_post_thumb" =>
            "border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};",
    ],
]);

$this->add_control("img_scale", [
    "label" => esc_html__("Scale on hover", "miga_custom_posts"),
    "type" => \Elementor\Controls_Manager::SWITCHER,
    "label_on" => esc_html__("Show", "miga_custom_posts"),
    "label_off" => esc_html__("Hide", "miga_custom_posts"),
    "return_value" => "yes",
    "default" => "yes",
]);

$this->end_controls_section();
