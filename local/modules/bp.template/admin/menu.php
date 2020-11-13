<?php

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

$menu = array(
    array(
        'parent_menu' => 'global_menu_content',
        'sort' => 400,
        'text' => Loc::getMessage('BP_TEMPLATE_MENU_TITLE'),
        'title' => Loc::getMessage('BP_TEMPLATE_MENU_TITLE'),
        'icon' => 'util_menu_icon',
        'url' => 'bp_template_index.php?file=index.php',
        'items_id' => 'menu_bp_template',
        'items' => array(
            array(
                'text' => Loc::getMessage('BP_TEMPLATE_CONST_TITLE'),
                'url' => 'bp_template_index.php?file=const.php&lang=' . LANGUAGE_ID,
                'more_url' => array('bp_template_index.php?file=const.php&lang=' . LANGUAGE_ID),
                'title' => Loc::getMessage('BP_TEMPLATE_CONST_TITLE'),
                'icon' => 'update_marketplace',
                'items_id' => 'menu_bp_template_const',
            ),
            array(
                'text' => 'Переменные разделов',
                'url' => 'bp_template_index.php?file=seo_values.php&lang=' . LANGUAGE_ID,
                'more_url' => array('bp_template_index.php?file=seo_values.php&lang=' . LANGUAGE_ID),
                'title' => Loc::getMessage('BP_TEMPLATE_CONST_TITLE'),
                'icon' => 'update_marketplace',
                'items_id' => 'menu_bp_template_const',
            ),
        ),
    ),
);

return $menu;
