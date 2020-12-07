<?php
namespace Bp\Template;

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Loader;

Loc::loadMessages(__FILE__);

class ChpuFilter
{
    protected static $instance = null;

    protected function __construct()
    {
        if(!Loader::includeModule('iblock'))
        {
            ShowError(GetMessage("CC_BCF_MODULE_NOT_INSTALLED"));
            return;
        }
    }
    protected function __clone()
    {

    }

    public static function getInstance()
    {
        if (!isset(static::$instance)) self::$instance = new ChpuFilter();
        return static::$instance;
    }
    public  function getNewPartsN()
    {
        $IBLOCK_ID = 1;
        $SECTION_ID = 1;
        $arPropsIds = [];
        $arProps = [];
        $cache = \Bitrix\Main\Data\Cache::createInstance();
        if ($cache->initCache(86400, 'getnewparts_n2', "hochucoffe")) // Если кэш валиден
        {
            $arProps = $cache->getVars();// Извлечение переменных из кэша
        } elseif ($cache->startDataCache())// Если кэш невалиден
        {
            $arLinkProps = \CIBlockSectionPropertyLink::GetArray($IBLOCK_ID, $SECTION_ID);
            foreach($arLinkProps as $arProp)
            {
                if($arProp['SMART_FILTER']=='Y' && $arProp['ACTIVE']=='Y')
                {
                    $arPropsIds[] = $arProp['PROPERTY_ID'];
                }
            }

            $rsProperty = \CIBlockProperty::GetList(Array(), Array("IBLOCK_ID"=>$IBLOCK_ID, 'PROPERTY_TYPE' => 'N'));
            while($arProperty = $rsProperty->Fetch())
            {
                if(in_array($arProperty['ID'],$arPropsIds))
                {
                    $arProps[] = strtolower($arProperty['CODE']);
                }
            }

            $cache->endDataCache($arProps);
        }
        return $arProps;
    }
    public  function getNewPartsS()
    {
        $IBLOCK_ID = 1;
        $SECTION_ID = 1;
        $arPropsIds = [];
        $arProps = [];
        $cache = \Bitrix\Main\Data\Cache::createInstance();
        if ($cache->initCache(86400, 'getnewparts_s', "hochucoffe")) // Если кэш валиден
        {
            $arProps = $cache->getVars();// Извлечение переменных из кэша
        } elseif ($cache->startDataCache())// Если кэш невалиден
        {
            $arLinkProps = \CIBlockSectionPropertyLink::GetArray($IBLOCK_ID, $SECTION_ID);
            foreach($arLinkProps as $arProp)
            {
                if($arProp['SMART_FILTER']=='Y' && $arProp['ACTIVE']=='Y')
                {
                    $arPropsIds[] = $arProp['PROPERTY_ID'];
                }
            }
            $rsProperty = \CIBlockProperty::GetList(Array(), Array("IBLOCK_ID"=>$IBLOCK_ID, 'PROPERTY_TYPE' => 'S'));
            while($arProperty = $rsProperty->Fetch())
            {
                if(in_array($arProperty['ID'],$arPropsIds))
                {
                    $arProps[] = strtolower($arProperty['CODE']);
                }
            }
            $cache->endDataCache($arProps);
        }
        return $arProps;
    }
    public  function getNewPartsL()
    {
        $IBLOCK_ID = 1;
        $SECTION_ID = 1;
        $arResult = [];

        $arProps = [];
        $arPropsIds = [];
        $arPropsValues = [];
        $cache = \Bitrix\Main\Data\Cache::createInstance();
        if ($cache->initCache(86401, 'getnewparts_l2', "hochucoffe")) // Если кэш валиден
        {
            $arResult = $cache->getVars();// Извлечение переменных из кэша
        } elseif ($cache->startDataCache())// Если кэш невалиден
        {
            $arLinkProps = \CIBlockSectionPropertyLink::GetArray($IBLOCK_ID, $SECTION_ID);
            foreach($arLinkProps as $arProp)
            {
                if($arProp['SMART_FILTER']=='Y' && $arProp['ACTIVE']=='Y')
                {
                    $arPropsIds[] = $arProp['PROPERTY_ID'];
                }
            }
            $rsProperty = \CIBlockProperty::GetList(Array(), Array("IBLOCK_ID"=>$IBLOCK_ID, 'PROPERTY_TYPE' => 'L'));
            while($arProperty = $rsProperty->Fetch())
            {
                if(in_array($arProperty['ID'],$arPropsIds))
                {
                    $arValues = [];
                    //if($arProperty['PROPERTY_TYPE']=='L')
                    //{
                        $property_enums = \CIBlockPropertyEnum::GetList(Array(), Array("IBLOCK_ID"=>$IBLOCK_ID, "PROPERTY_ID"=>$arProperty['ID']));
                        while($enum_fields = $property_enums->GetNext())
                        {
                            $arValues[$enum_fields["ID"]] = [
                                'XML_ID' => $enum_fields["XML_ID"],
                                'ID' => $enum_fields["ID"],
                                'VALUE' => $enum_fields["VALUE"],
                            ];
                            $arPropsValues[$enum_fields["VALUE"]][] = trim($arProperty["NAME"]);
                            $arProps[$enum_fields["XML_ID"]] = [
                                'VALUE' => $enum_fields["VALUE"],
                                'PROP_NAME' => $arProperty["NAME"],
                                'PROP_CODE' => $arProperty["CODE"],
                                'ENUM_ID' => $enum_fields["ID"],
                            ];
                        }
                    //}
                    /*$arProps[$arProperty['PROPERTY_TYPE']][$arProperty['ID']] = [
                        "CODE" => $arProperty["CODE"],
                        "NAME" => htmlspecialcharsbx($arProperty["NAME"]),
                        "VALUES" => $arValues
                    ];*/
                }
            }
            //pre(count($arPropsValues));
            $arDaMatrix = [
                'Новинка' => 'новинки',
            ];
            $arExProps = ['Цвет плафонов', 'Материал плафонов', 'Количество ламп'] ;
            $arDualPropsMatrix = [];

            //pre($arPropsValues);

            foreach($arPropsValues as $value => $arProp)
            {
                if(count($arProp)>1 || in_array($arProp[0],$arExProps))
                {
                    $arDualPropsMatrix[trim($value)] = trim($arProp[0]);
                }
            }
            //pre($arDualPropsMatrix);
            $arResult = [];
            foreach($arProps as $xml_id => $arProp)
            {
                $new_url = '';
                $new_url_alt ='';
                if($arProp["VALUE"]=='Да'){
                    $new_url = \Cutil::translit($arDaMatrix[trim($arProp["PROP_NAME"])],"ru",array("replace_space"=>"_","replace_other"=>"_"));
                    $new_url_alt = \Cutil::translit(strtolower($arProp["VALUE"]),"ru",array("replace_space"=>"_","replace_other"=>"_"));
                }
                 elseif($arProp["VALUE"]=='Нет'){
                     $new_url = \Cutil::translit(trim($arProp["PROP_NAME"]),"ru",array("replace_space"=>"_","replace_other"=>"_")).'-'.\Cutil::translit(trim($arProp["VALUE"]),"ru",array("replace_space"=>"_","replace_other"=>"_"));
                     $new_url_alt = \Cutil::translit(strtolower($arProp["VALUE"]),"ru",array("replace_space"=>"_","replace_other"=>"_"));
                 }
                elseif($arDualPropsMatrix[trim($arProp["VALUE"])])
                    if($arDualPropsMatrix[trim($arProp["VALUE"])]==trim($arProp["PROP_NAME"]) && (!in_array(trim($arProp["PROP_NAME"]),$arExProps)))
                        $new_url = \Cutil::translit(trim($arProp["VALUE"]),"ru",array("replace_space"=>"-","replace_other"=>"-"));
                    else{
                        $new_url = \Cutil::translit(trim($arProp["PROP_NAME"]),"ru",array("replace_space"=>"_","replace_other"=>"_")).'_'.\Cutil::translit(trim($arProp["VALUE"]),"ru",array("replace_space"=>"_","replace_other"=>"_"));
                        $new_url_alt = \Cutil::translit(strtolower($arProp["VALUE"]),"ru",array("replace_space"=>"-","replace_other"=>"-"));
                    }

                else
                    $new_url = \Cutil::translit(trim($arProp["VALUE"]),"ru",array("replace_space"=>"-","replace_other"=>"-"));

                $arResult[$xml_id] = [
                    'URL' => $new_url,
                    'URL_ALT' => $new_url_alt,
                    'PROP_NAME' => $arProp['PROP_NAME'],
                    'VALUE' => $arProp['VALUE'],
                    'PROP_CODE' => strtolower($arProp['PROP_CODE']),
                    'XML_ID' => $xml_id,
                    'ID' => $xml_id,
                ];
            }
            $cache->endDataCache($arResult);
        }

        return $arResult;
    }
    public function convertOldToNew($url)
    {
        if(strpos($url,'/filter/')!==false)
        {
            $new_url = '';
            $url = str_replace('filter/','',$url);
            $arNewParts = self::getNewPartsL();


            $arParts = explode("/", $url);

            foreach ($arParts as &$part)
            {
                if(strpos($part,'-is-')!==false)
                {
                    $arPart = preg_split("/-(from|to|is|or)-/", $part, -1, PREG_SPLIT_DELIM_CAPTURE);
                    $arNewUrlPart = [];
                    foreach ($arPart as $i => $smartElement)
                    {
                        if ($smartElement === "is" || $smartElement === "or")
                        {
                            $result['PROPS'][$control_name][] = $arPart[$i+1];
                            foreach($arNewParts as $arNewPart)
                            {
                                if($arNewPart['XML_ID']==$arPart[$i+1])
                                {
                                    $arNewUrlPart[] = $arNewPart['URL'];
                                }
                            }
                        }
                    }
                    if($arNewUrlPart)
                        $part = implode('/',$arNewUrlPart);
                }
            }
            if($arParts)
                $new_url = implode('/',$arParts);

            //echo $new_url;
            //die();
            return $new_url;
        } else {
            return false;
        }
    }
    public function convertNewToOld($url)
    {
        if(strpos($url,'/filter/')===false)
        {
            $filter_page = false;
            $arNewParts = self::getNewPartsL();

            $arNewPartsIndex = [];
            foreach($arNewParts as $arPart)
            {
                $arNewPartsIndex[$arPart['XML_ID']] = $arPart['URL'];
            }

            $arOldParts = [];
            $arParts = explode("/", $url);

            $index_no_filt = false;
            foreach ($arParts as $k=>$part)
            {
                //$partF = str_replace('-','_',$part);
                if($key = array_search($part,$arNewPartsIndex))
                {
                    $filter_page = true;
                    $arOldParts[$arNewParts[$key]['PROP_CODE']][$k] = $arNewParts[$key];

                    if(!$index_no_filt){
                        $index_no_filt = $k-1;
                    }
                }
                elseif(strpos($part,'optimal_price')!==false ||
                    strpos($part,'weight') !== false ||
                    strpos($part,'akciya') !== false
                ){
                    if(!$index_no_filt){
                        $index_no_filt = $k-1;
                    }
                }
            }
            //echo $index_no_filt;

            if($arOldParts)
            {
                $arUrlPart = [];
                foreach($arOldParts as $prop_code=>$arOldPart)
                {
                    $count=0;
                    $key = 0;
                    foreach($arOldPart as $k=>$arPart)
                    {
                        $url_part = $arPart['URL'];
                        if($arPart['URL_ALT']!=''){
                            $url_part = $arPart['URL_ALT'];
                        }

                        $count++;
                        if($count==1)
                        {
                            $key = $k;
                            $arUrlPart[$key] = $prop_code.'-is-'.$arPart['XML_ID'];
                        } else {
                            $arUrlPart[$key] = $arUrlPart[$key].'-or-'.$arPart['XML_ID'];
                            unset($arParts[$k]);
                        }
                    }
                }
                $arParts = array_replace($arParts, $arUrlPart);
            }
            if(!$filter_page)
            {

                $arNewParts = self::getNewPartsN();


                foreach($arNewParts as $prop_n_code)
                {
                    if(strpos($url,$prop_n_code)!==false)
                    {
                        $filter_page= true;
                        break;
                    }
                }
                if(!$filter_page)
                {

                    $arNewParts = self::getNewPartsS();

                    foreach($arNewParts as $prop_s_code)
                    {
                        if(strpos($url,$prop_s_code)!==false)
                        {
                            $filter_page= true;
                            if(!$index_no_filt){
                                foreach ($arParts as $k=>$p){
                                    if(strpos($p,$prop_s_code)!==false){
                                        $index_no_filt = $k-1;
                                    }
                                }
                            }
                            break;
                        }
                    }
                }
            }
            if($filter_page)
            {
                if(strpos($url,'/catalog/')!==false)
                {
                    $arParts[$index_no_filt] .='/filter';
                }
            }
            $old_url = implode('/',$arParts);

            //echo $old_url;
            //die();

            return $old_url;
        } else
        {
            return false;
        }
    }

