<?php

define('FIFU_JETPACK_SIZES', serialize(array(75, 100, 150, 240, 320, 500, 640, 800, 1024, 1280, 1600)));

function fifu_resize_jetpack_image_size($size, $url) {
    $size = (int) $size;

    if (strpos($url, 'resize=')) {
        $aux = explode('resize=', $url)[1];
        $aux = explode(',', $aux);
        $w = (int) $aux[0];
        $h = (int) $aux[1];
        $new_h = intval($size * $h / $w);
        $clean_url = explode('?', $url)[0];
        return "{$clean_url}?resize={$size},{$new_h}";
    }

    return "{$url}?w={$size}&resize={$size}";
}

function fifu_jetpack_get_set($url, $is_slider) {
    $quality = $is_slider ? 1.1 : 1;
    $set = '';
    $count = 0;
    foreach (unserialize(FIFU_JETPACK_SIZES) as $i)
        $set .= (($count++ != 0) ? ', ' : '') . fifu_resize_jetpack_image_size($i * $quality, $url) . ' ' . $i . 'w';
    return $set;
}

function fifu_jetpack_blocked($url) {
    if (fifu_is_photon_url($url))
        return true;

    $blocklist = array('localhost', 'amazon-adsystem.com', 'sapo.io', 'i.guim.co.uk', 's.yimg.com', 's1.yimg.com', 'www.washingtonpost.com', 'pbs.twimg.com', 'www.aljazeera.com', 'image.influenster.com', 'api.screenshotmachine.com', 'rackcdn.com', 'googleusercontent.com', 'drive.google.com', 'img.brownsfashion.com', 'accounts.parrotproducts.biz', 'www.dropbox.com', 'fbcdn.net', 'teespring.com', 'nitrocdn.com', 'brightspotcdn.com', 'realtysouth.com', 'img.youtube.com', 'cdn.fifu.app', 'cloud.fifu.app', 'images.placeholders.dev');
    foreach ($blocklist as $domain) {
        if (strpos($url, $domain) !== false)
            return true;
    }
    return false;
}

function fifu_jetpack_ssl($url) {
    $list = array('m.media-amazon.com', 'images-na.ssl-images-amazon.com', 'image.blockchain.news', 'static.news.bitcoin.com', 'thenewscrypto.com', 'cdn.coolstuff.com');
    foreach ($list as $domain) {
        if (strpos($url, $domain) !== false)
            return true;
    }
    return false;
}

function fifu_is_photon_url($url) {
    $list = array('i0.wp.com', 'i1.wp.com', 'i2.wp.com', 'i3.wp.com');
    foreach ($list as $domain) {
        if (strpos($url, $domain) !== false)
            return true;
    }
    return false;
}

function fifu_jetpack_photon_url($url, $args) {
    if (fifu_jetpack_blocked($url))
        return $url;

    if (fifu_ends_with($url, '.svg'))
        return $url;

    if (fifu_jetpack_ssl($url))
        $args['ssl'] = 1;

    $image_url_parts = wp_parse_url($url);
    if (!is_array($image_url_parts) || empty($image_url_parts['host']) || empty($image_url_parts['path']))
        return $url;
    $subdomain = abs(crc32($url) % 4);
    $host = $image_url_parts['host'];
    $path = $image_url_parts['path'];
    $photon_url = "https://i{$subdomain}.wp.com/{$host}{$path}";
    if ($args)
        return add_query_arg($args, $photon_url);
    return $photon_url;
}

