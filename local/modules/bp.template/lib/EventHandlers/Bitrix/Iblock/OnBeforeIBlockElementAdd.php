<?php
namespace Bp\Template\EventHandlers\Bitrix\IBlock;

use Bp\Template\Tools\Events\BaseEvent;

class OnBeforeIBlockElementAdd extends BaseEvent
{

    function myEventHandler(&$arFields)
    {
        //\Bitrix\Main\Diag\Debug::writeToFile($arFields,'arFields','_tt.txt');
        //код первого обработчика
        if($arFields["IBLOCK_ID"]==1 && is_Array($arFields["PROPERTY_VALUES"])){
            \Bp\Template\Catalog::ChangeSection1C($arFields, 1, 8, 1);
        }
    }
}
