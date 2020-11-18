var mango_inic = 0;
(function( $ ) {
    $.fn.mangoSlider = function() {
        var this_ps = this;

        var options = {
            class: '.' + this_ps.attr('class'),
            initializ: 'initialized',
            targetControl: '.js-slide-ix-control-block'
        }

        if(options.class !== 'undefined' || options.class !== undefined )
            options.clearClass  = options.class.replace(/\s/g,'.');

        var methods = {
            dataset: function(object){
                //devLog(object.length, object.parents('.js-slide-ix-control-block').outerWidth(),'rr');
                object.each(function(index, value){
                    //$(this).attr('left',$(this).position().left);
                    var left = object.parents('.js-slide-ix-control-block').outerWidth() / object.length * index;
                    $(this).attr('left',left);
                    //devLog(left);
                })
            },
            moveSliders: function(e, index){
                //devLog(index,'index');
              var current_pos = e.offsetX;
              var control_list = $(e.currentTarget).children();
              var list = $(e.currentTarget).siblings('.js-slide-ix-target');
              var index = index;

              if(index === 'undefined' || index == undefined){
                  control_list.each(function(){
                      if(current_pos > $(this).attr('left')){
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
        }
        mango_inic++;
    };
})(jQuery);


