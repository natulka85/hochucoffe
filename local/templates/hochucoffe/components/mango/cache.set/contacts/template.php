<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);
?>
<div class="page-block-head"><h1 class="page-title _type-1">Контакты</h1></div>
<div class="contacts__card">
    <div class="contacts__visit is-face">
        <div class="contacts__visit-logo"><img src="<?=SITE_TEMPLATE_PATH?>/static/dist/images/logo_bw.svg"></div>
        <div class="contacts__info">
            <div class="contacts__visit-left">Адрес: г.Москва, ул. Ленина, 36<br>
                E-mail: mail@profacademia.ru<br>
                Время работы:<br>
                пн-пт: 9:00 - 20:00;<br>сб, вс: выходные<br></div>
            <div class="contacts__visit-right">ОГРН 1057746698833<br>
                ИНН 7701593249<br>
                Тел.: 8 800 551-18-95<br>(круглосуточно)<br>
                По РФ звонок бесплатный<strong>ООО “МногоЧтоХочу”</strong></div>
        </div>
    </div>
    <div class="contacts__visit">
        <div class="contacts__visit-title icon-1p_penc">Напишите нам</div>
        <form class="contacts__form">
            <input type="hidden" name="action" value="feedback_contacts">
            <div class="contacts__form-fields">
                <div class="contacts__form-field">
                    <input class="contacts__form-input" type="text"
                           name="name" placeholder="Ваше имя">
                    <div class="error">Некорректное имя</div>
                </div>
                <div class="contacts__form-field"><input class="contacts__form-input" type="text"
                                                         name="phone" placeholder="Ваше телефон">
                    <div class="error">Некорректный телефон</div>
                </div>
                <div class="contacts__form-field"><input class="contacts__form-input" type="text"
                                                         name="email" placeholder="Ваше email">
                    <div class="error">Некорректный email</div>
                </div>
                <div class="contacts__form-btn-wrap">
                    <button class="contacts__form-btn btn is-green-s" type="submit" value="send">Отправить</button>
                    <button class="contacts__form-btn btn is-white" type="submit" value="dir">Сразу директору</button>
                </div>
            </div>
        </form>
    </div>
</div>
<div class="contacts__text">
    <div>Вы можете связаться с нами любым удобным для Вас способом:</div>
    <div class="contacts__soc-block"><a class="contacts__soc-el icon-1s_twit"></a><a
            class="contacts__soc-el icon-1u_mes"></a><a class="contacts__soc-el icon-1v_wats"></a></div>
</div>
<div class="contacts__text">
    <div>Вступайте в наши группы, чтобы быть в курсе всех акций и предложений:</div>
    <div class="contacts__soc-block"><a class="contacts__soc-el icon-1w_inst"></a><a
            class="contacts__soc-el icon-1x_vk"></a><a class="contacts__soc-el icon-1y_fb"></a></div>
</div>
<?/*<div class="page-title _type-2">Как к нам проехать</div>
<div class="contacts__map">
    <script type="text/javascript" charset="utf-8" async
            src="https://api-maps.yandex.ru/services/constructor/1.0/js/?um=constructor%3A3210b768e5aea11a273226434aee8b4dd9705ab9199c039d800935ca9463e228&amp;width=100%;height=576&amp;lang=ru_RU&amp;scroll=true"></script>
</div>*/?>

