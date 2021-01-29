<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);
?>
<?if(count($arResult['ITEMS'])>0):?>
    <div class="pomol__sw-cont swiper-container">
        <div class="pomol__list swiper-wrapper">
            <?foreach ($arResult['ITEMS'] as $arItem):?>
            <?if($arItem['ACTIVE_PAGE']=='Y'){
                $activeEl = $arItem;
                }?>
                <div class="pomol__item-wrap swiper-slide">
                    <?if($arItem['ACTIVE_PAGE']=='Y'):?>
                        <div class="pomol__item is-<?=$arItem['CODE']?> is-active">
                    <?else:?>
                    <a class="pomol__item is-<?=$arItem['CODE']?>" href="<?=$arItem['PROPERTIES']['URL']['VALUE']?>" <?if($arParams['TARGET']!=''):?>target="<?=$arParams['TARGET']?>"<?endif;?>>
                    <?endif;?>
                        <div class="pomol__img">
                            <img src="<?=$arItem['PROPERTIES']['PIC_PENCIL']['VALUE']['SRC']?>" alt="<?=$arItem['NAME']?> для заваривания кофе">
                            <img src="<?=$arItem['PROPERTIES']['PIC_PEN']['VALUE']['SRC']?>" alt="<?=$arItem['NAME']?>  для заваривания кофе цвет">
                        </div>
                        <div class="pomol__name"><?=$arItem['NAME']?></div>
                    <?if($arItem['ACTIVE_PAGE']=='Y'):?>
                    </div>
                    <?else:?>
                    </a>
                    <?endif;?>
                </div>
            <?endforeach;?>
        </div>
    </div>
    <?
    if($activeEl['ACTIVE_PAGE']=='Y'):?>
        <div class="pomol__desc is-<?=$activeEl['CODE']?>">
            <?if($_REQUEST['PAGEN_1']<=1):?>
            <div class="pomol__text"><div class="pomol__text-wr"><?=$activeEl['DETAIL_TEXT']?></div>
                <?if($activeEl['PROPERTIES']['ARTICLE_BIND']['DETAIL_PAGE_URL']!=''):?>
                    <div class="pomol__btn is-<?=$activeEl['CODE']?>"><a href="<?=$activeEl['PROPERTIES']['ARTICLE_BIND']['DETAIL_PAGE_URL']?>" target="_blank">Читать статью</a></div>
                <?endif;?>
            </div>
        <?endif;?>
            <div class="pomol__cont">
                <div class="pomol__props">
                    <?foreach($arResult['PROPS_TEMP'] as $k => $v):?>
                        <?if($activeEl['PROPERTIES'][$k]['VALUE'][0]!='' || $activeEl['PROPERTIES'][$k]['VALUE']!=''):?>
                            <div class="pomol__pitem">
                                <div class="pomol__ptitle"><?=$activeEl['PROPERTIES'][$k]['NAME']?></div>
                                <div class="pomol__picon <?=$v?>"></div>
                                <?if(is_array($activeEl['PROPERTIES'][$k]['VALUE'])){
                                    $propValue = $activeEl['PROPERTIES'][$k]['VALUE'][0];
                                    $propNote = $activeEl['PROPERTIES'][$k]['VALUE'][1];
                                }
                                else{
                                    $propValue = $activeEl['PROPERTIES'][$k]['VALUE'];
                                }?>
                                <div class="pomol__pvalue"><?=$propValue?></div>
                            </div>
                        <?endif;?>
                    <?endforeach?>
                </div>
                <?if($activeEl['PROPERTIES']['IMPORTANT']['VALUE']['TEXT']!=''):?>
                    <div class="pomol__imp">
                        <div class="main-text__border">
                            <div class="main-text__border-name">ВНИМАНИЕ!</div>
                            <?=$activeEl['PROPERTIES']['IMPORTANT']['VALUE']['TEXT']?>
                        </div>
                    </div>
                <?endif;?>
            </div>
        </div>
    <?endif;?>
<?endif;?>
