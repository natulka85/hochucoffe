<?php
namespace Bp\Template;

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Loader;
use Bp\Template\SettingsTable;


Loc::loadMessages(__FILE__);

class SeoSection
{
    protected static $instance = null;
    public $stringProperties = [
        '_HIT_PRODAZH','AKCIYA','WEIGHT',
    ];

    public function __construct()
    {
        $dbConst = SettingsTable::getList(array(
            'select' => array('ID','NAME','CODE','VALUE'),
            'filter' => array('CODE' => 'SEO_%')
        ));
        while ($arConst = $dbConst->Fetch()){
            $this->arConsts[$arConst['CODE']] = $arConst;
        }
    }

    public static function getInstance()
    {
        if (!isset(static::$instance)) self::$instance = new SeoSection();
        return static::$instance;
    }

    public function getSeoConst($name, $value)
    {
        if(is_array($name)){
          foreach($name as $name_v){
              if($this->arConsts['SEO_'.$name_v]['VALUE']!=''){
                  $arValues = json_decode($this->arConsts['SEO_'.$name_v]['VALUE'], TRUE);
                  $res[] = $arValues[$value];
              }
          }
        }
        else{
            if($this->arConsts['SEO_'.$name]['VALUE']!=''){
                $arValues = json_decode($this->arConsts['SEO_'.$name]['VALUE'], TRUE);
                $res = $arValues[$value];
            }
        }

        return  $res;
    }

