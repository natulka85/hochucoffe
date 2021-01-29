<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

global $BP_TEMPLATE;
CModule::IncludeModule("iblock");
CModule::IncludeModule("catalog");
$arFilter ['ACTIVE'] = 'Y';
$arFilter ['IBLOCK_ID'] = $arParams['IBLOCK_ID'];
$arFilter ['IBLOCK_TYPE'] = $arParams['IBLOCK_TYPE'];
$arTopCount = [];

if($arParams['COUNT_ON_PAGE']!=''){
    $arTopCount['nTopCount'] = $arParams['COUNT_ON_PAGE'];
}

$res = CIBlockElement::GetList(Array('ID'=>'asc'), $arFilter,false, $arTopCount, ['ID','NAME','PROPERTY_URL','PROPERTY_ID_ON_MAP','DETAIL_PICTURE','DETAIL_TEXT']);
while($ob = $res->FETCH()){
    $value = CFile::GetFileArray($ob['DETAIL_PICTURE']);

    $arFile = CFile::ResizeImageGet(
        $value,
        array("width" => 80, "height" => 80),
        BX_RESIZE_IMAGE_PROPORTIONAL ,
        true
    );
    $ob['PICTURE'] = $arFile;
    $arResult['ITEMS'][] = $ob;
}
