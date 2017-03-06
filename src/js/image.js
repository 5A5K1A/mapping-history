jQuery(function($){
    $('.entry p > img').each(function(){
        $(this).unwrap();
    });
});
