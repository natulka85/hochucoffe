<?php
namespace Bp\Template;

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Loader;
use Bp\Template\Constants;

Loc::loadMessages(__FILE__);

class Catalog
{

    protected static $instance = null;

    public static function getInstance()
    {
        if (!isset(static::$instance)) self::$instance = new Catalog();
        return static::$instance;
    }

    public $arCount = 18;

    public function hashurl($url ,$only_hash=false)
    {
        //echo $url.PHP_EOL;

        global $BP_TEMPLATE;
        $newUrl = '';
        $hash = array();

        if ($arUrl = parse_url($url)) {
            $newUrl .= $BP_TEMPLATE->ChpuFilter()->convertOldToNew($arUrl['path']);
            /*if (substr($newUrl, -1) != '/') {
                $newUrl .= '/';
            } */
            //$newUrl = preg_replace('#(pagen[\d]+/)#is', '', $newUrl);
            parse_str(htmlspecialcharsback($arUrl['query']), $arQuery);
            $sort = '';
            $order = '';

            if(isset($arQuery['catalog_ajax_call'])){
                unset($arQuery['catalog_ajax_call']);
            }
            foreach ($arQuery as $k => $v) {
                if (substr($k, 0, 5)=='PAGEN') {
                    $hash[] = 'p'.intval($v);
                    unset($arQuery[$k]);
                } elseif(substr($k, 0, 5)=='count')
                {
                    $hash[] = intval($v);
                    unset($arQuery[$k]);
                } elseif(substr($k, 0, 4)=='sort')
                {
                    $sort = $v;
                    unset($arQuery[$k]);
                } elseif(substr($k, 0, 5)=='order')
                {
                    $order = $v;
                    unset($arQuery[$k]);
                }

            }

            if($sort=='price' && $order=='desc')
                $hash[] = 'prise_max';
            elseif($sort=='price' && $order=='asc')
                $hash[] = 'prise_min';
            elseif($sort=='discount' && $order=='desc')
                $hash[] = 'sale_max';
            elseif($sort=='discount' && $order=='asc')
                $hash[] = 'sale_min';
            elseif($sort=='new' && $order=='desc')
                $hash[] = 'new';

            $buildQuery = http_build_query($arQuery, '', '&amp;');
            if (strlen($buildQuery)) {
                $newUrl .= '?'.$buildQuery;
            }

            if(count($hash)>0)
            {
                $hash = implode('&amp;', $hash);
                $newUrl .= '#'.$hash;

                if($only_hash)
                    $newUrl = '#'.$hash;
            } elseif($only_hash){
                //$newUrl = '#p1';
            }
        }
        return $newUrl;
    }

