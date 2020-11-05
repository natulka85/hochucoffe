/*$('#drop a').click(function(){
       // Simulate a click on the file input button
       // to show the file browser dialog
       $(this).parent().find('input').click();
   });*/
$(function(){

    // Initialize the jQuery File Upload plugin
    $('.js-upload').fileupload({
        maxChunkSize: 10000000,
        url: '/local/php_interface/ajax/upload_selection.php',
        // This element will accept file drag/drop uploading
        dropZone: $(this).find('.js-upload-drop'),
        dataType: 'json',

        // This function is called when a file is added to the queue
        // either via the browse button, or via drag/drop:
        add: function (e, data) {
            // Выводим сообщение о допустимых типах файлов
            console.log(data);

            data.context = $(this).find('.js-file-box');

            // Automatically upload the file once it is added to the queue
            var jqXHR = data.submit();
        },
        progress: function (e, data) {
            // Calculate the completion percentage of the upload
            var progress = parseInt(data.loaded / data.total * 100, 10);

            // Update the hidden input field and trigger a change
            // so that the jQuery knob plugin knows to update the dial
            //data.context.find('input').val(progress).change();

            if (progress == 100) {
                data.context.removeClass('working');
            }
        },

        fail: function (e, data) {
            // Something has gone wrong!
            data.context.addClass('error');
            console.log('error');
            console.log(data);
        },
        done: function (e, data) {
            console.log('done');
            //console.log(data.result,'datarrr',data.result.files.type,data.result.files);
            //console.log(data.result);

            if(data.result.message === 'no_valid'){

                var formatMessage = '<div class="popup__box is-selection-alert"><div class="popup__title"><span class="popup-title-text">Подбор по фото</span></div><div class="js-popup-close-only-this close-popup-btn"></div><div class="popup__subtile">Разрешены следующие расширения файлов: jpg, gif, bmp, png, jpeg, heic. Для изображений других форматов, напишите нам письмо на email: <a href="mailto:info@vamsvet.ru?subject=Подбор по фото">info@vamsvet.ru</a> с темой: «Подбор по фото»</div><div class="popup__btn-wrapp"><div class="popup__btn btn_c is-green js-popup-close-only-this">Закрыть</div></div></div>';

                $("body").prepend(formatMessage);
                //$(".popup__box.selection-popup").remove();
                $(".popup__box.selection-alert").remove();
                showPopup($(".popup__box.is-selection-alert"));
                return;

                //alert("Можно загрузить только файлы с расширениями: .png, .jpg, .jpeg"); return;
            }
            else if(data.result.status === 'success'){
                //console.log(data.result.files,data.result.files.name);
                var size_format = '';
                var file_name = data.result.files.size + '_' + data.result.files.name;

                if (data.result.files.size >= 1000000)
                    size_format = Math.round((data.result.files.size / 1000000), 1) + ' M';
                else
                    size_format = Math.round((data.result.files.size / 1000), 1) + ' Кб';

                var tpl = $('<div class="file-inputed__item"><input type="hidden" value="' + file_name + '" name="filenm[]"><div class="file-inputed__name">' + data.files[0]['name'] + ' - ' + size_format + '</div><div class="file-inputed__close icon-1x_close"></div></div>');

                // Append the file name and file size
                //tpl.find('input').val(data.files[0]['size']+'_'+data.files[0]['name']);

                // Add the HTML to the UL element
                data.context = tpl.appendTo($(this).find('.js-file-box'));

                // Initialize the knob plugin
                //tpl.find('input').knob();
                // Listen for clicks on the cancel icon
                tpl.find('.file-inputed__close').click(function () {

                    if (tpl.hasClass('working')) {
                        jqXHR.abort();
                    }

                    tpl.fadeOut(function () {
                        tpl.remove();
                    });

                });

                $('<input type="hidden" value="'+ data.result.path +'" name="fpath">').appendTo(data.form);
            }
        },
    })



// Prevent the default action when a file is dropped on the window
$(document).on('drop dragover', function (e) {
    e.preventDefault();
});
//remove
/*$(document).on('.loaded-plan__remove','click', function (e) {
    e.target.parents('.loaded-plan').remove();
});*/
});

// Helper function that formats the file sizes
function formatFileSize(bytes) {
    if (typeof bytes !== 'number') {
        return '';
    }

    if (bytes >= 1000000000) {
        return (bytes / 1000000000).toFixed(2) + ' GB';
    }

    if (bytes >= 1000000) {
        return (bytes / 1000000).toFixed(2) + ' MB';
    }

    return (bytes / 1000).toFixed(2) + ' KB';
}

