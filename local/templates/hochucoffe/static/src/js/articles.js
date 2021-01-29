$(function (){
    if($('.articles-detail__sw-cont').length>0){
        var swiper_1 = new Swiper('.articles-detail__sw-cont.swiper-container', {
            speed: 400,
            slidesPerView: 1,
            spaceBetween: 0,
            slidesPerGroup: 1,
            loop: true,
            pagination: {
                el: '.articles-detail__sw-cont .swiper-pagination',
                type: 'bullets',
                dynamicBullets:true
            },
            navigation: {
                nextEl: '.articles-detail__prods .swiper-button-next',
                prevEl: '.articles-detail__prods .swiper-button-prev',
            },
            breakpoints: {
                641: {
                    slidesPerView: 4,
                    spaceBetween: 20,
                    slidesPerGroup: 4,
                }
            }
        });
    }
})
