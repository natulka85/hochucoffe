<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die(); ?>
<div class="js-ajax-content">
    <? foreach ($arResult['ITEMS'] as $arItem): ?>
        <a href="<?=$arItem['DETAIL_PAGE_URL']?>"><?=$arItem['NAME']?> цена - <?=$arItem['PRICES'][1]?></a><br/>
        <?//pre($arItem);?>
    <? endforeach; ?>
</div>
<?=$arResult['NAV_STRING']?>
