<?php

class fifu_cli extends WP_CLI_Command {

    // admin

    function reset() {
        fifu_reset_settings();
        //WP_CLI::line($args[0]);
    }

    // automatic

    function content($args, $assoc_args) {
        if (!empty($assoc_args['position'])) {
            update_option('fifu_spinner_nth', $args[0], 'no');
            return;
        }
        if (!empty($assoc_args['skip'])) {
            update_option('fifu_skip', $args[0], 'no');
            return;
        }
        if (!empty($assoc_args['cpt'])) {
            update_option('fifu_html_cpt', $args[0], 'no');
            return;
        }
        if (!empty($assoc_args['hide'])) {
            switch ($args[0]) {
                case 'on':
                    update_option('fifu_pop_first', 'toggleon', 'no'); // toggle
                    break;
                case 'off':
                    update_option('fifu_pop_first', 'toggleoff', 'no'); // toggle
                    break;
            }
            return;
        }
        if (!empty($assoc_args['remove-query'])) {
            switch ($args[0]) {
                case 'on':
                    update_option('fifu_query_strings', 'toggleon', 'no'); // toggle
                    break;
                case 'off':
                    update_option('fifu_query_strings', 'toggleoff', 'no'); // toggle
                    break;
            }
            return;
        }
        if (!empty($assoc_args['overwrite'])) {
            switch ($args[0]) {
                case 'on':
                    update_option('fifu_ovw_first', 'toggleon', 'no'); // toggle
                    break;
                case 'off':
                    update_option('fifu_ovw_first', 'toggleoff', 'no'); // toggle
                    break;
            }
            return;
        }
        if (!empty($assoc_args['decode'])) {
            switch ($args[0]) {
                case 'on':
                    update_option('fifu_decode', 'toggleon', 'no'); // toggle
                    break;
                case 'off':
                    update_option('fifu_decode', 'toggleoff', 'no'); // toggle
                    break;
            }
            return;
        }
        if (!empty($assoc_args['check'])) {
            switch ($args[0]) {
                case 'on':
                    update_option('fifu_check', 'toggleon', 'no'); // toggle
                    break;
                case 'off':
                    update_option('fifu_check', 'toggleoff', 'no'); // toggle
                    break;
            }
            return;
        }
        switch ($args[0]) {
            case 'on':
                update_option('fifu_get_first', 'toggleon', 'no'); // toggle
                break;
            case 'off':
                update_option('fifu_get_first', 'toggleoff', 'no'); // toggle
                break;
        }
    }

    // featured image

    function image($args, $assoc_args) {
        if (!empty($assoc_args['title-copy'])) {
            switch ($args[0]) {
                case 'on':
                    update_option('fifu_auto_alt', 'toggleon', 'no'); // toggle
                    break;
                case 'off':
                    update_option('fifu_auto_alt', 'toggleoff', 'no'); // toggle
                    break;
            }
            return;
        }
        if (!empty($assoc_args['title-always'])) {
            switch ($args[0]) {
                case 'on':
                    update_option('fifu_dynamic_alt', 'toggleon', 'no'); // toggle
                    break;
                case 'off':
                    update_option('fifu_dynamic_alt', 'toggleoff', 'no'); // toggle
                    break;
            }
            return;
        }
        if (!empty($assoc_args['hide-page'])) {
            switch ($args[0]) {
                case 'on':
                    update_option('fifu_hide_page', 'toggleon', 'no'); // toggle
                    break;
                case 'off':
                    update_option('fifu_hide_page', 'toggleoff', 'no'); // toggle
                    break;
            }
            return;
        }
        if (!empty($assoc_args['hide-post'])) {
            switch ($args[0]) {
                case 'on':
                    update_option('fifu_hide_post', 'toggleon', 'no'); // toggle
                    break;
                case 'off':
                    update_option('fifu_hide_post', 'toggleoff', 'no'); // toggle
                    break;
            }
            return;
        }
        if (!empty($assoc_args['hide-cpt'])) {
            switch ($args[0]) {
                case 'on':
                    update_option('fifu_hide_cpt', 'toggleon', 'no'); // toggle
                    break;
                case 'off':
                    update_option('fifu_hide_cpt', 'toggleoff', 'no'); // toggle
                    break;
            }
            return;
        }
        if (!empty($assoc_args['default'])) {
            switch ($args[0]) {
                case 'on':
                    update_option('fifu_enable_default_url', 'toggleon', 'no'); // toggle
                    $default_url = get_option('fifu_default_url');
                    if (!$default_url)
                        fifu_db_delete_default_url();
                    elseif (fifu_is_on('fifu_fake')) {
                        if (!wp_get_attachment_url(get_option('fifu_default_attach_id'))) {
                            $att_id = fifu_db_create_attachment($default_url);
                            update_option('fifu_default_attach_id', $att_id);
                            fifu_db_set_default_url();
                        } else
                            fifu_db_update_default_url($default_url);
                    }
                    break;
                case 'off':
                    update_option('fifu_enable_default_url', 'toggleoff', 'no'); // toggle
                    fifu_db_delete_default_url();
                    break;
            }
            return;
        }
        if (!empty($assoc_args['default-url'])) {
            update_option('fifu_default_url', $args[0], 'no');
            if (fifu_is_off('fifu_enable_default_url'))
                fifu_db_delete_default_url();
            elseif (!$args[0])
                fifu_db_delete_default_url();
            return;
        }
        if (!empty($assoc_args['default-types'])) {
            update_option('fifu_default_cpt', $args[0], 'no');
            return;
        }
        if (!empty($assoc_args['content-page'])) {
            switch ($args[0]) {
                case 'on':
                    update_option('fifu_content_page', 'toggleon', 'no'); // toggle
                    break;
                case 'off':
                    update_option('fifu_content_page', 'toggleoff', 'no'); // toggle
                    break;
            }
            return;
        }
        if (!empty($assoc_args['content-post'])) {
            switch ($args[0]) {
                case 'on':
                    update_option('fifu_content', 'toggleon', 'no'); // toggle
                    break;
                case 'off':
                    update_option('fifu_content', 'toggleoff', 'no'); // toggle
                    break;
            }
            return;
        }
        if (!empty($assoc_args['content-cpt'])) {
            switch ($args[0]) {
                case 'on':
                    update_option('fifu_content_cpt', 'toggleon', 'no'); // toggle
                    break;
                case 'off':
                    update_option('fifu_content_cpt', 'toggleoff', 'no'); // toggle
                    break;
            }
            return;
        }
    }

