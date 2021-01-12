$(function(){
    animeUtp();
    var swiper_1 = new Swiper('.banner__cont', {
        slidesPerView:1,
        speed: 400,
        centeredSlides: true,
        watchSlidesProgress:true,
        loop: true,
        effect: 'fade',
        fadeEffect: {
            crossFade: true
        },
        pagination: {
            el: '.banner .swiper__bullet',
            type: 'bullets',
            clickable: true,
        },
        navigation: {
            nextEl: '.banner .swiper-button-next',
            prevEl: '.banner .swiper-button-prev',
        },
    });
    var swiper_2 = new Swiper('.sections__cont', {
        speed: 400,
        slidesPerView: 2.33,
        spaceBetween: 0,
        loop: true,
        autoplay: {
            delay: 4000,
        },
        cubeEffect: {
            slideShadows: false,
        },
        pagination: {
            el: '.sections .swiper-pagination',
            type: 'bullets',
        },
        navigation: {
            nextEl: '.sections .swiper-button-next',
            prevEl: '.sections .swiper-button-prev',
        },
        breakpoints: {
            641: {
                slidesPerView: 4.33,
                spaceBetween: 0,
            }
        }
    });
    var swiper_3 = new Swiper('.sale__sw-cont.swiper-container', {
        speed: 400,
        slidesPerView: 1,
        spaceBetween: 0,
        slidesPerGroup: 1,
        loop: true,
        pagination: {
            el: '.sale .swiper-pagination',
            type: 'bullets',
            dynamicBullets:true
        },
        navigation: {
            nextEl: '.sale .swiper-button-next',
            prevEl: '.sale .swiper-button-prev',
        },
        breakpoints: {
            641: {
                slidesPerView: 4,
                spaceBetween: 20,
                slidesPerGroup: 4,
            }
        }
    });
    var swiper_4 = new Swiper('.hits__sw-cont.swiper-container', {
        speed: 400,
        slidesPerView: 1,
        spaceBetween: 0,
        slidesPerGroup: 1,
        loop: true,
        pagination: {
            el: '.hits .swiper-pagination',
            type: 'bullets',
            dynamicBullets:true
        },
        navigation: {
            nextEl: '.hits .swiper-button-next',
            prevEl: '.hits .swiper-button-prev',
        },
        breakpoints: {
            641: {
                slidesPerView: 4,
                spaceBetween: 20,
                slidesPerGroup: 4,
            }
        }
    });

    if(window.outerWidth<=640){
        var swiper_5 = new Swiper('.utp .swiper-container', {
            speed: 400,
            slidesPerView: 2.32,
            spaceBetween: 10,
            slidesPerGroup: 2,
            loop: true,
            //setInitialSlide: 0,
            //loop: true,
        });
        //swiper_5.slideTo(3, false,false);
        $('.utp__item-wrap:not(.is-rotated)').addClass('is-rotated')
    }
    else{
        if(swiper_5){
            swiper_5.destroy();
        }
    }
    var swiper_6 = new Swiper('.pomol__sw-cont.swiper-container', {
        speed: 400,
        slidesPerView: 2.32,
        spaceBetween: 0,
        slidesPerGroup: 2,
        loop: true,
        breakpoints: {
            641: {
                slidesPerView: 8,
                loop: false,
            }
        }
    });
    var swiper_7 = new Swiper('.reviews.swiper-container', {
        speed: 400,
        slidesPerView: 2.32,
        spaceBetween: 10,
        slidesPerGroup: 2,
        loop: true,
        navigation: {
            nextEl: '.reviews .swiper-button-next',
            prevEl: '.reviews .swiper-button-prev',
        },
        breakpoints: {
            641: {
                slidesPerView: 5,
                slidesPerGroup: 1,
                spaceBetween: 30,
            }
        }
    });

    var swiper_5 = new Swiper('.view-sw.swiper-container', {
        speed: 400,
        slidesPerView: 1,
        effect: 'flip',
        loop: true,
        autoplay: {
            delay: 8000,
        },
        navigation: {
            nextEl: '.view .swiper-button-next',
            prevEl: '.view .swiper-button-prev',
        },
    });

    function makeWindow($html,coord){
        var element = document.createElement('div');
        element.setAttribute('class', 'map__text');
        element.innerHTML = $html;
        document.body.append(element);
        var el_height = $('.map__text').outerHeight();
        var el_width = $('.map__text').outerWidth();
        var mapRW = $('.map__right').offset().left;
        if(coord.left + el_width >  mapRW){
            coord.left = mapRW-el_width;
        }
        $('.map__text').css({
            "top":coord.top - el_height,
            'left':coord.left,
            'height': 'auto',
            'opacity':'1',
        });
    }
    if($('.map__left').length > 0){
        var req = new XMLHttpRequest();
        req.open('GET', '/local/templates/hochucoffe/static/dist/images/svg/world_map.svg', true);
        req.onreadystatechange = function() {
            if (req.readyState == 4 && req.status == 200) {
                var container = document.createElement('div');
                //container.style.display='none';
                container.innerHTML = req.responseText;
                var place =  document.getElementsByClassName('map__left');
                place[0].appendChild(container);
            }
        };
        req.send(null);
        $(document).on('mouseenter', '.map__country-link', function(){
            var country = $(this).data('country_id');
            var mapPoint = $(".map__left path[id="+country+"]");
            mapPoint.attr("class",'is-hover');
            var coord = mapPoint.offset();

            makeWindow($(this).html(),coord)
        })
        $(document).on('mouseleave', '.map__country-link, .map__left path', function(){
            $('.map__left path').attr('class','');
            $('.map__text').remove();
        })

        $(document).on('mouseenter','.map__left path', function(){
            var mapPoint = $(this);
            var coord = mapPoint.offset();
            var html = $('.map__country-link[data-country_id='+mapPoint.attr('id')+']').find('span').html();
            if(html!=undefined && html !=='undefined'){
                makeWindow('<span>'+html+'</span>',coord)
            }

        })

        $(document).on('click','.form__head',function () {
            $(this).parents('.form').toggleClass('is-opened');
        })
    }

    if($('.view__list.is-slider').length > 0){
        $(document).on('click','.view__bottom-arrows span.slick-prev',function(){
          $(this).parents('.view').find('button.slick-prev').trigger('click');
          console.log('test');
        })
        $(document).on('click','.view__bottom-arrows span.slick-next',function(){
            $(this).parents('.view').find('button.slick-next').trigger('click');
        })
    }

})

function animeUtp(){
    var obj = $('.utp__item-wrap:not(.is-rotated)');
    obj.css({'width':'auto'});
    /*setTimeout(function(){
        obj.each(function(index){
            $(this).css({'width':'auto'});
            let left = $(this).offset().left + $(this).outerWidth();
            //$(this).css({'transform':'translateX(-'+left+'px)'});
        })
    },1000)*/

    $(window).scroll(function () {
        if($('.utp__item-wrap:not(.is-rotated)').length>0){
            var scroll = $(window).scrollTop();
            var parent = $('.utp__list');
            var parentPos = parent.offset().top;

            if(scroll>parentPos - window.outerHeight + parent.outerHeight()){
                obj.each(function(index){
                    var $this = $(this);
                    setTimeout(function(){
                        $this.addClass('is-rotated')
                    },(index+1)*600);
                })
            }
        }
    });
}

