<div class="basket-cart">
    <div class="basket-cart__title">Сравнение товаров</div>
    <?if($compare>0):?>
        <ul class="cart-list">
            <?
            if(is_array($arData['compare'])):
                $sum = 0;
                foreach($arData['compare'] as $prod_id=>$arBasket):
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
                    <?if($arFile['src']!=''):?>
                        <li>
                            <a href="<?=$arData['products'][$prod_id]['DETAIL_PAGE_URL']?>" class="img">
                                <img src="<?=$arFile['src']?>" alt=""></a>
                            <div class="holder">
                                <a href="<?=$arData['products'][$prod_id]['DETAIL_PAGE_URL']?>"><?=$arData['products'][$prod_id]['NAME']?></a>
                                <span class="basket-cart__btn-close bt-close js-do" data-action="compare_change" data-id="<?=$arData['products'][$prod_id]['ID']?>" data-state="Y" <?=$arParams['~EVENTS']['button_del_compare']?>></span>
                            </div>
                        </li>
                    <?endif;?>
                <?endforeach?>
            <?endif?>
        </ul>
        <div class="basket-cart__control">
            <a href="<?=$arParams["PATH_TO_COMPARE"]?>" class="basket-cart__btn btn-buy is-green" <?=$arParams['EVENTS']['button_sravnit_tovari']?>>Cравнить товары</a>
        </div>
    <?else:?>
        <div class="basket-cart__text">Добавьте товары для сравнения</div>
    <?endif;?>
</div>
