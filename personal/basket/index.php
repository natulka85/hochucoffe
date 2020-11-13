<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Новый раздел");
?>

<?$notemptybasket=$APPLICATION->IncludeComponent(
    "bitrix:sale.order.ajax",
    ".default",
    array(
        "PAY_FROM_ACCOUNT" => "N",
        "ONLY_FULL_PAY_FROM_ACCOUNT" => "N",
        "COUNT_DELIVERY_TAX" => "N",
        "ALLOW_AUTO_REGISTER" => "Y",
        "SEND_NEW_USER_NOTIFY" => "N",
        "DELIVERY_NO_AJAX" => "Y",
        "DELIVERY_NO_SESSION" => "N",
        "TEMPLATE_LOCATION" => "popup",
        "DELIVERY_TO_PAYSYSTEM" => "d2p",
        "USE_PREPAYMENT" => "N",
        "PROP_1" => array(
        ),
        "ALLOW_NEW_PROFILE" => "N",
        "SHOW_PAYMENT_SERVICES_NAMES" => "Y",
        "SHOW_STORES_IMAGES" => "N",
        "PATH_TO_BASKET" => "/",
        "PATH_TO_PERSONAL" => "index.php",
        "PATH_TO_PAYMENT" => "payment.php",
        "PATH_TO_AUTH" => "/auth/",
        "SET_TITLE" => "Y",
        "DISABLE_BASKET_REDIRECT" => "Y",
        "PRODUCT_COLUMNS" => array(
            0 => "PREVIEW_PICTURE",
            1 => "PROPERTY__KOLICHESTVO_LAMP",
            2 => "PROPERTY__TIP_TSOKOLYA",
            3 => "PROPERTY__PROIZVODITEL",
            4 => "PROPERTY__STRANA",
            5 => "PROPERTY_CML2_ARTICLE",
            6 => "PROPERTY__RAZDEL_NA_SAYTE",
            7 => "PROPERTY__NOVINKA",
            8 => "PROPERTY_KRATNOST_OTGRUZKI_SHT",
        ),

        "AJAX_MODE" => "Y",
        "AJAX_OPTION_SHADOW" => "N",
        "AJAX_OPTION_JUMP" => "N",
        "AJAX_OPTION_STYLE" => "Y",
        "AJAX_OPTION_HISTORY" => "N",
        'USER_CONSENT' => 'Y',
        'USER_CONSENT_ID' => 1,
        'USER_CONSENT_IS_CHECKED' => 'Y',
        'USER_CONSENT_IS_LOADED' => 'Y'
    ),
    false
);
?>

<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>
