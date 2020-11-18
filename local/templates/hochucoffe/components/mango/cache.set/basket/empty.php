<?if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>
<?global $BP_TEMPLATE;?>
<div class="basket__content-wr is-empty">
    <div class="basket__content">
        <div class="basket__left">
            <div class="basket__icon icon-1g_coffecapn"></div>
        </div>
        <div class="basket__right">
            <div class="basket__text _type-4">Ваша корзина пуста :(</div>
            <div class="basket__text _type-5">Вы можете перейти в <a href="#">каталог</a> и легко это
                исправить :)
            </div>
            <div class="basket__text _type-5">Какое утро без кофе? ^^</div>
        </div>
    </div>
    <section class="cloud">
        <div class="cloud__list">
            <div class="cloud__item is-active"><a class="cloud__link" href="#">Камерун<span
                            class="cloud__num">50</span></a></div>
            <div class="cloud__item"><a class="cloud__link" href="#">Сильная обжарка<span
                            class="cloud__num">50</span></a></div>
            <div class="cloud__item"><a class="cloud__link" href="#">Средня обжарка<span
                            class="cloud__num">50</span></a></div>
            <div class="cloud__item"><a class="cloud__link" href="#">Молотый<span
                            class="cloud__num">50</span></a></div>
            <div class="cloud__item"><a class="cloud__link" href="#">до 1000 ₽<span class="cloud__num">50</span></a>
            </div>
            <div class="cloud__item"><a class="cloud__link" href="#">Дорогой кофе<span
                            class="cloud__num">50</span></a></div>
            <div class="cloud__item is-active"><a class="cloud__link" href="#">Камерун<span
                            class="cloud__num">50</span></a></div>
            <div class="cloud__item"><a class="cloud__link" href="#">Сильная обжарка<span
                            class="cloud__num">50</span></a></div>
            <div class="cloud__item"><a class="cloud__link" href="#">Средня обжарка<span
                            class="cloud__num">50</span></a></div>
            <div class="cloud__item"><a class="cloud__link" href="#">Молотый<span
                            class="cloud__num">50</span></a></div>
            <div class="cloud__item"><a class="cloud__link" href="#">до 1000 ₽<span class="cloud__num">50</span></a>
            </div>
            <div class="cloud__item"><a class="cloud__link" href="#">Дорогой кофе<span
                            class="cloud__num">50</span></a></div>
        </div>
    </section>

    <?
    include_once ($_SERVER['DOCUMENT_ROOT'].'/includes/viewed_generate.php');
    if(count($GLOBALS['arViewedProducts'])>0):?>
        <section class="your-viewed">
            <div class="page-block-head"><h2 class="page-title _type-2">Вы недавно смотрели</h2><a
                        class="page-title-link" href="/personal/viewed/">Смотреть все</a></div>
            <?
            global  $prodFilter;
            $prodFilter = array(
                "IBLOCK_ID" => $BP_TEMPLATE->getConstants()->IBLOCK_MAIN_ID,
                'ID' => $GLOBALS['arViewedProducts']
            );
            $APPLICATION->IncludeComponent(
                "mango:element.list",
                "",
                array(
                    "IBLOCK_TYPE" => $BP_TEMPLATE->getConstants()->IBLOCK_MAIN_TYPE,
                    "IBLOCK_ID" => $BP_TEMPLATE->getConstants()->IBLOCK_MAIN_ID,
                    "COUNT_ON_PAGE" => 10,
                    "CACHE_TIME"  =>  3600,

                    "SECTION_ID" => "",
                    "FILTER_NAME" => "prodFilter",
                    "SORT_FIELD" => "",
                    "SORT_ORDER" => "asc",

                    "DISPLAY_TOP_PAGER" => "N",
                    "DISPLAY_BOTTOM_PAGER" => "N",
                    "PAGER_TEMPLATE" => "",
                ),
                false
            );?>
        </section>
    <?endif;?>
</div>
