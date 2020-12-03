<?if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(false);
global $BP_TEMPLATE,$APPLICATION;
?>
<?if($_POST["is_ajax_post"] != "Y"): ?>
<form action="<?=$APPLICATION->GetCurPage();?>" method="POST" name="ORDER_FORM" id="ORDER_FORM" enctype="multipart/form-data">
<?=bitrix_sessid_post()?>
<div id="order_form_content">
<?else:?>
    <?$APPLICATION->RestartBuffer();?>
<?endif;?>
        <input type="hidden" name="is_ajax_post" id="is_ajax_post" value="Y">
        <input type="hidden" value="<?=$_SESSION["ref"]?>" name="REFFERER">
        <input type="hidden" value="<?=$_SESSION["bp_cache"]['bp_user']["city"]?>" name="REG-CITY">
        <?if($arResult['COUPON']!='' && $arResult['DISCOUNT']>0):?>
        <input type="hidden" value="<?=$arResult['COUPON']?>" name="PROMOCODE">
        <?endif?>

        <?if($arResult['ORDER_ID']):?>
        <? include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/confirm.php");
        if($_POST["is_ajax_post"] == "Y")
            die();?>
    <?elseif(
        !isset($arResult['ITEMS'])
        /*&& !isset($_SESSION['bp_cache']['bp_user']['rest'])*/
    ):?>
        <?
        include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/empty.php");
        if($_POST["is_ajax_post"] == "Y")
            die();?>
        <?else:?>

