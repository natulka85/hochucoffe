<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
global $BP_TEMPLATE;
\Bitrix\Main\Loader::IncludeModule('iblock');
\Bitrix\Main\Loader::IncludeModule('sale');
\Bitrix\Main\Loader::IncludeModule('catalog');
$fUserID = \Bitrix\Sale\Fuser::getId();
$siteId = \Bitrix\Main\Context::getCurrent()->getSite();
$basket = \Bitrix\Sale\Basket::loadItemsForFUser(
    $fUserID,
    $siteId
);

if(!$_REQUEST['is_ajax_post'])
    unset($_SESSION['bp_cache']['bp_user']['rest']);

$arResult = [];
$disc = 0;
foreach ($basket as $item) {
    if($item->canBuy() && !$item->isDelay())
    {
        $el = [
            'BASKET_ID' => $item->getId(),
            'QUANTITY' => $item->getQuantity(),
            'NAME' => $item->getField('NAME'),
            'PRICE' => $item->getField('PRICE'),
            //'PRICE_2' => $_SESSION['bp_cache']['bp_user']['products'][$item->getField('PRODUCT_ID')]['PRICE_2'],
            'PRODUCT_ID' => $item->getField('PRODUCT_ID'),
            'PROPS' => $item->getPropertyCollection()->getPropertyValues(),
        ];

		$pId = $item->getField('PRODUCT_ID');
        $arResult['ITEMS'][$pId] = $el;
        if($_SESSION['bp_cache']['bp_user']['products'][$pId]['PRICE_2']> $item->getField('PRICE'))
            $arResult['ITEMS'][$pId]['PRICE_2'] = $_SESSION['bp_cache']['bp_user']['products'][$pId]['PRICE_2'];

        if($arResult['ITEMS'][$pId]['PRICE_2'] - $arResult['ITEMS'][$pId]['PRICE']>0){
            $disc+= ($arResult['ITEMS'][$pId]['PRICE_2'] - $arResult['ITEMS'][$pId]['PRICE']) * $arResult['ITEMS'][$pId]['QUANTITY'];
        }

        $arResult['CURRENT']['QUANTITY'] += $arResult['ITEMS'][$pId]['QUANTITY'];
    }
}

$arResult['CURRENT']['DISC'] = $disc;

