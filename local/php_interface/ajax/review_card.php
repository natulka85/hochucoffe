<?php
global $BP_TEMPLATE;
$successMes = '';
$successMes .= "
<div class='popup__text _type-3'>
    <strong>Спасибо за Ваше мнение!</strong></div>
    <div class='popup__text _type-2'>Отзывы помогают нам совершенствоваться, <br>а Вам, получать только лучший продукт и великолепный сервис!</div>";

$validRequest =[
    'name' => false,
    'review' => false,
    'grade' => false,
];

$validation = true;
foreach ($validRequest as $key=>$val){
    if($_REQUEST[$key] == ''){
        $validation = false;
        $valid[$key] = false;
    }
    else{
        $valid[$key] = true;
    }
}
if($validation)
{
    global $USER;
    if($_REQUEST['ag_id'] != ''){
        \Bitrix\Main\UserConsent\Consent::addByContext($_REQUEST['ag_id'], 'main/review_card', $_REQUEST['name'], array('URL' => $_SERVER['HTTP_REFERER']));
    }

    CModule::IncludeModule("askaron.reviews");
    $result = \Askaron\Reviews\ReviewTable::add([
        'ACTIVE' => 'N',
        'AUTHOR_NAME' => $_REQUEST['name'],
        'GRADE' => $_REQUEST['grade'],
        'TEXT' => $_REQUEST['review'],
        'ELEMENT_ID' => $_REQUEST['elid'],
        'AUTHOR_USER_ID' => $USER->GetID(),
        'DATE' => new \Bitrix\Main\Type\DateTime(),
    ]);

    if ($result->isSuccess())
    {
        $id = $result->getId();

        $arFields['MESSAGE'] = '<a href="http://hochucoffe.ru/bitrix/admin/admin_helper_route.php?lang=ru&module=bp.template&view=reviews_list&restore_query=Y&entity=tools">Новый отзыв на сайте</a>';
        $send_id = CEvent::Send("ASKARON_REVIEWS_NEW_REVIEW", SITE_ID, $arFields, "N", 87);
    } else {
        $json['error'] = 'error';
    }
    $out = '';
    if($id)
    {
        $out .= '<div class=\"popup__box is-review-thx\">';
        $out .= '<div class=\"popup__box-wrap\"></div>';
        $out .= '<div class=\"popup__close icon-2a_plus\"></div>';
        $out .= '<div class=\"popup__content\">';
        $out .= $successMes;
        $out .= '<div class=\"popup__btn btn is-white js-popup-close\">Закрыть</div>';
        $out .= '</div>';
        $out .= '</div>';

        $event = '';

        $json['error'] = 'ok';
        $json['func'] ='$(".popup").html(`'.$out.'`);
                    showPopup($(".popup"),{});
                    $("form.review-form")[0].reset();'.$event;
    }
}

$json['func'] .= $BP_TEMPLATE->Valid($valid,'form.review-form');
