<?php
$serverRef = $_SERVER['HTTP_REFERER'];
//setcookie('__refferer', '', strtotime('-30 days'));
//setcookie('__refferer', '', strtotime('-30 days'),"/",$_SERVER['SERVER_NAME']);

if(isset($_GET['utm_source']))
{
    $refer = $_GET['utm_source'];

        if(isset($_GET["gclid"]))
            $refer = "google";

    if(isset($_GET['utm_medium']))
        $refer .= ' - '.$_GET['utm_medium'];
    setcookie('__refferer', $refer, strtotime('+30 days'),"/",$_SERVER['SERVER_NAME']);

}
elseif(isset($_GET['gclid'])){
    $refer = 'google.adwords';
    setcookie('__refferer', $refer, strtotime('+30 days'),"/",$_SERVER['SERVER_NAME']);
}
elseif($_COOKIE['__refferer'] == ''){
    $refer = $_SERVER['SERVER_NAME'];
    setcookie('__refferer', $refer, strtotime('+30 days'),"/",$_SERVER['SERVER_NAME']);
}

$_SESSION['ref'] = $refer;
if ($_SESSION["ref"] == "")
{
    $_SESSION["ref"] = "hochucoffe.ru"; // Если все пусто, значит заход прямой
}
