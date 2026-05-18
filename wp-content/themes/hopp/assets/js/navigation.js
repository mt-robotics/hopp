(function () {
	const button = document.querySelector(".site-nav-toggle");
	const navigation = document.querySelector("#site-navigation");
	const returnToTop = document.querySelector(".return-to-top");

	if (!button || !navigation) {
		return;
	}

	const submenuParents = navigation.querySelectorAll(".menu-item-has-children");

	const positionSubmenu = function (item) {
		const submenu = item.querySelector(":scope > .sub-menu");
		const link = item.querySelector(":scope > a");

		if (!submenu || !link || !window.matchMedia("(min-width: 961px)").matches) {
			return;
		}

		const linkRect = link.getBoundingClientRect();
		const headerRect = document.querySelector(".site-header").getBoundingClientRect();
		submenu.style.setProperty("--hopp-submenu-left", `${linkRect.left + linkRect.width / 2}px`);
		submenu.style.setProperty("--hopp-submenu-top", `${headerRect.bottom + 8}px`);
	};

	submenuParents.forEach(function (item) {
		const link = item.querySelector(":scope > a");
		const submenu = item.querySelector(":scope > .sub-menu");
		let closeTimer;

		if (!link) {
			return;
		}

		const openSubmenu = function () {
			window.clearTimeout(closeTimer);
			positionSubmenu(item);
			item.classList.add("menu-item-submenu-open");
			link.setAttribute("aria-expanded", "true");
		};

		const closeSubmenu = function () {
			closeTimer = window.setTimeout(function () {
				item.classList.remove("menu-item-submenu-open");
				link.setAttribute("aria-expanded", "false");
			}, 280);
		};

		link.setAttribute("aria-haspopup", "true");
		link.setAttribute("aria-expanded", "false");
		item.addEventListener("mouseenter", openSubmenu);
		item.addEventListener("mouseleave", closeSubmenu);
		item.addEventListener("focusin", openSubmenu);
		item.addEventListener("focusout", closeSubmenu);
		if (submenu) {
			submenu.addEventListener("mouseenter", openSubmenu);
			submenu.addEventListener("mouseleave", closeSubmenu);
		}
	});

	window.addEventListener("resize", function () {
		submenuParents.forEach(positionSubmenu);
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

	if (returnToTop) {
		const syncReturnToTop = function () {
			returnToTop.classList.toggle("return-to-top--visible", window.scrollY > 520);
		};

		syncReturnToTop();
		window.addEventListener("scroll", syncReturnToTop, { passive: true });
	}
})();
