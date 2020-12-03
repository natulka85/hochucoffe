<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
global $BP_TEMPLATE;
global $APPLICATION;

if($arParams['CATEGORY_TYPE']=='ARTSECTION'){


    $APPLICATION->arAdditionalChain[1]['TITLE'] = $arParams['MOD_CUR_SECTION']['NAME'];
    $APPLICATION->arAdditionalChain[1]['LINK'] = $arParams['MOD_CUR_SECTION']['LINK'];

    foreach ($arResult['MOD_CACHE']['MOD_TAGS'] as $tag){
        if($tag['ID'] == $arParams['MOD_CUR_SECTION']['ID'])
            continue;

        $APPLICATION->arAdditionalChain[1]['ADDITIONAL'][] =
            ['TITLE' => TruncateText($tag['NAME'], 18),
                'LINK' => $tag['LINK']];
    }
}

?>
