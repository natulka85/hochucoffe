<?php
define('STOP_STATISTICS', true); 
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
$GLOBALS['APPLICATION']->RestartBuffer();

global $APPLICATION, $BP_TEMPLATE;

$json = array(
    'error' => '',
    'result' => '',
    'message' => '',
);

if($_REQUEST["action"] != "")
    include __DIR__."/ajax/".strtolower($_REQUEST["action"]).".php";
    
    
echo json_encode($json); 