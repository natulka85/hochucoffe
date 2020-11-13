<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if(empty($arResult))
    return '';


$strReturn = '<div class="breadcrumbs"><div class="breadcrumbs__list">';

for($index = 0, $itemSize = count($arResult); $index < $itemSize; $index++)
{
    $strReturn .= '<div class="breadcrumbs__item">';

    $title = htmlspecialcharsex($arResult[$index]["TITLE"]);
    if($arResult[$index]["LINK"] != "" && $index != $itemSize-1){
        $strReturn .= '<a class="breadcrumbs__link" href="'.$arResult[$index]["LINK"].'">'.$title.'</a>';
    }
    else
        $strReturn .= '<span class="breadcrumbs__span">'.$title.'</span>';

    $strReturn .= '</div>';
}

$strReturn .= '</div></div>';
//$strReturn .= print_r($arResult, true);

return $strReturn;?>
