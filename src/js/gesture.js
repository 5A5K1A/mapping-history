jQuery(function($) {

	// open sidebar & mobile no animation

	if ($(window).width() > 380) {
		$(".swipe").css({'transition' : 'margin 0.6s ease-in-out'})
	}

	$(".swipe").addClass("open-sidebar");

	$(".open-map").click(function(){
		$(".swipe").removeClass("open-sidebar");
		$("#maphist_app").toggleClass("wide-map");
		google.maps.event.trigger(oApp.oMap.oGMap, "resize");
	});

	// on click
	$("#sidebar-toggle").click(function() {
		var toggle_el = $(this).data("toggle");
		$(toggle_el).toggleClass("open-sidebar");


		// update google map
		var timeout = 0;
		if($(toggle_el).hasClass("open-sidebar")) {
			timeout = 1000;
		}
		setTimeout(function(){
			$("#maphist_app").toggleClass("wide-map");
			google.maps.event.trigger(oApp.oMap.oGMap, "resize");
		}, timeout);
	});

	// on swipe
	$(".swipe-area").swipe({
		swipeStatus:function(event, phase, direction, distance, duration, fingers)
		{
			if (phase=="move" && direction =="left") {
				$(".swipe").addClass("open-sidebar");
				setTimeout( function(){
					$("#maphist_app").removeClass("wide-map");
					google.maps.event.trigger(oApp.oMap.oGMap, "resize");
				}, 1000 );
				return false;
			}
			if (phase=="move" && direction =="right") {
				$(".swipe").removeClass("open-sidebar");
				$("#maphist_app").addClass("wide-map");
				google.maps.event.trigger(oApp.oMap.oGMap, "resize");
				return false;
			}
		}
	});

}(jQuery));
