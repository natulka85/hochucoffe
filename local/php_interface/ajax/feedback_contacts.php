<?php
global $BP_TEMPLATE;
$successMes = '';
$successMes .= "<div class='popup__title'>Сообщение отправлено!</div>";
$successMes .= "<div class='popup__subtitle'>Мы свяжемся с Вами в ближайшее время</div>";

$valid['phone'] = false;
$valid['name'] = false;
$valid['email'] = false;

$phone = preg_replace("/[^\d]+/","",$_REQUEST['phone']);
if(strlen($phone)>=11)
{
    $valid['phone'] = true;
}
if(strlen($_REQUEST['name'])>=1){
    $valid['name'] = true;
}
if($_REQUEST['email']!=''){
    $regEx = '/.+@.+\..+/i';
    preg_match($regEx, $_REQUEST['email'], $matches);
    if(!empty($matches)){
        $valid['email'] = true;
    }
}

if($valid['phone'] && $valid['name']  && $valid['email'])
{
        $arFields = Array(
            "PHONE" => $_REQUEST["phone"],
            'NAME' => $_REQUEST['name'],
            'EMAIL' => $_REQUEST['email'],
            'THEME' => 'Отправка формы с контактов'
        );

        //соглашения
        if($_REQUEST['ag_id'] != ''){
            \Bitrix\Main\UserConsent\Consent::addByContext($_REQUEST['ag_id'], 'main/feddback_contactts', $arFields['PHONE'], array('URL' => $_SERVER['HTTP_REFERER']));
        }

        $send_id = CEvent::Send("FEEDBACK_FORM", SITE_ID, $arFields, "N", 50);

        if($send_id>0){
            $out .= '<div class=\"popup__box\">';
            $out .= '<div class=\"mc-close close-mc\"></div>';
            $out .= $successMes;
            $out .= '<div class=\"popup__btn btn_c is-green js-popup-close\">Закрыть</div>';
            $out .= '</div>';

            $event = '';

            $json['error'] = 'ok';
            $json['func'] ='
                    $(".contacts__form-fields").html(`'.$successMes.'`);'.$event;
        }

} else {
    $json['error'] = 'error';
    $json['func'] = '';
}
$json['func'] .= $BP_TEMPLATE->Valid($valid,'form.contacts__form');
