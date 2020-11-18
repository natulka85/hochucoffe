function ajaxResult(res)
{
    $('.basket-calc__fields').css('opacity','0.5');

    var orderForm = BX('ORDER_FORM');
    try
    {// if json came, it obviously a successfull order submit
        var json = JSON.parse(res);
        BX.closeWait();

        if (json.error)
        {
            BXFormPosting = false;
            return;
        }
        else if (json.redirect)
        {
            window.top.location.href = json.redirect;
        }
    }
    catch (e)
    {// json parse failed, so it is a simple chunk of html
        BXFormPosting = false;
        BX('order_form_content').innerHTML = res;
        //$('.basket-calc__fields').css('opacity','1');
        basketinit();


        if($('.after-pay').attr('data-id')!=undefined)
            newOrderEvent($('.after-pay').attr('data-id'),"fullorder","");
    }

    BX.onCustomEvent(orderForm, 'onAjaxSuccess');
    $(".sbasket-refresh").click();
}

$(document).ready(function(){
    $( "body" ).on( "click", ".basket-adddelay a.open", function(e) {
        e.preventDefault();
        $(this).parent('.basket-adddelay').find('.basket-adddelay-block').show();
    });
    $( "body" ).on( "click", ".basket-adddelay-block a.cclose", function(e) {
        e.preventDefault();
        $(this).parents('.basket-adddelay-block').hide();
    });
    $( "body" ).on( "click", ".js-basket-close", function(e) {
        e.preventDefault();
        $(this).parent('.dop-items').hide();
        $(this).parents('.item-line-upgrade').find('.js-basket-open').css('display', 'block');
    });
    $( "body" ).on( "click", ".js-basket-open", function(e) {
        e.preventDefault();
        $(this).hide();
        $(this).parents('.item-line-upgrade').find('.dop-items').show();
        $.post(
            BX.message('PATH_TO_AJAX'),
            {
                'action': 'basket_dop',
                'id': $( this ).data().id,
                'lamps': $( this ).data().lamps,
                'other': $( this ).data().other,
                'q': $( this ).data().q,
            },
            function (data) {
                var arResult = JSON.parse(data);
                if (!arResult.func && arResult.error == 'ok')
                    $(arResult.selector).html(arResult.result);
                else if (!arResult.func && arResult.error == 'error')
                    alert(arResult.message);
                var tmpFunc = new Function(arResult.func);
                tmpFunc();

                $(arResult.selector).find('.dop-items-between').slick({
                    infinite: false,
                    slidesToShow: 3,
                    slidesToScroll: 1,
                    dots: false,
                    variableWidth: true,
                    arrows: true,
                    //prevArrow: '.after-table-n-p-prev',
                    //nextArrow: '.after-table-n-p-next, .slider-right-arrow-srav',
                    responsive: [
                        {
                            breakpoint: 767,
                            settings: {
                                slidesToShow: 1,
                                //vertical: true,
                                //mobileFirst: true,
                            }
                        }
                    ]
                });
            }
        );
    });
    $( "body" ).on( "click", ".js-basket-delivery", function(e) {
        e.preventDefault();
        $(this).parent('.checkbox-item').find('.js-basket-description').toggle();
    });
    $( "body" ).on( "click", "input[name='delivery']", function(e) {
        e.preventDefault();
        $('#basket-refresh').click();
    });
    $( "body" ).on( "click", ".js-promocode", function(e) {
        e.preventDefault();
        $('#basket-refresh').click();
    });
    $( "body" ).on( "click", ".js-bsk-promo", function(e) {
        e.preventDefault();
        $('#formpromo').show();
        $('#formmsk').hide();
    });
    $( "body" ).on( "click", ".js-bsk-msk", function(e) {
        e.preventDefault();
        $('#formpromo').hide();
        $('#formmsk').show();
    });
    $( "body" ).on( "click", ".msr-help-open", function(e) {
        e.preventDefault();
        $('.msr-help-old').toggle();
    });
    $( "body" ).on("focusin", '#ORDER_FORM input[type="text"]', function()
    {
        if($(this).val()=='')
        {
            $(this).attr('data-placeholder', $(this).attr('placeholder'));
            $(this).attr('placeholder','');
        }
    });
    $( "body" ).on("focusout", '#ORDER_FORM input[type="text"]', function()
    {
        if($(this).val()==''||$(this).val()=='+7(___)___-__-__  ____')
            $(this).attr('placeholder',$(this).attr('data-placeholder'));
    });

    $( "body" ).on("change", '#ORDER_FORM input[name="fio"]', function()
    {
            $('#ORDER_FORM input[name="qname"]').val($(this).val());
    });
    $( "body" ).on("change", '#ORDER_FORM input[name="bphone"]', function()
    {
            $('#ORDER_FORM input[name="qphone"]').val($(this).val());
    });

    if(location.hash == '#tabs-2' && document.referrer!=window.location.href)
    {
      setTimeout(function() {window.scrollTo(0, 0);}, 1);
    }

    basketinit();

    //ylike
    if ($( ".js-ylike" ).length > 0 )
    {

        $.post(
            BX.message('PATH_TO_AJAX'),
            {
                'action': 'ylike',
                'ids': $( ".js-ylike" ).data().ids,
            },
            function (data) {
                var arResult = JSON.parse(data);
                if (!arResult.func && arResult.error == 'ok')
                    $(arResult.selector).html(arResult.result);
                else if (!arResult.func && arResult.error == 'error')
                    alert(arResult.message);
                var tmpFunc = new Function(arResult.func);
                tmpFunc();
            }
        );
    }

    //YSHOWN
    if ($( ".js-yshown" ).length > 0 )
    {

        $.post(
            BX.message('PATH_TO_AJAX'),
            {
                'action': 'yshown',
                'ids': $( ".js-yshown" ).data().ids,
            },
            function (data) {
                var arResult = JSON.parse(data);
                if (!arResult.func && arResult.error == 'ok')
                    $(arResult.selector).html(arResult.result);
                else if (!arResult.func && arResult.error == 'error')
                    alert(arResult.message);
                var tmpFunc = new Function(arResult.func);
                tmpFunc();
            }
        );
    }

});

function basketinit()
{
    $("input[name='bphone']").mask("+7(999)999-99-99 ? 9");
    $("input[name='qphone']").mask("+7(999)999-99-99 ? 9");
    spinnerb();
    $( "#formmsk" ).tooltip({position: {my: "center bottom-1", at: "center top"}});
    //calc_basket.calcSumm();

}

$(document).on('click','.basket-calc__link',function () {
    $(this).toggleClass('is-closed').next('.basket-calc__fields').slideToggle(500)
})

