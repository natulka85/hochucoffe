<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use \Bitrix\Main\Localization\Loc;
$this->setFrameMode(true);
?>
<div class="js-prod-ajax">
<div class="popup__box is-fast">
    <div class="popup__box-wrap">
        <div class="popup__close icon-2a_plus"></div>
        <div class="fast-card">

            <div class="prod__head">
                <h1 class="page-title _type-1"><?=$arResult['NAME']?></h1>
                <div class="prod__name is-eng"><?=$arResult['PROPERTIES']['NAME_ENG']['VALUE']?></div>
            </div>
            <div class="prod__control">
                <div class="prod__raiting">
                    <div class="catg__stars">
                        <?for($i=1;$i<=5;$i++):?>
                            <div class="catg__star<?if($i<=$arResult['MOD_REVIEW_AVERAGE']):?> icon-1b_star_full<?else:?> icon-1a_star<?endif;?>"></div>
                        <?endfor;?>
                    </div>
                    <a href="#reviews" class="js-anchor">отзывов <?=$arResult['PROPERTIES']['ASKARON_REVIEWS_COUNT']['VALUE']?></a></div>
                <div class="prod__link js-do" data-state="N" data-img="<?=$arResult['PREVIEW_PICTURE']['SRC']?>" data-action="delay_change" data-id="<?=$arResult['ID']?>">
                    <div class="prod__link-btn catg__like btn-like"></div>
                    <div class="prod__link-text _text">Отложить</div>
                </div>
                <div class="prod__link">
                    <div class="prod__link-btn catg__sravn btn-sravn"></div>
                    <div class="prod__link-text">В сравнение</div>
                </div>
                <div class="prod__link">
                    <div class="prod__link-btn catg__share btn-share"></div>
                    <div class="prod__link-text">Поделиться</div>
                </div>
            </div>
            <div class="prod__wrap">
                <div class="prod__top">
                    <div class="prod__left">
                        <div class="prod__image-wrap">
                            <?
                            $arTempLables = $arResult['LABLES_TEMPLATE'];?>
                            <?foreach ($arTempLables as $pos=>$arVal){
                                $cnt=0;
                                foreach ($arVal as $k=>$v){
                                    if(isset($arResult['LABLES'][$v])){
                                        $cnt++;
                                    }
                                }
                                if($cnt==0) unset($arTempLables[$pos]);
                            }?>
                            <?if(count($arTempLables)>0):?>
                                <div class="prod__labels labels">
                                    <?foreach ($arTempLables as $pos=>$arVal):?>
                                        <div class="labels__items is-<?=mb_strtolower($pos)?>">
                                            <?foreach ($arVal as $lKey):?>
                                                <?if(isset($arResult['LABLES'][$lKey])):?>
                                                    <div class="labels__item <?=$arResult['LABLES'][$lKey]['CLASS']?>">
                                                        <?if($arResult['LABLES'][$lKey]['IMG_AR']['src']!=''):?>
                                                            <div class="labels__item-wrap">
                                                                <img src="<?=$arResult['LABLES'][$lKey]['IMG_AR']['src']?>" alt="">
                                                            </div>
                                                        <?endif;?>
                                                        <span><?=$arResult['LABLES'][$lKey]['TEXT']?></span>
                                                    </div>
                                                <?endif;?>
                                            <?endforeach;?>
                                        </div>
                                    <?endforeach;?>
                                </div>
                            <?endif;?>
                            <div class="prod__image js-slick-3">
                                <?foreach ($arResult['DEFAULT_IMAGE'] as $defImg):?>
                                    <div class="prod__image-img">
                                        <a href="<?=$defImg['HREF']?>" data-fancybox="element">
                                            <img src="<?=$defImg['SRC']?>" alt="<?=$defImg['ALT']?>">
                                        </a>
                                    </div>
                                <?endforeach;?>
                            </div>
                            <div class="prod__tmbs js-slick-nav-3">
                                <?foreach ($arResult['DEFAULT_TUMB_IMAGE'] as $defImgTumb):?>
                                    <div class="prod__tmb">
                                        <div class="prod__tmb-img">
                                            <img src="<?=$defImgTumb['SRC']?>" alt="<?=$defImgTumb['ALT']?>">
                                        </div>
                                    </div>
                                <?endforeach;?>
                            </div>
                        </div>

                    </div>
                    <div class="prod__right">
                        <div class="prod__check-wrap">
                            <div class="prod__check">
                                <div class="catg__price-block">
                                    <?if($arResult['STATE']['PRICE']>0):?>
                                        <div class="catg__price"><?=\SaleFormatCurrency($arResult['STATE']['PRICE'], 'RUB')?></div>
                                    <?endif;?>
                                    <?if($arResult['STATE']['PRICE_OLD']>0):?>
                                        <div class="catg__price-old"><?=\SaleFormatCurrency($arResult['STATE']['PRICE_OLD'], 'RUB')?></div>
                                    <?endif;?>
                                </div>
                                <div class="catg__price-block-gr">
                                    <?=\SaleFormatCurrency($arResult['MOD_PRICE_100_G'], 'RUB');?>/100 г
                                </div>
                                <div class="prod__avail-block">
                                    <div class="prod__avail"><?=$arResult['STATE']['TEXT']?></div>
                                </div>
                                <div class="prod__deliv"><span>Бесплатная</span> доставка при заказе от 5000 ₽</div>
                                <div class="prod__deliv-info"><span>Доставка на:</span> 26.12.2020</div>
                                <div class="prod__btn-wrap">
                                    <div class="prod__btn btn is-buy js-do" data-id="<?=$arResult['ID']?>" data-action="basket_change" <?foreach ($arResult['STATE']['DATA'] as $data=>$dataVal):?><?=$data?>="<?=$dataVal?>"<?endforeach;?>>В корзину</div>
                            </div>
                        </div>
                        <div class="prod__main">

                            <div class="prod__main-choose-bl is-weight">
                                <div class="prod__main-title">Вес упаковки:</div>
                                <div class="prod__fields">
                                    <?foreach ($arResult['PROPERTIES']['WEIGHT_VAR_AR'] as $id=>$weight):?>
                                        <div class="prod__field">
                                            <div class="prod__form-btn-choose js-do <?if($weight==$arResult['PROPERTIES']['WEIGHT']['VALUE']):?>is-active<?endif;?>" data-cur_id="<?=$arResult['ID']?>" data-id="<?=$id?>" data-weight="<?=$weight?>" data-fast="Y" data-action="product_card" data-template="FAST_VIEW"><?=$weight?> г</div>
                                        </div>
                                    <?endforeach;?>
                                </div>
                            </div>
                            <?foreach ($arResult['TMPL_PROPS_DOP_OPTIONS'] as $dopProp=>$dopValue):?>
                                <div class="prod__main-choose-bl is-fri">
                                    <div class="prod__main-title"><?=$arResult['PROPERTIES'][$dopProp]['NAME']?>:</div>
                                    <div class="prod__fields">
                                        <?foreach ($arResult['PROPERTIES'][$dopProp]['VALUE'] as $keyVal=>$value):?>
                                            <div class="prod__field">
                                                <div class="prod__form-btn-choose js-do<?if($arResult['STATE']['DATA_AR'][$dopProp][2] == $value):?> is-active
                            <?elseif ($value == $dopValue['DEFAULT_VALUE']):?> is-active<?endif;?>" data-action="card_params"  data-cur_id="<?=$arResult['ID']?>" data-code="<?=$dopProp?>" data-name="<?=$dopValue['NAME_PROP']?>" data-value="<?=$value?>">
                                                    <?=$value?></div>
                                            </div>
                                        <?endforeach;?>
                                    </div>
                                </div>
                            <?endforeach;?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="prod__bottom">
                <div class="fast-card__description">
                    <?=$arResult['DETAIL_TEXT_SHORT']?>
                </div>
                <div class="prod__main-char">
                    <div class="prod__main-title">Основные характеристики:</div>
                    <? foreach ($arResult['TMPL_PROPS'] as $TMPL_PROP):?>
                        <?if($arResult['PROPERTIES'][$TMPL_PROP]['VALUE']!=''):?>
                            <div class="prod__main-bl">
                                <div class="prod__main-param"><span class="is-wrap"><?=$arResult['PROPERTIES'][$TMPL_PROP]['NAME']?></span></div>
                                <?if($arResult['PROPERTIES'][$TMPL_PROP]['FILTER_LINK']!=''):?>
                                    <?$i=0;
                                    ?>
                                    <div class="prod__main-val">
                                <span class="is-wrap">
                                <?foreach ($arResult['PROPERTIES'][$TMPL_PROP]['FILTER_LINK'] as $value=>$link):?>
                                    <?
                                    $i++;?>
                                    <a class="is-link"
                                       href="<?=$link?>"
                                       target="_blank"
                                       title="Посмотреть другие товары данной категории"><span><?if($i>1):?><?=mb_strtolower($value)?><?else:?><?=$value?><?endif;?></span><?if($i!=count($arResult['PROPERTIES'][$TMPL_PROP]['FILTER_LINK'])):?><?echo', ';?><?endif;?>
                                    </a>
                                <?endforeach;?>
                                    </span>
                                    </div>
                                <?else:?>
                                    <div class="prod__main-val"><span class="is-wrap"><?=$arResult['PROPERTIES'][$TMPL_PROP]['VALUE']?></span></div>
                                <?endif;?>
                            </div>
                        <?endif;?>
                    <?endforeach;?>
                </div>
            </div>
            <a href="<?=$arResult['DETAIL_PAGE_URL']?>" class="fast-card__btn btn is-blue">Подробнее о товаре</a>
            </div>

        </div>
    </div>
</div>
</div>

