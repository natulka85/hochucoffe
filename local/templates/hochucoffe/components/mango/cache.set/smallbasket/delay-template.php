<div class="basket-cart">
    <div class="basket-cart__title">Отложенные товары</div>
    <?if($delay>0):?>
        <ul class="cart-list">
            <?
            if(is_array($arData['delay'])):
                $sum = 0;
                foreach($arData['delay'] as $prod_id=>$arBasket):
                    //image
                    $pic_width = $pic_height = 52;
                    $arFile = CFile::ResizeImageGet(
                        $arData['products'][$prod_id]['PREVIEW_PICTURE_ID'],
                        array("width" => $pic_width, "height" => $pic_height),
                        BX_RESIZE_IMAGE_PROPORTIONAL_ALT ,
                        true
                    );
                    $name = $arData['products'][$prod_id]['NAME'];
                    if(strlen($name) > 40){
                        $shotName = substr($name, 0, 40);
                        $arData['products'][$prod_id]['NAME'] = substr($shotName, 0, strrpos($shotName, ' ')).'...';
                    }
                    ?>
                    <li>
                        <a href="<?=$arData['products'][$prod_id]['DETAIL_PAGE_URL']?>" class="img">
                            <img src="<?=$arFile['src']?>" alt=""></a>
                        <div class="holder">
                            <a href="<?=$arData['products'][$prod_id]['DETAIL_PAGE_URL']?>"><?=$arData['products'][$prod_id]['NAME']?></a>
                            <span class="basket-cart__btn-close bt-close js-do" data-action="delay_change" data-id="<?=$arData['products'][$prod_id]['ID']?>" data-state="Y" <?=$arParams['~EVENTS']['button_del_otlog']?>></span>
                        </div>
                    </li>
                <?endforeach?>
            <?endif?>
        </ul>
        <div class="basket-cart__control">
            <a href="<?=$arParams["PATH_TO_DELAY"]?>" class="basket-cart__btn btn-buy is-green" <?=$arParams['EVENTS']['button_pereity_otlog']?>>Перейти к отложенным</a>
        </div>
    <?else:?>
        <div class="basket-cart__text">Отложите понравившиеся товары</div>
    <?endif;?>
</div>
