<?php

namespace Bp\Template\Tools\Services\AdminInterface;

use DigitalWand\AdminHelper\Helper\AdminEditHelper;

/**
 * Хелпер описывает интерфейс, выводящий форму редактирования новости.
 *
 * {@inheritdoc}
 */
class EventtEditHelper extends AdminEditHelper
{
    protected static $model = '\Bp\Template\Tools\Services\EventTable';
    protected function showMessages()
    {
        parent::showMessages();
        \CAdminMessage::ShowMessage(["MESSAGE"=>'TODO: со временем добавить галку - показывать в шапке', "TYPE"=>"OK"]); 
        \CAdminMessage::ShowMessage(["MESSAGE"=>'FUNCTION: содержит две предопределенных переменных data и event_data', "TYPE"=>"OK"]);  
    }
}