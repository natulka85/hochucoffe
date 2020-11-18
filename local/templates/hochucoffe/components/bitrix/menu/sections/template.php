<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?

$i = -1;
foreach ($arResult as $key => $item) {
    if ($item['DEPTH_LEVEL'] == 2) {
        $i++;
        $arNewItems[$i] = $item;
    } elseif ($item['DEPTH_LEVEL'] == 3) {
        $arNewItems[$i]['CHILDREN'][] = $item;
    }
}
$arResult['ITEMS'] = $arNewItems;
unset($arNewItems);
?>
<?if (!empty($arResult['ITEMS'])):?>
    <div class="menu-top">
        <div class="menu-top__list">
            <?foreach ($arResult['ITEMS'] as $item):?>
                <div class="menu-top__item"><a class="menu-top__link" href="<?=$item['LINK']?>"><span><?=$item['TEXT']?></span></a></div>
            <?endforeach;?>
        </div>
    </div>
<?endif?>
