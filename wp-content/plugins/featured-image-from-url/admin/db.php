<?php

class FifuDb {

    private $posts;
    private $postmeta;
    private $termmeta;
    private $term_taxonomy;
    private $term_relationships;
    private $query;
    private $wpdb;
    private $author;

    function __construct() {
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->posts = $wpdb->prefix . 'posts';
        $this->options = $wpdb->prefix . 'options';
        $this->postmeta = $wpdb->prefix . 'postmeta';
        $this->terms = $wpdb->prefix . 'terms';
        $this->termmeta = $wpdb->prefix . 'termmeta';
        $this->term_taxonomy = $wpdb->prefix . 'term_taxonomy';
        $this->term_relationships = $wpdb->prefix . 'term_relationships';
        $this->author = 77777;
        $this->MAX_INSERT = 1500;
        $this->MAX_URL_LENGTH = 2048;
        $this->types = $this->get_types();
    }

    function get_types() {
        $post_types = fifu_get_post_types();
        return join("','", $post_types);
    }

    /* alter table */

    function change_url_length() {
        $length = $this->wpdb->get_col_length($this->posts, 'guid');
        if ($length && $length['length'] >= $this->MAX_URL_LENGTH)
            return;

        $this->wpdb->get_results("
            ALTER TABLE " . $this->posts . "
            MODIFY COLUMN guid VARCHAR(" . $this->MAX_URL_LENGTH . ")"
        );
    }

    /* wordpress upgrade */

    function fix_guid() {
        $this->wpdb->get_results("
            UPDATE " . $this->posts . " p 
            INNER JOIN " . $this->postmeta . " pm ON (
                pm.post_id = p.id 
                AND	pm.meta_key = '_wp_attached_file'
            )
            SET p.guid = pm.meta_value
            WHERE p.post_author = " . $this->author . "  
            AND LENGTH(p.guid) = 255"
        );
    }

    /* attachment metadata */

    // insert 1 _wp_attached_file for each attachment
    function insert_attachment_meta_url($ids, $is_ctgr) {
        $ctgr_sql = $is_ctgr ? "AND p.post_name LIKE 'fifu-category%'" : "";

        $this->wpdb->get_results("
            INSERT INTO " . $this->postmeta . " (post_id, meta_key, meta_value) (
                SELECT p.id, '_wp_attached_file', p.guid
                FROM " . $this->posts . " p LEFT OUTER JOIN " . $this->postmeta . " b ON p.id = b.post_id AND meta_key = '_wp_attached_file'
                WHERE b.post_id IS NULL
                AND p.post_parent IN (" . $ids . ") 
                AND p.post_type = 'attachment' 
                AND p.post_author = " . $this->author . " 
                " . $ctgr_sql . " 
            )"
        );
    }

    // delete 1 _wp_attached_file or _wp_attachment_image_alt for each attachment
    function delete_attachment_meta($ids, $is_ctgr) {
        $ctgr_sql = $is_ctgr ? "AND p.post_name LIKE 'fifu-category%'" : "";

        $this->wpdb->get_results("
            DELETE FROM " . $this->postmeta . "
            WHERE meta_key IN ('_wp_attached_file', '_wp_attachment_image_alt', '_wp_attachment_metadata')
            AND EXISTS (
                SELECT 1 
                FROM " . $this->posts . " p
                WHERE p.id = post_id
                AND p.post_parent IN (" . $ids . ")
                AND p.post_type = 'attachment' 
                AND p.post_author = " . $this->author . " 
                " . $ctgr_sql . " 
            )"
        );
    }

    // insert 1 _wp_attachment_image_alt for each attachment
    function insert_attachment_meta_alt($ids, $is_ctgr) {
        if (fifu_is_off('fifu_auto_alt'))
            return;

        $ctgr_sql = $is_ctgr ? "AND p.post_name LIKE 'fifu-category%'" : "";

        $this->wpdb->get_results("
            INSERT INTO " . $this->postmeta . " (post_id, meta_key, meta_value) (
                SELECT p.id, '_wp_attachment_image_alt', p.post_title 
                FROM " . $this->posts . " p LEFT OUTER JOIN " . $this->postmeta . " b ON p.id = b.post_id AND meta_key = '_wp_attachment_image_alt'
                WHERE b.post_id IS NULL
                AND p.post_parent IN (" . $ids . ") 
                AND p.post_type = 'attachment' 
                AND p.post_author = " . $this->author . " 
                " . $ctgr_sql . "
            )"
        );
    }

    // insert 1 _thumbnail_id for each attachment (posts)
    function insert_thumbnail_id($ids, $is_ctgr) {
        $ctgr_sql = $is_ctgr ? "AND p.post_name LIKE 'fifu-category%'" : "";

        $this->wpdb->get_results("
            INSERT INTO " . $this->postmeta . " (post_id, meta_key, meta_value) (
                SELECT p.post_parent, '_thumbnail_id', p.id 
                FROM " . $this->posts . " p LEFT OUTER JOIN " . $this->postmeta . " b ON p.post_parent = b.post_id AND meta_key = '_thumbnail_id'
                WHERE b.post_id IS NULL
                AND p.post_parent IN (" . $ids . ") 
                AND p.post_type = 'attachment' 
                AND p.post_author = " . $this->author . " 
                " . $ctgr_sql . " 
            )"
        );
    }

    // has attachment created by FIFU
    function is_fifu_attachment($att_id) {
        return $this->wpdb->get_row("
            SELECT 1 
            FROM " . $this->posts . " 
            WHERE id = " . $att_id . " 
            AND post_author = " . $this->author
                ) != null;
    }

    // get ids from categories with external media and no thumbnail_id
    function get_categories_without_meta() {
        return $this->wpdb->get_results("
            SELECT DISTINCT term_id
            FROM " . $this->termmeta . " a
            WHERE a.meta_key IN ('fifu_image_url')
            AND a.meta_value IS NOT NULL 
            AND a.meta_value <> ''
            AND NOT EXISTS (
                SELECT 1 
                FROM " . $this->termmeta . " b 
                WHERE a.term_id = b.term_id 
                AND b.meta_key = 'thumbnail_id'
                AND b.meta_value <> 0
            )"
        );
    }

    // get ids from posts with external media and no _thumbnail_id
    function get_posts_without_meta() {
        return $this->wpdb->get_results("
            SELECT DISTINCT post_id
            FROM " . $this->postmeta . " a
            WHERE a.meta_key IN ('fifu_image_url')
            AND a.meta_value IS NOT NULL 
            AND a.meta_value <> ''
            AND NOT EXISTS (
                SELECT 1 
                FROM (SELECT post_id FROM " . $this->postmeta . " WHERE meta_key = '_thumbnail_id') AS b
                WHERE a.post_id = b.post_id 
            )"
        );
    }

    // get ids from posts with external url
    function get_posts_with_url() {
        return $this->wpdb->get_results("
            SELECT post_id 
            FROM " . $this->postmeta . " 
            WHERE meta_key = 'fifu_image_url'"
        );
    }

