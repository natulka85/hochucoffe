<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

global $BP_TEMPLATE;
CModule::IncludeModule("iblock");
CModule::IncludeModule("catalog");

$arFilter ['IBLOCK_ID'] = $arParams['IBLOCK_ID'];
$arFilter ['IBLOCK_TYPE'] = $arParams['IBLOCK_TYPE'];
global ${$arParams['FILTER_NAME']};
$arFilter = array_merge($arFilter,${$arParams['FILTER_NAME']});
$arTopCount = [];

if($arParams['COUNT_ON_PAGE']!=''){
    $arTopCount['nTopCount'] = $arParams['COUNT_ON_PAGE'];
}

$res = CIBlockElement::GetList(Array('ID'=>'asc'), $arFilter,false, $arTopCount, ['*']);
$i = 0;
while($ob = $res->GetNextElement()){
    $arResult['ITEMS'][$i] = $ob->GetFields();
    $arResult['ITEMS'][$i]['PROPERTIES'] = $ob->getProperties();
    $arResult['ITEMS'][$i]['DETAIL_PICTURE'] = CFile::GetFileArray($arResult['ITEMS'][$i]['DETAIL_PICTURE']);

    $i++;
}
