<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?
$arResult['ELEM_PROPS'] = [
    '_STRANA' =>'Страна',
    'VKUS' =>'Вкусовые нотки',
    'GOD_UROGAYA' =>'Год урожая',
    'OPTIMAL_PRICE' =>'Цена',

];
$arSelect = ['ID','NAME','PROPERTY_WEIGHT'];
$arSelect = ['ID','NAME','PROPERTY_WEIGHT'];
foreach ($arResult['ELEM_PROPS'] as $prop=>$propName){
    $arSelect[] = 'PROPERTY_'.$prop;
}
if($arResult['PROPERTIES']['ELEMENT_ID']['VALUE']!=''){
    $res = CIBlockElement::GetList(Array('ID'=>'asc'), ['IBLOCK_ID'=>1,'=ID'=>$arResult['PROPERTIES']['ELEMENT_ID']['VALUE']],false, false, $arSelect);
    if($ob = $res->FETCH()){
        $ob['PROPERTY_VKUS_VALUE_F'] = implode(', ',$ob['PROPERTY_VKUS_VALUE']);
        $arResult['ELEMENTS'][$ob['ID']] = $ob;
    }
}
?>
