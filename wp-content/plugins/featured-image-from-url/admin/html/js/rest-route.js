function fifu_get_rest_url() {
    var out = null;
    error = false;
    jQuery.ajax({
        method: "POST",
        url: fifuScriptVars.homeUrl + '/wp-json/featured-image-from-url/v2/rest_url_api/',
        async: false,
        success: function (data) {
            out = data;
        },
        error: function (jqXHR, textStatus, errorThrown) {
            protocol = fifuScriptVars.homeUrl.includes('http:') ? 'https:' : 'http:';
            jQuery.ajax({
                method: "POST",
                url: fifuScriptVars.homeUrl.replace(/[^:]+:/, protocol) + '/wp-json/featured-image-from-url/v2/rest_url_api/',
                async: false,
                success: function (data) {
                    out = data;
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    error = true;
                }
            });
        }
    });
    if (error) {
        jQuery.ajax({
            method: "POST",
            url: fifuScriptVars.homeUrl + '?rest_route=/featured-image-from-url/v2/rest_url_api/',
            async: false,
            success: function (data) {
                out = data;
            },
            error: function (jqXHR, textStatus, errorThrown) {
                protocol = fifuScriptVars.homeUrl.includes('http:') ? 'https:' : 'http:';
                jQuery.ajax({
                    method: "POST",
                    url: fifuScriptVars.homeUrl.replace(/[^:]+:/, protocol) + '?rest_route=/featured-image-from-url/v2/rest_url_api/',
                    async: false,
                    success: function (data) {
                        out = data;
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        error = true;
                    }
                });
            }
        });
    }
    return out;
}