    public function getDetailText($id, $code, $type='SECTION',$name=false)
    {
        global $APPLICATION,$BP_TEMPLATE;
        $page_url = $APPLICATION->getCurPage();

        $arTextOff = json_decode($this->arConsts['SEO_text_off']['VALUE'], TRUE);

        if(in_array($page_url,$arTextOff))
            return '';

        //echo $type.'$type';

        switch ($type) {
            case 'ALL_CAT':
                $text = '<p>На нашем сайте вы можете выбрать для себя любимые и самые лучшие сорта кофе,  которые будут радовать Вас с каждым новым глотком!</p>
                        <p>Для того, чтобы было проще ориентироваться во всем ассортименте нашего интернет-магазина, мы подготовили:</p>
                            <ul>
                                <li>Качественное описание каждого товара;</li>
                                <li>Оценки SCA;</li>
                                <li>Страну и регион происхождения;</li>
                                <li>Год урожая;</li>
                                <li>Состав;</li>
                                <li>Вкусовые и ароматические оттенки;</li>
                                <li>Вес каждой пачки;</li>
                            </ul>
                          
                                У нас вы можете выбрать необходимую вам степень обжарки, а также помол для вашего способа приготовления совершенно БЕСПЛАТНО!
                          
                        <p>Мы работаем напрямую с лучшими обжарщиками кофе, которые имеют все необходимые сертификаты.<br>
                            Мы выбираем только надежных и самых быстрых партнеров по доставке, ведь,  чем свежее обжарка, тем насыщеннее вкус.<br>
                            Подробнее о способах и сроках доставки вы можете почитать здесь.
                        </p>
                            Менеджеры в нашем Контакт-центре знают о кофе все и с удовольствием помогут Вам определиться с выбором. Позвоните нам:
                            <ul>
                                <li>8(495)220-20-20 (для Москвы и МО)</li>
                                <li>8(800)220-20-20(бесплатно по РФ)</li>
                            </ul>
                        или просто закажите обратный звонок на сайте, и с Вами свяжутся в кратчайшие сроки.
                        <p>Кстати, наш контакт-центр работает 24/7</p>
                        <p>Почему покупают у нас:
                            <ul>
                                <li>Выбираем продукцию с высокими оценками SCA от 80+</li>
                                <li>100% арабика класса specialty</li>
                                <li>Все ТОПовые обжарщики на одном сайте</li>
                                <li>Всегда свежая обжарка на оборудовании признанных брендов</li>
                                <li>Выбор любой степени обжарки и помола</li>
                                <li>Быстрая и бережная доставка</li>
                                <li>Скидки и приятные подарки</li>
                                <li>Более 10 лет кропотливой работы с разными сортами кофе</li>
                            </ul>
                        </p>
                         <strong>Будем рады возможности побаловать Вас свежим и вкусным Кофе!</strong>';
                return $text;
            case 'SECTION':
                break;
            case 'ALL_CAT_FILTER' || 'SECTION_FILTER':
                $arProps = self::convertUrlToCheck($page_url);
                $text = '';

                if(count($arProps['PROPS'])==1 && count($arProps['PROPS'][array_keys($arProps['PROPS'])[0]][0])==1){
                    $arPredlog = json_decode($this->arConsts['SEO_predlog_1']['VALUE'], TRUE);
                    $arPredList = json_decode($this->arConsts['SEO_list_1']['VALUE'], TRUE);
                    foreach ($arProps['PROPS'] as $propCode=>$val){
                        $ar = [];
                        $ar['PROPS'][$propCode] = $val;

                        $arVars['predlog_1'][] = str_replace('[prop_value]',$BP_TEMPLATE->str_fst_lower(self::getFullName($ar, $id)),$arPredlog[$propCode]);
                        $arVars['list_1'][] = $arPredList[$propCode];
                    }
                    foreach ($arVars['predlog_1'] as $pred){
                        if($pred!=''){
                            $text .= '<p>'.$pred.'</p>';
                        }
                    }
                    $text .= '<p>Для того, чтобы было проще ориентироваться во всем ассортименте нашего интернет-магазина, мы подготовили:</p>';
                    $text .= '<ul>';

                    foreach ($arVars['list_1'] as $list){
                        if($list!=''){
                            $text .= '<li>'.$list.'</li>';
                        }
                    }
                    $text .= '<li>Качественное описание каждого товара;</li>
                                <li>Оценки SCA;</li>
                                <li>Страну и регион происхождения;</li>
                                <li>Год урожая;</li>
                                <li>Состав;</li>
                                <li>Вкусовые и ароматические оттенки;</li>
                                <li>Вес каждой пачки;</li>
                            </ul>
                          
                                У нас вы можете выбрать необходимую вам степень обжарки, а также помол для вашего способа приготовления совершенно БЕСПЛАТНО!
                          
                        <p>Мы работаем напрямую с лучшими обжарщиками кофе, которые имеют все необходимые сертификаты.<br>
                            Мы выбираем только надежных и самых быстрых партнеров по доставке, ведь,  чем свежее обжарка, тем насыщеннее вкус.<br>
                            Подробнее о способах и сроках доставки вы можете почитать здесь.
                        </p>
                            Менеджеры в нашем Контакт-центре знают о кофе все и с удовольствием помогут Вам определиться с выбором. Позвоните нам:
                            <ul>
                                <li>8(495)220-20-20 (для Москвы и МО)</li>
                                <li>8(800)220-20-20(бесплатно по РФ)</li>
                            </ul>
                        или просто закажите обратный звонок на сайте, и с Вами свяжутся в кратчайшие сроки.
                        <p>Кстати, наш контакт-центр работает 24/7</p>
                        <p>Почему покупают у нас:
                            <ul>
                                <li>Выбираем продукцию с высокими оценками SCA от 80+</li>
                                <li>100% арабика класса specialty</li>
                                <li>Все ТОПовые обжарщики на одном сайте</li>
                                <li>Всегда свежая обжарка на оборудовании признанных брендов</li>
                                <li>Выбор любой степени обжарки и помола</li>
                                <li>Быстрая и бережная доставка</li>
                                <li>Скидки и приятные подарки</li>
                                <li>Более 10 лет кропотливой работы с разными сортами кофе</li>
                            </ul>
                        </p>
                         <strong>Будем рады возможности побаловать Вас свежим и вкусным Кофе!</strong>';
                }
                return $text;
            default:
                return '';
        }
    }

    public function getTitle($id, $type='SECTION')
    {
        global $APPLICATION;
        $page_url = $APPLICATION->getCurPage();

        switch ($type) {}

    }

