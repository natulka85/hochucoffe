<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);
global $BP_TEMPLATE;
?>
<div class="page-block-head"><h1 class="page-title _type-1"><?=$arResult['NAME']?></h1></div>
<?if($arResult['DETAIL_TEXT'] != ''):?>
<div class="akcii-el">
    <div class="akcii-el__pic">
        <img src="<?=$arResult['DETAIL_PICTURE']['SRC']?>" alt="<?=$arResult['NAME']?>">
    </div>
    <div class="akcii-el__text">
        <?=$arResult['DETAIL_TEXT']?>
    </div>
</div>
<?endif;?>
<?include($_SERVER["DOCUMENT_ROOT"].$this->GetFolder().'/schema_org.php');?>
