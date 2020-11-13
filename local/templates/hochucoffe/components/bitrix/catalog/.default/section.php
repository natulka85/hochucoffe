<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
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
//костыль для категорий

$section_code = 'tipy';
$arFilter["=CODE"] = 'tipy';


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
<?$APPLICATION->IncludeComponent("bitrix:breadcrumb","simple",Array(
            "START_FROM" => "0",
            "PATH" => "",
            "SITE_ID" => "s1",
        )
    );?>

<div class="page-block-head"><h1 class="page-title _type-1">Каталог</h1>
    <span class="page-title-note">100 товаров</span>
</div>
<section class="cloud">
                    <div class="cloud__list">
                        <div class="cloud__item is-active"><a class="cloud__link" href="#">Камерун<span
                                class="cloud__num">50</span></a></div>
                        <div class="cloud__item"><a class="cloud__link" href="#">Сильная обжарка<span
                                class="cloud__num">50</span></a></div>
                        <div class="cloud__item"><a class="cloud__link" href="#">Средня обжарка<span class="cloud__num">50</span></a>
                        </div>
                        <div class="cloud__item"><a class="cloud__link" href="#">Молотый<span
                                class="cloud__num">50</span></a></div>
                        <div class="cloud__item"><a class="cloud__link" href="#">до 1000 ₽<span
                                class="cloud__num">50</span></a></div>
                        <div class="cloud__item"><a class="cloud__link" href="#">Дорогой кофе<span
                                class="cloud__num">50</span></a></div>
                        <div class="cloud__item is-active"><a class="cloud__link" href="#">Камерун<span
                                class="cloud__num">50</span></a></div>
                        <div class="cloud__item"><a class="cloud__link" href="#">Сильная обжарка<span
                                class="cloud__num">50</span></a></div>
                        <div class="cloud__item"><a class="cloud__link" href="#">Средня обжарка<span class="cloud__num">50</span></a>
                        </div>
                        <div class="cloud__item"><a class="cloud__link" href="#">Молотый<span
                                class="cloud__num">50</span></a></div>
                        <div class="cloud__item"><a class="cloud__link" href="#">до 1000 ₽<span
                                class="cloud__num">50</span></a></div>
                        <div class="cloud__item"><a class="cloud__link" href="#">Дорогой кофе<span
                                class="cloud__num">50</span></a></div>
                    </div>
                </section>
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
            $arSettings = [
                "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
                "IBLOCK_ID" => $arParams["IBLOCK_ID"],
                "COUNT_ON_PAGE" => $arParams["PAGE_ELEMENT_COUNT"],
                "CACHE_TIME"  =>  $arParams["CACHE_TIME"],

                "SECTION_ID" => $arCurSection['ID'],
                "FILTER_NAME" => $arParams["FILTER_NAME"],
                "SORT_FIELD" => $arParams["ELEMENT_SORT_FIELD"],
                "SORT_ORDER" => $arParams["ELEMENT_SORT_ORDER"],

                "DISPLAY_TOP_PAGER" => "N",
                "DISPLAY_BOTTOM_PAGER" => "Y",
                "PAGER_TEMPLATE" => $arParams["PAGER_TEMPLATE"],

                'COUNT_LIST' => $BP_TEMPLATE->Catalog()->arCount,
                //'SORT_LIST' => $BP_TEMPLATE->Catalog()->getSortList($arParams["IBLOCK_ID"],$arParams['CATEGORY_TYPE'],$arCurSection['ID']),
                'SORT_CODE' => $arParams["ELEMENT_SORT_CODE"],
                'MOD_PAGE_TYPE' => $arResult['MAIN_SECTION_TYPE'],
                'MOD_SECTION_TYPE' => $arResult['SECTION_TYPE'],
                'MOD_AR_SECTION' => $arResult['SECTION_BY_TYPE'],
                'CATEGORY_TYPE' => $arParams['CATEGORY_TYPE'],
                'MOD_SECTION_INPUT' => $arResult['SECTION_INPUT']
            ];

            $APPLICATION->IncludeComponent(
                "mango:element.list",
                "",
                $arSettings,
                false
            );
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
</div>



<?
$BP_TEMPLATE->SeoSection()->getRobots();

$h1 = $BP_TEMPLATE->SeoSection()->generateH1($arResult['SECTION_INPUT'],$arParams['CATEGORY_TYPE']);
$title = $BP_TEMPLATE->SeoSection()->getTitle($arResult['SECTION_INPUT'],$arParams['CATEGORY_TYPE']);
$desc = $BP_TEMPLATE->SeoSection()->getDesc($arResult['SECTION_INPUT'],$arParams['CATEGORY_TYPE']);

if($APPLICATION->GetPageProperty("withoutmeta")!='Y')
{
    $APPLICATION->SetPageProperty("title", $title);
    $APPLICATION->SetPageProperty("description", $desc);
    $APPLICATION->SetPageProperty("h1", $h1);
}
$strAjaxBreadcrumbs = $APPLICATION->GetNavChain(array('s1', false), 0, '/local/templates/veleluce/components/bitrix/breadcrumb/simple/template.php', true, false);

if ($_REQUEST["catalog_ajax_call"] == "Y")
{
    $APPLICATION->RestartBuffer();
    echo \Bitrix\Main\Web\Json::encode([
        'filter'=>$strAjaxFilter,
        'items'=>$strAjaxItems,
        'breadcrumbs' =>$strAjaxBreadcrumbs,
        'h1' =>$h1,
        'title' =>$title,
        'description' =>$desc

    ]);
    die();
}
?>
