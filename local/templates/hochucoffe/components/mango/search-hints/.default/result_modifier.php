<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
global $BP_TEMPLATE;
CModule::IncludeModule("iblock");

if($_REQUEST['state']=='empty'){

    $arRand = [
        2 => ['NAME'=>'Кофе Арабика', 'LINK'=>'/catalog/section/arabika/akciya-is-true/','EXTERNAL_ID'=>'arabika'],
        13 => ['NAME'=>'Ароматизированный кофе', 'LINK'=>'catalog/section/aromatizirovannyy/akciya-is-true/','EXTERNAL_ID'=>'aromatizirovannyy'],
    ];

    $sect_rand = array_rand($arRand);
    $arResult['SECTIONS'][] = $arRand[$sect_rand];
    $arResult['CUR_SECT'] = $sect_rand;

    if(!empty($arRand[$sect_rand])){
        $res = CIBlockElement::GetList(
             array('PROPERTY_AKTSIYA'=>'desc,nulls'),
            array("IBLOCK_ID" => $arParams['IBLOCK_ID'], 'ACTIVE' => 'Y', 'SECTION_ID'=>$sect_rand,'>PROPERTY_OSTATOK_POSTAVSHCHIKA'=>0,'=PROPERTY_AKCIYA'=>'true'),
            false,
            ['nTopCount'=>4],
            array("ID",
                'NAME',
                'CODE',
                'IBLOCK_ID',
                'IBLOCK_SECTION_ID',
                'CATALOG_QUANTITY',
                'CATALOG_GROUP_1',
                'CATALOG_GROUP_2',
                'PROPERTY_AKCIYA',
                'PROPERTY__NOVINKA',
                'PROPERTY__HIT_PRODAZH',
                'PREVIEW_PICTURE'
            )
        );

        $arrElements = array();
        while ($el = $res->Fetch()) {
            $arItems[$el['ID']]['TITLE'] = $el['NAME'];
            $arItems[$el['ID']]['STATE'] = $BP_TEMPLATE->Catalog()->state(
                $el["IBLOCK_ID"],
                $el["CATALOG_QUANTITY"],
                $el['CATALOG_PRICE_1'],//$arItem['PRICES']['rozn']['VALUE'],
                $el['CATALOG_PRICE_2']//$arItem['PRICES']['Акция на сайте']['VALUE'],
            );

            $arItems[$el['ID']]['LABLES'] = $BP_TEMPLATE->Catalog()->lables(
                $el["IBLOCK_ID"],
                $el['CATALOG_PRICE_1'],//$arItem['PRICES']['rozn']['VALUE'],
                $el['CATALOG_PRICE_2'],//$arItem['PRICES']['Акция на сайте']['VALUE'],
                $el["PROPERTY__NOVINKA_VALUE"],
                $el["PROPERTY_ARTICLE_COMP_VALUE"],
                $el['PROPERTY__HIT_PRODAZH_VALUE']
            );
            $arItems[$el['ID']]['URL'] = '/catalog/product/'.$el['CODE'].'/';
            $arItems[$el['ID']]['PREVIEW_PICTURE'] = CFile::getPath($el['PREVIEW_PICTURE']);
            //$arItems[$el['ID']] = array_merge($arItemsAdditional,$arItems[$el['ID']]);
        }
    }
    $arResult['ITEMS'] = $arItems;

$arResult['TITLE'] = 'Кофе со скидкой для Вас:';
$arResult['BUTTON'] = [
    'TITLE' => $arRand[$sect_rand]['NAME'].' со скидкой',
    'LINK' => $arRand[$sect_rand]['LINK'],
];
}
$title_length = 200;
foreach($arResult["ITEMS"] as &$arItem)
{
    if(strlen($arItem['TITLE']) >  $title_length){
        $shotPrevText = substr($arItem['TITLE'], 0, $title_length);
        $arItem['TITLE'] = substr($shotPrevText, 0, strrpos($shotPrevText, ' ')).'...';
    }
}
unset($arItem);


?>
