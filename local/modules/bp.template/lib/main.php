<?php
namespace Bp\Template;


use Bitrix\Main\Localization\Loc;
use Bp\Template\Basket;
use Bp\Template\Constants;
use Bp\Template\Session;
use Bitrix\Main\Loader;
use Bp\Template\Userstat;
use \Olegpro\IpGeoBase\IpGeoBase;

Loc::loadMessages(__FILE__);

class Main
{
    protected static $instance = null;

    public function __construct()
    {
        if(!$_SESSION){
            session_start();
        }
        //автозагрузка событий
        //Создаем инстанс нашего загрузчика
        $eventListener = new \Bp\Template\Tools\Events\Listener();
        //Добавляем соответствие между пространством имен и директорией, в которой будет производиться поиск обработчиков
        //с этим пространством имен
        $eventListener->addNamespace('Bp\\Template\\EventHandlers', $_SERVER['DOCUMENT_ROOT']. '/local/modules/bp.template/lib/EventHandlers');
        //Вызываем метод регистрации, который соберет все классы и вызовет для всех функцию AddEventHandler
        $eventListener->register();
        self::initCity();
        self::initHit();
    }

    protected function __clone()
    {

    }
    public $dopProperties = [
        'POMOL'=>[
            'DEFAULT_VALUE' => 'Не молоть',
            'NAME_OPT' => ' помол',
            'NAME_PROP' => 'Помол'
        ],
        'STEPEN_OBJARKI'=>[
            'DEFAULT_VALUE' => 'Слабая',
            'NAME_OPT' => ' помол',
            'NAME_PROP' => 'Степень обжарки'
        ]
    ];
    public $pomolSposobTem = [
        'franch-press'=>[
            'name'=>'Фрэнс-пресс',
            'icon'=>'icon-3d_franch'
        ],
        'kemeks'=>[
            'name'=>'Кемекс',
            'icon'=>'icon-3c_kem'
        ],
        'aeroexpress'=>[
            'name'=>'Аэропресс',
            'icon'=>'icon-3e_aero'
        ],
        'purover'=>[
            'name'=>'Пуровер',
            'icon'=>'icon-3f_pur'
        ],
        'aspresso'=>[
            'name'=>'Эспрессо',
            'icon'=>'icon-3g_espres'
        ],
        'turka'=>[
            'name'=>'Турка',
            'icon'=>'icon-3h_turka'
        ],
        'sifon'=>[
            'name'=>'Сифон',
            'icon'=>'icon-3i_sif'
        ],
    ];
    public $pomolSposob  = [
        'Не молоть' =>['franch-press','kemeks','aeroexpress','sifon','purover','aspresso','turka'],
        'Мелкий' =>[
            'franch-press','kemeks','aeroexpress','sifon','purover','aspresso','turka'
        ],
        'Средний' =>[
            'franch-press','kemeks','aeroexpress','sifon','purover','aspresso','turka'
        ],
        'Средний+' =>[
            'franch-press','kemeks','aeroexpress','sifon','purover','aspresso','turka'
        ],
        'Крупный' =>[
            'franch-press','kemeks','aeroexpress','sifon','purover','aspresso','turka'
        ],
    ];

