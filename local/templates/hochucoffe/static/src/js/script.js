var objSetupScroll ={
    cursorcolor:"#eb6a57",
    cursorborderradius: "5px",
    //cursorfixedheight: 'true',
    hidecursordelay: 100,
    cursorminheight: 46
};


$(function () {
    console.log('test');
    //Barba.Pjax.start();


    var curUrl = window.location.href;
    if (curUrl.indexOf('#') > 0) {
        var data = parceAjaxUrl(curUrl);
        reloadPage(curUrl, data);
    }

    //хэштег добавился
    window.onhashchange = function () {
        var curUrl = window.location.href;
        if (curUrl.indexOf('#') > 0) {
            var data = parceAjaxUrl(curUrl);
            //console.log(Object.keys(data).length,'Object.keys(data).length',data);
            if(Object.keys(data).length !== 0){
                reloadPage(curUrl, data);
            }
        }
    }

    $('.catalog__text-box').replaceWith($('.catalog__text'));


    //SlickSliders();
    moreContent();
    moreContentChange();

    mobileMenu();
    adaptiveContent();
    searchBtn();
    close();
    formAjax();
    //select();
    initTabs();
    contentOpen();
    NoTarget();
    //initProductDetailPhotoGallery();
    ajaxDo();
    reinitfuncs();
    HeaderSticky();
    initScrollGoal();
    EventsGa();
    SortList();
    counter();
    slideDown();
    scaleHTML();
    elementShowMore();
    Anchor();
    menuBurger();
    initSliderImageMove();
    catgSort();
    searchHint();
    changeCardList();
    inputValid();

    $("input[name='phone']").mask("+7(999)999-99-99");
})

var window_width = window.innerWidth;
$(window).on('resize', function () {
    if(window.innerWidth != window_width){
        scaleHTML();
    }
})
function inputValid(){
    $('input,textarea').each(function(){
        if($(this).val()!=='' && $(this).val() !== '+7(___)___-__-__'){
            $(this).addClass('is-valid');
        }
    })
    $(document).on('blur','input,textarea',function(){
        if($(this).val()!=='' && $(this).val() !== '+7(___)___-__-__'){
            $(this).addClass('is-valid');
        }
        else{
            $(this).removeClass('is-valid');
        }
    })
}
function scaleHTML(){
    if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
        // код для мобильных устройств
    } else {
        // код для обычных устройств
        if(window.innerWidth > 640 && window.innerWidth < 1480){
            console.log(window.innerWidth / 1650 * 100 +'%"');
            var zoom = Math.round(window.innerWidth / 1650 * 100) +"%";
            $('html').css({
                'zoom': zoom,
                'transform-origin':'left top'
            })

            console.log( zoom);
        }
        else{
            $('html').css({
                'zoom': '100%'
            })
        }
    }
}