    public function getDesc($id, $type='SECTION')
    {
        global $APPLICATION;
        $page_url = $APPLICATION->getCurPage();
        switch ($type) {
        }
    }

    public function getH1($id, $type='SECTION', $page_url = '')
    {
        global $APPLICATION,$BP_TEMPLATE;
        if(!$page_url)
            $page_url = $APPLICATION->getCurPage();

        switch ($type) {
            case 'ALL_CAT':
                return 'Каталог зернового кофе свежей обжарки';
            case 'SECTION':
                return mb_ucfirst(self::getSeoConst('secname', $id));
            case 'ALL_CAT_FILTER' || 'SECTION_FILTER':
                $arProps = self::convertUrlToCheck($page_url);
                if(is_array($arProps['PROPS']))
                {
                    return $FullName = $BP_TEMPLATE->str_fst_upper(self::getFullName($arProps, $id));
                }  else
                    return '';
            default:
                return '';
        }
    }
    public function NavRecordCount()
    {
        global $APPLICATION;
        return $APPLICATION->GetPageProperty("NavRecordCount");
    }

    public function getCanonical($curDir)
    {
        if($curDir!='')
            \Bitrix\Main\Page\Asset::getInstance()->addString('<link rel="canonical" href="http://hochucoffe.ru'.$curDir.'">');
    }

    public function getRobots()
    {
        global $APPLICATION;
        $page_url = $APPLICATION->getCurPage();

        $arProps = self::convertUrlToCheck($page_url);

        $one_value = true;
        if(isset($arProps['PROPS']))
        {
            foreach($arProps['PROPS'] as $prop_code=>$arValue)
            {
                if(count($arValue)>1)
                    $one_value = false;
            }
            if(
                !$one_value
                ||
                count($arProps['PROPS'])>2
            )
                \Bitrix\Main\Page\Asset::getInstance()->addString('<meta name="robots" content="noindex, follow">');
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

        }
        return $result;
    }

