<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);
global $BP_TEMPLATE;
?>
<?if($arResult['DETAIL_TEXT'] != ''):?>
<div class="article-el">
    <div class="article-el__text">
        <?=$arResult['DETAIL_TEXT']?>
    </div>
</div>
<?if(!empty($arResult['MOD_GOODS'])):?>
<div class="article-el__goods">
    <div class="article-el__title">Подходящие товаров</div>
<?
global $filterGoods;
    $filterGoods['ID'] = $arResult['MOD_GOODS']['ID'];
    $arSettings = [
        "IBLOCK_TYPE" => $BP_TEMPLATE->getConstants()->IBLOCK_MAIN_TYPE,	// Тип инфоблока,
        "IBLOCK_ID" => $BP_TEMPLATE->getConstants()->IBLOCK_MAIN_ID,
        "COUNT_ON_PAGE" => '3',
        "CACHE_TIME"  =>  '3600',

        "SECTION_ID" => '',
        "FILTER_NAME" => 'filterGoods',
        "SORT_FIELD" => '',
        "SORT_ORDER" => '',

        "DISPLAY_TOP_PAGER" => "N",
        "DISPLAY_BOTTOM_PAGER" => "N",
        "PAGER_TEMPLATE" => '',
        "LINK_TO_SECTION" => 'N',
        //'COUNT_LIST' => $BP_TEMPLATE->Catalog()->arCount,
        //'SORT_LIST' => $BP_TEMPLATE->Catalog()->getSortList($catalog_IB,$catalogType),
        //'SORT_CODE' => "asc,nulls",
        //'CATEGORY_TYPE' => $catalogType,
        'MOD_ARTICLES_DETAIL_URL'=>$arResult['MOD_GOODS']['LINK'],
        'ARTICLESPAGE'=>'Y',
    ];

    $APPLICATION->IncludeComponent(
        "bp:element.list",
        "",
        $arSettings,
        false
    );
    ?>
</div>
<?endif;?>
<?endif;?>
<?include($_SERVER["DOCUMENT_ROOT"].$this->GetFolder().'/schema_org.php');?>
