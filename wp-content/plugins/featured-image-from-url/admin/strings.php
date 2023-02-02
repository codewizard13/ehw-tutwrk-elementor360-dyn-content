<?php

function fifu_get_strings_settings() {
    $fifu = array();

    // php
    $fifu['php']['message']['wait'] = function () {
        return __("Please wait a few seconds...", FIFU_SLUG);
    };

    // buttons
    $fifu['button']['submit'] = function () {
        _e("Submit", FIFU_SLUG);
    };
    $fifu['button']['ok'] = function () {
        _e("OK", FIFU_SLUG);
    };

    // details
    $fifu['detail']['important'] = function () {
        _e("Important", FIFU_SLUG);
    };
    $fifu['detail']['requirement'] = function () {
        _e("Requirement", FIFU_SLUG);
    };
    $fifu['detail']['tip'] = function () {
        _e("Tip", FIFU_SLUG);
    };
    $fifu['detail']['suggestion'] = function () {
        _e("Suggestion", FIFU_SLUG);
    };
    $fifu['detail']['example'] = function () {
        _e("Example", FIFU_SLUG);
    };
    $fifu['detail']['eg'] = function () {
        _e("e.g.:", FIFU_SLUG);
    };
    $fifu['detail']['result'] = function () {
        _e("Result", FIFU_SLUG);
    };
    $fifu['detail']['notice'] = function () {
        _e("Notice", FIFU_SLUG);
    };
    $fifu['detail']['developers'] = function () {
        _e("Developers", FIFU_SLUG);
    };

    // words
    $fifu['word']['attribute'] = function () {
        _e("attribute", FIFU_SLUG);
    };
    $fifu['word']['selector'] = function () {
        _e("selector", FIFU_SLUG);
    };
    $fifu['word']['cover'] = function () {
        _e("cover", FIFU_SLUG);
    };
    $fifu['word']['contain'] = function () {
        _e("contain", FIFU_SLUG);
    };
    $fifu['word']['fill'] = function () {
        _e("fill", FIFU_SLUG);
    };
    $fifu['word']['width'] = function () {
        _e("width", FIFU_SLUG);
    };
    $fifu['word']['height'] = function () {
        _e("height", FIFU_SLUG);
    };
    $fifu['word']['color'] = function () {
        _e("color", FIFU_SLUG);
    };
    $fifu['word']['mode'] = function () {
        _e("mode", FIFU_SLUG);
    };
    $fifu['word']['inline'] = function () {
        _e("inline", FIFU_SLUG);
    };
    $fifu['word']['lightbox'] = function () {
        _e("lightbox", FIFU_SLUG);
    };
    $fifu['word']['zindex'] = function () {
        _e("z-index", FIFU_SLUG);
    };
    $fifu['word']['zoom'] = function () {
        _e("zoom", FIFU_SLUG);
    };
    $fifu['word']['function'] = function () {
        _e("Function", FIFU_SLUG);
    };
    $fifu['word']['field'] = function () {
        _e("Fields", FIFU_SLUG);
    };
    $fifu['word']['delimiter'] = function () {
        _e("Delimiter", FIFU_SLUG);
    };
    $fifu['word']['documentation'] = function () {
        _e("Documentation", FIFU_SLUG);
    };
    $fifu['word']['tags'] = function () {
        _e("Tags", FIFU_SLUG);
    };

    // where
    $fifu['where']['page'] = function () {
        _e("on page", FIFU_SLUG);
    };
    $fifu['where']['post'] = function () {
        _e("on post ", FIFU_SLUG);
    };
    $fifu['where']['cpt'] = function () {
        _e("on custom post type", FIFU_SLUG);
    };
    $fifu['where']['home'] = function () {
        _e("on home (or shop)", FIFU_SLUG);
    };
    $fifu['where']['single'] = function () {
        _e("on single post types", FIFU_SLUG);
    };
    $fifu['where']['desktop'] = function () {
        _e("on desktop", FIFU_SLUG);
    };
    $fifu['where']['mobile'] = function () {
        _e("on mobile", FIFU_SLUG);
    };

    // player
    $fifu['player']['youtube'] = function () {
        _e("for YouTube videos", FIFU_SLUG);
    };
    $fifu['player']['vimeo'] = function () {
        _e("for Vimeo videos", FIFU_SLUG);
    };

    // chrome
    $fifu['chrome']['link'] = function () {
        _e("Chrome extension", FIFU_SLUG);
    };

    // referral
    $fifu['referral']['link'] = function () {
        _e("Affiliate program", FIFU_SLUG);
    };

    // messages
    $fifu['message']['wait'] = function () {
        _e("Please wait a few seconds...", FIFU_SLUG);
    };

    // tabs
    $fifu['tab']['help'] = function () {
        _e("Help", FIFU_SLUG);
    };
    $fifu['tab']['admin'] = function () {
        _e("Admin", FIFU_SLUG);
    };
    $fifu['tab']['image'] = function () {
        _e("Featured image", FIFU_SLUG);
    };
    $fifu['tab']['auto'] = function () {
        _e("Automatic", FIFU_SLUG);
    };
    $fifu['tab']['metadata'] = function () {
        _e("Metadata", FIFU_SLUG);
    };
    $fifu['tab']['performance'] = function () {
        _e("Performance", FIFU_SLUG);
    };
    $fifu['tab']['api'] = function () {
        _e("REST API", FIFU_SLUG);
    };
    $fifu['tab']['shortcode'] = function () {
        _e("Shortcode", FIFU_SLUG);
    };
    $fifu['tab']['slider'] = function () {
        _e("Featured slider", FIFU_SLUG);
    };
    $fifu['tab']['audio'] = function () {
        _e("Featured audio", FIFU_SLUG);
    };
    $fifu['tab']['social'] = function () {
        _e("Social", FIFU_SLUG);
    };
    $fifu['tab']['video'] = function () {
        _e("Featured video", FIFU_SLUG);
    };
    $fifu['tab']['woo'] = function () {
        _e("WooCommerce", FIFU_SLUG);
    };
    $fifu['tab']['wai'] = function () {
        _e("WP All Import", FIFU_SLUG);
    };
    $fifu['tab']['trouble'] = function () {
        _e("Troubleshooting", FIFU_SLUG);
    };
    $fifu['tab']['key'] = function () {
        _e("License key", FIFU_SLUG);
    };
    $fifu['tab']['cloud'] = function () {
        _e("Cloud", FIFU_SLUG);
    };

    // titles
    $fifu['title']['support'] = function () {
        _e("Fast support", FIFU_SLUG);
    };
    $fifu['title']['start'] = function () {
        _e("Getting started", FIFU_SLUG);
    };
    $fifu['title']['dev'] = function () {
        _e("Integrate your plugin with FIFU", FIFU_SLUG);
    };
    $fifu['title']['reset'] = function () {
        _e("Reset Settings", FIFU_SLUG);
    };
    $fifu['title']['cli'] = function () {
        _e("WP-CLI", FIFU_SLUG);
    };
    $fifu['title']['media'] = function () {
        _e("Save in the Media Library", FIFU_SLUG);
    };
    $fifu['title']['height'] = function () {
        _e("Same Height", FIFU_SLUG);
    };
    $fifu['title']['auto'] = function () {
        _e("Auto set featured image using post title and search engine", FIFU_SLUG);
    };
    $fifu['title']['isbn'] = function () {
        _e("Auto set featured image using ISBN and books API", FIFU_SLUG);
    };
    $fifu['title']['screenshot'] = function () {
        _e("Auto set screenshot as featured image", FIFU_SLUG);
    };
    $fifu['title']['finder'] = function () {
        _e("Auto set featured media using web page address", FIFU_SLUG);
    };
    $fifu['title']['tags'] = function () {
        _e("Auto set featured image from Unsplash using tags", FIFU_SLUG);
    };
    $fifu['title']['block'] = function () {
        _e("Disable right-click", FIFU_SLUG);
    };
    $fifu['title']['replace'] = function () {
        _e("Replace Not Found Image", FIFU_SLUG);
    };
    $fifu['title']['default'] = function () {
        _e("Default Featured Image", FIFU_SLUG);
    };
    $fifu['title']['content'] = function () {
        _e("Featured Image in Content", FIFU_SLUG);
    };
    $fifu['title']['hide'] = function () {
        _e("Hide Featured Media", FIFU_SLUG);
    };
    $fifu['title']['redirection'] = function () {
        _e("Page Redirection", FIFU_SLUG);
    };
    $fifu['title']['html'] = function () {
        _e("Auto set featured media from post content", FIFU_SLUG);
    };
    $fifu['title']['metadata'] = function () {
        _e("Image Metadata", FIFU_SLUG);
    };
    $fifu['title']['clean'] = function () {
        _e("Clean Metadata", FIFU_SLUG);
    };
    $fifu['title']['dimensions'] = function () {
        _e("Save Image Dimensions", FIFU_SLUG);
    };
    $fifu['title']['schedule'] = function () {
        _e("Schedule Metadata Generation", FIFU_SLUG);
    };
    $fifu['title']['delete'] = function () {
        _e("Delete All URLs", FIFU_SLUG);
    };
    $fifu['title']['audio'] = function () {
        _e("Featured Audio", FIFU_SLUG);
    };
    $fifu['title']['lazy'] = function () {
        _e("Lazy Load", FIFU_SLUG);
    };
    $fifu['title']['jetpack'] = function () {
        _e("CDN + Optimized Thumbnails", FIFU_SLUG);
    };
    $fifu['title']['api'] = function () {
        _e("WP / WooCommerce REST API", FIFU_SLUG);
    };
    $fifu['title']['shortcodes'] = function () {
        _e("FIFU Shortcodes", FIFU_SLUG);
    };
    $fifu['title']['slider'] = function () {
        _e("Featured Slider", FIFU_SLUG);
    };
    $fifu['title']['social'] = function () {
        _e("Social Tags", FIFU_SLUG);
    };
    $fifu['title']['bbpress'] = function () {
        _e("bbPress", FIFU_SLUG);
    };
    $fifu['title']['rss'] = function () {
        _e("Media RSS Tags", FIFU_SLUG);
    };
    $fifu['title']['title'] = function () {
        _e("Auto Set Image Title", FIFU_SLUG);
    };
    $fifu['title']['video'] = function () {
        _e("Featured Video", FIFU_SLUG);
    };
    $fifu['title']['thumbnail'] = function () {
        _e("Video Thumbnail", FIFU_SLUG);
    };
    $fifu['title']['play'] = function () {
        _e("Play Button", FIFU_SLUG);
    };
    $fifu['title']['width'] = function () {
        _e("Minimum Width", FIFU_SLUG);
    };
    $fifu['title']['controls'] = function () {
        _e("Video Controls", FIFU_SLUG);
    };
    $fifu['title']['mouseover'] = function () {
        _e("Mouseover Autoplay", FIFU_SLUG);
    };
    $fifu['title']['autoplay'] = function () {
        _e("Autoplay", FIFU_SLUG);
    };
    $fifu['title']['loop'] = function () {
        _e("Loop", FIFU_SLUG);
    };
    $fifu['title']['mute'] = function () {
        _e("Mute", FIFU_SLUG);
    };
    $fifu['title']['background'] = function () {
        _e("Background Video", FIFU_SLUG);
    };
    $fifu['title']['privacy'] = function () {
        _e("Privacy Enhanced Mode", FIFU_SLUG);
    };
    $fifu['title']['zoom'] = function () {
        _e("Lightbox and Zoom", FIFU_SLUG);
    };
    $fifu['title']['category'] = function () {
        _e("Auto Set Category Images", FIFU_SLUG);
    };
    $fifu['title']['variable'] = function () {
        _e("Variable Product", FIFU_SLUG);
    };
    $fifu['title']['order-email'] = function () {
        _e("Add image to order email", FIFU_SLUG);
    };
    $fifu['title']['import'] = function () {
        _e("Import", FIFU_SLUG);
    };
    $fifu['title']['addon'] = function () {
        _e("Add-On", FIFU_SLUG);
    };
    $fifu['title']['key'] = function () {
        _e("License Key", FIFU_SLUG);
    };
    $fifu['title']['gallery'] = function () {
        _e("FIFU Product Gallery", FIFU_SLUG);
    };
    $fifu['title']['buy'] = function () {
        _e("Quick Buy", FIFU_SLUG);
    };

    // support
    $fifu['support']['email'] = function () {
        _e("If you need help, you can refer to troubleshooting or send an email to", FIFU_SLUG);
    };
    $fifu['support']['with'] = function () {
        _e("with this", FIFU_SLUG);
    };
    $fifu['support']['status'] = function () {
        _e("status", FIFU_SLUG);
    };
    $fifu['support']['disappeared'] = function () {
        _e("All my images disappeared", FIFU_SLUG);
    };
    $fifu['support']['plugin'] = function () {
        _e("A plugin doesn't work with FIFU ", FIFU_SLUG);
    };
    $fifu['support']['style'] = function () {
        _e("I'm having style issues ", FIFU_SLUG);
    };
    $fifu['support']['facebook'] = function () {
        _e("Facebook doesn't share my images", FIFU_SLUG);
    };
    $fifu['support']['null'] = function () {
        _e("\"Nulled\" Premium doesn't work", FIFU_SLUG);
    };
    $fifu['support']['money'] = function () {
        _e("Broken image icon is being displayed", FIFU_SLUG);
    };
    $fifu['support']['speed'] = function () {
        _e("PageSpeed and GTmetrix issues", FIFU_SLUG);
    };
    $fifu['support']['disappeared-desc'] = function () {
        _e("You were probably using a deprecated feature. Just do it: 1) access Metadata tab; 2) run Clean Metadata; 3) enable Image Metadata (~50,000 URLs/min); 4) clean your cache (optional).", FIFU_SLUG);
    };
    $fifu['support']['plugin-desc'] = function () {
        _e("Contact us. If you are available to discuss the details and the plugin is free, we should provide an integration. Or contact its developer and ask him to use the FIFU integration functions below.", FIFU_SLUG);
    };
    $fifu['support']['style-desc'] = function () {
        _e("Some themes and plugins aren't responsive enough to work with external images. You may solve that by using <b>FIFU Cloud</b> (fast) or running Metadata > Save Image Dimensions (slow).", FIFU_SLUG);
    };
    $fifu['support']['facebook-desc'] = function () {
        _e("You probably have a plugin or theme that sets a default image as the Facebook image (og:image tag). Just find and disable the option. And make sure Social > Social Tags is enabled.", FIFU_SLUG);
    };
    $fifu['support']['null-desc'] = function () {
        _e("This plugin has no nulled versions, but modified versions. Don't install that. It's illegal and may ruin your site. Moreover, sales of the original premium version keep this project alive.", FIFU_SLUG);
    };
    $fifu['support']['money-desc'] = function () {
        _e("Possibilities: a) image file deleted by owner; b) URL has changed; c) hotlink protection; d) URL is wrong. Use <b>FIFU Cloud</b> to prevent image loss or bypass hotlink protection.", FIFU_SLUG);
    };
    $fifu['support']['speed-desc'] = function () {
        _e("Solve that by activating Performance > CDN + Optimized Thumbnails or <b>FIFU Cloud</b> (much better). Even large images hosted on slow servers should load quickly on your website.", FIFU_SLUG);
    };

    // start
    $fifu['start']['url']['external'] = function () {
        _e("Hi, I'm an EXTERNAL image!", FIFU_SLUG);
    };
    $fifu['start']['url']['not'] = function () {
        _e("It means I'm NOT in your media library and I'm NOT an attached plugin file too.", FIFU_SLUG);
    };
    $fifu['start']['url']['url'] = function () {
        _e("Don't you believe me? So why don't you check my Internet address (also known as URL)?", FIFU_SLUG);
    };
    $fifu['start']['url']['right'] = function () {
        _e("1) right click me now;", FIFU_SLUG);
    };
    $fifu['start']['url']['copy'] = function () {
        _e("2) select \"Copy image address\";", FIFU_SLUG);
    };
    $fifu['start']['url']['paste'] = function () {
        _e("3) paste it here:", FIFU_SLUG);
    };
    $fifu['start']['url']['drag'] = function () {
        _e("or just drag and drop me here", FIFU_SLUG);
    };
    $fifu['start']['url']['click'] = function () {
        _e("Right click me!", FIFU_SLUG);
    };
    $fifu['start']['post']['famous'] = function () {
        _e("Since now you have my address (also known as URL), how about making me famous?", FIFU_SLUG);
    };
    $fifu['start']['post']['create'] = function () {
        _e("You just need to create a post and use me as the featured image:", FIFU_SLUG);
    };
    $fifu['start']['post']['new'] = function () {
        _e("1) add a new post;", FIFU_SLUG);
    };
    $fifu['start']['post']['box'] = function () {
        _e("2) find the box", FIFU_SLUG);
    };
    $fifu['start']['post']['featured'] = function () {
        _e("Featured image", FIFU_SLUG);
    };
    $fifu['start']['post']['address'] = function () {
        _e("3) paste my address into \"Image URL\" field.", FIFU_SLUG);
    };
    $fifu['start']['post']['storage'] = function () {
        _e("And don't worry about storage. I will remain EXTERNAL. I WON'T be uploaded to your media library.", FIFU_SLUG);
    };

    // dev
    $fifu['dev']['function'] = function () {
        _e("Are you a WordPress developer? So now you can easily integrate your code with FIFU using the functions below.", FIFU_SLUG);
    };
    $fifu['dev']['args'] = function () {
        _e("All you need is to inform the post id and the image url(s). And FIFU will set the custom fields and create the metadata.", FIFU_SLUG);
    };
    $fifu['dev']['plugins'] = function () {
        _e("Plugins that make use of FIFU integration functions:", FIFU_SLUG);
    };
    $fifu['dev']['field']['image'] = function () {
        _e("Featured image", FIFU_SLUG);
    };
    $fifu['dev']['field']['video'] = function () {
        _e("Featured video", FIFU_SLUG);
    };
    $fifu['dev']['field']['product'] = function () {
        _e("Product image", FIFU_SLUG);
    };
    $fifu['dev']['field']['gallery'] = function () {
        _e("Image gallery", FIFU_SLUG);
    };
    $fifu['dev']['field']['category']['image'] = function () {
        _e("Product category image", FIFU_SLUG);
    };
    $fifu['dev']['field']['category']['video'] = function () {
        _e("Product category video", FIFU_SLUG);
    };

    // cli
    $fifu['cli']['desc'] = function () {
        _e("Configure FIFU via command line.", FIFU_SLUG);
    };
    $fifu['cli']['tab']['commands'] = function () {
        _e("Commands", FIFU_SLUG);
    };
    $fifu['cli']['tab']['documentation'] = function () {
        _e("Documentation", FIFU_SLUG);
    };
    $fifu['cli']['documentation']['site'] = function () {
        _e("WP-CLI", FIFU_SLUG);
    };
    $fifu['cli']['column']['tab'] = function () {
        _e("Tab", FIFU_SLUG);
    };
    $fifu['cli']['column']['section'] = function () {
        _e("Section", FIFU_SLUG);
    };
    $fifu['cli']['column']['feature'] = function () {
        _e("Feature", FIFU_SLUG);
    };
    $fifu['cli']['column']['option'] = function () {
        _e("Option", FIFU_SLUG);
    };
    $fifu['cli']['column']['command'] = function () {
        _e("Command", FIFU_SLUG);
    };
    $fifu['cli']['column']['eg'] = function () {
        _e("e.g. (args)", FIFU_SLUG);
    };

    // reset
    $fifu['reset']['desc'] = function () {
        _e("Reset FIFU settings to the default configuration.", FIFU_SLUG);
    };
    $fifu['reset']['reset'] = function () {
        _e("reset settings", FIFU_SLUG);
    };

    // media library
    $fifu['media']['desc'] = function () {
        _e("It's possible to save the external images in the media library and set those new local images as standard WordPress/WooCommerce featured/gallery images automatically. Make a backup before running the cron job, because after making an image local it can't be external again, so FIFU will have no more control over that.", FIFU_SLUG);
    };
    $fifu['media']['upload'] = function () {
        _e("show upload button on post editor", FIFU_SLUG);
    };
    $fifu['media']['job'] = function () {
        _e("run a cron job that searches for external images and saves them in the media library periodically.", FIFU_SLUG);
    };
    $fifu['media']['dev'] = function () {
        _e("php function that runs the upload process once and immediately (i.e. without a cron job).", FIFU_SLUG);
    };
    $fifu['media']['run'] = function () {
        _e("Run now", FIFU_SLUG);
    };
    $fifu['media']['tab']['main'] = function () {
        _e("Main", FIFU_SLUG);
    };
    $fifu['media']['tab']['proxy'] = function () {
        _e("Proxy", FIFU_SLUG);
    };
    $fifu['media']['tab']['dev'] = function () {
        _e("Developer", FIFU_SLUG);
    };
    $fifu['media']['proxy']['desc'] = function () {
        _e("Proxies are used to bypass IP ban, what may happen when your site downloads too many images from the same host. As disadvantages, a proxy can limit the number of requests and its IP can be banned as well. Both will force the plugin to try other proxies until find a good one. So proxies should make the process much slower, except if you have access to a private proxy. By default, FIFU works with a list of public proxies updated every 30 minutes and caches the ones that are working with your image URLs.", FIFU_SLUG);
    };
    $fifu['media']['proxy']['toggle'] = function () {
        _e("use proxies to intermediate the traffic between your site and the image hosts", FIFU_SLUG);
    };
    $fifu['media']['proxy']['private'] = function () {
        _e("Private proxy", FIFU_SLUG);
    };
    $fifu['media']['proxy']['placeholder'] = function () {
        _e("192.168.0.1:80, 127.0.0.1:8080, username:password@172.16.0.0:443", FIFU_SLUG);
    };

    // height
    $fifu['height']['desc'] = function () {
        _e("If you have different sizes of images on your home (or shop), enable the toggle below to show them in the same height. Depending on your theme, it may be necessary to use a selector to inform where is the group of images that you want to resize.", FIFU_SLUG);
    };
    $fifu['height']['tab']['height'] = function () {
        _e("Same height", FIFU_SLUG);
    };
    $fifu['height']['tab']['selector'] = function () {
        _e("Add selector", FIFU_SLUG);
    };
    $fifu['height']['tab']['default'] = function () {
        _e("Default selectors", FIFU_SLUG);
    };
    $fifu['height']['tab']['ignore'] = function () {
        _e("Ignore images", FIFU_SLUG);
    };
    $fifu['height']['tab']['ratio'] = function () {
        _e("Size ratio", FIFU_SLUG);
    };
    $fifu['height']['tab']['fit'] = function () {
        _e("Object fit", FIFU_SLUG);
    };
    $fifu['height']['tab']['delay'] = function () {
        _e("Delay", FIFU_SLUG);
    };
    $fifu['height']['selector']['desc'] = function () {
        _e("Examples of selectors...", FIFU_SLUG);
    };
    $fifu['height']['default']['desc'] = function () {
        _e("Default selectors:", FIFU_SLUG);
    };
    $fifu['height']['ignore']['desc'] = function () {
        _e("Ignore images...", FIFU_SLUG);
    };
    $fifu['height']['ignore']['parent'] = function () {
        _e("Parent selectors:", FIFU_SLUG);
    };
    $fifu['height']['height']['ratio'] = function () {
        _e("and you want a 4:3 size ratio", FIFU_SLUG);
    };
    $fifu['height']['height']['fit'] = function () {
        _e("and you want a 4:3 size ratio and cover as object fit", FIFU_SLUG);
    };
    $fifu['height']['ratio']['desc'] = function () {
        _e("Examples of valid size ratios", FIFU_SLUG);
    };
    $fifu['height']['fit']['cover'] = function () {
        _e("crops the images;", FIFU_SLUG);
    };
    $fifu['height']['fit']['contain'] = function () {
        _e("shows full images (in smaller sizes);", FIFU_SLUG);
    };
    $fifu['height']['fit']['fill'] = function () {
        _e("deforms the images.", FIFU_SLUG);
    };
    $fifu['height']['delay']['time'] = function () {
        _e("time (in ms)", FIFU_SLUG);
    };

    // auto set
    $fifu['auto']['desc'] = function () {
        _e("Set featured images automatically. FIFU will check every minute if there are post types without featured images and will perform web searches based on post titles to get the image URLs (~10/min).", FIFU_SLUG);
    };
    $fifu['auto']['tab']['auto'] = function () {
        _e("Auto set", FIFU_SLUG);
    };
    $fifu['auto']['tab']['filters'] = function () {
        _e("Size filter", FIFU_SLUG);
    };
    $fifu['auto']['tab']['blocklist'] = function () {
        _e("Blocklist", FIFU_SLUG);
    };
    $fifu['auto']['tab']['cpt'] = function () {
        _e("Post types", FIFU_SLUG);
    };
    $fifu['auto']['tab']['source'] = function () {
        _e("Source filter", FIFU_SLUG);
    };
    $fifu['auto']['filter']['width'] = function () {
        _e("minimum width (px)", FIFU_SLUG);
    };
    $fifu['auto']['filter']['height'] = function () {
        _e("minimum height (px)", FIFU_SLUG);
    };
    $fifu['auto']['filter']['blocklist'] = function () {
        _e("List of strings that shouldn't be in the image URL:", FIFU_SLUG);
    };
    $fifu['auto']['cpt']['desc'] = function () {
        _e("This feature is pre configured to work only with the post type \"post\". But you can include more post types below (delimited by \",\").", FIFU_SLUG);
    };
    $fifu['auto']['cpt']['found'] = function () {
        _e("Post types found on your site: ", FIFU_SLUG);
    };
    $fifu['auto']['source']['desc'] = function () {
        _e("Here you can limit the search to one or more sites.", FIFU_SLUG);
    };

    // isbn
    $fifu['isbn']['desc'] = function () {
        _e("Set featured images automatically. FIFU will check every minute if there are post types without featured images and will perform web searches based on ISBN to get the image URLs.", FIFU_SLUG);
    };
    $fifu['isbn']['tab']['auto'] = function () {
        _e("Auto set", FIFU_SLUG);
    };
    $fifu['isbn']['tab']['custom'] = function () {
        _e("Custom field", FIFU_SLUG);
    };
    $fifu['isbn']['custom']['desc'] = function () {
        _e("If you already have the ISBN saved in your database, you could inform its custom field name here. FIFU would access that and import the value. For example, if the ISBN is saved in the SKU field, you could add \"_sku\", which is the field where the SKU is stored.", FIFU_SLUG);
    };

    // screenshot
    $fifu['screenshot']['desc'] = function () {
        _e("Set screenshots from external web pages as featured images automatically. FIFU will check every minute if there are post types without featured images and will access the informed web page URLs to get their screenshots. The screenshots are saved in the media library and are automatically updated once a month.", FIFU_SLUG);
    };
    $fifu['screenshot']['tab']['auto'] = function () {
        _e("Auto set", FIFU_SLUG);
    };
    $fifu['screenshot']['tab']['crop'] = function () {
        _e("Crop", FIFU_SLUG);
    };
    $fifu['screenshot']['tab']['scale'] = function () {
        _e("Scale", FIFU_SLUG);
    };
    $fifu['screenshot']['tab']['resolution'] = function () {
        _e("High resolution", FIFU_SLUG);
    };
    $fifu['screenshot']['crop']['height'] = function () {
        _e("height (px)", FIFU_SLUG);
    };
    $fifu['screenshot']['scale']['width'] = function () {
        _e("width (px)", FIFU_SLUG);
    };
    $fifu['screenshot']['resolution']['desc'] = function () {
        _e("The default screenshot size is 500x348px. Enable the option below to have full screenshots, i.e., without cropping.", FIFU_SLUG);
    };

    // find
    $fifu['finder']['desc'] = function () {
        _e("Set images from external web pages as featured images automatically. FIFU will check every minute if there are post types without featured images and will access the informed web page URLs to get the main image. For that FIFU looks for the Open Graph tag image (used for sharing on social media). If og:image is not found, FIFU will get the larger image found. It's also able to look for embedded videos and set the first one found as featured video. Videos have priority over images.", FIFU_SLUG);
    };
    $fifu['finder']['auto'] = function () {
        _e("auto set featured media", FIFU_SLUG);
    };
    $fifu['finder']['video'] = function () {
        _e("look for embedded videos", FIFU_SLUG);
    };
    $fifu['finder']['tab']['auto'] = function () {
        _e("Auto set", FIFU_SLUG);
    };
    $fifu['finder']['tab']['custom'] = function () {
        _e("Custom field", FIFU_SLUG);
    };
    $fifu['finder']['tab']['amazon'] = function () {
        _e("Amazon", FIFU_SLUG);
    };
    $fifu['finder']['custom']['desc'] = function () {
        _e("If you already have the web page address saved in your database, you could inform its custom field name here. FIFU would access that and import the value. For example, if the web page URL is saved in the Product URL field, you could add \"_product_url\", which is the field where the external URL to the product is stored. For posts created by WordPress Automatic Plugin, add \"original_link\".", FIFU_SLUG);
    };

    // tags
    $fifu['tags']['desc'] = function () {
        _e("Set images from Unsplash as featured images automatically. FIFU will check every minute if there are post types without featured images and will perform Unsplash searches based on the tags to get the image URLs.", FIFU_SLUG);
    };

    // block
    $fifu['block']['desc'] = function () {
        _e("Disable right-click on all images.", FIFU_SLUG);
    };

    // redirection
    $fifu['redirection']['desc'] = function () {
        _e("Adds a new meta box in the post editor where you can specify a forwarding URL. Then, when accessing a post and clicking on the featured image, the user will be redirected to the specified address.", FIFU_SLUG);
    };

    // replace
    $fifu['replace']['desc'] = function () {
        _e("Define the URL of an image to be displayed in case of image not found error.", FIFU_SLUG);
    };

    // default
    $fifu['default']['desc'] = function () {
        _e("Define the URL of a default image to be displayed when you create (or update) a post type with no featured image.", FIFU_SLUG);
    };
    $fifu['default']['tab']['url'] = function () {
        _e("Image URL", FIFU_SLUG);
    };
    $fifu['default']['tab']['cpt'] = function () {
        _e("Post types", FIFU_SLUG);
    };
    $fifu['default']['cpt']['found'] = function () {
        _e("Post types found on your site: ", FIFU_SLUG);
    };
    $fifu['default']['cpt']['info'] = function () {
        _e("After adding or removing a post type, you need to restart the feature by disabling and enabling the toggle below.", FIFU_SLUG);
    };

    // content
    $fifu['content']['desc'] = function () {
        _e("Some themes don't show the featured image on posts (only on home). If that's is your case and you would like to show the featured image on posts, just enable the toggle. The featured image will appear at the beginning of the content, before the text.", FIFU_SLUG);
    };

    // hide
    $fifu['hide']['desc'] = function () {
        _e("Hide the featured media (image, video or slider) on posts but keeping its visibility on home.", FIFU_SLUG);
    };
    $fifu['hide']['exception'] = function () {
        _e("(except WooCommerce products)", FIFU_SLUG);
    };

    // configuration
    $fifu['html']['desc'] = function () {
        _e("Set featured images/videos automatically. FIFU will read the HTML of your post and use the 1st image/video URL found as featured media. It happens when you click on Publish/Update button. Images URLs must be in \"img\" tags.", FIFU_SLUG);
    };
    $fifu['html']['tab']['auto'] = function () {
        _e("Auto set", FIFU_SLUG);
    };
    $fifu['html']['tab']['all'] = function () {
        _e("Run for all posts", FIFU_SLUG);
    };
    $fifu['html']['tab']['run'] = function () {
        _e("Run now", FIFU_SLUG);
    };
    $fifu['html']['tab']['schedule'] = function () {
        _e("Scheduling", FIFU_SLUG);
    };
    $fifu['html']['tab']['important'] = function () {
        _e("Important", FIFU_SLUG);
    };
    $fifu['html']['tab']['requirement'] = function () {
        _e("Requirement", FIFU_SLUG);
    };
    $fifu['html']['position'] = function () {
        _e("image/video position", FIFU_SLUG);
    };
    $fifu['html']['first'] = function () {
        _e("use the found image/video as featured image/video", FIFU_SLUG);
    };
    $fifu['html']['hide'] = function () {
        _e("hide the image/video from content", FIFU_SLUG);
    };
    $fifu['html']['query'] = function () {
        _e("remove query strings (whatever follows the question mark sign \"?\")", FIFU_SLUG);
    };
    $fifu['html']['overwrite'] = function () {
        _e("overwrite the existing featured image/video", FIFU_SLUG);
    };
    $fifu['html']['prioritize'] = function () {
        _e("prioritize video than image (if both exist)", FIFU_SLUG);
    };
    $fifu['html']['decode'] = function () {
        _e("decode HTML entities", FIFU_SLUG);
    };
    $fifu['html']['check'] = function () {
        _e("check \"don't get URL from post content\" option, in the post editor, by default", FIFU_SLUG);
    };
    $fifu['html']['skip']['desc'] = function () {
        _e("skip URLs with", FIFU_SLUG);
    };
    $fifu['html']['skip']['placeholder'] = function () {
        _e("example.com,anotherexample,onemore", FIFU_SLUG);
    };
    $fifu['html']['cpt']['desc'] = function () {
        _e("post types", FIFU_SLUG);
    };

    // all
    $fifu['all']['desc'] = function () {
        _e("Update all your posts applying the configuration above. To repeat the process enable the toggle again.", FIFU_SLUG);
    };
    $fifu['all']['important'] = function () {
        _e("This process can take several minutes and can't be undone, so make a backup.", FIFU_SLUG);
    };
    $fifu['all']['requirement'] = function () {
        _e("If you have thousands of posts, access wp-config.php and add \"set_time_limit(1800);\" before the \"Happy publishing\" line. This ensures the process won't be killed before 30 minutes (the default value is 30 seconds).", FIFU_SLUG);
    };
    $fifu['all']['tip'] = function () {
        _e("To schedule this process (hourly, daily etc), you can use the hook fifu_event with your favorite cron event plugin.", FIFU_SLUG);
    };
    $fifu['all']['ignore'] = function () {
        _e("ignore posts that already have a featured image/video", FIFU_SLUG);
    };
    $fifu['all']['update'] = function () {
        _e("update all your posts now", FIFU_SLUG);
    };

    // metadata
    $fifu['metadata']['desc'] = function () {
        _e("Generate the database registers that helps WordPress components to work with the external images.", FIFU_SLUG);
    };
    $fifu['metadata']['generate'] = function () {
        _e("generate the missing metadata now", FIFU_SLUG);
    };

    // clean
    $fifu['clean']['desc'] = function () {
        _e("Clean the Image Metadata generated by FIFU, but not the URLs. Run it if you intend to deactivate the plugin and use only local featured images again.", FIFU_SLUG);
    };
    $fifu['clean']['disabled'] = function () {
        _e("it will be automatically disabled when finished", FIFU_SLUG);
    };

    // dimensions
    $fifu['dimensions']['desc'] = function () {
        _e("Some themes and plugins may not work correctly without image dimensions. Then you can run this feature to get the dimensions of ~150 images by minute. Problems with the product gallery can be better solved by FIFU Settings > WooCommerce > FIFU Product Gallery.", FIFU_SLUG);
    };
    $fifu['dimensions']['now'] = function () {
        _e("save the dimensions of all featured images now", FIFU_SLUG);
    };

    // schedule
    $fifu['schedule']['desc'] = function () {
        _e("If you are setting the image URLs in a nonstandard way, the images probably won't be displayed for the visitors because extra metadata is required. Here you schedule an event to run every minute and check if there are image URLs without metadata and create that.", FIFU_SLUG);
    };

    // delete
    $fifu['delete']['important'] = function () {
        _e("this plugin doesn't save images in the media library. It means that enabling the toggle below all post types that have an external featured image will no longer have any featured image. And you can't undo this action later. This also applies to FIFU galleries, videos, audios and sliders.", FIFU_SLUG);
    };
    $fifu['delete']['now'] = function () {
        _e("delete all your URLs now", FIFU_SLUG);
    };
    $fifu['delete']['requirement'] = function () {
        _e("Requirement: access Plugins -> Plugin Editor -> Select plugin to edit -> Featured Image from URL -> Select. Then change the value of FIFU_DELETE_ALL_URLS from false to true.", FIFU_SLUG);
    };

    // jetpack
    $fifu['jetpack']['desc'] = function () {
        _e("Your external images will be automatically optimized and served from a free CDN. And to make things even faster FIFU will load the thumbnails in the exact size your site needs.", FIFU_SLUG);
    };
    $fifu['jetpack']['requirement'] = function () {
        _e("for images added in an automated way, the Lazy Load feature below should be enabled.", FIFU_SLUG);
    };
    $fifu['jetpack']['toggle']['cdn'] = function () {
        _e("CDN + optimized thumbnails", FIFU_SLUG);
    };
    $fifu['jetpack']['toggle']['social'] = function () {
        _e("use CDN URLs in social tags and media RSS tags", FIFU_SLUG);
    };
    $fifu['jetpack']['toggle']['crop'] = function () {
        _e("crop featured images (to keep the aspect ratio defined by theme or WordPress media settings)", FIFU_SLUG);
    };
    $fifu['jetpack']['toggle']['content'] = function () {
        _e("apply to content images (requires Lazy Load)", FIFU_SLUG);
    };

    // audio
    $fifu['audio']['desc'] = function () {
        _e("It enables the featured audio field, where you can set the URL of an audio file, like mp3 or ogg. Then player controls will be added to the external featured image and the visitors will be able to play the audio.", FIFU_SLUG);
    };
    $fifu['audio']['requirement'] = function () {
        _e("you must set an external featured image as well.", FIFU_SLUG);
    };

    // lazy
    $fifu['lazy']['desc'] = function () {
        _e("With lazy load, images and videos won't be loaded until user scrolls to them. It makes your home (or shop) faster.", FIFU_SLUG);
    };
    $fifu['lazy']['important'] = function () {
        _e("some themes and plugins have their own lazy load implementations, causing conflicts. Your images may not load if you have more than one lazy load component running at the same time.", FIFU_SLUG);
    };

    // api
    $fifu['api']['tab']['endpoints'] = function () {
        _e("Endpoints", FIFU_SLUG);
    };
    $fifu['api']['tab']['custom'] = function () {
        _e("Custom fields", FIFU_SLUG);
    };
    $fifu['api']['tab']['product'] = function () {
        _e("Creating your first product", FIFU_SLUG);
    };
    $fifu['api']['tab']['category'] = function () {
        _e("product category", FIFU_SLUG);
    };
    $fifu['api']['tab']['variable'] = function () {
        _e("variable product", FIFU_SLUG);
    };
    $fifu['api']['tab']['variation'] = function () {
        _e("product variation", FIFU_SLUG);
    };
    $fifu['api']['tab']['batch-product'] = function () {
        _e("batch product", FIFU_SLUG);
    };
    $fifu['api']['tab']['batch-category'] = function () {
        _e("batch category", FIFU_SLUG);
    };
    $fifu['api']['tab']['post'] = function () {
        _e("post", FIFU_SLUG);
    };
    $fifu['api']['tab']['documentation'] = function () {
        _e("Documentation", FIFU_SLUG);
    };
    $fifu['api']['endpoint']['product'] = function () {
        _e("Product", FIFU_SLUG);
    };
    $fifu['api']['endpoint']['category'] = function () {
        _e("Product category", FIFU_SLUG);
    };
    $fifu['api']['endpoint']['variation'] = function () {
        _e("Product variation", FIFU_SLUG);
    };
    $fifu['api']['endpoint']['batch-product'] = function () {
        _e("Batch product", FIFU_SLUG);
    };
    $fifu['api']['endpoint']['batch-category'] = function () {
        _e("Batch category", FIFU_SLUG);
    };
    $fifu['api']['endpoint']['post'] = function () {
        _e("Post", FIFU_SLUG);
    };
    $fifu['api']['endpoint']['cpt'] = function () {
        _e("Custom post type", FIFU_SLUG);
    };
    $fifu['api']['custom']['image'] = function () {
        _e("Image", FIFU_SLUG);
    };
    $fifu['api']['custom']['title'] = function () {
        _e("Image title", FIFU_SLUG);
    };
    $fifu['api']['custom']['images'] = function () {
        _e("Product image + gallery (URLs delimited by \"|\")", FIFU_SLUG);
    };
    $fifu['api']['custom']['titles'] = function () {
        _e("Product image title + gallery titles (delimited by \"|\")", FIFU_SLUG);
    };
    $fifu['api']['custom']['video'] = function () {
        _e("Video", FIFU_SLUG);
    };
    $fifu['api']['custom']['videos'] = function () {
        _e("Product video + gallery (URLs delimited by \"|\")", FIFU_SLUG);
    };
    $fifu['api']['custom']['slider'] = function () {
        _e("Slider", FIFU_SLUG);
    };
    $fifu['api']['custom']['isbn'] = function () {
        _e("ISBN", FIFU_SLUG);
    };
    $fifu['api']['custom']['finder'] = function () {
        _e("Media finder (webpage URL)", FIFU_SLUG);
    };
    $fifu['api']['custom']['screenshot'] = function () {
        _e("Screenshot (webpage URL)", FIFU_SLUG);
    };
    $fifu['api']['custom']['key'] = function () {
        _e("Key", FIFU_SLUG);
    };
    $fifu['api']['documentation']['wordpress'] = function () {
        _e("WordPress REST API", FIFU_SLUG);
    };
    $fifu['api']['documentation']['woocommerce'] = function () {
        _e("WooCommerce REST API", FIFU_SLUG);
    };

    // FIFU shortcodes
    $fifu['shortcodes']['desc'] = function () {
        _e("Add FIFU elements anywhere with a shortcode.", FIFU_SLUG);
    };
    $fifu['shortcodes']['tab']['shortcodes'] = function () {
        _e("Display media", FIFU_SLUG);
    };
    $fifu['shortcodes']['tab']['edition'] = function () {
        _e("Edition forms", FIFU_SLUG);
    };
    $fifu['shortcodes']['column']['shortcode'] = function () {
        _e("Shortcode", FIFU_SLUG);
    };
    $fifu['shortcodes']['column']['description'] = function () {
        _e("Description", FIFU_SLUG);
    };
    $fifu['shortcodes']['column']['optional'] = function () {
        _e("Optional parameters", FIFU_SLUG);
    };
    $fifu['shortcodes']['column']['required'] = function () {
        _e("Required parameters", FIFU_SLUG);
    };
    $fifu['shortcodes']['description']['fifu'] = function () {
        _e("Displays the featured image/video", FIFU_SLUG);
    };
    $fifu['shortcodes']['description']['slider'] = function () {
        _e("Displays the featured slider", FIFU_SLUG);
    };
    $fifu['shortcodes']['description']['gallery'] = function () {
        _e("Displays the product gallery", FIFU_SLUG);
    };
    $fifu['shortcodes']['description']['form']['image'] = function () {
        _e("Input text field for featured image URL", FIFU_SLUG);
    };

    // slider
    $fifu['slider']['desc'] = function () {
        _e("This feature allows you to have a slider of images instead of a regular featured image. It's often quite useful on some types of websites, such as real estate. It can run fast even with a huge amount of big images (just enable the performance settings).", FIFU_SLUG);
    };
    $fifu['slider']['tab']['configuration'] = function () {
        _e("Configuration", FIFU_SLUG);
    };
    $fifu['slider']['featured'] = function () {
        _e("featured slider", FIFU_SLUG);
    };
    $fifu['slider']['pause'] = function () {
        _e("pause autoplay on hover", FIFU_SLUG);
    };
    $fifu['slider']['buttons'] = function () {
        _e("show prev/next buttons", FIFU_SLUG);
    };
    $fifu['slider']['start'] = function () {
        _e("start automatically", FIFU_SLUG);
    };
    $fifu['slider']['click'] = function () {
        _e("show gallery on click", FIFU_SLUG);
    };
    $fifu['slider']['thumb'] = function () {
        _e("show thumbnails gallery", FIFU_SLUG);
    };
    $fifu['slider']['counter'] = function () {
        _e("show counter", FIFU_SLUG);
    };
    $fifu['slider']['crop'] = function () {
        _e("display images in the same height", FIFU_SLUG);
    };
    $fifu['slider']['single'] = function () {
        _e("load slider on singular post types only", FIFU_SLUG);
    };
    $fifu['slider']['vertical'] = function () {
        _e("vertical mode", FIFU_SLUG);
    };
    $fifu['slider']['time'] = function () {
        _e("time between each transition (in ms)", FIFU_SLUG);
    };
    $fifu['slider']['duration'] = function () {
        _e("transition duration (in ms)", FIFU_SLUG);
    };
    $fifu['slider']['left'] = function () {
        _e("Prev button", FIFU_SLUG);
    };
    $fifu['slider']['right'] = function () {
        _e("Next button", FIFU_SLUG);
    };
    $fifu['slider']['optional'] = function () {
        _e("image URL (optional)", FIFU_SLUG);
    };

    // quick buy
    $fifu['buy']['enable'] = function () {
        _e("quick buy", FIFU_SLUG);
    };
    $fifu['buy']['text']['text'] = function () {
        _e("Text button", FIFU_SLUG);
    };
    $fifu['buy']['disclaimer']['text'] = function () {
        _e("Disclaimer", FIFU_SLUG);
    };
    $fifu['buy']['cf']['text'] = function () {
        _e("Custom field", FIFU_SLUG);
    };
    $fifu['buy']['text']['placeholder'] = function () {
        _e("buy now (optional)", FIFU_SLUG);
    };
    $fifu['buy']['disclaimer']['placeholder'] = function () {
        _e("disclaimer (optional)", FIFU_SLUG);
    };
    $fifu['buy']['cf']['placeholder'] = function () {
        _e("custom field name (optional)", FIFU_SLUG);
    };

    // social
    $fifu['social']['desc'] = function () {
        _e("Use social tags to share your posts (and their featured images) on the social media.", FIFU_SLUG);
    };
    $fifu['social']['add'] = function () {
        _e("add social tags", FIFU_SLUG);
    };
    $fifu['social']['only'] = function () {
        _e("only image tags", FIFU_SLUG);
    };

    // rss
    $fifu['rss']['desc'] = function () {
        _e("Add media RSS tags in the RSS feed. This way, services that make use of RSS, such as Google News, can show the featured images.", FIFU_SLUG);
    };
    $fifu['rss']['documentation']['publisher'] = function () {
        _e("<a href='https://support.google.com/news/publisher-center/answer/9545245?hl=en' target='_blank'>Google News: Feed content guidelines</a>", FIFU_SLUG);
    };

    // bbpress
    $fifu['bbpress']['desc'] = function () {
        _e("Allows you to add featured images/videos to bbPress forums and topics.", FIFU_SLUG);
    };
    $fifu['bbpress']['fields'] = function () {
        _e("add featured image/video fields to bbPress forms", FIFU_SLUG);
    };
    $fifu['bbpress']['title'] = function () {
        _e("display featured image before forum/topic title", FIFU_SLUG);
    };
    $fifu['bbpress']['avatar'] = function () {
        _e("replace profile picture by featured image", FIFU_SLUG);
    };
    $fifu['bbpress']['copy'] = function () {
        _e("copy the featured image/video to the forum/topic content", FIFU_SLUG);
    };

    // title
    $fifu['title']['desc'] = function () {
        _e("Set the title of a featured image with the post title.", FIFU_SLUG);
    };
    $fifu['title']['copy'] = function () {
        _e("copy the post title to FIFU alt/title field (it has effect when you click on Publish button in the post editor)", FIFU_SLUG);
    };
    $fifu['title']['always'] = function () {
        _e("always use the post title as image title (it will ignore FIFU alt/title field)", FIFU_SLUG);
    };

    // video
    $fifu['video']['desc'] = function () {
        _e("FIFU supports videos from YouTube, Vimeo, Twitter, Imgur, 9GAG, Cloudinary, Tumblr, Publitio, JW Player, VideoPress, Sprout, Odysee, Rumble, Dailymotion and Cloudflare Stream. External and local video files are supported as well.", FIFU_SLUG);
    };
    $fifu['video']['tab']['video'] = function () {
        _e("Featured video", FIFU_SLUG);
    };
    $fifu['video']['tab']['local'] = function () {
        _e("Video files", FIFU_SLUG);
    };
    $fifu['video']['local']['desc'] = function () {
        _e("It's possible to use mp4 videos from your media library as featured videos. However it's required to create a video thumbnail, that will be stored in your media library. For that, in the \"Feature video\" meta box, forward the video to a frame you like and click on \"set this frame as thumbnail\" button. Save the post and that's it.", FIFU_SLUG);
    };
    $fifu['video']['external']['desc'] = function () {
        _e("For external videos, webm and ogg formats are supported as well. Requirement: you must also set an external featured image (video thumbnail).", FIFU_SLUG);
    };

    // thumbnail
    $fifu['thumbnail']['desc'] = function () {
        _e("Show the video thumbnail instead of the video. Thumbnails are images, so they are loaded much faster than embedded videos.", FIFU_SLUG);
    };

    // play
    $fifu['play']['desc'] = function () {
        _e("Add play button to video thumbnail. Clicking on that, the video starts inline or in a lightbox.", FIFU_SLUG);
    };
    $fifu['play']['hide'] = function () {
        _e("hide from grid", FIFU_SLUG);
    };

    // width
    $fifu['width']['desc'] = function () {
        _e("Define a minimum width that a theme area should have to show a video. FIFU automatically shows a thumbnail when the minimum width is not reached.", FIFU_SLUG);
    };

    // black
    $fifu['controls']['desc'] = function () {
        _e("You can disable video controls here.", FIFU_SLUG);
    };
    // mouseover
    $fifu['mouseover']['desc'] = function () {
        _e("Play a video on \"mouseover\" and pause on \"mouseout\". Requires \"Video Controls\" and \"Mute\".", FIFU_SLUG);
    };

    // autoplay
    $fifu['autoplay']['desc'] = function () {
        _e("Autoplay videos (available for YouTube, Vimeo and local videos). Requires \"Mute\".", FIFU_SLUG);
    };

    // loop
    $fifu['loop']['desc'] = function () {
        _e("Loop videos (available for YouTube, Vimeo and local videos).", FIFU_SLUG);
    };

    // mute
    $fifu['mute']['desc'] = function () {
        _e("Start the videos without audio (available for YouTube, Vimeo and local videos).", FIFU_SLUG);
    };

    // background
    $fifu['background']['desc'] = function () {
        _e("Start the videos in background, which means autoplay, no controls and no sound.", FIFU_SLUG);
    };

    // privacy
    $fifu['privacy']['desc'] = function () {
        _e("The Privacy Enhanced Mode of the YouTube embedded player prevents the use of views of embedded YouTube content from influencing the viewer’s browsing experience on YouTube.", FIFU_SLUG);
    };

    // zoom
    $fifu['zoom']['desc'] = function () {
        _e("Disable lightbox and zoom from image gallery.", FIFU_SLUG);
    };

    // category
    $fifu['category']['desc'] = function () {
        _e("Set one image for each category. The chosen image is the featured image from the most recent product from that category.", FIFU_SLUG);
    };

    // gallery
    $fifu['gallery']['desc'] = function () {
        _e("It is known that some galleries provided by some themes work only when the dimensions of the images are saved in the database, which is often impracticable due to the slowness of this process. So FIFU offers a product gallery that does not depend on the dimensions of the external images to work correctly. You can configure the behavior of this gallery in the \"Featured slider\" tab. To change the aspect ratio, access \"Featured image > Same Height > Size ratio\".", FIFU_SLUG);
    };
    $fifu['gallery']['toggle'] = function () {
        _e("product gallery", FIFU_SLUG);
    };
    $fifu['gallery']['adaptive'] = function () {
        _e("adaptive height", FIFU_SLUG);
    };
    $fifu['gallery']['videos'] = function () {
        _e("videos before images", FIFU_SLUG);
    };

    // buy
    $fifu['buy']['desc'] = function () {
        _e("That's a faster alternative to the WooCommerce single product page. Click on a product image from the shop page to have the main product information in a lightbox. The \"Buy Now\" button adds the product to the cart and redirects to the checkout page.", FIFU_SLUG);
    };

    // variable
    $fifu['variable']['desc'] = function () {
        _e("Add FIFU fields (featured image and image gallery) to product variations.", FIFU_SLUG);
    };
    $fifu['variable']['important'] = function () {
        _e("variation image gallery requires FIFU Product Gallery.", FIFU_SLUG);
    };

    // order email
    $fifu['order-email']['desc'] = function () {
        _e("Add product images to order emails.", FIFU_SLUG);
    };

    // import
    $fifu['import']['desc'] = function () {
        _e("Use FIFU with WooCommerce import.", FIFU_SLUG);
    };
    $fifu['import']['tab']['import'] = function () {
        _e("Importing products...", FIFU_SLUG);
    };
    $fifu['import']['tab']['custom'] = function () {
        _e("Custom fields", FIFU_SLUG);
    };
    $fifu['import']['tab']['priority'] = function () {
        _e("Priority", FIFU_SLUG);
    };
    $fifu['import']['import']['csv'] = function () {
        _e("CSV example", FIFU_SLUG);
    };
    $fifu['import']['custom']['key'] = function () {
        _e("Key", FIFU_SLUG);
    };
    $fifu['import']['custom']['image'] = function () {
        _e("Featured image URL", FIFU_SLUG);
    };
    $fifu['import']['custom']['alt'] = function () {
        _e("Featured image title", FIFU_SLUG);
    };
    $fifu['import']['custom']['video'] = function () {
        _e("Featured video URL", FIFU_SLUG);
    };
    $fifu['import']['custom']['images'] = function () {
        _e("Product image URL + gallery URLs (delimited by \"|\")", FIFU_SLUG);
    };
    $fifu['import']['custom']['titles'] = function () {
        _e("Product image title + gallery titles (delimited by \"|\")", FIFU_SLUG);
    };
    $fifu['import']['custom']['videos'] = function () {
        _e("Product video URL + gallery URLs (delimited by \"|\")", FIFU_SLUG);
    };
    $fifu['import']['custom']['slider'] = function () {
        _e("Featured slider URLs (delimited by \"|\")", FIFU_SLUG);
    };
    $fifu['import']['custom']['isbn'] = function () {
        _e("ISBN", FIFU_SLUG);
    };
    $fifu['import']['custom']['finder'] = function () {
        _e("Media finder (webpage URL)", FIFU_SLUG);
    };
    $fifu['import']['custom']['screenshot'] = function () {
        _e("Screenshot (webpage URL)", FIFU_SLUG);
    };
    $fifu['import']['priority']['lists'] = function () {
        _e("prioritize fifu_list_video_url than fifu_list_url (if both exist) ", FIFU_SLUG);
    };

    // addon
    $fifu['addon']['desc'] = function () {
        _e("FIFU automatically adds its add-on to WP All Import.", FIFU_SLUG);
    };
    $fifu['addon']['tab']['import'] = function () {
        _e("Importing products...", FIFU_SLUG);
    };
    $fifu['addon']['tab']['faq'] = function () {
        _e("FAQ", FIFU_SLUG);
    };
    $fifu['addon']['import']['csv'] = function () {
        _e("CSV example", FIFU_SLUG);
    };
    $fifu['addon']['faq']['woocommerce'] = function () {
        _e("Importing variable products to WooCommerce", FIFU_SLUG);
    };
    $fifu['addon']['faq']['variation-child-xml'] = function () {
        _e('Examples for "Variations As Child XML Elements"', FIFU_SLUG);
    };
    $fifu['addon']['faq']['xml'] = function () {
        _e("XML", FIFU_SLUG);
    };
    $fifu['addon']['faq']['words']['section'] = function () {
        _e("Section", FIFU_SLUG);
    };
    $fifu['addon']['faq']['words']['for'] = function () {
        _e("For", FIFU_SLUG);
    };
    $fifu['addon']['faq']['words']['description'] = function () {
        _e("Description", FIFU_SLUG);
    };
    $fifu['addon']['faq']['template'] = function () {
        _e("import template", FIFU_SLUG);
    };
    $fifu['addon']['faq']['how']['examples'] = function () {
        _e("Examples", FIFU_SLUG);
    };
    $fifu['addon']['faq']['how']['not'] = function () {
        _e("How NOT to configure WP All Import", FIFU_SLUG);
    };
    $fifu['addon']['faq']['how']['to'] = function () {
        _e("How to configure FIFU Add-On", FIFU_SLUG);
    };
    $fifu['addon']['faq']['section']['images'] = function () {
        _e("Images", FIFU_SLUG);
    };
    $fifu['addon']['faq']['section']['addon'] = function () {
        _e("FIFU Add-On", FIFU_SLUG);
    };
    $fifu['addon']['faq']['section']['cf'] = function () {
        _e("Custom Fields", FIFU_SLUG);
    };
    $fifu['addon']['faq']['section']['record'] = function () {
        _e("Record Matching", FIFU_SLUG);
    };
    $fifu['addon']['faq']['for']['delimiter'] = function () {
        _e("List of URLs delimited by comma", FIFU_SLUG);
    };
    $fifu['addon']['faq']['for']['columns'] = function () {
        _e("URLs in different columns", FIFU_SLUG);
    };
    $fifu['addon']['faq']['description']['empty'] = function () {
        _e("DON'T add filenames or URLs. Keep the text fields EMPTY", FIFU_SLUG);
    };
    $fifu['addon']['faq']['description']['woocommerce'] = function () {
        _e("For WooCommerce, DON'T use \"Featured image (URL)\" field", FIFU_SLUG);
    };
    $fifu['addon']['faq']['description']['cf'] = function () {
        _e("DON'T add FIFU custom fields", FIFU_SLUG);
    };
    $fifu['addon']['faq']['description']['update'] = function () {
        _e("DON'T check \"Update existing products with the data in your file > Update all data\"", FIFU_SLUG);
    };
    $fifu['addon']['faq']['description']['choose'] = function () {
        _e("DON'T check \"Update existing products with the data in your file > Choose which data to update > Images > Update all images\"", FIFU_SLUG);
    };
    $fifu['addon']['faq']['description']['gallery'] = function () {
        _e("Use \"Product image URL + gallery URLs\", even if you have 1 URL only", FIFU_SLUG);
    };
    $fifu['addon']['faq']['description']['delimiter'] = function () {
        _e("Enter a comma in the \"List delimiter\" field", FIFU_SLUG);
    };

    // key
    $fifu['key']['desc'] = function () {
        _e("Please insert your email and license key below to receive updates and use this plugin without limitations.", FIFU_SLUG);
    };
    $fifu['key']['buy'] = function () {
        _e("if you intend to use FIFU in multiple distinct sites you can buy more license keys <a href='https://fifu.app/#price' target='_blank'>here</a>", FIFU_SLUG);
    };
    $fifu['key']['renew'] = function () {
        _e("you can renew your license key(s) or get more information about that <a href='https://ws.featuredimagefromurl.com/keys/' target='_blank'>here</a>", FIFU_SLUG);
    };
    $fifu['key']['email'] = function () {
        _e("Email", FIFU_SLUG);
    };
    $fifu['key']['address'] = function () {
        _e("Email address where you received the license key", FIFU_SLUG);
    };
    $fifu['key']['key'] = function () {
        _e("License key", FIFU_SLUG);
    };
    $fifu['key']['tab']['activation'] = function () {
        _e("Activation", FIFU_SLUG);
    };
    $fifu['key']['tab']['documentation'] = function () {
        _e("Documentation", FIFU_SLUG);
    };
    $fifu['key']['documentation'] = function () {
        _e("FIFU activation is based on the domain. So submitting your license key on the site example.com, [subdomain].example.com or example.com/[anything] will activate the domain example.com. After that you could use the same license key on the sites example.com, [subdomain].example.com and example.com/[anything]. You also could use the same license key to activate a second domain for your test/development/stage site. If your domain has changed, please contact the support.", FIFU_SLUG);
    };
    $fifu['key']['important'] = function () {
        _e("even though with 1 license you could use FIFU on unlimited sites from the same domain, the technical support is still limited to 1 site.", FIFU_SLUG);
    };

    // cloud

    $fifu['cloud']['details']['social'] = function () {
        _e("FIFU Cloud improves the social sharing by smart cropping the images in the size defined by the main social medias (~1200x630). The smart crop is specially useful when you're sharing a portrait image, because a standard central crop may lead to a picture where the main object is not shown or people appears without their heads.", FIFU_SLUG);
    };
    $fifu['cloud']['details']['rss'] = function () {
        _e("FIFU Cloud improves the RSS feed by smart cropping the images in the size defined by Google News (~1200x800). The smart crop is specially useful when you're sharing a portrait image, because a standard central crop may lead to a picture where the main object is not shown or people appears without their heads.", FIFU_SLUG);
    };
    $fifu['cloud']['details']['photon'] = function () {
        _e("FIFU Cloud can also serve optimized thumbnails from a global CDN, but with many advantages: image storage (not only cache), supports any image source, technical support, smart crop, hotlink protection and more.", FIFU_SLUG);
    };
    $fifu['cloud']['details']['click'] = function () {
        _e("FIFU Clouds improves the security of your images by offering hotlink protection. With that, even if a bot or someone else gets image URLs from the source code of your website, it won't be possible to embed them on other websites. An error message is displayed instead of the images.", FIFU_SLUG);
    };
    $fifu['cloud']['details']['dimensions'] = function () {
        _e("FIFU Cloud eliminates the need for this feature by writing the image dimensions in the image URL. So there are no waiting or style issues anymore.", FIFU_SLUG);
    };
    $fifu['cloud']['details']['library'] = function () {
        _e("FIFU Cloud can work as an alternative to the WordPress media library. Both store images, but FIFU Cloud process them in the cloud, while WordPress core consumes a lot of your website resources. Also, FIFU Cloud is able to process and store thousands of images simultaneously in a few seconds while the media library will work with one image at a time.", FIFU_SLUG);
    };
    $fifu['cloud']['details']['crop'] = function () {
        _e("FIFU Cloud overcomes this feature by serving real cropped thumbnails in the exact size requested by your theme. FIFU Cloud doesn't require any configuration or CSS knowledge and will load your images much faster. Moreover, its smart crop makes use of AI to make your cropped images more attractive.", FIFU_SLUG);
    };
    $fifu['cloud']['details']['replace'] = function () {
        _e("FIFU Cloud prevents image loss by saving your local or external images in the cloud.", FIFU_SLUG);
    };
    $fifu['cloud']['details']['play'] = function () {
        _e("FIFU Cloud overcomes this feature by drawing a play icon on the video image thumbnails. With that, the plugin doesn't need to add any additional HTML or CSS, what might conflict with the theme.", FIFU_SLUG);
    };

    return $fifu;
}