    // metadata

    function metadata($args) {
        switch ($args[0]) {
            case 'on':
                update_option('fifu_fake_stop', false, 'no');
                fifu_enable_fake();
                set_transient('fifu_image_metadata_counter', fifu_db_count_urls_without_metadata(), 0);
                update_option('fifu_fake', 'toggleon', 'no'); // toggle
                break;
            case 'off':
                update_option('fifu_fake_created', false, 'no');
                update_option('fifu_fake_stop', true, 'no');
                set_transient('fifu_image_metadata_counter', fifu_db_count_urls_without_metadata(), 0);
                update_option('fifu_fake', 'toggleoff', 'no'); // toggle
                break;
        }
    }

    function clean() {
        fifu_db_enable_clean();
        update_option('fifu_data_clean', 'toggleoff', 'no');
        set_transient('fifu_image_metadata_counter', fifu_db_count_urls_without_metadata(), 0);
    }

    function dimensions() {
        fifu_run_get_and_save_sizes_api(new WP_REST_Request());
    }

    // performance

    function cdn($args, $assoc_args) {
        if (!empty($assoc_args['social'])) {
            switch ($args[0]) {
                case 'on':
                    update_option('fifu_cdn_social', 'toggleon', 'no'); // toggle
                    break;
                case 'off':
                    update_option('fifu_cdn_social', 'toggleoff', 'no'); // toggle
                    break;
            }
            return;
        }
        if (!empty($assoc_args['crop'])) {
            switch ($args[0]) {
                case 'on':
                    update_option('fifu_cdn_crop', 'toggleon', 'no'); // toggle
                    break;
                case 'off':
                    update_option('fifu_cdn_crop', 'toggleoff', 'no'); // toggle
                    break;
            }
            return;
        }
        if (!empty($assoc_args['content'])) {
            switch ($args[0]) {
                case 'on':
                    update_option('fifu_cdn_content', 'toggleon', 'no'); // toggle
                    break;
                case 'off':
                    update_option('fifu_cdn_content', 'toggleoff', 'no'); // toggle
                    break;
            }
            return;
        }
        switch ($args[0]) {
            case 'on':
                update_option('fifu_photon', 'toggleon', 'no'); // toggle
                break;
            case 'off':
                update_option('fifu_photon', 'toggleoff', 'no'); // toggle
                break;
        }
    }

    function lazy($args) {
        switch ($args[0]) {
            case 'on':
                update_option('fifu_lazy', 'toggleon', 'no'); // toggle
                break;
            case 'off':
                update_option('fifu_lazy', 'toggleoff', 'no'); // toggle
                break;
        }
    }

    // social

    function social($args, $assoc_args) {
        if (!empty($assoc_args['image-only'])) {
            switch ($args[0]) {
                case 'on':
                    update_option('fifu_social_image_only', 'toggleon', 'no'); // toggle
                    break;
                case 'off':
                    update_option('fifu_social_image_only', 'toggleoff', 'no'); // toggle
                    break;
            }
            return;
        }
        switch ($args[0]) {
            case 'on':
                update_option('fifu_social', 'toggleon', 'no'); // toggle
                break;
            case 'off':
                update_option('fifu_social', 'toggleoff', 'no'); // toggle
                break;
        }
    }

    // woocommerce

    function woo($args, $assoc_args) {
        if (!empty($assoc_args['lightbox'])) {
            switch ($args[0]) {
                case 'on':
                    update_option('fifu_wc_lbox', 'toggleon', 'no'); // toggle
                    break;
                case 'off':
                    update_option('fifu_wc_lbox', 'toggleoff', 'no'); // toggle
                    break;
            }
            return;
        }
        if (!empty($assoc_args['zoom'])) {
            switch ($args[0]) {
                case 'on':
                    update_option('fifu_wc_zoom', 'toggleon', 'no'); // toggle
                    break;
                case 'off':
                    update_option('fifu_wc_zoom', 'toggleoff', 'no'); // toggle
                    break;
            }
            return;
        }
    }

}

WP_CLI::add_command('fifu', 'fifu_cli');

