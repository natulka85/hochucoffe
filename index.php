<?
define('FOOTER_TYPE','type-1');
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("");
$APPLICATION->SetPageProperty("description","");
$APPLICATION->SetPageProperty("keywords","");
global $BP_TEMPLATE;
\Bitrix\Main\Page\Asset::getInstance()->addJs("/local/templates/hochucoffe/static/js/minify-js/index.min.js");
?>
<div class="page__ears is-left"></div>
<div class="page__ears is-right"></div>
<section class="banner">
    <div class="banner__list">
        <div class="banner__item"><a class="banner__link" href="/akcii/dly-teh-kto-lubit-poslashe/"><img class="banner__img"
                                                               src="<?=SITE_TEMPLATE_PATH?>/static/images/develop/banner_1.png"></a>
        </div>
        <div class="banner__item"><a class="banner__link"><img class="banner__img"
                                                               src="<?=SITE_TEMPLATE_PATH?>/static/images/develop/banner_1.png"></a>
        </div>
        <div class="banner__item"><a class="banner__link"><img class="banner__img"
                                                               src="<?=SITE_TEMPLATE_PATH?>/static/images/develop/banner_1.png"></a>
        </div>
        <div class="banner__item"><a class="banner__link"><img class="banner__img"
                                                               src="<?=SITE_TEMPLATE_PATH?>/static/images/develop/banner_1.png"></a>
        </div>
    </div>
</section>
<section class="sections">
    <div class="sections__list">
        <div class="sections__item is-good-week">
            <a class="sections__link" href="/catalog/product/kofe-braziliya-alta-vista-250-g-1103/" target="_blank">
                <div class="sections__content">
                    <div class="sections__img"><img src="<?=SITE_TEMPLATE_PATH?>/static/images/develop/section_week_cofe.png"></div>
                </div>
            </a>
            <span class="sections__btn is-left">Посмотреть</span>
        </div>
        <div class="sections__item">
            <a class="sections__link">
                <div class="sections__content">
                    <a class="sections__link" href="/catalog/84/84-5/84-75/85/85-25/87-75/" target="_blank">
                        <div class="sections__img"><img src="<?=SITE_TEMPLATE_PATH?>/static/images/develop/section_sca.png"></div>
                    </a>
                </div>
                <span class="sections__btn">Посмотреть</span>
            </a>
        </div>
        <div class="sections__item"><a class="sections__link">
                <div class="sections__content">
                </div>
            </a></div>
        <div class="sections__item"><a class="sections__link">
                <div class="sections__content">
                    <a class="sections__link" href="/catalog/akciya-is-true/" target="_blank">
                        <div class="sections__img"><img src="/local/templates/hochucoffe/static/images/develop/section_sale.png"></div>
                    </a>
                </div>
                <span class="sections__btn">Посмотреть</span>
            </a></div>
        <div class="sections__item"><a class="sections__link">
                <div class="sections__content">
                </div>
            </a></div>
        <div class="sections__item"><a class="sections__link">
                <div class="sections__content">
                </div>
            </a></div>
        <div class="sections__item"><a class="sections__link">
                <div class="sections__content">
                </div>
            </a></div>
        <div class="sections__item"><a class="sections__link">
                <div class="sections__content">
                </div>
            </a></div>
    </div>
