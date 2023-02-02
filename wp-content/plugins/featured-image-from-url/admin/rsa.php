<?php

// Encrypt the data to $encrypted using the public key:
// openssl_public_encrypt($data, $encrypted, $pubKey);
// Decrypt the data using the private key and store the results in $decrypted:
// openssl_private_decrypt($encrypted, $decrypted, $privKey);
// Encrypt the data to $encrypted using the private key:
// openssl_private_encrypt($data, $encrypted, $privKey, OPENSSL_PKCS1_PADDING);
// Decrypt the data using the public key and store the results in $decrypted:
// openssl_public_decrypt($encrypted, $decrypted, $pubKey);

function fifu_create_keys($email) {

    require_once(ABSPATH . '/wp-load.php');

    $config = array(
        "digest_alg" => "sha256",
        "private_key_bits" => 2048,
        "private_key_type" => OPENSSL_KEYTYPE_RSA,
    );

    // Create the private and public key
    $res = openssl_pkey_new($config);

    // Extract the private key from $res to $privKey
    openssl_pkey_export($res, $privKey);

    // Extract the public key from $res to $pubKey
    $pubKey = openssl_pkey_get_details($res);
    $pubKey = $pubKey["key"];

    // Store key
    update_option('fifu_su_email', array(base64_encode($email)), 'no');
    update_option('fifu_su_privkey', array(base64_encode(openssl_encrypt($privKey, "AES-128-ECB", $email . fifu_get_home_url()))), 'no');

    return base64_encode($pubKey);
}

function fifu_create_signature($data) {
    // Recover key
    $email = base64_decode(get_option('fifu_su_email')[0]);
    $privKey = openssl_decrypt(base64_decode(get_option('fifu_su_privkey')[0]), "AES-128-ECB", $email . fifu_get_home_url());

    // $data is assumed to contain the data to be signed
    // fetch private key from file and ready it
    $pkeyid = openssl_pkey_get_private($privKey);

    // compute signature
    openssl_sign($data, $signature, $privKey, OPENSSL_ALGO_SHA256);

    return base64_encode($signature);
}

function fifu_create_hash($data) {
    $license_key = get_option('fifu_key');
    return hash_hmac('sha256', $data, $license_key);
}