function slideDown(){
    $(document).on('click', '.js-slide-btn', function(){
       $(this).siblings('.js-slide-content').slideToggle(500);
       $(this).toggleClass('is-active');
    })
}
function catgSort(){
    $(document).on('click','.js-ctg-sort', function(e) {
        e.preventDefault();
        e.stopPropagation();
        var data = parceAjaxUrl(window.location.href);
        data['sort'] = $(this).attr('data-value');
        //$.cookie('tmpl_count', data['count'], {path: '/'});
        reloadPage(window.location.href, data);
    });
}
function StickyMy(parent,element,num=0){
    //var parent = parent;
    //var element = element;
    function addClasses(){
        var scroll = $(window).scrollTop();
        var parentPos = parent.offset().top;
        //console.log(parentPos,'parentPos',parent,element);
        if(!element.is('.noSticky')){
            //console.log((parentPos + parent.outerHeight() - element.outerHeight()-(num*2)), scroll,parentPos,parent.outerHeight(),element.outerHeight());
            if(scroll > (parentPos + parent.outerHeight() - element.outerHeight()-(num*2))){
                element.removeClass('doFixed').addClass('doFixedBottom');
            }
            else if(scroll > parentPos + num){
                element.removeClass('doFixedBottom').addClass('doFixed');
            }
            else if(scroll < parentPos - num){
                element.removeClass('doFixedBottom').removeClass('doFixed');
            }
            else{
                element.removeClass('isFixedBottom').removeClass('isFixed');
                if($flagLogoHeader){
                    logoInHeader(logo_elem);
                }
            }
        }
    }
    addClasses();
    //console.log(parent.length , element.length);
    if(parent.length > 0 && element.length>0){
        $(window).scroll(function () {
            //console.log('aa');
            addClasses();
        });
    }
}
function counter(){
    $(document).on('click', '.js-plus',function () {
        var input = $(this).parents('.js-counter').find('input');
        let num = +input.val();
        input.val(num+1);
        input.trigger('change');

    });
    $(document).on('click', '.js-minus',function () {
        var input = $(this).parents('.js-counter').find('input');
        let num = +input.val();
        if (num > 1 && num - 1 >= 0) {
            input.val(num - 1);
            input.trigger('change');
        }
    });
    $(document).on('focus','.count-block__value',function () {
        $(this).attr('current-value', $(this).val());
        $(this).val('');
    })

    $(document).on('blur','.count-block__value',function () {
        if (!$(this).val()) {
            $(this).val($(this).attr('current-value'));
        }
    })
}
function SortList(){
    $(document).on('click','.js-sort-list',function(){
        closeSelect();
        var $_this = $(this);
        var list = $_this.find('.cst-select');
        var position = $_this.offset();
        var id = Math.floor(Math.random() * (100 - 1 + 1) ) + 1;
        var container = $_this.parents('[data-select="block"]');
        list.attr('data-ident',id);
        $_this.attr('data-ident',id);
        list.clone()
            .addClass('is-clone')
            .css({top:position.top + $_this.outerHeight(),left:position.left,'min-width':$_this.outerWidth()})
            .prependTo('body')
            .show();

        $(this).toggleClass('is-opened');
    });
    $(document).on('click','.cst-select-option', function(){
        var text = $(this).text();
        var ident = $(this).parents('.cst-select').data('ident');
        $('.js-sort-list[data-ident='+ident+']').find('.cst-select-current').text(text);
        closeSelect();
    })

    $(document).on('mouseleave','.cst-select.is-clone',function(){
        closeSelect();
    });
    $(document).mouseup(function (e){ // событие клика по веб-документу
        var domElem = $('.cst-select');
        if (!domElem.is(e.target) // если клик был не по нашему блоку
            && domElem.has(e.target).length === 0) { // и не по его дочерним элементам
            //target.click();
            closeSelect();
        }
    });
}
function closeSelect(){
    $('.cst-select.is-clone').remove();
    //$('[data-ident]').attr('data-ident','');
}
function select(){

}
function HeaderSticky(){
    // When the user scrolls the page, execute myFunction
    window.onscroll = function() {HeaderStickyD()};

// Get the header
    var header = document.getElementById("header");
    if(header!=undefined){
        var sticky = header.offsetTop;
        var clone = $(header).clone(true);
// Add the sticky class to the header when you reach its scroll position. Remove "sticky" when you leave the scroll position
        function HeaderStickyD() {
            if (window.pageYOffset >= sticky + 10) {
                clone.addClass('is-sticky').prependTo( ".content" );
            } else {
                $("#header.is-sticky").remove();
            }
        }
    }

}
// AJAXRELOAD PARCE URL
function parceAjaxUrl(url) {
    var url_param = '';
    var data = {'catalog_ajax_call': 'Y'};
    var curUrl = url;

    //get global option
    //BpSortVariants set in /local/modules/bp.template/lib/catalog.php
    //and initiate /local/modules/bp.template/lib/main.php
    //var sort_vals = window.BpSortVariants;

    var cur_hash = curUrl.substring(curUrl.indexOf('#') + 1);
    var ar_hash = cur_hash.split('&');
    var ob_hash = {};

    for (i in ar_hash) {
        var hash_i = ar_hash[i];
        if(hash_i == parseInt(hash_i)+''){
            ob_hash['count'] = hash_i;
        } else if(hash_i.substring(0, 1) == 'p' && parseInt(hash_i.substring(1)) > 0){
            ob_hash['page'] = hash_i;
        } else if(hash_i.indexOf('/') == -1  && jQuery.inArray( hash_i, sort_vals )>=0 ){
            ob_hash['sort'] = hash_i;
        }
    }

    var c = 0;
    for (i in ob_hash) {
        if (i == 'page') {

           if ((curUrl.indexOf('search') !== -1 || window.location.href.indexOf('search') !== -1)  && ob_hash[i] != '') {
                //data['PAGEN_1'] = parseInt(ob_hash[i].substring(1));
                data['PAGEN_2'] = parseInt(ob_hash[i].substring(1));
            }
            else if (ob_hash[i] != '') {
                data['PAGEN_1'] = parseInt(ob_hash[i].substring(1));
            }
        }
        else if (i == 'count') {
            data['count'] = ob_hash[i];
        }
        else if(i == 'sort' && ob_hash[i] != undefined  && jQuery.inArray( ob_hash[i], sort_vals )>=0){
            data['sort'] = ob_hash[i];
        } else {
            return;
        }

        if (ob_hash[i] !== undefined) {
            ar_hash[c] = ob_hash[i];
            c++;
        }
    }
    return data;
}

