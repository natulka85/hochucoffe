var basketAjax = {
    contaner : '.js-ajax-content',
    page: window.location.href,
    timeOut: null,
    start: false,
    small_list_status: 'is-closed',
    changeQunatity: function(elem){

        if(elem.parents('.top-small-basket').length > 0){
            this.small_list_status = 'is-opened'
        }

        var quantity = parseInt(elem.parent().find('.js-count-field').val());
        var id = elem.parent().attr('data-id');

        if(elem.is('._minus')){
            if(quantity == 1)
                quantity = 1;
            else
                quantity -= 1;
        }
        else if(elem.is('._plus'))
            quantity += 1;

        if(!!this.timeOut)
        {
            clearTimeout(this.timeOut);
        }

        this.timeOut = setTimeout(function(){
            $.post(
                main_ajax_url,
                {
                    'action' : 'BASKETUPDATE',
                    'id': id,
                    'quantity' : quantity
                },
                function(data){
                    if(data.status != 'error'){
                        basketAjax.reloadBasket();
                    }
                });
        }, 500);
    },
    addProducts: function(elem){
        var id = elem.attr('data-id');
        var quantity = elem.attr('data-quantity');
        var parent = elem.attr('data-parent');
        var custom_price = elem.attr('data-custom_price');

        $.post(
            main_ajax_url,
            {
                'action' : 'BASKETADD',
                'id': id,
                'quantity' : quantity,
                'parent' : parent,
                'custom_price':custom_price
            },
            function(data){
                if(data.status != 'error')
                    basketAjax.reloadBasket();
            });
    },
    delProducts: function(elem){
        var id = elem.attr('data-id');
        var pid = elem.attr('data-pid');
        var parent = elem.attr('data-parent');

        $.post(
            main_ajax_url,
            {
                'action' : 'BASKETDEL',
                'id': id,
                'pid': pid,
                'parent' : parent
            },
            function(data){
                if(data.status != 'error'){
                    basketAjax.reloadBasket();
                    reloadSmallBasket('TEMPLATE_LK');
                }
            });
    },
    paymentChange : function(elem){
        var pay_id = elem.val();

        if(!!this.timeOut)
        {
            clearTimeout(this.timeOut);
        }

        this.timeOut  =setTimeout(function(){
            $.post(main_ajax_url,
                {
                    'action' : 'BASKET_PAY_SYSTEM',
                    'pay_id' : pay_id
                }, function(data){
                    basketAjax.reloadBasket();
                })
        },500);
    },
    deliveryChange : function(elem){
        var delivery_name = elem.attr('data-name');
        if(!!this.timeOut)
        {
            clearTimeout(this.timeOut);
        }
        this.timeOut = setTimeout(function(){
            $.post(main_ajax_url,
                {
                    'action' : 'BASKET_DELIVERY',
                    'delivery_name' : delivery_name
                }, function(data){
                    basketAjax.reloadBasket();
                })
        },500);
    },
    changeUser:function (elem) {
        initStyledSelect();

        var user_id = elem.val();

        $.post(main_ajax_url,
            {
                'action' : 'BASKET_USER_ELEM',
                'user_id' : user_id
            }, function(data){
                basketAjax.reloadBasket();
            })

    },
    fastorder :  function(){
        var fastorder = $('form[name="ORDER_FORM"]').find('input[id="fastorder_input"]').val();
        if(fastorder === 'Y'){
            $('input[name="ORDER_PROP_1"]').val($('input[name="FAST_ORDER_PROP_1"]').val());
            $('input[name="ORDER_PROP_2"]').val($('input[name="FAST_ORDER_PROP_2"]').val());
            $('input[name="ORDER_PROP_3"]').val('fastorder@vamopt.ru');
        }
    },
    takeInput: function(){
        var data={};
        var inputs = $('form[name="ORDER_FORM"]').find('input');
        for($i=0;$i<inputs.length;$i++){
            if(inputs[$i].name.indexOf('ORDER_PROP_')>=0){
                data[inputs[$i].name] = inputs[$i].value;
            }
        }
        return  data;
    },
    submit : function(elem){

        var $form = elem.parents('form');
        var url_post = $form.attr('action');

        this.fastorder();

        var data = $form.serialize();

        $('.ordering-errors').empty();

        $.post(url_post, data, function (data) {
            console.log(data);
            if(data.order.ERROR !== "undefined" && data.order.ERROR !== undefined){
                if(data.order.ERROR.PROPERTY !== "undefined"  && data.order.ERROR.PROPERTY !== undefined){
                    var prop_errors = data.order.ERROR.PROPERTY;
                    for($i=0;$i<prop_errors.length;$i++){
                        if(prop_errors[$i] !== undefined){
                            $('.ordering-errors').append('<div>'+prop_errors[$i]+'</div>');
                        }
                    }
                }
            }
            if(data.order.ID > 0){
                window.location.href = data.order.REDIRECT_URL;
            }
        })
    },
    reloadBasket: function(){
        clearTimeout(this.timeOut);
        var url = this.page;
        var container = this.contaner;

        if( url.indexOf("/lk/orders/") > 0 ||
            url.indexOf("/basket/") > 0){
            var data ={};
            data.props = this.takeInput();
            data.basket_ajax_call = 'Y';
            data.fastorder = $(container).find('input[id="fastorder_input"]').val();

            $.post(url,data,function (data) {
                $(container).replaceWith(data);
                initStyledSelect();
                showBasketDopProductInfo();
                initPhoneMask();
                initBasketItemDopProductsSlider();
            });
        }

        reloadSmallBasket($('.top-small-basket').attr('data-template'), this.small_list_status);

    }
}