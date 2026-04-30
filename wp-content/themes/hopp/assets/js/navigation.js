(function () {
	const button = document.querySelector(".site-nav-toggle");
	const navigation = document.querySelector("#site-navigation");

	if (!button || !navigation) {
		return;
	}

	button.addEventListener("click", function () {
		const expanded = button.getAttribute("aria-expanded") === "true";
		button.setAttribute("aria-expanded", String(!expanded));
		document.body.classList.toggle("nav-open", !expanded);
	});

	navigation.addEventListener("click", function (event) {
		if (event.target instanceof HTMLAnchorElement) {
			button.setAttribute("aria-expanded", "false");
			document.body.classList.remove("nav-open");
		}
	});
})();
