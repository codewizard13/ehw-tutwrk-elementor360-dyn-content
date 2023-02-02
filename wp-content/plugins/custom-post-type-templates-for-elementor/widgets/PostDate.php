<?php

class Elementor_Widget_miga_post_date extends \Elementor\Widget_Base
{
    public function get_name()
    {
        return "miga_custom_posts_date";
    }

    public function get_title()
    {
        return __("Post date", "miga_custom_posts");
    }

    public function get_icon()
    {
        return "eicon-date";
    }

    public function get_categories()
    {
        return ["miga_custom_posts"];
    }

    protected function _register_controls()
    {
        require_once "helper.php";
        $this->start_controls_section("content_section", [
            "label" => __("Settings", "miga_custom_posts"),
            "tab" => \Elementor\Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control("miga_custom_posts_date_align", [
            "label" => __("Alignment", "miga_custom_posts"),
            "type" => \Elementor\Controls_Manager::CHOOSE,
            "options" => [
                "left" => [
                    "title" => __("Left", "miga_custom_posts"),
                    "icon" => "eicon-text-align-left",
                ],
                "center" => [
                    "title" => __("Center", "miga_custom_posts"),
                    "icon" => "eicon-text-align-center",
                ],
                "right" => [
                    "title" => __("Right", "miga_custom_posts"),
                    "icon" => "eicon-text-align-right",
                ],
            ],
            "default" => "left",
            "toggle" => true,
        ]);

        $this->end_controls_section();

        $this->start_controls_section("style_section", [
            "label" => __("Style", "miga_custom_posts"),
            "tab" => \Elementor\Controls_Manager::TAB_STYLE,
        ]);

        addFontUI([
            "this" => $this,
            "id" => "date",
            "class" => ".miga_custom_posts_date",
            "hover" => false,
        ]);

        $this->add_group_control(
            \Elementor\Group_Control_Text_Shadow::get_type(),
            [
                "name" => "text_shadow",
                "label" => esc_html__("Text Shadow", "miga_custom_posts"),
                "selector" => "{{WRAPPER}} .miga_custom_posts_date",
            ]
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        $isEditor = \Elementor\Plugin::$instance->editor->is_edit_mode();
        $settings = $this->get_settings_for_display();
        if ($isEditor) {
            echo '<div class="miga_custom_posts_date" style="text-align:' .
                esc_attr($settings["miga_custom_posts_date_align"]) .
                '">Post Date</div>';
        } else {
            if (
                !empty(get_query_var("ptype")) &&
                !empty(get_query_var("pid"))
            ) {
                echo '<div class="miga_custom_posts_date" style="text-align:' .
                    esc_attr($settings["miga_custom_posts_date_align"]) .
                    '">' .
                    esc_attr(get_the_date("", get_query_var("pid"))) .
                    "</div>";
            } else {
                echo '<div class="miga_custom_posts_date" style="text-align:' .
                    esc_attr($settings["miga_custom_posts_date_align"]) .
                    '">' .
                    esc_attr(get_the_date()) .
                    "</div>";
            }
        }
    }
}
