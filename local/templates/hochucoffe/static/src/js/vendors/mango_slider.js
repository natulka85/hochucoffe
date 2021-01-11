var mango_inic = 0;
(function( $ ) {
    $.fn.mangoSlider = function() {
        var this_ps = this;

        var options = {
            class: '.' + this_ps.attr('class'),
            initializ: 'initialized',
            targetControl: '.js-slide-ix-control-block',
        }

        if(options.class !== 'undefined' || options.class !== undefined )
            options.clearClass  = options.class.replace(/\s/g,'.');

        var methods = {
            dataset: function(object){
                //console.log(object.length, object.parents('.js-slide-ix-control-block').outerWidth(),'rr');
                object.each(function(index, value){
                    //$(this).attr('left',$(this).position().left);
                    var left = object.parents('.js-slide-ix-control-block').outerWidth() / object.length * index;
                    $(this).attr('left',left);
                    //devLog(left);
                })
            },
            reinit: function(){

            },
            moveSliders: function(e, index){
                //devLog(index,'index');
                var k = 1;
                if($('html').css('zoom')){
                    k = $('html').css('zoom')
                }
              var current_pos = e.offsetX * (1/k) ;
              var control_list = $(e.currentTarget).children();
              var list = $(e.currentTarget).siblings('.js-slide-ix-target');
              var index = index;

              if(index === 'undefined' || index == undefined){

                  control_list.each(function(){
                      if(current_pos > ($(this).attr('left'))){
                          index = $(this).data('index');
                          return index;
                      }
                  })
              }
              //console.log(index,'index');
                list.children().removeClass('is-active').addClass('is-hide');
                list.children().eq(index).removeClass('is-hide').addClass('is-active');
                control_list.removeClass('is-active');
                control_list.eq(index).addClass('is-active');
            },
        }

        this_ps.each(function(){
            if(!$(this).hasClass(options.initializ)){
                var list = $(this).find('.js-slide-ix-target');
                var controls = $(this).find('.js-slide-ix-control-block');

                if(list.find('img').length > 1){
                    methods.dataset($(controls).children());
                    $(this).addClass(options.initializ);
                }
            }
        });

     /*   $.reinit = function(){
            console.log('reinit');
            this_ps.each(function(){
                if($(this).hasClass(options.initializ)){
                    var list = $(this).find('.js-slide-ix-target');
                    var controls = $(this).find('.js-slide-ix-control-block');

                    if(list.find('img').length > 1){
                        methods.dataset($(controls).children());
                    }
                }
            });
        }*/
        //devLog(mango_inic,'mango_inic');

        if(mango_inic<=0){
            $(document).on('mousemove', '.js-slide-ix-block' + '.'+ options.initializ + ' ' + options.targetControl, function(e){
                //devLog('move1');
                e.preventDefault();
                e.stopPropagation();
                methods.moveSliders(e);
            });

            $(document).on('mouseleave', '.js-slide-ix-block' + '.'+ options.initializ + ' ' + options.targetControl, function(e){
                //devLog('move2');
                //console.log('leave');
                methods.moveSliders(e, 0);
            })

            $(document).on('mouseenter', '.js-slide-ix-block' + '.'+ options.initializ + ' ' + options.targetControl + ' .js-slide-ix-control', function(e){
                e.preventDefault();
                e.stopPropagation();
                //devLog('good');
                //devLog('move3');
                //methods.moveSliders(e, e.target.getAttribute('data-index'));
            });

            $(document).on('mouseleave', '.js-slide-ix-block' + '.'+ options.initializ + ' ' + options.targetControl + ' .js-slide-ix-control', function(e){
                //devLog('move4');
                //methods.moveSliders(e, 0);
            })
            $(document).on('ajaxComplete', function(e){
                //$(this).text('Событие ' + e.type);
                //devLog('test');
                $(options.clearClass + ':not(.'+options.initializ+')').mangoSlider();
            })
            let event = null;
            var $dif = null;
            $(document).on('touchstart', '.js-slide-ix-block' + '.'+ options.initializ + ' ' + options.targetControl, function(e){
                event = e;
            });
            $(document).on('touchmove', '.js-slide-ix-block' + '.'+ options.initializ + ' ' + options.targetControl, function(e){
                e.preventDefault();
                //e.stopPropagation();
                $dif = parseInt(e.changedTouches[0].clientX - event.changedTouches[0].clientX);
                //$('.page-block-head').text(e.changedTouches[0].clientX+'---'+event.changedTouches[0].clientX+'---|'+$dif);
                $('.swiper-container').stop();
            });
            $(document).on('touchend', '.js-slide-ix-block' + '.'+ options.initializ + ' ' + options.targetControl, function(e){
                //e.preventDefault();
                if($dif){
                    var last_index = $(this).find('.js-slide-ix-control').length - 1;
                    var cur_index = parseInt($(this).find('.js-slide-ix-control.is-active').attr('data-index'));
                    var index = 0;
                    if($dif>=15){
                        index = cur_index-1;
                        if(index<0){
                            index = 0;
                        }
                        methods.moveSliders(e, index);
                    }
                    else{
                        index = cur_index+1;
                        if(index>last_index){
                            index = last_index;
                        }
                        methods.moveSliders(e, index);
                    }
                }
                event = $dif = null;
                //alert(index);
            });
        }
        mango_inic++;
    };
})(jQuery);


