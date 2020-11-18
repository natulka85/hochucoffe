<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Корзина");
\Bitrix\Main\Page\Asset::getInstance()->addJs("/local/templates/hochucoffe/static/js/minify-js/basket.min.js");
?>
<div class="basket">
    <?$APPLICATION->IncludeComponent("bitrix:breadcrumb","simple",Array(
            "START_FROM" => "0",
            "PATH" => "",
            "SITE_ID" => "s1"
        )
    );?>
    <div class="page-block-head"><h1 class="page-title _type-1">Корзина</h1></div>
    <?
    global $USER;
    $USER_ID = 38; // поменять
    if($_GET["userkey"])
    { //Надо для заказов по телефону - ответ на ссылку менеджера
        $USER->Logout();
        $USER->Authorize($USER_ID);  //в конце страницы логаут
        //print_r($USER->GetID());
    }
    ?>
    <?if(LK=='Y' && $USER->IsAuthorized()):?><div class="lk-basket-content"><?endif?>
        <?$APPLICATION->IncludeComponent(
            "mango:cache.set",
            "basket",
            array(
                "YSHOWN" => "Y",
            ),
            false
        );
        ?><?if(LK=='Y' && $USER->IsAuthorized()):?></div><?endif?><?
    if($USER->getID()==$USER_ID)
        $USER->Logout();
    ?>
</div>

<?require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");?>
