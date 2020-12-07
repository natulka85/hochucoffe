$(function(){
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
        req.open('GET', '/local/templates/hochucoffe/static/images/svg/world_map.svg', true);
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

