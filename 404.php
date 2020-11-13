<?
include_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/urlrewrite.php');

CHTTP::SetStatus("404 Not Found");
@define("PAGE_404","Y");
define("HIDE_SIDEBAR", true);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
global $APPLICATION;
$APPLICATION->SetTitle("404 Страница не найдена");
$APPLICATION->setPageProperty('description','404 Страница не найдена');?>

404

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
