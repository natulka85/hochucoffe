<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

global $BP_TEMPLATE;
$arResult['TMPL_PROPS_DOP_OPTIONS'] = $BP_TEMPLATE->Catalog()->dopProperties;

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


    $no_photo_src = '/local/templates/hochucoffe/static/dist/images/general/no-photo.png';
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

        //more_picture
        if(count($arItem['PROPERTIES']['MORE_PHOTO']['VALUE'])>0)
        {
            //$arItemsAdditional['MORE_IMAGE'][] = $arItemsAdditional['DEFAULT_IMAGE'];
            //$arItemsAdditional['MORE_IMAGE'][0]['HREF'] = $arItem['DETAIL_PICTURE']['SRC'];
            if(is_array($arItem['PROPERTIES']['MORE_PHOTO']['VALUE']))
            {
                $arMorePhotos = $arItem['PROPERTIES']['MORE_PHOTO']['VALUE'];
                if(count($arMorePhotos) > 5){
                    $arMorePhotos = array_slice($arMorePhotos, 0,5);
                }

                foreach($arMorePhotos as $value)
                {
                    $arFile = CFile::ResizeImageGet(
                        $value,
                        array("width" => $pic_width, "height" => $pic_height),
                        BX_RESIZE_IMAGE_PROPORTIONAL_ALT ,
                        true
                    );

                    $arItemsAdditional['MORE_IMAGE'][] = array(
                        'SRC' => $arFile['src'],
                        'WIDTH' => $arFile['width'],
                        'HEIGHT' => $arFile['height'],
                        'HREF' => CFile::GetPath($value),
                    );
                }
            }
        }

        $arItemsAdditional['DEFAULT_IMAGE']['ALT'] = $arItem['NAME'];

        $arItemsAdditional['LABLES'] = $BP_TEMPLATE->Catalog()->lables(
            $arItem["IBLOCK_ID"],
            $arItem['PRICES']['max'],//$arItem['PRICES']['rozn']['VALUE'],
            $arItem['PRICES']['min'],//$arItem['PRICES']['Акция на сайте']['VALUE'],
            $arItem["PROPERTIES"]["_NOVINKA"]["VALUE"],
            $arItem["PROPERTIES"]["ARTICLE_COMP"]["VALUE"],
            $arItem['PROPERTIES']['_HIT_PRODAZH']['VALUE'],
            $arItem['PROPERTIES']['OCENKA_SCA']['VALUE'],
            $arItem['PROPERTIES']['_STRANA']['VALUE']
        );

        if($arItemsAdditional['LABLES']['COUNTRY']['IMG']!=''){
            $arFile = CFile::ResizeImageGet(
                $arItemsAdditional['LABLES']['COUNTRY']['IMG'],
                array("width" => 60, "height" => 60),
                BX_RESIZE_IMAGE_PROPORTIONAL_ALT ,
                true
            );
            $arItemsAdditional['LABLES']['COUNTRY']['IMG_AR'] = $arFile;
        }

        //state
        $arItemsAdditional['STATE'] = $BP_TEMPLATE->Catalog()->state(
            $arItem["IBLOCK_ID"],
            $arItem["CATALOG_QUANTITY"],
            $arItem['PRICES']['max'],//$arItem['PRICES']['rozn']['VALUE'],
            $arItem['PRICES']['min']//$arItem['PRICES']['Акция на сайте']['VALUE'],
        );

        $arResult['ITEMS'][$key] = array_merge($arItemsAdditional, $arItem);

        if(count($arItem['PROPERTIES']['VKUS']['VALUE_ENUM'])>0){
            $vkus = $BP_TEMPLATE->str_fst_upper(mb_strtolower(implode(', ',$arItem['PROPERTIES']['VKUS']['VALUE_ENUM'])));
            $arResult['ITEMS'][$key]['PROPERTIES']['VKUS']['VALUE_FORMATTED'] =  $vkus;
            unset($vkus);
        }

        if($arItem['PROPERTIES']['WEIGHT_VAR']['~VALUE']!=''){
            $arResult['ITEMS'][$key]['PROPERTIES']['WEIGHT_VAR_AR'] = json_decode($arItem['PROPERTIES']['WEIGHT_VAR']['~VALUE'],TRUE);
            asort($arResult['ITEMS'][$key]['PROPERTIES']['WEIGHT_VAR_AR'],SORT_NUMERIC);
        }
        if($arResult['ITEMS'][$key]['PROPERTIES']['WEIGHT']['VALUE']!=''){
            $arResult['ITEMS'][$key]['MOD_PRICE_100_G'] = round($arResult['ITEMS'][$key]['STATE']['PRICE'] / $arResult['ITEMS'][$key]['PROPERTIES']['WEIGHT']['VALUE']  * 100,0);
        }

        $arResult['ITEMS'][$key]['MOD_REVIEW_AVERAGE'] = round($arItem['PROPERTIES']['ASKARON_REVIEWS_AVERAGE']['VALUE'],0);
    }

   /* echo "<pre>";
    print_r($arResult["NAV_STRING"]);
    echo "</pre>";
    */
    //showmore + hash
    if(
        $arResult["NAV_RESULT"]->NavPageCount>1
        && $arResult["NAV_RESULT"]->NavPageNomer!=$arResult["NAV_RESULT"]->nEndPage
    )
    {
        $arQuery = explode("?",htmlspecialchars_decode($_SERVER['REQUEST_URI']));
        $addurlparam = "";

        $arResult['SHOWMORE_URL'] = $BP_TEMPLATE->Catalog()->hashurl($arQuery[0].'?PAGEN_'.$arResult["NAV_RESULT"]->NavNum.'='.($arResult["NAV_RESULT"]->NavPageNomer+1).$addurlparam, true);
        $arResult['SHOWMORE_COUNT'] = $arResult['NAV_RESULT']->nSelectedCount - $arResult['NAV_RESULT']->SIZEN*$arResult['NAV_RESULT']->PAGEN;

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
}