    // get ids from terms with external url
    function get_terms_with_url() {
        return $this->wpdb->get_results("
            SELECT term_id 
            FROM " . $this->termmeta . " 
            WHERE meta_key = 'fifu_image_url'
            AND meta_value <> ''
            AND meta_value IS NOT NULL"
        );
    }

    // get ids from fake attachments
    function get_fake_attachments() {
        return $this->wpdb->get_results("
            SELECT id 
            FROM " . $this->posts . " 
            WHERE post_type = 'attachment' 
            AND post_author = " . $this->author
        );
    }

    // get att_id by post and url
    function get_att_id($post_parent, $url, $is_ctgr) {
        $ctgr_sql = $is_ctgr ? "AND p.post_name LIKE 'fifu-category%'" : "";

        $result = $this->wpdb->get_results("
            SELECT p.id 
            FROM " . $this->posts . " p 
            WHERE p.post_parent = " . $post_parent . "
            AND p.guid = '" . $url . "' 
            AND post_author = " . $this->author . "
            " . $ctgr_sql . " 
            LIMIT 1"
        );
        return $result ? $result[0]->id : null;
    }

    // get posts without dimensions
    function get_posts_without_dimensions() {
        return $this->wpdb->get_results("
            SELECT p.ID, p.guid
            FROM " . $this->posts . " p LEFT OUTER JOIN " . $this->postmeta . " b ON p.id = b.post_id AND meta_key = '_wp_attachment_metadata'
            WHERE b.post_id IS NULL
            AND p.post_type = 'attachment' 
            AND p.post_author = " . $this->author . "
            AND p.post_status NOT IN ('auto-draft', 'trash')
            ORDER BY p.id DESC"
        );
    }

    // count images without dimensions
    function get_count_posts_without_dimensions() {
        return $this->wpdb->get_results("
            SELECT COUNT(1) AS amount
            FROM " . $this->posts . " p LEFT OUTER JOIN " . $this->postmeta . " b ON p.id = b.post_id AND meta_key = '_wp_attachment_metadata'
            WHERE b.post_id IS NULL
            AND p.post_type = 'attachment' 
            AND p.post_author = " . $this->author
        );
    }

    // count urls with metadata
    function get_count_urls_with_metadata() {
        return $this->wpdb->get_results("
            SELECT COUNT(1) AS amount
            FROM " . $this->posts . " p
            WHERE p.post_author = " . $this->author . ""
        );
    }

    // count urls
    function get_count_urls() {
        return $this->wpdb->get_results("
            SELECT SUM(id) AS amount
            FROM (
                SELECT count(post_id) AS id
                FROM " . $this->postmeta . " pm
                WHERE pm.meta_key LIKE 'fifu_%'
                AND pm.meta_key LIKE '%url%'
                AND pm.meta_key NOT LIKE '%list%'
                UNION 
                SELECT count(term_id) AS id
                FROM " . $this->termmeta . " tm
                WHERE tm.meta_key LIKE 'fifu_%'
                AND tm.meta_key LIKE '%url%'
            ) x"
        );
    }

    // count urls without metadata
    function get_count_urls_without_metadata() {
        return $this->wpdb->get_results("
            SELECT SUM(amount) AS amount
            FROM (
                SELECT COUNT(1) AS amount
                FROM " . $this->postmeta . " pm
                WHERE pm.meta_key LIKE 'fifu_%'
                AND pm.meta_key LIKE '%url%'
                AND pm.meta_key NOT LIKE '%list%'
                UNION 
                SELECT COUNT(1) AS amount
                FROM " . $this->termmeta . " tm
                WHERE tm.meta_key LIKE 'fifu_%'
                AND tm.meta_key LIKE '%url%'
                UNION
                SELECT -COUNT(1) AS amount
                FROM " . $this->posts . " p
                WHERE p.post_author = " . $this->author . "
            ) x"
        );
    }

    // guid size
    function get_guid_size() {
        return $this->wpdb->get_col_length($this->posts, 'guid')['length'];
    }

    // get last (images/videos/sliders)
    function get_last($meta_key) {
        return $this->wpdb->get_results("
            SELECT p.id, pm.meta_value
            FROM " . $this->posts . " p
            INNER JOIN " . $this->postmeta . " pm ON p.id = pm.post_id
            WHERE pm.meta_key = '" . $meta_key . "'
            ORDER BY p.post_date DESC
            LIMIT 3"
        );
    }

    function get_last_image() {
        return $this->wpdb->get_results("
            SELECT pm.meta_value
            FROM " . $this->postmeta . " pm 
            WHERE pm.meta_key = 'fifu_image_url'
            ORDER BY pm.meta_id DESC
            LIMIT 1"
        );
    }

    // get attachments without post
    function get_attachments_without_post($post_id) {
        $result = $this->wpdb->get_results("
            SELECT GROUP_CONCAT(id) AS ids 
            FROM " . $this->posts . " 
            WHERE post_parent = " . $post_id . " 
            AND post_type = 'attachment' 
            AND post_author = " . $this->author . "
            AND post_name NOT LIKE 'fifu-category%' 
            AND NOT EXISTS (
	            SELECT 1
                FROM " . $this->postmeta . "
                WHERE post_id = post_parent
                AND meta_key = '_thumbnail_id'
                AND meta_value = id
            )
            GROUP BY post_parent"
        );
        return $result ? $result[0]->ids : null;
    }

    function get_ctgr_attachments_without_post($term_id) {
        $result = $this->wpdb->get_results("
            SELECT GROUP_CONCAT(id) AS ids 
            FROM " . $this->posts . " 
            WHERE post_parent = " . $term_id . " 
            AND post_type = 'attachment' 
            AND post_author = " . $this->author . " 
            AND post_name LIKE 'fifu-category%' 
            AND NOT EXISTS (
	            SELECT 1
                FROM " . $this->termmeta . "
                WHERE term_id = post_parent
                AND meta_key = 'thumbnail_id'
                AND meta_value = id
            )
            GROUP BY post_parent"
        );
        return $result ? $result[0]->ids : null;
    }

    function get_posts_without_featured_image($post_types) {
        return $this->wpdb->get_results("
            SELECT id, post_title
            FROM " . $this->posts . " 
            WHERE post_type IN ('$post_types')
            AND post_status = 'publish'
            AND NOT EXISTS (
                SELECT 1
                FROM " . $this->postmeta . " 
                WHERE post_id = id
                AND meta_key IN ('_thumbnail_id', 'fifu_image_url')
            )
            ORDER BY id DESC"
        );
    }

    function get_number_of_posts() {
        return $this->wpdb->get_row("
            SELECT count(1) AS n
            FROM " . $this->posts . " 
            WHERE post_type IN ('$this->types')
            AND post_status = 'publish'"
                )->n;
    }

    function get_category_image_url($term_id) {
        return $this->wpdb->get_results("
            SELECT meta_value 
            FROM " . $this->termmeta . " 
            WHERE meta_key = 'fifu_image_url' 
            AND term_id = " . $term_id
        );
    }

