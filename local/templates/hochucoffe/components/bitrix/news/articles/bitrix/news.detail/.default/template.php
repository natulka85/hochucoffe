<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);
global $BP_TEMPLATE,$APPLICATION;
?>
<?
if($arResult['DETAIL_TEXT']!=''):?>
    <article class="articles-detail">
        <div class="page-block-head">
            <h1 class="page-title _type-1"><?=$arResult['NAME']?></h1>
            <div class="page-title-note"><?=$arResult['DATE_ACTIVE_FROM']?></div>
        </div>
        <div class="articles-detail__pic">
            <img src="<?=$arResult['DETAIL_PICTURE']['SRC']?>" alt="<?=$arResult['NAME']?>">
        </div>
        <?if($arResult['SPOSOB']!=''):?>
            <div class="pomol">
                <div class="pomol__props">
                    <?foreach($arResult['PROPS_TEMP'] as $k => $v):?>
                        <?if($arResult['SPOSOB']['PROPERTIES'][$k]['VALUE'][0]!='' || $arResult['SPOSOB']['PROPERTIES'][$k]['VALUE']!=''):?>
                            <div class="pomol__pitem">
                                <div class="pomol__ptitle"><?=$arResult['SPOSOB']['PROPERTIES'][$k]['NAME']?></div>
                                <div class="pomol__picon <?=$v?>"></div>
                                <?if(is_array($arResult['SPOSOB']['PROPERTIES'][$k]['VALUE'])){
                                    $propValue = $arResult['SPOSOB']['PROPERTIES'][$k]['VALUE'][0];
                                    $propNote = $arResult['SPOSOB']['PROPERTIES'][$k]['VALUE'][1];
                                }
                                else{
                                    $propValue = $arResult['SPOSOB']['PROPERTIES'][$k]['VALUE'];
                                }?>
                                <div class="pomol__pvalue"><?=$propValue?></div>
                            </div>
                        <?endif;?>
                    <?endforeach?>
                </div>
            </div>
        <?endif;?>
        <div class="articles-detail__text">
            <?=$arResult['DETAIL_TEXT']?>
        </div>
        <?if($arResult['PROPERTIES']['PRODS_BIND']['VALUE']!=''):?>
        <div class="articles-detail__prods sw-global-wr">
            <?
            global $arrFilterProds;
            $arrFilterProds['ID'] =  $arResult['PROPERTIES']['PRODS_BIND']['VALUE'];?>
            <div class="articles-detail__sw-cont swiper-container">
            <?
            if(count($arrFilterProds['ID']) > 0){
                $APPLICATION->IncludeComponent(
                    "mango:element.list",
                    "",
                    [
                        "IBLOCK_TYPE" => $BP_TEMPLATE->getConstants()->IBLOCK_MAIN_TYPE,
                        "IBLOCK_ID" => array(1),
                        "COUNT_ON_PAGE" => 20,
                        "CACHE_TIME"  =>  3600,

                        //"" => 5,
                        "FILTER_NAME" => "arrFilterProds",
                        "SORT_FIELD" => "",
                        "SORT_ORDER" => "",

                        "DISPLAY_TOP_PAGER" => "N",
                        "DISPLAY_BOTTOM_PAGER" => "N",
                        "PAGER_TEMPLATE" => "",
                        'PAGE_TYPE' => 'VIEWED',
                        'MOD_ITEM_CLASS' => 'swiper-slide',
                        'MOD_LIST_CLASS' => 'swiper-wrapper',
                        'MOD_TITTLE' => 'Подходящий кофе',
                        'MOD_BTN_MORE'=>$arResult['SPOSOB']['PROPERTIES']['URL']['VALUE']
                    ],
                    false
                );
            }
            ?>
                <div class="swiper-pagination swiper__bullet"></div>
            </div>
            <div class="swiper__btn swiper-button-prev is-top"></div>
            <div class="swiper__btn swiper-button-next is-top"></div>
        </div>
        <?endif;?>
    </article>
<?endif;?>
<?include($_SERVER["DOCUMENT_ROOT"].$this->GetFolder().'/schema_org.php');?>
