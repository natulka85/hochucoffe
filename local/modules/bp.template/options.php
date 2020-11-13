<?php

use Bitrix\Main\Application;
use Bitrix\Main\Config\Option;
use Bitrix\Main\Localization\Loc;
//use Bitrix\Main\Text\String;

defined('ADMIN_MODULE_NAME') or define('ADMIN_MODULE_NAME', 'bp.template');


\Bitrix\Main\Loader::includeModule("iblock");

if (!$USER->isAdmin()) {
    $APPLICATION->authForm('Nope');
}

$app = Application::getInstance();
$context = $app->getContext();
$request = $context->getRequest();

Loc::loadMessages($context->getServer()->getDocumentRoot()."/bitrix/modules/main/options.php");
Loc::loadMessages(__FILE__);

$tabControl = new CAdminTabControl("tabControl", array(
    array(
        "DIV" => "edit2",
        "TAB" => 'СЕО переменные фильтра',
        "TITLE" => 'СЕО переменные фильтра',
    ),
    array(
        "DIV" => "edit3",
        "TAB" => 'СЕО переменные разделов каталога',
        "TITLE" => 'СЕО переменные разделов каталога',
    ),
));

if ((!empty($save) || !empty($restore)) && $request->isPost() && check_bitrix_sessid()) {
    if (!empty($restore)) {
        Option::delete(ADMIN_MODULE_NAME);
        CAdminMessage::showMessage(array(
            "MESSAGE" => Loc::getMessage("REFERENCES_OPTIONS_RESTORED"),
            "TYPE" => "OK",
        ));
    } elseif ($request->getPost('options')) {
        /*foreach($request->getPost('options') as $option_key=>$option_value)
        {
        } */
        /*Option::set(
            ADMIN_MODULE_NAME,
            'options',
            serialize($request->getPost('options'))
        );*/

        file_put_contents(__DIR__.'/lib/settings.txt', serialize($request->getPost('options')));

        CAdminMessage::showMessage(array(
            "MESSAGE" => Loc::getMessage("REFERENCES_OPTIONS_SAVED"),
            "TYPE" => "OK",
        ));
    } else {
        CAdminMessage::showMessage(Loc::getMessage("REFERENCES_INVALID_VALUE"));
    }
}
$tmp = unserialize(file_get_contents(__DIR__.'/lib/settings.txt'));

$tabControl->begin();
?>

<form method="post" action="<?=sprintf('%s?mid=%s&lang=%s', $request->getRequestedPage(), urlencode($mid), LANGUAGE_ID)?>">
    <?php
    echo bitrix_sessid_post();
    $tabControl->beginNextTab();

    $tabControl->BeginNextTab();

    $tabControl->buttons();
    ?>
    <input type="submit"
           name="save"
           value="<?=Loc::getMessage("MAIN_SAVE") ?>"
           title="<?=Loc::getMessage("MAIN_OPT_SAVE_TITLE") ?>"
           class="adm-btn-save"
           />
    <?php
    $tabControl->end();
    ?>
</form>
