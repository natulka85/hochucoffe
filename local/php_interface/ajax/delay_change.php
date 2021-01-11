<?php
global $BP_TEMPLATE;
CModule::IncludeModule("iblock");
CModule::IncludeModule("sale");
CModule::IncludeModule("catalog");
$json = array(
    'error' => '',
    'result' => '',
    'message' => '',
);
$arId = explode(',',$_REQUEST["id"]);
$img = explode(',',$_REQUEST['img']);

$popup_opened = false;
if($_REQUEST['from'] == 'popup_basket'){
    $popup_opened = true;
}

//$id = intVal($_REQUEST["id"]);
if($_REQUEST["state"]=='N')
{
    $quantity = 1;

    foreach($arId as $id){
        $basketID = $BP_TEMPLATE->basket()->addDelay($id);
    }

    $arWordParts = [
        'Ж' => 'добавлена',
        'М' => 'добавлен',
        'С' => 'добавлено',
    ];
    if(count($arId)>1){
        $w = $_REQUEST['name'];
    }
    else{
        $w = $_SESSION['bp_cache']['bp_user']['products'][$id]['NAME'];
    }
    $rod = $BP_TEMPLATE->InsRod($w);

    $part = $arWordParts[$rod];
    if($part=='')
        $part = 'добавлен';

    if($basketID>0)
    {

        if(!$popup_opened && count($_REQUEST["id"]) == 1){
            //additional items
            global $BP_TEMPLATE;
            \Bitrix\Main\Loader::includeModule('iblock');

            $res = CIBlockElement::GetList(
                [],
                ['ID' => $_REQUEST['id']],
                false,
                ['nTopCount' => 1],
                [
                    'NAME',
                    'PROPERTY_ANALOGS',
                    'PROPERTY_ANALOGS_HAND_VALUE',
                ]
            );
            $arIds = [];
            if ($ob = $res->Fetch()) {
                $arAnalogs = [];
                if($ob['PROPERTY_ANALOGS_VALUE']!=''){
                    $arAnalogs = explode(',',$ob['PROPERTY_ANALOGS_VALUE']);
                }

                if($ob['PROPERTY_ANALOGS_HAND_VALUE']!=''){
                    $arAnalogsHand = explode(',',$ob['PROPERTY_ANALOGS_HAND_VALUE']);
                    $arAnalogs = array_merge($arAnalogs,$arAnalogsHand);
                }
                if(count($arAnalogs)>12){
                    array_splice($arAnalogs,12);
                }
                $arIds = array_merge($arIds,$arAnalogs);
            }

            global $prodFilterBC;
            $prodFilterBC['ID'] = $arIds;

            ob_start();
            if(count($prodFilterBC['ID'])>0)
            {
                $events = [];
                /*$APPLICATION->IncludeComponent(
                    "mango:cache.set",
                    "slider-smallbasket-full",
                    array(
                        "IBLOCK_TYPE" => $BP_TEMPLATE->getConstants()->IBLOCK_MAIN_TYPE,
                        "IBLOCK_ID" => $BP_TEMPLATE->getConstants()->IBLOCK_MAIN_ID,
                        "CACHE_TIME"  =>  3600,
                        "FILTER_NAME" => "prodFilterBC",
                        'MOD_TITLE' => 'Похожие товары',
                        'MOD_LIST_CLASS' => 'js-slick-basket-add',
                        //'CUR_ELEMENT' => $arResult,
                        'FROM' => 'sbasket',
                        'IDS' => $prodFilterBC['ID'],//cache unique
                        'EVENTS' => $events,
                        'DATA_FROM' => 'popup_otlozhennye'
                    ),
                    false
                );*/
            }
            $out_add = ob_get_contents();
            ob_end_clean();
        }


        $out = '';
        $out .= '<div class="popup-ajax-content">';

        if(!$popup_opened){
            $out .= '<div class="popup__box is-otlozhit">';
            $out .= '<div class=\"popup__box-wrap\"></div>';
            $out .= '<div class="popup__close icon-2a_plus"></div>';
        }
        $out .= '<div class="popup__content">';
        $out .= '<div class="popup__text _type-1">';
        $out .= '<strong>'.$w.'</strong><br>';
        $out .= ' '.$part.' в отложенные';
        $out .= '</div>';

        if($img !=''){
            $out .= '<div class="popup__img">';
            foreach ($img as $im){
                $out .= '<img src="'.$im.'">';
            }
            $out .= '</div>';
        }
        $out .= '<div class="popup__btn-wrap">';
        $out .= '<a href="/personal/delay/" target="_blank" class="popup__btn btn is-transp-red">Перейти к отложенным</a>';
        $out .= '</div>';
        $out .= '</div>';
        if(!$popup_opened){
            $out .= $out_add;
            $out .= '</div>';
        }

        $out .= '</div>';

        //echo $popup_opened.'$popup_opened_1';
        if(!$popup_opened){
            $json['func'] = '$(".popup").html(`'.$out.'`);
                        showPopup($(".popup"),{cssAuto:"true"});
                        inDelay('.$_REQUEST['id'].', "Y");
                        $(".sbasket-refresh").click();';
            $json['error'] = 'ok';
        }
        else{

        }


    } else {
        $json['error'] = 'error';
        $json['message'] = 'Проблема добавления';
    }
} else {
    foreach($arId as $id){
        $basketID = $BP_TEMPLATE->basket()->delDelay($id);
    }

    //echo $popup_opened.'$popup_opened';
    if(!$popup_opened){
        $json['func'] = 'inDelay('.$_REQUEST['id'].', "N");
        $(".sbasket-refresh").click();';
        if($_REQUEST['reload']=='Y'){
            $json['func'] .= 'reloadPage(window.location.href,{catalog_ajax_call:"Y"});';
        }
        $json['error'] = 'ok';
    }
    else{

    }
}
