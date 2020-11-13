$(function(){
    if(window.innerWidth>640){
        Sticky($('.basket__content-wr'), $('.basket__result'),10);
    }

    $(document).on('change','input[name="fast_order"]',function(){
        console.log($(this).val());
        if($(this)[0].checked){
        $('.basket__full-order').fadeOut(300);
        }
        else{
        $('.basket__full-order').fadeIn(300);
        }
    })
})