function fifu_get_strings_meta_box() {
    $fifu = array();

    // word
    $fifu['word']['remove'] = function () {
        _e("Remove", FIFU_SLUG);
    };

    // common
    $fifu['common']['alt'] = function () {
        _e("alt/title attribute (optional)", FIFU_SLUG);
    };
    $fifu['common']['image'] = function () {
        _e("Image URL", FIFU_SLUG);
    };
    $fifu['common']['preview'] = function () {
        _e("Preview", FIFU_SLUG);
    };
    $fifu['common']['video'] = function () {
        _e("Video URL", FIFU_SLUG);
    };
    $fifu['common']['capture'] = function () {
        _e("set this frame as thumbnail", FIFU_SLUG);
    };

    // details
    $fifu['detail']['ratio'] = function () {
        _e("Ratio", FIFU_SLUG);
    };
    $fifu['detail']['eg'] = function () {
        _e("e.g.:", FIFU_SLUG);
    };

    // titles
    $fifu['title']['category']['video'] = function () {
        _e("Featured video", FIFU_SLUG);
    };
    $fifu['title']['category']['image'] = function () {
        _e("Featured image", FIFU_SLUG);
    };

    // video
    $fifu['video']['remove'] = function () {
        _e("remove external featured video", FIFU_SLUG);
    };

    // image
    $fifu['image']['ignore'] = function () {
        _e("don't get URL from post content", FIFU_SLUG);
    };
    $fifu['image']['keywords'] = function () {
        _e("Image URL or Keywords", FIFU_SLUG);
    };
    $fifu['image']['remove'] = function () {
        _e("remove external featured image", FIFU_SLUG);
    };
    $fifu['image']['sirv']['add'] = function () {
        _e("Add image from Sirv", FIFU_SLUG);
    };
    $fifu['image']['sirv']['choose'] = function () {
        _e("Choose Sirv image", FIFU_SLUG);
    };
    $fifu['image']['upload'] = function () {
        _e("upload to media library", FIFU_SLUG);
    };

    return $fifu;
}

