<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);
?>
<?if(count($arResult['ITEMS'])>0):?>
<section class="cloud" data-show_more="block" data-show_more_height="70">
    <div class="cloud__title">Возможно Вас заинтересует:</div>
    <div class="cloud__list" data-show_more="content">
        <?foreach ($arResult['ITEMS'] as $arItem):?>
            <div class="cloud__item<?if($arItem['ACTIVE']=='Y'):?> is-active<?endif;?>">
                <a class="cloud__link" href="<?=$arItem['LINK']?>"><?=$arItem['NAME']?>
                    <?if($arItem['CNT']!=''):?>
                        <span class="cloud__num">50</span>
                    <?endif;?>
                </a></div>
        <?endforeach;?>
    </div>
    <div class="cloud__btn-more btn is-bege">
        <span class="js-show-more-btn" data-show_more_btnshow="Еще" data-show_more_btnhide="Скрыть">Еще</span>
    </div>
</section>
<?endif;?>