    public function ChangeSection1C(&$arFields, $IBLOCK_1C_ID, $PROP_ID_SECTION_PATH, $TYPE_SEC_ID)
    {
        $ar = func_get_args();
        //global $Acmilan;
        //вытягиваем из свойства "Раздел на сайте" - путь
        $arPropTmp = $arFields["PROPERTY_VALUES"][$PROP_ID_SECTION_PATH]; //array_shift - затирает свойство, поэтому такие извороты
        $path = array_shift($arFields["PROPERTY_VALUES"][$PROP_ID_SECTION_PATH]);
        $arFields["PROPERTY_VALUES"][$PROP_ID_SECTION_PATH] = $arPropTmp; //array_shift - затирает свойство, поэтому такие извороты
        $path = $path["VALUE"];
        $paths = explode("/", $path);
        unset($paths[0]);

        // формируем массив id - разделы нашего пути
        $arSections = array();

        foreach ($paths as $k=>$sec_name) {

            //формируем символьный код, если есть родитель - его учитываем (в xml_id)
            if(isset($paths[$k-1])) {
                $sec_code = \Cutil::translit($sec_name,"ru",array("replace_space"=>"-","replace_other"=>"-"));
                $sec_xml_code = \Cutil::translit($paths[$k-1]."-".$sec_name,"ru",array("replace_space"=>"-","replace_other"=>"-"));
                $sec_parent_id = $arSections[$k-1];
            } else {
                $sec_xml_code = $sec_code = \Cutil::translit($sec_name,"ru",array("replace_space"=>"-","replace_other"=>"-"));
                $sec_parent_id = $TYPE_SEC_ID;
            }

            //ищем существующий раздел по коду
            $db_list = \CIBlockSection::GetList(array(), array("IBLOCK_ID"=>$IBLOCK_1C_ID, "XML_ID"=>$sec_xml_code), false, array("ID"));
            if($ar_result = $db_list->GetNext()) {
                $sec_id = $ar_result["ID"];
            }  else {

                $arSecFields = Array(
                    //"ID" => $Acmilan->CATALOG_TYPE,
                    "ACTIVE" => "Y",
                    "IBLOCK_SECTION_ID" => $sec_parent_id,
                    "IBLOCK_ID" => $IBLOCK_1C_ID,
                    "NAME" => $sec_name,
                    "CODE" => $sec_code,
                    "XML_ID" => $sec_xml_code,
                    "SORT" => 400,
                );
                $bs = new \CIBlockSection;
                $sec_id = $bs->Add($arSecFields);
            }
            $arSections[$k] = $sec_id;
        }
        $new_sec_id = array_pop($arSections);


        if($new_sec_id>0)
        {
            $arFields["IBLOCK_SECTION"] = $new_sec_id;
            $arFields["IBLOCK_SECTION_ID"] = $new_sec_id;
        }

    }

    public function state($IBLOCK_ID, $CATALOG_QUANTITY, $PRICE1, $PRICE2)
    {
        \CModule::IncludeModule("sale");
        \CModule::IncludeModule("iblock");

        $arResult = array();
        //1 - В наличие STATE_INSTOCK
        if($CATALOG_QUANTITY>0 && $PRICE1>1) {

            $arResult['NAME'] = 'STATE_INSTOCK';
            $arResult['BUTTON_TEXT'] = 'В корзину';
            $arResult['BUTTON_CLASS'] = 'is-brown-light';
            $arResult['TEXT_CARD'] = $CATALOG_QUANTITY.' шт'; //$this->STATE_INSTOCK_TEXT;
            $arResult['TEXT'] = 'В наличии'; //$this->STATE_INSTOCK_TEXT;


            if($PRICE2>0 && $PRICE2<$PRICE1)
            {
                $arResult['PRICE'] = $PRICE2;
                $arResult['PRICE_OLD'] = $PRICE1;
            } else
                $arResult['PRICE'] = $PRICE1;

            $arResult['JS_ACTION'] = 'basket_change';
        } //2 - уточняйте у менеджера STATE_OUTSTOCK
        elseif(
            $CATALOG_QUANTITY==0
            && $PRICE1>1
        ) {
            $arResult['NAME'] = 'STATE_OUTSTOCK';
            $arResult['BUTTON_TEXT'] = 'Под заказ';
            $arResult['BUTTON_COLOR'] = 'buy-order';
            $arResult['TEXT'] = 'Под заказ';
            $arResult['TEXT_CARD'] = 'Под заказ';

            if($PRICE2>0 && $PRICE2<$PRICE1)
            {
                $arResult['PRICE'] = $PRICE2;
                $arResult['PRICE_OLD'] = $PRICE1;
            } else
                $arResult['PRICE'] = $PRICE1;

            $arResult['JS_ACTION'] = 'open_zakaz';
            $arResult['CLASS'] = 'is-outstock';
        } //3 - cнято с производства  STATE_OUTPROD

        return $arResult;
    }

    public function discount($price1 = false, $price2 = false)
    {
        if($price1>0 && $price2>0 && $price1!=$price2)
            return abs(round(($price1-$price2)/$price1*100,0));
        else
            return false;
    }

