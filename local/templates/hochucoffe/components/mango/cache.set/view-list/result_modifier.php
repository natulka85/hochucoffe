<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

global $BP_TEMPLATE;
CModule::IncludeModule("iblock");
CModule::IncludeModule("catalog");

$arFilter ['IBLOCK_ID'] = $arParams['IBLOCK_ID'];
$arFilter ['IBLOCK_TYPE'] = $arParams['IBLOCK_TYPE'];
$arTopCount = [];

if($arParams['COUNT_ON_PAGE']!=''){
    $arTopCount['nTopCount'] = $arParams['COUNT_ON_PAGE'];
}

$res = CIBlockElement::GetList(Array('ID'=>'asc'), $arFilter,false, $arTopCount, ['*']);
$i=0;
while($ob = $res->getNextElement()){
    $arResult['ITEMS'][$i] = $ob->getFields();
    $arResult['ITEMS'][$i]['PROPERTIES'] = $ob->getProperties();
    $i++;
}
include ($_SERVER['DOCUMENT_ROOT'].'/local/templates/hochucoffe/components/bitrix/news/view/bitrix/news.list/.default/result_modifier.php');
