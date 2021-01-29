<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use \Bitrix\Main\Localization\Loc;
$this->setFrameMode(true);
?>
<div class="js-prod-ajax">
    <div class="prod__content">
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
        <a class="link js-anchor" href="#reviews">отзывов <?=$arResult['PROPERTIES']['ASKARON_REVIEWS_COUNT']['VALUE']?></a></div>
    <div class="prod__link js-do" data-state="N" data-img="<?=$arResult['PREVIEW_PICTURE']['SRC']?>" data-action="delay_change" data-id="<?=$arResult['ID']?>">
        <div class="prod__link-btn btn-like"></div>
        <div class="prod__link-text _text">Отложить</div>
    </div>
    <div class="prod__link" title="В разработке" style="color: #b2b5b7 !important;cursor: default">
        <div class="prod__link-btn btn-sravn"></div>
        <div class="prod__link-text">В сравнение</div>
    </div>
    <div class="prod__link">
        <div class="prod__link-btn btn-share"></div>
        <div class="prod__link-text">Поделиться</div>
    </div>
</div>
<div class="prod__wrap">
    <div class="prod__top">
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
            <div class="prod__image swiper-container">
                <div class="swiper-wrapper">
                    <?foreach ($arResult['DEFAULT_IMAGE'] as $defImg):?>
                        <div class="prod__image-img swiper-slide">
                            <a href="<?=$defImg['HREF']?>" data-fancybox="element">
                                <img src="<?=$defImg['SRC']?>" alt="<?=$defImg['ALT']?>">
                            </a>
                        </div>
                    <?endforeach;?>
                </div>
                <div class="swiper-pagination swiper__bullet"></div>
                <div class="swiper__btn swiper-button-prev"></div>
                <div class="swiper__btn swiper-button-next"></div>
            </div>
            <div class="prod__tmbs swiper-container">
                <div class="swiper-wrapper">
                    <?foreach ($arResult['DEFAULT_TUMB_IMAGE'] as $defImgTumb):?>
                        <div class="prod__tmb swiper-slide">
                            <div class="prod__tmb-img">
                                <img src="<?=$defImgTumb['SRC']?>" alt="<?=$defImgTumb['ALT']?>">
                            </div>
                        </div>
                    <?endforeach;?>
                </div>
            </div>
        </div>
        <div class="prod__main">
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
            <div class="prod__main-choose-bl is-weight">
                <div class="prod__main-title">Вес упаковки:</div>
                <div class="prod__fields">
                    <?foreach ($arResult['PROPERTIES']['WEIGHT_VAR_AR'] as $id=>$weight):?>
                        <div class="prod__field">
                            <div class="prod__form-btn-choose js-do <?if($weight==$arResult['PROPERTIES']['WEIGHT']['VALUE']):?>is-active<?endif;?>" data-cur_id="<?=$arResult['ID']?>" data-id="<?=$id?>" data-weight="<?=$weight?>" data-action="product_card"><?=$weight?> г</div>
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
                            <?if($arResult['POMOL_TIPS'][$value]!=''):?>
                                <div class="prod__field-tip">
                                    <?foreach ($arResult['POMOL_TIPS'][$value] as $tip):?>
                                        <div class="prod__field-icon <?=$arResult['POMOL_TIPS_TEMP'][$tip]['icon']?>" title="<?=$arResult['POMOL_TIPS_TEMP'][$tip]['name']?>"></div>
                                    <?endforeach;?>
                                </div>
                            <?endif;?>
                            <div class="prod__form-btn-choose js-do<?if($arResult['STATE']['DATA_AR'][$dopProp][2] == $value):?> is-active
                            <?elseif ($value == $dopValue['DEFAULT_VALUE']):?> is-active<?endif;?>" data-action="card_params"  data-cur_id="<?=$arResult['ID']?>" data-code="<?=$dopProp?>" data-name="<?=$dopValue['NAME_PROP']?>" data-value="<?=$value?>">
                                <?=$value?></div>
                        </div>
                        <?endforeach;?>
                    </div>
                </div>
            <?endforeach;?>
        </div>
        <div class="prod__check-wrap">
            <div class="prod__check is-st-shadow">
                <div class="catg__price-block">
                    <?if($arResult['STATE']['PRICE']>0):?>
                        <div class="catg__cur-w">
                            <div class="catg__price"><?=\SaleFormatCurrency($arResult['STATE']['PRICE'], 'RUB')?></div>
                            <div class="catg__price-block-gr">
                                <?=\SaleFormatCurrency($arResult['MOD_PRICE_100_G'], 'RUB');?>/100г
                            </div>
                        </div>
                    <?endif;?>
                    <?if($arResult['STATE']['PRICE_OLD']>0):?>
                        <div class="catg__old-w">
                            <div class="catg__price-old"><?=\SaleFormatCurrency($arResult['STATE']['PRICE_OLD'], 'RUB')?></div>
                        <?$lKey='ACTION'?>
                            <div class="labels__item <?=$arResult['LABLES'][$lKey]['CLASS']?>">
                                <span><?=$arResult['LABLES'][$lKey]['TEXT']?></span>
                            </div>
                        </div>
                    <?endif;?>
                </div>
                <div class="prod__avail-block">
                    <div class="prod__avail"><?=$arResult['STATE']['TEXT']?></div>
                </div>
                <?if($arResult['STATE']['BUTTON_TEXT']!=''):?>
                <div class="prod__deliv"><span>Бесплатная</span> доставка при заказе от 5000 ₽</div>
                <div class="prod__link-bl js-do" data-action="form_cheaper">Хочу дешевле!</div>
                <div class="prod__deliv-info"><span>Доставка на:</span> 26.12.2020</div>
                <div class="prod__btn-wrap">

                    <div class="prod__btn btn is-buy js-do" data-id="<?=$arResult['ID']?>" data-action="basket_change" <?foreach ($arResult['STATE']['DATA'] as $data=>$dataVal):?><?=$data?>="<?=$dataVal?>"<?endforeach;?>>В корзину</div>

                </div>
            <?endif;?>
            </div>
        </div>
    </div>
    <div class="prod__bottom">
        <div class="prod__des-block">
            <div class="prod__des-btn js-slide-btn icon-2a_plus is-active">Описание</div>
            <div class="js-slide-content" data-show_more="block" data-show_more_height="400">
                <div class="prod__des-content" data-show_more="content"><?=$arResult['~DETAIL_TEXT']?></div>
                <div class="prod__btn-more">
                    <span class="js-show-more-btn" data-show_more_btnshow="Подробнее" data-show_more_btnhide="Свернуть">Подробнее</span>
                </div>

            </div>
        </div>
        <div class="prod__des-block">
            <div class="prod__des-btn js-slide-btn icon-2a_plus">Важное про кофе</div>
            <div class="prod__des-content js-slide-content" style="display: none;">
                <div class="main-text__h">
                    Как хранить кофе...
                </div>
                <p>
                    Хранить зерновое кофе можно в обычном кухонном шкафу, но температура не должна превышать 25 градусов. Избегайте попадание тепла и прямых солнечных лучей, ограничьте попадание воздуха в упаковку, за исключением специального клапана на самой пачке.<br>
                    При соблюдении этих простых правил, кофе будет радовать Вас теми неповторимыми вкусовыми нотами и ароматами, которые позволит раскрыть выбранный Вами способ приготовления.
                </p>

                <div class="main-text__border">
                    <div class="main-text__border-name">ВАЖНО!</div>

                    Кофе, после помола, может терять свою свежесть.  Необходимо тщательно соблюдать условия хранения и молоть только нужное вам количество зерен для приготовления напитка.
                    При соблюдении этих простых правил, кофе будет радовать Вас теми неповторимыми вкусовыми нотами и ароматами, которые позволит раскрыть выбранный Вами способ приготовления.
                </div>
            </div>
        </div>
        <div class="prod__des-block">
            <?
            $display = 'none';
            if(count($arResult['MOD_REVIEWS'])==0) {$display='block';}?>
            <div class="prod__des-btn js-slide-btn icon-2a_plus is-active" id="reviews">Отзывы:</div>
            <div class="prod__des-content js-slide-content">
                <div class="prod__reviews">
                    <div class="prod__reviews-l">
                        <div class="prod__btn is-review btn is-white-bege js-toggle-rf">Написать отзыв</div>
                        <div class="prod__review-form" style="display:<?=$display;?>">
                            <form action="" class="review-form">
                                <div class="review-form__title">Отзыв о <?=$arResult['NAME']?></div>
                                <input type="hidden" name="action" value="review_card"/>
                                <input type="hidden" name="elid" value="<?=$arResult['ID']?>"/>
                                <div class="review-form__fields">
                                    <div class="review-form__field is-stars">
                                        <input type="hidden" name="grade" value="5"/>
                                        <span class="review-form__label">Ваша оценка</span>
                                        <div class="catg__stars">
                                            <div class="catg__star is-on"></div>
                                            <div class="catg__star is-on"></div>
                                            <div class="catg__star is-on"></div>
                                            <div class="catg__star is-on"></div>
                                            <div class="catg__star is-on"></div>
                                        </div>
                                    </div>
                                    <div class="review-form__field">
                                        <input class="review-form__input" placeholder="Ваше имя" value="" name="name">
                                        <div class="error">Некорректное имя</div>
                                    </div>
                                    <div class="review-form__field">
                                        <textarea class="review-form__textarea" placeholder="Оставить отзыв" value="" name="review"></textarea>
                                        <span class="review-form__label"></span>
                                        <div class="error"><?=$arResult['ERRORS']['email']?></div>
                                    </div>
                                    <div class="review-form__btn-wrap">
                                        <button class="review-form__btn btn is-bege">Отправить отзыв</button>
                                        <div class="review-form__btn btn is-white-bege js-toggle-rf">Отмена</div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <?if(count($arResult['MOD_REVIEWS'])>0):?>
                            <?foreach ($arResult['MOD_REVIEWS'] as $reviw):?>
                                <div class="prod__reviews-item">
                                    <div class="prod__reviews-name">
                                        <div class="prod__reviews-name-m"><?=$reviw['AUTHOR_NAME']?></div>
                                        <div class="prod__reviews-date"><?=$reviw['DATE']?></div>
                                    </div>
                                    <div class="catg__stars">
                                        <?for($i=1;$i<=5;$i++):?>
                                            <div class="catg__star<?if($i<=$reviw['GRADE']):?> icon-1b_star_full<?else:?> icon-1a_star<?endif;?>"></div>
                                        <?endfor;?>
                                    </div>
                                    <div class="prod__reviews-text"><?=$reviw['TEXT']?>
                                    </div>
                                    <?if($reviw['ANSWER']!=''):?>
                                        <div class="prod__r-answer">
                                            <div class="prod__r-answer-text">
                                                <?=$reviw['ANSWER']?>
                                            </div>
                                            <div class="prod__r-man">
                                                <div class="prod__r-man-img">
                                                    <img src="<?=$reviw['MANAGER']['PICTURE']['src']?>" alt="<?=$reviw['MANAGER']['PROPERTY_POSITION_VALUE']?>">
                                                </div>
                                                <div class="prod__r-pos"><?=$reviw['MANAGER']['PROPERTY_POSITION_VALUE']?></div>
                                            </div>
                                        </div>
                                    <?endif;?>
                                </div>
                            <?endforeach;?>
                        <?endif;?>
                    </div>
                    <div class="prod__reviews-r">
                        <?if($arResult['PROPERTIES']['ASKARON_REVIEWS_COUNT']['VALUE']>0):?>
                            <div class="prod__reviews-res">
                                <div class="prod__reviews-mark"><?=number_format($arResult['PROPERTIES']['ASKARON_REVIEWS_AVERAGE']['VALUE'],2)?></div>
                                <div class="catg__stars">
                                    <?for($i=1;$i<=5;$i++):?>
                                        <div class="catg__star<?if($i<=$arResult['MOD_REVIEW_AVERAGE']):?> icon-1b_star_full<?else:?> icon-1a_star<?endif;?>"></div>
                                    <?endfor;?>
                                </div>
                                <div class="prod__reviews-inf">На основании оценок<br><?=$arResult['PROPERTIES']['ASKARON_REVIEWS_COUNT']['VALUE']?> покупателей
                                </div>
                                <div class="prod__reviews-cnt">
                                    <?$gradeSum = array_sum($arResult['MOD_REVIEWS_RES']);?>
                                    <?foreach ($arResult['MOD_REVIEWS_RES'] as $grade=>$gradeCnt):?>
                                        <div class="prod__reviews-cnt-item"><span><?=$grade?></span>
                                            <div class="prod__reviews-cnt-points">
                                                <?
                                                    $procCnt = round($gradeCnt * 10 / $gradeSum,0);
                                                ?>
                                                <?for($i=1;$i<=10;$i++):?>
                                                    <div class="prod__reviews-cnt-point<?if($i<=$procCnt):?> is-active<?endif;?>"></div>
                                                <?endfor;?>
                                            </div>
                                            <span><?=round($gradeCnt * 10 / $gradeSum*10,2)?>%</span></div>
                                    <?endforeach;?>
                                </div>
                            </div>
                        <?endif;?>
                    </div>
                </div>
            </div>
        </div>

        <div class="prod__des-block">
            <div class="prod__des-btn js-slide-btn icon-2a_plus">Доставка и оплата</div>
            <div class="prod__des-content js-slide-content" style="display: none;">
                <p>
                    Мы доставляем кофе прямо до вашего дома бережно и в короткие сроки.
                    Подробнее о способах и сроках доставки вы можете узнать ЗДЕСЬ (ссылка на страницу Доставка и Оплата)
                </p>
            </div>
        </div>



    </div>

</div>
</div>

<section class="page-kartochka__screen-two">
    <?include_once($_SERVER['DOCUMENT_ROOT'].'/local/php_interface/ajax/same_collection.php');?>
</section>
<section class="page-kartochka__screen-three">
    <?include_once($_SERVER['DOCUMENT_ROOT'].'/local/php_interface/ajax/analogs.php');?>
</section>
<section class="page-kartochka__screen-four">
    <?
    $APPLICATION->IncludeComponent(
        "mango:cache.set",
        "cloud-element",
        [
            //'SECTION_ID' => $arResult['SECTION_ID'],
            'PROPS' => $arResult['PROPERTIES'],
        ],
        false
    );
    ?>
</section>
<script>
    $(function (){
        if(window.outerWidth > 640){
            StickyMy($('.prod__content'), $('.prod__check'),10);
        }
    })
</script>

</div>