// AJAXRELOAD PAGE
function reloadPage(url, data, mode, nothistory=false) {

    if (data !== undefined) {
        curState = {
            url: url,
        };

    if(!nothistory)
        history.pushState(curState, document.title, url);

        var star = false;
        if (
            url.indexOf('#')>0
        )
        {

            if(
                data['catalog_ajax_call'] == 'Y'
                && Object.keys(data).length > 1
            )
                star = true;
        } else
            star = true;


        if (star) {

            if(mode!=='filter_sec')
                $('.js-ajax-content').css('opacity', '0.5');

            //console.log(data,url,mode,'3');

            $.post(
                BX.util.add_url_param(url, data),
                data
            )
                .done(function (res) {
                    //try{ eval(res); } catch(e){ if(console && console.log) console.log(e); }
                    if (res && res.length) {
                        var json_res = JSON.parse(res.toString());
                        //console.log(res,'json_res');
                        var filter = $(json_res['filter']);
                        var content = $(json_res['items']);
                        var text = $(json_res['text']);
                        var breadcrumbs = json_res['breadcrumbs'];
                        var h1 = json_res['h1'];
                        var nav_cnt = json_res['nav_cnt'];
                        var cloud = json_res['cloud'];

                        if (mode === 'block') {
                            var block = $(".js-ajax-content[data-ajax="+data['ajax']+"]");

                            if(block.length>0){
                                block.replaceWith(content);
                            }
                        }
                        else if(mode === 'filter_sec'){
                            $('.js-ajax-filter').replaceWith(filter);
                            $('.js-ajax-content').replaceWith(content);
                            $('.breadcrumbs').replaceWith(breadcrumbs);
                            $('.page-title._type-1').text(h1);
                            $('.page-title-note').text(nav_cnt);
                            $('.catalog__text .page-text').html(text);
                            $('.cloud').replaceWith(cloud);
                            moreContent();
                        }
                        else if(mode === 'filter_opros'){
                            $('.js-ajax-filter').replaceWith(filter);
                            var res_url = json_res['res_filter_url'];
                            $('#set_filter').attr('href',res_url).removeAttr('disable');
                        }
                        else if(mode === 'add'){
                            var prod_list = $(content).find('[data-block="block-item"]');
                            $('[data-block="block-list"]').append(prod_list);
                            $('.catg__more').replaceWith($(content).find('.catg__more'));
                            $('.pagination').replaceWith($(content).find('.pagination'));
                        }
                        else {
                            //console.log($('.js-ajax-content:not([data-ajax])'),'$(\'.js-ajax-content:not([data-ajax])\')');
                            //console.log(catlist,'res');
                            $('.js-ajax-content:not(:first):not([data-ajax])').remove();
                            $('.js-ajax-content:not([data-ajax])').replaceWith(content);

                            console.log(data,'data');

                            if(data['PAGEN_1'] !== undefined)
                            {
                                $('html, body').animate({scrollTop: 200}, '250');
                            }
                        }

                        $('.js-ajax-content').css('opacity', '1');
                        //console.log(data);
                        reinitfuncs();
                    }
                })
                .fail(function () {
                    alert("Не удалось загрузить страницу.\nПопробуйте еще раз или обновите страницу вручную.");
                });
        }
    }
}

