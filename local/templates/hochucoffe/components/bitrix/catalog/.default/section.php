<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
use Bitrix\Sale;
use Bitrix\Catalog\CatalogViewedProductTable;
use Bitrix\Main\Loader;
use Bitrix\Main\ModuleManager;
global $BP_TEMPLATE,$APPLICATION;
$url =$_SERVER['REQUEST_URI'];

if($BP_TEMPLATE->Catalog()->is404($url))
{
    CHTTP::SetStatus("404 Not Found");
} else {
    CHTTP::SetStatus("200 OK");
}

//echo CHTTP::GetLastStatus();
//die();



//костыль для категорий

if (0 < intval($arResult["VARIABLES"]["SECTION_ID"]))
    $arFilter["ID"] = $arResult["VARIABLES"]["SECTION_ID"];
elseif ('' != $arResult["VARIABLES"]["SECTION_CODE"])
    $arFilter["=CODE"] = $arResult["VARIABLES"]["SECTION_CODE"];


$obCache = new CPHPCache();
if ($obCache->InitCache(36000, serialize($arFilter), "/iblock/catalog"))
{
    $arCurSection = $obCache->GetVars();
}
elseif ($obCache->StartDataCache())
{
    $arCurSection = array();
    if (Loader::includeModule("iblock"))
    {
        $dbRes = CIBlockSection::GetList(array(), $arFilter, false, array("ID",'DEPTH_LEVEL','CODE','NAME'));

        if(defined("BX_COMP_MANAGED_CACHE"))
        {
            global $CACHE_MANAGER;
            $CACHE_MANAGER->StartTagCache("/iblock/catalog");

            if ($arCurSection = $dbRes->Fetch())
                $CACHE_MANAGER->RegisterTag("iblock_id_".$arParams["IBLOCK_ID"]);

            $CACHE_MANAGER->EndTagCache();
        }
        else
        {
            if(!$arCurSection = $dbRes->Fetch())
                $arCurSection = array();
        }
    }
    $obCache->EndDataCache($arCurSection);
}
if (!isset($arCurSection))
    $arCurSection = array();


?>
<div class="menu-catalog__wrap">
    <div class="menu-catalog">
        <div class="menu-catalog__list">
            <div class="menu-catalog__item"><a class="menu-catalog__link js-link is-sort-link" href="#"><span
                            class="icon-2l_sort">Сортировка</span></a></div>
            <div class="menu-catalog__item"><a class="menu-catalog__link js-link is-filter" href="#"><span
                            class="icon-2k_filter">Фильтр</span></a></div>
        </div>
    </div>
</div>

<?$APPLICATION->IncludeComponent("bitrix:breadcrumb","simple",Array(
            "START_FROM" => "0",
            "PATH" => "",
            "SITE_ID" => "s1",
        )
    );?>


<div class="page-block-head"><?=$APPLICATION->ShowProperty('h1');?>
    <span class="page-title-note">товаров <?=$APPLICATION->ShowProperty('NavRecordCount')?></span>
</div>
<div class="catalog__text">
    <div class="page-text is-col-2">
        <?
        if($_REQUEST['PAGEN_1']<=1) {
            $APPLICATION->ShowProperty("text");
        }
        ?>
    </div>
