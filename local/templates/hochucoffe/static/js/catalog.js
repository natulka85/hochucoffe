$(function(){
    $(document).on('click','.js-link.is-filter', function(e){
        e.preventDefault();
        $('.filter').toggle(300);
    })
    $(document).on('click','.js-link.is-sort-link', function(e){
        e.preventDefault();
        $('.catg__list-control').toggle(300);
    })

    if(window.outerWidth <= 640){
        $(document).on('click','.catg__list-control-value', function(){
            $('.catg__list-control').toggle(300);
        })
    }

    Sticky($('.page'), $('.menu-catalog'),0);
})
