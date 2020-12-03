<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

global $BP_TEMPLATE;
CModule::IncludeModule("iblock");
CModule::IncludeModule("catalog");

$arFilter ['IBLOCK_ID'] = $arParams['IBLOCK_ID'];
$arFilter ['IBLOCK_TYPE'] = $arParams['IBLOCK_TYPE'];
global ${$arParams['FILTER_NAME']};
$arFilter = array_merge($arFilter,${$arParams['FILTER_NAME']});
$arTopCount = [];
$arSelect = Array(
    "ID",
    "CODE",
    "XML_ID",
    "NAME",
    "DETAIL_PICTURE",
    "DETAIL_TEXT",
    'DETAIL_PAGE_URL',
    "CATALOG_GROUP_1",
    "CATALOG_GROUP_2",
    "CATALOG_GROUP_3",
    "CATALOG_GROUP_4",
    "CATALOG_GROUP_5",
    "CATALOG_GROUP_6",
);

if($arParams['COUNT_ON_PAGE']!=''){
    $arTopCount['nTopCount'] = $arParams['COUNT_ON_PAGE'];
}

$res = CIBlockElement::GetList(Array('ID'=>'asc'), $arFilter,false, $arTopCount, $arSelect);
$i = 0;
while($ob = $res->GetNextElement()){
    $arResult['ITEMS'][$i] = $ob->GetFields();
    $arResult['ITEMS'][$i]['PROPERTIES'] = $ob->getProperties();
    $arResult['ITEMS'][$i]['DETAIL_PICTURE'] = CFile::GetFileArray($arResult['ITEMS'][$i]['DETAIL_PICTURE']);

    if($arResult['ITEMS'][$i]['ID']>0){
        $db_res = \Bitrix\Catalog\PriceTable::getList([
                "select" => ["*"],
                "filter" => [
                    "=PRODUCT_ID" => $arResult['ITEMS'][$i]['ID'],
                    'CATALOG_GROUP_ID'=>[1,2]
                ],
                "order" => ["CATALOG_GROUP_ID" => "ASC"]
            ]
        );
        while ($ar_res = $db_res->Fetch())
        {
            $ar_res['VALUE'] = $ar_res['PRICE'];
            $arResult['ITEMS'][$i]['PRICES'][] = $ar_res;
        }
    }
    $i++;
}

if(count($arResult['ITEMS'])==1){
    $arResult = $arResult['ITEMS'][0];
    unset($arResult['ITEMS']);
}
include ($_SERVER['DOCUMENT_ROOT'].'/local/templates/hochucoffe/components/bitrix/catalog.element/.default/result_modifier.php');

$arResult['LABLES_TEMPLATE'] = [
    'LEFT' => ['HIT','NEW','ACTION'],
    'RIGHT' => ['COUNTRY','SCA'],
];

if($arParams['MOD_TEMPATE']=='FAST_VIEW'){
    $arResult['DETAIL_TEXT_SHORT'] = stristr($arResult['~DETAIL_TEXT'],'</p>',TRUE);
}

$cp = $this->__component; // объект компонента
if (is_object($cp)) {
    $cp->arResult['DETAIL_PAGE_URL'] = $arResult['DETAIL_PAGE_URL'];
    $cp->SetResultCacheKeys(Array('DETAIL_PAGE_URL'));
}