</div>
<?if($_REQUEST["catalog_ajax_call"] == "Y") {

    ob_start();
}?>
<div class="js-ajax-top"><?
$FilterProps = $BP_TEMPLATE->SeoSection()->convertUrlToCheck($url);
if(count($FilterProps['PROPS'])==1 && count($FilterProps['PROPS']['SPOSOB_PRIGOTOVLENIYA'])==1){
    ?> <section class="pomol"><?
    $APPLICATION->IncludeComponent(
        "mango:cache.set",
        "sposobi_zav",
        [
            "IBLOCK_TYPE" => 'content',
            "IBLOCK_ID" => 8,
            "ID_PROP" => $FilterProps['PROPS']['SPOSOB_PRIGOTOVLENIYA'][0],
        ],
        false
    );?></section><?
}
else{
    $APPLICATION->IncludeComponent(
        "mango:cache.set",
        "cloud-section",
        [
            'SECTION_ID' => $arCurSection['ID'],
            'SECTION_CODE' => $arResult['VARIABLES']['SECTION_CODE']
        ],
        false
    );
}
?>
</div>
<?if ($_REQUEST["catalog_ajax_call"] == "Y")
{
    $strAjaxCloud = ob_get_clean();
    $APPLICATION->RestartBuffer();
}
?>
<div class="catalog__main-wrap fw">
    <div class="sidebar">
        <?if($_REQUEST["catalog_ajax_call"] == "Y") {

            ob_start();
        }
        ?>
        <div class="js-ajax-filter">
        <?
        $APPLICATION->IncludeComponent(
            "mango:catalog.smart.filter",
            "",
            array(
                "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
                "IBLOCK_ID" => $arParams["IBLOCK_ID"],
                "SECTION_ID" => $arCurSection['ID'],
                "FILTER_NAME" => $arParams["FILTER_NAME"],
                //"PRICE_CODE" => $arParams["PRICE_CODE"],
                "CACHE_TYPE" => $arParams["CACHE_TYPE"],
                "CACHE_TIME" => $arParams["CACHE_TIME"],
                "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
                "SAVE_IN_SESSION" => "N",
                "FILTER_VIEW_MODE" => $arParams["FILTER_VIEW_MODE"],
                "XML_EXPORT" => "Y",
                "SECTION_TITLE" => "NAME",
                "SECTION_DESCRIPTION" => "DESCRIPTION",
                'HIDE_NOT_AVAILABLE' => $arParams["HIDE_NOT_AVAILABLE"],
                "TEMPLATE_THEME" => $arParams["TEMPLATE_THEME"],
                'CONVERT_CURRENCY' => $arParams['CONVERT_CURRENCY'],
                'CURRENCY_ID' => $arParams['CURRENCY_ID'],
                "SEF_MODE" => $arParams["SEF_MODE"],
                "SEF_RULE" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["smart_filter"],
                "SMART_FILTER_PATH" => $arResult["VARIABLES"]["SMART_FILTER_PATH"],
                "PAGER_PARAMS_NAME" => $arParams["PAGER_PARAMS_NAME"],
                "INSTANT_RELOAD" => $arParams["INSTANT_RELOAD"],
            ),
            $component
        );?>
        </div>
        <?if ($_REQUEST["catalog_ajax_call"] == "Y")
        {
            $strAjaxFilter = ob_get_clean();
            $APPLICATION->RestartBuffer();
        }
        ?>
    </div>
    <div class="main-content">
        <?if($_REQUEST["catalog_ajax_call"] == "Y") {

            ob_start();
        }
        ?>

        <div class="js-ajax-content">

            <?
            //global $arrFilter;
            $arParams["PAGE_ELEMENT_COUNT"] = $BP_TEMPLATE->Catalog()->arCount;
            //sort
            $arSort = $BP_TEMPLATE->Catalog()->getCurSort($_REQUEST['sort'],$arParams["IBLOCK_ID"],$arParams['CATEGORY_TYPE'], $arCurSection['ID']);

            //pre($arParams['CATEGORY_TYPE']);
            //pre($arSort);
            $arParams["ELEMENT_SORT_FIELD"] = $arSort['prop'];
            $arParams["ELEMENT_SORT_ORDER"] = $arSort['order'];
            $arParams["ELEMENT_SORT_CODE"] = $arSort['code'];

            $arParams["PAGE_ELEMENT_COUNT"] = $BP_TEMPLATE->Catalog()->arCount;
            $arSettings = [
                "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
                "IBLOCK_ID" => $arParams["IBLOCK_ID"],
                "COUNT_ON_PAGE" => $arParams["PAGE_ELEMENT_COUNT"],
                "CACHE_TIME"  =>  $arParams["CACHE_TIME"],

                "SECTION_ID" => $arCurSection['ID'],
                "FILTER_NAME" => $arParams["FILTER_NAME"],
                "SORT_FIELD" => $arParams["ELEMENT_SORT_FIELD"],
                "SORT_ORDER" => $arParams["ELEMENT_SORT_ORDER"],

                "DISPLAY_TOP_PAGER" => "Y",
                "DISPLAY_BOTTOM_PAGER" => "Y",
                "PAGER_TEMPLATE" => $arParams["PAGER_TEMPLATE"],

                'COUNT_LIST' => $BP_TEMPLATE->Catalog()->arCount,
                'SORT_LIST' => $BP_TEMPLATE->Catalog()->getSortList($arParams["IBLOCK_ID"],$arParams['CATEGORY_TYPE'],$arCurSection['ID']),
                'SORT_CODE' => $arParams["ELEMENT_SORT_CODE"],
                'MOD_PAGE_TYPE' => $arResult['MAIN_SECTION_TYPE'],
                'MOD_SECTION_TYPE' => $arResult['SECTION_TYPE'],
                'MOD_AR_SECTION' => $arResult['SECTION_BY_TYPE'],
                'CATEGORY_TYPE' => $arParams['CATEGORY_TYPE'],
                'MOD_SECTION_INPUT' => $arResult['SECTION_INPUT'],
                'data_from' => 'catalog',
                'EVENTS' => [
                    'v_korzinu' => 'ym(71202064,\'reachGoal\',\'click_v_korziny_katalog\')'
                ]
            ];

            $APPLICATION->IncludeComponent(
                "mango:element.list",
                "",
                $arSettings,
                false
            );
            ?>
            <?
            if($_REQUEST['PAGEN_1']<=1){
                $text = $BP_TEMPLATE->SeoSection()->getDetailText($arCurSection['ID'], $arResult["VARIABLES"]["SECTION_CODE"],$arParams['CATEGORY_TYPE'],$arCurSection['NAME']);
            }
            ?>
        </div>
        <?
        if ($_REQUEST["catalog_ajax_call"] == "Y")
        {
            $strAjaxItems = ob_get_clean();
            $APPLICATION->RestartBuffer();
        }

        ?>
    </div>
    <div style="clear: both">
        <? include ($_SERVER['DOCUMENT_ROOT'].'/local/templates/hochucoffe/includes/you_viewed.php');?>
        <div class="catalog__text-box"></div>
    </div>
