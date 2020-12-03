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
            array(
                'text' => Loc::getMessage('BP_TEMPLATE_SEO_TITLE'),
                'url' => 'bp_template_index.php?file=seo.php&lang=' . LANGUAGE_ID,
                'more_url' => array(
                    'bp_template_index.php?file=seo_product.php',
                    'bp_template_index.php?file=seo_sections.php',
                ),
                'title' => Loc::getMessage('BP_TEMPLATE_SEO_TITLE'),
                'icon' => 'seo_menu_icon',
                'items_id' => 'menu_bp_template_seo',
                'items' => array(
                    array(
                        'text' => Loc::getMessage('BP_TEMPLATE_SEO_PRODUCT_TITLE'),
                        'url' => 'bp_template_index.php?file=seo_product.php&lang=' . LANGUAGE_ID,
                        'more_url' => array(),
                        'title' => Loc::getMessage('BP_TEMPLATE_SEO_PRODUCT_TITLE'),
                        //'icon' => 'update_marketplace',
                    ),
                    array(
                        'text' => Loc::getMessage('BP_TEMPLATE_SEO_SECTIONS_TITLE'),
                        'url' => 'bp_template_index.php?file=seo_sections.php&lang=' . LANGUAGE_ID,
                        'more_url' => array(),
                        'title' => Loc::getMessage('BP_TEMPLATE_SEO_SECTIONS_TITLE'),
                        //'icon' => 'update_marketplace',
                    ),
                    array(
                        'text' => Loc::getMessage('BP_TEMPLATE_SEO_CANONICAL_TITLE'),
                        'url' => 'bp_template_index.php?file=seo_canonical.php&lang=' . LANGUAGE_ID,
                        'more_url' => array(),
                        'title' => Loc::getMessage('BP_TEMPLATE_SEO_CANONICAL_TITLE'),
                        //'icon' => 'update_marketplace',
                    ),
                    array(
                        'text' => "Правила для разделов",
                        'url' => 'bp_template_index.php?file=seo_nometa.php&lang=' . LANGUAGE_ID,
                        'more_url' => array(),
                        'title' => "Правила для разделов",
                        //'icon' => 'update_marketplace',
                    ),
                    array(
                        'text' => "Правила для страницы поиска",
                        'url' => 'bp_template_index.php?file=seo_search_rules.php&lang=' . LANGUAGE_ID,
                        'more_url' => array(),
                        'title' => "Правила для разделов",
                        //'icon' => 'update_marketplace',
                    ),
                    array(
                        'text' => "robots.txt",
                        'url' => 'bp_template_index.php?file=seo_robots.php&lang=' . LANGUAGE_ID,
                        'more_url' => array(),
                        'title' => "robots.txt",
                        //'icon' => 'update_marketplace',
                    ),
                ),
            ),
            array(
                'text' => 'Отзывы на товары',
                'url' => \Bp\Template\Tools\AdminInterface\ReviewsListHelper::getUrl(),
                'more_url' => [
                    \Bp\Template\Tools\AdminInterface\ReviewsEditHelper::getUrl(),
                ],
                'title' => 'Отзывы на товары',
                'items_id' => 'menu_bp_template_reviewsgoods',
            ),
            array(
                'text' => Loc::getMessage('BP_TEMPLATE_SORT_TITLE'),
                'url' => 'bp_template_index.php?file=sort.php&lang=' . LANGUAGE_ID,
                'more_url' => array('bp_template_index.php?file=sort.php&lang=' . LANGUAGE_ID),
                'title' => Loc::getMessage('BP_TEMPLATE_SORT_TITLE'),
                'icon' => 'sale_menu_icon_crm',
                'items_id' => 'menu_bp_template_sort',
                'items' => array(
                    array(
                        'text' => Loc::getMessage('BP_TEMPLATE_SORT_VAR'),
                        'url' => 'bp_template_index.php?file=sort_var.php&lang=' . LANGUAGE_ID,
                        'more_url' => array(),
                        'title' => Loc::getMessage('BP_TEMPLATE_SORT_VAR'),
                        //'icon' => 'update_marketplace',
                    )
                ),
            ),
        ),
    ),

);

return $menu;
