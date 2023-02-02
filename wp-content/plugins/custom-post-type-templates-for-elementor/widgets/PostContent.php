<?php

class Elementor_Widget_miga_post_content extends \Elementor\Widget_Base
{
    public function get_name()
    {
        return "miga_custom_posts_content";
    }

    public function get_title()
    {
        return __("Post content", "miga_custom_posts");
    }

    public function get_icon()
    {
        return "eicon-post-content";
    }

    public function get_categories()
    {
        return ["miga_custom_posts"];
    }

    protected function _register_controls()
    {
        $this->start_controls_section("style_section", [
            "label" => __("Style", "miga_custom_posts"),
            "tab" => \Elementor\Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control("title_color", [
            "label" => esc_html__("Text color", "miga_custom_posts"),
            "type" => \Elementor\Controls_Manager::COLOR,
            "selectors" => [
                "{{WRAPPER}} .miga_custom_posts_content" => "color: {{VALUE}}",
            ],
        ]);

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                "name" => "content_typography",
                "selector" => "{{WRAPPER}} .miga_custom_posts_content",
            ]
        );
        $this->add_group_control(\Elementor\Group_Control_Border::get_type(), [
            "name" => "border",
            "label" => esc_html__("Border", "miga_custom_posts"),
            "selector" => "{{WRAPPER}} .miga_custom_posts_content",
        ]);

        $this->add_group_control(
            \Elementor\Group_Control_Text_Shadow::get_type(),
            [
                "name" => "text_shadow",
                "label" => esc_html__("Text Shadow", "miga_custom_posts"),
                "selector" => "{{WRAPPER}} .miga_custom_posts_content",
            ]
        );
        $this->end_controls_section();
    }

    protected function render()
    {
        $isEditor = \Elementor\Plugin::$instance->editor->is_edit_mode();
        $settings = $this->get_settings_for_display();

        echo '<div class="miga_custom_posts_content">';
        if ($isEditor) {
            echo "Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. Nullam dictum felis eu pede mollis pretium. Integer tincidunt";
        } else {
            if (
                !empty(get_query_var("ptype")) &&
                !empty(get_query_var("pid"))
            ) {
                $args = [
                    "post_type" => get_query_var("ptype"),
                    "id" => get_query_var("pid"),
                ];
                $query = get_post(get_query_var("pid"));
                $elementor_page = get_post_meta(get_query_var("pid"), '_elementor_edit_mode', true);
                if ($elementor_page) {
                    // elementor page
                    echo Elementor\Plugin::instance()->frontend->get_builder_content(get_query_var("pid"));
                } else {
                    // normale page
                    echo wp_kses_post($query->post_content);
                }
            } else {
                $content_post = get_post();
                $id = $content_post->ID;
                if (!empty($id)) {
                    $pluginElementor = \Elementor\Plugin::instance();
                    if (empty($pluginElementor->frontend)) {
                        $content = $pluginElementor->frontend->get_builder_content(
                            $id
                        );
                        if (empty($content)) {
                            echo $content_post->post_content;
                        } else {
                            echo $content;
                        }
                    } else {
                        echo the_content();
                    }
                }
            }
        }
        echo "</div>";
    }
}
