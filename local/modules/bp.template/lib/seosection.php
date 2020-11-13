<?
namespace Bp\Template;

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Loader;

Loc::loadMessages(__FILE__);

class SeoSection
{
    protected static $instance = null;

    public static function getInstance()
    {
        if (!isset(static::$instance)) self::$instance = new SeoSection();
        return static::$instance;
    }
    protected function __construct()
    {
        $dbConst = SettingsTable::getList(array(
            'select' => array('ID','NAME','CODE','VALUE'),
            'filter' => array('CODE' => 'SEO_%')
        ));
        while ($arConst = $dbConst->Fetch())
            $this->arConsts[$arConst['CODE']] = $arConst;
    }

    public function getRobots()
    {
        global $APPLICATION;
        $page_url = $APPLICATION->getCurPage();

        $arProps = self::convertUrlToCheck($page_url);

        $arValidFilt['CNT_PROP'] = count($arProps['PROPS']);
        foreach($arProps['PROPS'] as $prop_code=>$arValue)
        {
            $arValidFilt['CNT_VALUES'] += count($arValue);
        }

        $one_value = true;
        if(isset($arProps['PROPS']))
        {
            if($arValidFilt['CNT_PROP']>3 ||
                ($arValidFilt['CNT_VALUES'] > $arValidFilt['CNT_PROP'])){
                \Bitrix\Main\Page\Asset::getInstance()->addString('<meta name="robots" content="noindex, nofollow">');
            }
        }
    }

    public function generateH1($code,$type='SECTION'){
        global $APPLICATION;
        $page_url = $APPLICATION->getCurPage();

        switch ($type) {
            case 'SECTION':
                if(self::getSeoConst('secname', $code)!='')
                    return mb_ucfirst(self::getSeoConst('secname', $code));
                else
                    return '';
            case 'ALL_CAT_FILTER' || 'SECTION_FILTER':
                $arProps = self::convertUrlToCheck($page_url);

                if(is_array($arProps['PROPS']))
                {
                    $one_value = true;
                    foreach($arProps['PROPS'] as $prop_code=>$arValue)
                    {
                        if(count($arValue)>1)
                            $one_value = false;
                    }
                    if(
                        count($arProps['PROPS'])>0
                        && count($arProps['PROPS'])<4
                        && $one_value
                        && self::NavRecordCount()>0
                    )
                    {
                        if(count($arProps['PROPS']) == 2 && $arProps['PROPS']['AKTSIYA'][0] == 'true' && count($arProps['PROPS']['_STIL']) == 1){ //каталог+распродажа+1стиль
                            $FullName = self::getFullName($arProps, $code,true);
                            $FullName = trim(str_replace('осветительные приборы','освещения',$FullName));

                            if($arProps['PROPS']['_STIL'][0] == 'ef9c992b-2de0-11e4-a463-d4ae52a11316'){
                                return 'Распродажа освещения в классическом стиле';
                            }
                            elseif($arProps['PROPS']['_STIL'][0] == 'ef9c992c-2de0-11e4-a463-d4ae52a11316'){
                                return 'Распродажа освещения в современном стиле';
                            }
                            elseif($arProps['PROPS']['_STIL'][0] == '1eb5dfae-3f03-11e4-a463-d4ae52a11316'){
                                return 'Распродажа освещения в стиле лофт';
                            }
                            else{
                                return 'Распродажа '.strtolower($FullName);
                            }
                        } elseif($arProps['PROPS']['_STEPEN_ZASHCHITY_IP'])
                        {
                            $FullNameAlt = self::getFullNameAlt($arProps, $code);
                            return mb_ucfirst($FullNameAlt);
                        }
                        else{
                            $FullName = self::getFullName($arProps, $code);

                            if(count($arProps['PROPS'])==1) //selected one prop
                            {
                                return mb_ucfirst($FullName);
                            }
                            elseif(count($arProps['PROPS'])==2) //selected two props
                            {
                                return mb_ucfirst($FullName);
                            }
                            elseif(count($arProps['PROPS'])==3) //selected two props
                            {
                                return mb_ucfirst($FullName);
                            }
                        }

                    }  else
                        return '';
                }  else
                    return '';
            default:
                return '';
        }
    }
    public function getFullName($arProps, $code,$anyResult=false)
    {
        global $BP_TEMPLATE;
        if(count($arProps['PROPS'])>0)
        {
            //pre($arProps);
            $arX = [];
            $arY = [];
            $good = true;
            if($arProps['PROPS']['TYPE']!=''){
                $TYPE = $arProps['PROPS']['TYPE'];
                unset($arProps['PROPS']['TYPE']);
                $arProps['PROPS']['TYPE'] = $TYPE;
            }


            $arPropsCodes = array_keys($arProps['PROPS']);
            foreach($arProps['PROPS'] as $prop_code=>$arValue)
            {
                foreach($arValue as $value)
                {
                    //if($prop_code=='_STEPEN_ZASHCHITY_IP')
                    //    $value = 'IP'.$value;
                    $good_value = false;
                    $x = $y = $xalt = $yalt = $yalt1 = '';
                    $x = self::getSeoConst('props_x', $value);
                    if($x!='')
                    {
                        $arX[] = $x;
                        $good_value = true;
                    }

                    //additional rules for  props_yalt1
                    if(
                        in_array('_FORMA_PLAFONA',$arPropsCodes)
                        && ($prop_code=='TSVET_PLAFONOV' || $prop_code=='_MATERIAL_PLAFONOV')
                    )
                        $getfrom = 'props_yalt1';
                    elseif(
                        in_array('TSVET_ARMATURY',$arPropsCodes)
                        && $prop_code=='_MATERIAL_ARMATURY'
                    )
                        $getfrom = 'props_yalt1';
                    else
                        $getfrom = 'props_y';

                    $y = self::getSeoConst($getfrom, $value);

                    if($y!='' && $prop_code!='_PROIZVODITEL') $y;
                    if($y!='')
                    {
                        $arY[] = $y;
                        $good_value = true;
                    }

                }
                if(!$good_value)
                    $good = false;

                if($anyResult){
                    $good = true;
                }
            }

            if($good)
            {
                $secname = 'светильники';

                $out_x = implode(' ', $arX);
                $out_y = implode(' ', $arY);
                $out = $BP_TEMPLATE->str_fst_lower($out_x).' '.$BP_TEMPLATE->str_fst_lower($secname).' '.$out_y;

                return trim($out);
            } else
                return '';
        } else {
            return '';
        }
    }

