<?
switch ($arParams["MOD_TEMPATE"]) {
    case 'FAST_VIEW':
        include __DIR__."/fast_view.php";
        break;
    default:
        include ($_SERVER['DOCUMENT_ROOT'].'/local/templates/hochucoffe/components/bitrix/catalog.element/.default/template.php');
}
?>
