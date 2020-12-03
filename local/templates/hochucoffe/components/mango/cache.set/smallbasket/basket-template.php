<?if(count($arData['basket'])>0):?>
<? $arResult['TMPL_PROPS_DOP_OPTIONS'] = $BP_TEMPLATE->Catalog()->dopProperties;?>
<div class="pers-info__list-wrap">
    <div class="pers-info__list-title">Корзина</div>
    <div class="pers-info__list-hold">
    <div class="basket__list">

            <?
            if(is_array($arData['basket'])):
                $sum = 0;
                $disc = 0;
                foreach($arData['basket'] as $prod_id=>$arBaskets):
                    foreach ($arBaskets as $basket_id=>$arBasket):
                    //image
                    $pic_width = $pic_height = 52;
                    $arFile = CFile::ResizeImageGet(
                        $arData['products'][$prod_id]['PREVIEW_PICTURE_ID'],
                        array("width" => $pic_width, "height" => $pic_height),
                        BX_RESIZE_IMAGE_PROPORTIONAL_ALT ,
                        true
                    );
                    $sum = $sum + $arData['products'][$prod_id]['PRICE_1']*$arBasket['quantity'];

                    if($arData['products'][$prod_id]['PRICE_2'] - $arData['products'][$prod_id]['PRICE_1']>0){
                        $disc += ($arData['products'][$prod_id]['PRICE_2'] - $arData['products'][$prod_id]['PRICE_1']) * $arBasket['quantity'];
                    }

                    $name = $arData['products'][$prod_id]['NAME'];
                    if(strlen($name) > 60){
                        $shotName = substr($name, 0, 60);
                        $arData['products'][$prod_id]['NAME'] = substr($shotName, 0, strrpos($shotName, ' ')).'...';
                    }
                    ?>

                    <div class="basket__item">
                    <div class="basket__el">
                        <div class="basket__el-pic"><img src="<?=$arFile['src']?>"/></div>
                        <div class="basket__el-name-wrap">
                            <a href="<?=$arData['products'][$prod_id]['DETAIL_PAGE_URL']?>" class="basket__el-name"><?=$arData['products'][$prod_id]['NAME']?></a>
                            <div class="basket__elem-props">
                                <?foreach ($arResult['TMPL_PROPS_DOP_OPTIONS'] as $dopProp=>$dopValue):?>
                                    <div class="basket__gram">
                                        <div class="cst-select-list js-sort-list">
                                            <span class="cst-select-current icon-1h_galka"><?=$arBasket['basket_props'][$dopProp]['VALUE']?></span>
                                            <div class="cst-select" name="op_<?=$dopProp?>">
                                                <?foreach ($arData['products'][$prod_id][$dopProp]['VALUE'] as $keyVal=>$value):?>
                                                    <div class="cst-select-option js-do" data-action="basket_change" data-dop_<?=$dopProp?>="<?=$dopProp.'||'.$dopValue['NAME_PROP'].'||'.$value?>" data-bid="<?=$basket_id?>" data-q="<?=round($arBasket['quantity'],0)?>"><?=$value?><?=$dopValue['NAME']?></div>
                                                <?endforeach;?>
                                            </div>
                                        </div>
                                    </div>
                                <?endforeach;?>
                            </div>
                        </div>
                        <div class="basket__price">
                            <div class="basket__price-c"><?=\SaleFormatCurrency($arData['products'][$prod_id]['PRICE_1'], 'RUB')?></div>
                            <?if($arData['products'][$prod_id]['PRICE_2']>0):?>
                                <div class="basket__price-old"><?=\SaleFormatCurrency($arData['products'][$prod_id]['PRICE_2'], 'RUB')?></div>
                            <?endif;?>
                        </div>
                    </div>
                    <div class="basket__el-control">
                        <div class="basket__cnt">
                            <div class="count-block js-counter">
                                <div class="count-block__btn js-minus icon-2b_minus"></div>
                                <input class="count-block__value" value="<?=round($arBasket['quantity'],0)?>" data-bid="<?=$basket_id?>" data-action="basket_change">
                                <div class="count-block__btn js-plus icon-2a_plus"></div>
                            </div>
                        </div>
                        <div class="basket__cont-btn-wrap">
                            <div class="basket__cont-btn btn-like js-do" data-state="N" data-img="<?=$arFile['src']?>" data-action="delay_change" data-id="<?=$prod_id?>"><span class="_text">Отложить</span></div>
                            <div class="basket__cont-btn btn-remove js-do" data-action="basket_change" data-default_q="<?=$arBasket['quantity']?>" data-q="0" data-bid="<?=$basket_id?>"><span class="_text">Удалить</span></div>
                        </div>
                    </div>
                </div>
                <?endforeach?>
                <?endforeach?>
            <?endif?>
    </div>
    </div>
    <div class="pers-info__res">
        <div class="pers-info__res-block">
            <div class="pers-info__res-name">Итого:</div>
            <div class="pers-info__res-value"><?=\SaleFormatCurrency($sum, 'RUB')?></div>
        </div>
        <div class="pers-info__res-block is-discount">
            <div class="pers-info__res-name">Скидка:</div>
            <div class="pers-info__res-value"><?=\SaleFormatCurrency($disc, 'RUB')?></div>
        </div>
    </div>
    <div class="pers-info__btn-wrap">
        <a class="pers-info__btn-link btn is-white" href="/personal/basket/">Перейти в корзину</a>
    </div>
</div>
<?endif;?>


