<?
if(count($arData['delay'])>0):?>
    <div class="pers-info__list-wrap">
        <div class="pers-info__list-title">Отложенные товары</div>
        <div class="pers-info__list-hold">
            <div class="basket__list">
                <?
                if(is_array($arData['delay'])):
                    $sum = 0;
                    $disc = 0;
                    foreach($arData['delay'] as $prod_id=>$arDelay):
                            //image
                            $pic_width = $pic_height = 52;
                            $arFile = CFile::ResizeImageGet(
                                $arData['products'][$prod_id]['PREVIEW_PICTURE_ID'],
                                array("width" => $pic_width, "height" => $pic_height),
                                BX_RESIZE_IMAGE_PROPORTIONAL_ALT ,
                                true
                            );

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
                                    </div>
                                    <div class="basket__price">
                                        <div class="basket__price-c"><?=\SaleFormatCurrency($arData['products'][$prod_id]['PRICE_1'], 'RUB')?></div>
                                        <?if($arData['products'][$prod_id]['PRICE_2']>0):?>
                                            <div class="basket__price-old"><?=\SaleFormatCurrency($arData['products'][$prod_id]['PRICE_2'], 'RUB')?></div>
                                        <?endif;?>
                                    </div>
                                </div>
                                <div class="basket__el-control">
                                    <div class="basket__cont-btn-wrap">
                                        <?$inBasket = false;?>
                                        <?if(isset($arData['basket'][$prod_id])) $inBasket=true;?>
                                        <div class="basket__cont-btn btn-basket <?if(!$inBasket):?>js-do<?else:?>is-active<?endif;?>" data-action="basket_change" data-q="1" data-id="<?=$prod_id?>" <?foreach($arResult['TMPL_PROPS_DOP_OPTIONS'] as $k => $v):?>
                                            <?if(in_array($v['DEFAULT_VALUE'],$arData['products'][$prod_id][$k]['VALUE']))
                                                $val=$v['DEFAULT_VALUE'];
                                            else{
                                                $val=$arData['products'][$prod_id][$k]['VALUE'][0];
                                            }
                                            ?>
                                            <?$string = $k.'||'.$v['NAME_PROP'].'||'.$val?>
                                            data-dop_<?=strtolower($k)?>="<?=$string?>"
                                        <?endforeach?>
                                        ><span class="_text">В корзину</span></div>
                                        <div class="basket__cont-btn btn-remove js-do" data-state="Y" data-img="<?=$arFile['src']?>" data-action="delay_change" data-id="<?=$prod_id?>"><span class="_text">Удалить</span></div>
                                    </div>
                                </div>
                            </div>
                    <?endforeach?>
                <?endif?>
            </div>
        </div>
        <div class="pers-info__btn-wrap">
            <a class="pers-info__btn-link btn is-white" href="/personal/delay/">Перейти к отложенным</a>
        </div>
    </div>
<?endif;?>



