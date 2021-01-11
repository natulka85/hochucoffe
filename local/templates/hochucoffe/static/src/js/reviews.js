$(function () {
    var $container = $(".mansonry__content");
    $container.imagesLoaded(function () {
        $container.masonry({
            columnWidth: $container.innerWidth / 4,
            itemSelector: ".reviews__item"
        });
    });
});
