<?php

function addFontUI($params)
{
    $id = $params["id"];
    $class = $params["class"];
    $parentThis = $params["this"];
    $hover = isset($params["hover"]) ? $params["hover"] : true;

    $parentThis->add_group_control(
        \Elementor\Group_Control_Typography::get_type(),
        [
            "name" => $id . "_text_font",
            "selector" => "{{WRAPPER}} " . $class,
        ]
    );

    if ($hover) {
        $parentThis->start_controls_tabs("style_" . $id);
        $parentThis->start_controls_tab("tab_" . $id . "1", [
            "label" => esc_html__("Normal", "miga_custom_posts"),
        ]);
    }
    $parentThis->add_control($id . "_text_color", [
        "label" => esc_html__("Text color", "miga_custom_posts"),
        "type" => \Elementor\Controls_Manager::COLOR,
        "selectors" => [
            "{{WRAPPER}} " . $class => "color: {{VALUE}}",
        ],
    ]);

    $parentThis->add_group_control(
        \Elementor\Group_Control_Text_Shadow::get_type(),
        [
            "name" => $id . "_text_shadow",
            "label" => esc_html__("Text Shadow", "miga_custom_posts"),
            "selector" => "{{WRAPPER}} " . $class,
        ]
    );

    $parentThis->add_control("hr_" . $id . "1a", [
        "type" => \Elementor\Controls_Manager::DIVIDER,
    ]);

    $parentThis->add_group_control(
        \Elementor\Group_Control_Background::get_type(),
        [
            "name" => $id . "_background",
            "label" => esc_html__("Background", "miga_custom_posts"),
            "types" => ["classic", "gradient", "video"],
            "selector" => "{{WRAPPER}} " . $class,
        ]
    );
    $parentThis->add_control("hr_" . $id . "1b", [
        "type" => \Elementor\Controls_Manager::DIVIDER,
    ]);

    $parentThis->add_group_control(
        \Elementor\Group_Control_Border::get_type(),
        [
            "name" => $id . "_border",
            "label" => esc_html__("Border", "miga_custom_posts"),
            "selector" => "{{WRAPPER}} " . $class,
        ]
    );

    if ($hover) {
        $parentThis->end_controls_tab();
        $parentThis->start_controls_tab("tab_" . $id . "2", [
            "label" => esc_html__("Hover", "miga_custom_posts"),
        ]);

        $parentThis->add_control($id . "_text_color_hover", [
            "label" => esc_html__("Text color", "miga_custom_posts"),
            "type" => \Elementor\Controls_Manager::COLOR,
            "selectors" => [
                "{{WRAPPER}} " . $class . ":hover" => "color: {{VALUE}}",
            ],
        ]);

        $parentThis->add_group_control(
            \Elementor\Group_Control_Text_Shadow::get_type(),
            [
                "name" => $id . "_text_shadow_hover",
                "label" => esc_html__("Text Shadow", "miga_custom_posts"),
                "selector" => "{{WRAPPER}} " . $class . ":hover",
            ]
        );

        $parentThis->add_control("hr_" . $id . "2", [
            "type" => \Elementor\Controls_Manager::DIVIDER,
        ]);

        $parentThis->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                "name" => $id . "_background_hover",
                "label" => esc_html__("Background", "miga_custom_posts"),
                "types" => ["classic", "gradient", "video"],
                "selector" => "{{WRAPPER}} " . $class . ":hover",
            ]
        );

        $parentThis->add_control("hr_" . $id . "3", [
            "type" => \Elementor\Controls_Manager::DIVIDER,
        ]);

        $parentThis->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                "name" => $id . "_border_hover",
                "label" => esc_html__("Border", "miga_custom_posts"),
                "selector" => "{{WRAPPER}} " . $class . ":hover",
            ]
        );

        $parentThis->end_controls_tab();

        $parentThis->end_controls_tabs();
    }
    $parentThis->add_control("hr_" . $id . "4", [
        "type" => \Elementor\Controls_Manager::DIVIDER,
    ]);
}
