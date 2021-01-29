<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);
?>
<?if(count($arResult['ITEMS'])>0):?>
<section class="cloud is-nice">
    <div class="page-block-head is-center"><h2 class="page-title _type-2">Возможно Вас заинтересует :</h2></div>
    <div class="cloud__list-wr">
        <div class="cloud__list">
            <?foreach ($arResult['ITEMS'] as $arItem):?>
                <div class="cloud__item-wr">
                <div class="cloud__item<?if($arItem['ACTIVE']=='Y'):?> is-active<?endif;?>">
                    <a class="cloud__link" href="<?=$arItem['LINK']?>" target="_blank">
                        <?if($arItem['IMG']!=''):?>
                            <span class="cloud__img-wrap">
                            <img class="cloud__img" src="<?=$arItem['IMG']?>"/><br>
                        </span>
                        <?endif;?>
                        <span><?=$arItem['NAME']?><?if($arItem['CNT']!=''):?><?endif;?></span>
                        <small class="cloud__num"><?=$arItem['CNT']?> шт</small>
                    </a></div>
                </div>
            <?endforeach;?>
        </div>
    </div>
</section>
<?endif;?>
