<?
use Bp\Template\Userstat;define('FOOTER_TYPE','type-1');
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("");
$APPLICATION->SetPageProperty("description","");
$APPLICATION->SetPageProperty("keywords","");
global $BP_TEMPLATE;

$ar = $BP_TEMPLATE->ChpuFilter()->getNewPartsL();

$_REQUEST['q'] = 'орех';
$APPLICATION->IncludeComponent(
        "mango:search-hints",
        "",
        array(
            'IBLOCK_ID' => 1,
            'CACHE_TIME' => 259200,
            'PICTURE'=> ['width'=>66, 'heigth'=>60],
            'SEARCH_PAGE' => '/search/',
            'REQUEST' => $_REQUEST['q'],
            'REQUEST_SECTION' => $_REQUEST['section'],
            'FIX_HEADER' => $_REQUEST['fixheader']
        ),
        false
    );



    ?>



<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
