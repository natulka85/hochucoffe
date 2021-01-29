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

    if($_REQUEST['from']=='main'){
        $ym['v_korzinu'] = 'ym(71202064,\'reachGoal\',\'click_v_korziny_glavnaya_bisrij_prosmotr\');';
    }
    elseif ($_REQUEST['from']=='catalog'){
        $ym['v_korzinu'] = 'ym(71202064,\'reachGoal\',\'click_v_korziny_katalog_bistrij_prosmotr\');';
    }
    else{
        $ym['v_korzinu'] = 'ym(71202064,\'reachGoal\',\'click_v_korziny_ostalnoe_bistrij_prosmotr\');';
    }
    $arSettings = [
        "IBLOCK_TYPE" => $BP_TEMPLATE->getConstants()->IBLOCK_MAIN_IB_TYPE,
        "IBLOCK_ID" => $BP_TEMPLATE->getConstants()->IBLOCK_MAIN_ID,
        "COUNT_ON_PAGE" => 1,
        "CACHE_TIME"  =>  86400,
        "SECTION_ID" => $BP_TEMPLATE->getConstants()->IBLOCK_MAIN_TYPE,
        "FILTER_NAME" => 'arFilterCard',
        'MOD_TEMPATE' => 'FAST_VIEW',
        'CATEGORY_TYPE'=> 'ONE_CARD',
        'REQUEST_ID' => $id,
        'EVENTS' => $ym
    ];

    $APPLICATION->IncludeComponent(
        "mango:cache.set",
        "one_element",
        $arSettings,
        false
    );

    $out = ob_get_flush();
    $APPLICATION->RestartBuffer();

    $json['error'] = 'ok';
    $json['func'] ='$(".popup-dop").html(`'.$out.'`);
                    showPopup($(".popup-dop"),{widthCss:"1000",className:"is-fast",absolute:"true"});
                    initProductDetailPhotoGallery();'.$event;
}

?>