</section>
<section class="utp">
    <div class="utp__list">
        <div class="utp__item">
            <div class="utp__img"><img src="<?=SITE_TEMPLATE_PATH?>/static/images/develop/utp_2.png"></div>
        </div>
        <div class="utp__item">
            <div class="utp__img"><img src="<?=SITE_TEMPLATE_PATH?>/static/images/develop/utp_2.png"></div>
        </div>
        <div class="utp__item">
            <div class="utp__text">Только кофе высокого качества</div>
            <div class="utp__img"><img src="<?=SITE_TEMPLATE_PATH?>/static/images/develop/utp_2.png"></div>
        </div>
        <div class="utp__item">
            <div class="utp__text">Только кофе высокого качества</div>
            <div class="utp__img"><img src="<?=SITE_TEMPLATE_PATH?>/static/images/develop/utp_2.png"></div>
        </div>
        <div class="utp__item">
            <div class="utp__text">Только кофе высокого качества</div>
            <div class="utp__img"><img src="<?=SITE_TEMPLATE_PATH?>/static/images/develop/utp_4.png"></div>
        </div>
        <div class="utp__item">
            <div class="utp__text">Только кофе высокого качества</div>
            <div class="utp__img"><img src="<?=SITE_TEMPLATE_PATH?>/static/images/develop/utp_2.png"></div>
        </div>
    </div>
</section>
<section class="hits">
    <div class="page-block-head"><h2 class="page-title _type-2">Хиты продаж</h2>
        <a class="page-title-link" href="/catalog/">Смотреть все</a></div>
    <?
    global  $prodFilter;
    $prodFilter = array(
        "IBLOCK_ID" => $BP_TEMPLATE->getConstants()->IBLOCK_MAIN_ID,
        ">PROPERTY_OSTATOK_POSTAVSHCHIKA" => 0,
    );
    $APPLICATION->IncludeComponent(
        "mango:element.list",
        "",
        array(
            "IBLOCK_TYPE" => $BP_TEMPLATE->getConstants()->IBLOCK_MAIN_TYPE,
            "IBLOCK_ID" => $BP_TEMPLATE->getConstants()->IBLOCK_MAIN_ID,
            "COUNT_ON_PAGE" => 10,
            "CACHE_TIME"  =>  3600,

            "SECTION_ID" => "",
            "FILTER_NAME" => "prodFilter",
            "SORT_FIELD" => "",
            "SORT_ORDER" => "asc",

            "DISPLAY_TOP_PAGER" => "N",
            "DISPLAY_BOTTOM_PAGER" => "N",
            "PAGER_TEMPLATE" => "",
        ),
        false
    );?>
</section>
<section class="pomol">
    <div class="page-block-head"><h2 class="page-title _type-2">Не знаете какой помол выбрать?</h2></div>
    <div class="pomol__image"><img src="<?=SITE_TEMPLATE_PATH?>/static/images/develop/pomol.png"></div>
    <div class="pomol__note">* В нашем Магазине при заказе зерного кофе Вы можете выбрать любой помол, а
        Также степень обжарки !!!
    </div>
</section>
<section class="sale">
    <div class="page-block-head"><h2 class="page-title _type-2">Успейте купить кофе со скидкой</h2>
        <a href="/catalog/akciya-is-true/"
                class="page-title-link">Смотреть все</a></div>
    <?
    global  $prodFilter;
    $prodFilter = array(
        "IBLOCK_ID" => $BP_TEMPLATE->getConstants()->IBLOCK_MAIN_ID,
        ">PROPERTY_OSTATOK_POSTAVSHCHIKA" => 0,
        "=PROPERTY_AKCIYA" => 'true',
    );
    $APPLICATION->IncludeComponent(
        "mango:element.list",
        "",
        array(
            "IBLOCK_TYPE" => $BP_TEMPLATE->getConstants()->IBLOCK_MAIN_TYPE,
            "IBLOCK_ID" => $BP_TEMPLATE->getConstants()->IBLOCK_MAIN_ID,
            "COUNT_ON_PAGE" => 10,
            "CACHE_TIME"  =>  3600,

            "SECTION_ID" => "",
            "FILTER_NAME" => "prodFilter",
            "SORT_FIELD" => "",
            "SORT_ORDER" => "asc",

            "DISPLAY_TOP_PAGER" => "N",
            "DISPLAY_BOTTOM_PAGER" => "N",
            "PAGER_TEMPLATE" => "",
        ),
        false
    );?>
</section>
<?

