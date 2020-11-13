<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

global $BP_TEMPLATE;

if(isset($arResult['ITEMS'])) {

    foreach($arResult["ITEMS"] as $cell=>&$arElement)
    {
        $arPrices = [];
        foreach($arElement["PRICES"] as $PRICE)
        {
            $arPrices[] = $PRICE;
        }
        $arResult["ITEMS"][$cell]["PRICES"]['min'] = min($arPrices);
        $arResult["ITEMS"][$cell]["PRICES"]['max'] = max($arPrices);

        $arResult["ITEMS"][$cell]["CATALOG_QUANTITY"] =  $arElement['PROPERTIES']['OSTATOK_POSTAVSHCHIKA']['VALUE'];
    }
    unset($arElement);


    $no_photo_src = '/local/templates/hochucoffe/static/images/general/no-photo.png';
    $pic_width = 220;
    $pic_height = 220;

    foreach ($arResult['ITEMS'] as $key => $arItem) {

        //default
        $arItemsAdditional = array(
            'DEFAULT_IMAGE' => array(  //по умолчанию - нет картинки
                'SRC' => $no_photo_src,
                'WIDTH' => $pic_width,
                'HEIGHT' => $pic_height,
            ),
            'LABLES' => array(), //бирки
            'STATE' => array(), //состояния (В наличие, Снято с производства, Уточняйте у менеджера)
        );
        if(is_array($arItem['PREVIEW_PICTURE']))
            $arItemsAdditional['DEFAULT_IMAGE'] = $arItem['PREVIEW_PICTURE'];
        elseif($arItem['PREVIEW_PICTURE'])
            $arItemsAdditional['DEFAULT_IMAGE'] = CFile::GetFileArray($arItem["PREVIEW_PICTURE"]);

        $arItemsAdditional['DEFAULT_IMAGE']['ALT'] = $arItem['NAME'];


        //picture
        if ($arItem['PROPERTIES']['MORO_PHOTO']['VALUE'][0])
            $arItemsAdditional['DEFAULT_IMAGE'] = CFile::GetFileArray($arItem['PROPERTIES']['MORO_PHOTO']['VALUE'][0]);

        $arItemsAdditional['DEFAULT_IMAGE']['ALT'] = $arItem['NAME'];

        $arItemsAdditional['LABLES'] = $BP_TEMPLATE->Catalog()->lables(
            $arItem["IBLOCK_ID"],
            $arItem['PRICES']['max'],//$arItem['PRICES']['rozn']['VALUE'],
            $arItem['PRICES']['min'],//$arItem['PRICES']['Акция на сайте']['VALUE'],
            $arItem["PROPERTIES"]["_NOVINKA"]["VALUE"],
            $arItem["PROPERTIES"]["_PROIZVODITEL"]["VALUE_ENUM_ID"],
            $arItem["PROPERTIES"]["CML2_ARTICLE"]["VALUE"],
            $arItem['PROPERTIES']['KHIT_PRODAZH']['VALUE']
        );

        //state
        $arItemsAdditional['STATE'] = $BP_TEMPLATE->Catalog()->state(
            $arItem["IBLOCK_ID"],
            $arItem["CATALOG_QUANTITY"],
            $arItem['PRICES']['max'],//$arItem['PRICES']['rozn']['VALUE'],
            $arItem['PRICES']['min']//$arItem['PRICES']['Акция на сайте']['VALUE'],
        );

        $arResult['ITEMS'][$key] = array_merge($arItemsAdditional, $arItem);
    }

   /* echo "<pre>";
    print_r($arResult["NAV_STRING"]);
    echo "</pre>";
    */

    //hash
    $arResult["NAV_STRING"] = preg_replace_callback(
        '/href="([^"]+)"/is',
        function($matches){
            global $BP_TEMPLATE;
            return 'href="'.$BP_TEMPLATE->Catalog()->hashurl($matches[1],true).'"';
        }
        ,$arResult["NAV_STRING"]
    );
}



$cp = $this->__component; // объект компонента
if (is_object($cp)) {
    $cp->arResult['NavRecordCount'] = $arResult["NAV_RESULT"]->nSelectedCount;
    $cp->arResult['ITEMS_ID'] = array_keys($arResult['ITEMS']);
    $cp->SetResultCacheKeys(Array('ITEMS_ID','NavRecordCount'));
}


