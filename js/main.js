$(document).ready(function () {
	var main_navbar = $("#navbar__main");
	
	/* Search icon displays input if closed */
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
	$("#nasa__sub-navigation").find(".nav li a").hover(function () {
		var nav_class = $(this).prop("class");
		if (nav_class) {
			$(".nasa__sub-navigation-dropdown:not(."+nav_class+")").stop(true, true).css({"display": "none", "opacity": 0}); //removeClass("active");
			$(".nasa__sub-navigation-dropdown."+nav_class+"").slideDown().css({ opacity: 1, transition: 'opacity 0.5s' }); //.addClass("active");
			clearTimeout(NavTimeout);
		}
	});
	$(".nasa__sub-navigation-dropdown, #nasa__sub-navigation .nav li a").mouseleave(function () {
		NavTimeout = setTimeout(function () { $(".nasa__sub-navigation-dropdown").css({ opacity: 0, transition: 'opacity 0.5s' }).slideUp(); /*removeClass("active");*/ }, 100);
	});
	$(".nasa__sub-navigation-dropdown").mouseover(function () {
		clearTimeout(NavTimeout);
	});
});