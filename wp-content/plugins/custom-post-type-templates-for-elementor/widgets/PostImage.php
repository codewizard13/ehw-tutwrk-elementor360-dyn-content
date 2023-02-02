<?php

class Elementor_Widget_miga_post_image extends \Elementor\Widget_Base
{
    public function get_name()
    {
        return "miga_custom_posts_image";
    }

    public function get_title()
    {
        return __("Post image", "miga_custom_posts");
    }

    public function get_icon()
    {
        return "eicon-image";
    }

    public function get_categories()
    {
        return ["miga_custom_posts"];
    }

    protected function _register_controls()
    {
        $this->start_controls_section("content_section", [
            "label" => __("Settings", "miga_custom_posts"),
            "tab" => \Elementor\Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control("miga_custom_posts_image_align", [
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
    }

    protected function render()
    {
        $isEditor = \Elementor\Plugin::$instance->editor->is_edit_mode();
        $settings = $this->get_settings_for_display();

        echo '<figure style="text-align: ' .
            esc_attr($settings["miga_custom_posts_image_align"]) .
            ';">';
        if ($isEditor) {
            echo '<img src="' .
                plugins_url("../images/placeholder.png", __FILE__) .
                '"/>';
        } else {
            if (
                !empty(get_query_var("ptype")) &&
                !empty(get_query_var("pid"))
            ) {
                echo wp_kses_post(get_the_post_thumbnail(get_query_var("pid")));
            } else {
                echo wp_kses_post(get_the_post_thumbnail());
            }
        }

        echo "</figure>";
    }
}
