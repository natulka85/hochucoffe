<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
global $BP_TEMPLATE;


$cp = $this->__component; // объект компонента
if (is_object($cp)) {
    //$cp->arResult['DATE_ACTIVE_TO'] = $arAddChainSec1;
    $cp->arResult['MOD_TAGS'] = $arTags;
    $cp->SetResultCacheKeys(Array('PREVIEW_TEXT','MOD_TAGS','MOD_CUR_SECTION'));
}
?>
