<?php

class Elementor_FIFU_Video_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'fifu-video-elementor';
    }

    public function get_title() {
        return __('(FIFU) Featured Video', 'elementor-fifu-video-extension');
    }

    public function get_icon() {
        return 'eicon-youtube';
    }

    public function get_categories() {
        return ['basic'];
    }

    protected function _register_controls() {

        $this->start_controls_section(
                'content_section_video',
                [
                    'label' => __('Featured video', 'elementor-fifu-video-extension'),
                    'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                ]
        );
        $this->add_control(
                'fifu_video_input_url',
                [
                    'label' => __('Video URL', 'elementor-fifu-video-extension'),
                    'show_label' => true,
                    'label_block' => true,
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'input_type' => 'url',
                    'placeholder' => __('https://youtube.com/watch?v=ID', 'elementor-fifu-video-extension'),
                    'description' => 'Requires FIFU PRO',
                ]
        );
        $this->end_controls_section();
    }

    protected function render() {
        
    }

}

