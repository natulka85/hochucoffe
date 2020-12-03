<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
global $BP_TEMPLATE;
if($arResult['PROPERTIES']['ARTICLES_SECTION']['VALUE'] != ''){
   global $articleSection;
    $articleSection = $arResult['PROPERTIES']['ARTICLES_SECTION']['VALUE'];
}
preg_match_all('/{addgoods:(.*)}/', $arResult["DETAIL_TEXT"], $all_matches );

if(count($all_matches)==2)
{
    foreach($all_matches[1] as $k=>$matche) {
        ob_start();
        ?>
        <div class="choose-list">
        <?
            $matche = explode(';',$matche);
            $GLOBALS["filterElArticles"]["CODE"] = $matche;

        $arSettings = [
            "IBLOCK_TYPE" => $BP_TEMPLATE->getConstants()->IBLOCK_MAIN_TYPE,
            "IBLOCK_ID" => $BP_TEMPLATE->getConstants()->IBLOCK_MAIN_ID,
            "COUNT_ON_PAGE" => count($GLOBALS["filterElArticles"]["CODE"]),
            "CACHE_TIME"  =>  $arParams["CACHE_TIME"],
            "FILTER_NAME" => 'filterElArticles',
            "DISPLAY_TOP_PAGER" => "N",
            "DISPLAY_BOTTOM_PAGER" => "N",
            //'COUNT_LIST' => '1',
            //'SORT_LIST' => '1',
        ];
        $dp_tmp = '';
        if(count($GLOBALS["filterElArticles"]["CODE"])>4)
        {
            $dp_tmp = 'slider';
            $arSettings['LIST_CLASS'] = 'js-slick-viewed js-twin-slide-for-mobile is-new-slick';
            $arSettings['LIST_DATA'] = 'data-width_limit=766';
        }
        $APPLICATION->IncludeComponent(
            "bp:element.list",
            $dp_tmp,
            $arSettings,
            false
        );?>
        </div>
        <?
        $item = ob_get_contents();
        ob_end_clean();

        $arResult["DETAIL_TEXT"] = str_replace($all_matches[0][$k], $item, $arResult["DETAIL_TEXT"]);
    }
}

$templateUrl = $arParams['MOD_TAGS_URL'];
//$arSection = $BP_TEMPLATE->SectionsForArt($BP_TEMPLATE->getConstants()->IBLOCK_MAIN_ID,$templateUrl);

$arFilter = array();
if($$arParams['FILTER_NAME'] != '')
    $arFilter = $$arParams['FILTER_NAME'];

$arrFilter = array(
    'IBLOCK_ID' => $arParams['IBLOCK_ID'],
    'ACTIVE' => 'Y'
);

$merFilter = array_merge($arFilter , $arrFilter);
//$arItems = $BP_TEMPLATE->ElementsForArt($merFilter);
$arTags  = array();
foreach ($arItems as $item){
    foreach($item['PROPERTIES']['ARTICLES_SECTION_MULTI']['VALUE'] as $section_id){
        foreach($arSection as $sect){
            if($section_id == $sect['ID'] || in_array($section_id, $sect['CHILDRENS']))
            {
                if(!isset($arTags[$sect['ID']]))
                    $arTags[$sect['ID']] = $sect;

                if($item['ID'] == $arResult['ID']){
                    $arResult['MOD_CUR_SECTION'] = $sect;
                }
                break(1);
            }
        }
    }
}
usort($arTags,
    function ($a, $b){
        if ($a['SORT'] == $b['SORT']) {
            return 0;
        }
        return ($a['SORT'] < $b['SORT']) ? -1 : 1;
    });

//похожие статьи
$currentIdSection = $arResult['MOD_CUR_SECTION']['CHILDRENS'];
$currentIdSection[$arResult['MOD_CUR_SECTION']['ID']] = $arResult['MOD_CUR_SECTION']['ID'];

foreach($arItems as $item){
    $arDiff = array_uintersect ( $currentIdSection,$item['PROPERTIES']['ARTICLES_SECTION_MULTI']['VALUE'],'strcasecmp');
    if(count($arDiff)>0 && $item['ID'] != $arResult['ID']){
        $arResult['MOD_SIMILAR'][] = $item;
    }
    if(count($arResult['MOD_SIMILAR']) == 3)
        break;
}
if(!empty($arResult['MOD_SIMILAR'])){
    foreach ($arResult['MOD_SIMILAR'] as &$item){
        //$dateCreate = new DateTime($item['CREATE_DATE']);
        $dateCreate = new DateTime($item['DATE_ACTIVE_FROM']);
        $item['FORMATE_DATE_CREATE'] = date_format($dateCreate, 'd.m.Y');
    }

}
unset($item);
//похожие статьи END


