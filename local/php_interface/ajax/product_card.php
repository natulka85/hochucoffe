<?require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die(); ?>
<?
echo "<pre>";
   print_r($_REQUEST);
echo "</pre>";
$id = 0;
if($_REQUEST['id']>0){
    $id = $_REQUEST['id'];
}
global $BP_TEMPLATE,$APPLICATION;
global $arFilterCard;
$template = 'CARD';
if($_REQUEST['template'] !=''){
    $template = $_REQUEST['template'];
}

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
        'MOD_TEMPATE' => $template,
        'CATEGORY_TYPE'=> 'ONE_CARD',
        'REQUEST_ID' => $id
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
    $json['func'] = 'var block = $(".js-prod-ajax");
                    block.animate({"opacity":0},200).replaceWith(`'.$out.'`).animate({"opacity":1},200);
                    initProductDetailPhotoGallery();
                    moreContent();';
    if($_REQUEST['fast']==''){
        $json['func'].= '
        var url = "'.$GLOBALS['MOD_GLOBALS']['CARD_URL'].'";
        console.log(url);
        curState ={url:url};
        history.pushState(curState, document.title, url);';
    }

   /* $json['error'] = 'ok';
    $json['func'] = '$(".catg__item[data-elem='.$_REQUEST['cur_id'].']").replaceWith(`'.$out.'`);';*/
}

?>
