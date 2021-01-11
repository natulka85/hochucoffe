<?
if(strpos($_SERVER['REQUEST_URI'], 'product') === false){
    @define("CATALOG_PAGE", "Y");
}
else{
    @define('CONTENT_STYLE','is-product-page');
}
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
global $APPLICATION;
$APPLICATION->SetTitle("Каталог");
$CategoryType = '';
require(__DIR__."/catalog.php");
\Bitrix\Main\Page\Asset::getInstance()->addJs("/local/templates/hochucoffe/static/dist/js/catalog.min.js");
\Bitrix\Main\Page\Asset::getInstance()->addCss("/local/templates/hochucoffe/static/dist/css/catalog.css");
?>
<div class="catalog">
    <div class="page__ears is-left"></div>
    <div class="page__ears is-right"></div>
<?

global $arrFilter,$BP_TEMPLATE;
$arrFilter[">PROPERTY_OSTATOK_POSTAVSHCHIKA"] = 0;
$arrFilter[">PROPERTY_OPTIMAL_PRICE"] = 0;

$APPLICATION->IncludeComponent("bitrix:catalog", "", Array(
    "IBLOCK_TYPE" => $BP_TEMPLATE->getConstants()->IBLOCK_MAIN_TYPE,	// Тип инфоблока
    "IBLOCK_ID" => $BP_TEMPLATE->getConstants()->IBLOCK_MAIN_ID,	// Инфоблок
    "HIDE_NOT_AVAILABLE" => "Y",	// Недоступные товары
    "SECTION_ID_VARIABLE" => "SECTION_ID",	// Название переменной, в которой передается код группы
    "SEF_MODE" => "Y",	// Включить поддержку ЧПУ
    "SEF_FOLDER" => "/catalog/",	// Каталог ЧПУ (относительно корня сайта)
    "AJAX_MODE" => "N",	// Включить режим AJAX
    "AJAX_OPTION_JUMP" => "N",	// Включить прокрутку к началу компонента
    "AJAX_OPTION_STYLE" => "Y",	// Включить подгрузку стилей
    "AJAX_OPTION_HISTORY" => "N",	// Включить эмуляцию навигации браузера
    "CACHE_TYPE" => "A",	// Тип кеширования
    "CACHE_TIME" => "3603",	// Время кеширования (сек.)
    "CACHE_FILTER" => "Y",	// Кешировать при установленном фильтре
    "CACHE_GROUPS" => "Y",	// Учитывать права доступа
    "SET_STATUS_404" => "Y",	// Устанавливать статус 404
    "SET_TITLE" => "Y",	// Устанавливать заголовок страницы
    "ADD_SECTIONS_CHAIN" => "N",	// Включать раздел в цепочку навигации
    "ADD_ELEMENT_CHAIN" => "Y",	// Включать название элемента в цепочку навигации
    "USE_ELEMENT_COUNTER" => "Y",	// Использовать счетчик просмотров
    "USE_FILTER" => "Y",	// Показывать фильтр
    "FILTER_NAME" => "arrFilter",	// Фильтр
    "FILTER_FIELD_CODE" => array(	// Поля
        0 => "",
        1 => "",
    ),
    "FILTER_PROPERTY_CODE" => array(	// Свойства
        0 => "",
        1 => "_STRANA",
        2 => "TSVET_ARMATURY_NOVYY",
        3 => "_TIP_TSOKOLYA_NOVYY",
        4 => "",
    ),
    "FILTER_PRICE_CODE" => array(	// Тип цены
        0 => "rozn",
        1 => "akciya",
    ),
    "FILTER_VIEW_MODE" => "VERTICAL",	// Вид отображения умного фильтра
    "USE_REVIEW" => "Y",
    "MESSAGES_PER_PAGE" => "10",
    "USE_CAPTCHA" => "Y",
    "REVIEW_AJAX_POST" => "Y",
    "PATH_TO_SMILE" => "/bitrix/images/forum/smile/",
    "FORUM_ID" => "",
    "URL_TEMPLATES_READ" => "",
    "SHOW_LINK_TO_FORUM" => "Y",
    "POST_FIRST_MESSAGE" => "N",
    "USE_COMPARE" => "N",	// Разрешить сравнение товаров
    "PRICE_CODE" => array(	// Тип цены
        0 => "rozn",
        1 => "akciya",
    ),
    "USE_PRICE_COUNT" => "N",	// Использовать вывод цен с диапазонами
    "SHOW_PRICE_COUNT" => "1",	// Выводить цены для количества
    "PRICE_VAT_INCLUDE" => "Y",	// Включать НДС в цену
    "PRICE_VAT_SHOW_VALUE" => "N",	// Отображать значение НДС
    "CONVERT_CURRENCY" => "Y",	// Показывать цены в одной валюте
    "CURRENCY_ID" => "RUB",
    "BASKET_URL" => "/personal/cart/",	// URL, ведущий на страницу с корзиной покупателя
    "ACTION_VARIABLE" => "action",	// Название переменной, в которой передается действие
    "PRODUCT_ID_VARIABLE" => "id",	// Название переменной, в которой передается код товара для покупки
    "USE_PRODUCT_QUANTITY" => "Y",	// Разрешить указание количества товара
    "PRODUCT_QUANTITY_VARIABLE" => "quantity",	// Название переменной, в которой передается количество товара
    "ADD_PROPERTIES_TO_BASKET" => "Y",	// Добавлять в корзину свойства товаров и предложений
    "PRODUCT_PROPS_VARIABLE" => "prop",	// Название переменной, в которой передаются характеристики товара
    "PARTIAL_PRODUCT_PROPERTIES" => "N",	// Разрешить добавлять в корзину товары, у которых заполнены не все характеристики
    "PRODUCT_PROPERTIES" => "",	// Характеристики товара, добавляемые в корзину
    "SHOW_TOP_ELEMENTS" => "N",	// Выводить топ элементов
    "SECTION_COUNT_ELEMENTS" => "N",	// Показывать количество элементов в разделе
    "SECTION_TOP_DEPTH" => "1",	// Максимальная отображаемая глубина разделов
    "SECTIONS_VIEW_MODE" => "TEXT",	// Вид списка подразделов
    "SECTIONS_SHOW_PARENT_NAME" => "N",	// Показывать название раздела
    "PAGE_ELEMENT_COUNT" => $BP_TEMPLATE->Catalog()->defaultCount,	// Количество элементов на странице
    "LINE_ELEMENT_COUNT" => "3",	// Количество элементов, выводимых в одной строке таблицы
    "ELEMENT_SORT_FIELD2" => "HAS_PREVIEW_PICTURE",	// Поле для второй сортировки товаров в разделе
    "ELEMENT_SORT_ORDER2" => "desc,nulls",	// Порядок второй сортировки товаров в разделе
    "ELEMENT_SORT_FIELD" => "SORT",	// По какому полю сортируем товары в разделе
    "ELEMENT_SORT_ORDER" => "asc,nulls",	// Порядок сортировки товаров в разделе
    "LIST_PROPERTY_CODE" => array(	// Свойства
    ),
    "INCLUDE_SUBSECTIONS" => "N",	// Показывать элементы подразделов раздела
    "LIST_META_KEYWORDS" => "-",	// Установить ключевые слова страницы из свойства раздела
    "LIST_META_DESCRIPTION" => "-",	// Установить описание страницы из свойства раздела
    "LIST_BROWSER_TITLE" => "-",	// Установить заголовок окна браузера из свойства раздела
    "DETAIL_PROPERTY_CODE" => array(	// Свойства
        0 => "",
        1 => "NEWPRODUCT",
        2 => "MANUFACTURER",
        3 => "MATERIAL",
        4 => "",
    ),
    "DETAIL_META_KEYWORDS" => "-",	// Установить ключевые слова страницы из свойства
    "DETAIL_META_DESCRIPTION" => "-",	// Установить описание страницы из свойства
    "DETAIL_BROWSER_TITLE" => "-",	// Установить заголовок окна браузера из свойства
    "DETAIL_DISPLAY_NAME" => "N",	// Выводить название элемента
    "DETAIL_DETAIL_PICTURE_MODE" => "IMG",	// Режим показа детальной картинки
    "DETAIL_ADD_DETAIL_TO_SLIDER" => "N",	// Добавлять детальную картинку в слайдер
    "DETAIL_DISPLAY_PREVIEW_TEXT_MODE" => "E",	// Показ описания для анонса на детальной странице
    "LINK_IBLOCK_TYPE" => "",	// Тип инфоблока, элементы которого связаны с текущим элементом
    "LINK_IBLOCK_ID" => "",	// ID инфоблока, элементы которого связаны с текущим элементом
    "LINK_PROPERTY_SID" => "",	// Свойство, в котором хранится связь
    "LINK_ELEMENTS_URL" => "link.php?PARENT_ELEMENT_ID=#ELEMENT_ID#",	// URL на страницу, где будет показан список связанных элементов
    "USE_ALSO_BUY" => "Y",	// Показывать блок "С этим товаром покупают"
    "ALSO_BUY_ELEMENT_COUNT" => "4",	// Количество элементов для отображения
    "ALSO_BUY_MIN_BUYES" => "1",	// Минимальное количество покупок товара
    "USE_STORE" => "Y",	// Показывать блок "Количество товара на складе"
    "USE_STORE_PHONE" => "Y",
    "USE_STORE_SCHEDULE" => "Y",
    "USE_MIN_AMOUNT" => "N",	// Показывать вместо точного количества информацию о достаточности
    "STORE_PATH" => "/store/#store_id#",	// Шаблон пути к каталогу STORE (относительно корня)
    "MAIN_TITLE" => "Наличие на складах",	// Заголовок блока
    "PAGER_TEMPLATE" => "",	// Шаблон постраничной навигации
    "DISPLAY_TOP_PAGER" => "Y",	// Выводить над списком
    "DISPLAY_BOTTOM_PAGER" => "Y",	// Выводить под списком
    "PAGER_TITLE" => "Товары",	// Название категорий
    "PAGER_SHOW_ALWAYS" => "N",	// Выводить всегда
    "PAGER_DESC_NUMBERING" => "N",	// Использовать обратную навигацию
    "PAGER_DESC_NUMBERING_CACHE_TIME" => "36013",	// Время кеширования страниц для обратной навигации
    "PAGER_SHOW_ALL" => "N",	// Показывать ссылку "Все"
    "TEMPLATE_THEME" => "site",	// Цветовая тема
    "ADD_PICT_PROP" => "MORE_PHOTO",
    "LABEL_PROP" => "-",
    "SHOW_DISCOUNT_PERCENT" => "N",	// Показывать процент скидки
    "SHOW_OLD_PRICE" => "N",	// Показывать старую цену
    "DETAIL_SHOW_MAX_QUANTITY" => "N",
    "MESS_BTN_BUY" => "Купить",	// Текст кнопки "Купить"
    "MESS_BTN_ADD_TO_BASKET" => "В корзину",	// Текст кнопки "Добавить в корзину"
    "MESS_BTN_COMPARE" => "Сравнение",	// Текст кнопки "Сравнение"
    "MESS_BTN_DETAIL" => "Подробнее",	// Текст кнопки "Подробнее"
    "MESS_NOT_AVAILABLE" => "Нет в наличии",	// Сообщение об отсутствии товара
    "DETAIL_USE_VOTE_RATING" => "Y",	// Включить рейтинг товара
    "DETAIL_VOTE_DISPLAY_AS_RATING" => "rating",	// В качестве рейтинга показывать
    "DETAIL_USE_COMMENTS" => "Y",	// Включить отзывы о товаре
    "DETAIL_BLOG_USE" => "Y",
    "DETAIL_VK_USE" => "N",	// Использовать Вконтакте
    "DETAIL_FB_USE" => "N",	// Использовать Facebook
    "DETAIL_FB_APP_ID" => "",
    "DETAIL_BRAND_USE" => "Y",	// Использовать компонент "Бренды"
    "DETAIL_BRAND_PROP_CODE" => array(	// Таблица с брендами
        0 => "",
        1 => "-",
        2 => "",
    ),
    "ANALOG_PROPERTIES" => array(
        0 => "_MESTO_USTANOVKI",
        1 => "_STIL",
        2 => "TSVET",
        3 => "TSVET_PLAFONOV",
        4 => "TSVET_ARMATURY",
        5 => "_INTERER",
        6 => "_FORMA_PLAFONA",
        7 => "_KOLICHESTVO_LAMP",
        8 => "_VYSOTA_MM",
        9 => "_DIAMETR_MM",
        10 => "_PLOSHCHAD_OSVESHCHENIYA_M2",
        11 => "_NAPRYAZHENIE_V",
    ),
    "AJAX_OPTION_ADDITIONAL" => "",	// Дополнительный идентификатор
    "COMPONENT_TEMPLATE" => "catalog",
    "USE_MAIN_ELEMENT_SECTION" => "N",	// Использовать основной раздел для показа элемента
    "SET_LAST_MODIFIED" => "N",	// Устанавливать в заголовках ответа время модификации страницы
    "DETAIL_SET_CANONICAL_URL" => "N",	// Устанавливать канонический URL
    "DETAIL_CHECK_SECTION_ID_VARIABLE" => "N",	// Использовать код группы из переменной, если не задан раздел элемента
    "SHOW_DEACTIVATED" => "N",	// Показывать деактивированные товары
    "STORES" => array(	// Склады
        0 => "",
        1 => "",
    ),
    "USER_FIELDS" => array(	// Пользовательские поля
        0 => "",
        1 => "",
    ),
    "FIELDS" => array(	// Поля
        0 => "",
        1 => "",
    ),
    "SHOW_EMPTY_STORE" => "Y",	// Отображать склад при отсутствии на нем товара
    "SHOW_GENERAL_STORE_INFORMATION" => "N",	// Показывать общую информацию по складам
    "HIDE_NOT_AVAILABLE_OFFERS" => "N",	// Недоступные торговые предложения
    "COMMON_SHOW_CLOSE_POPUP" => "N",	// Показывать кнопку продолжения покупок во всплывающих окнах
    "SIDEBAR_SECTION_SHOW" => "Y",	// Показывать правый блок в списке товаров
    "SIDEBAR_DETAIL_SHOW" => "Y",	// Показывать правый блок на детальной странице
    "SIDEBAR_PATH" => "",	// Путь к включаемой области для вывода информации в правом блоке
    "DETAIL_STRICT_SECTION_CHECK" => "N",	// Строгая проверка раздела для детального показа элемента
    "USE_SALE_BESTSELLERS" => "Y",	// Показывать список лидеров продаж
    "USE_COMMON_SETTINGS_BASKET_POPUP" => "N",	// Одинаковые настройки показа кнопок добавления в корзину или покупки на всех страницах
    "COMMON_ADD_TO_BASKET_ACTION" => "ADD",	// Показывать кнопку добавления в корзину или покупки
    "TOP_ADD_TO_BASKET_ACTION" => "ADD",	// Показывать кнопку добавления в корзину или покупки на странице с top'ом товаров
    "SECTION_ADD_TO_BASKET_ACTION" => "ADD",	// Показывать кнопку добавления в корзину или покупки на странице списка товаров
    "DETAIL_ADD_TO_BASKET_ACTION" => array(	// Показывать кнопки добавления в корзину и покупки на детальной странице товара
        0 => "BUY",
    ),
    "DETAIL_SHOW_BASIS_PRICE" => "Y",
    "SECTION_BACKGROUND_IMAGE" => "-",	// Установить фоновую картинку для шаблона из свойства
    "DETAIL_BACKGROUND_IMAGE" => "-",	// Установить фоновую картинку для шаблона из свойства
    "USE_GIFTS_DETAIL" => "Y",	// Показывать блок "Подарки" в детальном просмотре
    "USE_GIFTS_SECTION" => "Y",	// Показывать блок "Подарки" в списке
    "USE_GIFTS_MAIN_PR_SECTION_LIST" => "Y",	// Показывать блок "Товары к подарку" в детальном просмотре
    "GIFTS_DETAIL_PAGE_ELEMENT_COUNT" => "4",	// Количество элементов в блоке "Подарки" в строке в детальном просмотре
    "GIFTS_DETAIL_HIDE_BLOCK_TITLE" => "N",	// Скрыть заголовок "Подарки" в детальном просмотре
    "GIFTS_DETAIL_BLOCK_TITLE" => "Выберите один из подарков",	// Текст заголовка "Подарки" в детальном просмотре
    "GIFTS_DETAIL_TEXT_LABEL_GIFT" => "Подарок",	// Текст метки "Подарка" в детальном просмотре
    "GIFTS_SECTION_LIST_PAGE_ELEMENT_COUNT" => "4",	// Количество элементов в блоке "Подарки" строке в списке
    "GIFTS_SECTION_LIST_HIDE_BLOCK_TITLE" => "N",	// Скрыть заголовок "Подарки" в списке
    "GIFTS_SECTION_LIST_BLOCK_TITLE" => "Подарки к товарам этого раздела",	// Текст заголовка "Подарки" в списке
    "GIFTS_SECTION_LIST_TEXT_LABEL_GIFT" => "Подарок",	// Текст метки "Подарка" в списке
    "GIFTS_SHOW_DISCOUNT_PERCENT" => "Y",	// Показывать процент скидки
    "GIFTS_SHOW_OLD_PRICE" => "Y",	// Показывать старую цену
    "GIFTS_SHOW_NAME" => "Y",	// Показывать название
    "GIFTS_SHOW_IMAGE" => "Y",	// Показывать изображение
    "GIFTS_MESS_BTN_BUY" => "Выбрать",	// Текст кнопки "Выбрать"
    "GIFTS_MAIN_PRODUCT_DETAIL_PAGE_ELEMENT_COUNT" => "4",	// Количество элементов в блоке "Товары к подарку" в строке в детальном просмотре
    "GIFTS_MAIN_PRODUCT_DETAIL_HIDE_BLOCK_TITLE" => "N",	// Скрыть заголовок "Товары к подарку" в детальном просмотре
    "GIFTS_MAIN_PRODUCT_DETAIL_BLOCK_TITLE" => "Выберите один из товаров, чтобы получить подарок",	// Текст заголовка "Товары к подарку"
    "USE_BIG_DATA" => "N",	// Показывать персональные рекомендации
    "BIG_DATA_RCM_TYPE" => "bestsell",
    "PAGER_BASE_LINK_ENABLE" => "N",	// Включить обработку ссылок
    "SHOW_404" => "N",	// Показ специальной страницы
    "MESSAGE_404" => "",	// Сообщение для показа (по умолчанию из компонента)
    "COMPATIBLE_MODE" => "Y",	// Включить режим совместимости
    "DISABLE_INIT_JS_IN_COMPONENT" => "N",	// Не подключать js-библиотеки в компоненте
    "DETAIL_SET_VIEWED_IN_COMPONENT" => "N",	// Включить сохранение информации о просмотре товара на детальной странице для старых шаблонов
    "SEF_URL_TEMPLATES" => array(
        "sections" => "",
        "section" => "section/#SECTION_CODE#/",
        "element" => "product/#ELEMENT_CODE#/",
        "compare" => "compare/",
        "smart_filter" => $SmartTemplate,
    ),
    'CATEGORY_TYPE'=>$CategoryType
),
    false
);
?>
</div>

<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>
