<?
global $BP_TEMPLATE;
?>
</div> <?//inner?>
</div> <?//page?>
<div class="footer <?=$footer_class?>">
    <div class="footer__content inner">
        <div class="footer__promo">
            <div class="footer__logo"><img src="/local/templates/hochucoffe/static/dist/images/svg/logo.svg" alt="Интернет-магазин зернового кофе"></div>
            <div class="soc-col">
                <div class="soc-col__items">
                    <a href="/" class="soc-col__item icon-3o_fb"></a>
                    <a href="/" class="soc-col__item icon-3p_ok"></a>
                    <a href="/" class="soc-col__item icon-3r_vk"></a>
                    <a href="/" class="soc-col__item icon-3s_in"></a>
                </div>
            </div>
            <div class="f-subscribe">
                <div class="f-subscribe__title">Подписаться на рассылку</div>
                <form action="" class="f-subscribe__form js-form">
                    <input type="hidden" name="action" value="subscribe">
                    <div class="f-subscribe__field">
                        <input type="text" class="f-subscribe__input" placeholder="Ваш Email" name="email">
                        <div class="error">Введите Email</div>
                    </div>
                    <button class="f-subscribe__btn icon-1k_aeroplan"></button>
                    <div class="cons">
                        <label class="">
                            <input type="checkbox" checked="checked" class="main-checkbox__checkbox" >
                            <div class="main-checkbox__span">Я&nbsp;принимаю условия <a href="/politika-konfidencialnosti/" target="_blank">&laquo;Политики <br>конфиденциальности&raquo;</a></div>
                        </label>
                    </div>


                </form>
            </div>
        </div>
        <?$APPLICATION->IncludeComponent("bitrix:menu","footer",
            Array(
                "ROOT_MENU_TYPE" => "top",
                "MAX_LEVEL" => "3",
                "CHILD_MENU_TYPE" => "",
                "USE_EXT" => "Y",
                "DELAY" => "N",
                "ALLOW_MULTI_SELECT" => "Y",
                "MENU_CACHE_TYPE" => "N",
                "MENU_CACHE_TIME" => "3600",
                "MENU_CACHE_USE_GROUPS" => "Y",
                "MENU_CACHE_GET_VARS" => ""
            )
        );?>

        <div class="footer__contact">
            <div class="footer__contact-l">
                <div class="f-label">
                    <div class="f-label__title"><span>Колл-центр</span></div>
                    <div class="f-label__info"><span>ежедневно<br>9:00 - 22:00</span></div>
                </div>
                <div class="f-label">
                    <div class="f-label__title"><span>Доставка</span></div>
                    <div class="f-label__info"><span>ежедневно<br>9:00 - 22:00</span></div>
                </div>
            </div>
            <div class="footer__contact-r">
                <div class="footer__phone icon-3t_phone"><a href="tel:<?=$BP_TEMPLATE->getConstants()->PHONE_RUS?>"><?=$BP_TEMPLATE->getConstants()->PHONE_RUS?></a></div>
                <div class="footer__email icon-3u_mail"><a href="mailto:info@hochucoffe.ru">info@hochucoffe.ru</a></div>
                <div class="footer__address icon-3v_point"><span><?=$BP_TEMPLATE->getConstants()->UR_ADDRESS?></span></div>
                <div class="footer__address"><span>ИП Тамара Н.А.</span></div>
            </div>
        </div>
        <div class="rights">
            <div class="company">Создание сайта IT-Mango</div>
            <div class="copyright">© 2020-<?=date('Y')?>. Все права защищены.</div>
        </div>
    </div>
</div>
<div class="popup" id="popup"></div>
<div class="popup-dop"></div>
<div class="form-dark"></div>
</div>
</div>
</body>
<?//include_once ($_SERVER["DOCUMENT_ROOT"].'/includes/counters/clever.php');?>
</html>
