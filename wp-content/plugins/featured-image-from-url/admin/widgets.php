<?php

class Fifu_Widget_Image extends WP_Widget {

    public function __construct() {
        $fifu = fifu_get_strings_widget();
        parent::__construct(
                'fifu_widget_image', // Base ID
                '(FIFU) ' . $fifu['title']['media'](), // Name
                array('description' => $fifu['description']['media'](),) // Args
        );
    }

    public function widget($args, $instance) {
        echo $args['before_widget'];
        echo $args['after_widget'];
    }

    public function form($instance) {
        wp_enqueue_style('fifu-pro-css', plugins_url('/html/css/pro.css', __FILE__), array(), fifu_version_number());
        echo '<div class="fifu-pro" style="float:right;position:relative;right:-30px;top:-5px"><a class="fifu-pro-link" href="https://fifu.app/" target="_blank" title="Unlock all PRO features"><span class="dashicons dashicons-lock fifu-pro-icon"></span></a></div>';
        include 'html/widget-image.html';
    }

    public function update($new_instance, $old_instance) {
        $instance = array();
        return $instance;
    }

}

class Fifu_Widget_Grid extends WP_Widget {

    public function __construct() {
        $fifu = fifu_get_strings_widget();
        parent::__construct(
                'fifu_widget_grid', // Base ID
                '(FIFU) ' . $fifu['title']['grid'](), // Name
                array('description' => $fifu['description']['grid'](),) // Args
        );
    }

    public function widget($args, $instance) {
        extract($args);
        echo $args['before_widget'];
        echo $args['after_widget'];
    }

    public function form($instance) {
        $rows = isset($instance['rows']) ? $instance['rows'] : 1;
        $columns = isset($instance['columns']) ? $instance['columns'] : 1;
        echo '<div class="fifu-pro" style="float:right;position:relative;right:-30px;top:-5px"><a class="fifu-pro-link" href="https://fifu.app/" target="_blank" title="Unlock all PRO features"><span class="dashicons dashicons-lock fifu-pro-icon"></span></a></div>';
        include 'html/widget-grid.html';
    }

    public function update($new_instance, $old_instance) {
        $instance = array();
        return $instance;
    }

}

class Fifu_Widget_Gallery extends WP_Widget {

    public function __construct() {
        $fifu = fifu_get_strings_widget();
        parent::__construct(
                'fifu_widget_gallery', // Base ID
                '(FIFU) ' . $fifu['title']['gallery'](), // Name
                array('description' => $fifu['description']['gallery'](),) // Args
        );
    }

    public function widget($args, $instance) {
        echo $args['before_widget'];
        echo $args['after_widget'];
    }

    public function form($instance) {
        wp_enqueue_style('fifu-pro-css', plugins_url('/html/css/pro.css', __FILE__), array(), fifu_version_number());
        echo '<div class="fifu-pro" style="float:right;position:relative;right:-30px;top:-5px"><a class="fifu-pro-link" href="https://fifu.app/" target="_blank" title="Unlock all PRO features"><span class="dashicons dashicons-lock fifu-pro-icon"></span></a></div>';
        include 'html/widget-gallery.html';
    }

    public function update($new_instance, $old_instance) {
        $instance = array();
        return $instance;
    }

}

add_action('widgets_init', 'fifu_register_widgets');

function fifu_register_widgets() {
    register_widget('Fifu_Widget_Image');
    register_widget('Fifu_Widget_Grid');
    register_widget('Fifu_Widget_Gallery');
}

add_action('admin_head-widgets.php', 'fifu_add_icon_to_custom_widget');

function fifu_add_icon_to_custom_widget() {
    echo
    '
        <style>
            *[id*="fifu_widget_"] > div.widget-top > div.widget-title > h3:before {
                font-family: "dashicons";
                content: "\f306";
                width:18px;
                float:left;
                height:6px;
                font-size:15px;
            }
		</style>
    ';
}

