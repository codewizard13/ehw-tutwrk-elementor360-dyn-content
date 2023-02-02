<?php
$this->start_controls_section("filter_section", [
    "label" => __("Filter", "miga_custom_posts"),
    "tab" => \Elementor\Controls_Manager::TAB_CONTENT,
]);
$this->add_control("show_filter", [
    "label" => esc_html__("Show filter", "miga_custom_posts"),
    "type" => \Elementor\Controls_Manager::SWITCHER,
    "label_on" => esc_html__("Show", "miga_custom_posts"),
    "label_off" => esc_html__("Hide", "miga_custom_posts"),
    "return_value" => "yes",
    "default" => "no",
]);

$taxonomies = get_taxonomies();
$this->add_control("filter_groups", [
    "label" => esc_html__("Show Elements", "miga_custom_posts"),
    "type" => \Elementor\Controls_Manager::SELECT2,
    "multiple" => true,
    "options" => $taxonomies,
    "default" => ["title", "description"],
    "condition" => [
        "show_filter" => "yes",
    ],
]);

$this->end_controls_section();

$this->start_controls_section("style_filter_section", [
    "label" => __("Filter", "miga_custom_posts"),
    "tab" => \Elementor\Controls_Manager::TAB_STYLE,
]);

$this->add_control("filter_margin", [
    "label" => esc_html__("Margin", "miga_custom_posts"),
    "type" => \Elementor\Controls_Manager::DIMENSIONS,
    "size_units" => ["px", "%", "em"],
    "selectors" => [
        "{{WRAPPER}} .miga_custom_posts_filter" =>
            "margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};",
    ],
]);

$this->end_controls_section();
