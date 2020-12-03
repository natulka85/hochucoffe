<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);
?>
<?if(count($arResult['ITEMS'])>0):?>
<div class="reviews__list">
<?foreach($arResult['ITEMS'] as $arItem):?>
    <div class="reviews__item">
        <div class="reviews__top">
            <div class="reviews__image"><a href="<?=$arItem['ELEMENT']['DETAIL_PAGE_URL']?>" target="_blank"><img src="<?=$arItem['ELEMENT']['PICTURE']['src']?>"/></a></div>
            <div class="reviews__right"><a class="reviews__product" href="<?=$arItem['ELEMENT']['DETAIL_PAGE_URL']?>" target="_blank"><span><?=$arItem['ELEMENT']['NAME']?></span></a>
                <div class="reviews__stat">Отзывов<span> <?=$arItem['ELEMENT']['PROPERTY_ASKARON_REVIEWS_COUNT_VALUE']?></span> шт</div>
                <div class="reviews__stat">Оценка<span> <?=number_format($arItem['ELEMENT']['PROPERTY_ASKARON_REVIEWS_AVERAGE_VALUE'],1)?></span></div>
            </div>
        </div>
        <div class="reviews__bottom">
            <div class="catg__stars">
                <?for($i=1;$i<=5;$i++):?>
                    <div class="catg__star<?if($i<=$arItem['ELEMENT']['PROPERTY_ASKARON_REVIEWS_AVERAGE_VALUE']):?> icon-1b_star_full<?else:?> icon-1a_star<?endif;?>"></div>
                <?endfor;?>
            </div>
            <div class="reviews__name"><?=$arItem['AUTHOR_NAME']?></div>
            <div class="reviews__text"><?=$arItem['TEXT_FORMAT']?>
            </div>
            <div class="reviews__link-wrap"><a class="reviews__link" href="#">Читать весь отзыв</a>
            </div>
        </div>
    </div>
<?endforeach?>
</div>
<?endif;?>

