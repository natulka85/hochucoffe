<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Новый раздел");
?>


<?$APPLICATION->IncludeComponent("bitrix:breadcrumb","simple",Array(
        "START_FROM" => "0",
        "PATH" => "",
        "SITE_ID" => "s1"
    )
);

echo 'sssss';

?>
<br>

<br>
<br>
<br>
<br>
<br>




<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>