<div class="basket__content-wr">
    <div class="basket__content">
        <div class="basket__left">
            <div class="basket__list">
                <?foreach($arResult['ITEMS'] as $key=>$arItem):?>
                    <div class="basket__item">
                        <div class="basket__el">
                            <div class="basket__el-pic"><img src="<?=$arItem['PREVIEW_PICTURE']?>">
                            </div>
                            <div class="basket__el-name-wrap">
                                <div class="basket__el-name"><?=$arItem['NAME']?></div>
                                <div class="basket__elem-props">
                                    <?foreach ($arResult['TMPL_PROPS_DOP_OPTIONS'] as $dopProp=>$dopValue):?>
                                        <div class="basket__gram">
                                            <div class="cst-select-list js-sort-list">
                                            <span class="cst-select-current icon-1h_galka"><?=$arItem['PROPS'][$dopProp]['VALUE']?></span>
                                                <div class="cst-select" name="op_<?=$dopProp?>">
                                                    <?foreach ($arItem['PROPERTIES'][$dopProp]['VALUE'] as $keyVal=>$value):?>
                                                        <div class="cst-select-option js-do" data-action="basket_change" data-dop_<?=$dopProp?>="<?=$dopProp.'||'.$dopValue['NAME_PROP'].'||'.$value?>"
                                                        data-bid="<?=$arItem['BASKET_ID']?>" data-q="<?=$arItem['QUANTITY']?>"><?=$value?><?=$dopValue['NAME']?></div>
                                                    <?endforeach;?>
                                                </div>
                                            </div>
                                        </div>
                                    <?endforeach;?>
                                </div>
                            </div>
                            <div class="basket__price">
                                <div class="basket__price-c"><?=\SaleFormatCurrency(($arItem['PRICE']*$arItem['QUANTITY']), 'RUB')?></div>
                                <?if($arItem['PRICE_2'] != ''):?>
                                    <div class="basket__price-old"><?=\SaleFormatCurrency(($arItem['PRICE_2']*$arItem['QUANTITY']), 'RUB')?></div>
                                <?endif;?>
                            </div>
                        </div>
                        <div class="basket__el-control">
                            <div class="basket__cnt">
                                <div class="count-block js-counter">
                                    <div class="count-block__btn js-minus icon-2b_minus"></div>
                                    <input class="count-block__value" value="<?=$arItem['QUANTITY']?>" data-bid="<?=$arItem['BASKET_ID']?>" data-action="basket_change">
                                    <div class="count-block__btn js-plus icon-2a_plus"></div>
                                </div>
                            </div>
                            <div class="basket__cont-btn-wrap">
                                <div class="basket__cont-btn btn-like js-do" data-state="N" data-img="<?=$arItem['PREVIEW_PICTURE']?>" data-action="delay_change" data-id="<?=$arItem['PRODUCT_ID']?>"><span class="_text">Отложить</span></div>
                                <div class="basket__cont-btn btn-remove js-do" data-action="basket_change" data-default_q="<?=$arItem['QUANTITY']?>" data-q="0" data-bid="<?=$arItem['BASKET_ID']?>"><span>Удалить</span></div>
                            </div>
                        </div>
                    </div>
                <?endforeach;?>
            </div>
            <div class="basket__promo">
            <input class="basket__promo-input" placeholder="Промокод" name="promocode" value="<?=$arResult['show_promo']?>">
                <div class="basket__promo-btn js-promocode" name="ok" value="Применить">Применить</div>
            </div>
        </div>
        <div class="basket__right">
            <div class="<?if($_REQUEST['result_class']!=''):?><?=$_REQUEST['result_class']?><?else:?>basket__result<?endif;?>">
                <div class="basket__res-block">
                    <div class="basket__name is-main">Итого:</div>
                    <div class="basket__value is-main"><?=\SaleFormatCurrency(($arResult['PRICE']+$arResult['DELIVERY_PRICE']), 'RUB')?></div>
                </div>
                <div class="basket__res-block">
                    <div class="basket__name">Товары, <?=$arResult['CURRENT']['QUANTITY']?> шт.</div>
                    <div class="basket__value"><?=\SaleFormatCurrency($arResult['PRICE'] + $arResult['CURRENT']['DISC'], 'RUB')?></div>
                </div>
                <div class="basket__res-block">
                    <div class="basket__name">Скидка</div>
                    <div class="basket__value">-<?=\SaleFormatCurrency($arResult['CURRENT']['DISC'], 'RUB')?></div>
                </div>
                <div class="basket__res-block">
                    <div class="basket__name">Доставка</div>
                    <div class="basket__value"><?if($arResult['DELIVERY_PRICE']>0):?><?=\SaleFormatCurrency($arResult['DELIVERY_PRICE'], 'RUB')?> <?else:?>0<?endif;?></div>
                </div>
                <div class="basket__res-op-wrap basket__full-order" <?if($_REQUEST['forder_ok']=='F'):?> style="display: none;" <?endif;?>>
                    <div class="basket__res-op">
                        <div class="basket__res-op-name">Доставка:</div>
                        <div class="basket__res-op-value is-link"><a class="js-anchor" href="#delivery-block"><?=$arResult['CURRENT']['DELIVERY']['NAME_ALT']?></a></div>
                    </div>
                    <div class="basket__res-op">
                        <div class="basket__res-op-name">Адрес:</div>
                        <div class="basket__res-op-value"><?=($_REQUEST['address']!='') ? $_REQUEST['address'] : 'Куда везем?'?></div>
                        <a class="basket__link-change js-anchor" href="#change_adr" data-c_name="address">Изменить</a></div>
                    <div class="basket__res-op">
                        <div class="basket__res-op-name">Оплата:</div>
                        <div class="basket__res-op-value is-link"><a class="js-anchor" href="#payment-block"><?=$arResult['CURRENT']['PAYSYSTEM']['NAME']?></a></div>
                    </div>
                </div>
                <div class="basket__res-btn-wrap">
                <button type="submit" onclick="submitForm('Y');return false;" name="order_ok" class="basket__res-btn btn is-brown-light is-order">Оформить заказ</button>
                    <div class="basket__res-btn btn is-brown-light is-to-order">Перейти к оформлению
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="basket__form">
        <div class="basket__title-h">
            <div class="basket__title">Ваши данные:</div>
            <div class="basket__check">
                <div class="basket__field"><label class="basket__label"><input
                                class="basket__checkbox main-checkbox__checkbox" type="checkbox"
                                name="forder_ok" value="F" <?if($_REQUEST['forder_ok']=='F'):?>checked<?endif;?>><span class="main-checkbox__span basket__span">Минимум данных</span><span
                                class="basket__note">(Ужасно лень все заполнять)</span></label></div>
            </div>
        </div>

        <div class="basket__fast-order" <?if($_REQUEST['forder_ok']!='F'):?> style="display: none;" <?endif;?>>
         <div class="basket__fields is-name">
            <div class="basket__field<?if($arResult['ERRORS']['qname']!=''):?> is-error<?endif;?>">
                <input class="basket__input" placeholder="Имя" name="qname" value="<?=$_REQUEST['qname']?>">
                <span class="basket__label">(введите Ваше имя)</span>
                <div class="error"><?=$arResult['ERRORS']['qname']?></div>
            </div>
            <div class="basket__field<?if($arResult['ERRORS']['qphone']!=''):?> is-error<?endif;?>">
                <input class="basket__input" placeholder="Телефон" value="<?=$_REQUEST['qphone']?>" name="qphone">
                <span class="basket__label">(уточним детали заказа)</span>
                 <div class="error"><?=$arResult['ERRORS']['qphone']?></div>
             </div>

            </div>
        </div>


        <div class="basket__full-order" <?if($_REQUEST['forder_ok']=='F'):?> style="display: none;" <?endif;?>>
         <div class="basket__fields is-name">
            <div class="basket__field<?if($arResult['ERRORS']['fio']!=''):?> is-error<?endif;?>">
                <input class="basket__input" placeholder="Имя" name="fio" value="<?=$_REQUEST['fio']?>">
                <span class="basket__label">(введите Ваше имя)</span>
                <div class="error"><?=$arResult['ERRORS']['fio']?></div>
            </div>
            <div class="basket__field<?if($arResult['ERRORS']['bphone']!=''):?> is-error<?endif;?>">
                <input class="basket__input" placeholder="Телефон" value="<?=$_REQUEST['bphone']?>" name="bphone">
                <span class="basket__label">(уточним детали заказа)</span>
                 <div class="error"><?=$arResult['ERRORS']['bphone']?></div>
             </div>
              <div class="basket__field<?if($arResult['ERRORS']['email']!=''):?> is-error<?endif;?>" >
                <input class="basket__input" placeholder="Email" value="<?=$_REQUEST['email']?>" name="email">
                <span class="basket__label">(для письма о статусе заказа)</span>
                 <div class="error"><?=$arResult['ERRORS']['email']?></div>
             </div>
        </div>
        <div id="change_adr"></div>
            <div class="basket__title" id="delivery-block">Способ доставки:</div>
            <div class="basket__form-address"><span>Адрес</span>
            <input class="basket__input" type="text" name="address" value="<?=$_REQUEST['address']?>">
                <div class="basket__link-change" data-c_name="address">Изменить</div>
            </div>
            <div class="basket__hidden-block">

            <?foreach($arResult['DELIVERY'] as $arDelivery):?>
            <input id="delivery<?=$arDelivery['ID']?>" value="<?=$arDelivery['NAME']?>" type="radio" name="delivery" <?if($arDelivery['CHECKED']=='Y'):?>checked<?endif?>>
            <?endforeach;?>

