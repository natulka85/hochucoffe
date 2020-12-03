<?php
namespace Bp\Template;

//use Bitrix\Main;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Loader;
use Bitrix\Main\Context;
use Bitrix\Sale\Internals\BasketTable;
use Bitrix\Sale\Fuser;
use Bp\Template\Userstat;
use Bitrix\Main\Config\Option;
use Bitrix\Sale;
use Bitrix\Sale\Order;
use Bitrix\Main\Application;
use Bitrix\Sale\DiscountCouponsManager;
use Bp\Template\FuserTable;

Loc::loadMessages(__FILE__);

class Basket
{
    protected static $instance = null;

    //public $test2 = 'tt24';

    protected function __construct()
    {

    }
    protected function __clone()
    {

    }

    public static function getInstance()
    {
        if (!isset(static::$instance)) self::$instance = new Basket();
        return static::$instance;
    }

    public function getCurBasketList()
    {

        Loader::includeModule('sale');

        $arBaskets = [];

        $dbBasket = BasketTable::getList(
            [
                'filter' => [
                    'FUSER_ID' => Fuser::getId(true),
                    'LID' => Context::getCurrent()->getSite(),
                    'ORDER_ID' => null,
                    'CAN_BUY' => 'Y',
                    'DELAY' => 'N',
                ],
            ]
        );
        while($arBasket = $dbBasket->fetch())
        {
            $db_res = \CSaleBasket::GetPropsList(
                array(),
                array("BASKET_ID" => $arBasket['ID'])
            );
            while ($ar_res = $db_res->Fetch())
            {
                $arBasket['PROPS'][$ar_res['CODE']] = $ar_res;
            }
            $arBaskets[] = $arBasket;
        }

        return $arBaskets;
    }
    public function getDelayBasketList()
    {
        $result = FuserTable::getList(array(
            'filter'  => [
                'FUSER_ID' => \Bitrix\Sale\Fuser::getId(true),
                'TYPE' => 'D',
            ],
        ));
        if($row = $result->fetch())
        {
            return unserialize($row['DATA']);
        }

        return false;
    }
    public function getCompareBasketList()
    {
        $result = FuserTable::getList(array(
            'filter'  => [
                'FUSER_ID' => \Bitrix\Sale\Fuser::getId(true),
                'TYPE' => 'C',
            ],
        ));
        if($row = $result->fetch())
        {
            return unserialize($row['DATA']);
        }

        return false;
    }

    public function addDelay($PRODUCT_ID)
    {

        if($PRODUCT_ID>0)
        {
            $_SESSION['bp_cache']['bp_user']['delay'][$PRODUCT_ID] = $PRODUCT_ID;

            $arElement = Userstat::getProduct($PRODUCT_ID);
            $_SESSION['bp_cache']['bp_user']['products'][$arElement['ID']] = $arElement;

            self::setDelay();

            return $arElement;
        } else
            return false;

    }

    public function delDelay($PRODUCT_ID)
    {

        $PRODUCT_ID = intVal($PRODUCT_ID);

        if($PRODUCT_ID>0)
        {
            unset($_SESSION['bp_cache']['bp_user']['delay'][$PRODUCT_ID]);
        }

        self::setDelay();

        return true;
    }

