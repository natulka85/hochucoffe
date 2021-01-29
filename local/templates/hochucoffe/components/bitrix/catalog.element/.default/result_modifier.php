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


    $no_photo_src = '/local/templates/hochucoffe/static/dist/images/general/no-photo.png';
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
        $arResult["PROPERTIES"]["ARTICLE_COMP"]["VALUE"],
        $arResult['PROPERTIES']['_HIT_PRODAZH']['VALUE'],
        $arResult['PROPERTIES']['OCENKA_SCA']['VALUE'],
        $arResult['PROPERTIES']['_STRANA']['VALUE']
    );
    if($arResultsAdditional['LABLES']['COUNTRY']['IMG']!=''){
        $arFile = CFile::ResizeImageGet(
            $arResultsAdditional['LABLES']['COUNTRY']['IMG'],
            array("width" => 60, "height" => 60),
            BX_RESIZE_IMAGE_PROPORTIONAL_ALT ,
            true
        );
        $arResultsAdditional['LABLES']['COUNTRY']['IMG_AR'] = $arFile;
    }

    //state
    $arResultsAdditional['STATE'] = $BP_TEMPLATE->Catalog()->state(
        $arResult["IBLOCK_ID"],
        $arResult["CATALOG_QUANTITY"],
        $arResult['PRICES']['max'],//$arResult['PRICES']['rozn']['VALUE'],
        $arResult['PRICES']['min']//$arResult['PRICES']['Акция на сайте']['VALUE'],
    );

    foreach ($arResultsAdditional['STATE']['DATA'] as $k=>$data){
        $ar = explode('||',$data);
        //проверяем есть ли значение в товарах чтобы выставить по умолчанию
        if(!in_array($ar[2],$arResult['PROPERTIES'][$ar[0]]['VALUE'])) {
            $ar[2] = $arResult['PROPERTIES'][$ar[0]]['VALUE'][0];
            $arResultsAdditional['STATE']['DATA'][$k] = implode('||', $ar);
        }
    }
    foreach ($arResultsAdditional['STATE']['DATA'] as $k=>$data){
        $ar = explode('||',$data);
        //проверяем есть ли значение в товарах чтобы выставить по умолчанию
        $arResultsAdditional['STATE']['DATA_AR'][$ar[0]] = $ar;
    }

    $arResult = array_merge($arResultsAdditional, $arResult);

    if(count($arResult['PROPERTIES']['VKUS']['VALUE_ENUM'])>0){
        $vkus = $BP_TEMPLATE->str_fst_upper(mb_strtolower(implode(', ',$arResult['PROPERTIES']['VKUS']['VALUE_ENUM'])));
        $arResult['PROPERTIES']['VKUS']['VALUE_FORMATTED'] =  $vkus;
        unset($vkus);
    }
    if(count($arResult['PROPERTIES']['SPOSOB_PRIGOTOVLENIYA']['VALUE'])>0){
        $sposob_pregotovleniya = strtolower(implode(' / ',$arResult['PROPERTIES']['SPOSOB_PRIGOTOVLENIYA']['VALUE']));
        $arResult['PROPERTIES']['SPOSOB_PRIGOTOVLENIYA']['VALUE'] =  $sposob_pregotovleniya;
        unset($sposob_pregotovleniya);
    }

    if($arResult['PROPERTIES']['WEIGHT_VAR']['~VALUE']!=''){
        $arResult['PROPERTIES']['WEIGHT_VAR_AR'] = json_decode($arResult['PROPERTIES']['WEIGHT_VAR']['~VALUE'],TRUE);
        asort($arResult['PROPERTIES']['WEIGHT_VAR_AR'],SORT_NUMERIC);
    }
    if($arResult['PROPERTIES']['WEIGHT']['VALUE']!=''){
        $arResult['MOD_PRICE_100_G'] = round($arResult['STATE']['PRICE'] / $arResult['PROPERTIES']['WEIGHT']['VALUE']  * 100,0);
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
    'VISOTA_PROIZRASTANIYA',
    'SPOSOB_OBRABOTKI',
    "SPOSOB_PRIGOTOVLENIYA",
];
$arResult['TMPL_PROPS_DOP_OPTIONS'] = $BP_TEMPLATE->Catalog()->dopProperties;
$arResult['POMOL_TIPS'] = $BP_TEMPLATE->pomolSposob;
$arResult['POMOL_TIPS_TEMP'] = $BP_TEMPLATE->pomolSposobTem;
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

$arReviewRes = [
    5 => '',
    4 => '',
    3 => '',
    2 => '',
    1 => '',
];

if($arResult['PROPERTIES']['ASKARON_REVIEWS_COUNT']['VALUE']>0){
    $res = CIBlockElement::GetList(
        [],
        ['IBLOCK_ID' => 6,'ACTIVE'=>'Y'],
        false,
        false,
        [
            'ID',
            'NAME',
            'PREVIEW_PICTURE',
            'PROPERTY_POSITION',
        ]
    );
    while ($el = $res->Fetch()) {
        $arFile = CFile::ResizeImageGet(
            $el['PREVIEW_PICTURE'],
            array("width" => 150, "height" => 150),
            BX_RESIZE_IMAGE_PROPORTIONAL ,
            true
        );
        $el['PICTURE'] = $arFile;
        $arManagers[$el['ID']] = $el;
    }
    unset($res);

    CModule::IncludeModule("askaron.reviews");

    $res = \Askaron\Reviews\ReviewTable::getList(array(
        'filter' => array('ELEMENT_ID' => $arResult['ID'],'ACTIVE'=>'Y'),
        'order' => array('ID'=>'desc'),
        'select' => array('*')
    ));
    while($ob = $res->fetch()){
    $ob['MANAGER'] = $arManagers[$ob['USER_ID']];
     $arResult['MOD_REVIEWS'][] = $ob;
     $arReviewRes[$ob['GRADE']]++;
    }
}
else{
    $arResult['PROPERTIES']['ASKARON_REVIEWS_COUNT']['VALUE'] = 0;
}
$arResult['MOD_REVIEWS_RES'] = $arReviewRes;
$arResult['MOD_REVIEW_AVERAGE'] = round($arResult['PROPERTIES']['ASKARON_REVIEWS_AVERAGE']['VALUE'],0);

$arResult['LABLES_TEMPLATE'] = [
    'LEFT' => ['HIT','NEW'/*,'ACTION'*/],
    'RIGHT' => [/*'COUNTRY',*/'SCA'],
];

$cp = $this->__component; // объект компонента
if (is_object($cp)) {
    $cp->arResult['CHAIN'] = $arAddChainSec1;
    $cp->arResult['PRICE'] = $arPrices[0];
    $cp->arResult['STATE'] = $arResult['STATE'];
    $cp->SetResultCacheKeys(Array('CHAIN','PRICE','STATE'));
}

