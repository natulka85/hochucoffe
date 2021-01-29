<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die(); ?>
<?/*echo "<pre>";
   print_r($arResult);
echo "</pre>";*/
?>
<?if(count($arResult['ITEMS'])>0):?>
<?if($arParams['MOD_TITTLE']!=''):?>
        <div class="page-block-head is-center"><h2 class="page-title _type-2"><?=$arParams['MOD_TITTLE']?></h2>
            <?if($arParams['MOD_BTN_MORE']!=''):?>
                <a href="<?=$arParams['MOD_BTN_MORE']?>"
                   class="page-title-link">Смотреть все</a>
                <?endif;?>

        </div>
    <?endif;?>
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

    <div class="catg__list <?=$arParams['MOD_LIST_CLASS']?>" data-block="block-list">
        <?foreach ($arResult['ITEMS'] as $arItem):?>
            <?$item_data='block-item'?>
            <?include ($_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH.'/includes/catalog_card.php');?>
        <?endforeach;?>
    </div>
    <?
    if($arParams['DISPLAY_BOTTOM_PAGER']=='Y'):?>
        <?if($arResult['SHOWMORE_URL']): //если много товаров на странице - выводим?>
            <div class="catg__more">
                <a class="js-more-el" href="<?=$arResult['SHOWMORE_URL']?>">
                <span class="catg__more-1">Показать ещё
                    <span class="catg__more-icon icon-3a_more"></span>
                </span>
                    <span class="catg__more-2">осталось <?=$arResult['SHOWMORE_COUNT']?> шт</span>
                </a>
            </div>
        <?endif;?>
        <?=$arResult['NAV_STRING']?>
    <?endif;?>

<?endif;?>

