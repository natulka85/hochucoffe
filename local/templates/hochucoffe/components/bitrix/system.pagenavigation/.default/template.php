<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
if(!$arResult["NavShowAlways"])
{
    if ($arResult["NavRecordCount"] == 0 || ($arResult["NavPageCount"] == 1 && $arResult["NavShowAll"] == false))
        return;
}

$strNavQueryString = ($arResult["NavQueryString"] != "" ? $arResult["NavQueryString"]."&amp;" : "");
$strNavQueryStringFull = ($arResult["NavQueryString"] != "" ? "?".$arResult["NavQueryString"] : "");

$arResult["bShowAll"] = false;
?>

<div class="pagination">
    <div class="pagination__list">

        <?if($arResult["bDescPageNumbering"] === true):?>

            <?if ($arResult["NavPageNomer"] < $arResult["NavPageCount"]):?>
                <?if($arResult["bSavePage"]):?>
                    <a class="pagination__item is-back" href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=($arResult["NavPageNomer"]+1)?>">Предыдущая страница</a>
                <?else:?>
                    <?if ($arResult["NavPageCount"] == ($arResult["NavPageNomer"]+1) ):?>
                        <a class="pagination__item is-back" href="<?=$arResult["sUrlPath"]?><?=$strNavQueryStringFull?>">Предыдущая страница</a>
                    <?else:?>
                        <a class="pagination__item is-back" href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=($arResult["NavPageNomer"]+1)?>">Предыдущая страница</a>
                    <?endif?>
                <?endif?>
            <?else:?>
                <span class="pagination__item is-back">Предыдущая страница</span>
            <?endif?>

            <?while($arResult["nStartPage"] >= $arResult["nEndPage"]):?>
                <?$NavRecordGroupPrint = $arResult["NavPageCount"] - $arResult["nStartPage"] + 1;?>

                <?if ($arResult["nStartPage"] == $arResult["NavPageNomer"]):?>
                    <b><?=$NavRecordGroupPrint?></b>
                <?elseif($arResult["nStartPage"] == $arResult["NavPageCount"] && $arResult["bSavePage"] == false):?>
                    <a class="pagination__item" href="<?=$arResult["sUrlPath"]?><?=$strNavQueryStringFull?>"><?=$NavRecordGroupPrint?></a>
                <?else:?>
                    <a class="pagination__item" href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=$arResult["nStartPage"]?>"><?=$NavRecordGroupPrint?></a>
                <?endif?>

                <?$arResult["nStartPage"]--?>
            <?endwhile?>


            <?if ($arResult["NavPageNomer"] > 1):?>
                <a class="pagination__item is-forward" href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=($arResult["NavPageNomer"]-1)?>">Следующая страница</a>

            <?else:?>
                <span class="pagination__item is-forward is-no-active">Следующая страница</span>
            <?endif?>

        <?else:?>

            <?if ($arResult["NavPageNomer"] > 1):?>

                <?if($arResult["bSavePage"]):?>
                    <a class="pagination__item is-back" href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=($arResult["NavPageNomer"]-1)?>">Предыдущая страница</a>
                <?else:?>
                    <?if ($arResult["NavPageNomer"] > 2):?>
                        <a class="pagination__item is-back" href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=($arResult["NavPageNomer"]-1)?>">Предыдущая страница</a>
                    <?else:?>
                        <a class="pagination__item is-back" href="<?=$arResult["sUrlPath"]?><?=$strNavQueryStringFull?>">Предыдущая страница</a>
                    <?endif?>
                <?endif?>

            <?else:?>
                <span class="pagination__item is-back">Предыдущая страница</span>
            <?endif?>

            <?while($arResult["nStartPage"] <= $arResult["nEndPage"]):?>

                <?if ($arResult["nStartPage"] == $arResult["NavPageNomer"]):?>
                    <span class="pagination__item is-active"><?=$arResult["nStartPage"]?></span>
                <?elseif($arResult["nStartPage"] == 1 && $arResult["bSavePage"] == false):?>
                    <a class="pagination__item" href="<?=$arResult["sUrlPath"]?><?=$strNavQueryStringFull?>"><?=$arResult["nStartPage"]?></a>
                <?else:?>
                    <a class="pagination__item" href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=$arResult["nStartPage"]?>"><?=$arResult["nStartPage"]?></a>
                <?endif?>
                <?$arResult["nStartPage"]++?>
            <?endwhile?>

            <?if($arResult["NavPageNomer"] < $arResult["NavPageCount"]):?>
                <a class="pagination__item is-forward" href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=($arResult["NavPageNomer"]+1)?>">Следующая страница</a>&nbsp;
            <?else:?>
                <span class="pagination__item is-forward">Следующая страница</span>
            <?endif?>

        <?endif?>


        <?if ($arResult["bShowAll"]):?>
            <noindex>
                <?if ($arResult["NavShowAll"]):?>
                    |&nbsp;<a href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>SHOWALL_<?=$arResult["NavNum"]?>=0" rel="nofollow"><?=GetMessage("nav_paged")?></a>
                <?else:?>
                    |&nbsp;<a href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>SHOWALL_<?=$arResult["NavNum"]?>=1" rel="nofollow"><?=GetMessage("nav_all")?></a>
                <?endif?>
            </noindex>
        <?endif?>
    </div>
</div>
