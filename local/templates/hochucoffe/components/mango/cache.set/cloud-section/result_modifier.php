<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
use Bp\Template\SettingsTable;
use Bp\Template\SeoSection;
use Bp\Template\ChpuFilter;
global $BP_TEMPLATE,$APPLICATION;
$page_url = $APPLICATION->getCurPage();
$arProps = SeoSection::convertUrlToCheck($page_url);

if($arParams['SECTION_ID']>0){
    $arConsts = array();
    $dbConst = SettingsTable::getList(array(
        'select' => array('ID','NAME','CODE','VALUE'),
        'filter' => array('CODE' => 'FILTER_AVAIL_'.$arParams['SECTION_ID'])
    ));
    while ($arConst = $dbConst->Fetch()){
        if($arConst['VALUE']!='')
            $arResultTemp = json_decode($arConst['VALUE'],TRUE);
    }
}
$arItems = [];

if(count($arProps['PROPS']) == 0){
    $arProps['PROPS'] = ['WEIGHT'=>'','OCENKA_SCA'=>'','VKUS'=>'','_STRANA'=>''];
        foreach ($arProps['PROPS'] as $prop=>$val){
            $arItems[$prop] = $arResultTemp[$prop];
        }
}
elseif(count($arProps['PROPS'])<=3){
    foreach ($arProps['PROPS'] as $prop=>$val){
        if(in_array($prop,['AKCIYA','_NOVINKA','_HIT_PRODAZH'])) continue;
        $arItems[$prop] = $arResultTemp[$prop];
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
    foreach ($val as $k=>$v){
        $name = $filter_part = $active = $link = '';

        $arKeys = explode('||',$k);

        if(in_array($arKeys[1],$arProps['PROPS'][$prop])) continue;
        if(strpos($arKeys[1],'__') !== false){ // значение string
            $arKeys2 = explode('__',$arKeys[1]);
            $filter_part .= '/filter/'.strtolower($arKeys2[0]).'-is-'.$arKeys2[1];
        }
        else{
            $filter_part .= '/filter/'.strtolower($prop).'-is-'.$arKeys[1];
        }
        $name = $arKeys[0].$arPropMatrix[$prop];
        $link = $section_part.$filter_part.'/';

        if(strpos($page_url,str_replace('/catalog/filter/','',$link)) !==false ){
            $active = 'Y';
        }
        if($prop!='_STRANA'){
            $name = $BP_TEMPLATE->str_fst_lower($name);
        }
        $arResult['ITEMS'][] = [
            'NAME' => $name,
            'LINK' => ChpuFilter::convertOldToNew($link),
            'ACTIVE' => $active,
            'CNT' => $v
        ];
        $i++;
        if($i==10){
            break;
        }
    }
}
