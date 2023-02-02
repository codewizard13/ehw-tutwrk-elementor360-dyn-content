<?php

class Elementor_Widget_miga_post_title extends \Elementor\Widget_Base
{
    public function get_name()
    {
        return "miga_custom_posts_title";
    }

    public function get_title()
    {
        return __("Post title", "miga_custom_posts");
    }

    public function get_icon()
    {
        return "eicon-animated-headline";
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

        $this->add_control("miga_custom_posts_title_tag", [
            "label" => __("HTML Tag", "miga_custom_posts"),
            "type" => \Elementor\Controls_Manager::SELECT,
            "default" => "h1",
            "options" => [
                "h1" => __("H1", "miga_custom_posts"),
                "h2" => __("H2", "miga_custom_posts"),
                "h3" => __("H3", "miga_custom_posts"),
                "h4" => __("H4", "miga_custom_posts"),
                "h5" => __("H5", "miga_custom_posts"),
                "p" => __("p", "miga_custom_posts"),
            ],
        ]);

        $this->add_control("miga_custom_posts_title_align", [
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
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                "name" => "content_typography",
                "label" => __("Typography", "miga_custom_posts"),
                "selector" => "{{WRAPPER}} .miga_custom_posts_title",
            ]
        );

        $this->add_control("title_color", [
            "label" => __("Title Color", "miga_custom_posts"),
            "type" => \Elementor\Controls_Manager::COLOR,
            "scheme" => [
                "type" => \Elementor\Core\Schemes\Color::get_type(),
                "value" => \Elementor\Core\Schemes\Color::COLOR_1,
            ],
            "selectors" => [
                "{{WRAPPER}} .miga_custom_posts_title" => "color: {{VALUE}}",
            ],
        ]);

        $this->add_group_control(\Elementor\Group_Control_Border::get_type(), [
            "name" => "border",
            "label" => esc_html__("Border", "miga_custom_posts"),
            "selector" => "{{WRAPPER}} .miga_custom_posts_title",
        ]);

        $this->add_group_control(
            \Elementor\Group_Control_Text_Shadow::get_type(),
            [
                "name" => "text_shadow",
                "label" => esc_html__("Text Shadow", "miga_custom_posts"),
                "selector" => "{{WRAPPER}} .miga_custom_posts_title",
            ]
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        $isEditor = \Elementor\Plugin::$instance->editor->is_edit_mode();
        $settings = $this->get_settings_for_display();

        if ($isEditor) {
            echo "<" .
                esc_attr($settings["miga_custom_posts_title_tag"]) .
                ' class="miga_custom_posts_title" style="text-align: ' .
                esc_attr($settings["miga_custom_posts_title_align"]) .
                ';">Post Headline</' .
                esc_attr($settings["miga_custom_posts_title_tag"]) .
                ">";
        } else {
            if (
                !empty(get_query_var("ptype")) &&
                !empty(get_query_var("pid"))
            ) {
                $id = get_query_var("pid");
                echo "<" .
                    esc_attr($settings["miga_custom_posts_title_tag"]) .
                    ' class="miga_custom_posts_title" style="text-align: ' .
                    esc_attr($settings["miga_custom_posts_title_align"]) .
                    ';">' .
                    esc_attr(get_the_title($id)) .
                    "</" .
                    esc_attr($settings["miga_custom_posts_title_tag"]) .
                    ">";
            } else {
                echo "<" .
                    esc_attr($settings["miga_custom_posts_title_tag"]) .
                    ' class="miga_custom_posts_title" style="text-align: ' .
                    esc_attr($settings["miga_custom_posts_title_align"]) .
                    ';">' .
                    esc_attr(get_the_title()) .
                    "</" .
                    esc_attr($settings["miga_custom_posts_title_tag"]) .
                    ">";
            }
        }
    }
}
