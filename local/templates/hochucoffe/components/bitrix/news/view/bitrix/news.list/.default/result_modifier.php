<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
global $BP_TEMPLATE;
if(!empty($arResult['ITEMS'])){
    foreach ($arResult['ITEMS'] as &$arItem){
        if($arParams['PIC_ARRAY']=='Y'){
            $arItem['PREVIEW_PICTURE'] = CFile::GetFileArray($arItem['PREVIEW_PICTURE']);
        }
        if($arItem['PROPERTIES']['ELEMENT_ID']['VALUE']!=''){
            $arElements[] =$arItem['PROPERTIES']['ELEMENT_ID']['VALUE'];
        }
    }
}
unset($arItem);
$arResult['ELEM_PROPS'] = [
    '_STRANA' =>'Страна',
    'VKUS' =>'Вкусовые нотки',
    'GOD_UROGAYA' =>'Год урожая',
    'OPTIMAL_PRICE' =>'Цена',

];
$arSelect = ['ID','NAME','PROPERTY_WEIGHT'];
foreach ($arResult['ELEM_PROPS'] as $prop=>$propName){
    $arSelect[] = 'PROPERTY_'.$prop;
}
if(count($arElements)>0){
    $res = CIBlockElement::GetList(Array('ID'=>'asc'), ['IBLOCK_ID'=>1,'=ID'=>$arElements],false, false, $arSelect);
    while($ob = $res->FETCH()){
        $ob['PROPERTY_VKUS_VALUE_F'] = implode(', ',$ob['PROPERTY_VKUS_VALUE']);
        $arResult['ELEMENTS'][$ob['ID']] = $ob;
    }
}

//hash
$arResult["NAV_STRING"] = preg_replace_callback(
    '/href="([^"]+)"/is',
    function($matches){
        global $BP_TEMPLATE;
        return 'href="'.$BP_TEMPLATE->Catalog()->hashurl($matches[1],true).'"';
    }
    ,$arResult["NAV_STRING"]
);



?>