    function get_featured_and_gallery_ids($post_id) {
        return $this->wpdb->get_results("
            SELECT GROUP_CONCAT(meta_value SEPARATOR ',') as 'ids'
            FROM " . $this->postmeta . "
            WHERE post_id = " . $post_id . "
            AND meta_key IN ('_thumbnail_id')"
        );
    }

    function insert_default_thumbnail_id($value) {
        $this->wpdb->get_results("
            INSERT INTO " . $this->postmeta . " (post_id, meta_key, meta_value)
            VALUES " . $value
        );
    }

    // clean metadata

    function delete_thumbnail_ids($ids) {
        $this->wpdb->get_results("
            DELETE FROM " . $this->postmeta . " 
            WHERE meta_key = '_thumbnail_id' 
            AND meta_value IN (" . $ids . ")"
        );
    }

    function delete_thumbnail_ids_category($ids) {
        $this->wpdb->get_results("
            DELETE FROM " . $this->termmeta . " 
            WHERE meta_key = 'thumbnail_id' 
            AND term_id IN (" . $ids . ")"
        );
    }

    function delete_thumbnail_ids_category_without_attachment() {
        $this->wpdb->get_results("
            DELETE FROM " . $this->termmeta . " 
            WHERE meta_key = 'thumbnail_id' 
            AND NOT EXISTS (
                SELECT 1 
                FROM " . $this->posts . " p 
                WHERE p.id = meta_value
            )"
        );
    }

    function delete_invalid_thumbnail_ids($ids) {
        $this->wpdb->get_results("
            DELETE FROM " . $this->postmeta . " 
            WHERE meta_key = '_thumbnail_id' 
            AND post_id IN (" . $ids . ") 
            AND (
                meta_value = -1 
                OR meta_value IS NULL 
                OR meta_value LIKE 'fifu:%'
            )"
        );
    }

    function delete_fake_thumbnail_id($ids) {
        $att_id = get_option('fifu_fake_attach_id');
        if ($att_id) {
            $this->wpdb->get_results("
                DELETE FROM " . $this->postmeta . " 
                WHERE meta_key = '_thumbnail_id' 
                AND post_id IN (" . $ids . ") 
                AND meta_value = " . $att_id
            );
        }
    }

    function delete_attachments($ids) {
        $this->wpdb->get_results("
            DELETE FROM " . $this->posts . " 
            WHERE id IN (" . $ids . ")
            AND post_type = 'attachment'
            AND post_author = " . $this->author
        );
    }

    function delete_attachment_meta_url_and_alt($ids) {
        $this->wpdb->get_results("
            DELETE FROM " . $this->postmeta . " 
            WHERE meta_key IN ('_wp_attached_file', '_wp_attachment_image_alt', '_wp_attachment_metadata')
            AND post_id IN (" . $ids . ")
            AND EXISTS (
                SELECT 1 
                FROM " . $this->posts . " 
                WHERE id = post_id 
                AND post_author = " . $this->author . "
            )"
        );
    }

    function delete_attachment_meta_url($ids) {
        $this->wpdb->get_results("
            DELETE FROM " . $this->postmeta . " 
            WHERE meta_key = '_wp_attached_file' 
            AND post_id IN (" . $ids . ")"
        );
    }

    function delete_thumbnail_id_without_attachment() {
        if (fifu_is_multisite_global_media_active()) {
            $this->wpdb->get_results("
                DELETE FROM " . $this->postmeta . " 
                WHERE meta_key = '_thumbnail_id' 
                AND meta_value NOT LIKE '100000%' 
                AND NOT EXISTS (
                    SELECT 1 
                    FROM " . $this->posts . " p 
                    WHERE p.id = meta_value
                )"
            );
            return;
        }

        $this->wpdb->get_results("
            DELETE FROM " . $this->postmeta . " 
            WHERE meta_key = '_thumbnail_id' 
            AND NOT EXISTS (
                SELECT 1 
                FROM " . $this->posts . " p 
                WHERE p.id = meta_value
            )"
        );
    }

    function delete_attachment_meta_without_attachment() {
        $this->wpdb->get_results("
            DELETE FROM " . $this->postmeta . " 
            WHERE meta_key IN ('_wp_attached_file', '_wp_attachment_image_alt', '_wp_attachment_metadata') 
            AND NOT EXISTS (
                SELECT 1
                FROM " . $this->posts . " p 
                WHERE p.id = post_id
            )"
        );
    }

    function delete_empty_urls_category() {
        $this->wpdb->get_results("
            DELETE FROM " . $this->termmeta . " 
            WHERE meta_key = 'fifu_image_url'
            AND (
                meta_value = ''
                OR meta_value is NULL
            )"
        );
    }

    function delete_empty_urls() {
        $this->wpdb->get_results("
            DELETE FROM " . $this->postmeta . " 
            WHERE meta_key = 'fifu_image_url'
            AND (
                meta_value = ''
                OR meta_value is NULL
            )"
        );
    }

    function delete_metadata() {
        $fake_attach_id = get_option('fifu_fake_attach_id');
        $default_attach_id = get_option('fifu_default_attach_id');
        $value = '-1';
        $value = $fake_attach_id ? $value . ',' . $fake_attach_id : $value;
        $value = $default_attach_id ? $value . ',' . $default_attach_id : $value;
        $this->wpdb->get_results("
            DELETE FROM " . $this->postmeta . " 
            WHERE meta_key IN ('_thumbnail_id', '_product_image_gallery')
            AND meta_value IN (" . $value . ")"
        );
        $this->wpdb->get_results("
            DELETE FROM " . $this->postmeta . " 
            WHERE meta_key = 'fifu_image_dimension'"
        );
    }

    /* speed up */

    function get_all_urls($page) {
        $start = $page * 1000;

        $sql = "
            (
                SELECT pm.meta_id, pm.post_id, pm.meta_value AS url, pm.meta_key, p.post_name, p.post_title, p.post_date, false AS category, null AS video_url
                FROM " . $this->postmeta . " pm
                INNER JOIN " . $this->posts . " p ON pm.post_id = p.id
                WHERE pm.meta_key = 'fifu_image_url'
                AND pm.meta_value NOT LIKE '%https://cdn.fifu.app/%'
                AND pm.meta_value NOT LIKE 'http://localhost/%'
                AND p.post_status <> 'trash'
            )
        ";
        if (class_exists('WooCommerce')) {
            $sql .= " 
                UNION
                (
                    SELECT tm.meta_id, tm.term_id AS post_id, tm.meta_value AS url, tm.meta_key, null AS post_name, t.name AS post_title, null AS post_date, true AS category, null AS video_url
                    FROM " . $this->termmeta . " tm
                    INNER JOIN " . $this->terms . " t ON tm.term_id = t.term_id
                    WHERE tm.meta_key IN ('fifu_image_url')
                    AND tm.meta_value NOT LIKE '%https://cdn.fifu.app/%'
                    AND tm.meta_value NOT LIKE 'http://localhost/%'
                )
            ";
        }
        $sql .= " 
            ORDER BY post_id DESC
            LIMIT {$start},1000
        ";
        return $this->wpdb->get_results($sql);
    }

