function toHideList(list){
    this.list = list;
    this.hidenElements = this.list.find('.js-more-hidden');
    this.showBy = this.list.attr('data-show-by');
    this.flag = false;
    this.defaultButtonText = list.find('.js-more-btn').text();

    if(this.showBy === 'undefined')
        this.showBy = this.hidenElements.length;

    this.hidenElements.hide().addClass('is-closed');
}
toHideList.prototype.click = function(button){
    this.button = button;

    console.log(this.list);

    if(!this.flag){
        this.list.find('.is-closed').slice(0, this.showBy).slideDown(200).removeClass('is-closed');
    }
    else{
        this.hidenElements.slideUp(200).addClass('is-closed');
        this.button.text(this.defaultButtonText);
        this.flag = false;
    }

    this.closedElements = this.list.find('.is-closed').length;

    if(this.closedElements === 0){
        this.button.text('Скрыть');
        this.flag = true;
    }

};
toHideList.prototype.unbind = function(btn_name){
    this.hidenElements.show().removeClass('is-closed');
    this.list.find('.js-more-btn').text(btn_name);
};
