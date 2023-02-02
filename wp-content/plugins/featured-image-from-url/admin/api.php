<?php

define('FIFU_TRY_AGAIN_LATER', json_encode(array('code' => 0, 'message' => 'try again later', 'color' => 'orange')));
define('FIFU_NO_CREDENTIALS', json_encode(array('code' => 'no_credentials')));
define('FIFU_SU_ADDRESS', FIFU_CLOUD_DEBUG && fifu_is_local() ? 'http://0.0.0.0:8080' : 'https://ws.fifu.app');
define('FIFU_QUERY_ADDRESS', 'https://query.featuredimagefromurl.com');
define('FIFU_SURVEY_ADDRESS', 'https://survey.featuredimagefromurl.com');
define('FIFU_CLIENT', 'featured-image-from-url');

function fifu_is_local() {
    $query = 'http://localhost';
    return substr(get_home_url(), 0, strlen($query)) === $query;
}

function fifu_remote_post($endpoint, $array) {
    return fifu_is_local() ? wp_remote_post($endpoint, $array) : wp_safe_remote_post($endpoint, $array);
}

function fifu_api_sign_up(WP_REST_Request $request) {
    $first_name = $request['first_name'];
    $last_name = $request['last_name'];
    $email = $request['email'];
    $site = fifu_get_home_url();

    fifu_cloud_log(['sign_up' => ['site' => $site]]);

    $array = array(
        'headers' => array('Content-Type' => 'application/json; charset=utf-8'),
        'body' => json_encode(
                array(
                    'site' => $site,
                    'first_name' => $first_name,
                    'last_name' => $last_name,
                    'email' => $email,
                    'public_key' => fifu_create_keys($email),
                    'slug' => FIFU_CLIENT,
                    'version' => fifu_version_number()
                )
        ),
        'method' => 'POST',
        'data_format' => 'body',
        'blocking' => true,
        'timeout' => 120,
    );
    $response = fifu_remote_post(FIFU_SU_ADDRESS . '/sign-up/', $array);
    if (is_wp_error($response) || $response['response']['code'] == 404) {
        fifu_delete_credentials();
        return json_decode(FIFU_TRY_AGAIN_LATER);
    }

    $json = json_decode($response['http_response']->get_response_object()->body);
    if ($json->code <= 0) {
        fifu_delete_credentials();
        return $json;
    }

    $privKey = openssl_decrypt(base64_decode(get_option('fifu_su_privkey')[0]), "AES-128-ECB", $email . $site);
    if ($privKey) {
        openssl_private_decrypt(base64_decode($json->qrcode), $decrypted, $privKey);
        $json->qrcode = $decrypted;
    }

    return $json;
}

function fifu_delete_credentials() {
    delete_option('fifu_su_privkey');
    delete_option('fifu_su_email');
    delete_option('fifu_proxy_auth');
}

function fifu_api_login(WP_REST_Request $request) {
    if (!fifu_su_sign_up_complete())
        return json_decode(FIFU_NO_CREDENTIALS);

    $email = $request['email'];
    $site = fifu_get_home_url();
    $tfa = $request['tfa'];
    $ip = fifu_get_ip();
    $time = time();
    $signature = fifu_create_signature($site . $email . $time . $ip . $tfa);

    fifu_cloud_log(['login' => ['site' => $site]]);

    $array = array(
        'headers' => array('Content-Type' => 'application/json; charset=utf-8'),
        'body' => json_encode(
                array(
                    'site' => $site,
                    'email' => $email,
                    'signature' => $signature,
                    'time' => $time,
                    'ip' => $ip,
                    'proxy_auth' => get_option('fifu_proxy_auth') ? true : false,
                    'slug' => FIFU_CLIENT,
                    'version' => fifu_version_number()
                )
        ),
        'method' => 'POST',
        'data_format' => 'body',
        'blocking' => true,
        'timeout' => 30,
    );
    $response = fifu_remote_post(FIFU_SU_ADDRESS . '/login/', $array);
    if (is_wp_error($response))
        return json_decode(FIFU_TRY_AGAIN_LATER);

    $json = json_decode($response['http_response']->get_response_object()->body);
    $json->fifu_tfa_hash = hash('sha512', $tfa);

    if (isset($json->proxy_key)) {
        $privKey = openssl_decrypt(base64_decode(get_option('fifu_su_privkey')[0]), "AES-128-ECB", $email . $site);
        if ($privKey) {
            openssl_private_decrypt(base64_decode($json->proxy_key), $key, $privKey);
            openssl_private_decrypt(base64_decode($json->proxy_salt), $salt, $privKey);
            update_option('fifu_proxy_auth', array($key, $salt));
        }
    }

    return $json;
}

function fifu_api_logout(WP_REST_Request $request) {
    if (!fifu_su_sign_up_complete())
        return json_decode(FIFU_NO_CREDENTIALS);

    $email = fifu_su_get_email();
    $site = fifu_get_home_url();
    $tfa = $request['tfa'];
    $ip = fifu_get_ip();
    $time = time();
    $signature = fifu_create_signature($site . $email . $time . $ip . $tfa);

    fifu_cloud_log(['logout' => ['site' => $site]]);

    $array = array(
        'headers' => array('Content-Type' => 'application/json; charset=utf-8'),
        'body' => json_encode(
                array(
                    'site' => $site,
                    'email' => $email,
                    'signature' => $signature,
                    'time' => $time,
                    'ip' => $ip,
                    'slug' => FIFU_CLIENT,
                    'version' => fifu_version_number()
                )
        ),
        'method' => 'POST',
        'data_format' => 'body',
        'blocking' => true,
        'timeout' => 30,
    );
    $response = fifu_remote_post(FIFU_SU_ADDRESS . '/logout/', $array);
    if (is_wp_error($response))
        return json_decode(FIFU_TRY_AGAIN_LATER);

    $json = json_decode($response['http_response']->get_response_object()->body);
    if ($json->code == 8)
        setcookie('fifu-tfa', '');

    return $json;
}