    public function setDelay()
    {
        $result = FuserTable::getList(array(
            'filter'  => [
                'FUSER_ID' => \Bitrix\Sale\Fuser::getId(false),
                'TYPE' => 'D',
            ],
        ));
        if($row = $result->fetch())
        {
            if(count($_SESSION['bp_cache']['bp_user']['delay'])==0)
            {
                FuserTable::delete($row['ID']);
            }   else {
                FuserTable::update($row['ID'],[
                    'FUSER_ID' => \Bitrix\Sale\Fuser::getId(false),
                    'TYPE' => 'D',
                    'DATA' => serialize(array_keys($_SESSION['bp_cache']['bp_user']['delay']))
                ]);
            }
        } else {
            FuserTable::Add([
                'FUSER_ID' => \Bitrix\Sale\Fuser::getId(false),
                'TYPE' => 'D',
                'DATA' => serialize(array_keys($_SESSION['bp_cache']['bp_user']['delay']))
            ]);
        }
    }
    public function addProduct($PRODUCT_ID, $QUANTITY = 1, $arPropsCode = [])
    {
        global $APPLICATION;
        if(count($arPropsCode)>0){
            $arProps = $this->decodeProps($arPropsCode);
        }
        Loader::includeModule('catalog');
        Loader::includeModule('sale');

        if($QUANTITY<0)
            $QUANTITY = 1;

        //$arProps = ["CODE" => "RAITING", "NAME" => " Рейтинг", "VALUE" => '100'];
        if($PRODUCT_ID>0)
        {
            $basket_id = \Add2BasketByProductID(
                $PRODUCT_ID,
                $QUANTITY,
                array(),
                $arProps
            );
            //\Bitrix\Main\Diag\Debug::writeToFile($arProps,$basket_id,'_tt.txt');
        } else {
            $APPLICATION->ThrowException("basket::addProduct EMPTY PRODUCT ID", "EMPTY_PRODUCT_ID");
            return false;
        }

        if($basket_id>0)
        {
            $_SESSION['bp_cache']['bp_user']['basket_code'][$PRODUCT_ID][$basket_id] = json_encode($arPropsCode,JSON_UNESCAPED_UNICODE);

            $_SESSION['bp_cache']['bp_user']['basket'][$PRODUCT_ID][$basket_id] = [
                'quantity' => $QUANTITY,
                'basket_id' => $basket_id,
                'basket_props'=>json_encode($arPropsCode,JSON_UNESCAPED_UNICODE)
            ];
            $arElement = Userstat::getProduct($PRODUCT_ID);
            $_SESSION['bp_cache']['bp_user']['products'][$arElement['ID']] = $arElement;

            //rest
            if(isset($_SESSION['bp_cache']['bp_user']['rest'][$PRODUCT_ID]))
                unset($_SESSION['bp_cache']['bp_user']['rest'][$PRODUCT_ID]);

            return $basket_id;
        } else {
            $APPLICATION->ThrowException("basket::addProduct EMPTY BASKET ID", "EMPTY_BASKET_ID");
            return false;
        }

    }

