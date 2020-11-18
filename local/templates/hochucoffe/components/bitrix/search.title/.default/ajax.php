<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
if(!empty($arResult["CATEGORIES"]) && $arResult['CATEGORIES_ITEMS_EXISTS']):?>
	<div class="title-search-result">
        <?if(count($arResult['CATEGORIES'][2]['ITEMS'])>0):?>
            <div class="search-hints">
                <div class="search-hints__list">
                        <?foreach ($arResult['CATEGORIES'][2]['ITEMS'] as $i=>$item):?>
                            <div class="search-hints__item">
                                <a href="<?=$item['URL']?>" class="search-hints__link">
                                    <div class="search-hints__pic">
                                        <img src="<?=$arResult['SEARCH'][$i]['IMG']?>" alt="" class="search-hints__img"/>
                                    </div>
                                    <span class="search-hints__bottom">
                                    <div class="search-hints__collection"><?=$arResult['SEARCH'][$i]['KOLLEKTSIYA']?></div>
                                    <div class="search-hints__name"><?=$arResult['SEARCH'][$i]['NAME']?></div>
                                    </span>
                                </a>
                            </div>
                            <?$j++;?>
                            <?if($j>3):
                                break;?>
                            <?endif;?>
                        <?endforeach;?>
                </div>
                <div class="search-hints__all"><a href="<?=$arResult["CATEGORIES"]['all']["ITEMS"][0]['URL']?>">Все результаты поиска</a></div>
            </div>
        <?endif;?>
	</div>
<?endif;
?>
