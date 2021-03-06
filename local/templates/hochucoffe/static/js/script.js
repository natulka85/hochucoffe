var objSetupScroll ={
    cursorcolor:"#eb6a57",
    cursorborderradius: "5px",
    //cursorfixedheight: 'true',
    hidecursordelay: 100,
    cursorminheight: 46
};


$(function () {
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


    SlickSliders();
    moreContent();
    moreContentChange();
    mobileMenu();
    adaptiveContent();
    //searchBtn();
    close();
    formAjax();
    select();
    initTabs();
    contentOpen();
    NoTarget();
    initProductDetailPhotoGallery();
    ajaxDo();
    nicescrollInit();
    reinitfuncs();
    HeaderSticky();
    initScrollGoal();
    EventsGa();
    SortList();
    counter();
    slideDown();

    $("input[name='phone']").mask("+7(999)999-99-99");
})
function slideDown(){
    $(document).on('click', '.js-slide-btn', function(){
       $(this).siblings('.js-slide-content').slideToggle(500);
    })
}
function Sticky(parent,element,num=0){
    //var parent = parent;
    //var element = element;
    if(parent.length > 0 && element.length>0)
    $(window).scroll(function () {
        var scroll = $(window).scrollTop();
        var parentPos = parent.offset().top;
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
    });
}
function counter(){
    $(document).on('click', '.js-plus',function () {
        var input = $(this).parents('.js-counter').find('input');
        let num = +input.val();
        input.val(num+1);
    });
    $(document).on('click', '.js-minus',function () {
        var input = $(this).parents('.js-counter').find('input');
        let num = +input.val();
        if (num > 1 && num - 1 >= 0) input.val(num - 1);
    });
    $(document).on('focus','.count-block__value',function () {
        console.log('test');
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
        $(this).toggleClass('is-opened');
    });
    $(document).on('click','.cst-select-option', function(){
        var text = $(this).text();
        $(this).siblings().removeClass('is-active');
        $(this).addClass('is-active');
        $(this).parents('.cst-select-list').find('.cst-select-current').text(text);
    })

    $(document).on('mouseleave','.catg__item, .basket__el',function(){
        $(this).find('.js-sort-list').removeClass('is-opened');
    })
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

//    console.log(data,url,mode,'3');

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

                //$('.js-ajax-filter').css('opacity', '0.5');

            //console.log(data,'ss');

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
                        var catlist = $(json_res['catlist']);
                        var breadcrumbs = json_res['breadcrumbs'];
                        var h1 = json_res['h1'];

                        if (mode === 'block') {
                            var block = $(".js-ajax-content[data-ajax="+data['ajax']+"]");

                            if(block.length>0){
                                block.replaceWith(content);
                            }
                        }
                        else if(mode === 'filter_sec'){
                            $('.js-ajax-content:not(:first):not([data-ajax])').remove();

                            $('.js-ajax-filter').replaceWith(filter);
                            $('.js-ajax-catlist').replaceWith(catlist);
                            $('.js-ajax-content:not([data-ajax])').replaceWith(content);
                            $('.breadcrumbs').replaceWith(breadcrumbs)
                            $('.screen-title._type-1').text(h1);
                            $('title').text(json_res['title']);
                            $('[name=description]').attr('content',json_res['description']);

                        }
                        else {
                            //console.log($('.js-ajax-content:not([data-ajax])'),'$(\'.js-ajax-content:not([data-ajax])\')');
                            //console.log(catlist,'res');
                            $('.js-ajax-content:not(:first):not([data-ajax])').remove();
                            $('.js-ajax-content:not([data-ajax])').replaceWith(content);
                            $('.js-ajax-catlist').replaceWith(catlist);

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

function SlickSliders() {
    if ($(".banner__list").length > 0) {
        $(".banner__list:not(.slick-initialized)").slick({
            slidesToScroll: 1,
            //draggable: false,
            slidesToShow: 1,
            infinite: true,
            //accessibility: false,
            autoplay: true,
            pauseOnHover:true,
            autoplaySpeed: 3000, //делаем запуск мгновенный с загрузкой страницы
            dots:true,
            speed: 2000,
            lazyLoad: 'ondemand',
        });
        $('.banner .slick-dots, .banner .slick-arrow').on('click', function() {
            $('.banner__list').slick('slickPause');
        });
        //console.log('js-slick-1');
    }
    if ($(".sections__list").length > 0) {
        var mainOptions =  {
            slidesToScroll: 1,
            //draggable: false,
            slidesToShow: 3,
            infinite: true,
            //accessibility: false,
            autoplay: true,
            autoplaySpeed: 3000, //делаем запуск мгновенный с загрузкой страницы
            cssEase: 'linear', // делаем анимацию однотонной при смене слайда
            //speed: 8000,
            pauseOnHover:true,
            lazyLoad: 'ondemand',
        }
        var $slick = $(".sections__list:not(.slick-initialized)");
        $slick.slick(mainOptions);

        $('.sections__list .slick-dots, .sections__list .slick-arrow').on('click', function() {
            $('.sections__list').slick('slickPause');
        });

    }
    if ($(".view__list").length > 0) {
        $(".view__list:not(.slick-initialized)").slick({
            slidesToScroll: 1,
            //draggable: false,
            slidesToShow: 1,
            infinite: true,
            //accessibility: false,
            autoplay: true,
            pauseOnHover:true,
            speed: 1000
            //lazyLoad: 'ondemand',
        });
        $('.view__list .slick-dots, .view__list .slick-arrow').on('click', function() {
            $('.view__list').slick('slickPause');
        });
        //console.log('js-slick-1');
    }
    if ($(".your-interests .catg__list").length > 0) {
        $(".your-interests .catg__list:not(.slick-initialized)").slick({
            slidesToScroll: 4,
            slidesToShow: 4,
            infinite: true,
            autoplay: false,
            dots:true,
            lazyLoad: 'ondemand',
        });

    }
    if ($(".your-viewed .catg__list").length > 0) {
        $(".your-viewed .catg__list:not(.slick-initialized)").slick({
            slidesToScroll: 4,
            slidesToShow: 4,
            infinite: true,
            autoplay: false,
            dots:true,
            lazyLoad: 'ondemand',
        });

    }

}

function moreContent() {
    $('.js-show-more-btn').each(function () {
        var block = $(this).parents('[data-show_more="block"]');
        var height = block.attr('data-show_more_height');

        if(height>0){
            //console.log(height);
            var content = block.find('[data-show_more="content"]');
            var contentHieght = content.outerHeight();

            if(contentHieght > height){
                content.attr('data-show_more_realheight',contentHieght).css({'height':height});
                $(this).show();
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

    $(document).on('click','.mob-menu__list-item.is-submenu > .mob-menu__list-link',function(e){
        e.preventDefault();
        $(this).parents('.mob-menu__list-item.is-submenu').toggleClass('is-opened');
        $(this).siblings('.mob-menu__list-submenu').slideToggle(500);
    })

    $(document).on('mouseleave','.mob-menu.is-opened',function(){
        $('.main-menu__burger').toggleClass('is-opened');
        $('.mob-menu').toggleClass('is-opened');
    })
}

function adaptiveContent() {
    if(window.innerWidth <= 991){

    }
    else{
    }
}

function searchBtn(){
    if(window_width > 991){


    $(document).on('click','.search-block:not(".is-opened") button',function (e) {
        e.preventDefault();
        $(this).parents('.search-block').addClass('is-opened');
    })

    $(document).mouseup(function (e){ // событие клика по веб-документу
        var domElem = $('[data-elem-target]'),
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
}

function ajaxDo(){
    $( "body" ).on( "click", ".js-do", function(e) {
        e.preventDefault();
        var action = $(this).data( "action" )
        if(action!='undefined')
        {
            if(action == 'main_onescreen'){
                $('.main-slider-goods').fadeOut(1000);
            }
            var url = main_ajax;
            if($(this).data( "action" ) === 'current_page'){
                url = window.location.href;
            }
            $.post(main_ajax, $(this).data(), function (data) {
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

    var width = defaultWidth;
    var marLeft = 0;

    if(flag && object.widthCss !== undefined)
        width = object.widthCss;

    if(flag && object.marLeft !== undefined)
        marLeft = object.marLeft;
    else
        marLeft = -width/2;

    if(object.cssAuto === 'true'){
        $(popupItem).css({
            'width': width,
            'margin-left': marLeft
        });
    }


    if(flag && object.darkLay === undefined){
        $('.form-dark').fadeIn('slow');
    }

    if(object.top!=='self')
        $(popupItem).css("top", result_top_pos);

    $(popupItem).addClass("is-show")
}

function close() {
    $(document).on('click','.popup__close, .form-dark',function () {
        closePopup();
    })
}
function closePopup(){
    $('.popup').remove();
    $('.form-dark').fadeOut();
}
function formAjax(){

    $( document ).on( "submit", "form", function(e) {
        //$("form").on("submit", function (e) {
        var $this = $(this);
        $('.popup-content__errors').remove();
        $('input', $this).removeClass('is-error');

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
    //console.log(arResult);
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
    if($(".js-slick-3").length > 0){
        $(".js-slick-3:not(.slick-initialized)").slick({
            slidesToScroll: 1,
            draggable: false,
            slidesToShow: 1,
            infinite: true,
            accessibility: false,
            dots:false,

            lazyLoad: 'ondemand',
            asNavFor: '.js-slick-nav-3',
        });

        $('.js-slick-nav-3:not(.slick-initialized)').slick({
            slidesToShow: 5,
            slidesToScroll: 5,
            dots: false,
            asNavFor: '.js-slick-3',
            variableWidth: true,
            infinite: true,
            focusOnSelect: true,
            vertical: true,
            verticalSwiping: true,
        });
    }
}

function inDelay(obj)
{
    //console.log(obj,'obj');
    if($(obj).hasClass('checkbox__label') || $(obj).hasClass('js-state-reverse'))
    {
        $(obj).addClass("is-active");
        $(obj).data('state', 'Y');
        $(obj).parent().find('input').prop('checked', true);
    } else {
        $(obj).off();
        $(obj).addClass("is-active").removeClass("js-do");
        /*if(window.location.href.indexOf('/personal/delay/') < 0)
            $(obj).attr("onclick", "window.location.href = '/personal/delay/';");*/
    }
}
function inDelayList(objs)
{
    objs.forEach(function(item, i, arr) {
        var obj = $(".js-do[data-action=delay_change][data-id=\""+item+"\"]");
        inDelay(obj);
    });
}

function nicescrollInit(){
    $("#nicescroll").niceScroll(objSetupScroll);
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
