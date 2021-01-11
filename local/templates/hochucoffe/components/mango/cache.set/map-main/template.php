<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use \Bitrix\Main\Localization\Loc;
$this->setFrameMode(true);
?>
<?
if(count($arResult['ITEMS'])>0):?>
<section class="map">
    <div class="page-block-head is-center"><h2 class="page-title _type-2">Выбрать кофе по стране</h2></div>
    <div class="map__content">
        <div class="map__left"></div>
        <div class="map__right">
            <div class="map__country-list fw">
                <?foreach ($arResult['ITEMS'] as $arItem):?>
                    <a class="map__country-link" data-country_id="<?=$arItem['PROPERTY_ID_ON_MAP_VALUE']?>" href="<?=$arItem['PROPERTY_URL_VALUE']?>" target="_blank">
                        <div class="map__country-top"><span><?=$arItem['NAME']?></span>
                            <div class="map__flag"><img src="<?=$arItem['PICTURE']['src']?>"></div>
                        </div>
                        <p class="map__country-info"><?=$arItem['DETAIL_TEXT']?></p>
                    </a>
                <?endforeach;?>
            </div>
        </div>
    </div>
</section>
<?endif;?>