function fifu_get_strings_meta_box_php() {
    $fifu = array();

    // common
    $fifu['common']['wait'] = function () {
        return __("Please wait a few seconds...", FIFU_SLUG);
    };
    $fifu['common']['image'] = function () {
        return __("Image URL", FIFU_SLUG);
    };
    $fifu['common']['video'] = function () {
        return __("Video URL", FIFU_SLUG);
    };

    // wait
    $fifu['title']['product']['image'] = function () {
        return __("Product image", FIFU_SLUG);
    };
    $fifu['title']['product']['images'] = function () {
        return __("Image gallery", FIFU_SLUG);
    };
    $fifu['title']['product']['video'] = function () {
        return __("Featured video", FIFU_SLUG);
    };
    $fifu['title']['product']['videos'] = function () {
        return __("Video gallery", FIFU_SLUG);
    };
    $fifu['title']['product']['slider'] = function () {
        return __("Featured slider", FIFU_SLUG);
    };
    $fifu['title']['post']['image'] = function () {
        return __("Featured image", FIFU_SLUG);
    };
    $fifu['title']['post']['video'] = function () {
        return __("Featured video", FIFU_SLUG);
    };
    $fifu['title']['post']['slider'] = function () {
        return __("Featured slider", FIFU_SLUG);
    };
    $fifu['title']['post']['isbn'] = function () {
        return __("ISBN", FIFU_SLUG);
    };
    $fifu['title']['post']['screenshot'] = function () {
        return __("Screenshot", FIFU_SLUG);
    };
    $fifu['title']['post']['finder'] = function () {
        return __("Media finder", FIFU_SLUG);
    };
    $fifu['title']['post']['audio'] = function () {
        return __("Featured audio", FIFU_SLUG);
    };
    $fifu['title']['post']['redirection'] = function () {
        return __("Page redirection", FIFU_SLUG);
    };

    // variation
    $fifu['variation']['field'] = function () {
        return __("Product Image (URL)", FIFU_SLUG);
    };
    $fifu['variation']['info'] = function () {
        return __("Powered by Featured Image from URL plugin", FIFU_SLUG);
    };
    $fifu['variation']['image'] = function () {
        return __("Image URL", FIFU_SLUG);
    };
    $fifu['variation']['images'] = function () {
        return __("Gallery Image (URL)", FIFU_SLUG);
    };
    $fifu['variation']['upload'] = function () {
        return __("upload to media library", FIFU_SLUG);
    };

    return $fifu;
}

