<?php
namespace Bp\Template;

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Loader;
use Bp\Template\Basket;

Loc::loadMessages(__FILE__);

class Userstat
{
    public function getProduct($PRODUCT_ID)
    {

        $PRODUCT_ID = (int) $PRODUCT_ID;

        if ($PRODUCT_ID <= 0)
        {
            global $APPLICATION;

            $APPLICATION->ThrowException("User::updateProduct EMPTY PRODUCT ID", "EMPTY_PRODUCT_ID");
            return false;
        }

        if(is_array($_SESSION['bp_cache']['bp_user']['products'][$PRODUCT_ID])==true)
            return $_SESSION['bp_cache']['bp_user']['products'][$PRODUCT_ID];

        Loader::includeModule('iblock');
        Loader::includeModule('catalog');
        Loader::includeModule('sale');

        //элемент
        $dbElement = \Bitrix\Iblock\ElementTable::getList(array(
            'select' => [
                'IBLOCK_ID',
                'ID',
                'XML_ID',
                'NAME',
                'PREVIEW_PICTURE',
                'DETAIL_PICTURE',
                'CODE',
                'IBLOCK_SECTION_ID',
            ],
            'filter' => [
                '=ID' => $PRODUCT_ID,
            ],
        ));
        $arElement = $dbElement->Fetch();

        $arElement['PREVIEW_PICTURE_ID'] = $arElement['PREVIEW_PICTURE'];
        $arElement['PREVIEW_PICTURE'] = \CFile::GetPath($arElement['PREVIEW_PICTURE']);

        //свойства
        //по id нельзя так как разные инфоблоки могут быть
        if($arElement['IBLOCK_ID']>0 && $arElement['ID']>0)
        {
            $db_props = \CIBlockElement::GetProperty(
                $arElement['IBLOCK_ID'],
                $arElement['ID'],
                [],
                [
                    'CODE' => '_ARTICLE_COMP',
                ]
            );
            while($ar_props = $db_props->Fetch())
            {
                $arElement[$ar_props['CODE']] = $ar_props['VALUE'];
            }
        }
        //цены
        $dbPrices = \Bitrix\Catalog\PriceTable::getList(array(
            'select' => [
                'PRICE',
            ],
            'filter' => [
                'PRODUCT_ID' => $PRODUCT_ID,
            ],
        ));
        $arPrices = [];
        while($arPrice = $dbPrices->Fetch())
        {
            $arPrices[] = $arPrice['PRICE'];
        }
        sort($arPrices);
        $arElement['PRICE_1'] =  $arPrices[0];
        $arElement['PRICE_2'] =  $arPrices[1];

        return $arElement;
    }
}
