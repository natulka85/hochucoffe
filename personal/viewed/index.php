<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Просмотренные товары в интернет-магазине 'ХочуКофе'");

use Bitrix\Sale,
    Bitrix\Catalog\CatalogViewedProductTable;

CModule::IncludeModule("iblock");
CModule::IncludeModule("catalog");
CModule::IncludeModule("sale");?>

<div class="viewed-page">
    <?$APPLICATION->IncludeComponent("bitrix:breadcrumb","simple",Array(
            "START_FROM" => "0",
            "PATH" => "",
            "SITE_ID" => "s1"
        )
    );?>
    <div class="page-block-head">
        <div class="page-block-head"><h1 class="page-title _type-1">Кофе, который Вы смотрели</h1></div>
    </div>


<?
global $BP_TEMPLATE;
if($_REQUEST["catalog_ajax_call"] == "Y") {
    ob_start();
}
?>
    <div class="js-ajax-content">
            <?
            $F_USER = Sale\Fuser::getId();

            $bd_viewed = CatalogViewedProductTable::GetList(
                ['filter'=>[
                    'FUSER_ID' => $F_USER
                ]
                ]);

            while($ob_view = $bd_viewed->Fetch()){
                $arElements[] = $ob_view['ELEMENT_ID'];
            }
            global $arrFilter;
            $arrFilter['ID'] =  $arElements;

            $arParams['CATEGORY_TYPE'] = 'ALL_CAT';
            $arParams["PAGE_ELEMENT_COUNT"] = $BP_TEMPLATE->Catalog()->arCount;
            $arSort = $BP_TEMPLATE->Catalog()->getCurSort($_REQUEST['sort'],$BP_TEMPLATE->getConstants()->IBLOCK_MAIN,$arParams['CATEGORY_TYPE'], $BP_TEMPLATE->getConstants()->IBLOCK_MAIN_TYPE);
            //pre($arParams['CATEGORY_TYPE']);
            //pre($arSort);
            $arParams["ELEMENT_SORT_FIELD"] = $arSort['prop'];
            $arParams["ELEMENT_SORT_ORDER"] = $arSort['order'];
            $arParams["ELEMENT_SORT_CODE"] = $arSort['code'];

            if(count($arrFilter['ID']) > 0){
                $APPLICATION->IncludeComponent(
                    "mango:element.list",
                    "",
                    [
                        "IBLOCK_TYPE" => $BP_TEMPLATE->getConstants()->IBLOCK_MAIN_TYPE,
                        "IBLOCK_ID" => array(1),
                        "COUNT_ON_PAGE" => 150,
                        "CACHE_TIME"  =>  3600,
                        "FILTER_NAME" => "arrFilter",
                        "SORT_FIELD" => $arParams["ELEMENT_SORT_FIELD"],
                        "SORT_ORDER" => $arParams["ELEMENT_SORT_ORDER"],

                        'COUNT_LIST' => $BP_TEMPLATE->Catalog()->arCount,
                        'SORT_LIST' => $BP_TEMPLATE->Catalog()->getSortList($BP_TEMPLATE->getConstants()->IBLOCK_MAIN_ID,$arParams['CATEGORY_TYPE'],$arCurSection['ID']),
                        'SORT_CODE' => $arParams["ELEMENT_SORT_CODE"],

                        "DISPLAY_TOP_PAGER" => "Y",
                        "DISPLAY_BOTTOM_PAGER" => "Y",
                        "PAGER_TEMPLATE" => "",
                        'PAGE_TYPE' => 'VIEWED',
                    ],
                    false
                );
            }
            else{?>
                <div class="viewed-page">
                    <div class="viewed-page__info icon1-1a_otlogit">
                        <div class="viewed-page__title">Список просмотренных товаров пуст</div>
                    </div>
                    <div class="viewed-page__blank">
                    </div>
                </div>
            <?}?>
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
}
?>
</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
