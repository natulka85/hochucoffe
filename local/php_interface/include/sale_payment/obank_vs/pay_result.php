<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
$data = array(
	"userName" => "vamsvet-api",
	"password" => "3Pg4^!Ro2u!g",
	"orderId" => $_REQUEST["orderId"]
);

// $url = 'https://securetest.openbank.ru/testpayment/rest/getOrderStatus.do';  
$url = 'https://3dsec.sberbank.ru/payment/rest/getOrderStatus.do';

$ch = curl_init();
//\Bitrix\Main\Diag\Debug::writeToFile($_REQUEST,'REQUEST','_tt.txt');

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
 	$result = json_decode($result);
	if($result->OrderStatus == 2){
		CModule::IncludeModule("sale");
        
        //находим и меняем свойство Заказ оплачен
        $arFields = array(
           "ORDER_ID" => $_REQUEST["ORDER_ID"],
           "ORDER_PROPS_ID" => 8,
           "NAME" => "Заказ оплачен",
           "CODE" => "PAYED",
           "VALUE" => "Y"
        );
         
        $db_vals = CSaleOrderPropsValue::GetList(
            array(),
            array(
                    'CODE' => 'PAYED',
                    'ORDER_ID' => $_REQUEST["ORDER_ID"],
                )
        );
        if($arOrderProps = $db_vals->Fetch())
        {
            CSaleOrderPropsValue::Update($arOrderProps['ID'],$arFields);  
        } else {
            CSaleOrderPropsValue::Add($arFields);
        }
        //Чисто обновление заказа, что бы он зарегестрировался для передачи 1с
        $arFields = array(
            //именно N, при Y заказы перестают "обновлятся из 1с" с ошибкой
            "PAYED" => "N",  //"Заказ №#ID# не может быть изменен (находится в финальном статусе, оплачен или разрешена доставка)."
        );
        CSaleOrder::Update($_REQUEST["ORDER_ID"], $arFields);

		// CEvent::Send("SALE_PAID");

		CModule::IncludeModule("sale");
		$rsOrder = CSaleOrder::GetList(array(), array("ID" => $_REQUEST["ORDER_ID"]));
		if($arOrder = $rsOrder->Fetch()){
			$arFields_order["USER"] = "Логин: ".$arOrder["USER_LOGIN"];

			$rsProps = CSaleOrderPropsValue::GetOrderProps($arOrder["ID"]);
			while($arProps = $rsProps->Fetch()){
				if($arProps["CODE"] == "PNAME")
					$arFields_order["USER"] .= "<br> ФИО: ".$arProps["VALUE"];
				if($arProps["CODE"] == "TELEFON")
					$arFields_order["PHONE"] = $arProps["VALUE"];
			}

			$arFields_order["ORDER_ID"] = $arOrder["ID"];
			$arFields_order["DESCRIPTION"] = $arOrder["USER_DESCRIPTION"];
			$arFields_order["ORDER_DATE"] = $arOrder["DATE_INSERT"];
			$arFields_order["EMAIL"] = $arOrder["USER_EMAIL"];
			$arFields_order["SALE_EMAIL"] = COption::GetOptionString("sale", "order_email", "order@".$SERVER_NAME);
			$arFields_order["PRICE"] = $arOrder["PRICE"];
			$arFields_order["PRICE_DELIVERY"] = $arOrder["PRICE_DELIVERY"];
			$arFields_order["BASKET"] = "<table>";
			$arFields_order["BASKET"] .= "<tr><td style='padding:3px 10px;'>Название</td><td style='padding:3px 10px;'>Количество</td><td style='padding:3px 10px;'>Цена</td></tr>";
			
			if($arOrder["USER_EMAIL"])
				$arFields_order["USER"] .= "<br> Email: ".$arOrder["USER_EMAIL"];

			$rsBasket = CSaleBasket::GetList(array(), array("ORDER_ID" => $arOrder["ID"]));
			while($arBasket = $rsBasket->Fetch()){
				$arFields_order["BASKET"] .= "<tr>";
				$arFields_order["BASKET"] .= "<td style='padding:3px 10px;'><a href='https://www.vamsvet.ru".$arBasket["DETAIL_PAGE_URL"]."'>".$arBasket["NAME"]."</a></td>";
				$arFields_order["BASKET"] .= "<td style='padding:3px 10px;'>".$arBasket["QUANTITY"]."</td>";
				$arFields_order["BASKET"] .= "<td style='padding:3px 10px;'>".$arBasket["PRICE"]*$arBasket["QUANTITY"]."</td>";
				$arFields_order["BASKET"] .= "</tr>";
			}
			$arFields_order["BASKET"] .= "</table>";
		}
		echo CEvent::Send("SALE_ORDER_PAID", SITE_ID, $arFields_order, "N");

		LocalRedirect("/sale/success.php?id=".$_REQUEST["ORDER_ID"]);
	}else{
		echo "error";
		LocalRedirect("/sale/error.php");
	}
}

curl_close($ch);
?>