$APPLICATION->IncludeComponent(
    "mango:cache.set",
    "map-main",
    [
        "IBLOCK_TYPE" => 'content',
        "IBLOCK_ID" => 3,
    ],
    false
);
?>
<?if($_REQUEST["catalog_ajax_call"] == "Y") {
    ob_start();
}
?>
<div class="js-ajax-filter">
    <?
    global $arFilter;
    $arFilter[">PROPERTY_OSTATOK_POSTAVSHCHIKA"] = 0;
    $arFilter[">PROPERTY_OPTIMAL_PRICE"] = 0;
    if($_REQUEST['section_id']!='' && $_REQUEST['section_id']!=$BP_TEMPLATE->getConstants()->IBLOCK_MAIN_TYPE){
        $sefUrl = '/catalog/section/'.$_REQUEST['section_code'].'/filter/#SMART_FILTER_PATH#/';
    }
    else{
        $sefUrl = '/catalog/filter/#SMART_FILTER_PATH#/';
    }

    $APPLICATION->IncludeComponent(
        "mango:catalog.smart.filter",
        "opros",
        array(
            "IBLOCK_TYPE" => $BP_TEMPLATE->getConstants()->IBLOCK_MAIN_IB_TYPE,
            "IBLOCK_ID" => $BP_TEMPLATE->getConstants()->IBLOCK_MAIN_ID,
            "SECTION_ID" => ($_REQUEST['section_id']!='') ? $_REQUEST['section_id'] : $BP_TEMPLATE->getConstants()->IBLOCK_MAIN_TYPE,
            "FILTER_NAME" => 'arFilter',
            //"PRICE_CODE" => $arParams["PRICE_CODE"],
            "CACHE_TYPE" => 'A',
            "CACHE_TIME" => 86400,
            "CACHE_GROUPS" => 'Y',
            "SAVE_IN_SESSION" => "N",
            "FILTER_VIEW_MODE" => '',
            "XML_EXPORT" => "Y",
            "SECTION_TITLE" => "NAME",
            "SECTION_DESCRIPTION" => "DESCRIPTION",
            'HIDE_NOT_AVAILABLE' => 'Y',
            "TEMPLATE_THEME" => '',
            'CONVERT_CURRENCY' => 'Y',
            'CURRENCY_ID' => "RUB",
            "SEF_MODE" => 'Y',
            "SEF_RULE" => $sefUrl,
            //"SMART_FILTER_PATH" => '/catalog/filter/#SMART_FILTER_PATH#/',
            "PAGER_PARAMS_NAME" => '',
            "INSTANT_RELOAD" => '',
            'MOD_404' => 'N'
        ),
        false
    );?>
</div>
<?if ($_REQUEST["catalog_ajax_call"] == "Y")
{
    $strAjaxFilter = ob_get_clean();
    $APPLICATION->RestartBuffer();
}
?>

<section class="reviews">
    <div class="page-block-head">
        <h2 class="page-title _type-2">Выбор покупателей</h2><a
                class="page-title-link">Читать все отзывы</a></div>
    <?
    $APPLICATION->IncludeComponent(
        "mango:cache.set",
        "review-index",
        [
            'CNT' => 10
        ],
        false
    );?>

