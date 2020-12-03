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
        $(document).on('click','.filter__choose-block',function(){
            $(this).toggleClass('is-closed');
        })
        $('.filter__choose-block').addClass('is-closed');
    }
    if(window.outerWidth > 640){
        StickyMy($('.page'), $('.menu-catalog'),0);
        //StickyMy($('.page'), $('.filter'),0);
    }

})
