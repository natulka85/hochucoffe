<?//if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
//require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
//require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
global /*$APPLICATION, */$BP_TEMPLATE;

if(
    strpos($_SERVER['REQUEST_URI'], '/filter/')!==false
    && strpos($_SERVER['REQUEST_URI_OLD'], '/discount/')===false
)
{
    $new_url = $BP_TEMPLATE->ChpuFilter()->convertOldToNew($_SERVER['REQUEST_URI']);
    //if($new_url)
        //LocalRedirect($new_url, true, "301 Moved permanently");
}

if(strpos($_SERVER['REQUEST_URI'], '/catalog/')!==false && strpos($_SERVER['REQUEST_URI'], '/discount/')===false)
{
    $old_url = $BP_TEMPLATE->ChpuFilter()->convertNewToOld($_SERVER['REQUEST_URI']);

    if($old_url)
    {
        $_SERVER['REQUEST_URI_OLD'] =  $_SERVER['REQUEST_URI'];
        $_SERVER['REQUEST_URI'] =  $old_url;
    }
}

//new chpu
//redirect old prim

preg_match('/catalog\/[\w^]+/', $_SERVER['REQUEST_URI'], $match);

if($match[0]!=''){
    $match[0] = str_replace('catalog/','',$match[0]);
    $SmartTemplate =  $match[0]."/#SMART_FILTER_PATH#/";
}
else
    $SmartTemplate =  "filter/#SMART_FILTER_PATH#/";

if(str_replace('?'.$_SERVER['QUERY_STRING'], '',$_SERVER['REQUEST_URI']) == '/catalog/')
{
    $_SERVER['REQUEST_URI_OLD'] = $_SERVER['REQUEST_URI'];
    $_REQUEST['DEPTH_LVL'] = 3;
    $_SERVER['REQUEST_URI'] = '/catalog/';

}
elseif(str_replace('?'.$_SERVER['QUERY_STRING'], '',$_SERVER['REQUEST_URI'])=='/collections/')
{
    $_SERVER['REQUEST_URI_OLD'] = $_SERVER['REQUEST_URI'];
    $_REQUEST['DEPTH_LVL'] = 2;
    $_SERVER['REQUEST_URI'] = '/catalog/';
}

//$_SERVER['REQUEST_URI'] = str_replace('tipy',$_REQUEST['SECTION_CODE'],$_SERVER['REQUEST_URI']);

preg_match('/catalog\/[\w\-]+\//', $_SERVER['REQUEST_URI'], $output_array);

if(strpos($_SERVER['REQUEST_URI'], '/catalog/filter/')!==false)
{
    $CategoryType = "ALL_CAT_FILTER";
    $arUrl = explode('?',$_SERVER['REQUEST_URI']);
    $arUrl = explode('/filter/',$arUrl[0]);
    $arUrl = explode('/',$arUrl[1]);
    if(
        strpos($_SERVER['REQUEST_URI'], '_proizvoditel-is-')!==false
        && strpos($_SERVER['REQUEST_URI'], '-or-')===false
        && count($arUrl)<3
    )
    {
        $CategoryType =  "BRAND";
    } elseif(
        strpos($_SERVER['REQUEST_URI'], 'aktsiya-is-true')!==false
        && count($arUrl)<3
    )
    {
        if($_REQUEST['SECTION_CODE'])
            $CategoryType =  "STOCK_SECTION";
        else
            $CategoryType =  "STOCK";
    }
}
elseif($output_array[0]!=='')  //section+prim
{
    /*$CategoryType =  "SECTION";

    if(strpos($_SERVER['REQUEST_URI'], '/filter/')!==false)
    {
        if(
            strpos($_SERVER['REQUEST_URI'], '_proizvoditel-is-')!==false
            && strpos($_SERVER['REQUEST_URI'], '-or-')===false
        )
        {
            $arUrl = explode('?',$_SERVER['REQUEST_URI']);
            $arUrl = explode('/filter/',$arUrl[0]);
            $arUrl = explode('/',$arUrl[1]);
            if(count($arUrl)<3)
                $CategoryType =  "SECTION_MAN";
            else
                $CategoryType =  "SECTION_FILTER";
        }
        elseif(
            strpos($_SERVER['REQUEST_URI'], 'aktsiya-is-true')!==false
        )
            $CategoryType = "STOCK_SECTION";
        elseif(
            strpos($_SERVER['REQUEST_URI'], '_novinka-is-156fcd84-55c9-11e4-844e-d4ae52a11316')!==false
        )
            $CategoryType =  "NEW_SECTION";
        else
            $CategoryType = "SECTION_FILTER";
    }*/
}


// Доработка для D7 от версии 15.5
//для того что бы сработал новый $_SERVER['REQUEST_URI']
$application = \Bitrix\Main\Application::getInstance();
$context = $application->getContext();

$request = $context->getRequest();
$Response = $context->getResponse();
$Server = $context->getServer();

$server_get = $Server->toArray();
$server_get["REQUEST_URI"] = $_SERVER['REQUEST_URI'];
$Server->set($server_get);

$context->initialize(new Bitrix\Main\HttpRequest($Server, array(), array(), array(), $_COOKIE), $Response, $Server);

$GLOBALS['APPLICATION']->reinitPath();

?>