    public function lables($IBLOCK_ID, $PRICE1, $PRICE2, $NOVINKA, $BRAND = false, $ARTICLE=false,$HIT=false)
    {

        \CModule::IncludeModule("sale");

        $arResult = array();

        //акция
        if(
            $PRICE1>0
            && $PRICE2>0
            && $PRICE2<$PRICE1
        )
        {
            $arResult['ACTION']['COUNT'] = $this->discount($PRICE1,$PRICE2);
            $arResult['ACTION']['CLASS'] = 'is-sale';
            $arResult['ACTION']['TEXT'] = '-'.$this->discount($PRICE1,$PRICE2).'%';
            $arResult['ACTION']['IMAGE'] = false;
            $arResult['ACTION']['DIFF'] = \SaleFormatCurrency(($PRICE1-$PRICE2), 'RUB');
        }
        //хит продаж
        if(
            $HIT == 'true'
        )
        {
            $arResult['HIT']['COUNT'] = false;
            $arResult['HIT']['CLASS'] = 'is-hit';
            $arResult['HIT']['TEXT'] = 'Выбор покупателей';
        }

        if(
            $NOVINKA == 'Да'
        )
        {
            $arResult['NEW']['COUNT'] = false;
            $arResult['NEW']['CLASS'] = 'is-new';
            $arResult['NEW']['TEXT'] = '';
            //$arResult['NEW']['IMAGE'] = '<img width="35" height="34" src="'.SITE_TEMPLATE_PATH.'/static/images/minified-svg/ico-label-03.svg" alt="">';
        }

        return $arResult;
    }

    function sectionList(){

        $cache = \Bitrix\Main\Data\Cache::createInstance();
        if ($cache->initCache(86400, 'arsec_lvl_by_code', "hochucoffe")) // Если кэш валиден
        {
            $arSectRes = $cache->getVars();// Извлечение переменных из кэша
        } elseif ($cache->startDataCache())// Если кэш невалиден
        {
            $main_type = Constants::getInstance()->IBLOCK_MAIN_SEC;

                $res = \CIBlockSection::GetByID($main_type);
                if($ar_res = $res->GetNext()){
                    $arFilter = array('IBLOCK_ID' => $ar_res['IBLOCK_ID'],'>LEFT_MARGIN' => $ar_res['LEFT_MARGIN'],'<RIGHT_MARGIN' => $ar_res['RIGHT_MARGIN']); // выберет потомков без учета активности
                    $rsSect = \CIBlockSection::GetList(array('left_margin' => 'asc'),$arFilter);
                    while ($arSect = $rsSect->GetNext())
                    {
                        if($arSect['DEPTH_LEVEL'] == 2){
                            $type = 'category';
                        }
                        elseif($arSect['DEPTH_LEVEL'] == 3){
                            $type = 'type';
                        }
                        if($type!=''){
                            $arSectRes[$type]['ITEMS'][$arSect['ID']] = $arSect;
                            $arSectRes[$type]['CODES'][$arSect['ID']] = $arSect['CODE'];
                            $arSectRes[$type]['IDs'][$arSect['CODE']] = $arSect['ID'];
                        }
                    }
                }
            $cache->endDataCache($arSectRes);
        }
        return $arSectRes;
    }


    public function is404($url)
    {
        $arPatterns = [
            'catalog' => "(/catalog/\?.+|/catalog/$)", //catalog
            'catalog_filter' => "(/catalog/filter/.*)", //catalog + filter
            //'catalog_collection' => "(/catalog/collection/.*)", //catalog + filter
        ];

        $is404 = true;
        foreach($arPatterns as $k=>$pattern)
        {
            preg_match(
                $pattern,
                $url,
                $out
            );

            if(
            preg_match(
                $pattern,
                $url,
                $out
            )
            )
            {
                $is404 = false;
            }
        }

        return $is404;
    }
    function setQuantityProp($ID, $quantity)
    {
        if(isset($quantity)/* && (int)$quantity>0*/)
        {

            \CModule::IncludeModule('iblock');
            \Bitrix\Main\Loader::includeModule('catalog');

            $iblock_id = \CIBlockElement::GetIBlockByID($ID);
            \CIBlockElement::SetPropertyValuesEx($ID, $iblock_id, ['OSTATOK_POSTAVSHCHIKA' => $quantity]);

        }
    }
}