function reinitfuncs(){
    $( "body" ).on( "click", '.catalog-tip__show', function(e)
        //$('.catalog-tip__show').on("click", function(e)
    {
        e.preventDefault();
        url = $(this).attr('href');
        var data = parceAjaxUrl(url);
        reloadPage(url, data, 'filter_sec');
    });
}


function playPause(btn,vid){
    var vid = document.getElementById(vid);
    if(vid.paused){
        vid.play();
        btn.setAttribute("data-status", "pause");
    }
    else{
        console.log('not_paused');
        vid.pause();
        btn.setAttribute("data-status", "play");
    }
}

function VolumeOnOf(btn,vid){
    var vid = document.getElementById(vid);
    if(vid.muted){
        vid.muted = false;
        btn.setAttribute("data-status", "volume");
    } else{
        vid.muted = true;
        btn.setAttribute("data-status", "mute");
    }
}

var main_ajax= "/local/php_interface/ajax.php";
var window_width = window.innerWidth;

$(window).on('resize', function () {
    if(window.innerWidth != window_width){
        //SlickSliders();
        adaptiveContent();
    }
})

function moreContent() {
    $('.js-show-more-btn').each(function () {
        var block = $(this).parents('[data-show_more="block"]');
        var height = block.attr('data-show_more_height');

        if(height>0){
            //console.log(height);
            var content = block.find('[data-show_more="content"]');
            var contentHieght = content.outerHeight();

            if(contentHieght > height){
                content.attr('data-show_more_realheight',contentHieght).css({'height':height,'overflow-y':'hidden'});
                $(this).show();
            }
            else{
                block.find('.js-show-more-btn').hide();
                block.find('.js-show-more-btn').parents('[class*=__btn-more]').hide();
            }
        }
    })
}
function moreContentChange() {
    $(document).on('click','.js-show-more-btn',function () {
        var _this = $(this);
        var data = _this.data();
        var block = _this.parents('[data-show_more="block"]');
        var content =  block.find(('[data-show_more="content"]'));

        if(_this.text() === data.show_more_btnshow){
            content.animate({'height': content.attr('data-show_more_realheight')},500);
            _this.text(data.show_more_btnhide);
            block.addClass('is-opened');
        }
        else{
            content.animate({'height': block.attr('data-show_more_height')},500);
            _this.text(data.show_more_btnshow);
            block.removeClass('is-opened');
        }

    })
}
function mobileMenu() {
    $(document).on('click','.js-mob-menu-btn-open, .js-mob-menu-btn-close',function (e) {
        e.preventDefault();
        e.stopPropagation();
        $('.main-menu__burger').toggleClass('is-opened');
        $('.mob-menu').toggleClass('is-opened');
    })

   /* $(document).on('click','.mob-menu__list-item.is-submenu > .mob-menu__list-link',function(e){
        e.preventDefault();
        $(this).parents('.mob-menu__list-item.is-submenu').toggleClass('is-opened');
        $(this).siblings('.mob-menu__list-submenu').slideToggle(500);
    })

    $(document).on('mouseleave','.mob-menu.is-opened',function(){
        $('.main-menu__burger').toggleClass('is-opened');
        $('.mob-menu').toggleClass('is-opened');
    })*/
}

function adaptiveContent() {
    if(window.innerWidth <= 640){
        if($('.form.is-opened').length > 0){
            $('.form').removeClass('is-opened');
        }
        if($('.footer._type-1').length > 0){
            $('.footer._type-1').removeClass('_type-1').addClass('_type-2 _changed')
        }

    }
    else{
        if($('.footer._type-2._changed').length > 0){
            $('.footer._type-2._changed').removeClass('_type-2 _changed').addClass('_type-1')
        }
    }


}