function fifu_api_cancel(WP_REST_Request $request) {
    if (!fifu_su_sign_up_complete())
        return json_decode(FIFU_NO_CREDENTIALS);

    $site = fifu_get_home_url();
    $tfa = $request['tfa'];
    $ip = fifu_get_ip();
    $time = time();
    $signature = fifu_create_signature($site . $time . $ip . $tfa);

    fifu_cloud_log(['cancel' => ['site' => $site]]);

    $array = array(
        'headers' => array('Content-Type' => 'application/json; charset=utf-8'),
        'body' => json_encode(
                array(
                    'site' => $site,
                    'signature' => $signature,
                    'time' => $time,
                    'ip' => $ip,
                    'slug' => FIFU_CLIENT,
                    'version' => fifu_version_number()
                )
        ),
        'method' => 'POST',
        'data_format' => 'body',
        'blocking' => true,
        'timeout' => 30,
    );
    $response = fifu_remote_post(FIFU_SU_ADDRESS . '/cancel/', $array);
    if (is_wp_error($response))
        return json_decode(FIFU_TRY_AGAIN_LATER);

    $json = json_decode($response['http_response']->get_response_object()->body);

    return $json;
}

function fifu_api_payment_info(WP_REST_Request $request) {
    if (!fifu_su_sign_up_complete())
        return json_decode(FIFU_NO_CREDENTIALS);

    $site = fifu_get_home_url();
    $tfa = $request['tfa'];
    $ip = fifu_get_ip();
    $time = time();
    $signature = fifu_create_signature($site . $time . $ip . $tfa);

    fifu_cloud_log(['payment_info' => ['site' => $site]]);

    $array = array(
        'headers' => array('Content-Type' => 'application/json; charset=utf-8'),
        'body' => json_encode(
                array(
                    'site' => $site,
                    'signature' => $signature,
                    'time' => $time,
                    'ip' => $ip,
                    'slug' => FIFU_CLIENT,
                    'version' => fifu_version_number()
                )
        ),
        'method' => 'POST',
        'data_format' => 'body',
        'blocking' => true,
        'timeout' => 30,
    );
    $response = fifu_remote_post(FIFU_SU_ADDRESS . '/payment-info/', $array);
    if (is_wp_error($response))
        return json_decode(FIFU_TRY_AGAIN_LATER);

    $json = json_decode($response['http_response']->get_response_object()->body);

    return $json;
}

function fifu_api_connected(WP_REST_Request $request) {
    if (!fifu_su_sign_up_complete())
        return json_decode(FIFU_NO_CREDENTIALS);

    $email = fifu_su_get_email();
    $site = fifu_get_home_url();
    $tfa = $request['tfa'];
    $ip = fifu_get_ip();
    $time = time();
    $signature = fifu_create_signature($site . $email . $time . $ip . $tfa);

    fifu_cloud_log(['connected' => ['site' => $site]]);

    $array = array(
        'headers' => array('Content-Type' => 'application/json; charset=utf-8'),
        'body' => json_encode(
                array(
                    'site' => $site,
                    'email' => $email,
                    'signature' => $signature,
                    'time' => $time,
                    'ip' => $ip,
                    'slug' => FIFU_CLIENT,
                    'version' => fifu_version_number()
                )
        ),
        'method' => 'POST',
        'data_format' => 'body',
        'blocking' => true,
        'timeout' => 30,
    );
    $response = fifu_remote_post(FIFU_SU_ADDRESS . '/connected/', $array);
    if (is_wp_error($response))
        return json_decode(FIFU_TRY_AGAIN_LATER);

    // offline
    if ($response['http_response']->get_response_object()->status_code == 404)
        return json_decode(FIFU_TRY_AGAIN_LATER);

    // enable lazy load
    update_option('fifu_lazy', 'toggleon');

    return json_decode($response['http_response']->get_response_object()->body);
}

function fifu_get_ip() {
    foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key) {
        if (array_key_exists($key, $_SERVER) === true) {
            foreach (explode(',', $_SERVER[$key]) as $ip) {
                $ip = trim($ip);
                if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false)
                    return $ip;
            }
        }
    }
    return $_SERVER['REMOTE_ADDR'];
}

function fifu_api_create_thumbnails_list(WP_REST_Request $request) {
    if (!fifu_su_sign_up_complete())
        return json_decode(FIFU_NO_CREDENTIALS);

    $images = $request['selected'];
    $tfa = $request['tfa'];

    return fifu_create_thumbnails_list($images, $tfa, false);
}

