$(document).ready(function () {
	/* Search icon displays input if closed */
	var main_navbar = $("#navbar__main");
	$('#search-button').on('click', function(e) {
		if(!main_navbar.hasClass('navbar-search-open')) {
			e.preventDefault();
			main_navbar.addClass("navbar-search-open");
			return false;
		}
	});
	
	/* Hide input if clicked outside search area */
	$("#hide-search-input-container, body").click(function() {
		main_navbar.removeClass("navbar-search-open");
	});
	$('#search-input-container .form-group, #search-button').click(function(e){
		e.stopPropagation();
	});
	
	/* Sub-site Navigation */
	var NavTimeout;
	var hideSubDropdown = function () {
		$(".nasa__sub-navigation-dropdown").css({ opacity: 0, transition: 'opacity 0.5s' }).slideUp();
	};
	var cancelHideSubDropdown = function () {
		clearTimeout(NavTimeout);
	};
	var showSubDropdown = function (nav_class, isScroll) {
		var nav_not_element = $(".nasa__sub-navigation-dropdown:not(."+nav_class+")");
		var nav_element = $(".nasa__sub-navigation-dropdown."+nav_class+"");
		
		// Cancel any current animations and begin a new animation
		cancelHideSubDropdown();
		nav_not_element.stop(true, true).css({"display": "none", "opacity": 0});
		nav_element.slideDown().css({ opacity: 1, transition: 'opacity 0.5s' });
		if (isScroll){ $('html, body').animate({ scrollTop: nav_element.offset().top }, 500); }
	};
	
	/* Sub-site Navigation Handlers */
	$("#nasa__sub-navigation").find(".nav li a").on("mouseenter touchstart", function (e) {
		// Display dropdown on hover or touch
		var nav_class = $(this).prop("class");
		if (nav_class) {
			// Show dropdown. Scroll to the dropdown on touchscreens.
			showSubDropdown(nav_class, e.type === "touchstart");
		}
	}).on("touchend", function (e) {
		// Treat as dropdown on touch devices, not as a link
		e.preventDefault();
		return false;
	});
	$(".nasa__sub-navigation-dropdown, #nasa__sub-navigation .nav li a").mouseleave(function () {
		NavTimeout = setTimeout(function () { hideSubDropdown(); }, 300);
	}).on("touchstart", function (e) {
		e.stopPropagation();
	});
	$(".nasa__sub-navigation-dropdown").mouseover(function () {
		cancelHideSubDropdown();
	});
	$("body").on("touchstart", function () {
		hideSubDropdown();
	});
	
	/* Sidebar Navigation */
	$("#nasa__main-sidebar").find("li a").click(function (e) {
		if ( !$(e.target).hasClass("pull-right") && $(this).attr("href") !== "#" ) {
			e.stopPropagation();
			return true;
		} else {
			e.preventDefault();
		}
	});
	
	/* Select2 Dropdown */
	if ( $.isFunction($.fn.select2) ){
		$("select").select2({
			theme: "bootstrap",
			width: "100%",
			minimumResultsForSearch: 7
		});
	}
});