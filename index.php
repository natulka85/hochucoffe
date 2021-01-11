<?
define('FOOTER_TYPE','type-2');
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("");
$APPLICATION->SetPageProperty("description","");
$APPLICATION->SetPageProperty("keywords","");
global $BP_TEMPLATE;
//\Bitrix\Main\Page\Asset::getInstance()->addJs("/local/templates/hochucoffe/static/dist/js/vendors/svgcheckbox.min.js");
\Bitrix\Main\Page\Asset::getInstance()->addJs("/local/templates/hochucoffe/static/dist/js/index.min.js");
\Bitrix\Main\Page\Asset::getInstance()->addCss("/local/templates/hochucoffe/static/dist/css/index.css");
?>
<div class="page__ears is-left"></div>
<div class="page__ears is-right"></div>
</div>
<section class="banner">
    <div class="banner__cont swiper-container">
        <div class="banner__list swiper-wrapper inner">
            <div class="banner__item swiper-slide">
                <a class="banner__link" href="/">
                    <img class="banner__img" src="<?=SITE_TEMPLATE_PATH?>/static/dist/images/banners/banner_dostavka.jpg"/>
                </a>
            </div>
            <div class="banner__item swiper-slide">
                <a class="banner__link">
                    <img class="banner__img" src="<?=SITE_TEMPLATE_PATH?>/static/dist/images/develop/banner_1_4.png"/>
                </a>
            </div>
            <!--<div class="banner__item inner">
            <a class="banner__link">
                <img class="banner__img" src="<?/*=SITE_TEMPLATE_PATH*/?>/static/dist/images/develop/banner_1_3.png"/>
            </a>
        </div>-->
        </div>
        <div class="swiper-pagination swiper__bullet"></div>
        <div class="swiper__btn swiper-button-prev"></div>
        <div class="swiper__btn swiper-button-next"></div>
    </div>
</section>

<section class="sections">
    <div class="sections__cont swiper-container">
        <div class="sections__list swiper-wrapper">
            <div class="sections__item swiper-slide">
                <a class="sections__link" href="/catalog/otsenka-sca_84/otsenka-sca_84plus/otsenka-sca_84-5/otsenka-sca_84-75/otsenka-sca_85/otsenka-sca_85-25/otsenka-sca_87-75/" target="_blank">
                    <div class="sections__content">
                        <div class="sections__img"><img src="<?=SITE_TEMPLATE_PATH?>/static/dist/images/lenta/cofe_sca.jpg" loading="lazy" alt="Кофе с высокой оценкой SCA"></div>
                    </div>
                </a>
            </div>
            <div class="sections__item swiper-slide">
                <a class="sections__link" href="/catalog/product/kofe-braziliya-alta-vista-500g-1104/" target="_blank">
                    <div class="sections__content">
                        <div class="sections__img"><img src="<?=SITE_TEMPLATE_PATH?>/static/dist/images/lenta/coffe_nedeli.jpg" loading="lazy" alt="Кофе недели"></div>
                    </div>
                </a>
            </div>
            <div class="sections__item swiper-slide">
                <a class="sections__link" href="/articles/13/" target="_blank">
                    <div class="sections__content">
                        <div class="sections__img"><img src="<?=SITE_TEMPLATE_PATH?>/static/dist/images/lenta/coffe_statya.jpg" loading="lazy" alt="Статья про кофе 13"></div>
                    </div>
                </a>
            </div>
            <div class="sections__item swiper-slide">
                <a class="sections__link" href="/catalog/akciya-is-true/" target="_blank">
                    <div class="sections__content">
                        <div class="sections__img"><img src="<?=SITE_TEMPLATE_PATH?>/static/dist/images/lenta/coffe_po_akcii.jpg" loading="lazy" alt="Кофе со скидками"></div>
                    </div>
                </a>
            </div>
            <div class="sections__item swiper-slide">
                <a class="sections__link" href="/views/obzor-na-kofe-kolumbiya-la-luna/" target="_blank">
                    <div class="sections__content">
                        <div class="sections__img"><img src="<?=SITE_TEMPLATE_PATH?>/static/dist/images/lenta/coffe_obzor.jpg" loading="lazy" alt="Обзор на кофе"></div>
                    </div>
                </a>
            </div>
    </div>
        <div class="swiper__btn swiper-button-prev"></div>
        <div class="swiper__btn swiper-button-next"></div>
    </div>
