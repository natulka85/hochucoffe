<?php
namespace Bp\Template\EventHandlers\Bitrix\Catalog;

use Bp\Template\Tools\Events\BaseEvent;

class OnPriceUpdate extends BaseEvent
{
    /**
     * @eventSort 100
     * @eventLink OnPriceAdd
     */


    public static function myEventHandler($ID, $arFields)
    {
        //setOptimalPriceProp
        $iblock_id = \CIBlockElement::GetIBlockByID($arFields['PRODUCT_ID']);
        $arMIblocks = [1];
        if(
            in_array($iblock_id,$arMIblocks)
        )
        {
            $arPrice = \CCatalogProduct::GetOptimalPrice($arFields['PRODUCT_ID']);
            \CIBlockElement::SetPropertyValuesEx($arFields['PRODUCT_ID'], $iblock_id, array("OPTIMAL_PRICE" => $arPrice["PRICE"]["PRICE"]));
        }
    }
}
