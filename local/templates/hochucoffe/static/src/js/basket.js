function submitForm(val)
{
    var orderForm = $('#ORDER_FORM');
    var data = $(orderForm).serialize();
    $('button[name="order_ok"]').attr('disabled','disabled');
    if(val=='Y')
    {
        data = data+'&'+$.param({ 'order_ok': 'Y' });
        //window.location.hash = "tabs-1";
    }
    if(val=='F')
    {
        data = data+'&'+$.param({ 'forder_ok': 'Y' });
        //window.location.hash = "tabs-2";
    }
    data += '&result_class='+$('.basket__result').attr('class');
    BX.ajax.post(
        $(orderForm).attr('action'),
        data,
        ajaxResult
    );

}
function ajaxResult(res)
{
    $('.basket__left').css('opacity','0.5');

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
        basketinit();

        //if($('.after-pay').attr('data-id')!=undefined)
            //newOrderEvent($('.after-pay').attr('data-id'),"fullorder","");
    }

    BX.onCustomEvent(orderForm, 'onAjaxSuccess');
    $(".sbasket-refresh").click();
}
$(function(){
    stickyResult();
    basketInput();

    $(document).on('change','input[name="forder_ok"]',function(){
        console.log($(this).val());
        if($(this)[0].checked){
        $('.basket__full-order').fadeOut(300);
        $('.basket__fast-order').fadeIn(300);
        }
        else{
        $('.basket__fast-order').fadeOut(300);
        $('.basket__full-order').fadeIn(300);
        }
    })
    $(document).on('click','.js-nice-check',function(){
        var id = $(this).data('input_id');
        $('input#'+id)[0].checked = true;
        $(this).parents('.basket__fields').find('.basket__form-btn-choose').removeClass('is-active');
        $(this).find('.basket__form-btn-choose').addClass('is-active');

        $(".sbasket-refresh").click();
        $("#basket-refresh").click();
    })
    $(document).on('click', '.basket__link-change',function(){
        var _input_name = $(this).data('c_name');
        var _input = $('input[name='+_input_name+']');
        $(_input).trigger('focus');
    })
    if($('.your-viewed__sw-cont.swiper-container').length>0){
        var swiper_1 = new Swiper('.your-viewed__sw-cont.swiper-container', {
            speed: 400,
            slidesPerView: 2,
            spaceBetween: 0,
            slidesPerGroup: 2,
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
function basketinit(){
    stickyResult();
    basketInput();
    if($('.basket__field.is-error').length > 0){
        var _this = $('.basket__field.is-error').eq(0).parents('.basket__fields');
        var destination = _this.position().top;
        $('html').animate({ scrollTop: destination}, 500);
    }
}

function stickyResult(){
    if(window.innerWidth>640){
        StickyMy($('.basket__content-wr'), $('.basket__result'),10);
    }
}

function basketInput(){
    $('.basket__input').each(function(){
        if($(this).val()!=''){
            $(this).siblings('.basket__label').fadeOut();
        }
    })
}
