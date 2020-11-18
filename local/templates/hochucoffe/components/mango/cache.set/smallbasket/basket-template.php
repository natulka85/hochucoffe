<div class="basket-cart">
<div class="basket-cart__title">Корзина</div>
    <?if(count($arData['basket'])>0):?>
        <form action="/personal/basket/">
            <fieldset>
                <ul class="cart-list">
                    <?
                    if(is_array($arData['basket'])):
                        $sum = 0;
                        foreach($arData['basket'] as $prod_id=>$arBasket):
                            //image
                            $pic_width = $pic_height = 52;
                            $arFile = CFile::ResizeImageGet(
                                $arData['products'][$prod_id]['PREVIEW_PICTURE_ID'],
                                array("width" => $pic_width, "height" => $pic_height),
                                BX_RESIZE_IMAGE_PROPORTIONAL_ALT ,
                                true
                            );
                            $sum = $sum + $arData['products'][$prod_id]['PRICE_1']*$arBasket['quantity'];

                            $name = $arData['products'][$prod_id]['NAME'];
                            if(strlen($name) > 40){
                                $shotName = substr($name, 0, 40);
                                $arData['products'][$prod_id]['NAME'] = substr($shotName, 0, strrpos($shotName, ' ')).'...';
                            }
                            ?>
                            <li>
                                <div class="basket-cart__close mc-close js-do" data-action="updatebasket" data-id="<?=$arBasket['basket_id']?>" data-q="0" data-rest="<?=$arBasket['quantity']?>"></div>
                                <a href="<?=$arData['products'][$prod_id]['DETAIL_PAGE_URL']?>" class="img">
                                    <img src="<?=$arFile['src']?>" alt=""></a>
                                <div class="holder">
                                    <input
                                        class="spinner-b"
                                        value="<?=$arBasket['quantity']?>"
                                        data-action="updatebasket"
                                        data-id="<?=$arBasket['basket_id']?>"
                                        <?if($arData['products'][$prod_id]['KRATNOST_OTGRUZKI_SHT']!='' && $arData['products'][$prod_id]['KRATNOST_OTGRUZKI_SHT']>0):?>data-kratnost="<?=$arData['products'][$prod_id]['KRATNOST_OTGRUZKI_SHT']?>" <?endif?>
                                    >
                                    <a href="<?=$arData['products'][$prod_id]['DETAIL_PAGE_URL']?>"><?=$arData['products'][$prod_id]['NAME']?></a>
                                    <span class="price"><?=\SaleFormatCurrency($arData['products'][$prod_id]['PRICE_1'], 'RUB')?></span>
                                </div>
                            </li>
                        <?endforeach?>
                    <?endif?>
                </ul>
                <span class="total-summ">
                  К оплате:
                  <span><?=\SaleFormatCurrency($sum, 'RUB')?></span>
                </span>
                <div class="bt-basket">
                    <?/*<button
                    class="click js-do"
                    data-action="ONECLICKALLFORM"
                  >Купить в 1 клик</button>*/?>
                    <input type="submit" value="Оформить заказ" <?=$arParams['~EVENTS']['button_oformit_zakaz']?>>
                </div>
            </fieldset>
        </form>
    <?else:?>
        <div class="basket-cart__text">Добавьте товары для покупки</div>
    <?endif?>
</div>