function searchBtn(){
    $(document).on('click','.search__btn',function(e){
        var input = $(this).parents('form').find('input');
        if(input.val()===''){
            e.preventDefault();
            $(this).parents('.search').removeClass('is-opened');
        }
    })
    $(document).on('click','.search:not(".is-opened") button',function (e) {
        e.preventDefault();
        $(this).parents('.search').addClass('is-opened');
    })
    $(document).on('mouseenter','.search:not(".is-opened") button',function (e) {
        e.preventDefault();
        $(this).parents('.search').addClass('is-opened');
    })
    $(document).on('mouseleave','.search:not(".is-opened") button',function (e) {
        e.preventDefault();
        $(this).parents('.search').removeClass('is-opened');
    })

    $(document).mouseup(function (e){ // событие клика по веб-документу
        var domElem = $('.search'),
            targetName = domElem.attr('data-elem-target'),
            target = $('.js-targ[data-elem="'+targetName+'"]');

        if (!domElem.is(e.target) // если клик был не по нашему блоку
            && domElem.has(e.target).length === 0 &&
            !target.is(e.target) &&
            target.has(e.target).length === 0 &&
            domElem.is('.is-opened')) { // и не по его дочерним элементам
            //target.click();
            domElem.removeClass('is-opened');
        }
    });
}

function ajaxDo(){
    $( "body" ).on( "click", ".js-do", function(e) {
        e.preventDefault();
        //console.log(e.currentTarget,'EEEEE');
        var data = {};
        $.each(this.attributes, function(index, attribute) {
            // this.attributes is not a plain object, but an array
            // of attribute nodes, which contain both the name and value
            var dataName = '';
            if(attribute.name.indexOf('data-')>=0){
                dataName = attribute.name.split('data-')[1];
                if(dataName !== ''){
                    data[dataName] = attribute.value;
                }
            }
        });

        var action = data.action;
        if(action!='undefined')
        {
            if(action == 'main_onescreen'){
                $('.main-slider-goods').fadeOut(1000);
            }
            var url = main_ajax;
            if(data.action === 'current_page'){
                url = window.location.href;
            }
            $.post(main_ajax, data, function (data) {
                responseJson(data);
                $("input[name='phone']").mask("+7(999)999-99-99");
            });
        }
    });
}

// SHOW POPUP
function showPopup(popupItem, object) {

    var flag = false;
    if(object!== undefined)
        flag = true;

    var defaultWidth = 468;

    var min_vertical_indent = 80,
        popup_item_height = $(popupItem).innerHeight();

    var result_top_pos = ($(window).height() - popup_item_height) / 2;

    if (popup_item_height + min_vertical_indent * 2 > $(window).height())
        result_top_pos = min_vertical_indent;

    if(flag && object.absolute ==="true")
        result_top_pos = $(window).scrollTop() + result_top_pos;

    var width = defaultWidth;
    var marLeft = 0;

    if(flag && object.widthCss !== undefined)
        width = object.widthCss;

    if(flag && object.marLeft !== undefined)
        marLeft = object.marLeft;
    else
        marLeft = -width/2;

    //if(object.cssAuto === 'true'){
        $(popupItem).css({
            'width': width,
            'margin-left': marLeft
        });
    //}
    if(flag && object.className !== undefined)
        $(popupItem).addClass(object.className);

    //console.log(object.darkLay,'object');

    if(flag && object.darkLay === undefined){
        $('.form-dark').fadeIn('slow');
    }

    if(object.top!=='self')
        $(popupItem).css("top", result_top_pos);


    $(popupItem).addClass("is-show")
}

