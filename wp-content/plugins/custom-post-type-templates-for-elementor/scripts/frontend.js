(function() {
	'use strict';

	if (document.readyState !== 'loading') {
     migaCustomPostReady();
   } else {
     document.addEventListener('DOMContentLoaded', migaCustomPostReady);
   }

	function migaCustomPostReady() {

		var filterData = [];
		var paged;
		var isFilter = false;
		var replaceContent = false;
		var noItems = jQuery(".miga_custom_posts_row").attr("data-noitems");
		jQuery(".miga_custom_post_button_more").click(function() {
			paged = parseInt(jQuery(".miga_custom_post_button_more").attr("data-paged"));
			paged++;
			isFilter = false;
			replaceContent = false;

			filterData = [];
			var filters = document.querySelectorAll(".miga_custom_posts_filter");
			for (var i = 0; i < filters.length; ++i) {
				if (filters[i].value != "") {
					filterData.push([jQuery(filters[i]).attr("data-name"), filters[i].value]);
				}
			}
			if (filterData.length > 0) {
				isFilter = true;
			}

			miga_custom_posts_load_data();
		});

		jQuery(".miga_custom_posts_filter").change(function() {
			paged = 1;
			filterData = [];
			var filters = document.querySelectorAll(".miga_custom_posts_filter");
			for (var i = 0; i < filters.length; ++i) {
				filterData.push([jQuery(filters[i]).attr("data-name"), filters[i].value]);
			}
			isFilter = true;
			replaceContent = true;
			miga_custom_posts_load_data();
		});

		function miga_custom_posts_load_data() {

			var data = {
				action: 'miga_custom_post_filter',
				miga_custom_posts_nonce: miga_custom_posts_params.miga_custom_posts_nonce,
				paged: paged,
				postType: jQuery(".miga_custom_posts_row").attr("data-posttype"),
				numposts: jQuery(".miga_custom_posts_row").attr("data-numposts"),
				filterData: filterData,
				isFilter: isFilter
			}

			jQuery.ajax({
				type: 'post',
				url: miga_custom_posts_params.miga_custom_posts_url,
				data: data,
				beforeSend: function(data) {
					// if (loadMore) {
					// 	// Loading Animation Start
					// }
				},
				complete: function(data) {},
				success: function(data) {

					data = JSON.parse(data);

					if (data.error && data.error == "no_more") {
						jQuery(".miga_custom_post_button_more").hide();
						var errorAlignment = jQuery(".miga_custom_posts_row").attr("data-erroralignment");
						if (replaceContent) {
							jQuery(".miga_custom_posts_row").html('<div class="miga_custom_posts_error ' + errorAlignment + '">' + noItems + '</div>');
						} else {
							// during load more
						}
					} else {

						jQuery(".miga_custom_post_button_more").attr("data-paged", paged);

						var content = [];
						if (replaceContent) {
							for (var i = 0; i < data.items.length; ++i) {
								content.push(createItem(data.items[i]));
							}
							jQuery(".miga_custom_posts_row").html(content)
						} else {
							for (var i = 0; i < data.items.length; ++i) {
								content.push(createItem(data.items[i]));
							}
							jQuery(".miga_custom_posts_row").append(content)
						}

						if (data.maxPage == paged) {
							jQuery(".miga_custom_post_button_more").hide();
						} else {
							jQuery(".miga_custom_post_button_more").show();
						}
					}
					filterData = [];
				},
				error: function(data) {
					console.log("Error", data);
				},

			});
		}
	}

	function createItem(item) {
		var txt = document.querySelector(".miga_custom_post_template").innerHTML
		var obj = document.createElement("a");
		obj.href = item.link;
		obj.innerHTML = txt;
		var tags = "";
		for (var i = 0; i < item.tags.length; ++i) {
			var tag = document.createElement("div");
			tag.classList.add("miga_custom_posts_post_tag");
			tag.textContent = item.tags[i];
			tags += tag.outerHTML;
		}
		obj.querySelector(".miga_custom_posts_post_title").innerHTML = item.title;
		obj.querySelector(".miga_custom_posts_post_date").innerHTML = item.date;
		obj.querySelector(".miga_custom_posts_post_thumb").innerHTML = item.thumbnail;
		obj.querySelector(".miga_custom_posts_post_tags").innerHTML = tags;
		obj.querySelector(".miga_custom_posts_post_excerpt").innerHTML = item.excerpt;
		return obj;
	}
})();