function fifu_create_thumbnails_list($images, $tfa = null, $cron = false) {
    if (!fifu_su_sign_up_complete())
        return json_decode(FIFU_NO_CREDENTIALS);

    if ($cron) {
        $code = get_option('fifu_cloud_upload_auto_code');
        if (!$code)
            return json_decode(FIFU_NO_CREDENTIALS);
        $tfa = $code[0];
    }

    $rows = array();
    $total = count($images);
    $url_sign = '';
    foreach ($images as $image) {
        if (!$cron) {
            // manual
            $post_id = $image[0];
            $url = $image[1];
            $meta_key = $image[2];
            $meta_id = $image[3];
            $is_category = $image[4] == 1;
            $video_url = $image[5];
        } else {
            // upload auto
            $post_id = $image->post_id;
            $url = $image->url;
            $meta_key = $image->meta_key;
            $meta_id = $image->meta_id;
            $is_category = $image->category == 1;
            $video_url = $image->video_url;
        }

        if (!$url || !$post_id)
            continue;

        $encoded_url = base64_encode($url);
        $encoded_video_url = $video_url ? base64_encode($video_url) : '';
        array_push($rows, array($post_id, $encoded_url, $meta_key, $meta_id, $is_category, $encoded_video_url));
        $url_sign .= substr($encoded_url, -10);

        fifu_cloud_log(['create_thumbnails_list' => ['post_id' => $post_id, 'meta_key' => $meta_key, 'meta_id' => $meta_id, 'is_category' => $is_category, 'video_url' => $video_url, 'url' => $url]]);
    }
    $time = time();
    $ip = fifu_get_ip();
    $site = fifu_get_home_url();
    $signature = fifu_create_signature($url_sign . $site . $time . $ip . $tfa);
    $array = array(
        'headers' => array('Content-Type' => 'application/json; charset=utf-8'),
        'body' => json_encode(
                array(
                    'rows' => $rows,
                    'site' => $site,
                    'signature' => $signature,
                    'time' => $time,
                    'ip' => $ip,
                    'upload_auto' => $cron,
                    'slug' => FIFU_CLIENT,
                    'version' => fifu_version_number()
                )
        ),
        'method' => 'POST',
        'data_format' => 'body',
        'blocking' => true,
        'timeout' => 60,
    );
    $response = fifu_remote_post(FIFU_SU_ADDRESS . '/create-thumbnails/', $array);
    if (is_wp_error($response))
        return;

    $json = json_decode($response['http_response']->get_response_object()->body);
    $code = $json->code;
    if ($code && $code > 0) {
        if (count((array) $json->thumbnails) > 0) {
            $category_images = array();
            $post_images = array();
            foreach ((array) $json->thumbnails as $thumbnail) {
                if ($thumbnail->is_category)
                    array_push($category_images, $thumbnail);
                else
                    array_push($post_images, $thumbnail);
            }
            if (count($category_images) > 0)
                fifu_ctgr_add_urls_su($json->bucket_id, $category_images);

            if (count($post_images) > 0)
                fifu_add_urls_su($json->bucket_id, $post_images);
        }
    }

    return $json;
}

function fifu_api_delete(WP_REST_Request $request) {
    if (!fifu_su_sign_up_complete())
        return json_decode(FIFU_NO_CREDENTIALS);

    $rows = array();
    $images = $request['selected'];
    $tfa = $request['tfa'];
    $total = count($images);
    $url_sign = '';
    foreach ($images as $image) {
        $storage_id = $image['storage_id'];
        if (!$storage_id)
            continue;

        array_push($rows, $storage_id);
        $url_sign .= $storage_id;
    }
    $time = time();
    $ip = fifu_get_ip();
    $site = fifu_get_home_url();
    $signature = fifu_create_signature($url_sign . $site . $time . $ip . $tfa);

    fifu_cloud_log(['delete' => ['rows' => $rows]]);

    $array = array(
        'headers' => array('Content-Type' => 'application/json; charset=utf-8'),
        'body' => json_encode(
                array(
                    'rows' => $rows,
                    'site' => $site,
                    'signature' => $signature,
                    'time' => $time,
                    'ip' => $ip,
                    'slug' => FIFU_CLIENT,
                    'version' => fifu_version_number()
                )
        ),
        'method' => 'POST',
        'data_format' => 'body',
        'blocking' => true,
        'timeout' => 60,
    );
    $response = fifu_remote_post(FIFU_SU_ADDRESS . '/delete/', $array);
    if (is_wp_error($response))
        return json_decode(FIFU_TRY_AGAIN_LATER);

    $json = json_decode($response['http_response']->get_response_object()->body);
    if (!$json)
        return null;

    $code = $json->code;
    if ($code && $code > 0) {
        if (count((array) $json->urls) > 0) {
            $map = array();
            $posts = fifu_get_posts_su($rows);
            foreach ($posts as $post)
                $map[$post->storage_id] = $post;

            $category_images = array();
            $post_images = array();
            foreach ($posts as $post) {
                if ($post->category)
                    array_push($category_images, $post);
                else
                    array_push($post_images, $post);
            }

            if (count($post_images) > 0)
                fifu_remove_urls_su($json->bucket_id, $post_images, (array) $json->urls, (array) $json->video_urls);

            if (count($category_images) > 0)
                fifu_ctgr_remove_urls_su($json->bucket_id, $category_images, (array) $json->urls, (array) $json->video_urls);

            return fifu_api_confirm_delete($rows, $site, $ip, $tfa, $url_sign);
        }
    }

    return $json;
}

