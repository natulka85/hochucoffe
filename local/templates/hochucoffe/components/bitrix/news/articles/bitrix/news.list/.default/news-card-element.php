<div class="highest-new">
    <div class="highest-new-text">
        <a href="<?=$item['DETAIL_PAGE_URL']?>"><div class="title-of-new" <?=$arParams['EVENTS']['klik_po_statye']?>><?=$item['NAME']?></div></a>
        <div class="content-news">
            <a href="<?=$item['DETAIL_PAGE_URL']?>"><?=$item['PREVIEW_TEXT']?></a>
        </div>
    </div>
    <a href="<?=$item['DETAIL_PAGE_URL']?>" class="highest-new-pic-date" <?=$arParams['EVENTS']['klik_po_statye']?>>
        <img src="<?=$item['PREVIEW_PICTURE']['SRC']?>">
    </a>
    <div class="highest-new__footer">
        <span class="date-of-news"><?=$item['FORMATE_DATE_CREATE']?></span>
        <?if($item['SHOW_COUNTER']>0):?>
            <div class="show-counter">
                <div class="news-social__title"><span class="icon1-3o_eye"><?=$item['SHOW_COUNTER']?></span></div>
            </div>
        <?endif;?>
    </div>
</div>
