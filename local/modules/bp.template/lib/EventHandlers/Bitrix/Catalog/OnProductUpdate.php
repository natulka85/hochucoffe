<?php
namespace Bp\Template\EventHandlers\Bitrix\Catalog;

use Bp\Template\Tools\Events\BaseEvent;

class OnProductUpdate extends BaseEvent
{
    /**
     * @eventSort 100
     * @eventLink OnPriceAdd
     */
    public static function setQProp($ID, $arFields)
    {
        \Bp\Template\Catalog::setQuantityProp($ID, $arFields['QUANTITY']);
    }
}
