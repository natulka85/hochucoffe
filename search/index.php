<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
global $APPLICATION;
$APPLICATION->SetTitle("");
$APPLICATION->SetPageProperty("description", "");
global $BP_TEMPLATE, $APPLICATION;
\Bitrix\Main\Page\Asset::getInstance()->addCss("/local/templates/hochucoffe/static/dist/css/search-page.css");

?>
<div class="search-page inner">
    <?$APPLICATION->IncludeComponent("bitrix:breadcrumb","simple",Array(
            "START_FROM" => "0",
            "PATH" => "",
            "SITE_ID" => "s1"
        )
    );?>
    <div class="page-block-head"><h1 class="page-title _type-1">Поиск</h1></div>

    <?if($_REQUEST["catalog_ajax_call"] == "Y") {

        ob_start();
    }
    ?>

    <div class="js-ajax-content">

<?$arElements = $APPLICATION->IncludeComponent(
    "bitrix:search.page",
    ".default",
    Array(
        "RESTART" => "Y",
        "NO_WORD_LOGIC" => "Y",
        "CHECK_DATES" => "Y",
        "arrFILTER" => array("iblock_".$BP_TEMPLATE->getConstants()->IBLOCK_MAIN_IB_TYPE),
        "arrFILTER_iblock_".$BP_TEMPLATE->getConstants()->IBLOCK_MAIN_IB_TYPE => array($BP_TEMPLATE->getConstants()->IBLOCK_MAIN_ID),
        "USE_TITLE_RANK" => "N",
        "DEFAULT_SORT" => "rank",
        "DEFAULT_SORT_DIRECTION" => array(
                'rank_sort' => array("CUSTOM_RANK"=>"ASC", "RANK"=>"DESC", "DATE_CHANGE"=>"DESC")
        ),
        "FILTER_NAME" => "",
        "SHOW_WHERE" => "N",
        "arrWHERE" => [
            '=MODULE_ID' => 'iblock',
            'PARAM1' => 'catalog',
            //'PARAM2' => [2],
        ],
        "SHOW_WHEN" => "N",
        "PAGE_RESULT_COUNT" => 500,
        "DISPLAY_TOP_PAGER" => "N",
        "DISPLAY_BOTTOM_PAGER" => "N",
        "PAGER_TITLE" => "",
        "PAGER_SHOW_ALWAYS" => "N",
        "PAGER_TEMPLATE" => "N",
        "USE_LANGUAGE_GUESS" => "N",

    ),
    false
);
?>
            <div class="page-title _type-2">Вы искали: <?=$_REQUEST['q']?></div>
        <?if(!empty($arElements)):?>
            <div class="search-page__count">По Вашему запросу найдено <span><?=count($arElements)?></span> шт</div>
        <?endif;?>


<?
$phrase = $_REQUEST['q'];?>

<?if (!empty($arElements) && is_array($arElements))
{?>

   <?
    $basket_url = '/personal/basket/';
    global $searchFilter;
    $searchFilter = array(
        "=ID" => $arElements,
    );
    $arParams['CATEGORY_TYPE'] = 'ALL_CAT';
    $arParams["PAGE_ELEMENT_COUNT"] = $BP_TEMPLATE->Catalog()->arCount;
    $arSort = $BP_TEMPLATE->Catalog()->getCurSort($_REQUEST['sort'],$BP_TEMPLATE->getConstants()->IBLOCK_MAIN,$arParams['CATEGORY_TYPE'], $BP_TEMPLATE->getConstants()->IBLOCK_MAIN_TYPE);
    //pre($arParams['CATEGORY_TYPE']);
    //pre($arSort);
    $arParams["ELEMENT_SORT_FIELD"] = $arSort['prop'];
    $arParams["ELEMENT_SORT_ORDER"] = $arSort['order'];
    $arParams["ELEMENT_SORT_CODE"] = $arSort['code'];

    $APPLICATION->IncludeComponent(
    "mango:element.list",
    '',
    array(
        "IBLOCK_TYPE" => $BP_TEMPLATE->getConstants()->IBLOCK_MAIN_IB_TYPE,
        "IBLOCK_ID" => $BP_TEMPLATE->getConstants()->IBLOCK_MAIN_ID,
        "COUNT_ON_PAGE" => $arParams["PAGE_ELEMENT_COUNT"],
        "CACHE_TIME"  =>  3600,

        "SECTION_ID" => "",
        "FILTER_NAME" => "searchFilter",
        "SORT_FIELD" => $arParams["ELEMENT_SORT_FIELD"],
        "SORT_ORDER" => $arParams["ELEMENT_SORT_ORDER"],

        'COUNT_LIST' => $BP_TEMPLATE->Catalog()->arCount,
        'SORT_LIST' => $BP_TEMPLATE->Catalog()->getSortList($BP_TEMPLATE->getConstants()->IBLOCK_MAIN_ID,$arParams['CATEGORY_TYPE'],$arCurSection['ID']),
        'SORT_CODE' => $arParams["ELEMENT_SORT_CODE"],

        "DISPLAY_TOP_PAGER" => "Y",
        "DISPLAY_BOTTOM_PAGER" => "Y",
        "PAGER_TEMPLATE" => "",
    ),
    false
);


}?>

<?
if(empty($arElements)){
echo '<div class="search-page__result-count">К сожалению, по вашему запросу «'.$_REQUEST['q'].'» ничего не найдено</div>';
    //include(__DIR__.'/search_empty.php');
}
?>
    </div>
    <?
    if ($_REQUEST["catalog_ajax_call"] == "Y")
    {
        $strAjaxItems = ob_get_flush();
        $APPLICATION->RestartBuffer();
        echo \Bitrix\Main\Web\Json::encode([
            'items'=>$strAjaxItems,
        ]);
        die();
    }?>
</div>
<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");?>
