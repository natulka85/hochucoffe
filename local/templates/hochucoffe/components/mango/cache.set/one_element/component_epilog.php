<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
global $BP_TEMPLATE;
$APPLICATION->SetPageProperty("title", $arResult['TITLE']);
$APPLICATION->SetPageProperty("description", $arResult['DESC']);


$breadcrums = true;
if($arResult['SECTION']['PATH'][0]['CODE'] != 'tipy'){
    $APPLICATION->SetPageProperty("NOT_SHOW_NAV_CHAIN", "Y");
    $breadcrums = false;
}
\Bitrix\Main\Page\Frame::getInstance()->startDynamicWithID("list_dynamic_element");
if($arParams['CATEGORY_TYPE'] == 'MAIN_CATALOG_ELEMENT'){

    if($breadcrums){

        $url_refer = $BP_TEMPLATE->ChpuFilter()->convertNewToOld($_SERVER['HTTP_REFERER']);

        $FilterProps = $BP_TEMPLATE->SeoSection()->convertUrlToCheck($url_refer);
        $arFilterPropsValid = ['VKUS','GEOGRAPTHY','SPOSOB_PRIGOTOVLENIYA'];

        if((count($FilterProps['PROPS']) == 1)){
            $keyProps = array_keys($FilterProps['PROPS']);
            if(count($FilterProps['PROPS'][$keyProps[0]]) == 1 && in_array($keyProps[0], $arFilterPropsValid)){

                $rs_sect = CIBlockSection::GetList(array(), array("IBLOCK_ID"=>1,'ID'=>$arResult['SECTION']['ID']), false, array("UF_AVAIL_FILTER"));
                if($ob_sect = $rs_sect->Fetch())
                {
                    if($ob_sect['UF_AVAIL_FILTER']!=''){
                        $arValidFilterQnt = json_decode($ob_sect['UF_AVAIL_FILTER'],TRUE);
                    }
                }
                $props = $BP_TEMPLATE->ChpuFilter()->getNewPartsL();
                $i= 0;
                foreach ($arValidFilterQnt[$keyProps[0]] as $filtValues=>$filtId){
                    foreach ($props as $prop){
                        if($prop['PROP_CODE'] == strtolower($keyProps[0]) && $prop['VALUE'] == $filtValues){
                            $arAds[$i] = [
                                'TITLE' => $filtValues,
                                'LINK' => $arResult['SECTION']['SECTION_PAGE_URL'].$prop['URL'],
                            ];
                            if($FilterProps['PROPS'][$keyProps[0]][0] == $prop['XML_ID']){
                                $arAds[$i]['VERT_SORT'] = 1;
                            }
                            else{
                                $sort+=10;
                                $arAds[$i]['VERT_SORT'] = $sort;
                            }
                            $i++;
                            break;
                        }
                    }
                }
            }
            usort($arAds, function($b1,$b2){
                return (trim($b1['VERT_SORT'])>trim($b2['VERT_SORT'])) ? 1 : -1;
            });
        }

        foreach($arResult['CHAIN'] as $sec_id => $arSect)
        {
            if(is_array($arSect['CHILDS']) && count($arSect['CHILDS'])>0)
            {
                $APPLICATION->arAdditionalChain[1]['TITLE'] = $arSect['NAME'];
                $APPLICATION->arAdditionalChain[1]['LINK'] = $arSect['SECTION_PAGE_URL'];
                $arSect['NAME'] = '';  //"удаляем" из списка

                foreach($arSect['CHILDS'] as $sec_id2 => $arSect2)
                {
                    if($sec_id2 == $arResult['IBLOCK_SECTION_ID'])
                    {
                        $APPLICATION->arAdditionalChain[2]['TITLE'] = $arSect2['NAME'];
                        $APPLICATION->arAdditionalChain[2]['LINK'] = $arSect2['SECTION_PAGE_URL'];
                        $arSect2['NAME'] = '';    //"удаляем" из списка
                    }
                    if($arSect2['NAME']!='')
                        $APPLICATION->arAdditionalChain[2]['ADDITIONAL'][] = ['TITLE' => $arSect2['NAME'], 'LINK' => $arSect2['SECTION_PAGE_URL']];
                }
            } elseif($sec_id == $arResult['IBLOCK_SECTION_ID'])
            {
                $APPLICATION->arAdditionalChain[1]['TITLE'] = $arSect['NAME'];
                $APPLICATION->arAdditionalChain[1]['LINK'] = $arSect['SECTION_PAGE_URL'];
            }
            if($arSect['NAME']!='')
                $APPLICATION->arAdditionalChain[1]['ADDITIONAL'][] = ['TITLE' => $arSect['NAME'], 'LINK' => $arSect['SECTION_PAGE_URL']];
        }
        if(!empty($arAds)){

            $j = count($APPLICATION->arAdditionalChain) + 1;
            $APPLICATION->arAdditionalChain[$j] = [
                'TITLE' => $arAds[0]['TITLE'],
                'LINK' => $arAds[0]['LINK'],
            ];
            unset($arAds[0]);
            $APPLICATION->arAdditionalChain[$j]['ADDITIONAL'] = $arAds;
        }
    }
}