function fifu_get_strings_wai() {
    $fifu = array();

    // titles
    $fifu['title']['image'] = function () {
        return __("Featured image (URL)", FIFU_SLUG);
    };
    $fifu['title']['title'] = function () {
        return __("Featured image title", FIFU_SLUG);
    };
    $fifu['title']['video'] = function () {
        return __("Featured video (URL)", FIFU_SLUG);
    };
    $fifu['title']['images'] = function () {
        return __("Product image URL + gallery URLs", FIFU_SLUG);
    };
    $fifu['title']['titles'] = function () {
        return __("Product image title + gallery titles", FIFU_SLUG);
    };
    $fifu['title']['videos'] = function () {
        return __("Product video URL + gallery URLs", FIFU_SLUG);
    };
    $fifu['title']['slider'] = function () {
        return __("Featured slider (URLs)", FIFU_SLUG);
    };
    $fifu['title']['delimiter'] = function () {
        return __("List delimiter", FIFU_SLUG);
    };
    $fifu['title']['isbn'] = function () {
        return __("ISBN", FIFU_SLUG);
    };
    $fifu['title']['finder'] = function () {
        return __("Media finder (webpage URL)", FIFU_SLUG);
    };
    $fifu['title']['screenshot'] = function () {
        return __("Screenshot (webpage URL)", FIFU_SLUG);
    };

    // info
    $fifu['info']['delimited'] = function () {
        return __("Delimited by |", FIFU_SLUG);
    };
    $fifu['info']['default'] = function () {
        return __("Default values is |", FIFU_SLUG);
    };
    $fifu['info']['finder'] = function () {
        return __("Works with \"Auto set featured media using web page address\"", FIFU_SLUG);
    };
    $fifu['info']['screenshot'] = function () {
        return __("Works with \"Auto set screenshot as featured image\"", FIFU_SLUG);
    };

    return $fifu;
}