function close() {
    $(document).on('click','.popup__close, .form-dark, .js-popup-close',function (e) {
        closePopup();
        if($(this).parents('.popup-dop').length>0 || e.target.className==='form-dark'){
            $('.popup-dop').attr('style','').attr('class','popup-dop').empty();
            $('.form-dark').fadeOut();
        }
    })
}
function closePopup(){
    $('.popup').removeClass('is-show').fadeOut().empty();
    if(!$('.popup-dop').is('.is-show')){
        $('.form-dark').fadeOut();
    }

}
function formAjax(){

    $( document ).on( "submit", "form", function(e) {
        //$("form").on("submit", function (e) {
        var $this = $(this);
        $('.popup-content__errors').remove();
        $('input,textarea', $this).removeClass('is-error');

        var params = $(this).serializeArray();
        var ajaxform = false;
        $.each(params, function (key, param) {
            if (param.name == 'action' && param.value != '')
                ajaxform = true;
        });
        if (ajaxform) {
            e.preventDefault();
            var obj = this;
            var data = $(obj).serialize();
            $.post(main_ajax, data, function (data) {
                responseJson(data);
            });
        }
    });
}
function responseJson(data, json=true){
    //console.log(data);
    var arResult = JSON.parse(data);
    console.log(arResult);
    if (!arResult.func && arResult.error == 'ok')
        $(arResult.selector).html(arResult.result);
    else if (!arResult.func && arResult.error == 'error')
        alert(arResult.message);
    var tmpFunc = new Function(arResult.func);
    tmpFunc();
    $("input[name='phone']").mask("+7(999)999-99-99");
}
function moodBoards(){
    if($('.moodboards__content').length > 0){
        var $container = $(".moodboards__content");
        $container.imagesLoaded(function () {
            $container.masonry({
                columnWidth: $container.innerWidth / 2,
                itemSelector: ".page-about__moodboard-pic"
            });
        });
    }
}
function initTabs() {

    if ($(".js-tabs").length === 0)
        return false;

    /* switch tabs */
    $('body').on("click", '.js-switch-tab',function (e) {
        e.preventDefault();
        var link_elem = $(this),
            tabs_block = $(this).parents(".js-tabs"),
            new_active_content_block = $(this).attr("data-tab-goal");


        tabs_block.find(".js-switch-tab.is-active").removeClass("is-active");
        link_elem.addClass("is-active");

        tabs_block.find(".js-content-tab.is-active").removeClass("is-active");
        $(new_active_content_block).addClass("is-active");

    });
}
function contentOpen(){
    $('body').on('click','.js-open', function(){
        $('[data-open="main-block"]').removeClass('is-opened');
        $(this).parents('[data-open="main-block"]').toggleClass("is-opened");

        //console.log($(this).parents('[data-open="main-block"]'));
    })
}
function NoTarget(){
    $(document).mouseup(function (e){ // событие клика по веб-документу
        var domElem = $('.page-filter__field');
        var target = $('.page-filter__popup')
        if (!domElem.is(e.target) // если клик был не по нашему блоку
            && domElem.has(e.target).length === 0) { // и не по его дочерним элементам
            domElem.removeClass('is-opened');
        }

        var domElem = $('.kontakty-filter__field');
        var target = $('.kontakty__popup')
        if (!domElem.is(e.target) // если клик был не по нашему блоку
            && domElem.has(e.target).length === 0) { // и не по его дочерним элементам
            domElem.removeClass('is-opened');
        }

        var domElem = $('.header-city__field');
        var target = $('.header-city__popup')
        if (!domElem.is(e.target) // если клик был не по нашему блоку
            && domElem.has(e.target).length === 0) { // и не по его дочерним элементам
            domElem.removeClass('is-opened');
        }
    });
}
function initProductDetailPhotoGallery() {
    var galleryThumbs = new Swiper('.prod__tmbs', {
        spaceBetween: 20,
        slidesPerView: 6,
        freeMode: true,
        watchSlidesVisibility: true,
        watchSlidesProgress: true,
    });
    var galleryTop = new Swiper('.prod__image.swiper-container', {
        slidesPerView:1,
        spaceBetween: 0,
        navigation: {
            nextEl: '.prod__image .swiper-button-next',
            prevEl: '.prod__image .swiper-button-prev',
        },
        pagination: {
            el: '.prod__image .swiper__bullet',
            type: 'bullets',
            dynamicBullets: true,
            clickable: true,
        },
        thumbs: {
            swiper: galleryThumbs
        }
    });

    /*if($(".js-slick-3").length > 0){
        $(".js-slick-3:not(.slick-initialized)").slick({
            slidesToScroll: 1,
            draggable: false,
            slidesToShow: 1,
            infinite: true,
            accessibility: false,
            dots:true,
            fade:false,
            lazyLoad: 'ondemand',
            asNavFor: '.js-slick-nav-3',
        });

        $('.js-slick-nav-3:not(.slick-initialized)').slick({
            slidesToShow: 3,
            slidesToScroll: 1,
            arrows: false,
            asNavFor: '.js-slick-3',
            variableWidth: true,
            focusOnSelect:true,
            centerMode:false,
            infinite: false
        });
    }*/
}
function inBasket(obj, q, bid,id)
{
 //   console.log($(obj),q,bid,id);
    if(bid>0){
        var count_block = "<div class=\"count-block js-counter\">\n" +
            "                            <div class=\"count-block__btn js-minus icon-2b_minus\"></div>\n" +
            "                            <input class=\"count-block__value\" value=\""+ q +"\" data-bid=\""+bid+"\" data-action=\"basket_change\">\n" +
            "                            <div class=\"count-block__btn js-plus icon-2a_plus\"></div>\n" +
            "                        </div>"

        if(!q)
            q = 1;
        if($(obj).length>0){
            $(obj).removeClass('js-do').html(count_block);
        }
    }
}
function inDelay($id,$state)
{
    var obj = $('.js-do[data-action=delay_change][data-id="'+$id+'"]');
    //console.log(obj);
    if($state === 'Y'){
        $(obj).addClass("is-active");
        $(obj).attr('data-state', 'Y');
        $(obj).find('._text').text('В отложенном');
    }
    else if ($state === 'N'){
        $(obj).removeClass("is-active");
        $(obj).attr('data-state', 'N');
        $(obj).find('._text').text('Отложить');
    }
}
function inDelayList(objs)
{
    objs.forEach(function(item, i, arr) {
        inDelay(item,'Y');
    });
}

