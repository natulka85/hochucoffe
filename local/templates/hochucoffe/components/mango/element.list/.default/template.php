<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die(); ?>
<?/*echo "<pre>";
   print_r($arResult);
echo "</pre>";*/
$arResult['LABLES_TEMPLATE'] = [
        'LEFT' => ['HIT','NEW'],
        'RIGHT' => ['STRANA','OBJARK'],
        'CENTER' => ['ACTION'],
]?>
    <?if(count($arResult['ITEMS'])>0):?>

        <div class="catg__list">
            <?foreach ($arResult['ITEMS'] as $arItem):?>
                <div class="catg__item">
                    <div class="catg__img"><a href="<?=$arItem['DETAIL_PAGE_URL']?>">
                            <img src="<?=$arItem['DEFAULT_IMAGE']['SRC']?>" alt="<?=$arItem['DEFAULT_IMAGE']['ALT']?>">
                        </a>
                        <div class="catg__labels labels">
                            <?foreach ($arResult['LABLES_TEMPLATE'] as $pos=>$arVal):?>
                                <div class="labels__items is-<?=mb_strtolower($pos)?>">
                                    <?foreach ($arVal as $lKey):?>
                                        <?if(isset($arItem['LABLES'][$lKey])):?>
                                            <div class="labels__item <?=$arItem['LABLES'][$lKey]['CLASS']?>"><?=$arItem['LABLES'][$lKey]['TEXT']?></div>
                                        <?endif;?>
                                    <?endforeach;?>
                                </div>
                            <?endforeach;?>
                        </div>
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
                        <a class="catg__name" href="<?=$arItem['DETAIL_PAGE_URL']?>"><?=$arItem['NAME']?></a>
                        <div class="catg__short">Вишня, кокос, манго, кофе</div>
                        <div class="catg__select-block">
                            <div class="cst-select-list js-sort-list"><span
                                        class="cst-select-current icon-1h_galka">300 г</span>
                                <div class="cst-select" name="gr">
                                    <div class="cst-select-option" value="300">300 г</div>
                                    <div class="cst-select-option" value="1000">1000 г</div>
                                    <div class="cst-select-option" value="1500">1500 г</div>
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
                            <div class="catg__btn btn <?=$arItem['STATE']['CLASS']?> js-do <?=$arItem['STATE']['JS_ACTION']?>" data-id="<?=$arItem['ID']?>"><span><?=$arItem['STATE']['BUTTON_TEXT']?></span></div>
                        </div>
                    </div>
                </div>
            <?endforeach;?>
        </div>
    <?endif;?>
    <?
    if($arParams['DISPLAY_BOTTOM_PAGER']=='Y'):?>
        <?=$arResult['NAV_STRING']?>
    <?endif;?>