    public function getPrimMatrix()
    {
        return [
            "/catalog/section/kitchenlight/" => "/catalog/dlya-kukhni/",
            "/catalog/section/lyustry_dlya_kuhni/" => "/catalog/section/lyustry/dlya-kukhni/",
            "/catalog/section/bra_dlya_kuhni/" => "/catalog/section/bra-i-podsvetki/dlya-kukhni/",
            "/catalog/section/table_dlya_kuhni/" => "/catalog/section/table/dlya-kukhni/",
            "/catalog/section/svetilniki_dlya_kuhni/" => "/catalog/section/svetilniki/dlya-kukhni/",
            "/catalog/section/spoty_dlya_kuhni/" => "/catalog/section/spoty/dlya-kukhni/",
            "/catalog/section/standart-lamp_dlya_kuhni/" => "/catalog/section/standart-lamp/dlya-kukhni/",
            "/catalog/section/dlya_gostinoy/" => "/catalog/dlya-gostinoy/",
            "/catalog/section/lyustry_dlya_gostinoy/" => "/catalog/section/lyustry/dlya-gostinoy/",
            "/catalog/section/bra_dlya_gostinoy/" => "/catalog/section/bra-i-podsvetki/dlya-gostinoy/",
            "/catalog/section/table_dlya_gostinoy/" => "/catalog/section/table/dlya-gostinoy/",
            "/catalog/section/svetilniki_dlya_gostinoy/" => "/catalog/section/svetilniki/dlya-gostinoy/",
            "/catalog/section/spoty_dlya_gostinoy/" => "/catalog/section/spoty/dlya-gostinoy/",
            "/catalog/section/standart-lamp_dlya_gostinoy/" => "/catalog/section/standart-lamp/dlya-gostinoy/",
            "/catalog/section/dlya_spalni/" => "/catalog/dlya-spalni/",
            "/catalog/section/lyustry_dlya_spalni/" => "/catalog/section/lyustry/dlya-spalni/",
            "/catalog/section/bra_dlya_spalni/" => "/catalog/section/bra-i-podsvetki/dlya-spalni/",
            "/catalog/section/table_dlya_spalni/" => "/catalog/section/table/dlya-spalni/",
            "/catalog/section/svetilniki_dlya_spalni/" => "/catalog/section/svetilniki/dlya-spalni/",
            "/catalog/section/spoty_dlya_spalni/" => "/catalog/section/spoty/dlya-spalni/",
            "/catalog/section/standart-lamp_dlya_spalni/" => "/catalog/section/standart-lamp/dlya-spalni/",
            "/catalog/section/bathroom/" => "/catalog/dlya-vannoy/",
            "/catalog/section/svetilniki_dlya_vannoi/" => "/catalog/section/svetilniki/dlya-vannoy/",
            "/catalog/section/spoty_dlya_vannoi/" => "/catalog/section/spoty/dlya-vannoy/",
            "/catalog/section/children/" => "/catalog/dlya-detskoy/",
            "/catalog/section/lyustry_dlya_detskoy/" => "/catalog/section/lyustry/dlya-detskoy/",
            "/catalog/section/bra_dlya_detskoy/" => "/catalog/section/bra-i-podsvetki/dlya-detskoy/",
            "/catalog/section/svetilniki_dlya_detskoy/" => "/catalog/section/svetilniki/dlya-detskoy/",
            "/catalog/section/spoty_dlya_detskoy/" => "/catalog/section/spoty/dlya-detskoy/",
            "/catalog/section/standart-lamp_dlya_detskoy/" => "/catalog/section/standart-lamp/dlya-detskoy/",
            "/catalog/section/dlya_prikhozhey/" => "/catalog/dlya-prikhozhey/",
            "/catalog/section/lyustry_dlya_prikhozhey/" => "/catalog/section/lyustry/dlya-prikhozhey/",
            "/catalog/section/bra_dlya_prikhozhey/" => "/catalog/section/bra-i-podsvetki/dlya-prikhozhey/",
            "/catalog/section/table_dlya_prikhozhey/" => "/catalog/section/table/dlya-prikhozhey/",
            "/catalog/section/svetilniki_dlya_prikhozhey/" => "/catalog/section/svetilniki/dlya-prikhozhey/",
            "/catalog/section/spoty_dlya_prikhozhey/" => "/catalog/section/spoty/dlya-prikhozhey/",
            "/catalog/section/standart-lamp_dlya_prikhozhey/" => "/catalog/section/standart-lamp/dlya-prikhozhey/",
            "/catalog/section/dlya_kafe_restoranov/" => "/catalog/dlya-kafe-restoranov/",
            "/catalog/section/lyustry_dlya_kafe_restoranov/" => "/catalog/section/lyustry/dlya-kafe-restoranov/",
            "/catalog/section/bra_dlya_kafe_restoranov/" => "/catalog/section/bra-i-podsvetki/dlya-kafe-restoranov/",
            "/catalog/section/table_dlya_kafe_restoranov/" => "/catalog/section/table/dlya-kafe-restoranov/",
            "/catalog/section/svetilniki_dlya_kafe_restoranov/" => "/catalog/section/svetilniki/dlya-kafe-restoranov/",
            "/catalog/section/spoty_dlya_kafe_restoranov/" => "/catalog/section/spoty/dlya-kafe-restoranov/",
            "/catalog/section/standart-lamp_dlya_kafe_restoranov/" => "/catalog/section/standart-lamp/dlya-kafe-restoranov/",
            "/catalog/section/dlya_bolshikh_zalov/" => "/catalog/dlya-bolshikh-zalov/",
            "/catalog/section/lyustry_bolshikh_zalov/" => "/catalog/section/lyustry/dlya-bolshikh-zalov/",
            "/catalog/section/svetilniki_bolshikh_zalov/" => "/catalog/section/svetilniki/dlya-bolshikh-zalov/",
            "/catalog/section/dlya_ofisa/" => "/catalog/dlya-ofisa/",
            "/catalog/section/lyustry_dlya_ofisa/" => "/catalog/section/lyustry/dlya-ofisa/",
            "/catalog/section/bra_dlya_ofisa/" => "/catalog/section/bra-i-podsvetki/dlya-ofisa/",
            "/catalog/section/svetilniki_dlya_ofisa/" => "/catalog/section/svetilniki/dlya-ofisa/",
            "/catalog/section/spoty_dlya_ofisa/" => "/catalog/section/spoty/dlya-ofisa/",
            "/catalog/section/standart-lamp_dlya_ofisa/" => "/catalog/section/standart-lamp/dlya-ofisa/",
            "/catalog/section/dlya_magazina/" => "/catalog/dlya-magazina/",
        ];
    }
    public function getRealPrimUrl($url)
    {
        $arPrimMatrix = self::getPrimMatrix();
        if($arPrimMatrix[$url]!='')
            return $arPrimMatrix[$url];
        else
            return false;
    }
    public function getMatrixBrand()
    {
        $arData = [];
        $arNewBrands = [];
        $cache = \Bitrix\Main\Data\Cache::createInstance();
        if ($cache->initCache(86400, 'getchpubrands', "hochucoffe")) // Если кэш валиден
        {
            $arData = $cache->getVars();// Извлечение переменных из кэша
        } elseif ($cache->startDataCache())// Если кэш невалиден
        {
            $property_enums = \CIBlockPropertyEnum::GetList(Array(), Array("IBLOCK_ID"=>1, "PROPERTY_ID"=>35));
            while($enum_fields = $property_enums->GetNext())
            {
                $arNewBrands[$enum_fields["ID"]] = \Cutil::translit($enum_fields["VALUE"],"ru",array("replace_space"=>"-","replace_other"=>"-"));
            }

            $res = \CIBlockElement::GetList(
                [],
                [
                    "IBLOCK_TYPE" => "serv",
                    "IBLOCK_ID"=>3,
                    "ACTIVE"=>"Y",
                    "SECTION_ID" => 835,
                    //"CODE" => $brand_code,
                ],
                false,
                false,
                ["PROPERTY_BRAND_ID", 'NAME', 'CODE']
            );
            while($ob = $res->fetch())
            {
                if($arNewBrands[$ob['PROPERTY_BRAND_ID_VALUE']])
                    $arData[$ob['CODE']] = $arNewBrands[$ob['PROPERTY_BRAND_ID_VALUE']];
            }
            $cache->endDataCache($arData);
        }
        return $arData;
    }
    public function getRealBrandUrl($url)
    {
        $brand = '';

        $url2 = explode('?',$url);
        $url2 = $url2[0];

        $arParts = explode("/", $url2);
        foreach($arParts as $k=>$part)
        {
            if($part=='man')
            {
                $brand = $arParts[$k+1];
                if(isset($arParts[$k+2]) && $arParts[$k+2]!='') //collection
                    return false;
            }
        }

        $arData = self::getMatrixBrand();


        if($brand!='' && $new_part = $arData[$brand])
            return str_replace('/man/'.$brand,'/'.$new_part,$url);
        else
            return false;
    }
    public function getOldBrandByXMLID($xml_id)
    {
        $brand_code = '';
        $property_enums = \CIBlockPropertyEnum::GetList(Array(), Array("IBLOCK_ID"=>1, "CODE"=>"_PROIZVODITEL", 'XML_ID' => $xml_id));
        if($enum_fields = $property_enums->GetNext())
        {
            $arData = self::getMatrixBrand();
            $new_brand_code = \Cutil::translit($enum_fields["VALUE"],"ru",array("replace_space"=>"-","replace_other"=>"-"));
                foreach($arData as $k=>$v)
                {
                    if($v==$new_brand_code){
                        $res['old'] = $k;
                        $res['new'] = $new_brand_code;
                    }
                }

        }
        return $res;
    }
}