    function get_posts_with_internal_featured_image($page) {
        $start = $page * 1000;

        $sql = "
            (
                SELECT 
                    pm.post_id, 
                    att.guid AS url, 
                    p.post_name, 
                    p.post_title, 
                    p.post_date, 
                    att.id AS thumbnail_id,
                    (SELECT meta_value FROM " . $this->postmeta . " pm2 WHERE pm2.post_id = pm.post_id AND pm2.meta_key = '_product_image_gallery') AS gallery_ids,
                    false AS category
                FROM " . $this->postmeta . " pm
                INNER JOIN " . $this->posts . " p ON pm.post_id = p.id
                INNER JOIN " . $this->posts . " att ON (
                    pm.meta_key = '_thumbnail_id'
                    AND pm.meta_value = att.id
                    AND att.post_author <> " . $this->author . "
                )
                WHERE NOT EXISTS (
                    SELECT 1
                    FROM " . $this->postmeta . "
                    WHERE post_id = pm.post_id
                    AND (meta_key LIKE 'fifu_%image_url%' OR meta_key IN ('bkp_thumbnail_id', 'bkp_product_image_gallery'))
                )
                AND (
                    SELECT COUNT(1)
                    FROM " . $this->postmeta . "
                    WHERE post_id = pm.post_id
                    AND meta_key = '_product_image_gallery'
                ) <= 1
                AND p.post_status <> 'trash'
            )
        ";
        if (class_exists('WooCommerce')) {
            $sql .= " 
                UNION 
                (
                    SELECT
                        tm.term_id AS post_id, 
                        att.guid AS url, 
                        null AS post_name, 
                        t.name AS post_title, 
                        null AS post_date, 
                        att.id AS thumbnail_id,
                        null AS gallery_ids,
                        true AS category
                    FROM " . $this->termmeta . " tm
                    INNER JOIN " . $this->terms . " t ON tm.term_id = t.term_id
                    INNER JOIN " . $this->posts . " att ON (
                        tm.meta_key = 'thumbnail_id'
                        AND tm.meta_value = att.id
                        AND att.post_author <> " . $this->author . "
                    )
                    WHERE NOT EXISTS (
                        SELECT 1
                        FROM " . $this->termmeta . "
                        WHERE term_id = tm.term_id
                        AND (meta_key = 'fifu_image_url' OR meta_key = 'bkp_thumbnail_id')
                    )
                )
            ";
        }
        $sql .= " 
            ORDER BY post_id DESC
            LIMIT {$start},1000
        ";
        return $this->wpdb->get_results($sql);
    }

    function get_posts_su($storage_ids) {
        if ($storage_ids) {
            $storage_ids = '"' . implode('","', $storage_ids) . '"';
            $filter_post_image = "AND SUBSTRING_INDEX(SUBSTRING_INDEX(pm.meta_value, '/', 5), '/', -1) IN ({$storage_ids})";
            $filter_term_image = "AND SUBSTRING_INDEX(SUBSTRING_INDEX(tm.meta_value, '/', 5), '/', -1) IN ({$storage_ids})";
        } else
            $filter_post_image = $filter_term_image = "";

        $sql = "
            (
                SELECT SUBSTRING_INDEX(SUBSTRING_INDEX(pm.meta_value, '/', 5), '/', -1) AS storage_id, 
                    p.post_title, 
                    p.post_date, 
                    pm.meta_id, 
                    pm.post_id, 
                    pm.meta_key, 
                    false AS category
                FROM " . $this->postmeta . " pm
                INNER JOIN " . $this->posts . " p ON pm.post_id = p.id
                WHERE pm.meta_key LIKE 'fifu_%image_url%'
                AND pm.meta_value LIKE 'https://cdn.fifu.app/%'" .
                $filter_post_image . "
            )
        ";
        if (class_exists('WooCommerce')) {
            $sql .= "            
                UNION
                (
                    SELECT SUBSTRING_INDEX(SUBSTRING_INDEX(tm.meta_value, '/', 5), '/', -1) AS storage_id, 
                        t.name AS post_title, 
                        null AS post_date, 
                        tm.meta_id, 
                        tm.term_id AS post_id, 
                        tm.meta_key, 
                        true AS category
                    FROM " . $this->termmeta . " tm
                    INNER JOIN " . $this->terms . " t ON tm.term_id = t.term_id
                    WHERE tm.meta_key = 'fifu_image_url'
                    AND tm.meta_value LIKE 'https://cdn.fifu.app/%'" .
                    $filter_term_image . "
                )
            ";
        }
        return $this->wpdb->get_results($sql);
    }

    /* speed up (add) */

    function add_urls_su($bucket_id, $thumbnails) {
        // custom field
        $this->speed_up_custom_fields($bucket_id, $thumbnails, false);

        // two groups
        $featured_list = array();
        foreach ($thumbnails as $thumbnail) {
            if ($thumbnail->meta_key == 'fifu_image_url')
                array_push($featured_list, $thumbnail);
        }

        // featured group
        if (count($featured_list) > 0) {
            $att_ids_map = $this->get_thumbnail_ids($featured_list, false);
            if (count($att_ids_map) > 0) {
                $this->speed_up_attachments($bucket_id, $featured_list, $att_ids_map);
                $meta_ids_map = $this->get_thumbnail_meta_ids($featured_list, $att_ids_map);
                if (count($meta_ids_map) > 0)
                    $this->speed_up_attachments_meta($bucket_id, $featured_list, $meta_ids_map);
            }
        }
    }

    function ctgr_add_urls_su($bucket_id, $thumbnails) {
        // custom field
        $this->speed_up_custom_fields($bucket_id, $thumbnails, true);

        $featured_list = array();
        foreach ($thumbnails as $thumbnail)
            array_push($featured_list, $thumbnail);

        // featured group
        if (count($featured_list) > 0) {
            $att_ids_map = $this->get_thumbnail_ids($featured_list, true);
            if (count($att_ids_map) > 0) {
                $this->speed_up_attachments($bucket_id, $featured_list, $att_ids_map);
                $meta_ids_map = $this->get_thumbnail_meta_ids($featured_list, $att_ids_map);
                if (count($meta_ids_map) > 0)
                    $this->speed_up_attachments_meta($bucket_id, $featured_list, $meta_ids_map);
            }
        }
    }

    function get_su_url($bucket_id, $storage_id) {
        return 'https://cdn.fifu.app/' . $bucket_id . '/' . $storage_id;
    }

    function speed_up_custom_fields($bucket_id, $thumbnails, $is_ctgr) {
        $table = $is_ctgr ? $this->termmeta : $this->postmeta;

        $query = "
            INSERT INTO " . $table . " (meta_id, meta_value) VALUES ";
        $count = 0;
        foreach ($thumbnails as $thumbnail) {
            $su_url = $this->get_su_url($bucket_id, $thumbnail->storage_id);

            if ($count++ != 0)
                $query .= ", ";
            $query .= "(" . $thumbnail->meta_id . ",'" . $su_url . "') ";
        }
        $query .= "ON DUPLICATE KEY UPDATE meta_value=VALUES(meta_value)";
        return $this->wpdb->get_results($query);
    }

