<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
global $BP_TEMPLATE;

foreach($arResult["ITEMS"] as $k=>&$arItem){
    $vertsort +=10;
    $arItem['VERT_SORT'] = $vertsort;
    if(count($arItem['VALUES']) && !isset($arItem["PRICE"])){
        foreach($arItem['VALUES'] as $v){
            if($v["CHECKED"]){
                if($arItem["NAME"] == 'Акция')
                {
                    $v["VALUE"] = 'Да';
                }
                if($arItem["NAME"] == 'Хит продаж}')
                {
                    $arItem["NAME"] = 'Выбор покупателей';
                    $v["VALUE"] = 'Да';
                }

                $arResult["ACTIVE"][$v["CONTROL_ID"]] = $arItem["NAME"].':'.$v["VALUE"];
                $arResult["CHEKED_FILT"][$arItem['ID']][$v["CONTROL_ID"]] = $v;
                $arItem['CHECKED'] = true;
            }
        }
    }
}

unset($arItem);


//set404
$arCurUrl = explode('?',$arResult['FORM_ACTION']);

if(strpos($arResult['SEF_SET_FILTER_URL'],'filter/clear/')!==false)
{
    $filter_url = str_replace('filter/clear/', '', $arResult['SEF_SET_FILTER_URL']);
} else {
    $filter_url = $arResult['SEF_SET_FILTER_URL'];
}
if($arCurUrl[0]!=$filter_url && $_REQUEST['ajax']!='y')
{
    CHTTP::SetStatus("404 Not Found");
}


$curUrlLink = $APPLICATION->GetCurDir();
//чпу -  начало
//pre($arResult["JS_FILTER_PARAMS"]["SEF_SET_FILTER_URL"]);
$arUrls = array(
    "FILTER_AJAX_URL",
    "FILTER_URL",
    "FORM_ACTION",
    "SEF_SET_FILTER_URL",
);

//кастыль для акции
foreach($arUrls as $url)
{
    if($arResult[$url]=='/catalog/filter/aktsiya-is-true/')
        $arResult[$url] = '/sale/';
}

//чпу -  конец

$arResult["arConstruct"] = array(
    7 => 'Цена',
    3 => 'Вес, г',
    17 => 'Способ приготовления',
    13 => 'Состав',
    16 => 'Вкус',
    14 => 'География',
    9 => 'Регион произрастания',
    15 => 'Год урожая',
    11 => 'Способ обработки',
    12 => 'Оценка SCA',
);

//оставляем только присутствующие в фильтре
foreach($arResult["arConstruct"] as $key1 => &$item1){
    if(is_array($item1)){
        foreach($item1  as $key2 => &$item2){
            if(empty($arResult['ITEMS'][$key2]['VALUES'])){
                unset($arResult['ITEMS'][$key2]);
                unset($item1[$key2]);
            }
        }
    }
    else{
        if(empty($arResult['ITEMS'][$key1]['VALUES'])){
            unset($arResult['ITEMS'][$key1]);
            unset($arResult["arConstruct"][$key1]);
        }
    }
}
unset($item1);
unset($item2);

foreach($arResult["arConstruct"] as $key=> &$item){
    if(empty($item)){
        unset($arResult["arConstruct"][$key]);
    }
}
unset($item);
//оставляем только присутствующие в фильтре END

//epmty url with section
$arResult["FORM_EMPTY_URL"] = form_empty_url();
//pre($arResult["FORM_EMPTY_URL"]);
//редирект для отключенного js в браузере
if(array_key_exists('set_filter', $_REQUEST)){
    LocalRedirect($arResult['FILTER_URL'],301);
}

