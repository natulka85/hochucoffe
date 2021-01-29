<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if(empty($arResult))
    return '';

if(isset($arResult['FILTERS'])){
    $arFilters = $arResult['FILTERS'];
    unset($arResult['FILTERS']);
}

if(count($arFilters)>0){
    $arResult = array_merge($arResult,$arFilters);
}

$strReturn = '<div class="breadcrumbs"><div class="breadcrumbs__list">';

for($index = 0, $itemSize = count($arResult); $index < $itemSize; $index++)
{
    $list = '';
    if(count($arResult[$index]["ADDITIONAL"])>0){
        $list = ' is-list';
    }
    $strReturn .= '<div class="breadcrumbs__item'.$list;
    if($arResult[$index]["LINK"] != "" && $index != $itemSize-1){
        $strReturn .= ' is-link';
    }
    $strReturn .= '">';

    $title = htmlspecialcharsex($arResult[$index]["TITLE"]);

    if($arResult[$index]["LINK"] != "" && $index != $itemSize-1){
        $strReturn .= '<a class="breadcrumbs__link" href="'.$arResult[$index]["LINK"].'">'.$title.'</a>';
        if(count($arResult[$index]["ADDITIONAL"])>0){
            $strReturn .= '<div class="breadcrumbs__plus"></div>';
            $strReturn .= '<div class="breadcrumbs__sec-lvl">';
            $strReturn .= '<div class="breadcrumbs__sec-lvl-cont">';
                foreach ($arResult[$index]["ADDITIONAL"] as $ad){
                    if($ad['TITLE']!=$title){
                        $strReturn .= '<div class="breadcrumbs__item">';
                        $strReturn .= '<a class="breadcrumbs__link" href="'.$ad["LINK"].'">'.$ad['TITLE'].'</a>';
                        $strReturn .= '</div>';
                    }
                }
            $strReturn .= '</div>';
            $strReturn .= '</div>';
        }
    }
    else{
        $strReturn .= '<span class="breadcrumbs__span">'.$title.'</span>';
        if(count($arResult[$index]["ADDITIONAL"])>0){
            $strReturn .= '<div class="breadcrumbs__plus"></div>';
            $strReturn .= '<div class="breadcrumbs__sec-lvl">';
            $strReturn .= '<div class="breadcrumbs__sec-lvl-cont">';
            foreach ($arResult[$index]["ADDITIONAL"] as $ad){
                if($ad['TITLE']!=$title){
                    $strReturn .= '<div class="breadcrumbs__item">';
                    $strReturn .= '<a class="breadcrumbs__link" href="'.$ad["LINK"].'">'.$ad['TITLE'].'</a>';
                    $strReturn .= '</div>';
                }
            }
            $strReturn .= '</div>';
            $strReturn .= '</div>';
        }
    }

    $strReturn .= '</div>';
}

$strReturn .= '</div></div>';
//$strReturn .= print_r($arResult, true);

return $strReturn;?>
