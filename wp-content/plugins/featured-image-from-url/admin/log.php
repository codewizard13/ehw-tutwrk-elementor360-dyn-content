<?php

function fifu_cloud_log($entry, $mode = 'a', $file = 'fifu-cloud') {
    return fifu_log($entry, $file, $mode);
}

function fifu_plugin_log($entry, $mode = 'a', $file = 'fifu-plugin') {
    return fifu_log($entry, $file, $mode);
}

function fifu_log($entry, $file, $mode = 'a') {
    $upload_dir = wp_upload_dir()['basedir'];

    if (is_array($entry))
        $entry = json_encode([current_time('mysql') => $entry], JSON_UNESCAPED_SLASHES);

    $file = fopen("{$upload_dir}/{$file}.log", $mode);
    $bytes = fwrite($file, "{$entry}\n");
    fclose($file);
    return $bytes;
}