if($arParams['CATEGORY_TYPE']!='ONE_CARD'){
    $NavFirstRecordShow = ($arResult["NAV_RESULT"]->NavPageNomer-1)*$arResult["NAV_RESULT"]->NavPageSize+1;
    if ($arResult["NAV_RESULT"]->NavPageNomer != $arResult["NAV_RESULT"]->NavPageCount)
        $NavLastRecordShow = $arResult["NAV_RESULT"]->NavPageNomer * $arResult["NAV_RESULT"]->NavPageSize;
    else
        $NavLastRecordShow = $arResult["NAV_RESULT"]->nSelectedCount;

    $arResult['COUNT_MESSAGE'] = $NavFirstRecordShow.'-'.$NavLastRecordShow.' из '.$arResult["NAV_RESULT"]->nSelectedCount;

//для крошек
    $arAddChainSec1 = [];
    $arAddChainSec2 = [];
//выборка дерева подразделов для раздела
    $arSections = [];
    $rsParentSection = CIBlockSection::GetByID(1);
    if ($arParentSection = $rsParentSection->GetNext())
    {
        $parent_sec_id = 1;
        $arAddChainSec1[$parent_sec_id] = [
            'NAME' =>  $BP_TEMPLATE->str_fst_upper($BP_TEMPLATE->SeoSection()->getSeoConst('secname',$parent_sec_id)),
            'SECTION_PAGE_URL' =>  '/catalog/',
        ];
        $arFilter = array('IBLOCK_ID' => $arParentSection['IBLOCK_ID'],'>LEFT_MARGIN' => $arParentSection['LEFT_MARGIN'],'<RIGHT_MARGIN' => $arParentSection['RIGHT_MARGIN'],'>DEPTH_LEVEL' => $arParentSection['DEPTH_LEVEL']); // выберет потомков без учета активности
        $rsSect = CIBlockSection::GetList(array('left_margin' => 'asc'),$arFilter);
        while ($arSect = $rsSect->GetNext())
        {
            // получаем подразделы
            if($arSect['SORT']<500)
            {
                if($arSect['DEPTH_LEVEL']==2)
                    $arAddChainSec1[$arSect['ID']] = [
                        'NAME' =>  $BP_TEMPLATE->str_fst_upper($BP_TEMPLATE->SeoSection()->getSeoConst('secname',$arSect['ID'])),
                        'SECTION_PAGE_URL' =>  $arSect['SECTION_PAGE_URL'],
                    ];
                if($arSect['DEPTH_LEVEL']==3)
                    $arAddChainSec2[$arSect['IBLOCK_SECTION_ID']][$arSect['ID']] = [
                        'NAME' =>  $BP_TEMPLATE->str_fst_upper($arSect['NAME']),
                        'SECTION_PAGE_URL' =>  $arSect['SECTION_PAGE_URL'],
                    ];
                if($arSect['DEPTH_LEVEL']==3 && $arSect['ID']==$arParams['SECTION_ID'])
                    $parent_sec_id  = $arSect['IBLOCK_SECTION_ID'];
            }
        }

        $arAddChainSec1[$parent_sec_id]['CHILDS'] = $arAddChainSec2[$parent_sec_id];
    }
    $arResult['LABLES_TEMPLATE'] = [
        'LEFT' => ['HIT','NEW'],
        'RIGHT' => ['SCA'],
        'CENTER' => [],
    ];


    $cp = $this->__component; // объект компонента
    if (is_object($cp)) {
        $cp->arResult['NavRecordCount'] = $arResult["NAV_RESULT"]->nSelectedCount;
        $cp->arResult['CHAIN'] = $arAddChainSec1;
        $cp->arResult['ITEMS_ID'] = array_keys($arResult['ITEMS']);
        $cp->SetResultCacheKeys(Array('CHAIN','ITEMS_ID','NavRecordCount'));
    }
}

