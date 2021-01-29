<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
global $BP_TEMPLATE;
$title_length = 120;
if(!empty($arResult['ITEMS'])){
    foreach ($arResult['ITEMS'] as &$item){
        //$dateCreate = new DateTime($item['CREATE_DATE']);
        $dateCreate = new DateTime($item['DATE_ACTIVE_FROM']);
        $item['FORMATE_DATE_CREATE'] = date_format($dateCreate, 'd.m.Y');

        //для мета
        $arArtNames[] = strtolower($item['NAME']);

        if(strlen($item['~PREVIEW_TEXT']) >  $title_length){
            $shotPrevText = substr($item['~PREVIEW_TEXT'], 0, $title_length);
            $item['PREVIEW_TEXT_SHORT'] = substr($shotPrevText, 0, strrpos($shotPrevText, ' ')).'...';
        }
        else{
            $item['PREVIEW_TEXT_SHORT'] = $item['~PREVIEW_TEXT'];
        }
    }

}
unset($item);

//sort
if(count($arParams['SORT_LIST'])>0){
    $arPart = explode('_', $arParams["SORT_CODE"]);

    if(strpos($arParams["SORT_CODE"],'_min')!==false||
        strpos($arParams["SORT_CODE"],'_max')!==false) {
        if (isset($arParams['SORT_LIST'][$arParams["SORT_CODE"]])) {
            unset($arParams['SORT_LIST'][$arParams["SORT_CODE"]]);
        }
    }
    if($arPart[0]!='prise'){
        if(isset($arParams['SORT_LIST']['prise_max']))
            unset($arParams['SORT_LIST']['prise_max']);
    }
    if($arPart[0]!='data'){
        if(isset($arParams['SORT_LIST']['data_min']))
            unset($arParams['SORT_LIST']['data_min']);
    }

    foreach ($arParams['SORT_LIST'] as $sort_code=>&$list){
        if(strpos($sort_code,$arPart[0])!==false){
            $list['class'] .=' is-active';
        }
    }

}

//hash
$arResult["NAV_STRING"] = preg_replace_callback(
    '/href="([^"]+)"/is',
    function($matches){
        global $BP_TEMPLATE;
        return 'href="'.$BP_TEMPLATE->Catalog()->hashurl($matches[1],true).'"';
    }
    ,$arResult["NAV_STRING"]
);
?>