    function get_thumbnail_ids($thumbnails, $is_ctgr) {
        // join post_ids
        $i = 0;
        $ids = null;
        foreach ($thumbnails as $thumbnail)
            $ids = ($i++ == 0) ? $thumbnail->post_id : ($ids . "," . $thumbnail->post_id);

        // get featured ids
        if ($is_ctgr) {
            $result = $this->wpdb->get_results("
                SELECT term_id AS post_id, meta_value AS att_id
                FROM " . $this->termmeta . " 
                WHERE term_id IN (" . $ids . ") 
                AND meta_key = 'thumbnail_id'"
            );
        } else {
            $result = $this->wpdb->get_results("
                SELECT post_id, meta_value AS att_id
                FROM " . $this->postmeta . " 
                WHERE post_id IN (" . $ids . ") 
                AND meta_key = '_thumbnail_id'"
            );
        }

        // map featured ids
        $featured_map = array();
        foreach ($result as $res)
            $featured_map[$res->post_id] = $res->att_id;

        // map thumbnails
        $map = array();
        foreach ($thumbnails as $thumbnail) {
            if (isset($featured_map[$thumbnail->post_id])) {
                $att_id = $featured_map[$thumbnail->post_id];
                $map[$thumbnail->meta_id] = $att_id;
            }
        }
        // meta_id -> att_id
        return $map;
    }

    function speed_up_attachments($bucket_id, $thumbnails, $att_ids_map) {
        $count = 0;
        $query = "
            INSERT INTO " . $this->posts . " (id, guid) VALUES ";
        foreach ($thumbnails as $thumbnail) {
            if (!isset($att_ids_map[$thumbnail->meta_id])) // no metadata, only custom field
                continue;

            $su_url = $this->get_su_url($bucket_id, $thumbnail->storage_id);

            if ($count++ != 0)
                $query .= ", ";
            $query .= "(" . $att_ids_map[$thumbnail->meta_id] . ",'" . $su_url . "') ";
        }
        $query .= "ON DUPLICATE KEY UPDATE guid=VALUES(guid)";
        return $this->wpdb->get_results($query);
    }

    function get_thumbnail_meta_ids($thumbnails, $att_ids_map) {
        // join post_ids
        $i = 0;
        $ids = null;
        foreach ($thumbnails as $thumbnail) {
            if (!isset($att_ids_map[$thumbnail->meta_id])) // no metadata, only custom field
                continue;
            $ids = ($i++ == 0) ? $att_ids_map[$thumbnail->meta_id] : ($ids . "," . $att_ids_map[$thumbnail->meta_id]);
        }

        // get meta ids
        $result = $this->wpdb->get_results("
            SELECT meta_id, post_id
            FROM " . $this->postmeta . " 
            WHERE post_id IN (" . $ids . ") 
            AND meta_key = '_wp_attached_file'"
        );

        // map att_id -> meta_id
        $attid_metaid_map = array();
        foreach ($result as $res)
            $attid_metaid_map[$res->post_id] = $res->meta_id;

        // map meta_id (fifu metadata) -> meta_id (atachment metadata)
        $map = array();
        foreach ($thumbnails as $thumbnail) {
            if (!isset($att_ids_map[$thumbnail->meta_id])) // no metadata, only custom field
                continue;
            $att_meta_id = $attid_metaid_map[$att_ids_map[$thumbnail->meta_id]];
            $map[$thumbnail->meta_id] = $att_meta_id;
        }
        return $map;
    }

    function speed_up_attachments_meta($bucket_id, $thumbnails, $meta_ids_map) {
        $count = 0;
        $query = "
            INSERT INTO " . $this->postmeta . " (meta_id, meta_value) VALUES ";
        foreach ($thumbnails as $thumbnail) {
            if (!isset($meta_ids_map[$thumbnail->meta_id])) // no metadata, only custom field
                continue;

            $su_url = $this->get_su_url($bucket_id, $thumbnail->storage_id);

            if ($count++ != 0)
                $query .= ", ";
            $query .= "(" . $meta_ids_map[$thumbnail->meta_id] . ",'" . $su_url . "') ";
        }
        $query .= "ON DUPLICATE KEY UPDATE meta_value=VALUES(meta_value)";
        return $this->wpdb->get_results($query);
    }

    /* speed up (remove) */

    function remove_urls_su($bucket_id, $thumbnails, $urls, $video_urls) {
        foreach ($thumbnails as $thumbnail) {
            // post removed
            if (!$thumbnail->meta_id)
                unset($urls[$thumbnail->storage_id]);
        }

        if (empty($urls))
            return;

        // custom field
        $this->revert_custom_fields($thumbnails, $urls, $video_urls, false);

        // two groups
        $featured_list = array();
        foreach ($thumbnails as $thumbnail) {
            if ($thumbnail->meta_key == 'fifu_image_url')
                array_push($featured_list, $thumbnail);
        }

        // featured group
        if (count($featured_list) > 0) {
            $att_ids_map = $this->get_thumbnail_ids($featured_list, false);
            if (count($att_ids_map) > 0) {
                $this->revert_attachments($urls, $featured_list, $att_ids_map);
                $meta_ids_map = $this->get_thumbnail_meta_ids($featured_list, $att_ids_map);
                if (count($meta_ids_map) > 0)
                    $this->revert_attachments_meta($urls, $featured_list, $meta_ids_map);
            }
        }
    }

    function ctgr_remove_urls_su($bucket_id, $thumbnails, $urls, $video_urls) {
        foreach ($thumbnails as $thumbnail) {
            // post removed
            if (!$thumbnail->meta_id)
                unset($urls[$thumbnail->storage_id]);
        }

        if (empty($urls))
            return;

        // custom field
        $this->revert_custom_fields($thumbnails, $urls, $video_urls, true);

        $featured_list = array();
        foreach ($thumbnails as $thumbnail)
            array_push($featured_list, $thumbnail);

        // featured group
        if (count($featured_list) > 0) {
            $att_ids_map = $this->get_thumbnail_ids($featured_list, true);
            if (count($att_ids_map) > 0) {
                $this->revert_attachments($urls, $featured_list, $att_ids_map);
                $meta_ids_map = $this->get_thumbnail_meta_ids($featured_list, $att_ids_map);
                if (count($meta_ids_map) > 0)
                    $this->revert_attachments_meta($urls, $featured_list, $meta_ids_map);
            }
        }
    }

    /* speed up (add custom fields) */

    function revert_custom_fields($thumbnails, $urls, $video_urls, $is_ctgr) {
        $table = $is_ctgr ? $this->termmeta : $this->postmeta;

        $query = "
            INSERT INTO " . $table . " (meta_id, meta_value) VALUES ";
        $count = 0;
        foreach ($thumbnails as $thumbnail) {
            if ($count++ != 0)
                $query .= ", ";
            $url = $urls[$thumbnail->storage_id];
            $query .= "(" . $thumbnail->meta_id . ",'" . $url . "')";
        }
        $query .= "ON DUPLICATE KEY UPDATE meta_value=VALUES(meta_value)";
        return $this->wpdb->get_results($query);
    }

