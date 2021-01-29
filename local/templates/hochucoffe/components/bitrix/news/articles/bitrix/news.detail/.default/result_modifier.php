<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
global $BP_TEMPLATE;

$arFilter = ['=PROPERTY_ARTICLE_BIND'=>$arResult['ID'],'IBLOCK_ID'=>8];
$res = CIBlockElement::GetList(array("ID" => "ASC"),
    $arFilter, false, ['nTopCount'=>1],
    []
);

if($el = $res->getNextElement()) {
    $item = $el->getFields();
    $item['PROPERTIES'] = $el->getProperties();

    $arResult['PROPS_TEMP'] = [
        'COFFE_G' => 'icon-3j_sp',
        'POMOL' => 'icon-3k_pom',
        'WATER_TEMP' => 'icon-3l_temp',
        'WATER_L' => 'icon-3m_wat',
        'TIMER' => 'icon-3n_timer',
    ];
}
$arResult['SPOSOB'] = $item;
?>
