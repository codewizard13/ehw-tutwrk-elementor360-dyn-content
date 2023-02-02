<?php

class Elementor_Widget_miga_posts extends \Elementor\Widget_Base
{
    public function __construct($data = [], $args = null)
    {
        parent::__construct($data, $args);

        wp_register_script(
            "miga_custom_posts_frontend_js",
            plugins_url("../scripts/frontend.js", __FILE__),
            [],
            "1.0.0",
            true
        );

        wp_localize_script(
            "miga_custom_posts_frontend_js",
            "miga_custom_posts_params",
            [
                "miga_custom_posts_nonce" => wp_create_nonce(
                    "miga_custom_posts_nonce"
                ),
                "miga_custom_posts_url" => admin_url("admin-ajax.php"),
            ]
        );
    }

    public function get_name()
    {
        return "miga_custom_posts";
    }

    public function get_title()
    {
        return __("List of posts", "miga_custom_posts");
    }

    public function get_icon()
    {
        return "eicon-editor-list-ul";
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


        $postArray = array();
        $args = array('public'=> true);
        $output = 'names';
        $operator = 'and';
        $post_types = get_post_types($args, $output, $operator);
        foreach ($post_types  as $post_type) {
          $postArray[$post_type] = $post_type;
        }

        $this->add_control("widget_title", [
            "label" => esc_html__("Post type", "miga_custom_posts"),
            "type" => \Elementor\Controls_Manager::SELECT,
            "default" => esc_html__("post", "miga_custom_posts"),
            'options' => $postArray
        ]);

        $this->add_control("no_items", [
            "label" => esc_html__("No items text", "miga_custom_posts"),
            "type" => \Elementor\Controls_Manager::TEXT,
            "default" => esc_html__("No items", "miga_custom_posts"),
            "placeholder" => esc_html__(
                "no items text",
                "miga_custom_posts"
            ),
        ]);

        $this->add_control("post_count", [
            "label" => esc_html__("Post count", "miga_custom_posts"),
            "type" => \Elementor\Controls_Manager::NUMBER,
            "min" => -1,
            "max" => 100,
            "step" => 1,
            "default" => 3,
        ]);

        $this->add_control("show_load_more", [
            "label" => esc_html__("Show load more button", "miga_custom_posts"),
            "type" => \Elementor\Controls_Manager::SWITCHER,
            "label_on" => esc_html__("Yes", "miga_custom_posts"),
            "label_off" => esc_html__("No", "miga_custom_posts"),
            "return_value" => "yes",
            "default" => "no",
        ]);

        $this->end_controls_section();

        require_once "helper.php";
        require_once "PostList_item_layout.php";
        require_once "PostList_filter.php";
        require_once "PostList_more_button.php";
        require_once "PostList_styles.php";
        require_once "PostList_tags.php";
        require_once "PostList_image.php";
        require_once "PostList_show_details.php";
        require_once "PostList_error_message.php";
    }

    public function get_script_depends()
    {
        return ["miga_custom_posts_frontend_js"];
    }

