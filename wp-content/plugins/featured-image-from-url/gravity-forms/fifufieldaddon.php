<?php

define('GF_FIFU_FIELD_ADDON_VERSION', '1.0');

add_action('gform_loaded', array('GF_Fifu_Field_AddOn_Bootstrap', 'load'), 5);

class GF_Fifu_Field_AddOn_Bootstrap {

    public static function load() {

        if (!method_exists('GFForms', 'include_addon_framework')) {
            return;
        }

        require_once( 'class-gffifufieldaddon.php' );

        GFAddOn::register('GFFifuFieldAddOn');
    }

}