</section>
<div class="inner">
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

    <section class="hits sw-global-wr">
        <div class="page-block-head is-center"><h2 class="page-title _type-2">Хиты продаж</h2>
            <a class="page-title-link" href="/catalog/_hit_prodazh-is-true/">Смотреть все</a></div>
        <div class="hits__sw-cont swiper-container">
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
                'MOD_ITEM_CLASS' => 'swiper-slide',
                'MOD_LIST_CLASS' => 'swiper-wrapper'
            ),
            false
        );?>
        <div class="swiper-pagination swiper__bullet"></div>
        </div>
        <div class="swiper__btn swiper-button-prev is-top"></div>
        <div class="swiper__btn swiper-button-next is-top"></div>
    </section>
</div>
<div class="utp">
    <div class="page-block-head is-center"><h2 class="page-title _type-2">Причины сделать заказ у нас:</h2></div>
    <div class="utp__list-wr swiper-container">
        <div class="utp__list swiper-wrapper">
        <div class="utp__item-wrap swiper-slide">
            <div class="utp__item">
                <div class="utp__content">
                    <div class="utp__icon icon-2y_sca"></div>
                    <div class="utp__text">Только кофе высокого качества</div>
                </div>
            </div>
        </div>
        <div class="utp__item-wrap swiper-slide">
            <div class="utp__item">
                <div class="utp__content">
                    <div class="utp__icon icon-2x_obg_1"></div>
                    <div class="utp__text">100% натуральная Арабика</div>
                </div>
            </div>
        </div>
        <div class="utp__item-wrap swiper-slide">
            <div class="utp__item">
                <div class="utp__content">
                    <div class="utp__icon icon-2r_bean"></div>
                    <div class="utp__text">Свежая обжарка на лучшем оборудовании
                    </div>
                </div>
            </div>
        </div>
        <div class="utp__item-wrap swiper-slide">
            <div class="utp__item">
                <div class="utp__content">
                    <div class="utp__icon icon-2s_mel"></div>
                    <div class="utp__text">Выбор любой степени обжарки и помола
                    </div>
                </div>
            </div>
        </div>
        <div class="utp__item-wrap swiper-slide">
            <div class="utp__item">
                <div class="utp__content">
                    <div class="utp__icon icon-2p_car"></div>
                    <div class="utp__text">Быстрая и бережная доставка</div>
                </div>
            </div>
        </div>
        <div class="utp__item-wrap swiper-slide">
            <div class="utp__item">
                <div class="utp__content">
                    <div class="utp__icon icon-2t_surp"></div>
                    <div class="utp__text">Скидки и приятные подарки
                    </div>
                </div>
            </div>
        </div>
        <div class="utp__item-wrap swiper-slide">
            <div class="utp__item">
                <div class="utp__content">
                    <div class="utp__icon icon-2u_ten"></div>
                    <div class="utp__text">Более 10 лет работы с разными сортами кофе
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</div>