function fifu_get_strings_widget() {
    $fifu = array();

    // titles
    $fifu['title']['media'] = function () {
        return __("Featured media", FIFU_SLUG);
    };
    $fifu['title']['grid'] = function () {
        return __("Featured grid", FIFU_SLUG);
    };
    $fifu['title']['gallery'] = function () {
        return __("Product gallery", FIFU_SLUG);
    };

    // description
    $fifu['description']['media'] = function () {
        return __("Displays the featured image, video or slider from the current post, page or custom post type.", FIFU_SLUG);
    };
    $fifu['description']['grid'] = function () {
        return __("Displays the images from featured slider in a grid format.", FIFU_SLUG);
    };
    $fifu['description']['gallery'] = function () {
        return __("Displays the product gallery.", FIFU_SLUG);
    };

    return $fifu;
}

function fifu_get_strings_quick_edit() {
    $fifu = array();

    // titles
    $fifu['title']['image'] = function () {
        return __("Featured image", FIFU_SLUG);
    };
    $fifu['title']['video'] = function () {
        return __("Featured video", FIFU_SLUG);
    };
    $fifu['title']['slider'] = function () {
        return __("Featured slider", FIFU_SLUG);
    };
    $fifu['title']['search'] = function () {
        return __("Image search", FIFU_SLUG);
    };
    $fifu['title']['gallery']['image'] = function () {
        return __("Image gallery", FIFU_SLUG);
    };
    $fifu['title']['gallery']['video'] = function () {
        return __("Video gallery", FIFU_SLUG);
    };

    // tips
    $fifu['tip']['column'] = function () {
        return __("Quick edit", FIFU_SLUG);
    };
    $fifu['tip']['image'] = function () {
        return __("Set the featured image using an image URL", FIFU_SLUG);
    };
    $fifu['tip']['video'] = function () {
        return __("Set the featured video using a video URL", FIFU_SLUG);
    };
    $fifu['tip']['search'] = function () {
        return __("Search for Unsplash images. Example: sun,sea", FIFU_SLUG);
    };

    // placeholder
    $fifu['url']['image'] = function () {
        return __("Image URL", FIFU_SLUG);
    };
    $fifu['url']['video'] = function () {
        return __("Video URL", FIFU_SLUG);
    };
    $fifu['image']['keywords'] = function () {
        return __("Keywords", FIFU_SLUG);
    };

    return $fifu;
}

