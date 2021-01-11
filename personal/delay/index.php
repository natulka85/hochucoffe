<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Отложенные товары");
\Bitrix\Main\Page\Asset::getInstance()->addCss("/local/templates/hochucoffe/static/dist/css/delay-page.css");
?>
<div class="delay-page">
    <?$APPLICATION->IncludeComponent("bitrix:breadcrumb","simple",Array(
            "START_FROM" => "0",
            "PATH" => "",
            "SITE_ID" => "s1"
        )
    );
    global $arrFilter,$BP_TEMPLATE;
    $arrFilter['ID'] =  $_SESSION['bp_cache']['bp_user']['delay'];

    ?>
    <div class="page-block-head"><h1 class="page-title _type-1 icon-1f_heart_full">Отложенные товары</h1>
        <?if(count($arrFilter['ID'])>0):?><div class="delay-page__btn js-do" data-action="delay_change" data-id="<?=implode(',',$_SESSION['bp_cache']['bp_user']['delay'])?>" data-state="Y" data-reload="Y">Очистить отложенные</div><?endif;?>
    </div>
    <?if($_REQUEST["catalog_ajax_call"] == "Y") {
        ob_start();
    }
    ?>

    <div class="js-ajax-content">
<?
if(count($arrFilter['ID'])>0){
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
            "FILTER_NAME" => "arrFilter",
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
}
else{
    ?>
    <div class="delay-page__note">
        В Вашем списке отложенных нет товаров :(<br>
    </div>

    <a href="/catalog/" class="delay-page__btn-catalog">Перейти в каталог</a>
    <?
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
<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>