</div>



<?
$BP_TEMPLATE->SeoSection()->getRobots();

$h1 = $BP_TEMPLATE->SeoSection()->getH1($arCurSection['ID'],$arParams['CATEGORY_TYPE']);
$title = $BP_TEMPLATE->SeoSection()->getTitle($arCurSection['ID'],$arParams['CATEGORY_TYPE']);
$desc = $BP_TEMPLATE->SeoSection()->getDesc($arCurSection['ID'],$arParams['CATEGORY_TYPE']);

if($APPLICATION->GetPageProperty("withoutmeta")!='Y')
{
    $APPLICATION->SetPageProperty("title", $title);
    $APPLICATION->SetPageProperty("description", $desc);
    if($h1!=''){
        $APPLICATION->SetPageProperty("h1", '<h1 class="page-title _type-1">'.$h1.'</h1>');
    }
    $APPLICATION->SetPageProperty("text", $text);
}
$strAjaxBreadcrumbs = $APPLICATION->GetNavChain(array('s1', false), 0, '/local/templates/hochucoffe/components/bitrix/breadcrumb/simple/template.php', true, false);

if ($_REQUEST["catalog_ajax_call"] == "Y")
{
    $APPLICATION->RestartBuffer();
    echo \Bitrix\Main\Web\Json::encode([
        'filter'=>$strAjaxFilter,
        'items'=>$strAjaxItems,
        'breadcrumbs' =>$strAjaxBreadcrumbs,
        'h1' =>$h1,
        'nav_cnt' => 'товаров '.$APPLICATION->GetPageProperty("NavRecordCount"),
        'title' =>$title,
        'description' =>$desc,
        'text' =>$text,
        'cloud' =>$strAjaxCloud

    ]);
    die();
}

?>

<style>
    @media screen and (max-width: 640px){
        .menu-catalog{
            display: block;
        }
    }
</style>
