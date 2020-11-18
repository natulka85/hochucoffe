<?
use Bp\Template\Userstat;define('FOOTER_TYPE','type-1');
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("");
$APPLICATION->SetPageProperty("description","");
$APPLICATION->SetPageProperty("keywords","");
global $BP_TEMPLATE;
CModule::IncludeModule("iblock");


global $BP_TEMPLATE,$USER;
$_REQUEST['q'] = 'Кофе Бразилия Альта Виста, 250г';
?>
<?
$id = 524;
global $arFilterCard;
$arFilterCard = array(
    "=ID" => $id,
);

$arSettings = [
    "IBLOCK_TYPE" => $BP_TEMPLATE->getConstants()->IBLOCK_MAIN_IB_TYPE,
    "IBLOCK_ID" => $BP_TEMPLATE->getConstants()->IBLOCK_MAIN_ID,
    "COUNT_ON_PAGE" => 1,
    "CACHE_TIME"  =>  86400,
    "SECTION_ID" => $BP_TEMPLATE->getConstants()->IBLOCK_MAIN_TYPE,
    "FILTER_NAME" => 'arFilterCard',
    'MOD_TEMPATE' => 'CARD',
    'CATEGORY_TYPE'=> 'ONE_CARD'
];

$APPLICATION->IncludeComponent(
    "mango:cache.set",
    "one_element",
    $arSettings,
    false
);

    ?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
