<?if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();?>
<?
global $BP_TEMPLATE,$USER;

//foreach(GetModuleEvents("sale", "OnSaleComponentOrderOneStepFinal", true) as $arEvent)
//    ExecuteModuleEventEx($arEvent, Array($arResult['ORDER_ID'], $arOrder, $arParams));
$arResult['ORDER_ID'] = 11;
if($USER->GetID())
{
    $arFilter = [
        //'FUSER_ID' => \Bitrix\Sale\Fuser::getId(),
        'ID' => $arResult['ORDER_ID'],
        'USER_ID' => $USER->GetID(),
        'LID' => SITE_ID,
        //'CAN_BUY' => 'Y',
    ];
    if($USER->GetID()==38)
        unset($arFilter['FUSER_ID']);

    $orderRes = \Bitrix\Sale\Internals\OrderTable::getList(array(
        'filter' => $arFilter
    ));
} else {

    $arFilter = [
        'FUSER_ID' => \Bitrix\Sale\Fuser::getId(),
        'ORDER_ID' => $arResult['ORDER_ID'],
        'LID' => SITE_ID,
        'CAN_BUY' => 'Y',
    ];
    if($USER->GetID()==38)
        unset($arFilter['FUSER_ID']);

    $orderRes = \Bitrix\Sale\Internals\BasketTable::getList(array(
        'filter' => $arFilter
    ));
}

if($USER->GetID()==38 || $orderRes->fetch()) {

    $order = \Bitrix\Sale\Order::load($arResult['ORDER_ID']);
    unset($arResult);
    $arResult['ORDER_ID'] = $order->getId();
    $arResult['DATA'] = $order->getDateInsert()->toString();
    $arResult['DATA_F'] = $order->getDateInsert()->format("d.m.Y");
    $arResult['price'] = $order->getPrice(); // Сумма заказа
    $arResult['discount_price'] = $order->getDiscountPrice(); // Размер скидки
    $arResult['delivary_price'] = $order->getDeliveryPrice(); // Стоимость доставки

    $arResult['payment'] = $order->getPaymentSystemId(); // массив id способов оплат
    $arResult['payment'] = \Bitrix\Sale\PaySystem\Manager::getObjectById($arResult['payment'][0]);
    $arResult['payment_name'] = $arResult['payment']->getName();
    $arResult['delivery'] = $order->getDeliverySystemId(); // массив id способов доставки
    $arResult['delivery'] = \Bitrix\Sale\Delivery\Services\Manager::getObjectById($arResult['delivery'][0]);
    $arResult['delivery_name'] = $arResult['delivery']->getName();

    $arResult['PROPERTY']['NAME'] = $order->getPropertyCollection()->getItemByOrderPropertyId(1)->getValue();
    $arResult['PROPERTY']['PHONE'] = $order->getPropertyCollection()->getItemByOrderPropertyId(2)->getValue();
    $arResult['PROPERTY']['EMAIL'] = $order->getPropertyCollection()->getItemByOrderPropertyId(3)->getValue();
    $arResult['PROPERTY']['PAYSTATE'] = $order->getPropertyCollection()->getItemByOrderPropertyId(8)->getValue();

    CModule::IncludeModule("catalog");

    $arResult['discount_item_price'] = 0;

    $basket = $order->getBasket();
    foreach ($basket as $item) {
        $price_1 = $item->getPrice();
        $price_2 = $item->getBasePrice();

        /*
        $arPrices =  [];
        $db_res = CPrice::GetList(
                array(),
                array(
                        "PRODUCT_ID" => $item->getProductId(),
                    )
            );
        while ($ar_res = $db_res->Fetch())
        {
            $arPrices[] = $ar_res["PRICE"];
        }
        $price_2 = max($arPrices);
        */

        $discount = 0;
        if ($price_2 > 0) {
            $discount = $BP_TEMPLATE->Catalog()->discount($price_2, $price_1);
            if (!$discount)
                $discount = 0;
        }
        $arResult['discount_item_price'] = $arResult['discount_item_price'] + $discount;
        $pId = $item->getField('PRODUCT_ID');

        $arResult['ITEMS'][$item->getField('PRODUCT_ID')] = [
            'BASKET_ID' => $item->getId(),
            'QUANTITY' => $item->getQuantity(),
            'NAME' => $item->getField('NAME'),
            'PRICE' => $item->getPrice(),
            'PRICE_2' => $price_2,
            'DISCOUNT' => $discount,
            'BASE_PRICE' => $item->getBasePrice(),
            'FINAL_PRICE' => $item->getFinalPrice(),
            'PRODUCT_ID' => $item->getField('PRODUCT_ID'),
            //'PROPS' => $item->getPropertyCollection()->getPropertyValues(),
        ];

        $disc = 0;
        if($arResult['ITEMS'][$pId]['PRICE_2'] - $arResult['ITEMS'][$pId]['PRICE']>0){
            $arResult['ITEMS'][$pId]['DISC'] = ($arResult['ITEMS'][$pId]['PRICE_2'] - $arResult['ITEMS'][$pId]['PRICE']) * $arResult['ITEMS'][$pId]['QUANTITY'];
        }

        //$arProd[$item->getField('PRODUCT_ID')] = ['id'=>$item->getField('PRODUCT_ID'), 'price'=>$item->getPrice(),'quantity'=> $item->getQuantity()];
    }
}
?>