    public function getFullName($arProps, $id,$anyResult=false)
    {
        $arStringProps = $this->stringProperties;

        if(count($arProps['PROPS'])>0)
        {
            //pre($arProps);
            $arX = [];
            $arY = [];
            $good = true;
            $arPropsCodes = array_keys($arProps['PROPS']);
            foreach($arProps['PROPS'] as $prop_code=>$arValue)
            {
                $i = 0;
                foreach($arValue as $value)
                {
                    $i++;
                    //if($prop_code=='_STEPEN_ZASHCHITY_IP')
                    //    $value = 'IP'.$value;
                    $good_value = false;
                    $x = $y = $xalt = $yalt = $yalt1 = '';
                    if(in_array($prop_code,$arStringProps)){
                        $value = $prop_code.'__'.$value;
                    }
                    $x = self::getSeoConst('props_x', $value);
                    if($i>1){
                        $x = self::getSeoConst('props_xalt', $value);
                        if($x!=''){
                            $x = ', '.$x;
                        }
                    }
                    if($x!='')
                    {
                        $arX[] = $x;
                        $good_value = true;
                    }

                        $getfrom = 'props_y';

                    $y = self::getSeoConst($getfrom, $value);
                    if($i>1){
                        $y = self::getSeoConst('props_yalt', $value);
                        if($y!=''){
                            $y = ', '.$y;
                        }
                    }

                    //if($y!='' && $prop_code!='_PROIZVODITEL') $y = strtolower($y);
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
                $out_x = str_replace(' , ',', ',implode(' ', $arX));
                $out_y = str_replace(' , ',', ',implode(' ', $arY));
                $out = mb_ucfirst(strtolower($out_x)).' '.strtolower(self::getSeoConst('secname', $id)).' '.$out_y;

                return trim($out);
            } else
                return '';
        } else {
            return '';
        }
    }

    public function getFullNameAlt($arProps, $id)
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
                    $getfrom = 'props_yalt';


                    $y = self::getSeoConst($getfrom, $value);
                    if($y!='' && $prop_code!='_PROIZVODITEL') $y = strtolower($y);
                    if($y!='')
                    {
                        $arY[] = $y;
                        $good_value = true;
                    }

                    /*if($x=='' && $y=='')
                    {
                        $x = self::getSeoConst('props_x', $value);
                        if($x!='')
                        {
                            $arX[] = $x;
                            $good_value = true;
                        }
                        $y = self::getSeoConst('props_y', $value);
                        if($y!='' && $prop_code!='_PROIZVODITEL') $y = strtolower($y);
                        if($y!='')
                        {
                            $arY[] = $y;
                            $good_value = true;
                        }
                    }*/
                }
                if(!$good_value)
                    $good = false;
            }
            if($good)
            {
                $out_x = implode(' ', $arX);
                $out_y = implode(' ', $arY);
                $out = mb_ucfirst(strtolower($out_x)).' '.strtolower(self::getSeoConst('secname', $id)).' '.$out_y;
                return trim($out);
            } else
                return '';
        } else {
            return '';
        }
    }
    public static function CustomRuslesNoindex()
    {
        include ($_SERVER['DOCUMENT_ROOT'].'/local/modules/bp.template/files/noindex_urls.php');
        global $APPLICATION,$arUrlsNoindex;
        if(in_array($_SERVER['REQUEST_URI_OLD'],$arUrlsNoindex)){
            $APPLICATION->AddHeadString('<meta name="robots" content="noindex, nofollow" />',true);
        }
    }

    public function FilterRules($section_id, &$arItems, $arActive=[])
    {
        global $APPLICATION;
         self::CustomRuslesNoindex();

        $arRules = [];
        $dbConst = \Bp\Template\SettingsTable::getList(array(
            'select' => array('ID','NAME','CODE','VALUE'),
            'filter' => array('CODE' => 'SEO_nometa')
        ));
        if ($arConst = $dbConst->Fetch())
            $arRules = json_decode($arConst['VALUE'],TRUE);
        if(count($arRules)>0)
        {
            $arOut = [];
            foreach($arRules as $arRule)
            {
                if($arRule['section_id']==$section_id)
                {
                    //Скрываем свойство в фильтре
                    if(in_array('FILTER_HIDE',$arRule['rule']))
                    {
                        foreach($arItems as $k=>$arItem)
                        {
                            if($arItem['ID']==$arRule['property_id'])
                            {
                                unset($arItems[$k]);
                            }
                        }
                    }

                    //если выбрано свойство - помечаем страницу как безметатеговую
                    if(in_array('CHILDS_META_HIDE',$arRule['rule']) && is_array($arActive))
                    {
                        foreach($arActive as $k=>$name)
                        {
                            if(strpos($k,'arrFilter_'.$arRule['property_id'])!==false)
                                $APPLICATION->SetPageProperty("withoutmeta", "Y");
                        }
                    }

                    //если выбрано свойство - выводим robots "noindex, follow"
                    if(in_array('CHILDS_SHOW_NOINDEX',$arRule['rule']) && is_array($arActive))
                    {
                        foreach($arActive as $k=>$name)
                        {
                            if(strpos($k,'arrFilter_'.$arRule['property_id'])!==false)
                                $APPLICATION->AddHeadString('<meta name="robots" content="noindex, follow"/>',true);
                        }
                    }

                    if(in_array('CHILDS_NO_TEXT',$arRule['rule']) && is_array($arActive) && $arRule['property_id']!='')
                    {
                        foreach($arActive as $k=>$name)
                        {
                            if(strpos($k,'arrFilter_'.$arRule['property_id'])!==false)
                                $APPLICATION->SetPageProperty("withouttext", "Y");
                        }
                    }
                    if(in_array('CHILDS_NO_TEXT',$arRule['rule']) && $arRule['property_id']=='' && !is_array($arActive))
                    {
                        $APPLICATION->SetPageProperty("withouttext", "Y");
                    }
                    $arOut = $arRule;
                }
            }
            return $arOut;
        } else {
            return false;
        }
    }
}
