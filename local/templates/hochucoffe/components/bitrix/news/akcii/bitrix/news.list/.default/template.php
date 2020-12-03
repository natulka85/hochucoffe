<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);
global $BP_TEMPLATE;
?>
<?if(!empty($arResult['ITEMS'])):?>
<div class="page-block-head"><h1 class="page-title _type-1">Акции интернет-магазина "Хочу Кофе"</h1></div>
<div class="akcii__list">
    <?foreach ($arResult['ITEMS'] as $arItem):?>
    <div class="akcii__item <?=$arItem['PROPERTIES']['CLASS']['VALUE']?>">
        <a href="<?=$arItem['DETAIL_PAGE_URL']?>" class="akcii__item-pic">
            <img src="<?=$arItem['PREVIEW_PICTURE']['SRC']?>" alt="">
            <div class="akcii__item-name"><?=$arItem['NAME']?></div>
            <div class="akcii__item-short"><?=$arItem['PREVIEW_TEXT']?></div>
        </a>
        <div class="akcii__item-content">
            <a href="<?=$arItem['DETAIL_PAGE_URL']?>" class="akcii__item-name"><?=$arItem['NAME']?></a>
            <div class="akcii__item-short"><?=$arItem['PREVIEW_TEXT']?></div>
            <div class="akcii__item-info-block">
                <div class="akcii__item-info-bl">
                    <div class="akcii__item-i-name">Для кого:</div>
                    <div class="akcii__item-i-value"><?=$arItem['PROPERTIES']['FOR_WHO']['VALUE']?></div>
                </div>
                <div class="akcii__item-info-bl">
                    <div class="akcii__item-i-name">Воспользоваться чтобы успеть:</div>
                    <div class="akcii__item-i-value"><?=FormatDate('j F Y',strtotime($arItem['DATE_ACTIVE_FROM']))?> - <?=FormatDate('j F Y',strtotime($arItem['DATE_ACTIVE_TO']))?></div>
                </div>
            </div>
            <div class="akcii__link icon-1h_galka">
                <a href="<?=$arItem['DETAIL_PAGE_URL']?>">Узнать подробнее</a>
            </div>
        </div>
    </div>
    <?endforeach;?>
</div>
<?endif;?>
<?if($arParams["DISPLAY_BOTTOM_PAGER"]):?>
    <br /><?=$arResult["NAV_STRING"]?>
<?endif;?>
