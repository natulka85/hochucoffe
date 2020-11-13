<?php
namespace Bp\Template\EventHandlers\Bitrix\IBlock;

use Bp\Template\Tools\Events\BaseEvent;

class OnBeforeIBlockElementUpdate extends BaseEvent
{
    /**
     * @eventSort 100
     */
    public static function myEventHandler(&$arFields)
    {

        //код первого обработчика
        if($arFields["IBLOCK_ID"]==1 && is_Array($arFields["PROPERTY_VALUES"])){
            \Bp\Template\Catalog::ChangeSection1C($arFields, 1, 8   , 1);
            global $APPLICATION;

        }
    }

}
