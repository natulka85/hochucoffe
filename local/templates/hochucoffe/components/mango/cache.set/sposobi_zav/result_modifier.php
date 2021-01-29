<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
use Bp\Template\SettingsTable;
use Bp\Template\SeoSection;
use Bp\Template\ChpuFilter;
global $BP_TEMPLATE,$APPLICATION;
$res = \CIBlockElement::GetList(
    ['SORT'=>'asc'],
    [
        "IBLOCK_TYPE" => $arParams['IBLOCK_TYPE'],
        "IBLOCK_ID"=>$arParams['IBLOCK_ID'],
        "ACTIVE"=>"Y",
    ],
    false,
    false,
    ['*']
);
while($resOb = $res->getNextElement())
{
    $ob = [];
    $ob = $resOb->getFields();
    $ob['PROPERTIES'] = $resOb->getProperties();

    if($ob['PROPERTIES']['ARTICLE_BIND']['VALUE']!=''){
        $ARTICLE_ID = $ob['PROPERTIES']['ARTICLE_BIND']['VALUE'];
        $res2 = CIBlockElement::GetList([],['IBLOCK_ID'=>4,'ID'=>$ARTICLE_ID],false,['nTopCount'=>1],['DETAIL_PAGE_URL']);
        if($ar_res = $res2->GetNext()){
            if($ar_res['DETAIL_PAGE_URL']!=''){
                $ob['PROPERTIES']['ARTICLE_BIND']['DETAIL_PAGE_URL'] = $ar_res['DETAIL_PAGE_URL'];
            }
        }
    }
    if($ob['PROPERTIES']['PIC_PEN']['VALUE']!='' && $ob['PROPERTIES']['PIC_PENCIL']['VALUE'] !=''){
        $ob['PROPERTIES']['PIC_PEN']['VALUE'] = CFile::GetFileArray($ob['PROPERTIES']['PIC_PEN']['VALUE']);
        $ob['PROPERTIES']['PIC_PENCIL']['VALUE'] = CFile::GetFileArray($ob['PROPERTIES']['PIC_PENCIL']['VALUE']);
        if($arParams['ID_PROP']!='' && $arParams['ID_PROP'] == $ob['PROPERTIES']['ID_PROP']['VALUE']){
            $ob['ACTIVE_PAGE'] = 'Y';
        }

        $arResult['ITEMS'][] = $ob;
    }
}

$arResult['PROPS_TEMP'] = [
    'COFFE_G' => 'icon-3j_sp',
    'POMOL' => 'icon-3k_pom',
    'WATER_TEMP' => 'icon-3l_temp',
    'WATER_L' => 'icon-3m_wat',
    'TIMER' => 'icon-3n_timer',
];

