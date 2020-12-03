<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
global $BP_TEMPLATE;
if(!empty($arResult['ITEMS'])){
    foreach ($arResult['ITEMS'] as &$item){
        //$dateCreate = new DateTime($item['CREATE_DATE']);
        $dateCreate = new DateTime($item['DATE_ACTIVE_FROM']);
        $item['FORMATE_DATE_CREATE'] = date_format($dateCreate, 'd.m.Y');

        //для мета
        $arArtNames[] = strtolower($item['NAME']);
    }

}
unset($item);

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
