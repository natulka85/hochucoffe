$(function(){
    initTabs();
    ajaxItems();
    masks();
    jsAjaxDo();
    setAjaxFormHandler();
    closePopup();
    styledFormElements();
    blockDelete();
    MenuLinksSelect();
/*

    $('input[name^="PROP[417]"]').attr('readonly',true);
    $('input[name^="PROP[868]"]').attr('readonly',true);
    $('input[name^="PROP[869]"]').attr('readonly',true);*/
});

function masks(){
    $('.js-mask-data').mask('99.99.9999');
    $('.js-mask-data-time').mask('99.99.9999 99:99:99');
}

function initTabs() {

    if ($(".js-tabs").length === 0)
        return false;

    /* switch tabs */
    $(document).on("click", '.js-switch-tab',function (e) {
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

function ajaxItems(){
    $(document).on('click','.js-adm-btn', function(e){
        e.preventDefault();

        var $this = $(this),
            atributes = $this.data(),
            url = atributes['url'];

        if(atributes['more']===true){
            var block_wrap = $('[data-target="'+atributes['content']+'"]');

            var data = {};
            data.ajax_mode = 'more';
            data.index = atributes['index'];

            $.post(url,data,function(res){
                var btn_new = $(res).find('[data-more="true"]');
                var el_new = $(res).find('[data-element="element"]');

                block_wrap.append(el_new);
                $this.replaceWith(btn_new);
                masks();
            })

        }
        else if(atributes['delete']===true){
            var data = {};
            //data.ajax_mode = 'delete';
            //data.index = atributes['index'];

            $this.parents('[data-delete="element"]').empty();

            /*$.post(url,data,function(res){
                console.log(res);

            })*/
        }


        //console.log(data);

        return false;
    });
}

function jsAjaxDo(){
    $(document).on( "click touchstart", ".js-adm-do", function(e) {
        e.preventDefault();
        var file = $(this).data( "action" );
        if(file)
        {
            var data = $(this).data();
            if(data.take === 'form'){
                var form = $(this).parents('form');
                var Data = new FormData(form[0]);

                $.each(data,function (index,value) {
                    Data.append(index,value);
                });
                data = Data;
            }
            $.ajax({
                type: "POST",
                url: '/local/modules/ajax/' +file+ '.php',
                data: data,
                processData: false,
                contentType: false,
            }).done(function(data){
                console.log(data);
                var arResult = JSON.parse(data);
                //console.log(arResult);
                if (!arResult.func && arResult.error == 'ok')
                    $(arResult.selector).html(arResult.result);
                else if (!arResult.func && arResult.error == 'error')
                    alert(arResult.message);
                var tmpFunc = new Function(arResult.func);
                tmpFunc();
                masks();
            });
        }
    });
}

function responseJson(data, json){
    console.log(data,'data');
    if(json !== false){
        if(data !== ''){
            var arResult = JSON.parse(data);

            if(arResult.func){
                var func = new Function(arResult.func);
                func();
            }
        }
//console.log(arResult);
    }
    else{
    }

}

function showPopup(popupItem, width) {

    var defaultWidth = $(popupItem).actual('width');

    var min_vertical_indent = 80,
        popup_item_height = $(popupItem).innerHeight();

    var result_top_pos = ($(window).height() - popup_item_height) / 2;

    if (popup_item_height + min_vertical_indent * 2 > $(window).height())
        result_top_pos = min_vertical_indent;

    $(".global-overlay").addClass("is-show");

    if(width > 0){
        $(popupItem).css({
            'width': width,
            'margin-left': -width/2
        });
    }
    else{
        $(popupItem).css({
            'width': defaultWidth,
            'margin-left': -defaultWidth/2
        });
    }

    $(popupItem).css("top", result_top_pos).addClass("is-show");

    if($(popupItem).is('.is-blur')) {
        $('body, .fast-product-preview').addClass('is-blur');
    }
}

function setAjaxFormHandler() {
    $(document).on('submit', '.js-form', function(e){
        var $this = $(this);
        if(!$this.is('.nojs')){
            $('.popup-content__errors, .callback-form__errors').remove();
            $('input', $this).removeClass('is-error');

            var file = '';
            var params = $(this).serializeArray();
            var ajaxform = false;
            $.each(params, function (key, param) {
                if (param.name === 'action' && param.value !== '')
                    file = param.value;
                ajaxform = true;
            });

            console.log(file,ajaxform);

            if (ajaxform) {
                e.preventDefault();
                var obj = this;
                var data = $(obj).serialize();
                $.post(
                    '/local/modules/ajax/' +file+ '.php',
                    data,
                    function (data) {
                        console.log(data,'data');
                        var arResult = JSON.parse(data);
                        //console.log(arResult);
                        if (!arResult.func && arResult.error == 'ok')
                            $(arResult.selector).html(arResult.result);
                        else if (!arResult.func && arResult.error == 'error')
                            alert(arResult.message);
                        var tmpFunc = new Function(arResult.func);
                        tmpFunc();
                    }
                );
            }
        }
    });
}

// HIDE POPUP
function hidePopup() {
    $(".popup-admin.is-show").remove();
}

// CLOSE POPUP
function closePopup() {
    $("body").on("click", ".js-close-popup", function () {
        hidePopup();
    });

    $(document).mouseup(function (e){ // событие клика по веб-документу
        var domElem = $('.popup-admin'),
            targetName = $('.popup-admin'),
            target = $('.popup-admin');
        if (!domElem.is(e.target) // если клик был не по нашему блоку
            && domElem.has(e.target).length === 0 &&
            !target.is(e.target) &&
            target.has(e.target).length === 0 &&
            domElem.is('.is-show')) { // и не по его дочерним элементам
            target.click();
            hidePopup();
        }
    });
}



// FORM ELEMENTS
function styledFormElements() {
    /* init placeholder */
    $('input[placeholder], textarea[placeholder]').placeholder();

    /* styled select */
    if ($('.styled-select').length)
        $('.styled-select').selecter();
}

function blockDelete(){
    $(document).on('click', '.js-block-del',function () {
        $(this).parent().remove();
        var data = $(this).data();

        console.log(data,'data');

        if(data.id>0){
            $.post(window.location.url,data,function(data){
                responseJson(data);
            });
        }
    })
}

function MenuLinksSelect(){
    $(document).on('change','.js-linksmenu-propcode',function(){
        var prop = $(this).val();
        var elem = $(this).parents('.admin-form__items');
        $.post(window.location.url,{'property_code':prop}, function(data){
            elem.find('.js-ajax-content').replaceWith(data);
            styledFormElements();
        });
    })
}

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

    //if(object.cssAuto === 'true'){
    $(popupItem).css({
        'width': width,
        'margin-left': marLeft
    });
    //}

    console.log(object.darkLay,'object');

    if(flag && object.darkLay === undefined){
        $('.form-dark').fadeIn('slow');
    }

    if(object.top!=='self')
        $(popupItem).css("top", result_top_pos);

    $(popupItem).addClass("is-show")
}


///свойства товара
$(function(){

    var stop = true;
    $(document).on("drag", ".card-props__field, .card-props__block",function (e) {

        stop = true;

        if (e.originalEvent.clientY < 150) {
            stop = false;
            scroll(-1)
        }

        if (e.originalEvent.clientY > ($(window).height() - 150)) {
            stop = false;
            scroll(1)
        }

    });

    $(document).on("dragend",".card-props__field, .card-props__block", function (e) {
        stop = true;
    });

    var scroll = function (step) {
        var scrollY = $(window).scrollTop();

        $(window).scrollTop(scrollY + step);
        if (!stop) {
            setTimeout(function () { scroll(step) }, 20);
        }
    }

    addWraps('card-props__field-wrap','card-props__field');
    addWraps('card-props__block-wrap','card-props__block');

    $(document).on('click','.js-card-btn', function(){
        var attributes = $(this).data();
        var $this = $(this);

        if(attributes.act === 'delete_field'){
            console.log($this.siblings('[data-delete="element"]'));
            $this.parents('[data-delete="element"]').eq(0).remove();
            addWraps('.card-props__field-wrap','.card-props__field');
        }
        else if(attributes.act === 'add_field'){
            var element = $(this).prev().html();
            $(this).before('<div class="card-props__field">'+element+'</div>');

        }
    })

    var target = '';
    var elem = '';
    var block = false;
    var field = false
    $(document).on('dragstart','.card-props__field', function () {
        $(this).addClass('is-active');
        field = true;
    });
    $(document).on('dragenter','.card-props__field-wrap', function (event) {
        if(!field){
            return false
        }
        target = event.currentTarget;

        $('.card-props__field-wrap').removeClass('is-hover');
        $(target).addClass('is-hover');
    });
    $(document).on('dragend','.card-props__field', function (event) {
        if(!field){
            return false
        }
        elem = event.currentTarget;
        $(elem).prependTo(target);
        $(elem).unwrap().removeClass('is-active');
        addWraps('card-props__field-wrap','card-props__field');

        var block_index = $(elem).parents('.card-props__block').attr('data-block_index');

        $(elem).find('select').attr('name','props['+block_index+'][prop][]');

        target = '';
        field = false;
    });

    var target2 = '';
    var elem2 = '';
    $(document).on('dragstart','.card-props__block', function () {
        $(this).addClass('is-active');
        block = true;
    });
    $(document).on('dragenter','.card-props__block-wrap', function (event) {
        if(!block){
            return false
        }
        target2 = event.currentTarget;
        $('.card-props__block-wrap').removeClass('is-hover');
        $(target2).addClass('is-hover');
    });
    $(document).on('dragend','.card-props__block', function (event) {
        if(!block){
            return false
        }
        elem2 = event.currentTarget;
        $(elem2).prependTo(target2);
        if($(elem2).parent().is('.card-props__block-wrap'))
            $(elem2).unwrap().removeClass('is-active');

        addWraps('card-props__block-wrap','card-props__block');
        target2 = '';
        block = false;
    });

function addWraps(wrapClass, elemClass){
    $('.'+wrapClass).remove();
    $('.'+elemClass).each(function(index,element){
        if(!$(element).prev().is('.'+wrapClass)){
            $(element).before('<div class="'+wrapClass+'"></div>');
        }
        else if(!$(element).next().is(wrapClass)){
            $(element).after('<div class="'+wrapClass+'"></div>');
        }
    });
}
})
