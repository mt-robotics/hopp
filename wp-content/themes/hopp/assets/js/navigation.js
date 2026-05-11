(function () {
	const button = document.querySelector(".site-nav-toggle");
	const navigation = document.querySelector("#site-navigation");

	if (!button || !navigation) {
		return;
	}

	navigation.querySelectorAll(".menu-item-has-children > a").forEach(function (link) {
		link.setAttribute("aria-haspopup", "true");
		link.setAttribute("aria-expanded", "false");
	});

	button.addEventListener("click", function () {
		const expanded = button.getAttribute("aria-expanded") === "true";
		button.setAttribute("aria-expanded", String(!expanded));
		document.body.classList.toggle("nav-open", !expanded);
	});

	navigation.addEventListener("click", function (event) {
		if (!(event.target instanceof HTMLAnchorElement)) {
			return;
		}

		const parentItem = event.target.closest(".menu-item-has-children");
		if (
			parentItem &&
			event.target.parentElement === parentItem &&
			window.matchMedia("(max-width: 960px)").matches
		) {
			const expanded = parentItem.classList.toggle("menu-item-submenu-open");
			event.target.setAttribute("aria-expanded", String(expanded));
			if (expanded) {
				event.preventDefault();
				return;
			}
		}

		button.setAttribute("aria-expanded", "false");
		document.body.classList.remove("nav-open");
	});
})();