function fifu_api_confirm_delete($rows, $site, $ip, $tfa, $url_sign) {
    if (!fifu_su_sign_up_complete())
        return json_decode(FIFU_NO_CREDENTIALS);

    $time = time();
    $signature = fifu_create_signature($url_sign . $site . $time . $ip . $tfa);

    fifu_cloud_log(['confirm_delete' => ['rows' => $rows]]);

    $array = array(
        'headers' => array('Content-Type' => 'application/json; charset=utf-8'),
        'body' => json_encode(
                array(
                    'rows' => $rows,
                    'site' => $site,
                    'signature' => $signature,
                    'time' => $time,
                    'ip' => $ip,
                    'slug' => FIFU_CLIENT,
                    'version' => fifu_version_number()
                )
        ),
        'method' => 'POST',
        'data_format' => 'body',
        'blocking' => true,
        'timeout' => 300,
    );
    $response = fifu_remote_post(FIFU_SU_ADDRESS . '/confirm-delete/', $array);
    if (is_wp_error($response))
        return json_decode(FIFU_TRY_AGAIN_LATER);

    $json = json_decode($response['http_response']->get_response_object()->body);
    return $json;
}

function fifu_api_reset_credentials(WP_REST_Request $request) {
    fifu_delete_credentials();
    $email = $request['email'];
    $site = fifu_get_home_url();

    fifu_cloud_log(['reset_credentials' => ['site' => $site]]);

    $array = array(
        'headers' => array('Content-Type' => 'application/json; charset=utf-8'),
        'body' => json_encode(
                array(
                    'site' => $site,
                    'email' => $email,
                    'public_key' => fifu_create_keys($email),
                    'slug' => FIFU_CLIENT,
                    'version' => fifu_version_number()
                )
        ),
        'method' => 'POST',
        'data_format' => 'body',
        'blocking' => true,
        'timeout' => 30,
    );
    $response = fifu_remote_post(FIFU_SU_ADDRESS . '/reset-credentials/', $array);
    if (is_wp_error($response))
        return json_decode(FIFU_TRY_AGAIN_LATER);
    else {
        $json = json_decode($response['http_response']->get_response_object()->body);
        $privKey = openssl_decrypt(base64_decode(get_option('fifu_su_privkey')[0]), "AES-128-ECB", $email . $site);
        if (isset($json->qrcode)) {
            openssl_private_decrypt(base64_decode($json->qrcode), $decrypted, $privKey);
            $json->qrcode = $decrypted;
        }

        # unknown site
        if ($json->code == -21)
            fifu_delete_credentials();

        return $json;
    }
}

function fifu_api_list_all_su(WP_REST_Request $request) {
    if (!fifu_su_sign_up_complete())
        return json_decode(FIFU_NO_CREDENTIALS);

    $time = time();
    $site = fifu_get_home_url();
    $tfa = $request['tfa'];
    $page = (int) $request['page'];
    $ip = fifu_get_ip();
    $signature = fifu_create_signature($site . $time . $ip . $tfa);

    fifu_cloud_log(['list_all_su' => ['site' => $site, 'page' => $page]]);

    $array = array(
        'headers' => array('Content-Type' => 'application/json; charset=utf-8'),
        'body' => json_encode(
                array(
                    'site' => $site,
                    'signature' => $signature,
                    'time' => $time,
                    'ip' => $ip,
                    'page' => $page,
                    'slug' => FIFU_CLIENT,
                    'version' => fifu_version_number()
                )
        ),
        'method' => 'POST',
        'data_format' => 'body',
        'blocking' => true,
        'timeout' => 30,
    );
    $response = fifu_remote_post(FIFU_SU_ADDRESS . '/list-all/', $array);
    if (is_wp_error($response))
        return json_decode(FIFU_TRY_AGAIN_LATER);

    // offline
    if ($response['http_response']->get_response_object()->status_code == 404)
        return json_decode(FIFU_TRY_AGAIN_LATER);

    $map = array();
    $posts = fifu_get_posts_su(null);
    foreach ($posts as $post)
        $map[$post->storage_id] = $post;

    $json = json_decode($response['http_response']->get_response_object()->body);
    if ($json && $json->code > 0) {
        for ($i = 0; $i < count($json->photo_data); $i++) {
            $post = $json->photo_data[$i];
            if (isset($map[$post->storage_id])) {
                $post->title = $map[$post->storage_id]->post_title;
                $post->meta_id = $map[$post->storage_id]->meta_id;
                $post->post_id = $map[$post->storage_id]->post_id;
                $post->meta_key = $map[$post->storage_id]->meta_key;
            } else
                $post->title = $post->meta_id = $post->post_id = $post->meta_key = '';
            $is_video = strpos($post->meta_key, 'video') !== false;
            $post->proxy_url = fifu_speedup_get_signed_url(null, 128, 128, $json->bucket_id, $post->storage_id, $is_video);
        }
    }
    return $json;
}

