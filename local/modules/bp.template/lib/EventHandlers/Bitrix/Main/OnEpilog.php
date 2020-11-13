<?php
namespace Bp\Template\EventHandlers\Bitrix\Main;

use Bp\Template\Tools\Events\BaseEvent;

class OnEpilog extends BaseEvent
{

    function ShowError404() {
        if (\CHTTP::GetLastStatus()=='404 Not Found' && defined('PAGE_404') === false) {
            //@define("ERROR_404","Y");
            //LocalRedirect("/404.php", false, '303 Moved permanently');
            global $APPLICATION;
            $APPLICATION->RestartBuffer();
            require $_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH.'/header.php';
            require $_SERVER['DOCUMENT_ROOT'] . '/404.php';
            require $_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH.'/footer.php';
        }
    }
}
