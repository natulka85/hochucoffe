<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

switch ($arParams["MOD_TEMPATE"]) {
    case 'CARD':
        include __DIR__."/card.php";
        break;
    default:
        include __DIR__."/default.php";
}
?>
