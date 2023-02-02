<?php
$this->start_controls_section("style_content_section", [
    "label" => __("Content container", "miga_custom_posts"),
    "tab" => \Elementor\Controls_Manager::TAB_STYLE,
]);

$this->add_responsive_control("content_padding", [
    "type" => \Elementor\Controls_Manager::DIMENSIONS,
    "label" => esc_html__("Padding", "miga_custom_posts"),
    "size_units" => ["px", "em", "%"],
    "selectors" => [
        "{{WRAPPER}} .miga_custom_posts_post_content" =>
            "padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};",
    ],
]);

$this->add_control("content_full_height", [
    "label" => esc_html__("Full height", "miga_custom_posts"),
    "type" => \Elementor\Controls_Manager::SWITCHER,
    "label_on" => esc_html__("Show", "miga_custom_posts"),
    "label_off" => esc_html__("Hide", "miga_custom_posts"),
    "return_value" => "yes",
    "default" => "yes",
]);

$this->add_group_control(\Elementor\Group_Control_Background::get_type(), [
    "name" => "content_background",
    "label" => esc_html__("Background", "miga_custom_posts"),
    "types" => ["classic", "gradient", "video"],
    "selector" => "{{WRAPPER}} .miga_custom_posts_post_content",
]);

$this->end_controls_section();

$this->start_controls_section("style_section", [
    "label" => __("Title", "miga_custom_posts"),
    "tab" => \Elementor\Controls_Manager::TAB_STYLE,
]);

addFontUI([
    "this" => $this,
    "id" => "title",
    "class" => ".miga_custom_posts_post_title",
]);

$this->add_control("title_margin", [
    "label" => esc_html__("Margin", "miga_custom_posts"),
    "type" => \Elementor\Controls_Manager::DIMENSIONS,
    "size_units" => ["px", "%", "em"],
    "selectors" => [
        "{{WRAPPER}} .miga_custom_posts_post_title" =>
            "margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};",
    ],
]);

$this->end_controls_section();

$this->start_controls_section("style_section2", [
    "label" => __("Date", "miga_custom_posts"),
    "tab" => \Elementor\Controls_Manager::TAB_STYLE,
]);

addFontUI([
    "this" => $this,
    "id" => "_post_date",
    "class" => ".miga_custom_posts_post_date",
]);

$this->end_controls_section();

$this->start_controls_section("style_section3", [
    "label" => __("Excerpt", "miga_custom_posts"),
    "tab" => \Elementor\Controls_Manager::TAB_STYLE,
]);

addFontUI([
    "this" => $this,
    "id" => "_excerpt",
    "class" => ".miga_custom_posts_post_excerpt",
]);

$this->end_controls_section();
