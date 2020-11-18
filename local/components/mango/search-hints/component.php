<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/** @var CBitrixComponent $this */
/** @var array $arParams */
/** @var array $arResult */
/** @var string $componentPath */
/** @var string $componentName */
/** @var string $componentTemplate */
/** @global CDatabase $DB */
/** @global CUser $USER */
/** @global CMain $APPLICATION */
if(!Cmodule::includeModule('iblock'))
{
    ShowError(GetMessage("CC_BCF_MODULE_NOT_INSTALLED"));
    return;
}

if(!isset($arParams["CACHE_TIME"]))
    $arParams["CACHE_TIME"] = 36000000;

global $BP_TEMPLATE;

$arResult['ITEMS'] = array();

CModule::IncludeModule("search");
CModule::IncludeModule("iblock");
CModule::IncludeModule("catalog");
global  $BP_TEMPLATE;
//$arParams['REQUEST'] = 'гомер';
$request = $arParams['REQUEST'];
$request_section = $arParams['REQUEST_SECTION'];
$Iblock_filt = $arParams['IBLOCK_ID'];

if(strlen($request)>0){
    //категории
    $obCache = new CPHPCache();
    if($obCache->InitCache($arParams["CACHE_TIME"], serialize($arParams), "/iblock/search-hint")) // Если кэш валиден
    {
        $arResult = $obCache->GetVars();// Извлечение переменных из кэша
        $arSections = $arResult['arSections'];

    }
    elseif($obCache->StartDataCache())// Если кэш невалиден
    {
        $arSections = array();

        $res = CIBlockSection::GetByID($BP_TEMPLATE->getConstants()->IBLOCK_MAIN_TYPE);
        if($ar_res = $res->GetNext()){
            $leftMarginMainSect = $ar_res['LEFT_MARGIN'];
            $rightMarginMainSect = $ar_res['RIGHT_MARGIN'];
        }

        $arFilter = array('IBLOCK_ID' => $arParams['IBLOCK_ID'],'>LEFT_MARGIN' => $leftMarginMainSect, '<RIGHT_MARGIN' => $rightMarginMainSect, 'DEPTH_LEVEL'=>[2,3]);
        $arSelect = array('ID', 'IBLOCK_ID','NAME', "CODE", 'EXTERNAL_ID','IBLOCK_SECTION_ID', 'SORT', 'DEPTH_LEVEL');
        $rsSections = CIBlockSection::GetList(array('SORT' => 'ASC'), $arFilter, false, $arSelect, false);
        while ($arSction = $rsSections->Fetch())
        {
            if($arSction['SORT'] < 500){
                $arKeyWords = $BP_TEMPLATE->SeoSection()->getSeoConst(array('secname', 'sec_search_tags'),$arSction['ID']);
                $keyWords = implode(' ', $arKeyWords);
                $arSction['KEY_WORDS'] = str_replace(' |','',trim($keyWords));

                $link = '';
                $link = '/catalog/section/'.$arSction['CODE'].'/';
                $arSction['LINK'] = $link;
                $arSction['NAME'] = $arKeyWords[0];

                unset($arKeyWords);
                $arSections[$arSction['ID']] = $arSction;
            }
        }

        $arResult = array(
            'arSections' => $arSections,
        );
        $obCache->EndDataCache($arResult);
    }
    unset($arResult['arSections']);

    $arRequest = preg_split("/[\s,.:-]+/", $request);
    $excludeWords = $arParams['EXCLUDES_SEARCH'];

    $arSectionsMod = $arSections;

    foreach($arSectionsMod as $id => &$section){
        foreach($arRequest as $word){
            $valWord = trim($word);
            $wordLen = strlen($valWord);
            if($wordLen >= 3 && !in_array($valWord,$excludeWords)) {
                if ($wordLen <= 4)
                    $letLimit = $wordLen - 1;
                else
                    $letLimit = $wordLen - 3;

                while (strlen($valWord) > $letLimit) {
                    if (strripos($section['KEY_WORDS'], $valWord) !== false) {
                        foreach ($section['find'] as $find) {
                            if (strripos($find, $valWord) !== false)
                                break 2;
                        }
                        $section['find'][] = strtolower($valWord);
                        $section['find_cnt_lat'] += strlen($valWord);
                        $resSect[$section['ID']] = $section;
                    }

                    $valWord = substr($valWord, 0, -1);
                }
            }
        }
    }
    unset($section);

    if(!empty($resSect)){
        foreach ($resSect as $sect){
            //$fullNameSect = $BP_TEMPLATE->SeoSection()->getSeoConst('secname',$sect['ID']);
            //$fullNameSect = strtolower($fullNameSect);

            //теперь проверяем встречается ли найденное слово в fullname
            //if($fullNameSect !='');
            // $findCnt = FindWords($sect['find'], $fullNameSect);

            //if($findCnt > 0){
            $arCommonSect[] = array(
                'NAME' => $BP_TEMPLATE->str_fst_upper($sect['NAME']),
                'LINK' => $sect['LINK'],
                'EXTERNAL_ID' => $sect['EXTERNAL_ID'],
                'FIND_WORDS' => $sect['find'],
                'FIND_LAT' => $sect['find_cnt_lat'],
                'QUANTITY' => $sect['QUANTITY']
            );
            //}
        }
    }
    usort($arCommonSect, function ($a, $b) {
        return (trim($a['FIND_LAT'])<trim($b['FIND_LAT'])) ? 1 : -1;
    });
    $arResult['SECTIONS'] = $arCommonSect;
    unset($arCommonSect);
//товары

    $arFilter = [
        'SITE_ID' => 's1',
        'QUERY' => $request,
        'TAGS' => FALSE,
        'CHECK_DATES' => 'Y',
    ];
    if($request_section>0){
        $arFilter['PARAMS'] = ['iblock_section'=>$request_section];
    }

    $aSort = [
        'CUSTOM_RANK' => 'ASC',
        'RANK' => 'DESC',
        'DATE_CHANGE' => 'DESC',
    ];
    $exFILTER = [
        [
            '=MODULE_ID' => 'iblock',
            'PARAM1' => 'catalog',
            'PARAM2' => $Iblock_filt,
        ]
    ];

    $obSearch = new CSearch();
//When restart option is set we will ignore error on query with only stop words
    $obSearch->SetOptions([
        "ERROR_ON_EMPTY_STEM" => false,
        "NO_WORD_LOGIC" => true,
    ]);
    $obSearch->Search($arFilter, $aSort, $exFILTER);

    if($obSearch->errorno==0)
    {
        $ar = $obSearch->GetNext();
        //Search restart
        if(!$ar && $obSearch->Query->bStemming)
        {
            $exFILTER["STEMMING"] = false;
            $obSearch = new CSearch();
            $obSearch->Search($arFilter, $aSort, $exFILTER);

            if($obSearch->errorno == 0)
            {
                $ar = $obSearch->GetNext();
            }
        }

        $arItems = [];
        //$arIblocksId = [];
        while($ar)
        {
            //$arIblocksId[] = $ar['PARAM2'];
            if(count($arItems)<10){
                $arItems[$ar["ITEM_ID"]] = $ar;
                $ar = $obSearch->GetNext();
            }
            else
                break;
        }
    }

    $arItemsId = array_keys($arItems);

    if(count($arItemsId)>0){

        $res = CIBlockElement::GetList(
            array(),
            array("IBLOCK_ID" => $Iblock_filt,'IBLOCK_TYPE'=>'catalog', 'ACTIVE' => 'Y', 'ID'=>$arItemsId),
            false,
            false,
            array("ID",
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
        //\Bitrix\Main\Diag\Debug::startTimeLabel('mark1');

        $arrElements = array();
        while ($el = $res->Fetch()) {
            $arItemsAdditional['STATE'] = $BP_TEMPLATE->Catalog()->state(
                $el["IBLOCK_ID"],
                $el["CATALOG_QUANTITY"],
                $el['CATALOG_PRICE_1'],//$arItem['PRICES']['rozn']['VALUE'],
                $el['CATALOG_PRICE_2']//$arItem['PRICES']['Акция на сайте']['VALUE'],
            );

            $arItemsAdditional['LABLES'] = $BP_TEMPLATE->Catalog()->lables(
                $el["IBLOCK_ID"],
                $el['CATALOG_PRICE_1'],//$arItem['PRICES']['rozn']['VALUE'],
                $el['CATALOG_PRICE_2'],//$arItem['PRICES']['Акция на сайте']['VALUE'],
                $el["PROPERTY__NOVINKA_VALUE"],
                $el["PROPERTY_ARTICLE_COMP_VALUE"],
                $el['PROPERTY__HIT_PRODAZH_VALUE']
            );

            $arItemsAdditional['PREVIEW_PICTURE'] = CFile::getPath($el['PREVIEW_PICTURE']);
            $arItems[$el['ID']] = array_merge($arItemsAdditional,$arItems[$el['ID']]);
        }
    }
    $arResult['ITEMS'] = $arItems;
    unset($arItems);
}

$this->IncludeComponentTemplate();
?>
