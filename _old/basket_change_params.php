<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule("sale");
global $BP_TEMPLATE;


//echo $_REQUEST['rest'].'$_REQUEST["rest"]';
$basket_id = 0;
$arProps = [];

foreach ($_REQUEST as $data_key=>$data){
    if(strpos($data_key,'dop_')!==false){
        $ar=explode('dop_',$data_key);
        if($ar[1]!=''){
            $arProps[strtoupper($ar[1])] = $data;
        }
    }
}
$basket_id = $_REQUEST[]
foreach($_SESSION['bp_cache']['bp_user']['basket'] as $prod_id => $arBasket)
{
    if($prod_id == $_REQUEST['cur_id'])
    {
        $PRODUCT_ID = $arBasket['basket_id'];
    }
}
if($PRODUCT_ID >0){
    $BP_TEMPLATE->basket()->updateProduct($basket_id, $_REQUEST['q']);

    $json['error'] = 'ok';
    $json['func'] = '';

}


