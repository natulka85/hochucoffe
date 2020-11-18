<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use \Bitrix\Main\Localization\Loc;
$this->setFrameMode(true);
?>
<div class="js-prod-ajax">
<div class="page-block-head"><h1 class="page-title _type-1"><?=$arResult['NAME']?></h1></div>
<div class="prod__control">
    <div class="prod__raiting">
        <div class="catg__stars">
            <div class="catg__star icon-1b_star_full"></div>
            <div class="catg__star icon-1b_star_full"></div>
            <div class="catg__star icon-1b_star_full"></div>
            <div class="catg__star icon-1b_star_full"></div>
            <div class="catg__star icon-1b_star_full"></div>
        </div>
        <a href="#">10 отзывов</a></div>
    <div class="prod__link">
        <div class="prod__link-btn catg__like btn-like"></div>
        <div class="prod__link-text">В избранное</div>
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
        <div class="prod__image-wrap">
            <div class="prod__image js-slick-3">
                <?foreach ($arResult['DEFAULT_IMAGE'] as $defImg):?>
                    <div class="prod__image__img">
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
        <div class="prod__main">
            <div class="labels">
                <div class="labels__item is-new"></div>
                <div class="labels__item is-coffe is-three"></div>
                <div class="labels__item is-country"><img src="../../images/develop/country.png">
                </div>
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
            <div class="prod__main-choose-bl is-fri">
                <div class="prod__main-title">Степень обжарки:</div>
                <div class="prod__fields">
                    <div class="prod__field">
                        <div class="prod__form-btn-choose is-active">Малая</div>
                    </div>
                    <div class="prod__field">
                        <div class="prod__form-btn-choose">Средняя</div>
                    </div>
                    <div class="prod__field">
                        <div class="prod__form-btn-choose">Сильная</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="prod__check-wrap">
            <div class="prod__check is-st-shadow">
                <div class="catg__price-block">
                    <?if($arResult['STATE']['PRICE']>0):?>
                        <div class="catg__price"><?=\SaleFormatCurrency($arResult['STATE']['PRICE'], 'RUB')?></div>
                    <?endif;?>
                    <?if($arResult['STATE']['PRICE_OLD']>0):?>
                        <div class="catg__price-old"><?=\SaleFormatCurrency($arResult['STATE']['PRICE_OLD'], 'RUB')?></div>
                    <?endif;?>
                </div>
                <div class="prod__avail-block">
                    <div class="prod__avail"><?=$arResult['STATE']['TEXT']?></div>
                </div>
                <div class="prod__deliv"><span>Бесплатная</span> доставка при заказе от 5000 ₽</div>
                <div class="prod__link-bl">Хочу дешевле!</div>
                <div class="prod__deliv-info"><span>Доставка на:</span> 26.12.2020</div>
                <div class="prod__btn-wrap">
                    <div class="prod__btn btn is-brown-light is-buy js-do" data-id="<?=$arResult['ID']?>" data-action="basket_change">В корзину</div>
                </div>
            </div>
        </div>
    </div>
    <div class="prod__bottom">
        <div class="prod__des-block">
            <div class="prod__des-btn js-slide-btn icon-1h_galka">Описание</div>
            <div class="prod__des-content js-slide-content"><?=$arResult['DETAIL_TEXT']?></div>
        </div>
        <div class="prod__des-block">
            <div class="prod__des-btn js-slide-btn icon-1h_galka">Отзывы:</div>
            <div class="prod__des-content js-slide-content">
                <div class="prod__reviews">
                    <div class="prod__reviews-l">
                        <div class="prod__btn is-review btn is-brown-light">Написать отзыв</div>
                        <div class="prod__reviews-item">
                            <div class="prod__reviews-name">
                                <div class="prod__reviews-name-m">Сергей Чураков</div>
                                <div class="prod__reviews-date">14.10.2020 18:34</div>
                            </div>
                            <div class="catg__stars">
                                <div class="catg__star icon-1b_star_full"></div>
                                <div class="catg__star icon-1b_star_full"></div>
                                <div class="catg__star icon-1b_star_full"></div>
                                <div class="catg__star icon-1b_star_full"></div>
                                <div class="catg__star icon-1b_star_full"></div>
                            </div>
                            <div class="prod__reviews-text">Разнообразный и богатый опыт укрепление
                                и развитие структуры способствует подготовки и реализации системы
                                обучения кадров, соответствует насущным потребностям. Товарищи!
                                постоянное информационно-пропагандистское обеспечение нашей
                                деятельности играет важную роль в формировании позиций, занимаемых
                                участниками в отношении поставленных задач. Разнообразный и богатый
                                опыт новая модель организационной деятельности способствует
                                подготовки и реализации новых предложений.
                            </div>
                        </div>
                        <div class="prod__reviews-item">
                            <div class="prod__reviews-name">
                                <div class="prod__reviews-name-m">Сергей Чураков</div>
                                <div class="prod__reviews-date">14.10.2020 18:34</div>
                            </div>
                            <div class="catg__stars">
                                <div class="catg__star icon-1b_star_full"></div>
                                <div class="catg__star icon-1b_star_full"></div>
                                <div class="catg__star icon-1b_star_full"></div>
                                <div class="catg__star icon-1b_star_full"></div>
                                <div class="catg__star icon-1b_star_full"></div>
                            </div>
                            <div class="prod__reviews-text">Разнообразный и богатый опыт укрепление
                                и развитие структуры способствует подготовки и реализации системы
                                обучения кадров, соответствует насущным потребностям. Товарищи!
                                постоянное информационно-пропагандистское обеспечение нашей
                                деятельности играет важную роль в формировании позиций, занимаемых
                                участниками в отношении поставленных задач. Разнообразный и богатый
                                опыт новая модель организационной деятельности способствует
                                подготовки и реализации новых предложений.
                            </div>
                        </div>
                        <div class="prod__reviews-item">
                            <div class="prod__reviews-name">
                                <div class="prod__reviews-name-m">Сергей Чураков</div>
                                <div class="prod__reviews-date">14.10.2020 18:34</div>
                            </div>
                            <div class="catg__stars">
                                <div class="catg__star icon-1b_star_full"></div>
                                <div class="catg__star icon-1b_star_full"></div>
                                <div class="catg__star icon-1b_star_full"></div>
                                <div class="catg__star icon-1b_star_full"></div>
                                <div class="catg__star icon-1b_star_full"></div>
                            </div>
                            <div class="prod__reviews-text">Разнообразный и богатый опыт укрепление
                                и развитие структуры способствует подготовки и реализации системы
                                обучения кадров, соответствует насущным потребностям. Товарищи!
                                постоянное информационно-пропагандистское обеспечение нашей
                                деятельности играет важную роль в формировании позиций, занимаемых
                                участниками в отношении поставленных задач. Разнообразный и богатый
                                опыт новая модель организационной деятельности способствует
                                подготовки и реализации новых предложений.
                            </div>
                        </div>
                    </div>
                    <div class="prod__reviews-r">
                        <div class="prod__reviews-res">
                            <div class="prod__reviews-mark">5.0</div>
                            <div class="catg__stars">
                                <div class="catg__star icon-1b_star_full"></div>
                                <div class="catg__star icon-1b_star_full"></div>
                                <div class="catg__star icon-1b_star_full"></div>
                                <div class="catg__star icon-1b_star_full"></div>
                                <div class="catg__star icon-1b_star_full"></div>
                            </div>
                            <div class="prod__reviews-inf">На основании оценок<br>10 покупателей
                            </div>
                            <div class="prod__reviews-cnt">
                                <div class="prod__reviews-cnt-item"><span>5</span>
                                    <div class="prod__reviews-cnt-points">
                                        <div class="prod__reviews-cnt-point is-active"></div>
                                        <div class="prod__reviews-cnt-point is-active"></div>
                                        <div class="prod__reviews-cnt-point is-active"></div>
                                        <div class="prod__reviews-cnt-point is-active"></div>
                                        <div class="prod__reviews-cnt-point is-active"></div>
                                        <div class="prod__reviews-cnt-point is-active"></div>
                                        <div class="prod__reviews-cnt-point is-active"></div>
                                        <div class="prod__reviews-cnt-point is-active"></div>
                                        <div class="prod__reviews-cnt-point is-active"></div>
                                        <div class="prod__reviews-cnt-point is-active"></div>
                                    </div>
                                    <span>100%</span></div>
                                <div class="prod__reviews-cnt-item"><span>4</span>
                                    <div class="prod__reviews-cnt-points">
                                        <div class="prod__reviews-cnt-point is-active"></div>
                                        <div class="prod__reviews-cnt-point is-active"></div>
                                        <div class="prod__reviews-cnt-point is-active"></div>
                                        <div class="prod__reviews-cnt-point is-active"></div>
                                        <div class="prod__reviews-cnt-point is-active"></div>
                                        <div class="prod__reviews-cnt-point"></div>
                                        <div class="prod__reviews-cnt-point"></div>
                                        <div class="prod__reviews-cnt-point"></div>
                                        <div class="prod__reviews-cnt-point"></div>
                                        <div class="prod__reviews-cnt-point"></div>
                                    </div>
                                    <span>50%</span></div>
                                <div class="prod__reviews-cnt-item"><span>3</span>
                                    <div class="prod__reviews-cnt-points">
                                        <div class="prod__reviews-cnt-point"></div>
                                        <div class="prod__reviews-cnt-point"></div>
                                        <div class="prod__reviews-cnt-point"></div>
                                        <div class="prod__reviews-cnt-point"></div>
                                        <div class="prod__reviews-cnt-point"></div>
                                        <div class="prod__reviews-cnt-point"></div>
                                        <div class="prod__reviews-cnt-point"></div>
                                        <div class="prod__reviews-cnt-point"></div>
                                        <div class="prod__reviews-cnt-point"></div>
                                        <div class="prod__reviews-cnt-point"></div>
                                    </div>
                                    <span>0%</span></div>
                                <div class="prod__reviews-cnt-item"><span>2</span>
                                    <div class="prod__reviews-cnt-points">
                                        <div class="prod__reviews-cnt-point"></div>
                                        <div class="prod__reviews-cnt-point"></div>
                                        <div class="prod__reviews-cnt-point"></div>
                                        <div class="prod__reviews-cnt-point"></div>
                                        <div class="prod__reviews-cnt-point"></div>
                                        <div class="prod__reviews-cnt-point"></div>
                                        <div class="prod__reviews-cnt-point"></div>
                                        <div class="prod__reviews-cnt-point"></div>
                                        <div class="prod__reviews-cnt-point"></div>
                                        <div class="prod__reviews-cnt-point"></div>
                                    </div>
                                    <span>0%</span></div>
                                <div class="prod__reviews-cnt-item"><span>1</span>
                                    <div class="prod__reviews-cnt-points">
                                        <div class="prod__reviews-cnt-point"></div>
                                        <div class="prod__reviews-cnt-point"></div>
                                        <div class="prod__reviews-cnt-point"></div>
                                        <div class="prod__reviews-cnt-point"></div>
                                        <div class="prod__reviews-cnt-point"></div>
                                        <div class="prod__reviews-cnt-point"></div>
                                        <div class="prod__reviews-cnt-point"></div>
                                        <div class="prod__reviews-cnt-point"></div>
                                        <div class="prod__reviews-cnt-point"></div>
                                        <div class="prod__reviews-cnt-point"></div>
                                    </div>
                                    <span>0%</span></div>
                            </div>
                        </div>
                    </div>
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

</div>