</div>
            <div class="basket__fields is-deliv">
            <?foreach($arResult['DELIVERY'] as $arDelivery):?>
            <div class="basket__field js-nice-check" data-input_id="delivery<?=$arDelivery['ID']?>">
                    <div class="basket__form-btn-choose<?if($arDelivery['CHECKED']=='Y'):?> is-active<?endif?>"><?=$arDelivery['NAME']?></div>
                    <div class="basket__form-note"><?=$arDelivery['DESCRIPTION']?>
                    </div>
                </div>
            <?endforeach;?>
            </div>
            <div class="basket__title" id="payment-block">Способ оплаты:</div>

            <div class="basket__hidden-block">
                 <?foreach($arResult['PAYSYSTEM'] as $arPaysystem):?>
                 <input id="paysystem<?=$arPaysystem['ID']?>" value="<?=$arPaysystem['NAME']?>" type="radio" name="paysystem" <?if($arPaysystem['CHECKED']=='Y'):?>checked<?endif?>>
                 <?endforeach;?>
            </div>
            <div class="basket__fields is-pay">
            <?foreach($arResult['PAYSYSTEM'] as $arPaysystem):?>
                  <div class="basket__field js-nice-check" data-input_id="paysystem<?=$arPaysystem['ID']?>">
                    <div class="basket__form-btn-choose<?if($arPaysystem['CHECKED']=='Y'):?> is-active<?endif?>"><?=$arPaysystem['NAME']?></div>
                </div>
            <?endforeach?>
            <div class="basket__fields">
                <div class="basket__field">
                <textarea class="basket__textarea" name="comment" placeholder="Комментарий">
                    <?if($_REQUEST['comment']!=''):?>
                        <?=$_REQUEST['comment']?>
                    <?endif;?>

                    </textarea>
                    <span class="basket__label">(например, не звонить спит ребенок)</span>

                </div>
            </div>
        </div>
        <div class="basket__res-btn-wrap">
            <a class="basket__res-btn btn is-brown-light is-order-bot" href="/hochucoffe/static/html/pages/basket_success.html">Оформить заказ</a></div>
    </div>
</div>
    <?if($_POST["is_ajax_post"] != "Y"):?>
    <input type="hidden" name="is_ajax_post" id="is_ajax_post" value="Y">
    </div>
</form>
</div>
<?endif?>
<a id="basket-refresh" onclick="submitForm('N');" style="display:none;" rel="nofollow">Обновить</a>

<?endif;?>
<?
\Bitrix\Sale\DiscountCouponsManager::init();
\Bitrix\Sale\DiscountCouponsManager::clear(true);
?>
<?if($_POST["is_ajax_post"] == "Y")
{
    die();
}?>

