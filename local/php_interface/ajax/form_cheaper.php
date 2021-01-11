<?php
global $BP_TEMPLATE;

$out = '<div class="popup__box is-cheaper">
<div class="popup__close icon-2a_plus"></div>
<div class="popup__content">
    <div class="popup__text _type-3"><strong>Видели этот кофе дешевле?</strong></div>
    <div class="popup__text _type-2">Оставьте ссылку на такой же товар на сайт, <br>где Вы увидели этот кофе дешевле <br>и мы обязательно сделаем Вам <br>предложение лучше!</div>
<form class="form__form">
            <input type="hidden" name="action" value="form_cheaper_send">
            <div class="form__form-fields">
               <div class="form__form-field input-block">
                    <input class="form__input is-border-bottom" type="text" name="phone">
                    <span class="form__placeholder placeholder">Телефон</span>
                    <div class="border-bottom is-green"></div>
                    <div class="error">Некорректный телефон</div>
                </div>
                <div class="form__form-field input-block">
                    <input class="form__input is-border-bottom" type="text" name="email">
                    <span class="form__placeholder placeholder">Email</span>
                    <div class="border-bottom is-green"></div>
                    <div class="error">Некорректный email</div>
                </div>
                <div class="form__form-field input-block">
                    <input class="form__input is-border-bottom" type="text" name="link">
                    <span class="form__placeholder placeholder">Ссылка где видели этот кофе дешевле</span>
                    <div class="border-bottom is-green"></div>
                    <div class="error">Некорректный email</div>
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

