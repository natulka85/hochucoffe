<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Интересные статьи о кофе");
?>

<?$APPLICATION->IncludeComponent("bitrix:news", "articles", Array(
    "IBLOCK_TYPE" => "content",	// Тип инфоблока
    "IBLOCK_ID" => "4",	// Инфоблок
    "NEWS_COUNT" => "15",	// Количество новостей на странице
    "USE_SEARCH" => "N",	// Разрешить поиск
    "USE_RSS" => "N",	// Разрешить RSS
    "USE_RATING" => "N",	// Разрешить голосование
    "USE_CATEGORIES" => "N",	// Выводить материалы по теме
    "USE_REVIEW" => "N",	// Разрешить отзывы
    "USE_FILTER" => "N",	// Показывать фильтр
    "SORT_BY2" => "SORT",	// Поле для первой сортировки новостей
    "SORT_ORDER2" => "ASC",	// Направление для первой сортировки новостей
    "SORT_BY1" => "ACTIVE_FROM",	// Поле для второй сортировки новостей
    "SORT_ORDER1" => "DESC",	// Направление для второй сортировки новостей
    "CHECK_DATES" => "Y",	// Показывать только активные на данный момент элементы
    "SEF_MODE" => "Y",	// Включить поддержку ЧПУ
    "SEF_FOLDER" => "/articles/",	// Каталог ЧПУ (относительно корня сайта)
    "AJAX_MODE" => "N",	// Включить режим AJAX
    "AJAX_OPTION_JUMP" => "N",	// Включить прокрутку к началу компонента
    "AJAX_OPTION_STYLE" => "Y",	// Включить подгрузку стилей
    "AJAX_OPTION_HISTORY" => "N",	// Включить эмуляцию навигации браузера
    "CACHE_TYPE" => "N",	// Тип кеширования
    "CACHE_TIME" => "3600",	// Время кеширования (сек.)
    "CACHE_FILTER" => "N",	// Кешировать при установленном фильтре
    "CACHE_GROUPS" => "Y",	// Учитывать права доступа
    "SET_TITLE" => "N",	// Устанавливать заголовок страницы
    "SET_STATUS_404" => "Y",	// Устанавливать статус 404, если не найдены элемент или раздел
    "INCLUDE_IBLOCK_INTO_CHAIN" => "N",	// Включать инфоблок в цепочку навигации
    "ADD_SECTIONS_CHAIN" => "N",	// Включать раздел в цепочку навигации
    'ADD_ELEMENT_CHAIN' => 'Y',
    "USE_PERMISSIONS" => "N",	// Использовать дополнительное ограничение доступа
    "PREVIEW_TRUNCATE_LEN" => "400",	// Максимальная длина анонса для вывода (только для типа текст)
    "LIST_ACTIVE_DATE_FORMAT" => "j M Y",	// Формат показа даты
    "LIST_FIELD_CODE" => array(	// Поля
        0 => "DETAIL_PICTURE",
        1 => "DATE_ACTIVE_FROM",
        2 => "DATE_ACTIVE_TO",
        4 => "SHOW_COUNTER",
    ),
    "LIST_PROPERTY_CODE" => array(	// Свойства
        0 => "ARTICLES_SECTION",
        1 => "",
    ),
    "HIDE_LINK_WHEN_NO_DETAIL" => "N",	// Скрывать ссылку, если нет детального описания
    "DISPLAY_NAME" => "Y",	// Выводить название элемента
    "META_KEYWORDS" => "-",	// Установить ключевые слова страницы из свойства
    "META_DESCRIPTION" => "-",	// Установить описание страницы из свойства
    "BROWSER_TITLE" => "-",	// Установить заголовок окна браузера из свойства
    "DETAIL_ACTIVE_DATE_FORMAT" => "j M Y",	// Формат показа даты
    "DETAIL_FIELD_CODE" => array(	// Поля
        2 => "",
        0 => "DATE_ACTIVE_FROM",
        1 => "DATE_ACTIVE_TO",
        3 => "PREVIEW_PICTURE",
        4 => "SHOW_COUNTER",
    ),
    "DETAIL_PROPERTY_CODE" => array(	// Свойства
        0 => "TOVAR",
        1 => "BTN_NAME",
        2 => "BTN_LINK",
        3 =>'ARTICLES_SECTION'
    ),
    "DETAIL_DISPLAY_TOP_PAGER" => "N",	// Выводить над списком
    "DETAIL_DISPLAY_BOTTOM_PAGER" => "Y",	// Выводить под списком
    "DETAIL_PAGER_TITLE" => "",	// Название категорий
    "DETAIL_PAGER_TEMPLATE" => "",	// Название шаблона
    "DETAIL_PAGER_SHOW_ALL" => "Y",	// Показывать ссылку "Все"
    "DISPLAY_TOP_PAGER" => "N",	// Выводить над списком
    "DISPLAY_BOTTOM_PAGER" => "Y",	// Выводить под списком
    "PAGER_TITLE" => "Новости",	// Название категорий
    "PAGER_SHOW_ALWAYS" => "N",	// Выводить всегда
    "PAGER_TEMPLATE" => "",	// Шаблон постраничной навигации
    "PAGER_DESC_NUMBERING" => "N",	// Использовать обратную навигацию
    "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",	// Время кеширования страниц для обратной навигации
    "PAGER_SHOW_ALL" => "Y",	// Показывать ссылку "Все"
    "DISPLAY_DATE" => "Y",	// Выводить дату элемента
    "DISPLAY_PICTURE" => "Y",	// Выводить изображение для анонса
    "DISPLAY_PREVIEW_TEXT" => "Y",	// Выводить текст анонса
    "USE_SHARE" => "N",	// Отображать панель соц. закладок
    "AJAX_OPTION_ADDITIONAL" => "",	// Дополнительный идентификатор
    'MOD_TAGS_URL' => '/articles/sort_#NAME_TRANS#/',
    'CATEGORY_TYPE' =>'ARTICLES_LIST',
    "SEF_URL_TEMPLATES" => array(
        "news" => "",
        "section" => "#ELEMENT_CODE#/#SECTION_CODE#/",
        "detail" => "#ELEMENT_CODE#/",
    ),
),
    false,
    array(
        "ACTIVE_COMPONENT" => "Y"
    )
);?>

<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>