    public static function initCity(){
        global $BP_TEMPLATE;
        if(Loader::includeModule("olegpro.ipgeobase") && !$_COOKIE['curcityid']){

            $geo = IpGeoBase::getInstance()->getRecord($_SERVER['HTTP_X_FORWARDED_FOR']);
            if($geo['city'] == '')
                $geo['city'] = 'Москва';

            if(strlen($geo["city"])>0)
            {
                setcookie ("curcityid", urldecode($geo["city"]),time()+3600*24*30, "/");
            }
            else
            {
                setcookie ("curcityid", urldecode('Москва'),time()+3600*24*30, "/");
            }
        }

        if ($_SESSION['bp_cache']['bp_user']['city']=="" && $_COOKIE['curcityid']!="") {    //первый заход по сессии при рабочих куках
            $_SESSION['bp_cache']['bp_user']['city'] = urldecode($_COOKIE['curcityid']);
        }
        if ($_SESSION['bp_cache']['bp_user']['city']=="") {   //город по умолчанию  - Москва при нерабочих куках
            $_SESSION['bp_cache']['bp_user']['city'] = "Москва";
        }
        if (
            $_SESSION['bp_cache']['bp_user']['city']!=""
            && $_COOKIE['curcityid']!="" &&
            $_SESSION['bp_cache']['bp_user']['city']!=urldecode($_COOKIE['curcityid'])
        ) { //смена города
            $_SESSION['bp_cache']['bp_user']['city'] = urldecode($_COOKIE['curcityid']);
        }

        $cache = \Bitrix\Main\Data\Cache::createInstance();
        if ($cache->initCache('3600', serialize('initcities'),'hochucoffe'))
        {
            $ar = $cache->getVars();
        }
        elseif ($cache->startDataCache())
        {
            \CModule::IncludeModule('iblock');
            $arFilter = ['IBLOCK_ID'=>2,'ACTIVE'=>'Y'];
            $res = \CIBlockElement::GetList(Array(""), $arFilter, false,  false, ['*','PROPERTY_FREE_DELIVERY_SUMM']);
            $i = 0;
            while($ob = $res->Fetch())
            {
                $city_name = trim($ob['NAME']);
                $ar[$city_name]  = $ob;
            }
            $cache->endDataCache($ar);
        }
        $_SESSION['bp_cache']['bp_user']['city_data'] = $ar[$_SESSION['bp_cache']['bp_user']['city']];
    }

    public static function getInstance()
    {
        if (!isset(static::$instance)) self::$instance = new Main();
        return static::$instance;
    }

