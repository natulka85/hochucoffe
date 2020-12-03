<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
use Bitrix\Main,
    Bitrix\Main\Localization\Loc;

CModule::IncludeModule("iblock");

//$context = file_get_contents($_FILES['file_table']['tmp_name']);

$file = $_FILES['file_table']['tmp_name'];
$i = 0;
if (($handle = fopen($file, "r")) !== FALSE) {
    while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
        $i++;
        if($i>1){
            if(strlen(trim($data[2]))>2){
                $key = $data[1].'--'.$data[2]; //символьный код и артикул
                $info[$key] = [
                    'article' => $data[2],
                    'review' => $data[4],
                    'grade' => $data[5],
                    'name' => $data[6]
                ];
                $articleMatrix[$data[2]] = $key;
            }
        }
    }
    fclose($handle);
}

$arSelect = ['ID','IBLOCK_ID','NAME','PROPERTY__ARTICLE_COMP','CODE'];
$arFilter = ["IBLOCK_ID" => 1,'IBLOCK_CODE'=>'catalog', 'ACTIVE' => 'Y','PROPERTY__ARTICLE_COMP_VALUE'=> array_keys($articleMatrix)];
$res = \CIBlockElement::GetList(Array('ID'=>'asc'), $arFilter,false, [], $arSelect);
while($ob = $res->Fetch()){
    $key_2 = $ob['CODE'].'--'.$ob['PROPERTY__ARTICLE_COMP_VALUE'];
    //echo $key_2.'$key_2';
    if(isset($info[$key_2])){
        $info[$key_2]['ID'] = $ob['ID'];
        $info[$key_2]['NAME'] = $ob['NAME'];
    }
}


$out .= '<form class="nojs pack-handler-table" method="post" action="/bitrix/admin/bp_template_index.php?file=pack_handler_reviews.php&lang=ru">';
$out .= bitrix_sessid_post();

$out .= '<table><tr>
               <th>ID</th>
               <th>Имя товара</th>
               <th>Отзыв</th>
               <th>Оценка</th>
               <th>Имя клиента</th>
        </tr>';

$i= 0;
    foreach ($info as $item){
            $out .= '<tr>
                <td><input type="text" readonly name="element['.$i.'][id]" value="'.$item['ID'].'"></td>
                <td><input type="text" readonly name="element['.$i.'][name_good]" value="'.$item['NAME'].'"></td>
                <td><textarea type="textarea" class="textarea" name="element['.$i.'][review]">'.$item['review'].'</textarea></td>
                <td><input type="text" name="element['.$i.'][grade]" value="'.$item['grade'].'"></td>
                <td><input type="text" name="element['.$i.'][name]" value="'.$item['name'].'"></td>
        </tr>';
        $i++;
    }
$out .= '</table>';
    $out .= '<input type="submit"
                   name="save"
                   value="'.Loc::getMessage("MAIN_SAVE").'"
                   title="'.Loc::getMessage("MAIN_OPT_SAVE_TITLE").'"
                   class="adm-btn-save"/>';
$out .= '</form>';

//    echo $out;

$json['error'] = 'ok';
$json['func'] = '$(".pack-handler-table").remove(); $(".pack-handler-form").after(`'.$out.'`)';
echo json_encode($json);
//echo $context;

