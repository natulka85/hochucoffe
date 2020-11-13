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
}