    public function getFullNameAlt($arProps, $code)
    {
        if(count($arProps['PROPS'])>0)
        {
            $arX = [];
            $arY = [];
            $good = true;
            $arPropsCodes = array_keys($arProps['PROPS']);

            foreach($arProps['PROPS'] as $prop_code=>$arValue)
            {
                foreach($arValue as $value)
                {
                    //if($prop_code=='_STEPEN_ZASHCHITY_IP')
                    //    $value = 'IP'.$value;
                    $good_value = false;
                    $x = $y = $xalt = $yalt = $yalt1 = '';
                    $x = self::getSeoConst('props_xalt', $value);
                    if($x!='')
                    {
                        $arX[] = $x;
                        $good_value = true;
                    }

                    //additional rules for  props_yalt1
                    if(
                        in_array('_FORMA_PLAFONA',$arPropsCodes)
                        && ($prop_code=='TSVET_PLAFONOV' || $prop_code=='_MATERIAL_PLAFONOV')
                    )
                        $getfrom = 'props_yalt1';
                    elseif(
                        in_array('_MATERIAL_PLAFONOV',$arPropsCodes)
                        && $prop_code=='TSVET_PLAFONOV'
                    )
                        $getfrom = 'props_yalt1';
                    elseif(
                        in_array('TSVET_ARMATURY',$arPropsCodes)
                        && $prop_code=='_MATERIAL_ARMATURY'
                    )
                        $getfrom = 'props_yalt1';
                    else
                        $getfrom = 'props_yalt';


                    $y = self::getSeoConst($getfrom, $value);
                    if($y!='' && $prop_code!='_PROIZVODITEL') $y;
                    if($y!='')
                    {
                        $arY[] = $y;
                        $good_value = true;
                    }

                }
                if(!$good_value)
                    $good = false;
            }
            if($good)
            {
                $secname = 'светильники';
                $out_x = implode(' ', $arX);
                $out_y = implode(' ', $arY);
                $out = mb_ucfirst(strtolower($out_x)).' '.strtolower($secname).' '.$out_y;
                return trim($out);
            } else
                return '';
        } else {
            return '';
        }
    }

