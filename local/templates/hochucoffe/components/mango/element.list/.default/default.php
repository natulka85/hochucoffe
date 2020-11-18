<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die(); ?>
<?/*echo "<pre>";
   print_r($arResult);
echo "</pre>";*/
?>
<?if(count($arResult['ITEMS'])>0):?>
    <?if($arParams['DISPLAY_TOP_PAGER']=='Y'):?>
        <div class="catg__list-control">
            <div class="mob__mob-control">
                <div class="mob__btn-back icon-2m_arrow-l js-link is-sort-link"></div>
            </div>
            <div class="catg__list-control-bl is-sort">
                <div class="catg__list-control-name">Сортировать по:</div>
                <?foreach ($arParams['SORT_LIST'] as $sort_code=>$list):?>
                    <?if(strpos($sort_code))?>
                        <div class="catg__list-control-value js-ctg-sort <?=$list['class']?>" data-value="<?=$sort_code?>"><?=$list['name']?></div>
                <?endforeach;?>
            </div>
            <div class="catg__list-control-bl is-cnt">
                <div class="catg__list-control-name">Показано:</div>
                <div class="catg__list-control-value not-change"><?=$arResult['COUNT_MESSAGE']?></div>
            </div>
        </div>
    <?endif;?>

    <div class="catg__list">
        <?foreach ($arResult['ITEMS'] as $arItem):?>
            <?include ($_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH.'/includes/catalog_card.php');?>
        <?endforeach;?>
    </div>
<?endif;?>
<?
if($arParams['DISPLAY_BOTTOM_PAGER']=='Y'):?>
    <?=$arResult['NAV_STRING']?>
<?endif;?>

