<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
global $BP_TEMPLATE;

$GLOBALS['MOD_GLOBALS']['CARD_URL'] = $arResult['DETAIL_PAGE_URL'];
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