    public function convertUrlToCheck($url)
    {
        $result = array();
        $smartParts = explode("/", $url);
        foreach ($smartParts as $smartPart)
        {
            $item = false;
            $smartPart = preg_split("/-(from|to|is|or)-/", $smartPart, -1, PREG_SPLIT_DELIM_CAPTURE);

            $control_name = '';
            foreach ($smartPart as $i => $smartElement)
            {

                if ($i == 0)
                {
                    if (preg_match("/^price-(.+)$/", $smartElement, $match))
                        $control_name = strtoupper($match[1]);
                    else
                        $control_name = strtoupper($smartElement);
                }
                elseif ($smartElement === "from")
                {
                    $result['DIAP'][$control_name]["MIN"] = $smartPart[$i+1];
                }
                elseif ($smartElement === "to")
                {
                    $result['DIAP'][$control_name]["MAX"] = $smartPart[$i+1];
                }
                elseif ($smartElement === "is" || $smartElement === "or")
                {
                    $result['PROPS'][$control_name][] = $smartPart[$i+1];
                }
            }
            unset($item);

            //color temperature
            if(isset($result['DIAP']['_TEMPERATURA_SVETA_K']))
            {
                unset($result['PROPS']['_TEMPERATURA_SVETA_K']);
                if($result['DIAP']['_TEMPERATURA_SVETA_K']["MIN"]==3500 && $result['DIAP']['_TEMPERATURA_SVETA_K']["MAX"]==5000)
                {
                    $result['PROPS']['_TEMPERATURA_SVETA_K'][] = 'ColorTwhite';
                } elseif($result['DIAP']['_TEMPERATURA_SVETA_K']["MAX"]==3500) {
                    $result['PROPS']['_TEMPERATURA_SVETA_K'][] = 'ColorTwarm';
                } elseif($result['DIAP']['_TEMPERATURA_SVETA_K']["MIN"]==5000) {
                    $result['PROPS']['_TEMPERATURA_SVETA_K'][] = 'ColorTcold';
                }
            }

        }
        return $result;
    }
    public function NavRecordCount()
    {
        global $APPLICATION;
        return $APPLICATION->GetPageProperty("NavRecordCount");
    }
    public function getSeoConst($name, $value)
    {
        if(is_array($name)){
            foreach($name as $name_v){
                if($this->arConsts['SEO_'.$name_v]['VALUE']!=''){
                    $arValues = json_decode($this->arConsts['SEO_'.$name_v]['VALUE'], TRUE);

                    //Если переменная brand_trans для производителя не указана, то на её место подставляется переменная brand
                    if($name=='brand_trans' && !$arValues[$value])
                        $arValues = json_decode($this->arConsts['SEO_brand']['VALUE'], TRUE);

                    $res[] = $arValues[$value];
                }
            }
        }
        else{
            if($this->arConsts['SEO_'.$name]['VALUE']!=''){
                $arValues = json_decode($this->arConsts['SEO_'.$name]['VALUE'], TRUE);
                //Если переменная brand_trans для производителя не указана, то на её место подставляется переменная brand
                if($name=='brand_trans' && !$arValues[$value])
                    $arValues = json_decode($this->arConsts['SEO_brand']['VALUE'], TRUE);

                $res = $arValues[$value];
            }
        }

        return  $res;
    }

