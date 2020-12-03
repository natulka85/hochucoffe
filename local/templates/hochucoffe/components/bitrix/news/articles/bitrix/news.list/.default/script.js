var window_width = window.innerWidth;
$(function () {
    moveBlocks();
})
BX.addCustomEvent("onFrameDataReceived", function(json) {
    moveBlocks();
})
$(window).on('resize', function () {
    if(window.innerWidth !== window_width){
        moveBlocks();
    }
})

function moveBlocks(){
    var size = window.innerWidth;

    ///1298
    if(size <= 1298){
        $('.list-block.is-article').insertAfter($('.articles .highest-new').eq(1)).addClass('is-moved');
    }
    if(size > 1298){
        $('.list-block.is-article.is-moved').insertAfter($('.articles .highest-new').eq(2)).removeClass('is-moved');
    }
}