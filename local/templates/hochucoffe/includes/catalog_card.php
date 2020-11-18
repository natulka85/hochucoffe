<div class="catg__item" data-elem="<?=$arItem['ID']?>">
    <div class="catg__img js-slide-ix-block">
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
                                <div class="labels__item <?=$arItem['LABLES'][$lKey]['CLASS']?>"><?=$arItem['LABLES'][$lKey]['TEXT']?></div>
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
        <div class="catg__avail-block">
            <?if($arItem['STATE']['TEXT']!=''):?>
                <div class="catg__avail"><?=$arItem['STATE']['TEXT']?></div>
            <?endif;?>
            <div class="catg__raiting">
                <div class="catg__stars">
                    <div class="catg__star icon-1b_star_full"></div>
                    <div class="catg__star icon-1b_star_full"></div>
                    <div class="catg__star icon-1b_star_full"></div>
                    <div class="catg__star icon-1b_star_full"></div>
                    <div class="catg__star icon-1b_star_full"></div>
                </div>
                <span class="catg__raiting-cnt">5.0</span></div>
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
            <div class="cst-select-list js-sort-list"><span
                    class="cst-select-current icon-1h_galka">Малая обжарка</span>
                <div class="cst-select js-sort-list" name="ob">
                    <div class="cst-select-option" value="Малая">Малая обжарка</div>
                    <div class="cst-select-option" value="Средняя">Средняя обжарка</div>
                    <div class="cst-select-option" value="Сильная">Сильная обжарка</div>
                </div>
            </div>
        </div>
        <div class="catg__control">
            <div class="catg__sravn btn-sravn"></div>
            <div class="catg__like btn-like"></div>
            <div class="catg__btn btn <?=$arItem['STATE']['BUTTON_CLASS']?> js-do" data-action="<?=$arItem['STATE']['JS_ACTION']?>" data-id="<?=$arItem['ID']?>"><span><?=$arItem['STATE']['BUTTON_TEXT']?></span></div>
        </div>
    </div>
</div>
