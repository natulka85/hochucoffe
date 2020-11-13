<?
define('FOOTER_TYPE','type-1');
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("");
$APPLICATION->SetPageProperty("description","");
$APPLICATION->SetPageProperty("keywords","");
global $BP_TEMPLATE;
CModule::IncludeModule("iblock");

echo trim($BP_TEMPLATE->str_fst_upper('цитрус'));


?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