    function revert_attachments($urls, $thumbnails, $att_ids_map) {
        $count = 0;
        $query = "
            INSERT INTO " . $this->posts . " (id, guid) VALUES ";
        foreach ($thumbnails as $thumbnail) {
            if (!isset($att_ids_map[$thumbnail->meta_id])) // no metadata, only custom field
                continue;
            if ($count++ != 0)
                $query .= ", ";
            $query .= "(" . $att_ids_map[$thumbnail->meta_id] . ",'" . $urls[$thumbnail->storage_id] . "')";
        }
        $query .= "ON DUPLICATE KEY UPDATE guid=VALUES(guid)";
        return $this->wpdb->get_results($query);
    }

    function revert_attachments_meta($urls, $thumbnails, $meta_ids_map) {
        $count = 0;
        $query = "
            INSERT INTO " . $this->postmeta . " (meta_id, meta_value) VALUES ";
        foreach ($thumbnails as $thumbnail) {
            if (!isset($meta_ids_map[$thumbnail->meta_id])) // no metadata, only custom field
                continue;
            if ($count++ != 0)
                $query .= ", ";
            $query .= "(" . $meta_ids_map[$thumbnail->meta_id] . ",'" . $urls[$thumbnail->storage_id] . "')";
        }
        $query .= "ON DUPLICATE KEY UPDATE meta_value=VALUES(meta_value)";
        return $this->wpdb->get_results($query);
    }

    ///////////////////////////////////////////////////////////////////////////////////

    function count_available_images() {
        $total = 0;

        $featured = $this->wpdb->get_results("
            SELECT COUNT(1) AS total
            FROM " . $this->postmeta . "
            WHERE meta_key = '_thumbnail_id'"
        );

        $total += (int) $featured[0]->total;

        if (class_exists('WooCommerce')) {
            $gallery = $this->wpdb->get_results("
                SELECT SUM(LENGTH(meta_value) - LENGTH(REPLACE(meta_value, ',', '')) + 1) AS total
                FROM " . $this->postmeta . "
                WHERE meta_key = '_product_image_gallery'"
            );

            $total += (int) $gallery[0]->total;

            $category = $this->wpdb->get_results("
                SELECT COUNT(1) AS total
                FROM " . $this->termmeta . "
                WHERE meta_key = 'thumbnail_id'"
            );

            $total += (int) $category[0]->total;
        }

        return $total;
    }

    /* insert attachment */