if($_REQUEST['ORDER_ID'])
{
    $arResult['ORDER_ID'] = $_REQUEST['ORDER_ID'];
}
elseif(isset($arResult['ITEMS']) && count($arResult['ITEMS'])>0)
{
    $arResult['show_promo'] = $_REQUEST['promocode'];

    //spec promocodes
    $arPromoSpecMatrix = [
        'VSSH-2KE53' => ['VSSH-S67UH', 3000],
        'VSSH-Q62QY' => ['VSSH-2YR07', 3000],
        'VSSH-2YP9H' => ['VSSH-S10QL', 10000],
    ];
    if($_REQUEST['promocode']!='' && in_array($_REQUEST['promocode'],array_keys($arPromoSpecMatrix)))
    {
        $sum = 0;
        foreach ($arResult['ITEMS'] as $k=>$basketItem)
        {
            $sum = $sum + ($basketItem['PRICE']*$basketItem['QUANTITY']);
        }
        $arNewPromo = $arPromoSpecMatrix[$_REQUEST['promocode']];
        if($sum<$arNewPromo[1])
        {
            $_REQUEST['promocode'] = $arNewPromo[0];
        }
    }

    //discount
    if($_REQUEST['promocode']!='')
    {
        \Bitrix\Sale\DiscountCouponsManager::init();
        \Bitrix\Sale\DiscountCouponsManager::clear(true);
        \Bitrix\Sale\DiscountCouponsManager::add($_REQUEST['promocode']);
        $arErrors = \Bitrix\Sale\DiscountCouponsManager::getErrors();
        $basket->refreshData(array('PRICE', 'COUPONS'));
        $discounts = \Bitrix\Sale\Discount::loadByBasket($basket);
        $discounts->calculate();
        $discountResult = $discounts->getApplyResult();
        $basket->save();
        if (!empty($discountResult['PRICES']['BASKET']))
        {
            $discountResult = $discountResult['PRICES']['BASKET'];
            foreach ($arResult['ITEMS'] as $k=>$basketItem)
            {
                $arResult['ITEMS'][$k]['PRICE'] = $discountResult[$basketItem['BASKET_ID']]['PRICE'];
                $arResult['ITEMS'][$k]['BASE_PRICE'] = $discountResult[$basketItem['BASKET_ID']]['BASE_PRICE'];
                $arResult['ITEMS'][$k]['DISCOUNT'] = $discountResult[$basketItem['BASKET_ID']]['DISCOUNT'];
            }
        }

        $dbCoupon = \Bitrix\Sale\Internals\DiscountCouponTable::getList(array(
            'select' => array(
                'COUPON',
                'DESCRIPTION',
                'ID',
                'DISCOUNT_ID',
                'ACTIVE',
                'ACTIVE_FROM',
                'ACTIVE_TO'
            ),
            'filter' => array('=COUPON' => $_REQUEST['promocode'])
        ));
        $arCoupon = $dbCoupon->Fetch();
        $arResult['COUPON'] = $arCoupon['COUPON'];
        $arResult['COUPON_DISCOUNT_ID'] = $arCoupon['DISCOUNT_ID'];
        $arResult['TT'] = $arCoupon;
        $arResult['COUPON_DESCRIPTION'] = $arCoupon['DESCRIPTION'];

        if($arCoupon['DISCOUNT_ID'])
        {
          $dbDiscount = \Bitrix\Sale\Internals\DiscountTable::getList(array(
              'select' => array(
                  'ACTIVE_FROM',
                  'ACTIVE_TO'
              ),
              'filter' => array('=ID' => $arCoupon['DISCOUNT_ID'])
          ));
          $arDiscount = $dbDiscount->Fetch();
          $arResult['TT'] = $arDiscount;
          if($arCoupon['ACTIVE_TO']=='' && $arDiscount['ACTIVE_TO']!='')
            $arCoupon['ACTIVE_TO'] = $arDiscount['ACTIVE_TO'];
          if($arCoupon['ACTIVE_FROM']=='' && $arDiscount['ACTIVE_FROM']!='')
            $arCoupon['ACTIVE_FROM'] = $arDiscount['ACTIVE_FROM'];
        }

        if($arCoupon['ACTIVE']=='N' && $arCoupon['DESCRIPTION']=='0')
            $arResult['DISCOUNT_ERROR'] = 'SERTIF_OFF';
        elseif($arCoupon['ACTIVE']=='N' && !$arCoupon['DESCRIPTION'])
            $arResult['DISCOUNT_ERROR'] = 'COUPON_OFF';
        elseif(!$arCoupon['ACTIVE'])
            $arResult['DISCOUNT_ERROR'] = 'NOT_FOUND';
        elseif(/*$arCoupon['ACTIVE_FROM']!='' || */$arCoupon['ACTIVE_TO']!='')
        {
            //$stmp_DateFrom = MakeTimeStamp($arCoupon['ACTIVE_FROM'], "DD.MM.YYYY HH:MI:SS");
            $stmp_Date     = MakeTimeStamp(date('d.m.Y H:i:s'), "DD.MM.YYYY HH:MI:SS");
            $stmp_DateTo   = MakeTimeStamp($arCoupon['ACTIVE_TO'], "DD.MM.YYYY HH:MI:SS");
            if(!(/*$stmp_Date > $stmp_DateFrom && */$stmp_Date < $stmp_DateTo))
                $arResult['DISCOUNT_ERROR'] = 'COUPON_OFF';
        }
    }



    //summ etc
    $sum = 0;
    $discount = 0;
    foreach ($arResult['ITEMS'] as $k=>$basketItem)
    {
        $sum = $sum + ($basketItem['PRICE']*$basketItem['QUANTITY']);
        $discount = $discount + $basketItem['DISCOUNT']*$basketItem['QUANTITY'];
    }
    $arResult['PRICE'] = $sum; //$basket->getPrice();
    $arResult['DISCOUNT'] = $discount;

    //get PREVIEW_PICTURE   and PROPS
    $arProdIds = array_keys($arResult['ITEMS']);
    $dbElement = \CIBlockElement::GetList(
        [],
        ['ID' => $arProdIds,],
        false,
        false,
        [
            'IBLOCK_ID',
            'ID',
            'PREVIEW_PICTURE',
            //'PROPERTY__PROIZVODITEL',
            //'PROPERTY_STRANA',
        ]
    );
    while($arElement = $dbElement->GetNextElement())
    {
        $ar_res = $arElement->GetFields();
        $arFile = CFile::ResizeImageGet(
            $ar_res['PREVIEW_PICTURE'],
            array("width" => 102, "height" => 102),
            BX_RESIZE_IMAGE_PROPORTIONAL ,
            true
        );
        $arResult['ITEMS'][$ar_res['ID']]['PREVIEW_PICTURE'] = $arFile['src'];
        $arResult['ITEMS'][$ar_res['ID']]['IBLOCK_ID'] = $ar_res['IBLOCK_ID'];
        $arResult['ITEMS'][$ar_res['ID']]['PROPERTIES'] = $arElement->GetProperties();

        $arResult['ITEMS'][$ar_res['ID']]['LABLES'] = $BP_TEMPLATE->Catalog()->lables(
            $arResult['ITEMS'][$ar_res['ID']]["IBLOCK_ID"],
            $arResult['ITEMS'][$ar_res['ID']]["PRICE_2"],
            $arResult['ITEMS'][$ar_res['ID']]["PRICE"],
            $arResult['ITEMS'][$ar_res['ID']]['PROPERTIES']["_NOVINKA"]["VALUE"],
            false
        );
    }
    //ищем детей и родителей по свойcтву корзины CODE - PARENT, VALUE - id родителя
    foreach ($arResult['ITEMS'] as $k=>&$arItem) {
        if(count($arItem["PROPS"])>0) {
            foreach ($arItem["PROPS"] as $prop) {
                if($prop["CODE"]=="PARENT") {
                    $c = 0; //счетчик сопадений родителя
                    foreach ($arResult['ITEMS'] as $k2 => $arItem2) {
                        if($arItem2["PRODUCT_ID"]==$prop["VALUE"]) {
                            $arResult['ITEMS'][$k2]["ADDED"][$arItem["BASKET_ID"]] = $arItem;
                            unset($arResult['ITEMS'][$k]);
                            $c++;
                        }
                    }
                   /* if($c == 0) { //если дете есть, но нет родителя - такое дите надо удалить из корзины
                        CSaleBasket::Delete($arData["id"]);
                        unset($arResult["GRID"]["ROWS"][$k]);
                    }*/
                }
            }
        }
    }
    unset($arItem);

    //LISTS
    $arColl = [];
    $arAnalog = [];
    $arOtherAll = [];
    $arNewYouLike = [];
    foreach ($arResult['ITEMS'] as $k=>$arItem)
    {
        if($arItem['PROPERTIES']['BASKET_COLLECTIONS']['~VALUE'])
        {
            $arData = json_decode($arItem['PROPERTIES']['BASKET_COLLECTIONS']['~VALUE'],true);
            if($arData['COLL'])
                $arColl = array_merge($arColl, $arData['COLL']);
            if($arData['ANALOGS'])
                $arAnalog = array_merge($arAnalog, $arData['ANALOGS']);


            $arOther = [];
            if($arData['RECOMMEND'])
                $arOther = $arData['RECOMMEND'];
            if($arData['ALSO'])
                if($arOther)
                    $arOther = array_merge($arOther, $arData['ALSO']);
                else
                    $arOther = $arData['ALSO'];

            if($arOther)
            {
                $arOtherAll = array_merge($arOtherAll,$arOther);
                $arResult['ITEMS'][$k]['LAMPS'] = array_merge((array)$arOther,(array)$arResult['ITEMS'][$k]['LAMPS']);
                //$arResult['ITEMS'][$k]['OTHER'] = $arOther;
            }
        }

        //new ylike
        $arNewYouLike[$arItem['PRODUCT_ID']]++;
    }
    $arResult['YLIKE'] =  array_keys($arNewYouLike);

    if($arOtherAll)
    {
        foreach($arOtherAll as $id)
        {
            $arResult['LAMPS'][$id]["CATALOG_PRICE_1"] = 1;
        }
    }

    //pre($arResult['ITEMS']);

    //virtual order
    $order = \Bitrix\Sale\Order::create(
        \Bitrix\Main\Context::getCurrent()->getSite(),
        \CSaleUser::GetAnonymousUserID()
    );
    $order->setPersonTypeId(1);
    $basket = \Bitrix\Sale\Basket::loadItemsForFUser(
            \Bitrix\Sale\Fuser::getId(),
            \Bitrix\Main\Context::getCurrent()->getSite()
        )->getOrderableItems();
    $order->setBasket($basket);
    $order->doFinalAction(true);


    //shipment
    $shipmentCollection = $order->getShipmentCollection();
    $shipment = $shipmentCollection->createItem();
    $shipmentItemCollection = $shipment->getShipmentItemCollection();
    $shipment->setField('CURRENCY', $order->getCurrency());
    /** @var Sale\BasketItem $item */
    foreach ($order->getBasket() as $item)
    {
        /** @var Sale\ShipmentItem $shipmentItem */
        //pre($item);
        $shipmentItem = $shipmentItemCollection->createItem($item);
        $shipmentItem->setQuantity($item->getQuantity());
    }

    //delivary
    $availableDeliveries = [];
    $arDelivaryList = \Bitrix\Sale\Delivery\Services\Manager::getActiveList();
    $arDelivery = [];
    if (!empty($shipment)) {
        $availableDeliveries =  \Bitrix\Sale\Delivery\Services\Manager::getRestrictedObjectsList($shipment);
        foreach ($availableDeliveries as $obDelivery) {
            if($obDelivery->isCalculatePriceImmediately()) {
                $shipment->setField('DELIVERY_ID', $obDelivery->getId());
                $calcResult = $obDelivery->calculate();

                if ($calcResult->isSuccess()) {
                    $arDelivery[$obDelivery->getId()] = $arDelivaryList[$obDelivery->getId()];
                    $arDelivery[$obDelivery->getId()]['PRICE'] = $calcResult->getPrice();
                    $arDelivery[$obDelivery->getId()]['PeriodDescription'] = $calcResult->getPeriodDescription();
                } else {

                }
            }
        }
    }
    $arResult['ALT_NAMES'] = [
        'Доставка курьером (Москва и МО)' => 'Курьером'
    ];
    foreach ($arDelivery as $k=>$dev){
        $arDelivery[$k]['NAME_ALT'] = $arResult['ALT_NAMES'][$dev['NAME']];
    }
    $arResult['DELIVERY'] = $arDelivery;


    //города
    $city = $_SESSION['bp_cache']['bp_user']['city'];
    if($city=='Москва')
    {
        foreach($arResult['DELIVERY'] as $k=>$delivery)
        {
            if($delivery['NAME'] == 'Курьером по Санкт-Петербургу')
            {
                unset($arResult['DELIVERY'][$k]);
            }
            unset($arResult['DELIVERY'][9]);
            unset($arResult['DELIVERY'][13]);
        }
    } elseif($city=='Санкт-Петербург')
    {
        foreach($arResult['DELIVERY'] as $k=>$delivery)
        {
            if($delivery['NAME'] == 'Курьером по Москве')
            {
                unset($arResult['DELIVERY'][$k]);
            }
            unset($arResult['DELIVERY'][9]);
            unset($arResult['DELIVERY'][13]);
        }
    } else {  //убираем все лишние доставки в регионах
        foreach($arResult['DELIVERY'] as $k=>$delivery)
        {
            /*if($delivery['NAME']!='До транспортной компании'){
                unset($arResult["DELIVERY"][$k]);
            }*/
            //set $arPartners
            if(
                $delivery['NAME']=='Самовывоз из нашего офиса'
                || ($delivery['NAME']=='Доставка в регион до пункта выдачи' && is_array($_SESSION['bp_cache']['bp_user']['city_data']))
            )
            {
          /* CModule::IncludeModule('highloadblock');
                $arPartners = [];
                $hlblock = Bitrix\Highloadblock\HighloadBlockTable::getById(5)->fetch();
                $entity = Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hlblock);
                $entity_data_class = $entity->getDataClass();
                $rsData = $entity_data_class::getList(array(
                    'select' => array('*'),
                    'filter' => ['UF_ACTIVE'=>1, 'UF_CITY_ID' => $_SESSION['bp_cache']['bp_user']['city_data']['ID']]
                ));
                while($el = $rsData->fetch())
                {
                    $el['UF_NAME'] = htmlspecialcharsEx($el['UF_NAME']);
                    $el['UF_ADDRESS'] = htmlspecialcharsEx($el['UF_ADDRESS']);

                    $arPartners[] = $el;
                }*/
            }


            if($delivery['NAME']=='Самовывоз из нашего офиса')
            {
                if(!$arPartners || $arResult['PRICE']<3000)

                    unset($arResult["DELIVERY"][$k]);
                else
                {
                    if(count($arPartners)>1)
                    {
                        $arResult["DELIVERY"][$k]['NAME'] = 'Самовывоз';
                    } else {
                        $arResult["DELIVERY"][$k]['NAME'] = 'Самовывоз:'.$arPartners[0]['UF_NAME'].' - '.$arPartners[0]['UF_ADDRESS'];
                        //$_REQUEST['delivery'] = htmlspecialcharsEx($_REQUEST['delivery']);
                    }
                    unset($arResult["DELIVERY"][$k]['DESCRIPTION']);
                    $arResult['PARTNERS'] = $arPartners;
                    $_REQUEST['delivery'] = htmlspecialcharsEx($_REQUEST['delivery']);
                }
            }
            if(
                $delivery['NAME']=='Курьером по Санкт-Петербургу'
                //|| $delivery['NAME']=='Самовывоз из нашего офиса'
                || $delivery['NAME']=='Курьером по Москве'
            ){
                unset($arResult["DELIVERY"][$k]);
            }
            if($delivery['NAME']=='Доставка в регион до пункта выдачи' && is_array($_SESSION['bp_cache']['bp_user']['city_data']))
            {
                if($arResult['PARTNERS'])
                    unset($arResult["DELIVERY"][$k]);
                else {
                    if($arPartners)
                    {
                        $arResult["DELIVERY"][$k]['CITY'] = $_SESSION['bp_cache']['bp_user']['city_data']['UF_CNAME'];
                        $arResult["DELIVERY"][$k]['DIF_PRICE'] = round(3000-$arResult['PRICE']);

                        $arResult["DELIVERY"][$k]['NAME'] = 'Доставка в регион до пункта выдачи в партнёрском магазине';
                        $arResult["DELIVERY"][$k]['DESCRIPTION'] .= '<ul>';
                        foreach($arPartners as $arPartner)
                        {
                            $arResult["DELIVERY"][$k]['DESCRIPTION'] .= '<li>'.$arPartner['UF_NAME'].' - '.$arPartner['UF_ADDRESS'].'</li>';
                        }
                        $arResult["DELIVERY"][$k]['DESCRIPTION'] .= '</ul>';
                    } else {
                        $arCity = $_SESSION['bp_cache']['bp_user']['city_data'];
                        if($arCity['UF_CFREEDELIV'])
                            $arResult["DELIVERY"][$k]['DIF_PRICE'] = round($arCity['UF_CFREEDELIV']-$arResult['PRICE']);
                        $arResult["DELIVERY"][$k]['CITY'] = $arCity['UF_CNAME'];
                    }
                }
            }
        }
    }

    if($_REQUEST['delivery']) // CHECKED request element
    {
        $find = false;
        foreach($arResult['DELIVERY'] as $k=>$delivery)
        {
            if($delivery['NAME'] == $_REQUEST['delivery'])
            {
                $arResult['DELIVERY'][$k]['CHECKED'] = 'Y';
                $arResult['DELIVERY_PRICE'] = $delivery['PRICE'];
                $arResult['CURRENT']['DELIVERY'] = $arResult['DELIVERY'][$k];
                $find = true;

                //Если была выбрана доставка в регионы, но потом уменьшили количество товара
                if(isset($delivery['DIF_PRICE']) && $delivery['DIF_PRICE']>0)
                    $find = false;
            }
        }
        if(!$find)// CHECKED first element
        {
            $keys = array_keys($arResult['DELIVERY']);
            $firstKey = $keys[0];
            $arResult['DELIVERY'][$firstKey]['CHECKED'] = 'Y';
            $arResult['DELIVERY_PRICE'] = $arResult['DELIVERY'][$firstKey]['PRICE'];
            $arResult['CURRENT']['DELIVERY'] = $arResult['DELIVERY'][$firstKey];
        }
    }
    else  // CHECKED first element
    {
        $keys = array_keys($arResult['DELIVERY']);
        $firstKey = $keys[0];
        $arResult['DELIVERY'][$firstKey]['CHECKED'] = 'Y';
        $arResult['DELIVERY_PRICE'] = $arResult['DELIVERY'][$firstKey]['PRICE'];
        $arResult['CURRENT']['DELIVERY'] = $arResult['DELIVERY'][$firstKey];
    }
    $selfDelivary = false;
    foreach($arResult['DELIVERY'] as $arDelivery)
    {
        if($arDelivery["CHECKED"]=="Y" && in_array($arDelivery['ID'], [2,6,10,11,13]))
            $selfDelivary = true;
    }
    //pre($arDelivery);
    $arResult['SELF_DELIVARY'] = $selfDelivary;

    $DelivaryDate = true;
    foreach($arResult['DELIVERY'] as $arDelivery)
    {
        if($arDelivery["CHECKED"]=="Y" && in_array($arDelivery['ID'], [2,6,9,10,11,13]))
            $DelivaryDate = false;
    }
    if($DelivaryDate)
    {
        foreach ($arResult['ITEMS'] as $k=>$arItem)
        {
            if($arItem['PROPERTIES']['OSTATOK_POSTAVSHCHIKA']['VALUE']==777777)
            {
                $DelivaryDate = false;
            }
        }
    }
    if($DelivaryDate)
    {
        $arHoliday = [
            '23.02',
            '08.03',
            '01.05',
            '02.05',
            '03.05',
            '04.05',
            '09.05',
            '10.05',
            '11.05',
            '12.06',
            '04.11',
        ];
        $arTimeZones = [
            'Пн' => ['с 9 до 18','с 18 до 23','с 23 до 2'],
            'Вт' => ['с 9 до 18','с 18 до 23','с 23 до 2'],
            'Ср' => ['с 9 до 18','с 18 до 23','с 23 до 2'],
            'Чт' => ['с 9 до 18','с 18 до 23','с 23 до 2'],
            'Пт' => ['с 9 до 18','с 18 до 23','с 23 до 2'],
            'Сб' => ['с 9 до 18'],
        ];
        $cur_hour = FormatDate("H", strtotime($date .' +0 day'));
        $arDays = [];
        for ($i = 1; $i <= 14; $i++) {
            $d = FormatDate("D", strtotime($date .' +'.$i.' day'));
            $day = FormatDate("d.m", strtotime($date .' +'.$i.' day'));
            if($d!='Вс' && !in_array($day,$arHoliday))
            {
                if($i==1) {
                    $day = 'Завтра';
                    unset($arTimeZones[$d][0]);
                }
                $arDays[] = [
                    'D' => $d,
                    'DAY' => $day,
                    'TIME_ZONES' => $arTimeZones[$d],
                ];
            }
        }

        //by time
        if($cur_hour<16) //+1day  first
        {
            //$arDays = array_slice($arDays,0,6);
        } else { //+2day  first
            if($arDays[0]['DAY']=='Завтра')
                unset($arDays[0]);
        }
        $arDays = array_slice($arDays,0,6);
    }
    $arResult['DELIVARY_DATE'] = $arDays;

    //payment
    $payment = \Bitrix\Sale\Payment::create($order->getPaymentCollection());
    $payment->setField('SUM', $order->getPrice());
    $payment->setField("CURRENCY", $order->getCurrency());
    $paySystemsList = \Bitrix\Sale\PaySystem\Manager::getListWithRestrictions($payment);

    $arResult['PAYSYSTEM'] = $paySystemsList;

    if($_REQUEST['paysystem'])
    {
        $find = false;
        foreach($arResult['PAYSYSTEM'] as $k=>$paysystem)
        {
            if($paysystem['NAME'] == $_REQUEST['paysystem'])
            {
                $arResult['PAYSYSTEM'][$k]['CHECKED'] = 'Y';
                $arResult['CURRENT']['PAYSYSTEM'] = $arResult['PAYSYSTEM'][$k];
                $find = true;
            }
        }
        if(!$find)// CHECKED first element
        {
            $keys = array_keys($arResult['PAYSYSTEM']);
            $firstKey = $keys[0];
            $arResult['PAYSYSTEM'][$firstKey]['CHECKED'] = 'Y';
            $arResult['CURRENT']['PAYSYSTEM'] = $arResult['PAYSYSTEM'][$firstKey];
        }
    } else {  // CHECKED first element
        $keys = array_keys($arResult['PAYSYSTEM']);
        $firstKey = $keys[0];
        $arResult['PAYSYSTEM'][$firstKey]['CHECKED'] = 'Y';
        $arResult['CURRENT']['PAYSYSTEM'] = $arResult['PAYSYSTEM'][$firstKey];
    }


    //try fastorder

    if($_REQUEST['forder_ok']!=''){

        $arDebug['$_REQUEST'] = $_REQUEST;

        \Bitrix\Main\Diag\Debug::writeToFile($arDebug,$arDebug,'_basket.txt');

        $error = [];
        if(!($_REQUEST['qname']!=''))
        {
            $error['qname'] = 'Заполните поле Имя';
        }
        if(!($_REQUEST['qphone']!=''))
        {
            $error['qphone'] = 'Заполните поле Телефон';
        } elseif(strlen($_REQUEST['qphone'])<11) {
            $error['qphone'] = 'Неверный телефон';
        }

        if(count($error)==0)
        {
            $order_id = \Bp\Template\Basket::createOrder(
                1,
                2,
                2,
                '',
                [],
                [
                    'PNAME' => $_REQUEST["qname"],
                    'PHONE' =>$_REQUEST["qphone"],
                    'REFFERER' =>$_REQUEST["REFFERER"],
                    'USERID' =>$_REQUEST["USERID"],
                    'PROMOCODE' =>$_REQUEST["PROMOCODE"],
                ]
            );

            if($order_id>0)
            {
                $arResult['ORDER_ID'] = $order_id;
                \Bitrix\Main\UserConsent\Consent::addByContext(1, 'basket/fastorder', $_REQUEST["qphone"], array('URL' => $_SERVER['HTTP_REFERER']));
            }
        }
    }

    //try order
    elseif($_REQUEST['order_ok']!=''){
        $error = [];
        if(!($_REQUEST['fio']!=''))
        {
            $error['fio'] = 'Заполните поле Имя';
        }
        if(!($_REQUEST['bphone']!=''))
        {
            $error['bphone'] = 'Заполните поле Телефон';
        } elseif(strlen($_REQUEST['bphone'])<11) {
            $error['bphone'] = 'Неверный телефон';
        }
        if(!($_REQUEST['email']!=''))
        {
            $error['email'] = 'Заполните поле Email';
        } elseif (!filter_var($_REQUEST["email"], FILTER_VALIDATE_EMAIL))
        {
            $error['email'] = 'Не верный Email';
        }

        if(count($error)==0)
        {
            $ok = true;
            $delivary = 0;
            //delivary
            if($_REQUEST['delivery']) // CHECKED request element
            {
                foreach($arResult['DELIVERY'] as $k=>$value)
                {
                    if($_REQUEST['delivery']==$value['NAME'])
                        $delivary = $value['ID'];
                        $delivary_price = $value['PRICE'];
                }
                if($delivary==0)
                    $ok = false;
            } else {
                $ok = false;
            }

            //delivary + cad/mkad
            if($_REQUEST['caddistance']>0 && !$arResult['SELF_DELIVARY'])
            {
                $delivary_price = $delivary_price + $_REQUEST['caddistance']*15;
                $delivary = $_REQUEST['delivery'].'||'.$delivary_price;
            }

            //delivary + Pek
            if($_REQUEST['calc']['region_delivery_summ']>0)
            {
                $delivary_price = $delivary_price + $_REQUEST['calc']['region_delivery_summ'];
                $delivary = $_REQUEST['delivery'].'||'.$delivary_price;
            }

            //payment
            $payment = 0;
            if($_REQUEST['paysystem'])
            {
                foreach($arResult['PAYSYSTEM'] as $k=>$value)
                {
                    if($_REQUEST['paysystem']==$value['NAME'])
                        $payment = $value['ID'];
                }
                if($payment==0)
                    $ok = false;
            } else {
                $ok = false;
            }

            //comment + ADDRESS + DELIVARY_DATE
            if($_REQUEST["address"]!='')
                $_REQUEST["comment"] = $_REQUEST["comment"].' ||  Адрес:'.$_REQUEST["address"];
            if($_REQUEST["date"]!='')
                $_REQUEST["comment"] = $_REQUEST["comment"].' || Время доставки:'.$_REQUEST["date"];

            if($_REQUEST["partner"]!='')
                $_REQUEST["comment"] = $_REQUEST["comment"].' || Адрес самовывоза:'.$_REQUEST["partner"];

            if($_REQUEST['calc']['city_main']!=''){
                foreach($_REQUEST['calc'] as $key => $val){
                    $_REQUEST["comment"] .= $key.': '.$val.' ||';
                }
            }

            //create order
            if($ok)
            {
                $order_id = \Bp\Template\Basket::createOrder(
                    1,
                    $delivary,
                    $payment,
                    '',
                    [],
                    [
                        'PNAME' => $_REQUEST["fio"],
                        'PHONE' =>$_REQUEST["bphone"],
                        'EMAIL' =>$_REQUEST["email"],
                        'REFFERER' =>$_REQUEST["REFFERER"],
                        //'USERID' =>$_REQUEST["USERID"],
                        'PROMOCODE' =>$_REQUEST["PROMOCODE"],
                        'REG-CITY' =>$_REQUEST["REG-CITY"],
                        //'REG-SUMM' =>$_REQUEST["REG-SUMM"],
                        "ADDRESS" => $_REQUEST["address"],
                        //"DELIVARY_DATE" => $_REQUEST["date"],
                    ],
                    $_REQUEST["comment"]
                );
                //$_REQUEST["test"] = $_REQUEST["email"];
                if($order_id>0)
                {
                    $arResult['ORDER_ID'] = $order_id;
                    \Bitrix\Main\UserConsent\Consent::addByContext(1, 'basket/order', $_REQUEST["bphone"], array('URL' => $_SERVER['HTTP_REFERER']));
                }
            }
        }
    }

    $arResult['ERRORS'] = $error;


    global $USER;
    if($USER->IsAuthorized())
    {
        $rsUser = CUser::GetByID($USER->GetID());
        $arUser = $rsUser->Fetch();
        if(!$_REQUEST['fio'])
            $_REQUEST['fio'] = $USER->GetFullName();
        if(!$_REQUEST['bphone'])
            $_REQUEST['bphone'] = $arUser['PERSONAL_PHONE'];
        if(!$_REQUEST['email'])
            $_REQUEST['email'] = $arUser['EMAIL'];

        if(!$_REQUEST['qname'])
            $_REQUEST['qname'] = $USER->GetFullName();
        if(!$_REQUEST['qphone'])
            $_REQUEST['qphone'] = $arUser['PERSONAL_PHONE'];
    }
}
if($city!='Москва' && $city!='Санкт-Петербург'){
    //include_once ($_SERVER['DOCUMENT_ROOT'].'/local/scripts/tk/pek/pek_action_file.php');
}
