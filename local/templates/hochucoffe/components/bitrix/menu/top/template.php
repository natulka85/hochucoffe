<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
$i = -1;
foreach ($arResult as $key => &$item) {
    if ($item['DEPTH_LEVEL'] == 1) {
        $i++;
        $arNewItems[$i] = $item;
    } elseif ($item['DEPTH_LEVEL'] == 2) {
        if($item['SELECTED']==1){
            $arNewItems[$i]['SELECTED'] = true;
        }
        $arNewItems[$i]['CHILDREN'][] = $item;
    }
}
$arResult['ITEMS'] = $arNewItems;
unset($arNewItems);
?>
<?if (!empty($arResult['ITEMS'])):?>
    <div class="main-menu">
        <div class="main-menu__wrapp inner">
            <div class="main-menu__burger js-link is-menu burger--htx"><span></span></div>
            <div class="main-menu__list inner">
                <div class="mob__mob-control">
                    <div class="mob__btn-back icon-2m_arrow-l js-link is-menu"></div>
                </div>
                <?foreach ($arResult['ITEMS'] as $item):?>
                    <div class="main-menu__item-wrap <?=$item['PARAMS']['HTML_CLASS']?> <?if($item['SELECTED']):?> is-active<?endif;?>">
                        <div class="main-menu__item <?if($item['TEXT'] =='Каталог'):?>icon-2z_bar<?endif;?>">
                            <?/*if($item['TEXT'] =='Каталог'):*/?><!--
                                <div class="main-menu__burger">
                                    <div class="main-menu__burger-line"></div>
                                    <div class="main-menu__burger-line"></div>
                                    <div class="main-menu__burger-line"></div>
                                </div>
                            --><?/*endif;*/?>
                            <a href="<?=$item['LINK']?>"><?=$item['TEXT']?></a>
                            <?if(count($item['CHILDREN'])>0):?>
                                <div class="main-menu__item-btn"></div>
                            <?endif;?>
                        </div>
                        <?if(count($item['CHILDREN'])>0):?>
                            <div class="main-menu__submenu">
                                <div class="main-menu__submenu-cont">
                                    <?foreach ($item['CHILDREN'] as $child):?>
                                        <div class="main-menu__item"><a href="<?=$child['LINK']?>"><?=$child['TEXT']?></a></div>
                                    <?endforeach;?>
                                </div>
                            </div>
                        <?endif;?>
                    </div>
                <?endforeach;?>
            </div>
            <div class="header__logo">
                <?$alt = 'Интернет-магазин ХочуКофе – кофе высокого качества с доставкой по Москве и всей России'?>
                <?if($APPLICATION->GetCurPage()!="/"):?>
                    <a href="/" title="Перейти на главную страницу сайта"><img src="<?=SITE_TEMPLATE_PATH?>/static/dist/images/svg/logo.svg" alt="<?=$alt?>"></a>
                <?else:?>
                    <a href="/"><img src="<?=SITE_TEMPLATE_PATH?>/static/dist/images/svg/logo.svg" alt="<?=$alt?>"></a>
                <?endif?>
            </div>
            <div class="search">
                <div class="search__btn icon-1i_search"></div>
                <span class="search__form-section" data-section_id=""></span>
                <form class="search__form" action="/search/">
                    <div class="search__fields"><input class="search__input" name="q"
                                                       placeholder="Я ищу свой любимый кофе" autocomplete="off">
                        <button class="search__btn-funct">Найти</button>
                    </div>
                </form>
                <div class="search-hint-ajax"></div>
            </div>
        </div>
    </div>
<?endif?>
