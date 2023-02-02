function miga_custom_posts_addElement() {
	const {
		__,
		_x,
		_n,
		_nx
	} = wp.i18n;

	var count = parseInt(document.querySelectorAll(".box").length);

	clone = document.createElement("div");
	p1 = document.createElement("p");
	p1.textContent = objectL10n.postText;
	p2 = document.createElement("p");
	p2.textContent = objectL10n.pageText;
	clone.classList.add("box");


	sel_pages = document.getElementById("selectPages");
	sel_posts = document.getElementById("selectPosts");

	const pageClone = sel_pages.cloneNode(true);
	const postClone = sel_posts.cloneNode(true);

	pageClone.setAttribute("id", "miga_custom_post_type_" + count);
	pageClone.setAttribute("name", "miga_custom_posts[" + count + "][0]");
	pageClone.classList.remove("hidden");

	postClone.setAttribute("id", "miga_custom_post_id_" + count);
	postClone.setAttribute("name", "miga_custom_posts[" + count + "][1]");
	postClone.classList.remove("hidden");

	clone.append(p1);
	clone.append(postClone);
	clone.append(p2);
	clone.append(pageClone);

	document.querySelector(".boxes").append(clone);

}


function miga_custom_posts_remove(id) {
	document.querySelector("#box_" + id).remove();
}
