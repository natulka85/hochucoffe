<?php
global $BP_TEMPLATE;
$out = '<div class="popup__box is-callback">
<div class="popup__close icon-2a_plus"></div>
<div class="popup__content">
    <div class="popup__text _type-3"><strong>Обратный звонок</strong></div>
    <div class="popup__text _type-2">Оставьте номер телефона, <br>и мы свяжемся с Вами в ближайшее время.</div>
<form class="form__form">
            <input type="hidden" name="action" value="form_callback_send">
            <div class="form__form-fields">
               <div class="form__form-field input-block">
                    <input class="form__input is-border-bottom" type="text" name="phone">
                    <span class="form__placeholder placeholder">Телефон</span>
                    <div class="border-bottom is-green"></div>
                    <div class="error">Некорректный телефон</div>
                </div>
                <div class="form__form-btn-wrap">
                    <button class="form__btn btn is-center is-green-s" type="submit" value="send">Отправить</button>
                </div>
            </div>
        </form>
        </div></div>';

$json['func'] = '$(".popup").html(`'.$out.'`);
                        showPopup($(".popup"),{cssAuto:"true"});';
$json['error'] = 'ok';

