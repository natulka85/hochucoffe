<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
$i = -1;
foreach ($arResult as $key => $item) {
    if ($item['DEPTH_LEVEL'] == 1) {
        $i++;
        $arNewItems[$i] = $item;
    } elseif ($item['DEPTH_LEVEL'] == 2) {
        $arNewItems[$i]['CHILDREN'][] = $item;
    }
}
$arResult['ITEMS'] = $arNewItems;
unset($arNewItems)
?>
<?if (!empty($arResult['ITEMS'])):?>
    <div class="main-menu">
        <div class="main-menu__burger">
            <div class="main-menu__burger-line"></div>
            <div class="main-menu__burger-line"></div>
            <div class="main-menu__burger-line"></div>
        </div>
        <div class="main-menu__list">
            <?foreach ($arResult['ITEMS'] as $item):?>
                <div class="main-menu__item <?=$arParams['HTML_CLASS']?>">
                    <a href="<?=$item['LINK']?>"><?=$item['TEXT']?></a>
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
    </div>
<?endif?>