function fifu_get_strings_help() {
    $fifu = array();

    // title
    $fifu['title']['examples'] = function () {
        return __("Examples", FIFU_SLUG);
    };
    $fifu['title']['keywords'] = function () {
        return __("Keywords", FIFU_SLUG);
    };
    $fifu['title']['more'] = function () {
        return __("More", FIFU_SLUG);
    };
    $fifu['title']['url'] = function () {
        return __("Image URL", FIFU_SLUG);
    };
    $fifu['desc']['empty'] = function () {
        return __("If empty, returns a random image.", FIFU_SLUG);
    };
    $fifu['desc']['size'] = function () {
        return __("You can define the images dimensions at FIFU Settings > Featured image > Unsplash Image Size. For sharing on Facebook, the best size is 1200x630.", FIFU_SLUG);
    };
    $fifu['desc']['more'] = function () {
        return __("FIFU is able to auto set the images based on the post title, post tags, external web page address and more. Take a look at FIFU Settings > Automatic.", FIFU_SLUG);
    };

    return $fifu;
}

function fifu_get_strings_cloud() {
    $fifu = array();

    // title
    $fifu['title']['price'] = function () {
        return _e("Pricing", FIFU_SLUG);
    };
    $fifu['title']['getting'] = function () {
        return _e("Getting started", FIFU_SLUG);
    };
    $fifu['title']['signup'] = function () {
        return _e("Sign up", FIFU_SLUG);
    };
    $fifu['title']['login'] = function () {
        return _e("Log in", FIFU_SLUG);
    };
    $fifu['title']['logout'] = function () {
        return _e("Log out", FIFU_SLUG);
    };
    $fifu['title']['cancel'] = function () {
        return _e("Account", FIFU_SLUG);
    };
    $fifu['title']['payment'] = function () {
        return _e("Payment method and billing information", FIFU_SLUG);
    };
    $fifu['title']['add'] = function () {
        return _e("Upload to Cloud", FIFU_SLUG);
    };
    $fifu['title']['delete'] = function () {
        return _e("Delete from Cloud", FIFU_SLUG);
    };
    $fifu['title']['media'] = function () {
        return _e("Link local image URLs to FIFU plugin", FIFU_SLUG);
    };
    $fifu['title']['billing'] = function () {
        return _e("Billing", FIFU_SLUG);
    };

    // tabs
    $fifu['tabs']['welcome'] = function () {
        return _e("Welcome", FIFU_SLUG);
    };
    $fifu['tabs']['login'] = function () {
        return _e("Log in", FIFU_SLUG);
    };
    $fifu['tabs']['send'] = function () {
        return _e("Upload", FIFU_SLUG);
    };
    $fifu['tabs']['delete'] = function () {
        return _e("Delete", FIFU_SLUG);
    };
    $fifu['tabs']['media'] = function () {
        return _e("Local images", FIFU_SLUG);
    };
    $fifu['tabs']['trash'] = function () {
        return _e("Trash", FIFU_SLUG);
    };
    $fifu['tabs']['bill'] = function () {
        return _e("Billing", FIFU_SLUG);
    };

    // info
    $fifu['icon']['cancel'] = function () {
        _e("You'll receive a confirmation link by email.", FIFU_SLUG);
    };
    $fifu['icon']['payment'] = function () {
        _e("You'll receive an access link by email.", FIFU_SLUG);
    };

    // support
    $fifu['support']['whats'] = function () {
        _e("FIFU Cloud is a cloud service that stores your images in the Google Cloud infrastructure. The images are not only saved, but optimized and quickly served from the global Google's Edge Network. Thumbnails are automatically created for each image and all are served in webp format.", FIFU_SLUG);
    };
    $fifu['support']['save'] = function () {
        _e("Never lose an image again");
    };
    $fifu['support']['fast'] = function () {
        _e("Images loaded much faster");
    };
    $fifu['support']['process'] = function () {
        _e("Images processed in the cloud");
    };
    $fifu['support']['price'] = function () {
        _e("Pay per stored image");
    };
    $fifu['support']['smart'] = function () {
        _e("Smart crop");
    };
    $fifu['support']['hotlink'] = function () {
        _e("Hotlink protection");
    };
    $fifu['support']['save-desc'] = function () {
        _e("Image sources may remove their images or change their image URLs periodically due to an internal restructuring or even in order to prevent their images from being embedded on other websites. When images are deleted or their URLs change, websites that were embedding those images are seriously impacted because the images are lost and cannot be restored. FIFU Cloud solves that by saving your embedded images to the cloud and providing fixed image URLs to access them. Current URLs will be replaced by FIFU Cloud URLs, in a process that can be reverted.");
    };
    $fifu['support']['fast-desc'] = function () {
        _e("A big disadvantage of having external images embedded on your website is that you don't have thumbnails. Without thumbnails, your website loads the same huge image file on desktop or mobile, post or homepage. And sometimes the image is not optimized or is hosted on a slow server. FIFU Cloud solves all those problems by storing and serving optimized thumbnails from a fast CDN. Your visitors receive on each accessed page only the smallest image files necessary to display the images without quality loss. And the smaller the files, the faster the images are rendered.");
    };
    $fifu['support']['process-desc'] = function () {
        _e("Websites were not made to process images. But when you save an image in the media library, the WordPress core and even your theme and plugins start threads to process the image locally, converting, duplicating, rotating, resizing, cropping, compressing, etc. Depending on the number of images, it can take weeks and eventually the website needs to repeat the whole process again. It consumes a lot of storage, memory and processing, which can make the website slow for the users. But FIFU Cloud doesn't use your computing resources. We process your images 100% in Google Cloud servers. The power of the cloud allows us to process and store thousands of images simultaneously in seconds.");
    };
    $fifu['support']['price-desc'] = function () {
        _e("Similar cloud services often charge for the amount of hits to the images. Or they sell static plans where you pay for the amount of storage available, even if unused. But FIFU Cloud charges only for the daily average of stored images, every 30-day period. You don't pay for thumbnails. Example: on the first day, you stored 1000 images; ten days later, you deleted them all; ten days later, you added 1100, which were stored for ten days. So, on average, you used 700 images per day in a 30-day period and you will pay $3.50. If there are no changes in the next period, the average will be 1100 and the paid value will be $4.95. And if in the following period you remove all the images, there will be no cost.");
    };
    $fifu['support']['price-desc2'] = function () {
        _e("Similar cloud services often charge for the amount of hits to the images. Or they sell static plans where you pay for the amount of storage available, even if unused. But FIFU Cloud will charge only for the daily average of stored images, every 30-day period. You don't pay for thumbnails. Example: on the first day, you stored 1000 images; ten days later, you deleted them all; ten days later, you added 1100, which were stored for ten days. So, on average, you used 700 images per day in a 30-day period and you only pay for that. If there are no changes in the next period, the average will be 1100 and the paid value increases. And if in the following period you remove all the images, there will be no cost.");
    };
    $fifu['support']['smart-desc'] = function () {
        _e("WordPress themes and social media platforms work with predefined sizes of images and, when they receive an image with non-standard dimensions, they crop the central area of the image, which is not very smart, as often the main object is not located in the center. Facebook, Twitter and LinkedIn, for example, display the featured image at ~1200×630 pixels (landscape orientation), and when you try to share a full body photo (portrait), the cropped person will likely lose its head and feet. But FIFU Cloud detects faces and objects so its cropped images can show what really matters, without compromising style or information.");
    };
    $fifu['support']['hotlink-desc'] = function () {
        _e("You cannot prevent bots from accessing and extracting content from your website, including text and image URLs. And unfortunately, after being stored, the data set can be used to replicate your entire website elsewhere, which will receive visitors that should be yours by right. Fortunately, FIFU Cloud offers hotlink protection, which means that other websites, with the exception of social media, will not be able to display your images. And while this doesn't completely solve the problem, it will certainly inhibit web scraping on your website, because posts with blocked images become much less attractive.");
    };

    // getting started
    $fifu['getting']['important'] = function () {
        _e("Important");
    };
    $fifu['getting']['signup']['title'] = function () {
        _e("Sign up");
    };
    $fifu['getting']['login']['title'] = function () {
        _e("Log in");
    };
    $fifu['getting']['upload']['title'] = function () {
        _e("Upload");
    };
    $fifu['getting']['signup']['description'] = function () {
        _e("email confirmation + Stripe subscription");
    };
    $fifu['getting']['login']['description'] = function () {
        _e("two-factor authentication app");
    };
    $fifu['getting']['upload']['description'] = function () {
        _e("selected images");
    };
    $fifu['getting']['description'] = function () {
        _e("in order to load the thumbnails in their best sizes, FIFU Settings > Performance > Lazy Load must be enabled, otherwise the images won't be displayed. If you have any issues or would like to suggest improvements, please contact us at <b>cloud@fifu.app</b>.");
    };

    // pricing
    $fifu['pricing']['table']['quantity'] = function () {
        _e("Quantity of images");
    };
    $fifu['pricing']['desc'] = function () {
        _e("You pay for the daily average of stored images in FIFU Cloud, every 30-day period.");
    };
    $fifu['pricing']['thumbnails'] = function () {
        _e("You don't pay for the multiple image thumbnails created automatically by the service. Consider only the amount of images you uploaded to FIFU Cloud.");
    };
    $fifu['pricing']['example'] = function () {
        _e("Price calculation example");
    };
    $fifu['pricing']['table']['interval'] = function () {
        _e("30-day period interval");
    };
    $fifu['pricing']['table']['days'] = function () {
        _e("Number of days");
    };
    $fifu['pricing']['table']['stored'] = function () {
        _e("Quantity of images in FIFU Cloud");
    };
    $fifu['pricing']['table']['average'] = function () {
        _e("30-day average usage");
    };
    $fifu['pricing']['table']['price'] = function () {
        _e("Price per image");
    };
    $fifu['pricing']['table']['tier'] = function () {
        _e("Yours");
    };
    $fifu['pricing']['table']['total'] = function () {
        _e("Total price");
    };

    // upload
    $fifu['upload']['desc'] = function () {
        _e("When an image is uploaded to the cloud, it starts to generate costs from the uploaded date.");
    };
    $fifu['upload']['automatic']['title'] = function () {
        _e("Automatic upload");
    };
    $fifu['upload']['automatic']['desc'] = function () {
        _e("uploads external images to the cloud automatically.");
    };

    // delete
    $fifu['delete']['desc'] = function () {
        _e("When an image is deleted from the cloud, you stop being charged for it from the next day.");
    };

    // media
    $fifu['media']['desc'] = function () {
        _e("Before uploading local images to the cloud, the URLs need to be copied to FIFU custom fields, clicking on \"link\" button. It is highly recommended to have a backup of the database, because some post metadata will be replaced, making FIFU responsible for displaying the images. Also, you shouldn't delete images from the media library before making sure they're saved in the cloud, otherwise you will lose the images.");
    };

    // billing
    $fifu['billing']['desc'] = function () {
        _e("FIFU Cloud will charge you for the daily average of stored images, every 30-day period. The following data is updated hourly.");
    };
    $fifu['billing']['current'] = function () {
        _e("Current 30-day period");
    };
    $fifu['billing']['tiers'] = function () {
        _e("Tiers");
    };
    $fifu['billing']['column']['start'] = function () {
        _e("start date");
    };
    $fifu['billing']['column']['end'] = function () {
        _e("end date");
    };
    $fifu['billing']['column']['average'] = function () {
        _e("daily average of stored images");
    };
    $fifu['billing']['column']['cost'] = function () {
        _e("current cost");
    };

    // keys
    $fifu['keys']['header'] = function () {
        _e("Multiple image selection");
    };
    $fifu['keys']['adjacent'] = function () {
        _e("Adjacent");
    };
    $fifu['keys']['non-adjacent'] = function () {
        _e("Non-adjacent");
    };
    $fifu['keys']['shift'] = function () {
        _e("click on the first image, press <b>SHIFT</b> key and hold it. While holding Shift, click on the last image.");
    };
    $fifu['keys']['ctrl'] = function () {
        _e("click on the first image, press <b>CTRL</b> key and hold it. While holding Ctrl, click each of the other images you want to select.");
    };

    return $fifu;
}

