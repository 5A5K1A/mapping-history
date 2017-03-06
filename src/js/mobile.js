(function($) {
    var $window = $(window),
        $mobile = $('.year');

    // called on window resize
    function Resize() {
        if ($window.width() < 480) {
            return $mobile.addClass('dropup');
            $(".map").css("display", "none");
            $(".mobile-back").css("display", "none");
            $(".search-mobile").css("display", "none");
        }

        $mobile.removeClass('dropup');
    }

    // Trigger resize
    $window.resize(Resize);
    Resize();

    // click on the search mobile button enables the search field
    $('.search-mobile').click(function() {
        $('.search-address').fadeToggle('slow');
    });

    // click on the 'arrow back' button
    $('.mobile-back').click(Mobile_HideMap);

    // click on the 'show map' button
    $('.map').click(Mobile_ShowMap);

    // click on the year menu
    $('.year-menu-mobile').click(function() {
        $("main").css("display", "none");
        $(".icon-map-active").css("display", "block");
        $(".icon-map-inactive").css("display", "none");
    });

})(jQuery);

 /**
 * Shows the map for mobile users
 */
function Mobile_ShowMap() {
    var $ = jQuery;
    if($(window).width() > 480) return;

    // show
    $(".icon-map-active").css("display", "block");
    // hide
    $("main").css("display", "none");
    $(".icon-map-inactive").css("display", "none");
    $(".search-address").css("display", "none");
    $(".opacity-active").css("display", "none");
}

/**
 * Hides the map for mobile users
 */
function Mobile_HideMap() {
    var $ = jQuery;
    if($(window).width() > 480) return;

    // show
    $(".icon-map-inactive").css("display", "block");
    $("main").css("display", "block");
    //hide
    $(".icon-map-active").css("display", "none");
    $(".search-address").css("display", "none");
    $(".opacity-active").css("display", "none");
}
