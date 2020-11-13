<?global $USER;?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <?CJSCore::Init(array('mainjs'));?>
    <link href="<?=SITE_TEMPLATE_PATH?>/static/css/global.css" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?$APPLICATION->ShowTitle();?></title>
    <?$APPLICATION->ShowHead();?>
</head>
<body><!--script(type='text/javascript', src='/js/vendor/plyr.min.js')-->
<div class="wrapper">
    <?
    if($USER->IsAdmin()) $APPLICATION->ShowPanel();?>
    <div class="content">
        <div class="header">
            <div class="header__content inner">
                <div class="header__left">
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

                    <div class="header__block-l">
                        <div class="header__logo">
                            <?$alt = 'Интернет-магазин ХочуКофе – кофе высокого качества с доставкой по Москве и всей России'?>
                            <?if($APPLICATION->GetCurPage()!="/"):?>
                                <a href="/" title="Перейти на главную страницу сайта"><img src="<?=SITE_TEMPLATE_PATH?>/static/images/logo_main.svg" alt="<?=$alt?>"></a>
                            <?else:?>
                                <a href="/"><img src="<?=SITE_TEMPLATE_PATH?>/static/images/logo_main.svg" alt="<?=$alt?>"></a>
                            <?endif?>
                        </div>
                        <div class="header__phone icon-2h_phone">
                            <div class="header__phone-content"><a class="header__phone-link">8 (495) 320-20-20</a>
                                <div class="header__callback"><span>Обратный звонок</span></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="header__right">
                    <div class="pers-info">
                        <div class="pers-info__list">
                            <div class="pers-info__item is-main"><a class="pers-info__link"
                                                                    href="/">
                                    <div class="pers-info__icon icon-2j_house"></div>
                                    <div class="pers-info__text">Главная</div>
                                </a></div>
                            <div class="pers-info__item is-catalog"><a class="pers-info__link" href="/catalog/">
                                    <div class="pers-info__icon icon-2i_katalog"></div>
                                    <div class="pers-info__text">Каталаг</div>
                                </a></div>
                            <div class="pers-info__item"><a class="pers-info__link" href="/personal/compare/">
                                    <div class="pers-info__icon icon-1c_sravnn"></div>
                                    <div class="pers-info__text">В сравнении</div>
                                    <div class="pers-info__num"><span>5</span></div>
                                </a></div>
                            <div class="pers-info__item"><a class="pers-info__link" href="/personal/delay/">
                                    <div class="pers-info__icon icon-1e_heart"></div>
                                    <div class="pers-info__text">Отложенные</div>
                                    <div class="pers-info__num"><span>5</span></div>
                                </a></div>
                            <div class="pers-info__item"><a class="pers-info__link" href="/personal/basket/">
                                    <div class="pers-info__icon icon-1g_coffecapn"></div>
                                    <div class="pers-info__text">Корзина</div>
                                    <div class="pers-info__num"><span>5</span></div>
                                </a></div>
                        </div>
                    </div>
                    <div class="search">
                        <form class="search__form">
                            <div class="search__fields"><input class="search__input" name="q"
                                                               placeholder="Я ищу свой любимый кофе">
                                <button class="search__btn icon-1i_search"></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="page inner">
            <div class="menu-top">
                <div class="menu-top__list">
                    <div class="menu-top__item"><a class="menu-top__link" href="#"><span>Арабика</span></a></div>
                    <div class="menu-top__item"><a class="menu-top__link" href="#"><span>Арабика</span></a></div>
                    <div class="menu-top__item"><a class="menu-top__link" href="#"><span>Арабика</span></a></div>
                    <div class="menu-top__item"><a class="menu-top__link" href="#"><span>Арабика</span></a></div>
                    <div class="menu-top__item"><a class="menu-top__link" href="#"><span>Арабика</span></a></div>
                    <div class="menu-top__item"><a class="menu-top__link" href="#"><span>Арабика</span></a></div>
                </div>
            </div>
