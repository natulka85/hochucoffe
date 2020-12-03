<?php
global $BP_TEMPLATE;
$successMes = '';
$successMes .= "<div class='popup__text _type-3'><strong>Добро пожаловать <br>в Клуб ценителей хорошего кофе!</strong></div>";
$successMes .= "<div class='popup__text _type-4'>Мы уже готовим для Вас:</div>";
$successMes .= "<div class='popup__text _type-4'><ul>
<li>Секретные акции, о которых вы узнаете первым</li>
<li>Интересные статьи про Ваш любимый напиток</li>
<li>Персональные скидки и предложения</li>
</ul>
</div>";
$successMes .= "<div class='popup__text _type-4'>Вам уже доступна персональная скидка 5% на первую покупку в нашем магазине*</div>";
$successMes .= "<div class='popup__text _type-4'>Проверьте Вашу почту</div>";
$successMes .= "<div class='popup__text _type-5'>*скидка действует при условии, что вы совершаете свою первую покупку в интернет-магазине hochucoffe.ru на сумму не менее 3000 руб.</div>";

$out = '';
if(CModule::IncludeModule("subscribe")){
    $valid['email'] = false;
    if($_REQUEST['email']!=''){
        $regEx = '/.+@.+\..+/i';
        preg_match($regEx, $_REQUEST['email'], $matches);
        if(!empty($matches)){
            $valid['email'] = true;
        }
    }

    if($valid['email'])
    {
        $arFields = Array(
            "FORMAT" => ("html"),
            "EMAIL" => $_REQUEST["email"],
            "CONFIRMED" => "Y",
            "ACTIVE" => "Y",
            "SEND_CONFIRM" => "N",
            "RUB_ID" => array(1,2,3),
        );
        $subscr = new CSubscription;

        //can add without form-lkation
        $ID = $subscr->Add($arFields);

        if($ID>0)
        {
            //соглашения
            if($_REQUEST['ag_id'] != ''){
                \Bitrix\Main\UserConsent\Consent::addByContext($_REQUEST['ag_id'], 'main/subscribes', $arFields['PHONE'], array('URL' => $_SERVER['HTTP_REFERER']));
            }

            //$send_id = CEvent::Send("FEEDBACK_FORM", SITE_ID, $arFields, "N", 50);

                $out .= '<div class=\"popup__box is-subscribe\">';
                $out .= '<div class=\"popup__box-wrap\"></div>';
                $out .= '<div class=\"popup__close icon-2a_plus\"></div>';
                $out .= '<div class=\"popup__content\">';
                $out .= $successMes;
                $out .= '<div class="popup__btn-wrap">';
                $out .= '<div class=\"popup__btn btn is-white-blue\">Перейти в каталог</div>';
                $out .= '<div class=\"popup__btn btn is-white js-popup-close\">Закрыть</div>';
                $out .= '</div>';
                $out .= '</div>';
                $out .= '</div>';

                $event = '';

                $json['error'] = 'ok';
                $json['func'] ='$(".popup").html(`'.$out.'`);
                    showPopup($(".popup"),{widthCss:"560"});
                    $("form.subscribe__form")[0].reset();'.$event;
            }
        elseif ($subscr->LAST_ERROR!=''){
            $json['func'] ='
                    $("form.subscribe__form .error").html("'.$subscr->LAST_ERROR.'");';
            $valid['email'] = false;//чтобы ошибка прописалась
        }
    } else {
        $json['error'] = 'error';
        $json['func'] = '';
    }
    $json['func'] .= $BP_TEMPLATE->Valid($valid,'form.subscribe__form');
}


