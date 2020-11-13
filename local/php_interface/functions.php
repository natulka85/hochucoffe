<?php if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true)die();
//большенство функций перенесены в класс CBPTemplate модуля  bp.template

if (!function_exists('mb_ucfirst'))
{
    function mb_ucfirst($value)
    {
        return mb_strtoupper(mb_substr($value, 0, 1)) . mb_substr($value, 1);
    }
}

if (!function_exists('sklonenie')) {
    function sklonenie($n, $forms) {
        return $n%10==1&&$n%100!=11?$forms[0]:($n%10>=2&&$n%10<=4&&($n%100<10||$n%100>=20)?$forms[1]:$forms[2]);
    }
}
if (!function_exists('getRealIpAddr1')) {
    function getRealIpAddr1()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) { $ip=$_SERVER['HTTP_CLIENT_IP']; }
        elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {    $ip=$_SERVER['HTTP_X_FORWARDED_FOR']; $ip = explode(",",$ip); $ip = $ip[0]; }
        else { $ip=$_SERVER['REMOTE_ADDR'];    }
        return $ip;
    }
}

\CModule::AddAutoloadClasses('', array(
    'nav\\IblockOrm\\MultiplePropertyElementTable' => '/local/lib/nav/IblockOrm/MultiplePropertyElementTable.php',
    'nav\\IblockOrm\\SinglePropertyElementTable' => '/local/lib/nav/IblockOrm/SinglePropertyElementTable.php',
    'nav\\IblockOrm\\ElementTable' => '/local/lib/nav/IblockOrm/ElementTable.php',
));
