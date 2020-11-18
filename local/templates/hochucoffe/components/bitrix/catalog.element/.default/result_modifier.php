<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

global $BP_TEMPLATE;
if(isset($arResult)) {

    $arPrices = [];
    foreach($arResult["PRICES"] as $PRICE)
    {
        $arPrices[] = $PRICE['VALUE'];
    }
    $arResult["PRICES"] = [];
    $arResult["PRICES"]['min'] = min($arPrices);
    $arResult["PRICES"]['max'] = max($arPrices);

    $arResult["CATALOG_QUANTITY"] =  $arResult['PROPERTIES']['OSTATOK_POSTAVSHCHIKA']['VALUE'];


    $no_photo_src = '/local/templates/hochucoffe/static/images/general/no-photo.png';
    $pic_width = 562;
    $pic_height = 562;

    $pic_width_tumb = 80;
    $pic_height_tumb = 80;

    //default
    $arResultsAdditional = array(
        'DEFAULT_IMAGE' => [],
        'LABLES' => array(), //бирки
        'STATE' => array(), //состояния (В наличие, Снято с производства, Уточняйте у менеджера)
    );

    //picture
    $value = $arResult['DETAIL_PICTURE']['ID'];
    if ($value!='')
        $arResultsAdditional['DEFAULT_IMAGE'][0] = CFile::GetFileArray($value);

    $arResultsAdditional['DEFAULT_IMAGE'][0]['ALT'] = $arResult['NAME'];
    $arResultsAdditional['DEFAULT_IMAGE'][0]['HREF'] = $arResultsAdditional['DEFAULT_IMAGE'][0]['SRC'];

    $arFile = CFile::ResizeImageGet(
        $value,
        array("width" => $pic_width_tumb, "height" => $pic_height_tumb),
        BX_RESIZE_IMAGE_PROPORTIONAL ,
        true
    );
    $arResultsAdditional['DEFAULT_TUMB_IMAGE'][0] = array(
        'SRC' => $arFile['src'],
        'WIDTH' => $arFile['width'],
        'HEIGHT' => $arFile['height'],
    );

    //more_picture
    if($arResult['PROPERTIES']['MORE_PHOTO']['VALUE'])
    {
        if(is_array($arResult['PROPERTIES']['MORE_PHOTO']['VALUE']))
        {
            foreach($arResult['PROPERTIES']['MORE_PHOTO']['VALUE'] as $i=>$value)
            {
                if($i == 0){
                    //continue;
                }
                $arFile = CFile::ResizeImageGet(
                    $value,
                    array("width" => $pic_width, "height" => $pic_height),
                    BX_RESIZE_IMAGE_PROPORTIONAL ,
                    true
                );
                $arResultsAdditional['DEFAULT_IMAGE'][] = array(
                    'SRC' => $arFile['src'],
                    'WIDTH' => $arFile['width'],
                    'HEIGHT' => $arFile['height'],
                    'HREF' => CFile::GetPath($value),
                );
                $arFile = CFile::ResizeImageGet(
                    $value,
                    array("width" => $pic_width_tumb, "height" => $pic_height_tumb),
                    BX_RESIZE_IMAGE_PROPORTIONAL ,
                    true
                );
                $arResultsAdditional['DEFAULT_TUMB_IMAGE'][] = array(
                    'SRC' => $arFile['src'],
                    'WIDTH' => $arFile['width'],
                    'HEIGHT' => $arFile['height'],
                );
            }
        }
    }

    $arResultsAdditional['LABLES'] = $BP_TEMPLATE->Catalog()->lables(
        $arResult["IBLOCK_ID"],
        $arResult['PRICES']['max'],//$arResult['PRICES']['rozn']['VALUE'],
        $arResult['PRICES']['min'],//$arResult['PRICES']['Акция на сайте']['VALUE'],
        $arResult["PROPERTIES"]["_NOVINKA"]["VALUE"],
        $arResult["PROPERTIES"]["_PROIZVODITEL"]["VALUE_ENUM_ID"],
        $arResult["PROPERTIES"]["CML2_ARTICLE"]["VALUE"]
    );

    //state
    $arResultsAdditional['STATE'] = $BP_TEMPLATE->Catalog()->state(
        $arResult["IBLOCK_ID"],
        $arResult["CATALOG_QUANTITY"],
        $arResult['PRICES']['max'],//$arResult['PRICES']['rozn']['VALUE'],
        $arResult['PRICES']['min']//$arResult['PRICES']['Акция на сайте']['VALUE'],
    );

    $arResult = array_merge($arResultsAdditional, $arResult);

    if(count($arResult['PROPERTIES']['VKUS']['VALUE_ENUM'])>0){
        $vkus = $BP_TEMPLATE->str_fst_upper(mb_strtolower(implode(', ',$arResult['PROPERTIES']['VKUS']['VALUE_ENUM'])));
        $arResult['PROPERTIES']['VKUS']['VALUE_FORMATTED'] =  $vkus;
        unset($vkus);
    }

    if($arResult['PROPERTIES']['WEIGHT_VAR']['~VALUE']!=''){
        $arResult['PROPERTIES']['WEIGHT_VAR_AR'] = json_decode($arResult['PROPERTIES']['WEIGHT_VAR']['~VALUE'],TRUE);
        asort($arResult['PROPERTIES']['WEIGHT_VAR_AR'],SORT_NUMERIC);
    }

}

