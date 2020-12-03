<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);
global $BP_TEMPLATE;?>
<?if(!empty($arResult['ITEMS'])):?>
<div class="sort-wrapper page-top-controls">
    <div class="sort__count-wrapper">
        <?if(count($arParams["SORT_LIST"])>0):?>
            <span class="sort__title">Сортировать по</span>
            <form action="#" class="sort__form">
                <fieldset class="sort__fieldset">
                    <select name="" id="" class="select js-sort">
                        <?foreach($arParams["SORT_LIST"] as $sort_code=>$list):?>
                            <option <?=($sort_code==$arParams["SORT_CODE"])?' selected':'';?> value="<?=$sort_code?>"><?=$list['name']?></option>
                        <?endforeach;?>
                    </select>
                </fieldset>
            </form>
        <?endif?>
    </div>
</div>
    <div class="third-news-in-line">
        <?foreach ($arResult['ITEMS'] as $item):?>
        <?$i++;?>
        <?include(__DIR__.'/news-card-element.php')?>
            <?if($i == 3):?>
                <?if(!empty($arResult['MOD_TAGS'])):?>
                    <div class="list-block is-article">
                        <div class="list-block__title icon1-3n_newspapper">У нас вы найдете статьи по следующим темам</div>
                        <div class="list-block__menu">
                        <?foreach ($arResult['MOD_TAGS'] as $tag):?>
                            <div class="list-block__menu-el <?=$BP_TEMPLATE->sectionIconClass($tag['EXTERNAL_ID']);?>"><a href="<?=$tag['LINK']?>"><?=$tag['NAME']?></a></div>
                        <?endforeach;?>
                        </div>
                    </div>
                <?endif;?>

            <?endif;?>
        <?endforeach;?>
    </div>
<?endif;?>
<?if($arParams["DISPLAY_BOTTOM_PAGER"]):?>
    <br /><?=$arResult["NAV_STRING"]?>
<?endif;?>
