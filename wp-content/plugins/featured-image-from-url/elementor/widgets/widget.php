<?php

class Elementor_FIFU_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'fifu-elementor';
    }

    public function get_title() {
        return __('(FIFU) Featured Image', 'elementor-fifu-extension');
    }

    public function get_icon() {
        return 'eicon-featured-image';
    }

    public function get_categories() {
        return ['basic'];
    }

    protected function _register_controls() {

        $this->start_controls_section(
                'content_section_image',
                [
                    'label' => __('Featured image', 'elementor-fifu-extension'),
                    'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                ]
        );
        $this->add_control(
                'fifu_input_url',
                [
                    'label' => __('Image URL', 'elementor-fifu-extension'),
                    'show_label' => true,
                    'label_block' => true,
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'input_type' => 'url',
                    'placeholder' => __('https://example.com/image.jpg', 'elementor-fifu-extension'),
                ]
        );
        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        $image_url = $settings['fifu_input_url'];
        if ($image_url) {
            $image_url = fifu_convert($image_url);
            echo '<div style="width:100%;text-align:center;"><img class="oembed-elementor-widget fifu-elementor-image" src="' . $image_url . '"/></div>';
        }
    }

}

function fifu_image_after_save_elementor_data($post_id, $editor_data) {
    foreach ($editor_data as $data) {
        if (isset($data['elements'][0]['elements'][0]['widgetType'])) {
            $widgetType = $data['elements'][0]['elements'][0]['widgetType'];
            if (strpos($widgetType, 'fifu') !== false) {
                $settings = $data['elements'][0]['elements'][0]['settings'];

                if ($widgetType == 'fifu-elementor') {
                    if (isset($settings['fifu_input_url'])) {
                        $image_url = $settings['fifu_input_url'];
                        fifu_dev_set_image($post_id, $image_url);
                    }
                }
            }
        }
    }
}

add_action('elementor/editor/after_save', 'fifu_image_after_save_elementor_data', 10, 2);

