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
global $BP_TEMPLATE;
$arResult['LABLES_TEMPLATE'] = [
    'LEFT' => ['HIT','NEW'],
    'RIGHT' => ['STRANA','OBJARK'],
    'CENTER' => ['ACTION'],
];
?>
<div class="search-hint__wrap">
    <div class="search-hint__btn-close"></div>
<?if(!empty($arResult['ITEMS']) || !empty($arResult['SECTIONS'])):?>
        <div class="search-hint">
            <div class="search-hint__side is-category">
                <?if(!empty($arResult['SECTIONS'])):?>
                    <div class="search-hint__title">Категории:</div>
                    <ul class="search-hint__list">
                        <?foreach ($arResult['SECTIONS'] as $section):?>
                            <li class="search-hint__l-item"><a href="<?=$section['LINK']?>" class=""><?=$section['NAME']?></a></li>
                        <?endforeach;?>
                    </ul>
                <?endif;?>
            </div>
            <?if(!empty($arResult['ITEMS'])):?>
                <div class="search-hint__side is-goods">
                    <div class="search-hint__title"><?if($arResult['TITLE']!=''):?><?=$arResult['TITLE']?><?else:?>Кофе:<?endif;?></div>
                    <div class="search-hint__prods">
                        <?foreach ($arResult['ITEMS'] as $item):?>
                            <a class="search-hint__prods-item" href="<?=$item['URL']?>">
                                    <span class="search-hint__prods-img">
                                        <img src="<?=$item['PREVIEW_PICTURE']?>" alt="<?=$item['TITLE']?>">
                                       <?$arTempLables = $arResult['LABLES_TEMPLATE'];?>
                                        <?foreach ($arTempLables as $pos=>$arVal){
                                            $cnt=0;
                                            foreach ($arVal as $k=>$v){
                                                if(isset($item['LABLES'][$v])){
                                                    $cnt++;
                                                }
                                            }
                                            if($cnt==0){
                                                unset($arTempLables[$pos]);
                                            };
                                        }?>
                                        <?if(count($arTempLables)>0):?>
                                            <div class="catg__labels labels">
                                                <?foreach ($arTempLables as $pos=>$arVal):?>
                                                    <div class="labels__items is-<?=mb_strtolower($pos)?>">
                                                        <?foreach ($arVal as $lKey):?>
                                                            <?if(isset($item['LABLES'][$lKey])):?>
                                                                <div class="labels__item <?=$item['LABLES'][$lKey]['CLASS']?>"><?=$item['LABLES'][$lKey]['TEXT']?></div>
                                                            <?endif;?>
                                                        <?endforeach;?>
                                                    </div>
                                                <?endforeach;?>
                                            </div>
                                        <?endif;?>
                                    </span>
                                    <span class="search-hint__prods-name"><?=$item['TITLE']?></span>
                                    <div class="search-hint__buy-info">
                                    <?if($item['STATE']['NAME']=='STATE_INSTOCK'):?>
                                        <span class="search-hint__price">
                                            <?if($item['STATE']['PRICE']>0):?>
                                                <strong><?=SaleFormatCurrency($item['STATE']['PRICE'],'RUB')?></strong>
                                            <?endif;?>
                                            <s><?=SaleFormatCurrency($item['STATE']['PRICE_OLD'],'RUB')?></s>
                                        </span>
                                    <?endif;?>
                                    </div>
                            </a>
                        <?endforeach;?>
                    </div>
                </div>
            <?endif;?>
        </div>
<?endif;?>
    <?if(!empty($arResult['BUTTON'])):?>
        <div class="search-hint__main-btn">
            <a href="<?=$arResult['BUTTON']['LINK']?>" class="btn is-white" onclick="<?=$arResult['EVENTS']['btn_click'][$arResult['CUR_SECT']]?>"><span><?=$arResult['BUTTON']['TITLE']?></span></a>
        </div>
    <?else:?>
        <div class="search-hint__main-btn">
            <a href="<?=$arParams['SEARCH_PAGE']?>?q=<?=$arParams['REQUEST']?>" class="btn is-white"><span>Перейти ко всем результатам поиска</span></a>
        </div>
    <?endif;?>
</div>
