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
global $BP_TEMPLATE;?>

<div class="filter">
    <div class="mob__mob-control">
        <div class="mob__btn-back icon-2m_arrow-l js-link is-filter"></div>
    </div>
    <?if(count($arResult["ACTIVE"])>0):?>
    <div class="filter__info-wr">
        <div class="filter__info<?=$_REQUEST['ax_filter_class']?>">
            <div class="filter__control-block">
                <div class="filter__info-title">Выбранные фильтры</div>
                <div class="filter__info-btn" data-all="Y">Cбросить все</div>
            </div>
            <?foreach ($arResult["ACTIVE"] as $name => $val):?>
                <div class="filter__info-params">
                    <div class="filter__info-params-name"><?=$name?>:</div>
                    <?foreach ($val as $k=>$v):?>
                        <div class="filter__info-params-value" data-id="<?=$k?>"><span><?=$v?></span>
                            <div class="filter__remove icon-1o_krest"></div>
                        </div>
                    <?endforeach;?>
                </div>
            <?endforeach;?>
            <div class="filter__info-close icon-2a_plus"></div>
        </div>
    </div>
    <?endif?>
    <form class="filter__choose" name="smartfilter" id="smartfilter" action="<?echo $BP_TEMPLATE->ChpuFilter()->convertOldToNew($arResult["FORM_ACTION"])?>" method="get" >
        <?foreach($arResult["arConstruct"] as $key=>$struct):?>
                <?if(is_array($struct)):?>
                    <div class="filter__choose-block">
                        <div class="filter__control-block">
                            <div class="filter__info-title"><?=$key?></div>
                            <div class="filter__info-btn">Cбросить</div>
                        </div>
                        <?foreach ($struct as $s=>$v):?>
                                <?if(count($arResult["ITEMS"][$s]["VALUES"])>0):?>
                                    <?item_tmpl($arResult["ITEMS"][$s]["DISPLAY_TYPE"], $arResult["ITEMS"][$s],$arResult["ITEMS"][$s]['Q_OPENED']);?>
                                <?endif;?>
                        <?endforeach;?>
                     </div>
            <?else:?>
                <div class="filter__choose-block <?if($arResult["ITEMS"][$key]['CHECKED']):?> _is-checked<?endif;?>">
                    <?if(count($arResult["ITEMS"][$key]["VALUES"])>0):?>
                    <div class="filter__control-block">
                        <div class="filter__info-title"><?=$struct?></div>
                        <div class="filter__info-btn">Cбросить</div>
                    </div>
                    <?item_tmpl($arResult["ITEMS"][$key]["DISPLAY_TYPE"], $arResult["ITEMS"][$key],$arResult["ITEMS"][$key]['Q_OPENED']);?>
                    <?endif;?>
                </div>
                <?endif;?>


        <?endforeach;?>

        <div class="filter__btn-wrap">
            <button class="filter__btn btn is-white" type="submit" id="set_filter" name="set_filter">Подобрать</button>
        </div>

        <?//избавляемся от filter/clear/ в коде html
        if(strpos($arResult['FILTER_URL'],'filter/clear/')!==false)
        {
        $arResult['FILTER_URL'] = str_replace('filter/clear/', '#', $arResult['FILTER_URL']);
        }
        ?>
        <div class="filter__tip" id="modef" style="display: none">
            Найдено <span id="modef_num" class="value"><?=intval($arResult["ELEMENT_COUNT"])?> шт</span><br>
            <div id="modef_wait" ></div>
            <a href="<?echo $BP_TEMPLATE->ChpuFilter()->convertOldToNew($arResult["FILTER_URL"])?>" class="catalog-tip__show">Показать</a>
        </div>

    </form>
</div>

<script>
    var smartFilter = new JCSmartFilter('<?echo CUtil::JSEscape($arResult["FORM_ACTION"])?>', '<?=CUtil::JSEscape($arParams["FILTER_VIEW_MODE"])?>', <?=CUtil::PhpToJSObject($arResult["JS_FILTER_PARAMS"])?>);
    $(function (){
        initRangeSlider();
        if(window.outerWidth > 640){
            StickyMy($('.filter__choose'), $('.filter__info:not(.noSticky)'),0);
        }
        $('.filter__info-wr').css('height',$('.filter__info').outerHeight());
    })
</script>