<div class="inner">
    <section class="pomol">
        <div class="page-block-head is-center"><h2 class="page-title _type-2">В чем завариваете кофе?</h2></div>
        <div class="pomol__sw-cont swiper-container"><div class="pomol__list swiper-wrapper">
                <div class="pomol__item-wrap swiper-slide">
                    <div class="pomol__item is-franch">
                        <div class="pomol__img">
                            <img src="/local/templates/hochucoffe/static/dist/images/develop/pom_p_1.png" alt="">
                            <img src="/local/templates/hochucoffe/static/dist/images/develop/pom_p_1_c.png" alt="">
                        </div>
                        <div class="pomol__name">Фрэнч-Пресс</div>
                    </div>
                </div>
                <div class="pomol__item-wrap swiper-slide"><div class="pomol__item is-kem">
                        <div class="pomol__img">
                            <img src="/local/templates/hochucoffe/static/dist/images/develop/pom_p_2.png" alt="">
                            <img src="/local/templates/hochucoffe/static/dist/images/develop/pom_p_2_c.png" alt="">
                        </div>
                        <div class="pomol__name">Кемекс</div>
                    </div></div>
                <div class="pomol__item-wrap swiper-slide"><div class="pomol__item is-aero">
                        <div class="pomol__img">
                            <img src="/local/templates/hochucoffe/static/dist/images/develop/pom_p_3.png" alt="">
                            <img src="/local/templates/hochucoffe/static/dist/images/develop/pom_p_3_c.png" alt="">
                        </div>
                        <div class="pomol__name">Аэропресс</div>
                    </div></div>
                <div class="pomol__item-wrap swiper-slide"><div class="pomol__item is-sif">
                        <div class="pomol__img">
                            <img src="/local/templates/hochucoffe/static/dist/images/develop/pom_p_4.png" alt="">
                            <img src="/local/templates/hochucoffe/static/dist/images/develop/pom_p_4_c.png" alt="">
                        </div>
                        <div class="pomol__name">Сифон</div>
                    </div></div>
                <div class="pomol__item-wrap swiper-slide">
                    <div class="pomol__item is-pur">
                        <div class="pomol__img">
                            <img src="/local/templates/hochucoffe/static/dist/images/develop/pom_p_8.png" alt="">
                            <img src="/local/templates/hochucoffe/static/dist/images/develop/pom_p_8_c.png" alt="">
                        </div>
                        <div class="pomol__name">Пуровер</div>
                    </div>
                </div>
                <div class="pomol__item-wrap swiper-slide">
                    <div class="pomol__item is-kofe">
                        <div class="pomol__img">
                            <img src="/local/templates/hochucoffe/static/dist/images/develop/pom_p_6.png" alt="">
                            <img src="/local/templates/hochucoffe/static/dist/images/develop/pom_p_6_c.png" alt="">
                        </div>
                        <div class="pomol__name">Эспрессо</div>
                    </div>
                </div>
                <div class="pomol__item-wrap swiper-slide"><div class="pomol__item is-kapsul">
                        <div class="pomol__img">
                            <img src="/local/templates/hochucoffe/static/dist/images/develop/pom_p_9.png" alt="">
                            <img src="/local/templates/hochucoffe/static/dist/images/develop/pom_p_9_c.png" alt="">
                        </div>
                        <div class="pomol__name">Капсуллы</div>
                    </div></div>
                <div class="pomol__item-wrap swiper-slide"><div class="pomol__item is-turka">
                        <div class="pomol__img">
                            <div class="pomol__img is-turka">
                                <img src="/local/templates/hochucoffe/static/dist/images/develop/pom_p_7.png" alt="">
                                <img src="/local/templates/hochucoffe/static/dist/images/develop/pom_p_7_c.png" alt="">
                            </div>
                        </div>
                        <div class="pomol__name">Турка</div>
                    </div>
                </div>
            </div></div>
        <div class="pomol__note">* В нашем Магазине при заказе зерного кофе Вы можете выбрать любой помол, а
            также степень обжарки !!!
        </div>
    </section>
<section class="sale sw-global-wr">
    <div class="page-block-head is-center"><h2 class="page-title _type-2">Успейте купить кофе со скидкой</h2>
        <a href="/catalog/akciya-is-true/"
                class="page-title-link">Смотреть все</a></div>
    <div class="sale__sw-cont swiper-container">
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
            'MOD_ITEM_CLASS' => 'swiper-slide',
            'MOD_LIST_CLASS' => 'swiper-wrapper'
        ),
        false
    );?>
        <div class="swiper-pagination swiper__bullet"></div>
    </div>
    <div class="swiper__btn swiper-button-prev is-top"></div>
    <div class="swiper__btn swiper-button-next is-top"></div>
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

<section class="reviews swiper-container">
    <div class="page-block-head is-center">
        <h2 class="page-title _type-2">Выбор покупателей</h2><a href="/reviews/" class="page-title-link" target="_blank">Читать все отзывы</a></div>
    <?
    $APPLICATION->IncludeComponent(
        "mango:cache.set",
        "review-index",
        [
            'LIST_CLASS' => 'swiper-wrapper',
            'ITEM_CLASS' => 'swiper-slide',
            'CNT' => 10,
            'TEXT_LENGTH' => 170
        ],
        false
    );?>
    <div class="swiper__btn swiper-button-prev is-top"></div>
    <div class="swiper__btn swiper-button-next is-top"></div>