function fifu_get_strings_uninstall() {
    $fifu = array();

    $fifu['button']['text']['clean'] = function () {
        return __("Clean metadata and deactivate", FIFU_SLUG);
    };
    $fifu['button']['text']['deactivate'] = function () {
        return __("Deactivate", FIFU_SLUG);
    };
    $fifu['button']['description']['clean'] = function () {
        return __("if you don't intend to use FIFU again", FIFU_SLUG);
    };
    $fifu['button']['description']['deactivate'] = function () {
        return __("if it's a temporary deactivation", FIFU_SLUG);
    };
    $fifu['text']['why'] = function () {
        return __("Why are you deactivating FIFU?", FIFU_SLUG);
    };
    $fifu['text']['optional'] = function () {
        return __("Optional", FIFU_SLUG);
    };
    $fifu['text']['email'] = function () {
        return __("We'll reply you in 8 hours.", FIFU_SLUG);
    };
    $fifu['text']['reason']['conflict'] = function () {
        return __("Doesn't work with a theme, plugin or URL...", FIFU_SLUG);
    };
    $fifu['text']['reason']['pro'] = function () {
        return __("Works well, but I would need a new or pro feature...", FIFU_SLUG);
    };
    $fifu['text']['reason']['seo'] = function () {
        return __("Worried about SEO, performance or copyright...", FIFU_SLUG);
    };
    $fifu['text']['reason']['local'] = function () {
        return __("I wished it worked with my local images...", FIFU_SLUG);
    };
    $fifu['text']['reason']['undestand'] = function () {
        return __("I didn't understand how it works...", FIFU_SLUG);
    };
    $fifu['text']['reason']['others'] = function () {
        return __("Others...", FIFU_SLUG);
    };

    return $fifu;
}

function fifu_get_strings_dokan() {
    $fifu = array();

    $fifu['title']['product']['image'] = function () {
        _e("Product image", FIFU_SLUG);
    };
    $fifu['title']['product']['gallery'] = function () {
        _e("Image gallery", FIFU_SLUG);
    };

    $fifu['placeholder']['product']['image'] = function () {
        _e("Image URL", FIFU_SLUG);
    };

    return $fifu;
}
