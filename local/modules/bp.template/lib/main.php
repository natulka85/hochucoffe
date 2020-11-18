<?php
namespace Bp\Template;


use Bitrix\Main\Localization\Loc;
use Bp\Template\Constants;
use Bp\Template\Basket;
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
    }

    protected function __clone()
    {

    }
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

        $first = mb_substr($word,0,1);//первая буква
        $last = mb_substr($word,1);//все кроме первой буквы
        $word = mb_strtolower($first).$last;

        return $word;
    }
}
