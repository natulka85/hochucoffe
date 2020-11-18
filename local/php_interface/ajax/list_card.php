<?require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die(); ?>
<?

$id = 0;
if($_REQUEST['id']>0){
    $id = $_REQUEST['id'];
}
global $BP_TEMPLATE,$APPLICATION;
global $arFilterCard;

if($id>0){
    $APPLICATION->RestartBuffer();
    ob_start();

    $arFilterCard = array(
        "=ID" => $id,
    );

    $arSettings = [
        "IBLOCK_TYPE" => $BP_TEMPLATE->getConstants()->IBLOCK_MAIN_IB_TYPE,
        "IBLOCK_ID" => $BP_TEMPLATE->getConstants()->IBLOCK_MAIN_ID,
        "COUNT_ON_PAGE" => 1,
        "CACHE_TIME"  =>  86400,
        "SECTION_ID" => $BP_TEMPLATE->getConstants()->IBLOCK_MAIN_TYPE,
        "FILTER_NAME" => 'arFilterCard',
        'MOD_TEMPATE' => 'CARD',
        'CATEGORY_TYPE'=> 'ONE_CARD'
    ];

    $APPLICATION->IncludeComponent(
        "mango:element.list",
        "",
        $arSettings,
        false
    );

    $out = ob_get_flush();
    $APPLICATION->RestartBuffer();
    $json['result'] = $out;

   /* $json['error'] = 'ok';
    $json['func'] = '$(".catg__item[data-elem='.$_REQUEST['cur_id'].']").replaceWith(`'.$out.'`);';*/
}

?>
