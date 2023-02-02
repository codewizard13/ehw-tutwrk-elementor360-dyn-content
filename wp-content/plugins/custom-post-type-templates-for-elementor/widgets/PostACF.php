<?php

class Elementor_Widget_miga_post_acf extends \Elementor\Widget_Base
{
    public function get_name()
    {
        return "miga_custom_posts_acf";
    }

    public function get_title()
    {
        return __("Post ACF value", "miga_custom_posts");
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

        $this->add_control("field", [
            "label" => esc_html__("ACF field name", "miga_custom_posts"),
            "type" => \Elementor\Controls_Manager::TEXT,
            "default" => esc_html__("", "miga_custom_posts"),
            "placeholder" => esc_html__(
                "Type your field name here",
                "miga_custom_posts"
            ),
        ]);

        $this->add_control("acf_type", [
            "label" => esc_html__("Type", "miga_custom_posts"),
            "type" => \Elementor\Controls_Manager::SELECT,
            "default" => "text",
            "options" => [
                "text" => esc_html__("text", "miga_custom_posts"),
                "image" => esc_html__("image", "miga_custom_posts"),
                "link" => esc_html__("link", "miga_custom_posts"),
            ],
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
                "selector" => "{{WRAPPER}} .miga_custom_posts_acf",
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
                "{{WRAPPER}} .miga_custom_posts_acf" => "color: {{VALUE}}",
            ],
        ]);

        $this->add_group_control(\Elementor\Group_Control_Border::get_type(), [
            "name" => "border",
            "label" => esc_html__("Border", "miga_custom_posts"),
            "selector" => "{{WRAPPER}} .miga_custom_posts_acf",
        ]);

        $this->add_group_control(
            \Elementor\Group_Control_Text_Shadow::get_type(),
            [
                "name" => "text_shadow",
                "label" => esc_html__("Text Shadow", "miga_custom_posts"),
                "selector" => "{{WRAPPER}} .miga_custom_posts_acf",
            ]
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        $isEditor = \Elementor\Plugin::$instance->editor->is_edit_mode();
        $settings = $this->get_settings_for_display();
        $field = $settings["field"];

        if (class_exists("ACF")) {
            echo '<div class="miga_custom_posts_acf">';
            if ($isEditor) {
                echo "ACF Placeholder for: " . esc_attr($field);
            } else {

                if (
                    !empty(get_query_var("ptype")) &&
                    !empty(get_query_var("pid"))
                ) {
                    $args = [
                        "post_type" => get_query_var("ptype"),
                        "name" => get_query_var("pid"),
                    ];
                    $query = get_posts($args);
                    if (sizeOf($query) > 0) {
                        $id = $query[0]->ID;
                        if ($settings["acf_type"] == "image") {
                            $image = get_field($field, $id);
                            if ($image) {
                                echo '<img src="' .
                                    esc_url($image["url"]) .
                                    '"/>';
                            }
                        } elseif ($settings["acf_type"] == "link") {
                            $link = get_field($field, $id);
                            echo '<a class="button" href="' .
                                esc_url($link) .
                                '" >' .
                                esc_html($link) .
                                "</a>";
                        } else {
                            echo get_field($field, $id);
                        }
                    }
                } else {
                  $id =  get_the_id();
                  if ($settings["acf_type"] == "image") {
                      $image = get_field($field, $id);
                      if ($image) {
                          echo '<img src="' .
                              esc_url($image["url"]) .
                              '"/>';
                      }
                  } elseif ($settings["acf_type"] == "link") {
                      $link = get_field($field, $id);
                      echo '<a class="button" href="' .
                          esc_url($link) .
                          '" >' .
                          esc_html($link) .
                          "</a>";
                  } else {
                      echo get_field($field, $id);
                  }
                }
            }
            echo "</div>";
        } elseif ($isEditor) {
            echo "<i>ACF not installed or activated.</i>";
        }
    }
}
