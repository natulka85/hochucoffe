<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);
?>
<?if(count($arResult['ITEMS'])>0):?>
<section class="cloud">
    <div class="cloud__list">
        <?foreach ($arResult['ITEMS'] as $arItem):?>
            <div class="cloud__item<?if($arItem['ACTIVE']=='Y'):?> is-active<?endif;?>">
                <a class="cloud__link" href="<?=$arItem['LINK']?>"><?=$arItem['NAME']?>
                    <?if($arItem['CNT']!=''):?>
                        <span class="cloud__num">50</span>
                    <?endif;?>
                </a></div>
        <?endforeach;?>
    </div>
</section>
<?endif;?>
