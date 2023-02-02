<?php

if (!defined('ABSPATH')) {
    exit;
}

final class Elementor_FIFU_Extension {

    private static $_instance = null;

    public static function instance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function __construct() {
        add_action('init', [$this, 'i18n']);
        add_action('plugins_loaded', [$this, 'init']);
    }

    public function i18n() {
        load_plugin_textdomain('elementor-fifu-extension');
        load_plugin_textdomain('elementor-fifu-video-extension');
    }

    public function init() {
        // Add Plugin actions
        add_action('elementor/widgets/widgets_registered', [$this, 'init_widgets']);
        add_action('elementor/controls/controls_registered', [$this, 'init_controls']);

        // Register Widget Scripts
        add_action('elementor/frontend/after_register_scripts', [$this, 'widget_scripts']);
    }

    public function init_widgets() {
        // Include Widget files and register widget
        require_once( __DIR__ . '/widgets/widget.php' );
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new \Elementor_FIFU_Widget());

        require_once( __DIR__ . '/widgets/widget-video.php' );
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new \Elementor_FIFU_Video_Widget());
    }

    public function init_controls() {
        
    }

    public function widget_scripts() {
        
    }

}

Elementor_FIFU_Extension::instance();

