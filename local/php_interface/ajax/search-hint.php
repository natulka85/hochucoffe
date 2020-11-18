<?require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die(); ?>
<div class="search-hint-ajax">
    <?$APPLICATION->IncludeComponent(
        "mango:search-hints",
        "",
        array(
            'IBLOCK_ID' => 1,
            'CACHE_TIME' => 259200,
            'PICTURE'=> ['width'=>66, 'heigth'=>60],
            'SEARCH_PAGE' => '/search/',
            'REQUEST' => $_REQUEST['q'],
            'REQUEST_SECTION' => $_REQUEST['section'],
            'FIX_HEADER' => $_REQUEST['fixheader']
        ),
        false
    );
    ?>
</div>
