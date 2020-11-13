<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
global $BP_TEMPLATE;
$arCategories = ['ALL_CAT','ALL_CAT_FILTER','SECTION','BRAND','SECTION_MAN','STOCK','STOCK_SECTION','SECTION_FILTER'];
if(in_array($arParams['CATEGORY_TYPE'],$arCategories)){
    $APPLICATION->SetPageProperty("NavRecordCount", $arResult['NavRecordCount']);
}

//check delay
$arDelay = [];
if(isset($_SESSION['bp_cache']['bp_user']['delay']))
{
    foreach($_SESSION['bp_cache']['bp_user']['delay'] as $delay_id=>$value)
    {
        if(in_array($delay_id,$arResult['ITEMS_ID']))
            $arDelay[] = $delay_id;
    }
}
if(count($arDelay)>0)
{
    echo '<script>inDelayList('.\Bitrix\Main\Web\Json::encode($arDelay).');</script>';
}