    function insert_attachment_by($value) {
        $this->wpdb->get_results("
            INSERT INTO " . $this->posts . " (post_author, guid, post_title, post_mime_type, post_type, post_status, post_parent, post_date, post_date_gmt, post_modified, post_modified_gmt, post_content, post_excerpt, to_ping, pinged, post_content_filtered) 
            VALUES " . str_replace('\\', '', $value));
    }

    function insert_ctgr_attachment_by($value) {
        $this->wpdb->get_results("
            INSERT INTO " . $this->posts . " (post_author, guid, post_title, post_mime_type, post_type, post_status, post_parent, post_date, post_date_gmt, post_modified, post_modified_gmt, post_content, post_excerpt, to_ping, pinged, post_content_filtered, post_name) 
            VALUES " . str_replace('\\', '', $value));
    }

    function get_formatted_value($url, $alt, $post_parent) {
        return "(" . $this->author . ", '" . $url . "', '" . str_replace("'", "", $alt) . "', 'image/jpeg', 'attachment', 'inherit', '" . $post_parent . "', now(), now(), now(), now(), '', '', '', '', '')";
    }

    function get_ctgr_formatted_value($url, $alt, $post_parent) {
        return "(" . $this->author . ", '" . $url . "', '" . str_replace("'", "", $alt) . "', 'image/jpeg', 'attachment', 'inherit', '" . $post_parent . "', now(), now(), now(), now(), '', '', '', '', '', 'fifu-category-" . $post_parent . "')";
    }

    /* insert fake internal featured image */

    function insert_attachment_category() {
        $ids = null;
        $value = null;
        $i = 0;
        // insert 1 attachment for each selected category
        foreach ($this->get_categories_without_meta() as $res) {
            $ids = ($i++ == 0) ? $res->term_id : ($ids . "," . $res->term_id);
            $url = get_term_meta($res->term_id, 'fifu_image_url', true);
            if (!$url) {
                $result = $this->get_category_image_url($res->term_id);
                $url = $result[0]->meta_value;
            }
            $url = htmlspecialchars_decode($url);
            $value = $this->get_ctgr_formatted_value($url, get_term_meta($res->term_id, 'fifu_image_alt', true), $res->term_id);
            $this->insert_ctgr_attachment_by($value);
            $att_id = $this->wpdb->insert_id;
            update_term_meta($res->term_id, 'thumbnail_id', $att_id);
        }
        if ($ids) {
            $this->insert_attachment_meta_url($ids, true);
            $this->insert_attachment_meta_alt($ids, true);
        }
    }

    function insert_attachment() {
        $ids = null;
        $value = null;
        $i = 1;
        $count = 1;
        $total = (int) $this->get_count_urls_without_metadata()[0]->amount;
        // insert 1 attachment for each selected post
        $result = $this->get_posts_without_meta();
        foreach ($result as $res) {
            $ids = ($i == 1) ? $res->post_id : ($ids . "," . $res->post_id);
            $url = fifu_main_image_url($res->post_id, false);
            $aux = $this->get_formatted_value($url, get_post_meta($res->post_id, 'fifu_image_alt', true), $res->post_id);
            $value = ($i == 1) ? $aux : ($value . "," . $aux);
            if ($value && (($i % $this->MAX_INSERT == 0) || ($i % $this->MAX_INSERT != 0 && count($result) == $count))) {
                wp_cache_flush();
                $this->insert_attachment_by($value);
                $this->insert_thumbnail_id($ids, false);
                $this->insert_attachment_meta_url($ids, false);
                $this->insert_attachment_meta_alt($ids, false);
                set_transient('fifu_image_metadata_counter', $total - $count, 0);
                if (get_option('fifu_fake_stop'))
                    return;
                $ids = null;
                $value = null;
                $i = 1;
            } else
                $i++;
            $count++;
        }
    }

    /* delete fake internal featured image */

    function delete_attachment() {
        $ids = null;
        $i = 1;
        $count = 1;
        // delete fake attachments and _thumbnail_ids
        $result = $this->get_fake_attachments();
        foreach ($result as $res) {
            $ids = ($i == 1) ? $res->id : ($ids . "," . $res->id);
            if ($ids && (($i % $this->MAX_INSERT == 0) || ($i % $this->MAX_INSERT != 0 && count($result) == $count))) {
                wp_cache_flush();
                $this->delete_thumbnail_ids($ids);
                $this->delete_attachments($ids);
                $ids = null;
                $i = 1;
            } else
                $i++;
            $count++;
        }

        $ids = null;
        $i = 1;
        $count = 1;
        // delete attachment data and more _thumbnail_ids
        $result = $this->get_posts_with_url();
        foreach ($result as $res) {
            $ids = ($i == 1) ? $res->post_id : ($ids . "," . $res->post_id);
            if ($ids && (($i % $this->MAX_INSERT == 0) || ($i % $this->MAX_INSERT != 0 && count($result) == $count))) {
                wp_cache_flush();
                $this->delete_invalid_thumbnail_ids($ids);
                $this->delete_fake_thumbnail_id($ids);
                $this->delete_attachment_meta_url($ids);
                $ids = null;
                $i = 1;
            } else
                $i++;
            $count++;
        }

        // delete data without attachment
        $this->delete_thumbnail_id_without_attachment();
        $this->delete_attachment_meta_without_attachment();

        $this->delete_empty_urls();
    }

    function delete_attachment_category() {
        $ids = null;
        $i = 0;
        foreach ($this->get_terms_with_url() as $res)
            $ids = ($i++ == 0) ? $res->term_id : ($ids . "," . $res->term_id);
        if ($ids) {
            $this->delete_thumbnail_ids_category($ids);
            $this->delete_attachment_meta($ids, true);
            $this->delete_thumbnail_ids_category_without_attachment();
        }
        $this->delete_empty_urls_category();
    }

    /* dimensions: clean all */

    function clean_dimensions_all() {
        $this->wpdb->get_results("
            DELETE FROM " . $this->postmeta . " pm            
            WHERE pm.meta_key = '_wp_attachment_metadata'
            AND EXISTS (
                SELECT 1 
                FROM " . $this->posts . " p 
                WHERE p.id = pm.post_id
                AND p.post_type = 'attachment'
                AND p.post_author = " . $this->author . " 
            )"
        );
    }

    /* save 1 post */

    function update_fake_attach_id($post_id) {
        $att_id = get_post_thumbnail_id($post_id);
        $url = fifu_main_image_url($post_id, false);
        $has_fifu_attachment = $att_id ? ($this->is_fifu_attachment($att_id) && get_option('fifu_default_attach_id') != $att_id) : false;
        // delete
        if (!$url || $url == get_option('fifu_default_url')) {
            if ($has_fifu_attachment) {
                wp_delete_attachment($att_id);
                delete_post_thumbnail($post_id);
                if (fifu_get_default_url() && fifu_is_valid_default_cpt($post_id))
                    set_post_thumbnail($post_id, get_option('fifu_default_attach_id'));
            } else {
                // when an external image is removed and an internal is added at the same time
                $attachments = $this->get_attachments_without_post($post_id);
                if ($attachments) {
                    $this->delete_attachment_meta_url_and_alt($attachments);
                    $this->delete_attachments($attachments);
                }

                if (fifu_get_default_url() && fifu_is_valid_default_cpt($post_id)) {
                    $post_thumbnail_id = get_post_thumbnail_id($post_id);
                    $hasInternal = $post_thumbnail_id && get_post_field('post_author', $post_thumbnail_id) != $this->author;
                    if (!$hasInternal)
                        set_post_thumbnail($post_id, get_option('fifu_default_attach_id'));
                }
            }
        } else {
            // update
            $alt = get_post_meta($post_id, 'fifu_image_alt', true);
            if (!$alt && fifu_is_on('fifu_auto_alt'))
                $alt = get_the_title($post_id);

            if ($has_fifu_attachment) {
                update_post_meta($att_id, '_wp_attached_file', $url);
                update_post_meta($att_id, '_wp_attachment_image_alt', $alt);
                $this->wpdb->update($this->posts, $set = array('post_title' => $alt, 'guid' => $url), $where = array('id' => $att_id), null, null);
            }
            // insert
            else {
                $value = $this->get_formatted_value($url, $alt, $post_id);
                $this->insert_attachment_by($value);
                $att_id = $this->wpdb->insert_id;
                update_post_meta($post_id, '_thumbnail_id', $att_id);
                update_post_meta($att_id, '_wp_attached_file', $url);
                update_post_meta($att_id, '_wp_attachment_image_alt', $alt);
                $attachments = $this->get_attachments_without_post($post_id);
                if ($attachments) {
                    $this->delete_attachment_meta_url_and_alt($attachments);
                    $this->delete_attachments($attachments);
                }
            }
        }
    }

    /* save 1 category */

    function ctgr_update_fake_attach_id($term_id) {
        $att_id = get_term_meta($term_id, 'thumbnail_id');
        $att_id = $att_id ? $att_id[0] : null;
        $has_fifu_attachment = $att_id ? $this->is_fifu_attachment($att_id) : false;

        $url = get_term_meta($term_id, 'fifu_image_url', true);

        // delete
        if (!$url) {
            if ($has_fifu_attachment) {
                wp_delete_attachment($att_id);
                update_term_meta($term_id, 'thumbnail_id', 0);
            }
        } else {
            // update
            $alt = get_term_meta($term_id, 'fifu_image_alt', true);
            if ($has_fifu_attachment) {
                update_post_meta($att_id, '_wp_attached_file', $url);
                update_post_meta($att_id, '_wp_attachment_image_alt', $alt);
                $this->wpdb->update($this->posts, $set = array('guid' => $url, 'post_title' => $alt), $where = array('id' => $att_id), null, null);
            }
            // insert
            else {
                $value = $this->get_ctgr_formatted_value($url, $alt, $term_id);
                $this->insert_ctgr_attachment_by($value);
                $att_id = $this->wpdb->insert_id;
                update_term_meta($term_id, 'thumbnail_id', $att_id);
                update_post_meta($att_id, '_wp_attached_file', $url);
                update_post_meta($att_id, '_wp_attachment_image_alt', $alt);
                $attachments = $this->get_ctgr_attachments_without_post($term_id);
                if ($attachments) {
                    $this->delete_attachment_meta_url_and_alt($attachments);
                    $this->delete_attachments($attachments);
                }
            }
        }
    }

    /* default url */

    function create_attachment($url) {
        $value = $this->get_formatted_value($url, null, null);
        $this->insert_attachment_by($value);
        return $this->wpdb->insert_id;
    }

    function set_default_url() {
        $att_id = get_option('fifu_default_attach_id');
        if (!$att_id)
            return;
        $post_types = join("','", explode(',', str_replace(' ', '', get_option('fifu_default_cpt'))));
        $post_types ? $post_types : $this->types;
        $value = null;
        foreach ($this->get_posts_without_featured_image($post_types) as $res) {
            $aux = "(" . $res->id . ", '_thumbnail_id', " . $att_id . ")";
            $value = $value ? $value . ',' . $aux : $aux;
        }
        if ($value) {
            $this->insert_default_thumbnail_id($value);
            update_post_meta($att_id, '_wp_attached_file', get_option('fifu_default_url'));
        }
    }

    function update_default_url($url) {
        $att_id = get_option('fifu_default_attach_id');
        if ($url != wp_get_attachment_url($att_id)) {
            $this->wpdb->update($this->posts, $set = array('guid' => $url), $where = array('id' => $att_id), null, null);
            update_post_meta($att_id, '_wp_attached_file', $url);
        }
    }

    function delete_default_url() {
        $att_id = get_option('fifu_default_attach_id');
        wp_delete_attachment($att_id);
        delete_option('fifu_default_attach_id');
        $this->wpdb->delete($this->postmeta, array('meta_key' => '_thumbnail_id', 'meta_value' => $att_id));
    }

    /* delete post */

    function before_delete_post($post_id) {
        $default_url_enabled = fifu_is_on('fifu_enable_default_url');
        $default_att_id = $default_url_enabled ? get_option('fifu_default_attach_id') : null;
        $result = $this->get_featured_and_gallery_ids($post_id);
        if ($result) {
            $ids = explode(',', $result[0]->ids);
            $value = null;
            foreach ($ids as $id) {
                if ($id && $id != $default_att_id)
                    $value = ($value == null) ? $id : $value . ',' . $id;
            }
            if ($value) {
                $this->delete_attachment_meta_url_and_alt($value);
                $this->delete_attachments($value);
            }
        }
    }

    /* clean metadata */

    function enable_clean() {
        $this->delete_metadata();
        wp_delete_attachment(get_option('fifu_fake_attach_id'));
        wp_delete_attachment(get_option('fifu_default_attach_id'));
        delete_option('fifu_fake_attach_id');
        fifu_disable_fake();
        update_option('fifu_fake', 'toggleoff', 'no');
    }

    /* delete all urls */

    function delete_all() {
        sleep(3);
        if (fifu_is_on('fifu_run_delete_all') && get_option('fifu_run_delete_all_time') && FIFU_DELETE_ALL_URLS) {
            $this->wpdb->get_results("
                DELETE FROM " . $this->postmeta . " 
                WHERE meta_key LIKE 'fifu_%'"
            );
        }
    }

}

/* fake internal featured image */

function fifu_db_insert_attachment_category() {
    $db = new FifuDb();
    $db->insert_attachment_category();
}

function fifu_db_insert_attachment() {
    $db = new FifuDb();
    $db->insert_attachment();
}

function fifu_db_delete_attachment_category() {
    $db = new FifuDb();
    $db->delete_attachment_category();
}

function fifu_db_delete_attachment() {
    $db = new FifuDb();
    $db->delete_attachment();
}

function fifu_db_change_url_length() {
    $db = new FifuDb();
    $db->change_url_length();
}

/* dimensions: get all */

function fifu_db_get_all_without_dimensions() {
    $db = new FifuDb();
    return $db->get_posts_without_dimensions();
}

/* dimensions: clean all */

function fifu_db_clean_dimensions_all() {
    $db = new FifuDb();
    return $db->clean_dimensions_all();
}

/* dimensions: amount */

function fifu_db_missing_dimensions() {
    $db = new FifuDb();

    // too much
    if (fifu_db_count_urls_with_metadata() > 10000)
        return -1;

    $aux = $db->get_count_posts_without_dimensions()[0];
    return $aux ? $aux->amount : -1;
}

/* count: metadata */

function fifu_db_count_urls_with_metadata() {
    $db = new FifuDb();
    $aux = $db->get_count_urls_with_metadata()[0];
    return $aux ? $aux->amount : 0;
}

function fifu_db_count_urls_without_metadata() {
    $db = new FifuDb();
    $aux = $db->get_count_urls_without_metadata()[0];
    return $aux ? $aux->amount : 0;
}

/* count: urls */

function fifu_db_count_urls() {
    $db = new FifuDb();
    $aux = $db->get_count_urls()[0];
    return $aux ? $aux->amount : 0;
}

/* clean metadata */

function fifu_db_enable_clean() {
    $db = new FifuDb();
    $db->enable_clean();
}

/* delete all urls */

function fifu_db_delete_all() {
    $db = new FifuDb();
    return $db->delete_all();
}

/* save post */

function fifu_db_update_fake_attach_id($post_id) {
    $db = new FifuDb();
    $db->update_fake_attach_id($post_id);
}

/* save category */

function fifu_db_ctgr_update_fake_attach_id($term_id) {
    $db = new FifuDb();
    $db->ctgr_update_fake_attach_id($term_id);
}

/* default url */

function fifu_db_create_attachment($url) {
    $db = new FifuDb();
    return $db->create_attachment($url);
}

function fifu_db_set_default_url() {
    $db = new FifuDb();
    return $db->set_default_url();
}

function fifu_db_update_default_url($url) {
    $db = new FifuDb();
    return $db->update_default_url($url);
}

function fifu_db_delete_default_url() {
    $db = new FifuDb();
    return $db->delete_default_url();
}

/* delete post */

function fifu_db_before_delete_post($post_id) {
    $db = new FifuDb();
    $db->before_delete_post($post_id);
}

/* number of posts */

function fifu_db_number_of_posts() {
    $db = new FifuDb();
    return $db->get_number_of_posts();
}

/* speed up */

function fifu_db_get_all_urls($page) {
    $db = new FifuDb();
    return $db->get_all_urls($page);
}

function fifu_db_get_posts_with_internal_featured_image($page) {
    $db = new FifuDb();
    return $db->get_posts_with_internal_featured_image($page);
}

function fifu_get_posts_su($storage_ids) {
    $db = new FifuDb();
    return $db->get_posts_su($storage_ids);
}

function fifu_add_urls_su($bucket_id, $thumbnails) {
    $db = new FifuDb();
    return $db->add_urls_su($bucket_id, $thumbnails);
}

function fifu_ctgr_add_urls_su($bucket_id, $thumbnails) {
    $db = new FifuDb();
    return $db->ctgr_add_urls_su($bucket_id, $thumbnails);
}

function fifu_remove_urls_su($bucket_id, $thumbnails, $urls, $video_urls) {
    $db = new FifuDb();
    return $db->remove_urls_su($bucket_id, $thumbnails, $urls, $video_urls);
}

function fifu_ctgr_remove_urls_su($bucket_id, $thumbnails, $urls, $video_urls) {
    $db = new FifuDb();
    return $db->ctgr_remove_urls_su($bucket_id, $thumbnails, $urls, $video_urls);
}

function fifu_db_count_available_images() {
    $db = new FifuDb();
    return $db->count_available_images();
}

/* get last urls */

function fifu_db_get_last($meta_key) {
    $db = new FifuDb();
    return $db->get_last($meta_key);
}

function fifu_db_get_last_image() {
    $db = new FifuDb();
    return $db->get_last_image();
}

/* wordpress importer */

function fifu_db_delete_thumbnail_id_without_attachment() {
    $db = new FifuDb();
    return $db->delete_thumbnail_id_without_attachment();
}

/* att_id */

function fifu_db_get_att_id($post_parent, $url, $is_ctgr) {
    $db = new FifuDb();
    return $db->get_att_id($post_parent, $url, $is_ctgr);
}

/* wordpress upgrade */

function fifu_db_fix_guid() {
    $db = new FifuDb();
    return $db->fix_guid();
}

/* database info */

function fifu_db_get_guid_size() {
    $db = new FifuDb();
    return $db->get_guid_size();
}

