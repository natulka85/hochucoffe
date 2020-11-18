$(function(){
    var timeOut = false;
    $(document).on('change','.count-block__value', function(){
        if(timeOut)
        {
            clearTimeout(timeOut);
        }

        var data = $(this).data();
        data.q = $(this).val();

        console.log(data,'data');

        timeOut = setTimeout(function(){
            $.post(
                main_ajax,
                data,
                function(data){
                    responseJson(data);
                });
        }, 500);
    })
})
