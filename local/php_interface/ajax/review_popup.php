<?require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die(); ?>
<?

$id = 0;
if($_REQUEST['id']>0){
    $id = $_REQUEST['id'];
}
global $BP_TEMPLATE,$APPLICATION;
global $FilterReview;

if($id>0){
    $APPLICATION->RestartBuffer();
    ob_start();
    ?>
    <div class="popup__box is-review">
        <div class="popup__box-wrap">
            <div class="popup__close icon-2a_plus"></div>
    <?
    $FilterReview = array(
        "=ID" => $id,
    );

    $APPLICATION->IncludeComponent(
        "mango:cache.set",
        "review-index",
        [
            'CNT' => 1,
            'FILTER_NAME' => 'FilterReview',
            'GOOD_LINK' => 1,
        ],
        false
    );
    ?>
        </div>
    </div>
    <?
    $out = ob_get_flush();
    $APPLICATION->RestartBuffer();

    $json['error'] = 'ok';
    $json['func'] ='$(".popup").html(`'.$out.'`);
                    showPopup($(".popup"),{widthCss:"600"});';
}

?>
