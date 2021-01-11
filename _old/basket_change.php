<?php
/*
//echo $_REQUEST['rest'].'$_REQUEST["rest"]';
$PRODUCT_ID = 0;

foreach($_SESSION['bp_cache']['bp_user']['basket'] as $prod_id => $arBasket)
{
    if($prod_id == $_REQUEST['id'])
    {
        $basket_id = $arBasket['basket_id'];
        //echo $basket_id;
    }
}
echo $basket_id.'BASKETID';
if($basket_id >0){
    /*if($_REQUEST['childs'])
        $BP_TEMPLATE->Basket()->updateProduct($basket_id, $_REQUEST['q'], $_REQUEST['rest'], explode(',',$_REQUEST['childs']),$arProps,$prod_id);
    else{
        $BP_TEMPLATE->basket()->updateProduct($basket_id, $_REQUEST['q'], $_REQUEST['rest'],[],$arProps,$prod_id);
    }*/

$json['error'] = 'ok';
$json['func'] = '
    $(".sbasket-refresh").click();
    $("#basket-refresh").click();';

}
else{
    \Bitrix\Main\Loader::includeModule('iblock');
    $res = \CIBlockElement::GetByID($_REQUEST["id"]);
    if($ar_res = $res->GetNext())
    {
        $popup_opened = false;
        if($_REQUEST['from'] == 'popup_basket'){
            $popup_opened = true;
        }

        $arId = explode(',',$_REQUEST["id"]);
        $quantity = 1;
        $img = [];

        if($_REQUEST["q"])
            $quantity = $_REQUEST["q"];

        if($_REQUEST["parent"]!='')
        {
            $arProps[] =
                [
                    [
                        "NAME" => "Parent",
                        "CODE" => "PARENT",
                        "VALUE" => intVal($_REQUEST["parent"]) //PRODUCT_ID
                    ]
                ];
        }
        //$arProps[] = ['NAME'=>'Помол','CODE'=>'POMOL', 'VALUE'=>'Мелкий'];

        foreach ($arId as $id){
            echo 'aaaDD';
            $arElement = $BP_TEMPLATE->basket()->addProduct($id, $quantity, $arProps);
        }

        $sum = 0;
        foreach($_SESSION['bp_cache']['bp_user']['basket'] as $prod_id=>$arBasket)
        {
            $sum = $sum + $_SESSION['bp_cache']['bp_user']['products'][$prod_id]['PRICE_1']*$arBasket['quantity'];
        }

        $free_delivary = $_SESSION['bp_cache']['bp_user']['city_data']['PROPERTY_FREE_DELIVERY_SUMM_VALUE'];

        $arWordParts = [
            'Ж' => 'добавлена',
            'М' => 'добавлен',
            'С' => 'добавлено',
        ];
        if(count($arId)>1){
            $w = $_REQUEST['name'];
            //$img = explode(',',$_REQUEST['img']);
            $quant = 1;
        }
        else{
            $w = 'кофе';
            $img[] = $arElement["PREVIEW_PICTURE"];
            $quant = $_SESSION['bp_cache']['bp_user']['basket'][$arId[0]]['quantity'];
        }

        $rod = $BP_TEMPLATE->InsRod($w);
        $part = $arWordParts[$rod];

        $arRecomend = [];
        $arProps = [];
        $res = CIBlockElement::GetList(
            [],
            ['ID' => $_REQUEST['id']],
            false,
            ['nTopCount' => 1],
            [
                'NAME',
                'PROPERTY_RECOMMEND',
                'PROPERTY__RAZDEL_NA_SAYTE',
                'PROPERTY__TIP_TSOKOLYA',
                'PROPERTY__STIL',
                'PROPERTY__VIDY_MATERIALOV',
                'PROPERTY__NALICHIE_DIMMERA',
            ]
        );
        while ($el = $res->Fetch()) {
            $arRecomend = unserialize($el['PROPERTY_RECOMMEND_VALUE'])['VALUE'];
            $arProps = [
                '_RAZDEL_NA_SAYTE'  => ['VALUE' => $el['PROPERTY__RAZDEL_NA_SAYTE_VALUE']],
                '_TIP_TSOKOLYA'  => ['VALUE' => $el['PROPERTY__TIP_TSOKOLYA_VALUE']],
                '_STIL'  => ['VALUE' => $el['PROPERTY__STIL_VALUE']],
                '_VIDY_MATERIALOV'  => ['VALUE' => $el['PROPERTY__VIDY_MATERIALOV_VALUE']],
                '_NALICHIE_DIMMERA'  => ['VALUE' => $el['PROPERTY__NALICHIE_DIMMERA_VALUE']],
            ];
        }


        $arRecomendIDs = [];
        $arRecomend = array_slice($arRecomend, 0, 12);

        if($arRecomend)
        {
            $arRecomend = array_slice($arRecomend, 0, 12);

            $res = CIBlockElement::GetList(
                [],
                ['XML_ID' => $arRecomend],
                false,
                ['nTopCount' => 12],
                ['ID']
            );
            while ($el = $res->Fetch()) {
                $arRecomendIDs[] = $el['ID'];
            }
        }
        global  $prodFilter;
        $prodFilter['ID'] = $arRecomendIDs;
        shuffle($prodFilter['ID']);
        $prodFilter['ID'] = array_slice($prodFilter['ID'], 0, 10);
        ob_start();
        if(count($prodFilter['ID'])>0)
        {
            $APPLICATION->IncludeComponent(
                "mango:cache.set",
                "slider-smallbasket-full",
                array(
                    "IBLOCK_TYPE" => $BP_TEMPLATE->getConstants()->IBLOCK_MAIN_TYPE,
                    "IBLOCK_ID" => $BP_TEMPLATE->getConstants()->IBLOCK_MAIN_ID,
                    "CACHE_TIME"  =>  3600,
                    "FILTER_NAME" => "prodFilter",
                    'MOD_TITLE' => 'С этим товаром покупают',
                    'MOD_LIST_CLASS' => 'js-slick-basket-add',
                    //'CUR_ELEMENT' => $arResult,
                    'FROM' => 'sbasket',
                    'IDS' => $prodFilter['ID'],//cache unique
                    'EVENTS' => [],
                    'DATA_FROM' => 'popup_basket'
                ),
                false
            );
        }
        $out_add = ob_get_contents();
        ob_end_clean();

        if($part=='')
            $part = 'добавлен';

        $out = '';

        if($_REQUEST["window"]!='N')
        {
            if(!$popup_opened){
                $out .= '<div class="popup__box add_to_cart">';
                $out .= '<div class="popup__close icon-2a_plus"></div>';
            }
            $out .= '<div class="popup-ajax-content">';
            $out .= '<div class="popup__content">';
            $out .= '<div class="popup__text _type-1">';
            $out .= '<strong>'.$ar_res['NAME'].'</strong><br>'.$part.' в корзину';
            $out .= '</div>';
            if($img !=''){
                $out .= '<div class="popup__img">';
                foreach ($img as $im){
                    $out .= '<img src="'.$im.'">';
                }
                $out .= '</div>';
            }

            $out .= '<div class="popup__btn-wrap">';
            $out .= '<div class="popup__btn btn is-bege" onclick="window.location.href = \'/personal/basket/\';">Перейти в корзину</div>';
            $out .= '<div class="popup__btn btn is-blue" onclick="closePopup();">Продолжить покупки</div>';
            $out .= '</div>';
            $out .= '</div>';
            $out .= '<hr>';

            $dost_part = '';
            if($free_delivary>0)
            {
                if($sum>$free_delivary)
                {
                    $dost_part .= '<div class="popup__row">';
                    $dost_part .= '<span>В корзине товаров на сумму:</span>';
                    $dost_part .= '<div class="popup_amount">'.\SaleFormatCurrency($sum, 'RUB').'</div>';
                    $dost_part .= '</div>';
                    $dost_part .= '<div class="popup__dost">';

                    if($_SESSION['bp_cache']['bp_user']['city']=='Москва')
                        $dost_part .= '<span>Ваша доставка - бесплатная по Москве!</span>';
                    elseif($_SESSION['bp_cache']['bp_user']['city']=='Санкт-Петербург')
                        $dost_part .= '<span>Ваша доставка - бесплатная по Санкт-Петербургу!</span>';
                    else
                        $dost_part .= '<span>Поздравляем, доставка до пункта выдачи будет бесплатной!</span>';

                    $dost_part .= '<img src="'.SITE_TEMPLATE_PATH.'/static/dist/images/general/delivery-truck.jpg">';
                    $dost_part .= '<div class="line-deliver"></div>';
                    $dost_part .= '</div>';
                } else {
                    $dost_part .= '<div class="popup__row">';
                    $dost_part .= '<span>До бесплатной доставки осталось:</span>';
                    $dost_part .= '<div class="popup_amount">'.\SaleFormatCurrency(($free_delivary-$sum), 'RUB').'</div>';
                    $dost_part .= '</div>';
                    $dost_part .= '<div class="popup__dost">';
                    $dost_part .= '<img style="right: '.round(440*(($free_delivary-$sum)/$free_delivary)-10).'px; filter: gray; /* IE6-9 */ -webkit-filter: grayscale(1); /* Google Chrome, Safari 6+ & Opera 15+ */ filter: grayscale(1); /* Microsoft Edge and Firefox 35+ */" src="'.SITE_TEMPLATE_PATH.'/static/dist/images/general/delivery-truck.jpg">';
                    $dost_part .= '<div class="line-deliver" style="background-position-x: '.round(-440*(($free_delivary-$sum)/$free_delivary)).'px;"></div>';
                    $dost_part .= '</div>';
                }
            }  else {
                $summ_part = '';
                $summ_part .= '<div class="popup__row">';
                $summ_part .= '<span>В корзине товаров на сумму:</span>';
                $summ_part .= '<div class="popup_amount">'.\SaleFormatCurrency($sum, 'RUB').'</div>';
                $summ_part .= '</div>';

            }
            $out .= $dost_part;
            $out .= $summ_part;
            $out .= '</div>';
            if(!$popup_opened){
                $out .= $out_add;
                $out .= '</div>';
            }
        }
        $out .= '<script>';
        //$out .= "$('body').prepend('<div class=\"shadow shadow-select\"></div>');";
        $out .= 'showPopup($(".popup"),{cssAuto:"true"});';
        $out .= '$(".sbasket-refresh").click();';
        $out .= '$("#basket-refresh").click();';
        $out .= 'inBasket($(\'.js-do[data-id="'.$_REQUEST['id'].'"][data-action=basket_change]\'),'.$quant.');';

        $out .= '</script>';


        if(!$popup_opened){
            $json['selector'] = '.popup';
            $json['result'] = $out;
            $json['error'] = 'ok';
        }
        else{
            $json['func'] = '
                    var ajax_content = $(".small-cards [data-action='.$_REQUEST["action"].'][data-id='.$_REQUEST["id"].']").parents(".product-box__content");
                    var old_content = ajax_content.html();
                    ajax_content.html("<div style=\"margin-bottom: 30px\">'.$w.'</div><strong>'.$part.' в корзину.</strong>");
                        setTimeout(function(){
                        ajax_content.html(old_content);
                        inBasket($(\'.js-do[data-id="'.$_REQUEST['id'].'"][data-action=add_cart]\'),'.$quant.');
                        $(".popup__row").replaceWith(`'.$summ_part.'`);
                        $(".popup__dost").replaceWith(`'.$dost_part.'`);
                        },3000)';
            $json['error'] = 'ok';
        }
    }

}

*/
