function fifu_convert($url) {
    if (!$url)
        return $url;

    if (fifu_from_google_drive($url))
        return fifu_google_drive_url($url);

    if (fifu_has_special_char($url))
        return fifu_escape_special_char($url);

    return $url;
}

//Google Drive

function fifu_from_google_drive($url) {
    return $url.includes('drive.google.com');
}

function fifu_google_drive_id($url) {
    return $url.match(/[-\w]{25,}/);
}

function fifu_google_drive_url($url) {
    return 'https://drive.google.com/uc?id=' + fifu_google_drive_id($url);
}

//Special char

function fifu_has_special_char($url) {
    return $url.includes("'");
}

function fifu_escape_special_char($url) {
    return $url.replace("'", "%27");
}
