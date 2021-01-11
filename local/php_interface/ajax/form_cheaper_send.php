<?php
global $BP_TEMPLATE;
$successMes = '';
$successMes .= "<div class='popup__text _type-2'><strong>Сообщение отправлено!</strong></div>";
$successMes .= "<div class='popup__text _type-2'>Мы свяжемся с Вами в ближайшее время.</div>";

$valid['phone'] = false;
$valid['name'] = false;
$valid['link'] = false;
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
if(strlen($_REQUEST['link'])>=6)
{
    $string_url = $_REQUEST['link'];
    preg_match('/(?i)\b((?:https?:\/\/|www\d{0,3}[.]|[a-z0-9.\-]+[.][a-z]{2,4}\/)(?:[^\s()<>]+|\(([^\s()<>]+|(\([^\s()<>]+\)))*\))+(?:\(([^\s()<>]+|(\([^\s()<>]+\)))*\)|[^\s`!()\[\]{};:\'\".,<>?«»“”‘’]))/', $string_url, $output_array);

    if($output_array[0]!=''){
        $valid['link'] = true;
    }
}

if($valid['link'] && ($valid['email'] || $valid['phone']))
{
    $arFields = Array(
        "PHONE" => $_REQUEST["phone"],
        'NAME' => $_REQUEST['name'],
        'EMAIL' => $_REQUEST['email'],
        'LINK' => $_REQUEST['link'],
        'FROM_LINK' => $_SERVER['HTTP_REFERER'],
        'THEME' => 'Отправка формы ХОЧУ ДЕШЕВЛЕ}'
    );

    //соглашения
    if($_REQUEST['ag_id'] != ''){
        \Bitrix\Main\UserConsent\Consent::addByContext($_REQUEST['ag_id'], 'main/feddback_cheper', $arFields['PHONE'], array('URL' => $_SERVER['HTTP_REFERER']));
    }

    $send_id = CEvent::Send("FEEDBACK_FORM", SITE_ID, $arFields, "N", 88);

    if($send_id>0){
        $out .= '<div class=\"popup__box\">';
        $out .= '<div class=\"mc-close close-mc\"></div>';
        $out .= $successMes;
        $out .= '<div class=\"popup__btn btn_c is-green js-popup-close\">Закрыть</div>';
        $out .= '</div>';

        $event = '';

        $json['error'] = 'ok';
        $json['func'] ='
                    $(".popup__box.is-cheaper .popup__content").html(`'.$successMes.'`);'.$event;
    }

} else {
    $json['error'] = 'error';
    $json['func'] = '';
}
$json['func'] .= $BP_TEMPLATE->Valid($valid,'.popup__box.is-cheaper form.form__form');
