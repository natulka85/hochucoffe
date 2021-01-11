<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Честные отзывы наших покупателей о кофе");

\Bitrix\Main\Page\Asset::getInstance()->addJs(SITE_TEMPLATE_PATH."/static/dist/js/vendors/masonry.pkgd.min.js");
\Bitrix\Main\Page\Asset::getInstance()->addJs(SITE_TEMPLATE_PATH."/static/dist/js/vendors/imagesloaded.pkgd.min.js");
\Bitrix\Main\Page\Asset::getInstance()->addJs("/local/templates/hochucoffe/static/dist/js/reviews.min.js");
\Bitrix\Main\Page\Asset::getInstance()->addCss("/local/templates/hochucoffe/static/dist/css/reviews-page.css");

?>
<div class="reviews-page">
    <?$APPLICATION->IncludeComponent("bitrix:breadcrumb","simple",Array(
            "START_FROM" => "0",
            "PATH" => "",
            "SITE_ID" => "s1"
        )
    );?>
    <div class="page-block-head"><h1 class="page-title _type-1">Честные отзывы наших покупателей о кофе</h1></div>
    <?
    $APPLICATION->IncludeComponent(
        "mango:cache.set",
        "review-index",
        [
            'LIST_CLASS' =>'mansonry__content',
            'CNT' => '',
            'GOOD_LINK' => 1,
            'GOOD_LINK_TARGET' => '_blank',
        ],
        false
    );?>
</div>
<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>