function initScrollGoal() {
    $(document).on('click','.js-scrollto',function () {
        var element = $(this).data('scrollto_goal');
        $('html, body').animate({scrollTop: $(element).offset().top - 150}, '250');
    })
}


$(document).on('dragover','.standard-form__dis-btn', function(e){
    e.stopPropagation();
    $(this).addClass('is-hover');
})
$(document).on('dragleave dragend drop','.standard-form__dis-btn',function(e){
    e.stopPropagation();
    $(this).removeClass('is-hover');
});

function EventsGa(){
    var gaobj = {}
    gaobj.click = {
        0:{
            element: '.js-event-1',
            event_ga: 'ga(\'send\', \'event\', \'brand_page\', \'download_katalog\');'
        },
    }

        Object.keys(gaobj).forEach(function(key, index){
            //console.log(key,index,gaobj[key][0]);
            for(let $i=0;$i<Object.keys(gaobj[key]).length;$i++){
                $(document).on(key,gaobj[key].element,function(){
                    gaobj[key][$i].event_ga;
                });
            }

        });
}
function elementShowMore() {

    $('.js-more-list').each(function(i){
        var resolution_less = $(this).attr('data-resolution-less');
        var flag = true;

        if(resolution_less > 0){
            if(window.innerWidth > resolution_less)
                flag = false;
        }

        console.log(flag,'flag');

        var hideElementObj = new toHideList($(this));
        if(flag){
            $(this).find('.js-more-btn').on('click', function(){
                hideElementObj.click($(this));
            });
        }
        else{
            hideElementObj.unbind($(this).attr('data-btn-name'));
        }

    })
}

function Anchor(){
    $(document).on('click','.js-anchor',function (e) {
        e.preventDefault();
        var _this = $(this);
        var destination = $(_this.attr('href')).offset().top;
        $('html').animate({ scrollTop: destination - 50}, 500);
    });
}

