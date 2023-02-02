<?php

function miga_custom_posts_ajax_functions()
{
    $postType = sanitize_text_field($_POST["postType"]);

    // Pagination
    if ($_POST["paged"]) {
        $dataPaged = intval($_POST["paged"]);
    } else {
        $dataPaged = get_query_var("paged") ? get_query_var("paged") : 1;
    }

    // filter array
    $filterData = isset($_POST["filterData"]) ? $_POST["filterData"] : [];
    $isFilter = isset($_POST["isFilter"]) ? $_POST["isFilter"] : false;

    $data = [
        "post_type" => $postType,
        "post_status" => "publish",
        "paged" => $dataPaged,
        "posts_per_page" => intval($_POST["numposts"]),
    ];

    $taxArray = [];
    if ($isFilter && sizeOf($filterData) > 0) {
        foreach ($filterData as $fData):
            if (!empty($fData[1])) {
                $taxArray[] = [
                    "taxonomy" => $fData[0],
                    "field" => "name",
                    "terms" => $fData[1],
                ];
            }
        endforeach;
    }

    if (sizeOf($taxArray) > 0) {
        $data["tax_query"] = [$taxArray];
    }

    //post query
    $query = new WP_Query($data);
    ob_start();

    $returnValue = [];
    if ($query->have_posts()):
        while ($query->have_posts()):
            $query->the_post();
            $tags = [];

            $taxonomies = get_post_taxonomies();
            foreach ($taxonomies as $tax):
                $terms = wp_get_post_terms(get_the_ID(), $tax);
                foreach ($terms as $term):
                  $tags[] = $term->name;
                endforeach;
            endforeach;

            $returnValue[] = array(
              "link"=> get_the_permalink(),
              "thumbnail"=>get_the_post_thumbnail(),
              "title"=>get_the_title(),
              "date"=>get_the_date(),
              "excerpt"=>wp_trim_words(get_the_content(), 60),
              "tags"=>$tags
            );

        endwhile;
        wp_reset_postdata();
        $arr = ["items" => $returnValue, "maxPage" => $query->max_num_pages];

        echo json_encode($arr);
    else:
        if ($isFilter) {
            echo '{"error":"no_more", "isFilter": true}';
        } else {
            echo '{"error":"no_more"}';
        }
    endif;
    wp_reset_query();
    echo ob_get_clean();
    die();
}