</section>
    <section class="view">
        <div class="page-block-head is-center"><h2 class="page-title _type-2">Обзоры новинок</h2><a href="/views/" target="_blank"
                    class="page-title-link">Читать все обзоры</a></div>
        <div class="view-sw swiper-container">
        <?
        $APPLICATION->IncludeComponent(
            "mango:cache.set",
            "view-list",
            [
                'IBLOCK_ID' => 7,
                'IBLOCK_TYPE' => 'content',
                'COUNT_ON_PAGE' =>4,
                'LIST_CLASS' => 'swiper-wrapper',
                'ITEM_CLASS' => 'swiper-slide',
                'PIC_ARRAY' => 'Y'
            ],
            false
        );?>
        <div class="swiper__btn swiper-button-prev is-top"></div>
        <div class="swiper__btn swiper-button-next is-top"></div>
        </div>
    </section>
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
    <div class="page-text__wrap" data-show_more="block" data-show_more_height="300">
        <div class="page-text" data-show_more="content">
            <p><strong>Интернет-магазин “Хочу Кофе” предлагает широкий выбор в свежеобжаренного Specialty (Спешелти) кофе с доставкой по Москве и области.</strong></p>
            <p>В ассортименте представлены основные регионы - экспортеры кофейных зерен, включающие Африку, Центральную и Южную Америку, Восточную и Юго-Восточную Азия. В каталоге магазина вы найдете зерновой, молотый и ароматизированный свежеобжаренный кофе, а также капсулы, сорта арабика и различных кофейных смесей. Для искушенных гурманов доступны авторские бленды и специальная обжарка,. В данный момент доступно более 100 сортов кофе.
                <br>
                Мы тщательно отбираем весь кофе, который попадает в наш каталог и один из критериев отбора - высокая оценка SCA. Именно поэтому у нас вы не найдете бодрящего напитка с оценкой менее 80.
            </p>
            <p>Больше, чем просто магазин</p>
            <p>Мы по крупицам собираем интересные и редкие материалы о нашем с вами любимом напитке. Сайт даст ответ на любой вопрос. Интересные факты и статьи про кофе, уникальные справедливые обзоры, рецепты кофейных напитков и отзывы от реальных покупателей, появляются у нас регулярно.</p>
            <p><strong>Почему любители кофе покупают в "Хочу Кофе"</strong></p>
            <ul>
                Клиенты выбирают покупки здесь по следующим причинам:
                <li>наличие экспертной карточки товара;</li>
                <li>правдивые данные собраны профессиональными дегустаторами;</li>
                <li>только свежий кофе;</li>
                <li>некоторые сорта требуют незначительной выдержки, но всегда присутствует свежий урожай;</li>
                <li>обжарка и помол играют важнейшую роль;</li>
                <li>эти параметры, как и купажирование смесей, позволяют производить кастомизацию, раскрывать новые ощущения;</li>
                <li>любители кофе смогут подобрать любые вкусовые ноты.</li>
            </ul>
            <p>Разнообразие формируется из наиболее ярких сортов от известных мировых производителей. Это настоящая кофейная карта мира! Для опытных гурманов имеются эксклюзивные предложения.<br>
                У нас вы можете абсолютно бесплатно заказать помол для любого из способов приготовления, будь то: эспрессо-машина, френч-пресс, кемекс, аэрофильтр, пуровер или классическая джезва (турка). И, если вы предпочитаете капсулы зерновому кофе, то у нас они тоже есть!</p>
            <p>
                <strong>Как сделать заказ</strong><br>
                Все просто!<br>
                Найдите в каталоге понравившийся товар и положите его в корзину <br>
                Укажите свой адрес, выберите способ и время доставки: курьером, доставка в пункт <br>
                Выберите удобный способ оплаты: онлайн оплата на сайте, картой или наличными курьеру <br>
                Наслаждайтесь отличным кофе и не забудьте написать нам лучший отзыв <br>
            </p>
            <p>
                У нас невысокие цены благодаря тому, что мы не работаем с посредниками, а закупаем зерна напрямую у проверенных поставщиков-обжарщиков - настоящих мастеров своего дела!
                Всё богатство вкусов и сортов кофе доступно в для быстрой и бережной доставки по Москве и Московской области.
                Ждем ваших заказов, чтоб вы могли побаловать себя чашечкой свежего ароматного кофе!
            </p>
        </div>
        <div class="page-text__btn-more">
            <span class="js-show-more-btn" data-show_more_btnshow="Подробнее" data-show_more_btnhide="Свернуть">Подробнее</span>
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
