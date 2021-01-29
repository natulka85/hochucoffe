<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
global $BP_TEMPLATE;
$this->setFrameMode(true);?>

<?
if ($_REQUEST["catalog_ajax_call"] == "Y") {
    ob_get_clean();
    ob_start();
}
//sort
if(!$_REQUEST['sort'])
    $_REQUEST['sort'] = 'data_max';
$arSort = $BP_TEMPLATE->Catalog()->getCurSort($_REQUEST['sort'],$arParams["IBLOCK_ID"],$arParams['CATEGORY_TYPE'],'');
$arParams["ELEMENT_SORT_FIELD"] = $arSort['prop'];
$arParams["ELEMENT_SORT_ORDER"] = $arSort['order'];
$arParams["ELEMENT_SORT_CODE"] = $arSort['code'];
?><div class="js-ajax-content articles">
<?$APPLICATION->IncludeComponent(
	"bitrix:news.list",
	"",
	Array(
		"IBLOCK_TYPE"	=>	$arParams["IBLOCK_TYPE"],
		"IBLOCK_ID"	=>	$arParams["IBLOCK_ID"],
		"NEWS_COUNT"    =>   $arParams["NEWS_COUNT"],
		'SORT_LIST' => $BP_TEMPLATE->Catalog()->getSortList($arParams["IBLOCK_ID"],$arParams['CATEGORY_TYPE'],''),
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
		"DISPLAY_TOP_PAGER"	=>	$arParams["DISPLAY_TOP_PAGER"],
		"DISPLAY_BOTTOM_PAGER"	=>	$arParams["DISPLAY_BOTTOM_PAGER"],
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
		"FILTER_NAME"	=>	$arParams["FILTER_NAME"],
		"HIDE_LINK_WHEN_NO_DETAIL"	=>	$arParams["HIDE_LINK_WHEN_NO_DETAIL"],
		"CHECK_DATES"	=>	$arParams["CHECK_DATES"],
		"MOD_TAGS_URL"	=>	$arParams["MOD_TAGS_URL"],
	),
	$component
);?></div><?
if ($_REQUEST["catalog_ajax_call"] == "Y")
{
    $strAjaxItems = ob_get_flush();
    $APPLICATION->RestartBuffer();
}


if ($_REQUEST["catalog_ajax_call"] == "Y")
{
    $APPLICATION->RestartBuffer();
    echo \Bitrix\Main\Web\Json::encode([
        'filter'=>$strAjaxFilter,
        'items'=>$strAjaxItems,
        'title'=>$title,
        'desc'=>$desc,
        'h1'=>$h1,
        'text'=>$text,
        'breadcrumbs' => $breadcrumbs
    ]);
    die();
}
?>
