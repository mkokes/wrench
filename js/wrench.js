jQuery(document).ready(function($) {
	//Smooth Scroll to Top
	$(window).scroll(function(event) {
		var y = $(this).scrollTop();
		var viewport = $(window).height();
		if (y >= viewport) {
			$("#scrolltop").removeClass("hide");
			$("#scrolltop").addClass("show");
		} else {
			$("#scrolltop").removeClass("show");
			$("#scrolltop").addClass("hide");
		}
	});
	$('#scrolltop').click(function() {
		$("html, body").animate({
			scrollTop: 0
		}, "slow");
		return false;
	});
	//Tabs
	$("#tabs").tabs( { fx: { height: 'toggle', opacity: 'toggle' } } );
	//Search
	$("#site-search-btn").click(function(eventObject) {
	        if ($("#site-search-form").hasClass('visible')) {
			
            $("#site-search-form").toggleClass('visible');
        } else {
           
            $("#site-search-form").toggleClass('visible');
        }
	});
});