function item_tmpl($tmpl, $arItem,$quant,$struct=false) {
    global $BP_TEMPLATE;
    if($quant == '')
        $quant = 5;

    switch ($tmpl) {
        case "A":

            if($arItem["VALUES"]["MAX"]["FILTERED_VALUE"]>0 && $arItem["VALUES"]["MIN"]["FILTERED_VALUE"]>0)
            {
                $arItem["VALUES"]["MAX"]["VALUE"] = $arItem["VALUES"]["MAX"]["FILTERED_VALUE"];
                if($arItem["VALUES"]["MAX"]["HTML_VALUE"]>0 && $arItem["VALUES"]["MAX"]["HTML_VALUE"]>$arItem["VALUES"]["MAX"]["FILTERED_VALUE"])
                {
                    $arItem["VALUES"]["MAX"]["HTML_VALUE"] = $arItem["VALUES"]["MAX"]["FILTERED_VALUE"];
                }
                $arItem["VALUES"]["MIN"]["VALUE"] = $arItem["VALUES"]["MIN"]["FILTERED_VALUE"];
                if($arItem["VALUES"]["MIN"]["HTML_VALUE"]>0 && $arItem["VALUES"]["MIN"]["HTML_VALUE"]<$arItem["VALUES"]["MIN"]["FILTERED_VALUE"])
                {
                    $arItem["VALUES"]["MIN"]["HTML_VALUE"] = $arItem["VALUES"]["MIN"]["FILTERED_VALUE"];
                }
            }
            ?>
            <?$arItemValues = array(
            "minValue" => round($arItem["VALUES"]["MIN"]["VALUE"]),
            "maxValue" => round($arItem["VALUES"]["MAX"]["VALUE"]),
            "curMinValue" => round($arItem["VALUES"]["MIN"]["HTML_VALUE"]),
            "curMaxValue" => round($arItem["VALUES"]["MAX"]["HTML_VALUE"]),
            "fltMinValue" => intval($arItem["VALUES"]["MIN"]["FILTERED_VALUE"]) ? round($arItem["VALUES"]["MIN"]["FILTERED_VALUE"]) : round($arItem["VALUES"]["MIN"]["VALUE"]),
            "fltMaxValue" => intval($arItem["VALUES"]["MAX"]["FILTERED_VALUE"]) ? round($arItem["VALUES"]["MAX"]["FILTERED_VALUE"]) : round($arItem["VALUES"]["MAX"]["VALUE"]),
        );?>
            <div class="filter__fields">
                <div class="c-filt-price">
                    <div id="c-filt-price-range-<?=$arItem["ID"]?>" class="c-filt-price-range" data-curmin="<?=intval($arItemValues["curMinValue"]) ? : $arItemValues["minValue"]?>" data-curmax="<?=intval($arItemValues["curMaxValue"]) ? : $arItemValues["maxValue"]?>" data-minValue="<?=intval($arItemValues["fltMinValue"]) ? $arItemValues["fltMinValue"] : $arItemValues["minValue"]?>" data-midValue="<?=intval($arItemValues["fltMaxValue"]) ? round($arItemValues["fltMaxValue"]/10) : round($arItemValues["maxValue"]/10)?>" data-maxValue="<?=intval($arItemValues["fltMaxValue"]) ? $arItemValues["fltMaxValue"] : $arItemValues["maxValue"]?>"></div>
                    <div class="c-filt-price__values">
                        <input class="inp-text c-filt-price-field js-filter-minmax-input _min" type="text" name="<?echo $arItem["VALUES"]["MIN"]["CONTROL_NAME"]?>" id="<?echo $arItem["VALUES"]["MIN"]["CONTROL_ID"]?>" value="<?echo intval($arItem["VALUES"]["MIN"]["HTML_VALUE"]) ?: intval($arItem["VALUES"]["MIN"]["VALUE"])?>" data-default="<?echo intval($arItem["VALUES"]["MIN"]["HTML_VALUE"]) ? "false" : "true"?>" size="8" onchange="smartFilter.keyup(this)"/>
                        <input class="inp-text c-filt-price-field js-filter-minmax-input _max" type="text" name="<?echo $arItem["VALUES"]["MAX"]["CONTROL_NAME"]?>" id="<?echo $arItem["VALUES"]["MAX"]["CONTROL_ID"]?>" value="<?echo intval($arItem["VALUES"]["MAX"]["HTML_VALUE"]) ?: intval($arItem["VALUES"]["MAX"]["VALUE"])?>" data-default="<?echo intval($arItem["VALUES"]["MAX"]["HTML_VALUE"]) ? "false" : "true"?>" size="8" onchange="smartFilter.keyup(this)" />
                    </div>
                </div>
            </div>
            <?
            break;
        default:?>
            <?$c = 0?>
            <div class="filter__fields <?if(count($arItem["VALUES"])>$quant):?>js-more-list<?endif;?>">
                <?foreach ($arItem['VALUES'] as $val=>$ar):?>
                <?$c++;?>
                    <div class="filter__field <?if($c > $quant):?> js-more-hidden<?endif;?>">
                        <label class="filter__label">
                            <input class="filter__checkbox main-checkbox__checkbox" type="checkbox"
                                   name="<?=$ar["CONTROL_NAME"]?>"
                                   id="<?=$ar["CONTROL_NAME"]?>"
                                   value="<?=$ar["HTML_VALUE"]?>"
                                <?=($ar["CHECKED"]? 'checked="checked"': '')?>
                                   onclick="smartFilter.click(this);"
                            >
                            <span class="main-checkbox__span filter__span"><?=$ar['VALUE']?><span class="filter__cnt"><?if($ar['ELEMENT_COUNT']>0):?> <?=$ar['ELEMENT_COUNT']?><?endif?></span></span>

                        </label>
                    </div>
                <?endforeach;?>
                <?if(count($arItem["VALUES"])>$quant):?>
                    <div class="filter__more-btn"><span class="filter__info-btn js-more-btn">Показать еще</span></div>
                <?endif?>
            </div>

        <?
    }
}

