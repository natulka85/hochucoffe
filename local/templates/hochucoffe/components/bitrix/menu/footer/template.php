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
    <div class="footer-menu">
        <?foreach ($arResult['ITEMS'] as $item):?>
            <div class="footer-menu__b">
                <div class="footer-menu__title">
                    <?if($item['LINK']!=''):?>
                        <a href="<?=$item['LINK']?>"><?=$item['TEXT']?></a>
                    <?else:?>
                        <span><?=$item['TEXT']?></span>
                    <?endif;?>
                </div>
                <?if(count($item['CHILDREN'])>0):?>
                    <div class="footer-menu__btn icon-2a_plus"></div>
                    <div class="footer-menu__sub">
                        <?foreach ($item['CHILDREN'] as $child):?>
                            <div class="footer-menu__s-item"><a href="<?=$child['LINK']?>"><?=$child['TEXT']?></a></div>
                        <?endforeach;?>
                    </div>
                <?endif;?>
            </div>
        <?endforeach;?>
    </div>
<?endif?>
