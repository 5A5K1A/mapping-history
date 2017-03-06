jQuery(document).ready(function($) {

    // clicking on an image
    $(document).on('click', "a[href$='jpg'], a[href$='png'], a[href$='gif']", function(e){
        e.preventDefault();
        e.stopPropagation();

        // hide old image-popupes
        $(".image-popup").fadeOut(500, function(){
            $(this).remove();
        });

        // create popup html
        var $popup = $(MapHist.Template('template-image-popup', {
            imgSrc: $(this).attr("href"),
            caption: $(this).siblings(".wp-caption-text").clone().appendTo("<div />").parent().html()
        }));

        // show popup
        $("body").append($popup);
        $popup.hide().fadeIn(500);

        // don't bubble
        $popup.click(function(e){
            e.stopPropagation();
        });

        $(".panzoom img").load(function(){
            // get image width
            imageWidth = $(".panzoom img").css("width");
            $(".box").css("width", imageWidth);
            imageHeight = $(".panzoom img").css("height");
            $(".box").css("height", imageHeight);
        });

        if ($('.wp-caption-text').length){
            $(".zoom-out").css("bottom", '4.8em');
            $(".zoom-in").css("bottom", '4.8em');
        }

        // apply pan zoom script=
        $popup.find(".panzoom").panzoom({
            $zoomIn: $(".zoom-in"),
            $zoomOut: $(".zoom-out"),
            contain: "invert",
            minScale: 1,
            maxScale: 3,
            duration: 500,
            cursor: "move"
        }).panzoom("zoom");
        
        $("body, .image-popup-close").click(function(){
            $(".image-popup").fadeOut(500, function(){
                $(this).remove();
            });
        });
    });

}(jQuery));
