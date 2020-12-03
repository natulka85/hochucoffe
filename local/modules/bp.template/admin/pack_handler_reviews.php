<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_before.php");
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_after.php");
use Bitrix\Main,
    Bitrix\Main\Localization\Loc,
    Askaron\Reviews\ReviewTable;

\CModule::IncludeModule("askaron.reviews");

$ar_groups  = $USER->GetUserGroup($USER->GetID());
if(!$USER->IsAdmin() && !in_array(6,$ar_groups)){
    $APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));
}

CJSCore::Init(array("jquery"));
$APPLICATION->AddHeadScript('/local/templates/hochucoffe/static/js/vendors/jquery.placeholder.min.js');
$APPLICATION->AddHeadScript('/local/templates/hochucoffe/static/js/vendors/maskedinput.js');
$APPLICATION->AddHeadScript('/local/modules/script.js');
$APPLICATION->SetAdditionalCSS('/local/modules/styles.css');

$app = \Bitrix\Main\Application::getInstance();
$context = $app->getContext();
$request = $context->getRequest();

//Loc::loadMessages($context->getServer()->getDocumentRoot()."/bitrix/modules/main/options.php");
//Loc::loadMessages(__FILE__);

$tabControl = new CAdminTabControl("tabControl", array(
    array(
        "DIV" => "edit1",
        "TAB" => 'Загрузка отзывов',
        "TITLE" => 'Загрузка отзывов',
    ),
));

if ((!empty($save) || !empty($restore)) && $request->isPost() && check_bitrix_sessid()) {
    $isChanged = false;
    if (!empty($restore)) {
        Option::delete(ADMIN_MODULE_NAME);
        CAdminMessage::showMessage(array(
            "MESSAGE" => 'Восстановлены настройки по умолчанию',
            "TYPE" => "OK",
        ));
    } elseif ($request->getPost('element')) {
        $elements = $request->getPost('element');

        foreach ($elements as $postValue){

            $arFileds = [
                'ACTIVE' => 'Y',
                'AUTHOR_NAME' => $postValue['name'],
                'GRADE' => $postValue['grade'],
                'TEXT' => $postValue['review'],
                'ELEMENT_ID' => $postValue['id'],
                'AUTHOR_USER_ID' => $USER->GetID(),
            ];
            if($postValue['date']!='')
              $arFileds['DATE'] = new \Bitrix\Main\Type\DateTime($postValue['date'], "d.m.Y");
            else
              $arFileds['DATE'] = new \Bitrix\Main\Type\DateTime();

            $result = \Askaron\Reviews\ReviewTable::add($arFileds);

            if ($result->isSuccess())
            {
                $id = $result->getId();
            } else {
                $json['error'] = 'error';
                //$json['func'] = "alert('".print_r($result->getErrorMessages())."')";
            }

        }

        //file_put_contents($file_settings,json_encode($arRes,JSON_UNESCAPED_UNICODE));

        CAdminMessage::showMessage(array(
            "MESSAGE" => 'Настройки сохранены',
            "TYPE" => "OK",
        ));
        sleep(10);
        LocalRedirect('/bitrix/admin/admin_helper_route.php?lang=ru&module=bp.template&view=reviews_list&entity=tools');
    } else {
        CAdminMessage::showMessage('Введено неверное значение');
    }

    if($isChanged)
    {
        $cache = Cache::createInstance();
        $cache->clean('reviewsgood', '/iblock/module_review');
    }
}

$tabControl->begin();
$tabControl->beginNextTab();

?>
<tr>
    <td>
        <form class="nojs pack-handler-form" method="post" action="<?=$request->getRequestUri()?>">
            <?echo bitrix_sessid_post();?>
            <input type="file" name="file_table">
            <div class="btn is-blue js-adm-do" data-action="reviews-list-upload" data-take="form">Загрузить файл</div>
        </form>
    </td>
</tr>
<?$tabControl->BeginNextTab();?>

<?$tabControl->end();?>

<?require_once $_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/epilog_admin.php';?>