    public function updateProduct($BASKET_ID, $QUANTITY, $arPropsCode=[])
    {
        global $APPLICATION;
        Loader::includeModule('sale');
        Loader::includeModule('catalog');

        if($BASKET_ID > 0){
            $dbBasketItems = \CSaleBasket::GetList(
                [],
                ['ID'=>$BASKET_ID,"ORDER_ID" => "NULL"],
                false,
                false,
                []
            );
            if($arItems = $dbBasketItems->Fetch())
            {
                if(!empty($arPropsCode)){
                    $arPropsDecode = $this->decodeProps($arPropsCode);
                }

                $dbProp = \CSaleBasket::GetPropsList([],
                    ["BASKET_ID" => $arItems["ID"]]);
                while ($ar_res = $dbProp->Fetch())
                {
                    if(isset($arPropsDecode[$ar_res['CODE']])){
                       $arProps[$ar_res['CODE']] = $arPropsDecode[$ar_res['CODE']];
                    }
                    else{
                        $arProps[$ar_res['CODE']] = $ar_res;
                    }
                }

                //update(del) childs
                if(is_array($arChilds) && count($arChilds)>0)
                {
                    foreach($arChilds as $child_id)
                    {
                        self::updateProduct($child_id, 0);
                    }
                }
                $arFields = [
                    'QUANTITY' => $QUANTITY
                ];
                $arFields['PROPS'] = $arProps;
                \CSaleBasket::Update($BASKET_ID, $arFields);
                $element_id =$arItems['PRODUCT_ID'];
                if($QUANTITY>0)
                    $arBasket['quantity'] = $QUANTITY;
                else{
                    unset($_SESSION['bp_cache']['bp_user']['basket'][$element_id][$BASKET_ID]);
                    unset($_SESSION['bp_cache']['bp_user']['basket_code'][$element_id][$BASKET_ID]);
                    if(count($_SESSION['bp_cache']['bp_user']['basket'][$element_id])<=0){
                        unset($_SESSION['bp_cache']['bp_user']['basket'][$element_id]);
                        unset($_SESSION['bp_cache']['bp_user']['basket_code'][$element_id]);
                    }
                }
            }
        }
        else {
            $APPLICATION->ThrowException("basket::updateProduct EMPTY BASKET ID", "EMPTY_BASKET_ID");
            return false;
        }
    }
    public function createOrder($PERSON_TYPE_ID=1, $DELIVERY_ID=1, $PAY_SYSTEM_ID=1, $ordertype = '', $arProds=[], $arProps=[], $description='')
    {
        global $USER, $APPLICATION;
        \Bitrix\Main\Loader::IncludeModule('sale');
        $siteId = \Bitrix\Main\Context::getCurrent()->getSite();
        $currencyCode = Option::get('sale', 'default_currency', 'RUB');

        $app = \Bitrix\Main\Application::getInstance();
        $context = $app->getContext();
        $request = $context->getRequest();

        if(!$USER->IsAuthorized())
        {
            $rand = rand(0,999999999);
            $rand2 = rand(0,999999999);
            $newphone = $arProps['PHONE'];
            $newphone = preg_replace('/[^0-9]/', '', $newphone);

            if(strlen($newphone)<10)
                $APPLICATION->ThrowException("Order::OneClick ERROR PHONE", "ERROR_PHONE");
            //exit('error_phone');

            $user = new \CUser;

            if(!$arProps['EMAIL'])
            {
                $arProps['EMAIL'] =  $newphone."_fastorder@hochucoffe.ru";
                $login = $newphone.$rand.$rand2;

                if($arProps['PNAME'] == '')
                    $arProps['PNAME'] = $arProps['PHONE'];
            } else {
                $login = $arProps['EMAIL'];
            }

            $arFields = Array(
                //"NAME"              => $name,
                "LOGIN" => $login,
                "EMAIL" => $arProps['EMAIL'],
                "PASSWORD" => "1234561",
                "CONFIRM_PASSWORD" => "1234561"
            );

            $registeredUserID = $USER->Add($arFields);
            if(!$registeredUserID)
            {
                $arFields["LOGIN"] = rand(0,999).'_'.$arProps['EMAIL'];
                $registeredUserID = $USER->Add($arFields);
            }
        } else
            $registeredUserID = $USER->GetID();


        DiscountCouponsManager::init();


        $basket = Sale\Basket::loadItemsForFUser(
            Sale\Fuser::getId(),
            $siteId
        );

        if($ordertype=='oneclick' && count($arProds)>0)
        {
            //save current basket  to  $arCurBasket
            $arCurBasket = [];
            foreach ($basket as $item) {
                if($item->canBuy() && !$item->isDelay())
                {
                    $arCurBasket[] = $item->getId();
                    //disable
                    $item->setField('CAN_BUY', 'N');
                }
            }

            foreach($arProds as $PROD_ID=>$QUANTITY)
            {
                if ($item = $basket->getExistsItem('catalog', $PROD_ID)) {
                    $item->setField('QUANTITY', $QUANTITY);
                    $item->setField('CAN_BUY', 'Y');
                } else {
                    $item = $basket->createItem('catalog', $PROD_ID);
                    $item->setFields(array(
                        'QUANTITY' => $QUANTITY,
                        'CURRENCY' => \Bitrix\Currency\CurrencyManager::getBaseCurrency(),
                        'LID' => $siteId,
                        'PRODUCT_PROVIDER_CLASS' => 'CCatalogProductProvider',
                    ));
                }
            }

            //

            $basket->save();

            $arProps['ONECLICK'] = 'Y';
        }
        //getInfo
        $arInfoBasket = [];
        foreach ($basket as $item) {
            if($item->canBuy() && !$item->isDelay())
            {
                $arInfoBasket[] = [
                    'BASKET_ID' => $item->getId(),
                    'QUANTITY' => $item->getQuantity(),
                    'PRODUCT_ID' => $item->getProductId(),
                ];
            }
        }


        $order = Order::create($siteId, $registeredUserID);
        $order->setPersonTypeId($PERSON_TYPE_ID);

        $basket = Sale\Basket::loadItemsForFUser(\CSaleBasket::GetBasketUserID(), $siteId)->getOrderableItems();

        $order->setBasket($basket);

        if($ordertype=='oneclick' && count($arProds)>0)
        {
            //restore current basket
            $basket = Sale\Basket::loadItemsForFUser(
                Sale\Fuser::getId(),
                $siteId
            );
            foreach ($basket as $item) {
                if(in_array($item->getId(), $arCurBasket))
                {
                    //enable
                    $item->setField('CAN_BUY', 'Y');
                }
            }
            $basket->save();
        }


        //Shipment
        $DELIV_PRICE = 0;
        if(strpos($DELIVERY_ID,'||')!==false)
        {
            $arDelivData = explode('||',$DELIVERY_ID);
            $DELIVERY_NAME = $arDelivData[0];
            $DELIVERY_ID = 1;
            $DELIV_PRICE = (int) $arDelivData[1];
        }

        $shipmentCollection = $order->getShipmentCollection();
        $shipment = $shipmentCollection->createItem();
        $arDelivary = \Bitrix\Sale\Delivery\Services\Manager::getObjectById($DELIVERY_ID);

        if($DELIV_PRICE>0)
        {
            $shipment->setFields(array(
                'DELIVERY_ID' => $arDelivary->getId(),
                'DELIVERY_NAME' => $DELIVERY_NAME,
                'CURRENCY' => $order->getCurrency(),
                'BASE_PRICE_DELIVERY' => $DELIV_PRICE,
                'PRICE_DELIVERY' => $DELIV_PRICE,
            ));
        } else {
            $shipment->setFields(array(
                'DELIVERY_ID' => $arDelivary->getId(),
                'DELIVERY_NAME' => $arDelivary->getName(),
                'CURRENCY' => $order->getCurrency()
            ));
        }


        $shipmentItemCollection = $shipment->getShipmentItemCollection();

        foreach ($order->getBasket() as $item)
        {
            $shipmentItem = $shipmentItemCollection->createItem($item);
            $shipmentItem->setQuantity($item->getQuantity());
        }


        //Payment
        $paymentCollection = $order->getPaymentCollection();
        $extPayment = $paymentCollection->createItem(\Bitrix\Sale\PaySystem\Manager::getObjectById($PAY_SYSTEM_ID));

        //
        $order->doFinalAction(true);

        $propertyCollection = $order->getPropertyCollection();

        $arPropsKey = array_keys($arProps);

        foreach ($propertyCollection->getGroups() as $group)
        {

            foreach ($propertyCollection->getGroupProperties($group['ID']) as $property)
            {

                $p = $property->getProperty();
                if( in_array($p["CODE"],$arPropsKey))
                    $property->setValue($arProps[$p["CODE"]]);

            }
        }

        if($description!='')
        {
            $order->setField('USER_DESCRIPTION', $description);
        }

        $order->setField('CURRENCY', $currencyCode);
        //$order->setField('COMMENTS', 'Заказ оформлен через АПИ. ' . $comment);

        $order->save();
        $orderId = $order->GetId();

        if($orderId > 0){

            if($arInfoBasket>0)
            {
                foreach($arInfoBasket as $basket)
                {
                    $arElement = self::orderProduct($orderId, $basket['BASKET_ID'], $basket['QUANTITY'], $basket['PRODUCT_ID'], $order->getDeliveryPrice());
                }
            }

            $_SESSION['bp_cache']['bp_user']['order_info'][$orderId] = [
                'user_id' => (int) $USER->GetID(),
                'fio' => $arProps['PNAME'],
                'phone' => $arProps['PHONE'],
                'email' => $arProps['EMAIL'],
                'order_id' => (int) $orderId,
                'price' => (int) $order->getPrice(),
            ];
            return $orderId;
        }
        else{
            return false;
        }
    }
    public function orderProduct($orderId, $BASKET_ID, $QUANTITY, $PRODUCT_ID, $DeliveryPrice) {

        $_SESSION['bp_cache']['bp_user']['order'][$orderId][$PRODUCT_ID] = [
            'quantity' => $QUANTITY,
            'basket_id' => $BASKET_ID,
            'delivary' => $DeliveryPrice
        ];
        unset($_SESSION['bp_cache']['bp_user']['basket'][$PRODUCT_ID]);

        $arElement = Userstat::getProduct($PRODUCT_ID);
        $_SESSION['bp_cache']['bp_user']['products'][$arElement['ID']] = $arElement;
        return  $arElement;
    }

    public function decodeProps($arProps){
        foreach ($arProps as $prop=>$val){
            $arValues = explode('||',$val);
            $arPropsRes[$prop] = [
                'CODE'=>$arValues[0],
                'NAME'=>$arValues[1],
                'VALUE'=>$arValues[2],
            ];
        }
        return $arPropsRes;
    }
}
