<?php

namespace Bp\Template\Tools\Services\AdminInterface;

use DigitalWand\AdminHelper\Helper\AdminListHelper;

/**
 * Хелпер описывает интерфейс, выводящий список новостей.
 *
 * {@inheritdoc}
 */
class EventlistListHelper extends AdminListHelper
{
    protected static $model = '\Bp\Template\Tools\Services\EventlistTable'; 
    
    protected function showMessages()
    {
        parent::showMessages();
        //\CAdminMessage::ShowMessage(["MESSAGE"=>'TODO: со временем добавить воможность менять активность', "TYPE"=>"OK"]);    
    }
}