function fifu_api_list_daily_count(WP_REST_Request $request) {
    if (!fifu_su_sign_up_complete())
        return json_decode(FIFU_NO_CREDENTIALS);

    $time = time();
    $site = fifu_get_home_url();
    $tfa = $request['tfa'];
    $ip = fifu_get_ip();
    $signature = fifu_create_signature($site . $time . $ip . $tfa);

    fifu_cloud_log(['list_daily_count' => ['site' => $site]]);

    $array = array(
        'headers' => array('Content-Type' => 'application/json; charset=utf-8'),
        'body' => json_encode(
                array(
                    'site' => $site,
                    'signature' => $signature,
                    'time' => $time,
                    'ip' => $ip,
                    'slug' => FIFU_CLIENT,
                    'version' => fifu_version_number()
                )
        ),
        'method' => 'POST',
        'data_format' => 'body',
        'blocking' => true,
        'timeout' => 30,
    );
    $response = fifu_remote_post(FIFU_SU_ADDRESS . '/list-daily-count/', $array);
    if (is_wp_error($response))
        return json_decode(FIFU_TRY_AGAIN_LATER);

    // offline
    if ($response['http_response']->get_response_object()->status_code == 404)
        return json_decode(FIFU_TRY_AGAIN_LATER);

    $json = json_decode($response['http_response']->get_response_object()->body);
    return $json;
}

function fifu_api_cloud_upload_auto(WP_REST_Request $request) {
    if (!fifu_su_sign_up_complete())
        return json_decode(FIFU_NO_CREDENTIALS);

    $email = fifu_su_get_email();
    $site = fifu_get_home_url();
    $tfa = $request['tfa'];
    $ip = fifu_get_ip();
    $time = time();
    $signature = fifu_create_signature($site . $email . $time . $ip . $tfa);

    $enabled = $request['toggle'] == 'toggleon';

    fifu_cloud_log(['cloud_upload_auto' => ['site' => $site]]);

    $array = array(
        'headers' => array('Content-Type' => 'application/json; charset=utf-8'),
        'body' => json_encode(
                array(
                    'site' => $site,
                    'email' => $email,
                    'signature' => $signature,
                    'time' => $time,
                    'ip' => $ip,
                    'enabled' => $enabled,
                    'slug' => FIFU_CLIENT,
                    'version' => fifu_version_number()
                )
        ),
        'method' => 'POST',
        'data_format' => 'body',
        'blocking' => true,
        'timeout' => 30,
    );

    $response = fifu_remote_post(FIFU_SU_ADDRESS . '/upload-auto/', $array);
    if (is_wp_error($response))
        return json_decode(FIFU_TRY_AGAIN_LATER);

    $json = json_decode($response['http_response']->get_response_object()->body);
    $upload_auto_code = $json->upload_auto_code;

    if ($enabled)
        update_option('fifu_cloud_upload_auto_code', array($upload_auto_code));
    else
        delete_option('fifu_cloud_upload_auto_code');

    return $json;
}

function fifu_api_query($dataset) {
    $requests = array();

    $version = fifu_version_number();
    $site = fifu_get_home_url();

    foreach ($dataset as $data) {
        $post_id = $data[0];

        if (get_post_meta($post_id, 'fifu_dataset', true) == 2)
            continue;

        $old_url = $data[1];
        $new_url = $data[2];
        $title = $data[3];
        $permalink = $data[4];

        $time = time();
        $encoded_permalink = base64_encode($permalink);
        $permalink_sign = substr($encoded_permalink, -15);
        $signature = hash_hmac('sha256', $permalink_sign . $time, $new_url);

        array_push($requests,
                array(
                    'old_url' => base64_encode($old_url),
                    'new_url' => base64_encode($new_url),
                    'title' => base64_encode($title),
                    'permalink' => $encoded_permalink,
                    'time' => $time,
                    'signature' => $signature,
                    'version' => $version,
                    'site' => $site,
                    'premium' => false,
                )
        );
    }

    $array = array(
        'headers' => array('Content-Type' => 'application/json; charset=utf-8'),
        'body' => json_encode($requests),
        'method' => 'POST',
        'data_format' => 'body',
        'blocking' => true,
        'timeout' => 30,
    );
    $response = fifu_remote_post(FIFU_QUERY_ADDRESS, $array);
    if (is_wp_error($response))
        return null;

    $json = json_decode($response['http_response']->get_response_object()->body);
    if (isset($json->code) && in_array($json->code, array(200, 403))) {
        foreach ($dataset as $data) {
            $post_id = $data[0];
            update_post_meta($post_id, 'fifu_dataset', 2);
        }
    }
}

function fifu_get_storage_id($hex_id, $width, $height) {
    return $hex_id . '-' . $width . '-' . $height;
}

function fifu_api_list_all_fifu(WP_REST_Request $request) {
    $page = (int) $request['page'];
    $urls = fifu_db_get_all_urls($page);
    return $urls;
}

function fifu_api_list_all_media_library(WP_REST_Request $request) {
    if (!fifu_su_sign_up_complete())
        return null;

    $page = (int) $request['page'];
    return fifu_db_get_posts_with_internal_featured_image($page);
}

function fifu_enable_fake_api(WP_REST_Request $request) {
    update_option('fifu_fake_stop', false, 'no');
    fifu_enable_fake();
    set_transient('fifu_image_metadata_counter', fifu_db_count_urls_without_metadata(), 0);
    return json_encode(array());
}

function fifu_disable_fake_api(WP_REST_Request $request) {
    update_option('fifu_fake_created', false, 'no');
    update_option('fifu_fake_stop', true, 'no');
    set_transient('fifu_image_metadata_counter', fifu_db_count_urls_without_metadata(), 0);
    return json_encode(array());
}

function fifu_data_clean_api(WP_REST_Request $request) {
    fifu_db_enable_clean();
    update_option('fifu_data_clean', 'toggleoff', 'no');
    set_transient('fifu_image_metadata_counter', fifu_db_count_urls_without_metadata(), 0);
    return json_encode(array());
}