    public function initHit()
    {

        //unset($_SESSION['bp_cache']['bp_user']['basket']);
        //unset($_SESSION['bp_cache']['bp_user']['delay']);
        //unset($_SESSION['bp_cache']['bp_user']['products']);
        //unset($_SESSION['bp_cache']['bp_user']);

        if(
            $_SERVER['HTTP_BX_ACTION_TYPE']=='get_dynamic' //на хитах композита
            || $_REQUEST['catalog_ajax_call']=='Y' // хиты ajax
            //обычные хиты , но только скрипты скрипты самих страниц(не js,css, левые php т.д.) (не нашел за что еще зацепится)
            || $_SERVER['HTTP_ACCEPT'] == 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8'
        )
        {
            //unset($_SESSION['bp_cache']['bp_user']['products_curpage']);
            //\Bitrix\Main\Diag\Debug::writeToFile($_SERVER,'SERVER','_tt.txt');
            //\Bitrix\Main\Diag\Debug::writeToFile($_SESSION['bp_cache']['bp_user']['products_curpage'],'products_curpage','_tt.txt');
        }

        //basket init
        if(count($_SESSION['bp_cache']['bp_user']['basket'])>0)
        {
            $arBasket = Basket::getCurBasketList();
            if(count($arBasket)==0)
                unset($_SESSION['bp_cache']['bp_user']['basket']);
            else
            {
                foreach($arBasket as $basket)
                {
                    $_SESSION['bp_cache']['bp_user']['basket'][$basket['PRODUCT_ID']][$basket['ID']] = [
                        'quantity' => $basket['QUANTITY'],
                        'basket_id' => $basket['ID'],
                        'basket_props' => $basket['PROPS'],
                    ];
                    foreach ($basket['PROPS'] as $prop){
                        if(in_array($prop['CODE'], array_keys($this->dopProperties))){
                            $arProp[$prop['CODE']] = $prop['CODE'].'||'.$prop['NAME'].'||'.$prop['VALUE'];
                        }
                    }
                    $_SESSION['bp_cache']['bp_user']['basket_code'][$basket['PRODUCT_ID']][$basket['ID']] = json_encode($arProp,JSON_UNESCAPED_UNICODE);
                    $arElement = Userstat::getProduct($basket['PRODUCT_ID']);
                    $_SESSION['bp_cache']['bp_user']['products'][$arElement['ID']] = $arElement;
                }
            }
        }
        elseif(count($_SESSION['bp_cache']['bp_user']['basket'])<=0)
        {
            $arBasket = Basket::getCurBasketList();
            foreach($arBasket as $basket)
            {
                $_SESSION['bp_cache']['bp_user']['basket'][$basket['PRODUCT_ID']][$basket['ID']] = [
                    'quantity' => $basket['QUANTITY'],
                    'basket_id' => $basket['ID'],
                    'basket_props' => $basket['PROPS'],
                ];
                foreach ($basket['PROPS'] as $prop){
                    if(in_array($prop['CODE'], array_keys($this->dopProperties))){
                        $arProp[$prop['CODE']] = $prop['CODE'].'||'.$prop['NAME'].'||'.$prop['VALUE'];
                    }
                }
                $_SESSION['bp_cache']['bp_user']['basket_code'][$basket['PRODUCT_ID']][$basket['ID']] = json_encode($arProp,JSON_UNESCAPED_UNICODE);
                $arElement = Userstat::getProduct($basket['PRODUCT_ID']);
                $_SESSION['bp_cache']['bp_user']['products'][$arElement['ID']] = $arElement;
            }
        }

        //delay init
        if(count($_SESSION['bp_cache']['bp_user']['delay'])<=0)
        {
            $arDelay = Basket::getDelayBasketList();
            if(is_array($arDelay))
            {
                foreach($arDelay as $id)
                {
                    $_SESSION['bp_cache']['bp_user']['delay'][$id] = $id;
                    $arElement = Userstat::getProduct($id);
                    $_SESSION['bp_cache']['bp_user']['products'][$arElement['ID']] = $arElement;
                }
            }
        }

        //compare init
        if(count($_SESSION['bp_cache']['bp_user']['compare'])<=0)
        {
            $arDelay = Basket::getCompareBasketList();
            if(is_array($arDelay))
            {
                foreach($arDelay as $id)
                {
                    $_SESSION['bp_cache']['bp_user']['compare'][$id] = $id;
                    $arElement = Userstat::getProduct($id);
                    $_SESSION['bp_cache']['bp_user']['products'][$arElement['ID']] = $arElement;
                }
            }
        }
    }
    public function Catalog()
    {
        return Catalog::getInstance();
    }
    public function getConstants()
    {
        return Constants::getInstance();
    }
    public function Basket()
    {
        return Basket::getInstance();
    }
    public function ChpuFilter()
    {
        return ChpuFilter::getInstance();
    }
    public function SeoSection()
    {
        return seosection::getInstance();
    }
    public function InsRod($phrase){
        $rod = '';
        $arWords=[
            'Ж' => [

            ],
            'М' => [
                'кофе',
            ],
            'С' => [],
        ];
        $arExc = [
            'М' => ['комплект']
        ];

        foreach($arExc as $r=>$words){
            foreach($words as $word){
                if(stripos($phrase,$word)!==false){
                    $rod = $r;
                    break(1);
                }
            }
        }
        if($rod == ''){
            foreach($arWords as $r=>$words){
                foreach($words as $word){
                    if(stripos($phrase,$word)!==false){
                        $rod = $r;
                        break(1);
                    }
                }
            }
        }
        return $rod;
    }
    public function Valid($valid,$form_class){
        $res = '';
        foreach($valid as $name=>$val){
            if($val == false){
                $res .= '$("'.$form_class.' input[name=\''.$name.'\']").addClass(\'is-error\');';
                $res .= '$("'.$form_class.' textarea[name=\''.$name.'\']").addClass(\'is-error\');';
            }
        }
        return $res;
    }
    public function takePhotoFromDir($dir){
        $arFiles = scandir($dir);
        foreach($arFiles as $file){
            if($file == '.' || $file == '..') continue;
            else{
                $arUrls[] = str_replace($_SERVER['DOCUMENT_ROOT'],'',$dir.$file);
            }
        }
        return $arUrls;
    }
    public function str_fst_upper($fullname)
    {
        $fc = mb_strtoupper(mb_substr($fullname, 0, 1));
        $fullname = $fc.mb_substr($fullname, 1);
        return $fullname;
    }
    public function str_fst_lower($word) {

        $first = mb_strtolower(mb_substr($word,0,1));//первая буква
        $last = mb_substr($word,1);//все кроме первой буквы
        $word = mb_strtolower($first).$last;

        return $word;
    }
}
