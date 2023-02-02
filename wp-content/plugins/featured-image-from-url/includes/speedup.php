<?php

define('FIFU_SPEEDUP_SIZES', serialize(array(64, 128, 192, 256, 384, 512, 640, 768, 896, 1024, 1280, 1600, 1920)));

function fifu_is_from_speedup($url) {
    return strpos($url, "cdn.fifu.app") !== false;
}

function fifu_resize_speedup_image_size($size, $url, $width, $height, $is_video) {
    $new_height = intval($size * $height / $width);
    return fifu_speedup_get_signed_url($url, $size, $new_height, null, null, $is_video);
}

function fifu_speedup_get_set($url) {
    $sizes = fifu_speedup_get_sizes($url);
    $width = $sizes[0];
    $height = $sizes[1];
    $is_video = $sizes[2];
    $clean_url = $sizes[3];

    $set = '';
    $count = 0;
    foreach (unserialize(FIFU_SPEEDUP_SIZES) as $i) {
        $set .= (($count++ != 0) ? ', ' : '') . fifu_resize_speedup_image_size($i, $clean_url, $width, $height, $is_video) . ' ' . $i . 'w';
        if ($width <= $i)
            break;
    }
    return $set;
}

function fifu_speedup_get_url($image, $size, $att_id) {
    // video-url
    $image_url = $video_url = null;
    $has_video = fifu_speedup_has_video_thumb($image[0]);

    // add sizes
    $aux = explode('/', $image[0])[4];
    $original_width = (int) explode('-', $aux)[1];
    $original_height = (int) explode('-', $aux)[2];

    if ($image[1] <= 1) {
        $image[1] = $original_width;
        $image[2] = $original_height;
    }

    $image = fifu_add_size($image, $size);

    // no height
    if (!$image[2])
        $image[2] = (int) ($image[1] * $original_height / $original_width);

    // crop
    if ($image[3] || $image[2]) {
        if ($has_video && $image[1] == 320 && $image[2] == 180) {
            $image[0] = $image[0] . "&resize=1280,720";
            $image[1] = 1280;
            $image[2] = 720;
        } else {
            $image[0] .= $has_video ? '&' : '?';
            $image[0] .= "resize={$image[1]},{$image[2]}";
        }
    } else
        $image[0] .= "?theme-size={$image[1]},{$image[2]}";

    $image[0] = fifu_process_external_url($image[0], $att_id, $size);
    return $image;
}

function fifu_speedup_has_video_thumb($url) {
    return strpos($url, 'video-thumb') !== false;
}

function fifu_speedup_get_signed_url($url, $width, $height, $bucket_id, $storage_id, $is_video) {
    if ($url)
        $url = explode('?', $url)[0];

    $proxy_auth = get_option('fifu_proxy_auth');
    if (!$proxy_auth)
        return $url;

    if ($url) {
        $aux = explode('/', $url);
        $bucket_id = $aux[3];
        $storage_id = $aux[4];
    }

    $aux = explode('-', $storage_id);
    $original_width = (int) $aux[1];
    $original_height = (int) $aux[2];
    $center_x = (int) $aux[3];
    $center_y = (int) $aux[4];
    $top_head = (int) $aux[5];
    $bottom = (int) $aux[6];

    $watermark = $is_video ? '/wm:0.85:ce:0:0:0.25' : '';

    $x_fp = number_format($center_x / $original_width, 2);
    $y_fp = number_format($center_y / $original_height, 2);

    // dynamic crop
    if ($top_head > 0 && fifu_is_landscape($width, $height)) {
        $ratio = $width / $height;
        $w = fifu_is_portrait($original_width, $original_height) ? $original_width : $original_height;
        $h = $w / $ratio;

        if ($bottom > 0 && ($bottom - $top_head) <= $h) {
            $center_y = ($top_head + $bottom) / 2;
            $y_fp = number_format($center_y / $original_height, 2);
        } else {
            if ($center_y - ($h / 2) < $top_head) {
                $diff = $top_head - ($center_y - ($h / 2));
                if ($center_y + ($h / 2) < $original_height) {
                    $padding_bottom = $original_height - ($center_y + ($h / 2));
                    $center_y += min($diff, $padding_bottom);
                    $y_fp = number_format($center_y / $original_height, 2);
                }
            }
        }
    }

    $key = pack("H*", $proxy_auth[0]);
    $salt = pack("H*", $proxy_auth[1]);

    $path = "/rs:fill:{$width}:{$height}:1/g:fp:{$x_fp}:{$y_fp}{$watermark}/plain/{$bucket_id}/{$storage_id}@webp";
    $signature = rtrim(strtr(base64_encode(hash_hmac('sha256', $salt . $path, $key, true)), '+/', '-_'), '=');
    return "https://cloud.fifu.app/{$signature}{$path}";
}

function fifu_speedup_get_sizes($url) {
    $aux = explode('?', $url);
    $url = $aux[0];
    $parameters = isset($aux[1]) ? $aux[1] : '';
    parse_str($parameters, $parameters);

    $aux = explode('-', $url);
    $width = (int) $aux[1];
    $height = (int) $aux[2];

    if (isset($parameters['resize'])) {
        $aux = explode(',', $parameters['resize']);
        $width = (int) $aux[0];
        $height = (int) $aux[1];
    }

    $is_video = isset($parameters['video-thumb']);

    return array($width, $height, $is_video, $url);
}

