<?if($arParams['MOD_AJAX']==''):?><div class="catg__item"><?endif;?>
    <div class="catg__item-wrap" data-elem="<?=$arItem['ID']?>">
    <div class="catg__img js-slide-ix-block">
        <div class="catg__fast js-do" data-action="fast_card" data-id="<?=$arItem['ID']?>">Быстрый просмотр</div>
        <a href="<?=$arItem['DETAIL_PAGE_URL']?>" class="js-slide-ix-target">
            <img src="<?=$arItem['DEFAULT_IMAGE']['SRC']?>" alt="<?=$arItem['DEFAULT_IMAGE']['ALT']?>" loading="lazy" data-index="0" class="catg__img-el">
            <?if(!empty($arItem["MORE_IMAGE"])):?>
                <?foreach ($arItem["MORE_IMAGE"] as $morePictIndex=>$morePict):?>
                    <img src="<?=$morePict["SRC"]?>" loading="lazy" alt="<?=$arItem["DEFAULT_IMAGE"]["ALT"]?>" class="catg__img-el is-hide" data-index="<?=($morePictIndex + 1)?>">
                <?endforeach;?>
            <?endif;?>
        </a>
        <?if(!empty($arItem["MORE_IMAGE"])):?>
            <a class="catg__image-control js-slide-ix-control-block" href="<?=$arItem["DETAIL_PAGE_URL"]?>" target="_blank">
                <div class='js-slide-ix-control is-active' data-index="0"></div>
                <?foreach ($arItem["MORE_IMAGE"] as $morePictIndex=>$morePict):?>
                    <div class='js-slide-ix-control' data-index="<?=($morePictIndex + 1)?>"></div>
                <?endforeach;?>
            </a>
        <?endif;?>
        <?$arTempLables = $arResult['LABLES_TEMPLATE'];?>
        <?foreach ($arTempLables as $pos=>$arVal){
            $cnt=0;
            foreach ($arVal as $k=>$v){
                if(isset($arItem['LABLES'][$v])){
                    $cnt++;
                }
            }
            if($cnt==0) unset($arTempLables[$pos]);
        }?>
        <?if(count($arTempLables)>0):?>
            <div class="catg__labels labels">
                <?foreach ($arTempLables as $pos=>$arVal):?>
                    <div class="labels__items is-<?=mb_strtolower($pos)?>">
                        <?foreach ($arVal as $lKey):?>
                            <?if(isset($arItem['LABLES'][$lKey])):?>
                                <div class="labels__item <?=$arItem['LABLES'][$lKey]['CLASS']?>">
                                    <?if($arItem['LABLES'][$lKey]['IMG_AR']['src']!=''):?>
                                        <div class="labels__item-wrap">
                                            <img src="<?=$arItem['LABLES'][$lKey]['IMG_AR']['src']?>" alt="">
                                        </div>
                                    <?endif;?>
                                    <span><?=$arItem['LABLES'][$lKey]['TEXT']?></span>
                                </div>
                            <?endif;?>
                        <?endforeach;?>
                    </div>
                <?endforeach;?>
            </div>
        <?endif;?>
    </div>
    <div class="catg__content">
        <div class="catg__price-block">
            <?if($arItem['STATE']['PRICE']>0):?>
                <div class="catg__price"><?=\SaleFormatCurrency($arItem['STATE']['PRICE'], 'RUB');?></div>
            <?endif;?>
            <?if($arItem['STATE']['PRICE_OLD']>0):?>
                <div class="catg__price-old"><?=\SaleFormatCurrency($arItem['STATE']['PRICE_OLD'], 'RUB');?></div>
            <?endif;?>
        </div>
        <div class="catg__price-block-gr">
            <?=\SaleFormatCurrency($arItem['MOD_PRICE_100_G'], 'RUB');?>/100 г
        </div>
        <div class="catg__avail-block">
            <?if($arItem['STATE']['TEXT']!=''):?>
                <div class="catg__avail"><?=$arItem['STATE']['TEXT']?></div>
            <?endif;?>
            <?if($arItem['PROPERTIES']['ASKARON_REVIEWS_AVERAGE']['VALUE']!=''):?>
            <div class="catg__raiting">
                <div class="catg__stars">
                    <?for($i=1;$i<=5;$i++):?>
                        <div class="catg__star<?if($i<=$arItem['MOD_REVIEW_AVERAGE']):?> icon-1b_star_full<?else:?> icon-1a_star<?endif;?>"></div>
                    <?endfor;?>
                </div>
                <span class="catg__raiting-cnt"><?=number_format($arItem['PROPERTIES']['ASKARON_REVIEWS_AVERAGE']['VALUE'],1)?></span>
            </div>
            <?endif;?>
        </div>
        <a class="catg__name-bl" href="<?=$arItem['DETAIL_PAGE_URL']?>">
            <span class="catg__name is-rus"><?=$arItem['NAME']?></span>
            <span class="catg__name is-eng"><?=$arItem['PROPERTIES']['NAME_ENG']['VALUE']?></span>
        </a>
        <div class="catg__short"><?=$arItem['PROPERTIES']['VKUS']['VALUE_FORMATTED']?></div>
        <div class="catg__select-block">
            <div class="cst-select-list js-sort-list"><span
                    class="cst-select-current icon-1h_galka"><?=$arItem['PROPERTIES']['WEIGHT']['VALUE']?> г</span>
                <div class="cst-select" name="gr">
                    <?foreach ($arItem['PROPERTIES']['WEIGHT_VAR_AR'] as $id=>$weight):?>
                        <div class="cst-select-option js-list-card" data-cur_id="<?=$arItem['ID']?>" data-action="list_card" data-id="<?=$id?>" data-weight="<?=$weight?>"><?=$weight?> г</div>
                    <?endforeach;?>
                </div>
            </div>
            <?/*foreach ($arResult['TMPL_PROPS_DOP_OPTIONS'] as $dopProp=>$dopValue):*/?><!--
                <?/*if($arItem['PROPERTIES'][$dopProp]['VALUE']!=''):*/?>
                    <div class="cst-select-list js-sort-list">
                        <?/*if($_SESSION['bp_cache']['bp_user']['products'][$arItem['ID']]['dop_props'][$dopProp] != ''){
                            $def_value = $_SESSION['bp_cache']['bp_user']['products'][$arItem['ID']]['dop_props'][$dopProp];
                    }
                    else{
                        $def_value = $dopValue['DEFAULT_VALUE'];
                    }*/?>
                        <span class="cst-select-current icon-1h_galka"><?/*=$def_value*/?></span>
                        <div class="cst-select js-sort-list" name="op_<?/*=$dopProp*/?>">
                            <?/*foreach ($arItem['PROPERTIES'][$dopProp]['VALUE'] as $keyVal=>$value):*/?>
                                <div class="cst-select-option js-do" data-action="card_params" data-cur_id="<?/*=$arItem['ID']*/?>" data-code="<?/*=$dopProp*/?>" data-name="<?/*=$dopValue['NAME_PROP']*/?>" data-value="<?/*=$value*/?>"><?/*=$value*/?><?/*=$dopValue['NAME']*/?></div>
                            <?/*endforeach;*/?>
                        </div>
                    </div>
                <?/*endif;*/?>
            --><?/*endforeach;*/?>
        </div>
        <div class="catg__control">
            <div class="catg__sravn btn-sravn"></div>
            <div class="catg__like btn-like js-do" data-state="N" data-img="<?=$arItem['DEFAULT_IMAGE']['SRC']?>" data-action="delay_change" data-id="<?=$arItem['ID']?>"></div>
            <div class="catg__btn btn <?=$arItem['STATE']['BUTTON_CLASS']?> js-do" data-action="<?=$arItem['STATE']['JS_ACTION']?>" data-id="<?=$arItem['ID']?>"
            <?foreach ($arItem['STATE']['DATA'] as $data=>$dataVal):?><?=$data?>="<?=$dataVal?>"<?endforeach;?>
            ><span><?=$arItem['STATE']['BUTTON_TEXT']?></span></div>
        </div>
    </div>
</div>
<?if($arParams['MOD_AJAX']==''):?></div><?endif;?>
