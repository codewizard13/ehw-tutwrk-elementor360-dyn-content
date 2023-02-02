<?php

add_filter('wp_insert_post_data', 'fifu_remove_first_image_ext', 10, 2);

function fifu_remove_first_image_ext($data, $postarr) {
    /* invalid or internal or ignore */
    if (isset($_POST['fifu_input_url']) || isset($_POST['fifu_ignore_auto_set']))
        return $data;

    $post_id = $postarr['ID'];
    if (fifu_has_local_featured_image($post_id) || !fifu_is_valid_cpt($post_id))
        return $data;

    $content = $postarr['post_content'];
    if (!$content)
        return $data;

    $contentClean = fifu_show_all_images($content);
    $data = str_replace($content, $contentClean, $data);

    $img = fifu_first_img_in_content($contentClean);
    if (!$img)
        return $data;

    if (fifu_is_off('fifu_pop_first'))
        return str_replace($img, fifu_show_media($img), $data);

    return str_replace($img, fifu_hide_media($img), $data);
}

add_action('save_post', 'fifu_save_properties_ext');

function fifu_save_properties_ext($post_id) {
    if (isset($_POST['fifu_input_url']))
        return;

    $url = esc_url_raw(rtrim(fifu_first_url_in_content($post_id)));

    if ((!isset($_POST['action']) || $_POST['action'] != 'elementor_ajax') && $url && fifu_is_on('fifu_get_first') && !fifu_has_local_featured_image($post_id) && fifu_is_valid_cpt($post_id)) {
        update_post_meta($post_id, 'fifu_image_url', fifu_convert($url));
        fifu_db_update_fake_attach_id($post_id);
        return;
    }

    if (!$url && get_option('fifu_default_url') && fifu_is_on('fifu_enable_default_url')) {
        if (fifu_is_valid_default_cpt($post_id))
            fifu_db_update_fake_attach_id($post_id);
    }
}

function fifu_first_img_in_content($content) {
    $content = fifu_is_on('fifu_decode') ? html_entity_decode($content) : $content;
    $nth = get_option('fifu_spinner_nth') - 1;

    preg_match_all('/<img[^>]*>/', $content, $matches);
    if ($matches && $matches[0]) {
        $skip_list = get_option('fifu_skip');
        if (!$skip_list)
            return $matches[0][$nth];

        return fifu_skip_urls($skip_list, $matches[0], $nth);
    }

    return null;
}

function fifu_skip_urls($skip_list, $img_list, $nth) {
    $i = 0;
    foreach ($img_list as $img) {
        if ($i < $nth) {
            $i++;
            continue;
        }

        $skip = false;
        foreach (explode(',', $skip_list) as $word) {
            if (strpos($img, $word) !== false) {
                $skip = true;
                break;
            }
        }

        if ($skip) {
            $i++;
            continue;
        }
        return $img_list[$i];
    }
    return null;
}

function fifu_show_all_images($content) {
    $content = fifu_is_on('fifu_decode') ? html_entity_decode($content) : $content;
    $matches = array();
    preg_match_all('/<img[^>]*display:[ ]*none[^>]*>/', $content, $matches);
    foreach ($matches[0] as $img) {
        $content = str_replace($img, fifu_show_media($img), $content);
    }
    return $content;
}

function fifu_hide_media($img) {
    $img = fifu_is_on('fifu_decode') ? html_entity_decode($img) : $img;
    $img = stripslashes($img);
    if (strpos($img, 'style=') !== false)
        return preg_replace('/style=[\'\"][^\'\"]*[\'\"]/', 'style="display:none"', $img);
    return preg_replace('/[\/]*>/', ' style="display:none">', $img);
}

function fifu_show_media($img) {
    $img = fifu_is_on('fifu_decode') ? html_entity_decode($img) : $img;
    return preg_replace('/style=[\\\]*.display:[ ]*none[\\\]*./', '', $img);
}

function fifu_first_url_in_content($post_id) {
    $content = get_post_field('post_content', $post_id);
    $content = fifu_is_on('fifu_decode') ? html_entity_decode($content) : $content;
    if (!$content)
        return;

    $matches = array();

    preg_match_all('/<img[^>]*>/', $content, $matches);

    if (!$matches[0])
        return;

    $nth = get_option('fifu_spinner_nth');

    // $matches
    $tag = null;
    if (sizeof($matches) != 0) {
        $i = 0;
        foreach ($matches[0] as $tag) {
            $i++;
            if (($tag && strpos($tag, 'data:image/jpeg') !== false) || ($i < $nth))
                continue;

            // skip
            $skip_list = get_option('fifu_skip');
            if ($skip_list) {
                $skip = false;
                foreach (explode(',', $skip_list) as $word) {
                    if (strpos($tag, $word) !== false) {
                        $skip = true;
                        break;
                    }
                }
                if ($skip)
                    continue;
            }

            break;
        }
    }

    if (!$tag)
        return null;

    // src
    $src = fifu_get_attribute('src', $tag);

    //query strings
    if (fifu_is_on('fifu_query_strings'))
        return preg_replace('/\?.*/', '', $src);

    return $src;
}

function fifu_update_fake_attach_id($post_id) {
    fifu_db_update_fake_attach_id($post_id);
}

