<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

//if($arResult["VARIABLES"]["SECTION_CODE"]!='')
//   $APPLICATION->AddHeadString('<meta name="robots" content="noindex, nofollow">');

$this->setFrameMode(true);
global $BP_TEMPLATE;?>
<div class="articles-page-detail">

<?php

$ElementID = $APPLICATION->IncludeComponent(
    "bitrix:news.detail",
    "",
    Array(
        "DISPLAY_DATE" => $arParams["DISPLAY_DATE"],
        "DISPLAY_NAME" => $arParams["DISPLAY_NAME"],
        "DISPLAY_PICTURE" => $arParams["DISPLAY_PICTURE"],
        "DISPLAY_PREVIEW_TEXT" => $arParams["DISPLAY_PREVIEW_TEXT"],
        "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
        "IBLOCK_ID" => $arParams["IBLOCK_ID"],
        "FIELD_CODE" => $arParams["DETAIL_FIELD_CODE"],
        "PROPERTY_CODE" => $arParams["DETAIL_PROPERTY_CODE"],
        "DETAIL_URL"	=>	$arResult["FOLDER"].$arResult["URL_TEMPLATES"]["detail"],
        "SECTION_URL"	=>	$arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
        "META_KEYWORDS" => $arParams["META_KEYWORDS"],
        "META_DESCRIPTION" => $arParams["META_DESCRIPTION"],
        "BROWSER_TITLE" => $arParams["BROWSER_TITLE"],
        "DISPLAY_PANEL" => $arParams["DISPLAY_PANEL"],
        "SET_TITLE" => $arParams["SET_TITLE"],
        "SET_STATUS_404" => $arParams["SET_STATUS_404"],
        "INCLUDE_IBLOCK_INTO_CHAIN" => $arParams["INCLUDE_IBLOCK_INTO_CHAIN"],
        "ADD_SECTIONS_CHAIN" => $arParams["ADD_SECTIONS_CHAIN"],
        'ADD_ELEMENT_CHAIN' => $arParams['ADD_ELEMENT_CHAIN'],
        "ACTIVE_DATE_FORMAT" => $arParams["DETAIL_ACTIVE_DATE_FORMAT"],
        "CACHE_TYPE" => $arParams["CACHE_TYPE"],
        "CACHE_TIME" => $arParams["CACHE_TIME"],
        "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
        "USE_PERMISSIONS" => $arParams["USE_PERMISSIONS"],
        "GROUP_PERMISSIONS" => $arParams["GROUP_PERMISSIONS"],
        "DISPLAY_TOP_PAGER" => 'N',//$arParams["DETAIL_DISPLAY_TOP_PAGER"],
        "DISPLAY_BOTTOM_PAGER" => 'N', //$arParams["DETAIL_DISPLAY_BOTTOM_PAGER"],
        "PAGER_TITLE" => 'N', // $arParams["DETAIL_PAGER_TITLE"],
        "PAGER_SHOW_ALWAYS" => "N",
        "PAGER_TEMPLATE" => '', // $arParams["DETAIL_PAGER_TEMPLATE"],
        "PAGER_SHOW_ALL" => 'N', // $arParams["DETAIL_PAGER_SHOW_ALL"],
        "CHECK_DATES" => $arParams["CHECK_DATES"],
        "ELEMENT_ID" => $arResult["VARIABLES"]["ELEMENT_ID"],
        "ELEMENT_CODE" => $arResult["VARIABLES"]["ELEMENT_CODE"],
        "IBLOCK_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["news"],
        "USE_SHARE" 			=> $arParams["USE_SHARE"],
        "SHARE_HIDE" 			=> $arParams["SHARE_HIDE"],
        "SHARE_TEMPLATE" 		=> $arParams["SHARE_TEMPLATE"],
        "SHARE_HANDLERS" 		=> $arParams["SHARE_HANDLERS"],
        "SHARE_SHORTEN_URL_LOGIN"	=> $arParams["SHARE_SHORTEN_URL_LOGIN"],
        "SHARE_SHORTEN_URL_KEY" => $arParams["SHARE_SHORTEN_URL_KEY"],
        "CAT_SECTION_CODE" => $arResult["VARIABLES"]["SECTION_CODE"],

        "SORT_BY1"    =>    $arParams["SORT_BY1"],
        "SORT_ORDER1"    =>    $arParams["SORT_ORDER1"],
        "SORT_BY2"    =>    $arParams["SORT_BY2"],
        "SORT_ORDER2"    =>    $arParams["SORT_ORDER2"],
    ),
    $component
);
?>
    <div class="articles-detail__more-art">
        <div class="page-block-head is-center"><h2 class="page-title _type-2">Читайте также</h2>
            <a href="/articles/"
               class="page-title-link">Смотреть все</a></div>
