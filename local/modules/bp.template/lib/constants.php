<?php
namespace Bp\Template;

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Loader;
use Bp\Template\SettingsTable;
use Bitrix\Main\Data\Cache;

Loc::loadMessages(__FILE__);

class Constants
{
    protected static $instance = null;

    //public $test2 = 'tt24';

    protected function __construct()
    {

        //тянем константы из таблицы , кэшируем
        $cache = Cache::createInstance();
        if ($cache->initCache('3600', 'bp_template_consts', 'hochucoffe'))
        {
            $arResult = $cache->getVars();
        }
        elseif ($cache->startDataCache())
        {
            $arResult = [];
            $dbConst = SettingsTable::getList([
                'select' => ['CODE','VALUE'],
                'filter' => ['CODE' => 'CONST_%']
            ]);
            while ($arConst = $dbConst->Fetch())
            {
                $arResult[] = $arConst;
            }

            $cache->endDataCache($arResult);
        }

        foreach($arResult as $arConst)
        {
            $key = (string) str_replace('CONST_','',$arConst['CODE']);
            $this->$key = $arConst['VALUE'];
        }

    }
    protected function __clone()
    {

    }

    public static function getInstance()
    {
        if (!isset(static::$instance)) self::$instance = new Constants();
        return static::$instance;
    }

}