</section>
<section class="view">
    <div class="page-block-head"><h2 class="page-title _type-2">Обзоры новинок</h2><a
                class="page-title-link">Читать все обзоры</a></div>
    <div class="view__list">
        <div class="view__item">
            <div class="view__content">
                <div class="view__left">
                    <div class="view__image"><a href="#"><img src="<?=SITE_TEMPLATE_PATH?>/static/images/develop/view.png"></a></div>
                </div>
                <div class="view__right"><a class="view__title" href="#">Обзор Итальянского кофи пино
                        Гридже</a>
                    <div class="view__main-desc">
                        <div class="view__desc-name">Страна происхождения:</div>
                        <div class="view__desc-value">Перу</div>
                    </div>
                    <div class="view__main-desc">
                        <div class="view__desc-name">Вкусовые качества:</div>
                        <div class="view__desc-value">Вишня, манго, лимон</div>
                    </div>
                    <div class="view__main-desc">
                        <div class="view__desc-name">Степень обжарки:</div>
                        <div class="view__desc-value">Любая</div>
                    </div>
                    <div class="view__main-desc">
                        <div class="view__desc-name">Цена:</div>
                        <div class="view__desc-value">1000 Р за 1000 г</div>
                    </div>
                    <div class="view__describe">
                        <div class="view__sect">Описание</div>
                        <div class="view__text">Супер-новинка из Перу!<br>Настоящие перуанцы своими
                            перуанскими пальцами собирали этот кофе в условиях жуткой непогоды, рискуя
                            своими жизнями во время того как вулкан выбрасывал свою лаву.
                            Носили мешки в амбар, натирая мозоли на ногах
                        </div>
                    </div>
                    <div class="view__describe">
                        <div class="view__sect">Основные отличия</div>
                        <div class="view__text">Супер-новинка из Перу!Настоящие перуанцы своими перуанскими
                            пальцами собирали этот кофе в условиях жуткой непогоды, рискуя своими жизнями во
                            время того как вулкан выбрасывал свою лаву. Носили мешки в амбар, натирая мозоли
                            на ногах
                        </div>
                    </div>
                    <div class="view__control-links">
                        <div class="view__control-link is-view"><a href="#">Читать весь обзор</a></div>
                        <div class="view__control-link is-good"><a href="#">Перейти к товару</a></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="view__item">
            <div class="view__content">
                <div class="view__left">
                    <div class="view__image"><a href="#"><img src="<?=SITE_TEMPLATE_PATH?>/static/images/develop/view.png"></a></div>
                </div>
                <div class="view__right"><a class="view__title" href="#">Обзор Итальянского кофи пино
                        Гридже</a>
                    <div class="view__main-desc">
                        <div class="view__desc-name">Страна происхождения:</div>
                        <div class="view__desc-value">Перу</div>
                    </div>
                    <div class="view__main-desc">
                        <div class="view__desc-name">Вкусовые качества:</div>
                        <div class="view__desc-value">Вишня, манго, лимон</div>
                    </div>
                    <div class="view__main-desc">
                        <div class="view__desc-name">Степень обжарки:</div>
                        <div class="view__desc-value">Любая</div>
                    </div>
                    <div class="view__main-desc">
                        <div class="view__desc-name">Цена:</div>
                        <div class="view__desc-value">1000 Р за 1000 г</div>
                    </div>
                    <div class="view__describe">
                        <div class="view__sect">Описание</div>
                        <div class="view__text">Супер-новинка из Перу!<br>Настоящие перуанцы своими
                            перуанскими пальцами собирали этот кофе в условиях жуткой непогоды, рискуя
                            своими жизнями во время того как вулкан выбрасывал свою лаву.
                            Носили мешки в амбар, натирая мозоли на ногах
                        </div>
                    </div>
                    <div class="view__describe">
                        <div class="view__sect">Основные отличия</div>
                        <div class="view__text">Супер-новинка из Перу!Настоящие перуанцы своими перуанскими
                            пальцами собирали этот кофе в условиях жуткой непогоды, рискуя своими жизнями во
                            время того как вулкан выбрасывал свою лаву. Носили мешки в амбар, натирая мозоли
                            на ногах
                        </div>
                    </div>
                    <div class="view__control-links">
                        <div class="view__control-link is-view"><a href="#">Читать весь обзор</a></div>
                        <div class="view__control-link is-good"><a href="#">Перейти к товару</a></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="view__item">
            <div class="view__content">
                <div class="view__left">
                    <div class="view__image"><a href="#"><img src="<?=SITE_TEMPLATE_PATH?>/static/images/develop/view.png"></a></div>
                </div>
                <div class="view__right"><a class="view__title" href="#">Обзор Итальянского кофи пино
                        Гридже</a>
                    <div class="view__main-desc">
                        <div class="view__desc-name">Страна происхождения:</div>
                        <div class="view__desc-value">Перу</div>
                    </div>
                    <div class="view__main-desc">
                        <div class="view__desc-name">Вкусовые качества:</div>
                        <div class="view__desc-value">Вишня, манго, лимон</div>
                    </div>
                    <div class="view__main-desc">
                        <div class="view__desc-name">Степень обжарки:</div>
                        <div class="view__desc-value">Любая</div>
                    </div>
                    <div class="view__main-desc">
                        <div class="view__desc-name">Цена:</div>
                        <div class="view__desc-value">1000 Р за 1000 г</div>
                    </div>
                    <div class="view__describe">
                        <div class="view__sect">Описание</div>
                        <div class="view__text">Супер-новинка из Перу!<br>Настоящие перуанцы своими
                            перуанскими пальцами собирали этот кофе в условиях жуткой непогоды, рискуя
                            своими жизнями во время того как вулкан выбрасывал свою лаву.
                            Носили мешки в амбар, натирая мозоли на ногах
                        </div>
                    </div>
                    <div class="view__describe">
                        <div class="view__sect">Основные отличия</div>
                        <div class="view__text">Супер-новинка из Перу!Настоящие перуанцы своими перуанскими
                            пальцами собирали этот кофе в условиях жуткой непогоды, рискуя своими жизнями во
                            время того как вулкан выбрасывал свою лаву. Носили мешки в амбар, натирая мозоли
                            на ногах
                        </div>
                    </div>
                    <div class="view__control-links">
                        <div class="view__control-link is-view"><a href="#">Читать весь обзор</a></div>
                        <div class="view__control-link is-good"><a href="#">Перейти к товару</a></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="view__item">
            <div class="view__content">
                <div class="view__left">
                    <div class="view__image"><a href="#"><img src="<?=SITE_TEMPLATE_PATH?>/static/images/develop/view.png"></a></div>
                </div>
                <div class="view__right"><a class="view__title" href="#">Обзор Итальянского кофи пино
                        Гридже</a>
                    <div class="view__main-desc">
                        <div class="view__desc-name">Страна происхождения:</div>
                        <div class="view__desc-value">Перу</div>
                    </div>
                    <div class="view__main-desc">
                        <div class="view__desc-name">Вкусовые качества:</div>
                        <div class="view__desc-value">Вишня, манго, лимон</div>
                    </div>
                    <div class="view__main-desc">
                        <div class="view__desc-name">Степень обжарки:</div>
                        <div class="view__desc-value">Любая</div>
                    </div>
                    <div class="view__main-desc">
                        <div class="view__desc-name">Цена:</div>
                        <div class="view__desc-value">1000 Р за 1000 г</div>
                    </div>
                    <div class="view__describe">
                        <div class="view__sect">Описание</div>
                        <div class="view__text">Супер-новинка из Перу!<br>Настоящие перуанцы своими
                            перуанскими пальцами собирали этот кофе в условиях жуткой непогоды, рискуя
                            своими жизнями во время того как вулкан выбрасывал свою лаву.
                            Носили мешки в амбар, натирая мозоли на ногах
                        </div>
                    </div>
                    <div class="view__describe">
                        <div class="view__sect">Основные отличия</div>
                        <div class="view__text">Супер-новинка из Перу!Настоящие перуанцы своими перуанскими
                            пальцами собирали этот кофе в условиях жуткой непогоды, рискуя своими жизнями во
                            время того как вулкан выбрасывал свою лаву. Носили мешки в амбар, натирая мозоли
                            на ногах
                        </div>
                    </div>
                    <div class="view__control-links">
                        <div class="view__control-link is-view"><a href="#">Читать весь обзор</a></div>
                        <div class="view__control-link is-good"><a href="#">Перейти к товару</a></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="view__bottom-arrows">
        <span class="slick-prev slick-arrow"></span>
        <span class="slick-next slick-arrow"></span>
    </div>
