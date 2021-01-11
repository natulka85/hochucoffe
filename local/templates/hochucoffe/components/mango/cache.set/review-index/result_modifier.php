<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
global $BP_TEMPLATE;
CModule::IncludeModule("askaron.reviews");
CModule::IncludeModule("iblock");

$limit = 10;
if($arParams['CNT']>0)
    $limit = $arParams['CNT'];

$arFilter = ['ACTIVE' => 'Y'];
if($arParams['FILTER_NAME']!=''){
    global ${$arParams['FILTER_NAME']};
    $arFilter = array_merge($arFilter,${$arParams['FILTER_NAME']});
}
$res = \Askaron\Reviews\ReviewTable::getList(array(
    'filter' => $arFilter,
    'order' => array('DATE'=>'desc'),
    'select' => array('*'),
    'limit' =>$limit
));
while($ob = $res->fetch()){
    if($arParams['TEXT_LENGTH']!='' && strlen($ob['TEXT']) > $arParams['TEXT_LENGTH']){
        $shotName = substr($ob['TEXT'], 0, $arParams['TEXT_LENGTH']);
        $ob['TEXT_FORMAT'] = substr($shotName, 0, strrpos($shotName, ' ')).'...';
        $ob['BTN_ALL'] = true;
    }
    else{
        $ob['TEXT_FORMAT'] = $ob['TEXT'];
    }
    $arItems[] = $ob;
  $arElementsId[] = $ob['ELEMENT_ID'];
}
if(count($arElementsId)>0){
    $dbElement = CIBlockElement::GetList(
        [],
        ['IBLOCK_ID'=>1,'=ID'=>$arElementsId],
        false,
        false,
        [ 'ID',
            'NAME',
            'PREVIEW_PICTURE',
            'CODE',
            'PROPERTY_ASKARON_REVIEWS_COUNT','PROPERTY_ASKARON_REVIEWS_AVERAGE'
        ]
    );
    while($el = $dbElement->Fetch()){
        $arFile = CFile::ResizeImageGet(
            $el['PREVIEW_PICTURE'],
            array("width" => 130, "height" => 130),
            BX_RESIZE_IMAGE_PROPORTIONAL ,
            true
        );
         $el['PICTURE'] = $arFile;
         $el['DETAIL_PAGE_URL'] = '/catalog/product/'.$el['CODE'].'/';
         $arElems[$el['ID']] =  $el;
    }

}
foreach ($arItems as &$item){
    $item['ELEMENT'] = $arElems[$item['ELEMENT_ID']];
}
$arResult['ITEMS'] = $arItems;
unset($arItems);