$arResult['TMPL_PROPS'] =[
    '_ARTICLE_COMP',
    'OCENKA_SCA',
    '_SOSTAV',
    'VKUS',
    'GEOGRAPHY',
    '_STRANA',
    'REGION_PROIZRASTANIYA',
    'GOD_UROGAYA',
    'SPOSOB_OBRABOTKI',
    "SPOSOB_PRIGOTOVLENIYA",

];

$arPropertiesLink = [
    'OCENKA_SCA',
    '_SOSTAV',
    'VKUS',
    '_STRANA',
];
foreach ($arPropertiesLink as $propCode){
    $arValues = [];
    if($arResult['PROPERTIES'][$propCode]['VALUE']!=''){
        if(is_array($arResult['PROPERTIES'][$propCode]['VALUE'])){
            $arValues = $arResult['PROPERTIES'][$propCode]['VALUE'];
            foreach ($arValues as $k=>$v){
                $type_part = strtolower($arResult['PROPERTIES'][$propCode]['CODE'].'-is-'.$arResult['PROPERTIES'][$propCode]['VALUE_XML_ID'][$k]).'/';
                $link = $BP_TEMPLATE->ChpuFilter()->convertOldToNew('/catalog/filter/'.$type_part);
                $arResult['PROPERTIES'][$propCode]['FILTER_LINK'][$arValues[$k]] = $link;
            }
        }
        else{
            $type_part = strtolower($arResult['PROPERTIES'][$propCode]['CODE'].'-is-'.$arResult['PROPERTIES'][$propCode]['VALUE_XML_ID']).'/';
            $link = $BP_TEMPLATE->ChpuFilter()->convertOldToNew('/catalog/filter/'.$type_part);
            $arResult['PROPERTIES'][$propCode]['FILTER_LINK'][$arResult['PROPERTIES'][$propCode]['VALUE']] = $link;
        }
    }
}
//для крошек
$arAddChainSec1 = [];
$arAddChainSec2 = [];
//выборка дерева подразделов для раздела
$arSections = [];
$rsParentSection = CIBlockSection::GetByID(1);
if ($arParentSection = $rsParentSection->GetNext())
{
    $parent_sec_id = 0;
    $arFilter = array('IBLOCK_ID' => $arParentSection['IBLOCK_ID'],'>LEFT_MARGIN' => $arParentSection['LEFT_MARGIN'],'<RIGHT_MARGIN' => $arParentSection['RIGHT_MARGIN'],'>DEPTH_LEVEL' => $arParentSection['DEPTH_LEVEL']); // выберет потомков без учета активности
    $rsSect = CIBlockSection::GetList(array('left_margin' => 'asc'),$arFilter);
    while ($arSect = $rsSect->GetNext())
    {
        // получаем подразделы
        if($arSect['SORT']<500)
        {
            if($arSect['DEPTH_LEVEL']==2)
                $arAddChainSec1[$arSect['ID']] = [
                    'NAME' =>  $arSect['NAME'],
                    'SECTION_PAGE_URL' =>  $arSect['SECTION_PAGE_URL'],
                ];
            if($arSect['DEPTH_LEVEL']==3)
                $arAddChainSec2[$arSect['IBLOCK_SECTION_ID']][$arSect['ID']] = [
                    'NAME' =>  $arSect['NAME'],
                    'SECTION_PAGE_URL' =>  $arSect['SECTION_PAGE_URL'],
                ];
            if($arSect['DEPTH_LEVEL']==3 && $arSect['ID']==$arResult['IBLOCK_SECTION_ID'])
                $parent_sec_id  = $arSect['IBLOCK_SECTION_ID'];
        }
    }

    $arAddChainSec1[$parent_sec_id]['CHILDS'] = $arAddChainSec2[$parent_sec_id];
}

$arResult['META_TAGS']['TITLE'] = $arResult['NAME'].' – купить в Москве с доставкой';
$arResult['META_TAGS']['DESCRIPTION'] = $arResult['NAME'].' - купить по цене '.$arResult['STATE']['PRICE'].' руб. в Москве с доставкой по всей России и гарантией от производителя.';

$cp = $this->__component; // объект компонента
if (is_object($cp)) {
    $cp->arResult['CHAIN'] = $arAddChainSec1;
    $cp->arResult['PRICE'] = $arPrices[0];
    $cp->SetResultCacheKeys(Array('CHAIN','PRICE'));
}
