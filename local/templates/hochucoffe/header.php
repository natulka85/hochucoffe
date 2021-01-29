<?global $USER;?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <?CJSCore::Init(array('mainjs'));?>
    <link href="<?=SITE_TEMPLATE_PATH?>/static/dist/css/global.css" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?$APPLICATION->ShowTitle();?></title>
    <?//include_once ($_SERVER['DOCUMENT_ROOT'].'/includes/counters/metrika.php');?>
    <?//include_once ($_SERVER['DOCUMENT_ROOT'].'/includes/counters/ga.php');?>
    <?$APPLICATION->ShowHead();?>
    <?$APPLICATION->IncludeFile('/includes/refferer.php');?>
</head>
<body><!--script(type='text/javascript', src='/js/vendor/plyr.min.js')-->
<?//include_once ($_SERVER['DOCUMENT_ROOT'].'/includes/counters/ga_init.php');?>
<div class="wrapper <?if(CONTENT_STYLE!=''):?><?=CONTENT_STYLE?><?endif;?>">
    <?
    if($USER->IsAdmin()) $APPLICATION->ShowPanel();?>
    <div class="content">
        <div class="header">
            <div class="header__wrap">
            <div class="header__content inner">
                <div class="header__left">
                    <div class="header__phone">
                        <div class="header__phone-content icon-3x_phone"><a class="header__phone-link">8 (495) 320-20-20</a>
                            <div class="header__callback btn is-white js-do" data-action="form_callback"><span>Обратный звонок</span></div>
                        </div>
                    </div>
                </div>
                <div class="header__center">
                    <div class="header__logo">
                        <?$alt = 'Интернет-магазин ХочуКофе – кофе высокого качества с доставкой по Москве и всей России'?>
                        <?if($APPLICATION->GetCurPage()!="/"):?>
                            <a href="/" title="Перейти на главную страницу сайта"><img src="<?=SITE_TEMPLATE_PATH?>/static/dist/images/svg/logo.svg" alt="<?=$alt?>"></a>
                        <?else:?>
                            <a href="/"><img src="<?=SITE_TEMPLATE_PATH?>/static/dist/images/svg/logo.svg" alt="<?=$alt?>"></a>
                        <?endif?>
                    </div>
                </div>
                <div class="header__right">
                    <div class="pers-info">
                    <?$APPLICATION->IncludeComponent(
                        "mango:cache.set",
                        "smallbasket",
                        array(
                            "AJAX_MODE" => "Y",
                            "AJAX_OPTION_SHADOW" => "N",
                            "AJAX_OPTION_JUMP" => "N",
                            "AJAX_OPTION_STYLE" => "Y",
                            "AJAX_OPTION_HISTORY" => "N",
                            'BASKET_LINK' => '/personal/basket/',
                            'PATH_TO_DELAY'=> '/personal/delay/',
                            'PATH_TO_COMPARE'=> '/personal/compare/',
                            'EVENTS' => [],
                        ),
                        false
                    );?>
                    </div>
                </div>
            </div>
                <?$APPLICATION->IncludeComponent("bitrix:menu","top",
                    Array(
                        "ROOT_MENU_TYPE" => "top",
                        "MAX_LEVEL" => "3",
                        "CHILD_MENU_TYPE" => "top-blog-podmenu",
                        "USE_EXT" => "Y",
                        "DELAY" => "N",
                        "ALLOW_MULTI_SELECT" => "Y",
                        "MENU_CACHE_TYPE" => "N",
                        "MENU_CACHE_TIME" => "3600",
                        "MENU_CACHE_USE_GROUPS" => "Y",
                        "MENU_CACHE_GET_VARS" => ""
                    )
                );?>
            </div>
        </div>
        <?/*$APPLICATION->IncludeComponent("bitrix:menu","sections",
            Array(
                "ROOT_MENU_TYPE" => "left",
                "MAX_LEVEL" => "3",
                "CHILD_MENU_TYPE" => "",
                "USE_EXT" => "Y",
                "DELAY" => "N",
                "ALLOW_MULTI_SELECT" => "Y",
                "MENU_CACHE_TYPE" => "N",
                "MENU_CACHE_TIME" => "3600",
                "MENU_CACHE_USE_GROUPS" => "Y",
                "MENU_CACHE_GET_VARS" => ""
            )
        );*/?>
        <div class="page">
            <div class="inner">