//виды светильника
$PAR_SEC_ID = $BP_TEMPLATE->getConstants()->IBLOCK_MAIN_SEC;
//pre($arResult['SECTION']);
if($arResult['SECTION']['ID'])
    $ar_res = CIBlockSection::GetByID($arResult['SECTION']['ID'])->GetNext();
else
{
    $ar_res = array('DEPTH_LEVEL' => 1);
    $arResult['SECTION']['IBLOCK_ID'] = $BP_TEMPLATE->getConstants()->IBLOCK_MAIN_ID;
}
if($ar_res['DEPTH_LEVEL']==1 && $arResult['SECTION']['IBLOCK_ID']==$BP_TEMPLATE->getConstants()->IBLOCK_MAIN_ID) //catalog
{
    $arSect = array();
    $arFilter = Array('IBLOCK_ID'=>$arResult['SECTION']['IBLOCK_ID'], 'ACTIVE'=>'Y', 'SECTION_ID'=>$PAR_SEC_ID);
    $db_list = CIBlockSection::GetList(Array('SORT'=>'ASC'), $arFilter, false);
    while($ar_result = $db_list->GetNext())
    {
        if($ar_result['SORT']<500)
        {
            $arSect[$ar_result['ID']] = array(
                "CONTROL_NAME" => $ar_result['ID'],
                "VALUE" => $ar_result['NAME'],
                "CODE" => $ar_result['CODE'],
                'URL' => $ar_result['SECTION_PAGE_URL']
            );
        }
    }

    $arResult["ITEMS"]["SECTION"] = array(
        "TYPE" => "SECTION",
        "NAME" => "Тип светильника",
        "DISPLAY_TYPE" => "L",
        "DISPLAY_EXPANDED" => "Y",
        "VALUES" => $arSect,
    );
} elseif($ar_res['DEPTH_LEVEL']==2 && $arResult['SECTION']['IBLOCK_ID']==$BP_TEMPLATE->getConstants()->IBLOCK_MAIN_ID) //раздел
{
    $arSect = array();
    $arFilter = Array('IBLOCK_ID'=>$arResult['SECTION']['IBLOCK_ID'], 'ACTIVE'=>'Y', 'SECTION_ID'=>$PAR_SEC_ID);
    $db_list = CIBlockSection::GetList(Array('SORT'=>'ASC'), $arFilter, false);
    while($ar_result = $db_list->GetNext())
    {
        if($ar_result['SORT']<500)
        {
            $arSect[$ar_result['ID']] = array(
                "CONTROL_NAME" => $ar_result['ID'],
                "VALUE" => $ar_result['NAME'],
                "CODE" => $ar_result['CODE'],
                'URL' => $ar_result['SECTION_PAGE_URL']
            );
            if($ar_result['ID'] == $ar_res['ID'])
                $arSect[$ar_result['ID']]['CHECKED'] = 'Y';
        }
    }

    $arResult["ITEMS"]["SECTION"] = array(
        "TYPE" => "SECTION",
        "NAME" => "Тип светильника",
        "DISPLAY_TYPE" => "L",
        "DISPLAY_EXPANDED" => "Y",
        "VALUES" => $arSect,
    );

    $arSect = array();
    $arFilter = Array('IBLOCK_ID'=>$arResult['SECTION']['IBLOCK_ID'], 'ACTIVE'=>'Y', 'SECTION_ID'=>$ar_res['ID']);
    $db_list = CIBlockSection::GetList(Array('SORT'=>'ASC'), $arFilter, false);
    while($ar_result = $db_list->GetNext())
    {
        if($ar_result['SORT']<500)
        {
            $arSect[$ar_result['ID']] = array(
                "CONTROL_NAME" => $ar_result['ID'],
                "VALUE" => $ar_result['NAME'],
                "CODE" => $ar_result['CODE'],
                'URL' => $ar_result['SECTION_PAGE_URL']
            );
        }
    }

    $arResult["ITEMS"]["SUBSECTION"] = array(
        "TYPE" => "SUBSECTION",
        "NAME" => "Вид светильника",
        "DISPLAY_TYPE" => "L",
        "DISPLAY_EXPANDED" => "Y",
        "VALUES" => $arSect,
    );
} elseif($ar_res['DEPTH_LEVEL']==3 && $arResult['SECTION']['IBLOCK_ID']==$BP_TEMPLATE->getConstants()->IBLOCK_MAIN_ID) //подраздел
{
    $arSect = array();
    $arFilter = Array('IBLOCK_ID'=>$arResult['SECTION']['IBLOCK_ID'], 'ACTIVE'=>'Y', 'SECTION_ID'=>$PAR_SEC_ID);
    $db_list = CIBlockSection::GetList(Array('SORT'=>'ASC'), $arFilter, false);
    while($ar_result = $db_list->GetNext())
    {
        if($ar_result['SORT']<500)
        {

            $arSect[$ar_result['ID']] = array(
                "CONTROL_NAME" => $ar_result['ID'],
                "VALUE" => $ar_result['NAME'],
                "CODE" => $ar_result['CODE'],
                'URL' => $ar_result['SECTION_PAGE_URL']
            );
            if($ar_result['ID'] == $ar_res['IBLOCK_SECTION_ID'])
                $arSect[$ar_result['ID']]['CHECKED'] = 'Y';
        }
    }

    $arResult["ITEMS"]["SECTION"] = array(
        "TYPE" => "SECTION",
        "NAME" => "Тип светильника",
        "DISPLAY_TYPE" => "L",
        "DISPLAY_EXPANDED" => "Y",
        "VALUES" => $arSect,
    );

    $arSect = array();
    $arFilter = Array('IBLOCK_ID'=>$arResult['SECTION']['IBLOCK_ID'], 'ACTIVE'=>'Y', 'SECTION_ID'=>$ar_res['IBLOCK_SECTION_ID']);
    $db_list = CIBlockSection::GetList(Array('SORT'=>'ASC'), $arFilter, false);
    while($ar_result = $db_list->GetNext())
    {
        if($ar_result['SORT']<500)
        {

            $arSect[$ar_result['ID']] = array(
                "CONTROL_NAME" => $ar_result['ID'],
                "VALUE" => $ar_result['NAME'],
                "CODE" => $ar_result['CODE'],
                'URL' => $ar_result['SECTION_PAGE_URL']
            );
            if($ar_result['ID'] == $ar_res['ID'])
                $arSect[$ar_result['ID']]['CHECKED'] = 'Y';
        }
    }

    $arResult["ITEMS"]["SUBSECTION"] = array(
        "TYPE" => "SUBSECTION",
        "NAME" => "Вид светильника",
        "DISPLAY_TYPE" => "L",
        "DISPLAY_EXPANDED" => "Y",
        "VALUES" => $arSect,
    );
}
global $GLOBAL_CUSTOM;
$GLOBAL_CUSTOM['SECTIONS'] = $arResult["ITEMS"]['SECTION'];
$GLOBAL_CUSTOM['SUBSECTION'] = $arResult["ITEMS"]['SUBSECTION'];

function form_empty_url() {
    global $APPLICATION;
    $FORM_ACTION = $APPLICATION->GetCurPage();
    $val = substr($FORM_ACTION, 0, strpos($FORM_ACTION, 'filter/'));
    return $val;
}