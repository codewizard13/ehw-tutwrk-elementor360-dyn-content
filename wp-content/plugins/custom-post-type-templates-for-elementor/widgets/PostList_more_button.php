<?php
$this->start_controls_section("btn_more_section", [
    "label" => __("More button", "miga_custom_posts"),
    "tab" => \Elementor\Controls_Manager::TAB_STYLE,
]);

$this->add_control("btn_more_title", [
    "label" => esc_html__("Title", "miga_custom_posts"),
    "type" => \Elementor\Controls_Manager::TEXT,
    "default" => esc_html__("Load more", "miga_custom_posts"),
    "placeholder" => esc_html__("Type your title here", "miga_custom_posts"),
]);
$this->add_control("hr_more1", [
    "type" => \Elementor\Controls_Manager::DIVIDER,
]);

addFontUI([
    "this" => $this,
    "id" => "more",
    "class" => ".miga_custom_post_button_more",
]);

$this->add_control("btnmore_radius", [
    "label" => esc_html__("Border radius", "miga_custom_posts"),
    "type" => \Elementor\Controls_Manager::DIMENSIONS,
    "size_units" => ["px", "%", "em"],
    "selectors" => [
        "{{WRAPPER}} .miga_custom_post_button_more" =>
            "border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};",
    ],
]);

$this->add_control("hr_more4", [
    "type" => \Elementor\Controls_Manager::DIVIDER,
]);

$this->add_control("btnmore_margin", [
    "label" => esc_html__("Margin", "miga_custom_posts"),
    "type" => \Elementor\Controls_Manager::DIMENSIONS,
    "size_units" => ["px", "%", "em"],
    "selectors" => [
        "{{WRAPPER}} .miga_custom_post_button_more" =>
            "margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};",
    ],
]);

$this->add_responsive_control("btnmore_alignment", [
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
    "devices" => ["desktop", "tablet"],
    "prefix_class" => "content-align-%s",
]);

$this->end_controls_section();

?>
