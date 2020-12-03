<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Контакты");
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
