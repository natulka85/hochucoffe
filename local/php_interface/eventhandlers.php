<?php if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true)die();
use Bitrix\Main\EventManager;
//ВНИМАНИЕ! На сайте включено автоподключение событий
//Все события хранятся тут /local/modules/bp.template/lib/EventHandlers
\Bitrix\Main\Loader::includeModule('bp.template');


//подменяем обработчики сессии своими
//$handler = new \Bp\Template\Session();
//session_set_save_handler($handler, true);
//global $USER;

//это надо что бы класс шаблона подключился после подключения сессии
\Bitrix\Main\EventManager::getInstance()->addEventHandler(
    'main',
    'OnBeforeProlog',
    'OnPageStartTemplateStart'
);

function OnPageStartTemplateStart()
{
    global $BP_TEMPLATE;
    \Bitrix\Main\Loader::includeModule('bp.template');
    $BP_TEMPLATE = new \Bp\Template\Main();

}