</section>
</div>
</div>
<div class="subscribe__wrap">
<section class="subscribe inner">

    <div class="subscribe__title icon-1k_aeroplan"><span>Будьте в курсе всех акций<br>и интересных предложений</span>
    </div>
    <form class="subscribe__form">
        <div class="subscribe__fields">
            <input type="hidden" name="action" value="subscribe">
            <div class="subscribe__field">

                <input class="subscribe__input" type="text" name="email" placeholder="Ваш Email"/>
                <div class="error">Введите Email</div>
            </div>
            <div class="subscribe__btn-wrap">
                <button class="subscribe__btn btn is-white">Подписаться</button>
            </div>
        </div>
    </form>
</section>
</div>
<div class="inner">
    <div class="page-text">
        <p>На нашем сайте вы можете выбрать для себя любимые и самые лучшие сорта кофе,  которые будут радовать Вас с каждым новым глотком!<br>
            Для того, чтобы было проще ориентироваться во всем ассортименте нашего интернет-магазина, мы подготовили:
        </p>
        <ul>
            <li>Качественное описание каждого товара;</li>
            <li>Оценки SCA;</li>
            <li>Страну и регион происхождения;</li>
            <li>Год урожая;</li>
            <li>Состав;</li>
            <li>Вкусовые и ароматические оттенки;</li>
            <li>Вес каждой пачки;</li>
        </ul>
        <p>У нас вы можете выбрать необходимую вам степень обжарки, а также помол для вашего способа приготовления совершенно БЕСПЛАТНО!<br>
            Мы работаем напрямую с лучшими обжарщиками кофе, которые имеют все необходимые сертификаты.<br>
            Мы выбираем только надежных и самых быстрых партнеров по доставке, ведь,  чем свежее обжарка, тем насыщеннее вкус.<br>
            Подробнее о способах и сроках доставки вы можете почитать <a href="здесь" target="_blank"></a>.
        </p>
        <p>Менеджеры в нашем Контакт-центре знают о кофе все и с удовольствием помогут Вам определиться с выбором. Позвоните нам:<br>
            8(495)220-20-20 (для Москвы и МО)<br>
            8(800)220-20-20(бесплатно по РФ)<br>
            или просто закажите обратный звонок на сайте, и с Вами свяжутся в кратчайшие сроки.
        </p>
        <p>Кстати, наш контакт-центр работает 24/7</p>
        <p>Почему покупают у нас:</p>
        <ul>
            <li>Выбираем продукцию с высокими оценками SCA от 80+</li>
            <li>100% арабика класса specialty</li>
            <li>Все ТОПовые обжарщики на одном сайте</li>
            <li>Всегда свежая обжарка на оборудовании признанных брендов</li>
            <li>Выбор любой степени обжарки и помола</li>
            <li>Быстрая и бережная доставка</li>
            <li>Скидки и приятные подарки</li>
            <li>Более 10 лет кропотливой работы с разными сортами кофе</li>
        </ul>
        <p>
            Будем рады возможности побаловать Вас свежим и вкусным Кофе!
        </p>
    </div>
    <div class="index-banner-bottom">
        <div class="page-block-head"><h2 class="page-title _type-2">Или все-таки чай? :)</h2></div>
        <div class="index-banner-bottom__img"><a href=""><img src="<?=SITE_TEMPLATE_PATH?>/static/images/develop/banner_bot.png"></a>
        </div>
    </div>
    <?
    global $GLOBAL_CUSTOM;
    if ($_REQUEST["catalog_ajax_call"] == "Y")
    {
        $APPLICATION->RestartBuffer();
        echo \Bitrix\Main\Web\Json::encode([
            'filter'=>$strAjaxFilter,
            'res_filter_url'=>$GLOBAL_CUSTOM['FILTER_URL'],
        ]);
        die();
    }
    ?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
