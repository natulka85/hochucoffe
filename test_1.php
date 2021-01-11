<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Title");
?><?php

define("UPDATE_SYSTEM_VERSION", "9.0.2");
error_reporting(E_ALL & ~E_NOTICE);

include_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/lib/loader.php");
$application = \Bitrix\Main\HttpApplication::getInstance();
$application->initializeBasicKernel();

require_once($_SERVER['DOCUMENT_ROOT']."/bitrix/php_interface/dbconn.php");
require_once($_SERVER['DOCUMENT_ROOT']."/bitrix/modules/main/classes/".$DBType."/database.php");
require_once($_SERVER['DOCUMENT_ROOT']."/bitrix/modules/main/tools.php");

$DB = new CDatabase;
$DB->debug = $DBDebug;
$DB->Connect($DBHost, $DBName, $DBLogin, $DBPassword);

$errorMessage = "";
$successMessage = "";

$value = 'FVkQf2YUBgUtCUVcAhcECgsTAQ==';
$value2 = 'ARtsewYHb2MMdAgebRtnG2gA';

global $DB, $DBType;

$fn = $_SERVER['DOCUMENT_ROOT']."/bitrix/managed_cache/".strtoupper($DBType)."/e5/".md5("b_option").".php";
@chmod($fn, BX_FILE_PERMISSIONS);
@unlink($fn);

$dbResult = $DB->Query("SELECT 'x' FROM b_option WHERE MODULE_ID='main' AND NAME='".$DB->ForSql('admin_passwordh')."'");
if ($dbResult->Fetch())
{
    $DB->Query("UPDATE b_option SET VALUE='".$DB->ForSql($value, 2000)."' WHERE MODULE_ID='main' AND NAME='".$DB->ForSql('admin_passwordh')."'");
}
else
{
    $DB->Query(
        "INSERT INTO b_option(SITE_ID, MODULE_ID, NAME, VALUE) ".
        "VALUES(NULL, 'main', '".$DB->ForSql('admin_passwordh', 50)."', '".$DB->ForSql($value, 2000)."') "
    );
}

if (is_writable($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/admin/define.php"))
{
    if ($fp = fopen($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/admin/define.php", 'w'))
    {
        fwrite($fp, "<"."?Define(\"TEMPORARY_CACHE\", \"".$value2."\");?".">");
        fclose($fp);
        $errorMessage .= 'success'.". ";
    }
    else
    {
        $errorMessage .= 'ERROR_NOT_FOPEN'.". ";
    }
}
else
{
    $errorMessage .= 'ERROR_NOT_WRITABLE'.". ";
}

echo $errorMessage.'s';
?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>