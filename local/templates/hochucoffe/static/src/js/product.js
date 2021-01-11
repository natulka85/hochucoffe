function voteStars(){
    var voteSet =  $('.prod__review-form .catg__star');
    var stars = '.prod__review-form .catg__star';

    $(document).on('click', stars, function () {

        var index = voteSet.index(this) + 1;

        $(this).prevAll().addClass('on').removeClass('off');
        $(this).nextAll().addClass('off').removeClass('on');
        $(this).addClass('on').removeClass('off');
        $('input[name="grade"]').val(index);
    });

    $(document).on('mouseover', stars, function(e){
        var index = voteSet.index(this) + 1;
        $(stars).removeClass('is-on');
        $(stars).slice(0,index).addClass('is-on');

    });
    $(document).on('mouseout', stars, function(){
        var grade = $(this).parents('.review-form').find('input[name="grade"]').val();
        $(stars).removeClass('is-on');
        $(stars).slice(0,grade).addClass('is-on');
    });

}

$(function(){
    //Sticky($('.prod__reviews'), $('.prod__reviews-res'),10);
    voteStars();
    $(document).on('click','.js-toggle-rf',function(){
        $('.prod__review-form').slideToggle('slow');
    })
    if($('.your-viewed__sw-cont.swiper-container').length>0){
        var swiper_1 = new Swiper('.your-viewed__sw-cont.swiper-container', {
            speed: 400,
            slidesPerView: 1,
            spaceBetween: 0,
            slidesPerGroup: 1,
            loop: true,
            pagination: {
                el: '.your-viewed .swiper-pagination',
                type: 'bullets',
                dynamicBullets:true
            },
            navigation: {
                nextEl: '.your-viewed .swiper-button-next',
                prevEl: '.your-viewed .swiper-button-prev',
            },
            breakpoints: {
                640: {
                    slidesPerView: 4,
                    spaceBetween: 20,
                    slidesPerGroup: 4,
                }
            }
        });
    }
})

