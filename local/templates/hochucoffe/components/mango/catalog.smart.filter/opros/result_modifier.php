<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
global $BP_TEMPLATE;

require ($_SERVER['DOCUMENT_ROOT'].'/local/templates/hochucoffe/components/mango/catalog.smart.filter/.default/result_modifier.php');

$arResult["arConstruct"] = array(
    //14 => 'Откуда Ваш любимый кофе?',//География
    16 => 'Ваши любимые нотки во вкусе Кофе?', //вкус
    17 => 'В чем завариваете кофе', //вкус
    11 => 'Какой способ обработки предпочитаете?',
);
