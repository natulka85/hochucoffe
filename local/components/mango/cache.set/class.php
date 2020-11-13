<?php

class CacheSet extends CBitrixComponent
{
	public function executeComponent ()
	{
        global  $USER;

        if($arParams["CACHE_TIME"] == '')
            $arParams["CACHE_TIME"] = 3600;
        
        $arParams["CACHE_TIME"] = (int) $arParams["CACHE_TIME"];
        
        if($this->startResultCache(false, array($arParams, $USER->GetGroups())))
        {
            $this->includeComponentTemplate();
        }
	}
}