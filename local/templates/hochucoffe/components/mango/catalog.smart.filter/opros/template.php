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

<section class="form is-opened">
    <div class="form__head">
        <div class="form__title page-block-head"><h2 class="page-title _type-2 icon-1h_galka">Быстро найти
                любимый кофе</h2></div>
        <div class="form__note">Ответьте на несколько вопросов о том, какой кофе Вы ищете и мы покажем
            страницу, где оно представлено
        </div>
    </div>
    <div class="form__content">
        <div class="form__content-wrap"></div>
        <form class="index-form" name="smartfilter" id="smartfilter" action="<?echo $BP_TEMPLATE->ChpuFilter()->convertOldToNew($arResult["FORM_ACTION"])?>" method="get" >
            <div class="index-form__fields">
                <div class="index-form__quest">Какой сорт кофе предпочитаете?</div>
                <div class="index-form__field-group">
                    <?
                    $checked = 'checked';
                    foreach($arResult["ITEMS"]['SECTION']['VALUES'] as $sect_id =>$sect):?>
                    <?if($sect['CHECKED']=='Y') $checked=''?>
                        <div class="index-form__field">
                            <label class="index-form__label">
                                <input class="index-form__radio main-checkbox__checkbox" type="radio" name="section_id" data-section_code="<?=$sect['CODE']?>" value="<?=$sect_id?>" <?if($sect['CHECKED']=='Y'):?> checked <?endif;?>>
                                <span class="main-checkbox__span is-radio index-form__span"><?=$sect['VALUE']?></span>
                            </label>
                        </div>
                    <?endforeach;?>
                    <div class="index-form__field">
                        <label class="index-form__label">
                            <input class="index-form__radio main-checkbox__checkbox" type="radio" name="section_id" value="<?=$BP_TEMPLATE->getConstants()->IBLOCK_MAIN_TYPE?>" <?=$checked?>>
                            <span class="main-checkbox__span is-radio index-form__span">Все люблю</span>
                        </label>
                    </div>
                </div>
                <?foreach($arResult["arConstruct"] as $key=>$struct):?>
                    <div class="index-form__quest"><?=$struct?></div>
                    <div class="index-form__field-group">
                        <?item_tmpl('O', $arResult["ITEMS"][$key],$arResult["ITEMS"][$key]['Q_OPENED']);?>
                    </div>
                <?endforeach;?>
                <?//избавляемся от filter/clear/ в коде html
                if(strpos($arResult['FILTER_URL'],'filter/clear/')!==false)
                {
                    $arResult['FILTER_URL'] = str_replace('filter/clear/', '', $arResult['FILTER_URL']);
                }
                ?>
                <div class="" id="modef">
                    <span id="modef_num" class="value" style="display: none"></span>
                    <div class="index-form__btn-wrap">
                        <a href="<?echo $BP_TEMPLATE->ChpuFilter()->convertOldToNew($arResult["FILTER_URL"])?>" class="index-form__btn btn is-white" id="set_filter" target="_blank" disable="disabled">Посмотреть подборку</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>

<script>
    var smartFilter = new JCSmartFilter('<?echo CUtil::JSEscape($arResult["FORM_ACTION"])?>', '<?=CUtil::JSEscape($arParams["FILTER_VIEW_MODE"])?>', <?=CUtil::PhpToJSObject($arResult["JS_FILTER_PARAMS"])?>);
</script>
<?global $GLOBAL_CUSTOM;
$GLOBAL_CUSTOM['FILTER_URL'] = $arResult["FILTER_URL"];?>