function fifu_run_delete_all_api(WP_REST_Request $request) {
    fifu_db_delete_all();
    update_option('fifu_run_delete_all', 'toggleoff', 'no');
    return json_encode(array());
}

function fifu_disable_default_api(WP_REST_Request $request) {
    fifu_db_delete_default_url();
    return json_encode(array());
}

function fifu_none_default_api(WP_REST_Request $request) {
    return json_encode(array());
}

function fifu_rest_url(WP_REST_Request $request) {
    return get_rest_url();
}

function fifu_save_sizes_api(WP_REST_Request $request) {
    $json = json_encode(array());

    $att_id = $request['att_id'];
    if (filter_var($att_id, FILTER_VALIDATE_INT) === false)
        return $json;

    $width = $request['width'];
    if (filter_var($width, FILTER_VALIDATE_INT) === false)
        return $json;

    $height = $request['height'];
    if (filter_var($height, FILTER_VALIDATE_INT) === false)
        return $json;

    $url = $request['url'];
    if (filter_var($url, FILTER_SANITIZE_URL) === false)
        return $json;

    $att_id = filter_var($att_id, FILTER_SANITIZE_SPECIAL_CHARS);

    if (!$att_id || !$width || !$height || !$url)
        return $json;

    $guid = get_the_guid($att_id);

    if ($url != $guid)
        return $json;

    if (get_post_field('post_author', $att_id) != FIFU_AUTHOR)
        return;

    // save
    $metadata = get_post_meta($att_id, '_wp_attachment_metadata', true);
    if (!$metadata || !$metadata['width'] || !$metadata['height']) {
        $metadata = null;
        $metadata['width'] = filter_var($width, FILTER_SANITIZE_SPECIAL_CHARS);
        $metadata['height'] = filter_var($height, FILTER_SANITIZE_SPECIAL_CHARS);
        wp_update_attachment_metadata($att_id, $metadata);
    }

    return $json;
}

function fifu_api_list_all_without_dimensions(WP_REST_Request $request) {
    return fifu_db_get_all_without_dimensions();
}

function fifu_run_get_and_save_sizes_api(WP_REST_Request $request) {
    $token = base64_encode(rand());
    set_transient('fifu_token_for_get_and_save_sizes_api', $token, 3600 * 24 * 7);
    $array_requests = array();
    $results = fifu_db_get_all_without_dimensions();
    $count = 1;
    foreach ($results as $res) {
        $url = $res->guid;
        $array = array(
            'url' => esc_url_raw(rest_url()) . 'featured-image-from-url/v2/get_and_save_sizes_api/',
            'type' => 'POST',
            'headers' => [
                'Accept' => 'application/json'
            ],
            'data' => json_encode([
                'att_id' => $res->ID,
                'url' => $url,
                'token' => $token
            ]),
        );
        array_push($array_requests, $array);
        if ($count % 10 == 0 || count($results) == $count) {
            $requests = Requests::request_multiple($array_requests);
            $array_requests = array();
            $count = 1;
        } else
            $count++;
    }
    delete_transient('fifu_token_for_get_and_save_sizes_api');
}

function fifu_get_and_save_sizes_api(WP_REST_Request $request) {
    $json = json_decode($request->get_Body());
    $att_id = $json->att_id;
    $token = $json->token;
    $url = $json->url;
    $imageSize = getImageSize($url);
    $width = $imageSize[0];
    $height = $imageSize[1];
    $array = array(
        'headers' => array('Content-Type' => 'application/json; charset=utf-8'),
        'body' => json_encode(
                array(
                    'att_id' => $att_id,
                    'width' => $width,
                    'height' => $height,
                    'url' => $url,
                    'token' => $token
                )
        ),
        'method' => 'POST',
        'data_format' => 'body',
        'blocking' => false,
        'timeout' => 30,
    );
    $response = fifu_remote_post(esc_url_raw(rest_url()) . 'featured-image-from-url/v2/save_sizes_api/', $array);
}

function fifu_api_pre_deactivate(WP_REST_Request $request) {
    $email = filter_var($request['email'], FILTER_SANITIZE_EMAIL);
    $email = filter_var($email, FILTER_VALIDATE_EMAIL) ? $email : null;
    $description = $request['description'];
    $temporary = filter_var($request['temporary'], FILTER_VALIDATE_BOOLEAN);
    fifu_send_feedback($email, $description, $temporary);
    fifu_db_enable_clean();
    deactivate_plugins('featured-image-from-url/featured-image-from-url.php');
    return json_encode(array());
}

function fifu_api_feedback(WP_REST_Request $request) {
    $email = filter_var($request['email'], FILTER_SANITIZE_EMAIL);
    $email = filter_var($email, FILTER_VALIDATE_EMAIL) ? $email : null;
    $description = $request['description'];
    $temporary = filter_var($request['temporary'], FILTER_VALIDATE_BOOLEAN);
    fifu_send_feedback($email, $description, $temporary);
    return json_encode(array());
}

function fifu_api_deactivate_itself(WP_REST_Request $request) {
    deactivate_plugins('featured-image-from-url/featured-image-from-url.php');
    return json_encode(array());
}

