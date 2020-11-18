<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die(); ?>
<?/*echo "<pre>";
   print_r($arResult);
echo "</pre>";*/
?>
<?if(count($arResult['ITEMS'])==1):?>
        <?foreach ($arResult['ITEMS'] as $arItem):?>
            <?include ($_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH.'/includes/catalog_card.php');?>
        <?endforeach;?>
<?endif;?>

