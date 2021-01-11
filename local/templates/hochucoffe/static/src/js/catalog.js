$(function(){
    $(document).on('click','.js-link.is-filter', function(e){
        e.preventDefault();
        $('.filter').toggleClass('is-opened');
    })
    $(document).on('click','.js-link.is-sort-link', function(e){
        e.preventDefault();
        $('.catg__list-control').toggleClass('is-opened');
    })
    // additems
    $(document).on('click', '.js-more-el', function (e) {
        e.preventDefault();
        $(this).addClass('is-active');
        var url = window.location.href.split('#')[0];
        var data = parceAjaxUrl(url+$(this).attr('href'));
        reloadPage(url+$(this).attr('href'), data, 'add');
    });

    if(window.outerWidth <= 640){
        $(document).on('click','.catg__list-control-value', function(){
            $('.catg__list-control').toggle(300);
        })
        $(document).on('click','.filter__choose-block',function(){
            $(this).toggleClass('is-closed');
        })
        $('.filter__choose-block').addClass('is-closed');
    }
    if(window.outerWidth > 640){
        StickyMy($('.page'), $('.menu-catalog'),0);
    }
    if(window.outerWidth <= 640){
        StickyMy($('.page'), $('.menu-catalog'),0);
    }

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