function fifu_send_feedback($email, $description, $temporary) {
    if (!$email && !$description)
        return json_encode(array());

    $aux = fifu_db_get_last_image();
    $image = $aux ? fifu_db_get_last_image()[0]->meta_value : null;

    $array = array(
        'headers' => array('Content-Type' => 'application/json; charset=utf-8'),
        'body' => json_encode(
                array(
                    'email' => $email,
                    'description' => $description,
                    'version' => fifu_version_number(),
                    'temporary' => $temporary,
                    'image' => $image,
                    'fifu_auto_alt' => fifu_is_on('fifu_auto_alt'),
                    'fifu_cdn_content' => fifu_is_on('fifu_cdn_content'),
                    'fifu_cdn_crop' => fifu_is_on('fifu_cdn_crop'),
                    'fifu_cdn_social' => fifu_is_on('fifu_cdn_social'),
                    'fifu_check' => fifu_is_on('fifu_check'),
                    'fifu_confirm_delete_all' => FIFU_DELETE_ALL_URLS,
                    'fifu_content' => fifu_is_on('fifu_content'),
                    'fifu_content_cpt' => fifu_is_on('fifu_content_cpt'),
                    'fifu_content_page' => fifu_is_on('fifu_content_page'),
                    'fifu_decode' => fifu_is_on('fifu_decode'),
                    'fifu_dynamic_alt' => fifu_is_on('fifu_dynamic_alt'),
                    'fifu_enable_default_url' => fifu_is_on('fifu_enable_default_url'),
                    'fifu_fake' => fifu_is_on('fifu_fake'),
                    'fifu_get_first' => fifu_is_on('fifu_get_first'),
                    'fifu_hide_cpt' => fifu_is_on('fifu_hide_cpt'),
                    'fifu_hide_page' => fifu_is_on('fifu_hide_page'),
                    'fifu_hide_post' => fifu_is_on('fifu_hide_post'),
                    'fifu_lazy' => fifu_is_on('fifu_lazy'),
                    'fifu_ovw_first' => fifu_is_on('fifu_ovw_first'),
                    'fifu_photon' => fifu_is_on('fifu_photon'),
                    'fifu_pop_first' => fifu_is_on('fifu_pop_first'),
                    'fifu_query_strings' => fifu_is_on('fifu_query_strings'),
                    'fifu_social' => fifu_is_on('fifu_social'),
                    'fifu_social_image_only' => fifu_is_on('fifu_social_image_only'),
                    'fifu_wc_lbox' => fifu_is_on('fifu_wc_lbox'),
                    'fifu_wc_zoom' => fifu_is_on('fifu_wc_zoom'),
                )
        ),
        'method' => 'POST',
        'data_format' => 'body',
        'blocking' => false,
        'timeout' => 30,
    );
    fifu_remote_post(FIFU_SURVEY_ADDRESS . '/deactivate/', $array);
}

function fifu_api_quick_edit_image_info(WP_REST_Request $request) {
    $post_id = $request['post_id'];

    $url = get_post_meta($post_id, 'fifu_image_url', true);
    $alt = get_post_meta($post_id, 'fifu_image_alt', true);

    return json_encode(array('image_url' => $url, 'image_alt' => $alt));
}

function fifu_test_execution_time() {
    for ($i = 0; $i <= 120; $i++) {
        error_log($i);
        sleep(1);
        //flush();
    }
    return json_encode(array());
}

