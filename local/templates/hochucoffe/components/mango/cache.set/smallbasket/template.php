<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
CModule::IncludeModule("sale");
global $BP_TEMPLATE;
$arResult['TMPL_PROPS_DOP_OPTIONS'] = $BP_TEMPLATE->Catalog()->dopProperties;
$arData = $_SESSION['bp_cache']['bp_user'];
?>
<?
$count = 0;
if(is_array($arData['basket']))
{
    foreach($arData['basket'] as $prod_id=>$arBaskets){
        foreach ($arBaskets as $bas_id=>$arBasket)
            $count = $count + $arBasket['quantity'];
    }

}
$delay = false;
$compare =false;
if(count($arData['delay'])>0){
    $delay = true;
}
if(count($arData['compare'])>0){
    $compare = true;
}
?>
    <div class="pers-info__ajax-wrap">
        <a class="sbasket-refresh" style="display:none;" rel="nofollow" href="<?=$APPLICATION->GetCurPage()?>">Обновить</a>
        <div class="pers-info__list">
            <div class="pers-info__item is-main"><a class="pers-info__link" href="/">
                    <div class="pers-info__icon icon-2j_house"></div>
                    <div class="pers-info__text">Главная</div>
                </a></div>
            <div class="pers-info__item is-catalog"><a class="pers-info__link" href="/catalog/">
                    <div class="pers-info__icon icon-2i_katalog"></div>
                    <div class="pers-info__text">Каталаг</div>
                </a></div>
            <div class="pers-info__item is-compare"><a class="pers-info__link" href="/personal/compare/">
                    <div class="pers-info__icon icon-1c_sravnn"></div>
                    <div class="pers-info__text">В сравнении</div>
                    <div class="pers-info__num"><span></span></div>
                    <? //include (__DIR__.'/compare-template.php');?>
                </a></div>
            <div class="pers-info__item is-delay"><a class="pers-info__link" href="/personal/delay/">
                    <div class="pers-info__icon icon-1e_heart"></div>
                    <div class="pers-info__text">Отложенные</div>
                    <div class="pers-info__num"><span></span></div>
                    <? //include (__DIR__.'/delay-template.php');?>
                </a></div>
            <div class="pers-info__item is-basket" data-opened="basket">
                <a class="pers-info__link" href="/personal/basket/">
                    <div class="pers-info__icon icon-1g_coffecapn"></div>
                    <div class="pers-info__text">Корзина</div>
                    <div class="pers-info__num"><span></span></div>
                </a>
                <? include (__DIR__.'/basket-template.php');?>
            </div>
        </div>
    </div>
<script>
/*$( ".spinner" ).spinner({
    min: 1,
});*/
<?if($delay){
    $delay_class = 'is-active';
}
if($compare){
    $compare_class = 'is-active';
}
if(count($arData['basket'])>0){
    $basket_class = 'is-active';
}
?>
$(function(){
    $('.is-delay .pers-info__num').text('<?=count($arData['delay'])?>').addClass("<?=$delay_class?>");
    $('.is-compare .pers-info__num').text('<?=count($arData['compare'])?>').addClass("<?=$compare_class?>");
    $('.is-basket .pers-info__num').text('<?=$count?>').addClass("<?=$basket_class?>");
})

</script>
<?include_once ($_SERVER['DOCUMENT_ROOT'].'/local/templates/hochucoffe/include/remind_basket.php');?>