    protected function render()
    {
        global $post;
        $settings = $this->get_settings_for_display();
        $isEditor = \Elementor\Plugin::$instance->editor->is_edit_mode();
        $postType = "";
        $numPosts = $settings["post_count"];

        if (!empty($settings["widget_title"])) {
            $postType = $settings["widget_title"];

            $myposts = get_posts([
                "post_type" => $settings["widget_title"],
                "post_status" => "publish",
                "numberposts" => $numPosts,
                // 'order'    => 'ASC'
            ]);
        } else {
            $myposts = get_posts([
                "post_status" => "publish",
                "numberposts" => 1,
                // 'order'    => 'ASC'
            ]);
        }

        if ($settings["show_filter"] === "yes") {
            if (sizeOf($settings["filter_groups"]) == 1) {
                $args = [
                    "name" => $settings["filter_groups"][0],
                ];
                $taxonomies = get_taxonomies($args);
            } else {
                $taxonomies = get_taxonomies();
            }

            echo '<div class="miga_custom_posts_filters">';
            foreach ($taxonomies as $tax):
                $groupFilter = $settings["filter_groups"];

                if (
                    sizeOf($settings["filter_groups"]) > 1 &&
                    in_array($tax, $groupFilter) === false
                ) {
                    continue;
                }
                $terms = get_terms([
                    "taxonomy" => $tax,
                    "hide_empty" => false,
                ]);

                echo '<select class="miga_custom_posts_filter" data-name="' .
                    $tax .
                    '">';
                echo '<option value=""> - ' . $tax . " - </option>";
                foreach ($terms as $term):
                    echo "<option>" . $term->name . "</option>";
                endforeach;
                echo "</select>";
            endforeach;
            echo "</div>";
        }

        echo '<div class="miga_custom_posts_row ';
        if ($settings["flex_wrap"] === "yes") {
            echo " miga_custom_posts_row_wrap ";
        }
        if ($settings["img_scale"] === "yes") {
            echo " scale_image ";
        }
        echo '"';
        echo ' data-posttype="' . $postType . '" ';
        echo ' data-numposts="' . $numPosts . '" ';
        echo ' data-noitems="' . $settings["no_items"] . '" ';
        echo ' data-erroralignment="' . $settings["error_alignment"] . '" ';

        echo ">";
        foreach ($myposts as $post):
            setup_postdata($post);
            $templateEmpty = "";
            $template = '<a href="' . get_the_permalink() . '">';

            if ($settings["show_image"] === "yes") {
                $template .= '<div class="miga_custom_posts_post_thumb">';
                $templateEmpty .= '<div class="miga_custom_posts_post_thumb">';
                $template .= get_the_post_thumbnail(get_the_id(), 'large');
                $template .= "</div>";
                $templateEmpty .= "</div>";
            }

            $template .= '<div class="miga_custom_posts_post_content';
            $templateEmpty .= '<div class="miga_custom_posts_post_content';
            if ($settings["content_full_height"] === "yes") {
                $template .= " miga_custom_posts_post_content_full ";
                $templateEmpty .= " miga_custom_posts_post_content_full ";
            }
            $template .= '">';
            $templateEmpty .= '">';

            if ($settings["show_title"] === "yes") {
                $template .= '<div class="miga_custom_posts_post_title">';
                $templateEmpty .= '<div class="miga_custom_posts_post_title">';
                $template .= get_the_title();
                $template .= "</div>";
                $templateEmpty .= "</div>";
            }
            if ($settings["show_date"] === "yes") {
                $template .= '<div class="miga_custom_posts_post_date">';
                $templateEmpty .= '<div class="miga_custom_posts_post_date">';
                $template .= get_the_date();
                $template .= "</div>";
                $templateEmpty .= "</div>";
            }
            if ($settings["show_excerpt"] === "yes") {
                $template .= '<div class="miga_custom_posts_post_excerpt">';
                $templateEmpty .=
                    '<div class="miga_custom_posts_post_excerpt">';
                $template .= wp_trim_words(get_the_content(), 60);
                $template .= "</div>";
                $templateEmpty .= "</div>";
            }
            if ($settings["show_tags"] === "yes") {
                $template .= '<div class="miga_custom_posts_post_tags">';
                $templateEmpty .= '<div class="miga_custom_posts_post_tags">';

                $taxonomies = get_post_taxonomies();
                foreach ($taxonomies as $tax):
                    $terms = wp_get_post_terms($post->ID, $tax);
                    foreach ($terms as $term):
                        $templateEmpty .=
                            '<div class="miga_custom_posts_post_tag">';
                        $template .= '<div class="miga_custom_posts_post_tag">';
                        $template .= $term->name;
                        $template .= "</div>";
                        $templateEmpty .= "</div>";
                    endforeach;
                endforeach;
                $template .= "</div>";
                $templateEmpty .= "</div>";
            }

            if ($settings["show_next"] === "yes") {
                $template .=
                    '<div class="miga_custom_posts_next ' .
                    $settings["show_next_alignment"] .
                    '" style="';
                $templateEmpty .=
                    '<div class="miga_custom_posts_next ' .
                    $settings["show_next_alignment"] .
                    '" style="';

                if ($settings["show_next_margin"] != "0") {
                    if ($settings["show_next_alignment"] == "miga_pos_left") {
                        $template .=
                            "left:" . $settings["show_next_margin"] . "px;";
                        $templateEmpty .=
                            "left:" . $settings["show_next_margin"] . "px;";
                    } elseif (
                        $settings["show_next_alignment"] == "miga_pos_right"
                    ) {
                        $template .=
                            "right:" . $settings["show_next_margin"] . "px;";
                        $templateEmpty .=
                            "right:" . $settings["show_next_margin"] . "px;";
                    }
                }

                if (isset($settings["show_next_margin_bottom"])) {
                    $template .=
                        "bottom:" .
                        $settings["show_next_margin_bottom"] .
                        "px;";
                    $templateEmpty .=
                        "bottom:" .
                        $settings["show_next_margin_bottom"] .
                        "px;";
                }

                $template .= '">' . $settings["show_next_text"] . "</div>";
                $templateEmpty .= '">' . $settings["show_next_text"] . "</div>";
            }

            $template .= "</div>";
            $templateEmpty .= "</div>";
            $template .= "</a>";
            echo $template;
        endforeach;
        echo "</div>";

        if ($settings["show_load_more"] === "yes") {
            echo '<div style="text-align:' .
                $settings["btnmore_alignment"] .
                '"><button class="miga_custom_post_button_more" data-paged="1">' .
                $settings["btn_more_title"] .
                "</button></div>";
        }

        echo ' <script class="miga_custom_post_template" type="x-tmpl-post">' .
            $templateEmpty .
            "</script>";
        wp_reset_postdata();
    }
}
