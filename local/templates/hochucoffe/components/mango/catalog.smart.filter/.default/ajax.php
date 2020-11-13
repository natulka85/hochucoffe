<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$APPLICATION->RestartBuffer();
unset($arResult["COMBO"]);

//чпу -  начало
$arUrls = array(
    "FILTER_AJAX_URL",
    "FILTER_URL",
    "FORM_ACTION",
    "SEF_SET_FILTER_URL",
);
/*foreach($arResult["ITEMS"] as $items)
{
    //$id = $items['ID'];
    if($items['PROPERTY_TYPE']=='L')
    {
        foreach($items["VALUES"] as $id=>$value)
        {
            $val = $Acmilan->get_url_by_prop($value["VALUE"], $id);

            foreach($arUrls as $url)
            {
                $arResult[$url] = str_replace($value["URL_ID"], $val, $arResult[$url]);
            }
        }
    }
} */
//кастыль для акции
global $BP_TEMPLATE;
foreach($arUrls as $url)
{
    if($arResult[$url]!='/stock/')
        $arResult[$url] = $BP_TEMPLATE->ChpuFilter()->convertOldToNew($arResult[$url]);

    if($arResult[$url]=='/catalog/aktsiya-is-true/')
        $arResult[$url] = '/stock/';
    if(strpos($arResult[$url], 'filter/clear/')!==false)
        $arResult[$url] = str_replace('filter/clear/','',$arResult[$url]);

    if(strpos($arResult[$url], 'clear/')!==false)
        $arResult[$url] = str_replace('clear/','',$arResult[$url]);

}

//чпу -  конец

echo CUtil::PHPToJSObject($arResult, true);
?>
