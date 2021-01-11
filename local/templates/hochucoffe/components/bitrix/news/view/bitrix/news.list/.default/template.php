<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);
global $BP_TEMPLATE;?>
<?if(!empty($arResult['ITEMS'])):?>
    <div class="view__list <?=$arParams['LIST_CLASS']?>">
        <?foreach ($arResult['ITEMS'] as $arItem):?>
        <?$arElem = $arResult['ELEMENTS'][$arItem['PROPERTIES']['ELEMENT_ID']['VALUE']];?>
            <div class="view__item <?=$arParams['ITEM_CLASS']?>">
                <div class="view__content">
                    <div class="view__left">
                        <div class="view__image"><a href="/views/<?=$arItem['CODE']?>/"><img src="<?=$arItem['PREVIEW_PICTURE']['SRC']?>"></a></div>
                    </div>
                    <div class="view__right"><a class="view__title" href="/views/<?=$arItem['CODE']?>/"><?=$arItem['NAME']?></a>
                        <?foreach ($arResult['ELEM_PROPS'] as $prop=>$propName):?>
                        <?$propVal = $arElem['PROPERTY_'.$prop.'_VALUE'];
                        if($arElem['PROPERTY_'.$prop.'_VALUE_F']!=''){
                            $propVal = $arElem['PROPERTY_'.$prop.'_VALUE_F'];
                        }?>
                            <div class="view__main-desc">
                                <div class="view__desc-name"><?=$propName?>:</div>
                                <?if($prop=='OPTIMAL_PRICE'):?>
                                <?$price_100 = round($arElem['PROPERTY_'.$prop.'_VALUE'] / $arElem['PROPERTY_WEIGHT_VALUE']  * 100,0);?>
                                    <div class="view__desc-value">
                                        <span class="view__price-all"><?=\SaleFormatCurrency($arElem['PROPERTY_'.$prop.'_VALUE'], 'RUB')?> за <?=$arElem['PROPERTY_WEIGHT_VALUE']?>г</span><br>
                                        <span class="view__price-gr"><?=\SaleFormatCurrency($price_100, 'RUB')?> за 100г</span>
                                    </div>
                                <?else:?>
                                    <div class="view__desc-value"><?=$propVal?></div>
                                <?endif;?>
                            </div>
                        <?endforeach;?>
                        <?foreach ($arItem['PROPERTIES']['PREVIEW_BLOCK_NAME']['VALUE'] as $key=>$val):?>
                            <div class="view__describe">
                                <div class="view__sect"><?=$val?></div>
                                <div class="view__text"><?=$arItem['PROPERTIES']['PREVIEW_BLOCK_TEXT']['VALUE'][$key]['TEXT']?></div>
                            </div>
                        <?endforeach;?>
                        <div class="view__control-links">
                            <div class="view__control-link is-view"><a href="/views/<?=$arItem['CODE']?>/">Читать весь обзор</a></div>
                            <div class="view__control-link is-good"><a href="/catalog/product/<?=$arElem['CODE']?>/">Перейти к товару</a></div>
                        </div>
                    </div>
                </div>
            </div>
        <?endforeach;?>
    </div>
    <div class="view__bottom-arrows">
        <div class="swiper-button-prev"></div>
        <div class="swiper-button-next"></div>

        <span class="slick-prev slick-arrow"></span>
        <span class="slick-next slick-arrow"></span>
    </div>
<?endif;?>
<?if($arParams["DISPLAY_BOTTOM_PAGER"]=='Y'):?>
    <br /><?=$arResult["NAV_STRING"]?>
<?endif;?>