    public function getTitle($code, $type='SECTION')
    {
        global $APPLICATION,$BP_TEMPLATE;
        $page_url = $APPLICATION->getCurPage();
        switch ($type) {
            case 'ALL_CAT_FILTER':
                $arProps = self::convertUrlToCheck($page_url);
                $arValidFilt = [];
                if(is_array($arProps['PROPS']))
                {

                        $arValidFilt['CNT_PROP'] = count($arProps['PROPS']);
                        foreach($arProps['PROPS'] as $prop_code=>$arValue)
                        {
                            $arValidFilt['CNT_VALUES'] += count($arValue);
                        }

                    if($arValidFilt['CNT_PROP'] <= 2){
                        $arValidFilt['PROP_CODE'] = array_keys($arProps['PROPS'])[0];
                        if($arValidFilt['PROP_CODE'] == 'TYPE' || $arValidFilt['PROP_CODE'] == 'CATEGORY'){
                            return $BP_TEMPLATE->str_fst_upper(self::getFullName($arProps, $code,true)).' «Vele Luce» – купить недорого в Москве';
                        }
                        elseif($arValidFilt['PROP_CODE'] == '_NOVINKA'){
                            return $BP_TEMPLATE->str_fst_upper(self::getFullName($arProps, $code,true)).' «Vele Luce» – купить недорого в Москве';
                        }
                    }
                    elseif ($arValidFilt['CNT_PROP'] == 3 && $arValidFilt['CNT_VALUES'] == 3){
                        return $BP_TEMPLATE->str_fst_upper(self::getFullName($arProps, $code,true)).' «Vele Luce» – купить в Москве';
                    }

                }  else
                    return '';
            default:
                return '';
        }

    }

    public function getDesc($code, $type='SECTION')
    {
        global $APPLICATION,$BP_TEMPLATE;
        $page_url = $APPLICATION->getCurPage();
        switch ($type) {
            case 'ALL_CAT_FILTER':
                $arProps = self::convertUrlToCheck($page_url);
                $arValidFilt = [];
                if(is_array($arProps['PROPS']))
                {

                    $arValidFilt['CNT_PROP'] = count($arProps['PROPS']);
                    foreach($arProps['PROPS'] as $prop_code=>$arValue)
                    {
                        $arValidFilt['CNT_VALUES'] += count($arValue);
                    }

                    if($arValidFilt['CNT_PROP'] == 1 && $arValidFilt['CNT_VALUES'] == 1){
                        $arValidFilt['PROP_CODE'] = array_keys($arProps['PROPS'])[0];

                        if($arValidFilt['PROP_CODE'] == 'TYPE' || $arValidFilt['PROP_CODE'] == 'CATEGORY'){
                            return 'Купить '. $BP_TEMPLATE->str_fst_lower(self::getFullName($arProps, $code,true)).' «Vele Luce» по недорогой цене в Москве с гарантией от
производителя и доставкой по всей России. Модели всех стилей и цветов.';
                        }
                        elseif($arValidFilt['PROP_CODE'] == '_NOVINKA'){
                            return 'Купить '. $BP_TEMPLATE->str_fst_lower(self::getFullName($arProps, $code,true)).' «Vele Luce» по недорогой цене в Москве с гарантией от
производителя и доставкой по всей России. Модели всех стилей и цветов.';
                        }
                    }
                    elseif ($arValidFilt['CNT_PROP'] == 2 && $arValidFilt['CNT_VALUES'] == 2){
                            $maxname = self::getFullNameAlt($arProps, $code);
                            return 'Купить '. $BP_TEMPLATE->str_fst_lower(self::getFullName($arProps, $code,true)).' «Vele Luce» по недорогой цене в Москве с гарантией от производителя. '.$BP_TEMPLATE->str_fst_upper($maxname).' с
доставкой по всей России.';
                    }
                    elseif ($arValidFilt['CNT_PROP'] == 3 && $arValidFilt['CNT_VALUES'] == 3){
                        return 'Купить '. $BP_TEMPLATE->str_fst_lower(self::getFullName($arProps, $code,true)).' «Vele Luce» по недорогой цене в Москве с гарантией от
производителя и доставкой по всей России.';
                    }

                }  else
                    return '';
            default:
                return '';
        }

    }

}
