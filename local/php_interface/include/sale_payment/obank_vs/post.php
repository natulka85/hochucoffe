<?
$data = array(
	"userName" => "vamsvet-api",
	"password" => "3Pg4^!Ro2u!g", 
	"orderNumber" => $_REQUEST["orderNumber"].':'.rand(0,100),
	"amount" => $_REQUEST["amount"]*100,
	"language" => "RU",
	"returnUrl" => "https://www.vamsvet.ru/local/php_interface/include/sale_payment/obank_vs/pay_result.php?ORDER_ID=".$_REQUEST["orderNumber"]
);

$url = 'https://secure.openbank.ru/payment/rest/register.do';   
$ch = curl_init();


curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS,$data);
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_SSLVERSION, 1);

$result = curl_exec($ch);


if($result === false){
	echo json_encode(array("error" => curl_error($ch), "error_num" => curl_errno($ch)));
}else{
	// $result = json_decode($result, true);
	// if($result["errorCode"] == "1"){
		// $result = array("formUrl" => "https://www.vamsvet.ru/bitrix/php_interface/include/sale_payment/obank_vs/pay_result.php?ORDER_ID=".$_REQUEST["orderNumber"]);
	// }
	// $result = json_encode($result);
	echo $result;
}

curl_close($ch);
?>