function menuBurger(){
    $(document).on('click','.js-link.is-menu', function(e){
        e.preventDefault();
        if($('.main-menu__item-wrap.is-opened-sub').length>0){
            $('.main-menu__item-wrap.is-opened-sub .main-menu__item-btn').trigger('click');
        }
        else{
            $('.main-menu__list').toggleClass('is-opened');
        }

    })
    $(document).on('click', '.main-menu__item-btn',function(e){
        e.preventDefault();
        if($(this).parents('.main-menu__item-wrap').find('.main-menu__submenu').length>0){
            if($(this).parents('.main-menu__item-wrap.is-opened-sub').length>0){
            }
            else{
                $('.main-menu__item-wrap.is-opened-sub').removeClass('is-opened-sub');
            }
            $(this).parents('.main-menu__item-wrap').toggleClass('is-opened-sub');

        }
    })
}

function initSliderImageMove(){
    if($('.js-slide-ix-block:not(.initialized)').length > 0){
        $('.js-slide-ix-block:not(.initialized)').mangoSlider();
    }
}


var timeOutVal = false;
function  searchHint(){
    $(document).on('keyup', '.search__form input[name="q"]',function(){
        var request = $(this)[0].value;
        var block = $(this).parents('.search');
        var section = block.find('.search__form-section').attr('data-section_id');
        clearTimeout(timeOutVal);

        if(request.length > 2){
            timeOutVal  = setTimeout(
                function() {
                    sendRequest(request,timeOutVal,block,section,'')
                }, 300);
        }
    });

    function sendRequest(request,timeOutVal,block,section,state){
        $('.search-hint-ajax').empty();
        $.post('/local/php_interface/ajax/search-hint.php',
            {'q': request,'state': state,'section':section}, function (data) {
                block.find('.search-hint-ajax').replaceWith(data);
                clearTimeout(timeOutVal);
            });
    }

    $(document).on('focus', '.search__form input[name="q"]',function(){
        var request = $(this)[0].value;
        var block = $(this).parents('.search');
        var section = block.find('.search__form-section').attr('data-section_id');
        //devLog(section,'section');
        if(section <= 0){
            clearTimeout(timeOutVal);
            timeOutVal  = setTimeout(
                function() {
                    sendRequest(request,timeOutVal,block,section,'empty')
                }, 100);
        }
    });

    $(document).mouseup(function (e){ // событие клика по веб-документу
        var domElem = $('.search');
        /*if (!domElem.is(e.target) // если клик был не по нашему блоку
            && domElem.has(e.target).length === 0) { // и не по его дочерним элементам
            domElem.find('.search-hint-ajax').empty();
        }*/
    });
    /*$(document).on('click','.search-hint__btn-close',function (e){ // событие клика по веб-документу
        $(this).parents('.search-tip-ajax').empty();
    });*/
}

function changeCardList(){
 $(document).on('click','.js-list-card',function(){
     var data = $(this).data();
     data.ajax = 'list-card';
     var ident = $(this).parents('.cst-select').attr('data-ident');
     var parent = $('.js-sort-list[data-ident='+ident+']').parents('.catg__item .catg__item-wrap');
     console.log(ident,parent,'parent');
     $.post(main_ajax, data, function (data) {
         var arResult = JSON.parse(data);
         parent.animate({'opacity':0},200).replaceWith(arResult.result).animate({'opacity':1},200);
     });
 })
}

function flyProd(elem){
    if(elem!==undefined){
            var that = $(elem).parents('.popup__content').find('.popup__img');
            if(that!==undefined){
                var bascket = $(".pers-info__item.is-basket");
                var w = that.width();
                that.clone()
                    .css({'width' : w,
                        'position' : 'absolute',
                        'z-index' : '9999',
                        top: that.offset().top,
                        left:that.offset().left})
                    .appendTo("body")
                    .animate({opacity: 0.05,
                        left: bascket.offset()['left'],
                        top: bascket.offset()['top'],
                        width: 20}, 1000, function() {
                        $(this).remove();
                    });
            }

    }
}
