<?php
/*
$arRes = [
    'prop_code' => $_REQUEST['code'],
    'prop_name' => $_REQUEST['name'],
    'prop_value' => $_REQUEST['value'],
];*/
$elem_id = $_REQUEST['cur_id'];

$resString = $_REQUEST['code'].'||'.$_REQUEST['name'].'||'.$_REQUEST['value'];
$json['func'] = '$("[data-action=basket_change][data-id='.$elem_id.']").attr("data-dop_'.$_REQUEST['code'].'","'.$resString.'").addClass("js-do").html("В корзину");
$(".prod__form-btn-choose[data-code='.$_REQUEST['code'].']").removeClass("is-active");
$(".prod__form-btn-choose[data-code='.$_REQUEST['code'].'][data-value=\''.$_REQUEST['value'].'\']").addClass("is-active");';


foreach ($_SESSION['bp_cache']['bp_user']['basket_code'][$elem_id] as $basket_id => $prop){
    $html_element = '';
    $decode_prop = json_decode($prop,TRUE);
    foreach ($decode_prop as $propCode => $val){
        $html_element .= "[data-dop_".$propCode."='".$val."']";
    }
    $qunatity = round($_SESSION['bp_cache']['bp_user']['basket'][$elem_id][$basket_id]['quantity'],0);
    $bid = $basket_id;
    $json['func'] .= 'inBasket("[data-id='.$elem_id.']'.$html_element.'",'.$qunatity.','.$bid.','.$elem_id.');';
}


