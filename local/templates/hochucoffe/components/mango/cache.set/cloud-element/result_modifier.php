<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
use Bp\Template\SettingsTable;
use Bp\Template\SeoSection;
$obSeoSection = new Bp\Template\SeoSection;
use Bp\Template\ChpuFilter;
global $BP_TEMPLATE,$APPLICATION;
$page_url = $APPLICATION->getCurPage();
$arProps['PROPS'] = $arParams['PROPS'];
if($arProps == ''){
    $arProps = SeoSection::convertUrlToCheck($page_url);
}
$section_id = $arParams['SECTION_ID'];
if($section_id==''){
    $section_id = 1;
}
if($section_id>0){
    $arConsts = array();
    $dbConst = SettingsTable::getList(array(
        'select' => array('ID','NAME','CODE','VALUE'),
        'filter' => array('CODE' => 'FILTER_AVAIL_'.$section_id)
    ));
    while ($arConst = $dbConst->Fetch()){
        if($arConst['VALUE']!='')
            $arResultTemp = json_decode($arConst['VALUE'],TRUE);
    }
}
$arItems = [];
foreach ($arResultTemp as $prop=>$propValue){
    foreach ($propValue as $name=>$qnt){
        $arValuesTemp[$prop][explode('||',$name)[0]] = $name;
    }
}

if(count($arProps['PROPS']) > 0){
    foreach ($arProps['PROPS'] as $prop=>$val){
        $arValues = [];
        if(in_array($prop,['VKUS','_STRANA','_SOSTAV','OCENKA_SCA'])) {
            if(is_array($val['VALUE'])){
                $arValues = $val['VALUE'];
            }
            else{
                $arValues[0] =  $val['VALUE'];
            }
            foreach ($arValues as $valItem){
                if(isset($arValuesTemp[$prop][$valItem])){
                    $key = $arValuesTemp[$prop][$valItem];
                    $arItems[$prop][$key] = $arResultTemp[$prop][$key];
                }
            }

        }
    }
}

$section_part = '/catalog';
if($arParams['SECTION_ID']>1){
    $section_part .= '/section/'.$arParams['SECTION_CODE'];
}
$arPropMatrix = [
    'WEIGHT' => ' г',
    'GOD_UROGAYA' => ' г',
    'OCENKA_SCA' => ' SCA',
];
foreach ($arItems as $prop=>$val){
    $i=0;
    $ar['PROPS'] = [];
    foreach ($val as $k=>$v){
        $name = $filter_part = $active = $link = '';

        $arKeys = explode('||',$k);
        if(strpos($arKeys[1],'__') !== false){ // значение string
            $arKeys2 = explode('__',$arKeys[1]);
            $filter_part .= '/filter/'.strtolower($arKeys2[0]).'-is-'.$arKeys2[1];
        }
        else{
            $filter_part .= '/filter/'.strtolower($prop).'-is-'.$arKeys[1];
        }
        $name = $arKeys[0].$arPropMatrix[$prop];
        $ar['PROPS'][$prop][0] = $arKeys[1];
        $name = $obSeoSection->getFullName($ar, $section_id);
        $link = $section_part.$filter_part.'/';

        if(strpos($page_url,str_replace('/catalog/filter/','',$link)) !==false ){
            $active = 'Y';
        }
        $img = SITE_TEMPLATE_PATH.'/static/dist/images/filters/'.$arKeys[1].'.jpg';
        if($prop == 'OCENKA_SCA'){
            $img = SITE_TEMPLATE_PATH.'/static/dist/images/filters/sca.jpg';
        }
        $arResult['ITEMS'][] = [
            'NAME' => $BP_TEMPLATE->str_fst_lower($name),
            'LINK' => ChpuFilter::convertOldToNew($link),
            'IMG' => $img,
            'ACTIVE' => $active,
            'CNT' => $v
        ];
        $i++;
        if($i==10){
            break;
        }
    }
}
