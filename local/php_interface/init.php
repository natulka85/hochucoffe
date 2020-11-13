<?php if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true)die();
//js библиотеки, которые можно подключить через CJsCore

include_once $_SERVER["DOCUMENT_ROOT"].'/local/php_interface/frontend.php';

//константы
include_once $_SERVER["DOCUMENT_ROOT"].'/local/php_interface/const.php';

//PC::debug  - дебаг в консоль(надо расширение для хрома)
include_once $_SERVER["DOCUMENT_ROOT"].'/local/php_interface/debug.php';

//глобальные функции
include_once $_SERVER["DOCUMENT_ROOT"].'/local/php_interface/functions.php';

//обработчики событий
include_once $_SERVER["DOCUMENT_ROOT"].'/local/php_interface/eventhandlers.php';
?>
