<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);
global $BP_TEMPLATE;
?>
<?if($arResult['NAME'] != ''):?>
    <div class="page-block-head">
        <h1 class="page-title _type-1"><?=$arResult['NAME']?></h1>
    </div>
    <div class="view__item-detail ">
            <?$arElem = $arResult['ELEMENTS'][$arResult['PROPERTIES']['ELEMENT_ID']['VALUE']];?>
            <div class="view__item">
                <div class="view__content">
                    <div class="view__left">
                        <div class="view__image"><img src="<?=$arResult['DETAIL_PICTURE']['SRC']?>"></div>
                    </div>
                    <div class="view__right">
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
                                        <span class="view__price-gr"><?=\SaleFormatCurrency($price_100, 'RUB')?> за 100 г</span>
                                    </div>
                                <?else:?>
                                    <div class="view__desc-value"><?=$propVal?></div>
                                <?endif;?>
                            </div>
                        <?endforeach;?>
                        <?foreach ($arResult['PROPERTIES']['DETAIL_BLOCK_NAME']['VALUE'] as $key=>$val):?>
                            <div class="view__describe">
                                <div class="view__sect"><?=$val?></div>
                                <div class="view__text"><?=$arResult['PROPERTIES']['DETAIL_BLOCK_TEXT']['~VALUE'][$key]['TEXT']?></div>
                            </div>
                        <?endforeach;?>
                        <div class="view__control-links">
                            <div class="view__control-link is-good"><a href="/catalog/product/<?=$arElem['CODE']?>/">Перейти к товару</a></div>
                        </div>
                    </div>
                </div>
            </div>
    </div>
<?endif;?>
<?include($_SERVER["DOCUMENT_ROOT"].$this->GetFolder().'/schema_org.php');?>
