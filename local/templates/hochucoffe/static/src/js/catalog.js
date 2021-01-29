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
   /* $(document).on('mouseover', '.js-tip',function(){
       $(this).addClass('is-show');
    })
    $(document).on('mouseleave', '.js-tip',function(){
        $(this).removeClass('is-show');
    })*/

    if(window.outerWidth > 640){
        StickyMy($('.page'), $('.menu-catalog'),0);
        $(document).on('mouseenter','.breadcrumbs__item',function(){
            $(this).addClass('is-hover');
        })
        $(document).on('mouseleave','.breadcrumbs__item',function(){
            $(this).removeClass('is-hover');
        })
    }
    if(window.outerWidth <= 640){
        StickyMy($('.page'), $('.menu-catalog'),0);
        $(document).on('click','.catg__list-control-value', function(){
            $('.catg__list-control').toggle(300);
        })
        $(document).on('click','.filter__choose-block',function(){
            $(this).toggleClass('is-closed');
        })
        $('.filter__choose-block').addClass('is-closed');

        $(document).on('click','.breadcrumbs__plus',function(e){
            e.preventDefault();
            $(this).parents('.breadcrumbs__item').toggleClass('is-hover');
        })
    }

    $(document).mouseup(function (e){ // событие клика по веб-документу
        var domElem = $('.breadcrumbs__item');
        if (!domElem.is(e.target) // если клик был не по нашему блоку
            && domElem.has(e.target).length === 0) { // и не по его дочерним элементам
            //target.click();
            $('.breadcrumbs__item').removeClass('is-hover');
        }
    });

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
    if($('.pomol__sw-cont.swiper-container').length>0){
        var active = $('.pomol__item.is-active').parents('.pomol__item-wrap');
        var activeIndex = $('.pomol__item-wrap').index(active);
        var swiper_2 = new Swiper('.pomol__sw-cont.swiper-container', {
            speed: 400,
            slidesPerView: 2.32,
            spaceBetween: 0,
            slidesPerGroup: 1,
            initialSlide:activeIndex,
            loop: true,
            breakpoints: {
                641: {
                    slidesPerView: 8,
                    loop: false,
                }
            }
        });
    }

})