<div class="basket__content-wr is-success">
    <div class="basket__content">
        <div class="basket__left">
            <div class="basket__text _type-1">Ваш заказ № <?=$arResult['ORDER_ID']?> принят!</div>
            <div class="basket__text _type-2"><p>
                    <?if($arResult['PROPERTY']['NAME']!=''):?> <span><?=$arResult['PROPERTY']['NAME']?>,</span><?endif;?>
                    Вы успешно оформили заказ <?=$arResult['DATA']?></p>
                <p><span>Благодарим, что выбрали нас!<br></span>Информация о заявке выслана на Ваш
                    электронный адрес.</p>
                <p>Если у Вас возникнут вопросы к службе доставки в день получения заказа,<br>обращайтесь
                    по телефону: <a href="tel:7 (985) 146 72 22"><span>+7 (985) 146 72 22</span></a></p></div>
        </div>
        <div class="basket__right">
            <div class="basket__result">
                <div class="basket__text _type-3">Информация по заказу</div>
                <div class="basket__res-block">
                    <div class="basket__name">Имя</div>
                    <div class="basket__value"><?=$arResult['PROPERTY']['NAME']?></div>
                </div>
                <div class="basket__res-block">
                    <div class="basket__name">Телефон</div>
                    <div class="basket__value"><?=$arResult['PROPERTY']['PHONE']?></div>
                </div>
                <div class="basket__res-block">
                    <div class="basket__name">Email</div>
                    <div class="basket__value"><?=$arResult['PROPERTY']['EMAIL']?></div>
                </div>
                <div class="basket__res-op-wrap">
                    <?if($arResult['ALT_NAMES'][$arResult['delivery_name']]!=''):?>
                        <div class="basket__res-op">
                            <div class="basket__res-op-name">Доставка:</div>
                            <div class="basket__res-op-value"><?=$arResult['ALT_NAMES'][$arResult['delivery_name']]?></div>
                        </div>
                    <?endif;?>
                    <?if($arResult['PROPERTY']['ADDRESS']!=''):?>
                        <div class="basket__res-op">
                            <div class="basket__res-op-name">Адрес:</div>
                            <div class="basket__res-op-value"><?=$arResult['PROPERTY']['ADDRESS']?></div>
                        </div>
                    <?endif;?>
                    <?if($arResult['payment_name']!=''):?>
                        <div class="basket__res-op">
                            <div class="basket__res-op-name">Оплата:</div>
                            <div class="basket__res-op-value"><?=$arResult['payment_name']?></div>
                        </div>
                    <?endif;?>

                </div>
            </div>
        </div>
    </div>
    <div class="basket__table">
        <div class="basket__text _type-3">Состав заказа:</div>
        <table>
            <tr>
                <th>Наименование</th>
                <th>Цена</th>
                <th>Кол-во</th>
                <th>Сумма</th>
                <th>Скидка</th>
                <th>Итого</th>
            </tr>
            <?foreach ($arResult['ITEMS'] as $arItem):?>
                <tr>
                    <td><?=$arItem['NAME']?></td>
                    <td><?=\SaleFormatCurrency($arItem['PRICE'], 'RUB')?></td>
                    <td><?=$arItem['QUANTITY']?></td>
                    <?if($arItem['PRICE_2'] > 0):?>
                        <td><?=\SaleFormatCurrency($arItem['PRICE_2'] * $arItem['QUANTITY'], 'RUB')?></td>
                    <?else:?>
                        <td><?=\SaleFormatCurrency($arItem['PRICE'] * $arItem['QUANTITY'], 'RUB')?></td>
                    <?endif;?>
                    <td><?=\SaleFormatCurrency($arItem['DISC'] * $arItem['QUANTITY'], 'RUB')?></td>
                    <td><?=\SaleFormatCurrency($arItem['PRICE'] * $arItem['QUANTITY'], 'RUB')?></td>
                </tr>
            <?endforeach;?>
        </table>
        <div class="basket__table-result"><span>Доставка:</span><span><?=\SaleFormatCurrency($arResult['delivary_price'], 'RUB')?></span></div>
        <div class="basket__table-result"><span>Итого к оплате:</span><strong><?=\SaleFormatCurrency($arResult['price'], 'RUB')?></strong></div>
    </div>
</div>
