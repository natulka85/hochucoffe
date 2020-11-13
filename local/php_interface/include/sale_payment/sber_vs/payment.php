<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?>
<FORM ACTION="" METHOD="POST" id="submitPayment" name="payment_form">
<INPUT TYPE="HIDDEN" NAME="orderNumber" VALUE="<?=$arResult["ORDER_ID"]?>">
<INPUT TYPE="HIDDEN" NAME="amount" VALUE="<?=floor($arResult['price'])?>">   <?//была ошибка с неверной суммой при дробной части?>
<INPUT TYPE="SUBMIT" NAME="Submit" VALUE="Оплатить">
</FORM>
<script>
	$('#submitPayment').submit(function(){
		var data = $(this).serializeArray();
		$.ajax({
			url: '/local/php_interface/include/sale_payment/sber_vs/post.php',
			method: 'post',
			data: data,
			dataType: 'json',
			success: function(data){
				if(data.formUrl){
					window.location.href = data.formUrl;
				}
				console.log(data);
			},
			error: function(data){
				console.log(data);
			}
		});

		return false;
	});
</script>