//товары под статью
if(count($arResult['PROPERTIES']['ARTICLES_SECTION']['VALUE'])>1){//в приоритете определение по  этому разделу
    foreach($arResult['PROPERTIES']['ARTICLES_SECTION']['VALUE'] as $section_id) {
        foreach ($arSection as $sect) {
            if ($section_id == $sect['ID'] || in_array($section_id, $sect['CHILDRENS'])) {
                $sect['SECTION_PAGE_URL'] = '/catalog/section/' . $sect['CODE'] . '/';
                $arResult['MOD_CUR_SECTION_2'] = $sect;
                break(1);
            }
        }
    }
}
elseif(count($arResult['PROPERTIES']['ARTICLES_SECTION']['VALUE'])==1){
    $arResult['MOD_CUR_SECTION_2'] = array_shift($arResult['DISPLAY_PROPERTIES']['ARTICLES_SECTION']['LINK_SECTION_VALUE']);
}

$goodValid = true;
$arFilterProp = [];
$arFilterMain = ["IBLOCK_ID"=>1,"ACTIVE"=>"Y", '=PROPERTY_VIDIMOST' => 'Y', '>CATALOG_QUANTITY' => 0,'INCLUDE_SUBSECTIONS'=>'Y'];

if($arResult['MOD_CUR_SECTION_2']!=''){
    $arFilterMain['SECTION_ID'] = $arResult['MOD_CUR_SECTION_2']['ID'];
    $section_url = $arResult['MOD_CUR_SECTION_2']['SECTION_PAGE_URL'];
}
elseif ($arResult['MOD_CUR_SECTION'] != ''){
    $arFilterMain['SECTION_ID'] = $arResult['MOD_CUR_SECTION']['CHILDRENS'];
    $section_url = '/catalog/section/'.$arResult['MOD_CUR_SECTION']['CODE'].'/';
}
else{
    $goodValid = false;
}

if($goodValid){
    $url = $arResult['PROPERTIES']['FILTER_URL']['VALUE'];

    if($url!=''){
        $filterUrl = $BP_TEMPLATE->SeoSection()->convertUrlToCheck($url);

        $property_enums = CIBlockPropertyEnum::GetList(Array(),array('IBLOCK_ID'=>1,'CODE'=>array_keys($filterUrl['PROPS'])));
        while($enum_fields = $property_enums->GetNext())
        {
            $arProps[$enum_fields['PROPERTY_CODE']][$enum_fields['XML_ID']] = $enum_fields['ID'];
        }

        if(count($filterUrl['PROPS'])>0){
            foreach($filterUrl['PROPS'] as $prop_code => $prop){
                foreach($prop as $xmlid){
                    $arFilterProp['=PROPERTY_'.$prop_code][] =  $arProps[$prop_code][$xmlid];
                }
            }
        }
        unset($prop_code,$prop);

        if(count($filterUrl['DIAP'])>0){
            foreach($filterUrl['DIAP'] as $prop_code => $prop){
                if(count($prop) == 2){
                    $arFilterProp['><PROPERTY_'.$prop_code] = [$prop['MIN'],$prop['MAX']];
                }
                elseif($prop['MIN'] >=0){
                    $arFilterProp['>PROPERTY_'.$prop_code] = $prop['MIN'];
                }
                elseif($prop['MAX'] >=0){
                    $arFilterProp['<PROPERTY_'.$prop_code] = $prop['MAX'];
                }
            }
        }
    }

    $arFilter = array_merge($arFilterMain,$arFilterProp);
    $arSelect = Array("ID", "IBLOCK_ID", "NAME",);//IBLOCK_ID и ID обязательно должны быть указаны, см. описание arSelectFields выше

    $res = CIBlockElement::GetList(Array(), $arFilter, false, Array("nTopCount"=>3), $arSelect);
    while($ob = $res->Fetch()){
        $arResult['MOD_GOODS']['ID'][] = $ob['ID'];
    }
    $arResult['MOD_GOODS']['LINK'] = $section_url;
    if($url!=''){
        $arResult['MOD_GOODS']['LINK'] .= 'filter'.str_replace('%','',$url);
    }
}

//товары под статью END

$cp = $this->__component; // объект компонента
if (is_object($cp)) {
    //$cp->arResult['DATE_ACTIVE_TO'] = $arAddChainSec1;
    $cp->arResult['MOD_TAGS'] = $arTags;
    $cp->SetResultCacheKeys(Array('PREVIEW_TEXT','MOD_TAGS','MOD_CUR_SECTION'));
}
?>