<?
global $artFilter;
$artFilter['!=ID'] = $ElementID;
$APPLICATION->IncludeComponent(
    "bitrix:news.list",
    "",
    Array(
        "IBLOCK_TYPE"	=>	$arParams["IBLOCK_TYPE"],
        "IBLOCK_ID"	=>	$arParams["IBLOCK_ID"],
        "NEWS_COUNT"    =>   6,
        'SORT_LIST' => '',
        "SORT_BY1"	=>	$arParams["ELEMENT_SORT_FIELD"],
        "SORT_ORDER1"	=>	$arParams["ELEMENT_SORT_ORDER"],
        "SORT_BY2"	=>	$arParams["ELEMENT_SORT_CODE"],
        "SORT_ORDER2"	=>	$arParams["ELEMENT_SORT_ORDER"],
        'SORT_CODE' => $arParams["ELEMENT_SORT_CODE"],
        "FIELD_CODE"	=>	$arParams["LIST_FIELD_CODE"],
        "PROPERTY_CODE"	=>	$arParams["LIST_PROPERTY_CODE"],
        "DETAIL_URL"	=>	$arResult["FOLDER"].$arResult["URL_TEMPLATES"]["detail"],
        "SECTION_URL"	=>	$arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
        "IBLOCK_URL"	=>	$arResult["FOLDER"].$arResult["URL_TEMPLATES"]["news"],
        "DISPLAY_PANEL"	=>	$arParams["DISPLAY_PANEL"],
        "SET_TITLE"	=>	$arParams["SET_TITLE"],
        "SET_STATUS_404" => $arParams["SET_STATUS_404"],
        "INCLUDE_IBLOCK_INTO_CHAIN"	=>	$arParams["INCLUDE_IBLOCK_INTO_CHAIN"],
        "CACHE_TYPE"	=>	$arParams["CACHE_TYPE"],
        "CACHE_TIME"	=>	$arParams["CACHE_TIME"],
        "CACHE_FILTER"	=>	$arParams["CACHE_FILTER"],
        "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
        "DISPLAY_TOP_PAGER"	=>	'N',
        "DISPLAY_BOTTOM_PAGER"	=>	'N',
        "PAGER_TITLE"	=>	$arParams["PAGER_TITLE"],
        "PAGER_TEMPLATE"	=>	$arParams["PAGER_TEMPLATE"],
        "PAGER_SHOW_ALWAYS"	=>	$arParams["PAGER_SHOW_ALWAYS"],
        "PAGER_DESC_NUMBERING"	=>	$arParams["PAGER_DESC_NUMBERING"],
        "PAGER_DESC_NUMBERING_CACHE_TIME"	=>	$arParams["PAGER_DESC_NUMBERING_CACHE_TIME"],
        "PAGER_SHOW_ALL" => $arParams["PAGER_SHOW_ALL"],
        "DISPLAY_DATE"	=>	$arParams["DISPLAY_DATE"],
        "DISPLAY_NAME"	=>	"Y",
        "DISPLAY_PICTURE"	=>	$arParams["DISPLAY_PICTURE"],
        "DISPLAY_PREVIEW_TEXT"	=>	$arParams["DISPLAY_PREVIEW_TEXT"],
        "PREVIEW_TRUNCATE_LEN"	=>	$arParams["PREVIEW_TRUNCATE_LEN"],
        "ACTIVE_DATE_FORMAT"	=>	$arParams["LIST_ACTIVE_DATE_FORMAT"],
        "USE_PERMISSIONS"	=>	$arParams["USE_PERMISSIONS"],
        "GROUP_PERMISSIONS"	=>	$arParams["GROUP_PERMISSIONS"],
        "FILTER_NAME"	=>	'artFilter',
        "HIDE_LINK_WHEN_NO_DETAIL"	=>	$arParams["HIDE_LINK_WHEN_NO_DETAIL"],
        "CHECK_DATES"	=>	$arParams["CHECK_DATES"],
    ),
    $component
);?>
    </div>

</div>
