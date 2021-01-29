<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);
global $BP_TEMPLATE;?>
<div class="articles">
<?if(!empty($arResult['ITEMS'])):?>
<?
    if($arParams['DISPLAY_TOP_PAGER']!='N' && $arParams['~DISPLAY_TOP_PAGER']!='N'):?>
    <div class="catg__list-control">
        <?if($arParams['SORT_LIST']!=''):?>
        <div class="catg__list-control-bl is-sort">
            <div class="catg__list-control-name">Сортировать по:</div>
            <?foreach ($arParams['SORT_LIST'] as $sort_code=>$list):?>
                    <div class="catg__list-control-value js-ctg-sort <?=$list['class']?>" data-value="<?=$sort_code?>"><?=$list['name']?></div>
            <?endforeach;?>
        </div>
        <?endif;?>
    </div>
<?endif;?>
    <div class="articles__list">
        <?foreach ($arResult['ITEMS'] as $item):?>
            <div class="articles__item">
                <div class="articles__img">
                    <img src="<?=$item['PREVIEW_PICTURE']['SRC']?>" alt="">
                </div>
                <a href="<?=$item['DETAIL_PAGE_URL']?>" class="articles__back">
                    <div class="articles__date"><?=$item['FORMATE_DATE_CREATE']?></div>
                    <div class="articles__title"><?=$item['NAME']?></div>
                    <div class="articles__short"><?=$item['PREVIEW_TEXT_SHORT']?></div>
                    <span class="articles__item-f">
                        <div class="articles__btn btn is-transp-white">Подробнее</div>
                        <?if($item['SHOW_COUNTER']>0):?> <div class="articles__show icon-3b_eye"><span><?=$item['SHOW_COUNTER']?></span></div><?endif;?>
                    </span>
                </a>
            </div>
        <?endforeach;?>
    </div>
<?endif;?>
<?if($arParams["DISPLAY_BOTTOM_PAGER"]):?>
    <br /><?=$arResult["NAV_STRING"]?>
<?endif;?>
</div>
