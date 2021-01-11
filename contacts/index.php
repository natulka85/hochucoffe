<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Контакты");
\Bitrix\Main\Page\Asset::getInstance()->addCss("/local/templates/hochucoffe/static/dist/css/contacts.css");
?>

<div class="contacts">
    <?$APPLICATION->IncludeComponent("bitrix:breadcrumb","simple",Array(
            "START_FROM" => "0",
            "PATH" => "",
            "SITE_ID" => "s1"
        )
    );?>
    <?
    $APPLICATION->IncludeComponent(
        "mango:cache.set",
        "contacts",
        [

        ],
        false
    );?>
</div>

<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>
