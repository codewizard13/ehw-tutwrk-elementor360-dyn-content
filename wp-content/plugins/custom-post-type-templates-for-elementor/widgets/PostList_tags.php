<?php

$this->start_controls_section("style_tags_section", [
    "label" => __("Tags", "miga_custom_posts"),
    "tab" => \Elementor\Controls_Manager::TAB_STYLE,
]);

$this->add_control("tags_margin", [
    "label" => esc_html__("Margin tags container", "miga_custom_posts"),
    "type" => \Elementor\Controls_Manager::DIMENSIONS,
    "size_units" => ["px", "%", "em"],
    "selectors" => [
        "{{WRAPPER}} .miga_custom_posts_post_tags" =>
            "margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};",
    ],
]);
$this->add_control("tag_margin", [
    "label" => esc_html__("Margin single tag", "miga_custom_posts"),
    "type" => \Elementor\Controls_Manager::DIMENSIONS,
    "size_units" => ["px", "%", "em"],
    "selectors" => [
        "{{WRAPPER}} .miga_custom_posts_post_tag" =>
            "margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};",
    ],
]);

addFontUI([
    "this" => $this,
    "id" => "tags",
    "class" => ".miga_custom_posts_post_tag",
]);

$this->add_control("tag_radius", [
    "label" => esc_html__("Border radius", "miga_custom_posts"),
    "type" => \Elementor\Controls_Manager::DIMENSIONS,
    "size_units" => ["px", "%", "em"],
    "selectors" => [
        "{{WRAPPER}} .miga_custom_posts_post_tag" =>
            "border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};",
    ],
]);

$this->end_controls_section();
