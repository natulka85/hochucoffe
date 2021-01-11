<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);
global $BP_TEMPLATE;?>
<div class="articles">
<?if(!empty($arResult['ITEMS'])):?>
    <div class="catg__list-control-bl is-sort">
        <div class="catg__list-control-name">Сортировать по:</div>
        <?foreach ($arParams['SORT_LIST'] as $sort_code=>$list):?>
            <?if(strpos($sort_code))?>
                <div class="catg__list-control-value js-ctg-sort <?=$list['class']?>" data-value="<?=$sort_code?>"><?=$list['name']?></div>
        <?endforeach;?>
    </div>

    <div class="articles__list">
        <?foreach ($arResult['ITEMS'] as $item):?>
            <div class="articles__item">
                <div class="articles__img">
                    <img src="<?=$item['PREVIEW_PICTURE']['SRC']?>" alt="">
                </div>
                <a href="<?=$item['DETAIL_PAGE_URL']?>" class="articles__back">
                    <div class="articles__date">Подробнее</div>
                    <div class="articles__title"><?=$item['NAME']?></div>
                    <div class="articles__short"><?=$item['PREVIEW_TEXT']?></div>
                    <span class="articles__item-f">

                        <div class="articles__btn">Подробнее</div>
                    </span>
                </a>
            </div>
        <?include(__DIR__.'/news-card-element.php')?>

        <?endforeach;?>
    </div>
<?endif;?>
<?if($arParams["DISPLAY_BOTTOM_PAGER"]):?>
    <br /><?=$arResult["NAV_STRING"]?>
<?endif;?>
</div>