add_action('rest_api_init', function () {
    register_rest_route('featured-image-from-url/v2', '/enable_fake_api/', array(
        'methods' => 'POST',
        'callback' => 'fifu_enable_fake_api',
        'permission_callback' => 'fifu_get_private_data_permissions_check',
    ));
    register_rest_route('featured-image-from-url/v2', '/disable_fake_api/', array(
        'methods' => 'POST',
        'callback' => 'fifu_disable_fake_api',
        'permission_callback' => 'fifu_get_private_data_permissions_check',
    ));
    register_rest_route('featured-image-from-url/v2', '/data_clean_api/', array(
        'methods' => 'POST',
        'callback' => 'fifu_data_clean_api',
        'permission_callback' => 'fifu_get_private_data_permissions_check',
    ));
    register_rest_route('featured-image-from-url/v2', '/run_delete_all_api/', array(
        'methods' => 'POST',
        'callback' => 'fifu_run_delete_all_api',
        'permission_callback' => 'fifu_get_private_data_permissions_check',
    ));
    register_rest_route('featured-image-from-url/v2', '/disable_default_api/', array(
        'methods' => 'POST',
        'callback' => 'fifu_disable_default_api',
        'permission_callback' => 'fifu_get_private_data_permissions_check',
    ));
    register_rest_route('featured-image-from-url/v2', '/none_default_api/', array(
        'methods' => 'POST',
        'callback' => 'fifu_none_default_api',
        'permission_callback' => 'fifu_get_private_data_permissions_check',
    ));
    register_rest_route('featured-image-from-url/v2', '/save_sizes_api/', array(
        'methods' => 'POST',
        'callback' => 'fifu_save_sizes_api',
        'permission_callback' => function ($request) {
            $json = json_decode($request->get_Body());
            return get_transient('fifu_token_for_get_and_save_sizes_api') == $json->token;
        },
    ));
    register_rest_route('featured-image-from-url/v2', '/list_all_without_dimensions/', array(
        'methods' => 'POST',
        'callback' => 'fifu_api_list_all_without_dimensions',
        'permission_callback' => 'fifu_get_private_data_permissions_check',
    ));
    register_rest_route('featured-image-from-url/v2', '/run_get_and_save_sizes_api/', array(
        'methods' => 'POST',
        'callback' => 'fifu_run_get_and_save_sizes_api',
        'permission_callback' => 'fifu_get_private_data_permissions_check',
    ));
    register_rest_route('featured-image-from-url/v2', '/get_and_save_sizes_api/', array(
        'methods' => 'POST',
        'callback' => 'fifu_get_and_save_sizes_api',
        'permission_callback' => function ($request) {
            $json = json_decode($request->get_Body());
            return get_transient('fifu_token_for_get_and_save_sizes_api') == $json->token;
        },
    ));
    register_rest_route('featured-image-from-url/v2', '/quick_edit_image_info_api/', array(
        'methods' => 'POST',
        'callback' => 'fifu_api_quick_edit_image_info',
        'permission_callback' => 'fifu_get_private_data_permissions_check',
    ));
    register_rest_route('featured-image-from-url/v2', '/pre_deactivate/', array(
        'methods' => 'POST',
        'callback' => 'fifu_api_pre_deactivate',
        'permission_callback' => 'fifu_get_private_data_permissions_check',
    ));
    register_rest_route('featured-image-from-url/v2', '/feedback/', array(
        'methods' => 'POST',
        'callback' => 'fifu_api_feedback',
        'permission_callback' => 'fifu_get_private_data_permissions_check',
    ));
    register_rest_route('featured-image-from-url/v2', '/deactivate_itself/', array(
        'methods' => 'POST',
        'callback' => 'fifu_api_deactivate_itself',
        'permission_callback' => 'fifu_get_private_data_permissions_check',
    ));
    register_rest_route('featured-image-from-url/v2', '/rest_url_api/', array(
        'methods' => ['GET', 'POST'],
        'callback' => 'fifu_rest_url',
        'permission_callback' => 'fifu_public_permission',
    ));

    register_rest_route('featured-image-from-url/v2', '/create_thumbnails_list/', array(
        'methods' => 'POST',
        'callback' => 'fifu_api_create_thumbnails_list',
        'permission_callback' => 'fifu_get_private_data_permissions_check',
    ));
    register_rest_route('featured-image-from-url/v2', '/sign_up/', array(
        'methods' => 'POST',
        'callback' => 'fifu_api_sign_up',
        'permission_callback' => 'fifu_get_private_data_permissions_check',
    ));
    register_rest_route('featured-image-from-url/v2', '/login/', array(
        'methods' => 'POST',
        'callback' => 'fifu_api_login',
        'permission_callback' => 'fifu_get_private_data_permissions_check',
    ));
    register_rest_route('featured-image-from-url/v2', '/logout/', array(
        'methods' => 'POST',
        'callback' => 'fifu_api_logout',
        'permission_callback' => 'fifu_get_private_data_permissions_check',
    ));
    register_rest_route('featured-image-from-url/v2', '/connected/', array(
        'methods' => 'POST',
        'callback' => 'fifu_api_connected',
        'permission_callback' => 'fifu_get_private_data_permissions_check',
    ));
    register_rest_route('featured-image-from-url/v2', '/reset_credentials/', array(
        'methods' => 'POST',
        'callback' => 'fifu_api_reset_credentials',
        'permission_callback' => 'fifu_get_private_data_permissions_check',
    ));
    register_rest_route('featured-image-from-url/v2', '/list_all_su/', array(
        'methods' => 'POST',
        'callback' => 'fifu_api_list_all_su',
        'permission_callback' => 'fifu_get_private_data_permissions_check',
    ));
    register_rest_route('featured-image-from-url/v2', '/list_all_fifu/', array(
        'methods' => 'POST',
        'callback' => 'fifu_api_list_all_fifu',
        'permission_callback' => 'fifu_get_private_data_permissions_check',
    ));
    register_rest_route('featured-image-from-url/v2', '/list_all_media_library/', array(
        'methods' => 'POST',
        'callback' => 'fifu_api_list_all_media_library',
        'permission_callback' => 'fifu_get_private_data_permissions_check',
    ));
    register_rest_route('featured-image-from-url/v2', '/list_daily_count/', array(
        'methods' => 'POST',
        'callback' => 'fifu_api_list_daily_count',
        'permission_callback' => 'fifu_get_private_data_permissions_check',
    ));
    register_rest_route('featured-image-from-url/v2', '/delete/', array(
        'methods' => 'POST',
        'callback' => 'fifu_api_delete',
        'permission_callback' => 'fifu_get_private_data_permissions_check',
    ));
    register_rest_route('featured-image-from-url/v2', '/cancel/', array(
        'methods' => 'POST',
        'callback' => 'fifu_api_cancel',
        'permission_callback' => 'fifu_get_private_data_permissions_check',
    ));
    register_rest_route('featured-image-from-url/v2', '/payment_info/', array(
        'methods' => 'POST',
        'callback' => 'fifu_api_payment_info',
        'permission_callback' => 'fifu_get_private_data_permissions_check',
    ));
    register_rest_route('featured-image-from-url/v2', '/cloud_upload_auto/', array(
        'methods' => 'POST',
        'callback' => 'fifu_api_cloud_upload_auto',
        'permission_callback' => 'fifu_get_private_data_permissions_check',
    ));
});

function fifu_get_private_data_permissions_check() {
    if (!current_user_can('edit_posts')) {
        return new WP_Error('rest_forbidden', __('Private'), array('status' => 401));
    }
    return true;
}

function fifu_public_permission() {
    return true;
}

