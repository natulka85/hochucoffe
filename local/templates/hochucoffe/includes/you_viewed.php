<?
$F_USER = CSaleBasket::GetBasketUserID();

$bd_viewed = \Bitrix\Catalog\CatalogViewedProductTable::GetList(
    ['filter'=>[
        'FUSER_ID' => $F_USER
    ]
    ]);

while($ob_view = $bd_viewed->Fetch()){
    $arElements[] = $ob_view['ELEMENT_ID'];
}
global $arrFilterViewed;
$arrFilterViewed['ID'] =  $arElements;

if(count($arrFilterViewed['ID']) > 0){?>
<section class="your-viewed is-relative sw-global-wr">
    <div class="page-block-head is-center"><h2 class="page-title _type-2">Вы недавно смотрели</h2>
        <a class="page-title-link" href="/personal/viewed/" target="_blank">Смотреть все</a>
    </div>
    <div class="your-viewed__sw-cont swiper-container">
        <?}
        $APPLICATION->IncludeComponent(
            "mango:element.list",
            "",
            [
                "IBLOCK_TYPE" => $BP_TEMPLATE->getConstants()->IBLOCK_MAIN_TYPE,
                "IBLOCK_ID" => array(1),
                "COUNT_ON_PAGE" => 150,
                "CACHE_TIME"  =>  3600,

                "" => 5,
                "FILTER_NAME" => "arrFilterViewed",
                "SORT_FIELD" => "",
                "SORT_ORDER" => "",

                "DISPLAY_TOP_PAGER" => "N",
                "DISPLAY_BOTTOM_PAGER" => "N",
                "PAGER_TEMPLATE" => "",
                'PAGE_TYPE' => 'VIEWED',
                'MOD_ITEM_CLASS' => 'swiper-slide',
                'MOD_LIST_CLASS' => 'swiper-wrapper'
            ],
            false
        );
        ?>
        <div class="swiper-pagination swiper__bullet"></div>
    </div>
    <div class="swiper__btn swiper-button-prev is-top"></div>
    <div class="swiper__btn swiper-button-next is-top"></div>
</section>
