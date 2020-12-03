<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
global $BP_TEMPLATE;

$arCategories = ['ALL_CAT','ALL_CAT_FILTER','SECTION','BRAND','SECTION_MAN','STOCK','STOCK_SECTION','SECTION_FILTER'];
if(in_array($arParams['CATEGORY_TYPE'],$arCategories)){
    $APPLICATION->SetPageProperty("NavRecordCount", $arResult['NavRecordCount']);

if($arParams['CATEGORY_TYPE']!='ONE_CARD'){
    foreach($arResult['CHAIN'] as $sec_id => $arSect)
    {
        if(is_array($arSect['CHILDS']) && count($arSect['CHILDS'])>0)
        {
            $APPLICATION->arAdditionalChain[1]['TITLE'] = $arSect['NAME'];
            $APPLICATION->arAdditionalChain[1]['LINK'] = $arSect['SECTION_PAGE_URL'];
            $arSect['NAME'] = '';  //"удаляем" из списка

            foreach($arSect['CHILDS'] as $sec_id2 => $arSect2)
            {
                if($sec_id2 == $arParams['SECTION_ID'])
                {
                    $APPLICATION->arAdditionalChain[2]['TITLE'] = $arSect2['NAME'];
                    $APPLICATION->arAdditionalChain[2]['LINK'] = $arSect2['SECTION_PAGE_URL'];
                    $arSect2['NAME'] = '';    //"удаляем" из списка
                }
                if($arSect2['NAME']!='')
                    $APPLICATION->arAdditionalChain[2]['ADDITIONAL'][] = ['TITLE' => /*TruncateText($arSect2['NAME'], 18)*/$arSect2['NAME'], 'LINK' => $arSect2['SECTION_PAGE_URL']];
            }
        } elseif($sec_id == $arParams['SECTION_ID'] && $arParams['SECTION_ID'] != '')
        {
            $APPLICATION->arAdditionalChain[1]['TITLE'] = $arSect['NAME'];
            $APPLICATION->arAdditionalChain[1]['LINK'] = $arSect['SECTION_PAGE_URL'];
        }
        elseif($arParams['SECTION_ID'] == ''){
            $APPLICATION->arAdditionalChain[1]['TITLE'] = 'Каталог';
            $APPLICATION->arAdditionalChain[1]['LINK'] = '/catalog/';
        }
        if($arSect['NAME']!='')
            $APPLICATION->arAdditionalChain[1]['ADDITIONAL'][] = ['TITLE' => /*TruncateText($arSect['NAME'], 18)*/$arSect['NAME'], 'LINK' => $arSect['SECTION_PAGE_URL']];
    }
}
    if(!empty($chainFilters))
        $APPLICATION->arAdditionalChain = array_merge($APPLICATION->arAdditionalChain, $chainFilters);

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

foreach ($_SESSION['bp_cache']['bp_user']['basket_code'] as $el_ids=>$basketIds){
    $elem_id = $el_ids;
    foreach ($basketIds as $basket_id => $prop){
        $html_element = '';
        $decode_prop = json_decode($prop);
        foreach ($decode_prop as $propCode => $val){
            $html_element .= "[data-dop_".$propCode."='".$val."']";
        }
        $qunatity = round($_SESSION['bp_cache']['bp_user']['basket'][$elem_id][$basket_id]['quantity'],0);
        $bid = $basket_id;
        echo '<script>inBasket("[data-id='.$elem_id.']'.$html_element.'",'.$qunatity.','.$bid.','.$elem_id.');</script>';
    }
}

