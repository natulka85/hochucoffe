<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title></title>
</head>
<body>
<?
$params = base64_decode($_REQUEST['params']);
$arParams = explode('&',$params);
foreach($arParams as $param){
   $arP = explode('=',$param);
   $arParamsS[$arP[0]] = $arP[1];
}
?>
<div>
    <script>
        let script = document.createElement('script');
        script.src = "https://apis.google.com/js/platform.js?onload=renderOptIn";
        document.head.append(script);

        script.onload = function() {
            // в скрипте создаётся вспомогательная функция с именем "_"
            //alert("Загружено!"); // функция доступна
        };

        //console.log('testtets');
        window.renderOptIn = function() {
            window.gapi.load('surveyoptin', function() {
                window.gapi.surveyoptin.render(
                    {
                        // ОБЯЗАТЕЛЬНАЯ ЧАСТЬ
                        "merchant_id": "8696045",
                        "order_id": "<?=$arParamsS['order_id']?>",
                        "email": "<?=$arParamsS['email']?>",
                        "delivery_country": "ru",
                        "estimated_delivery_date": "<?=$arParamsS['date']?>",
                        //"opt_in_style": "CENTER_DIALOG"
                    });
            });
        }
    </script>
    <script>

        /*window.onbeforeunload = function() {
            console.log('sss');
            //$('#google-review').css('height', '0px');
        };*/

        script.src = "/local/templates/hochucoffe/static/js/vendors/jquery-ui.js";
        document.head.append(script);

        console.log('RRRRRR');

        var interval = '';
        interval = setInterval(function () {
            //console.log( "iframe onload", window.frames[0] );
            var iframeTag = document.body.children[0];

            var iframeWindow = iframeTag.contentWindow; // окно из тега

            console.log( frames[0] === iframeWindow ); // true, окно из коллекции frames
        },1000);

        setTimeout(function () {
            clearInterval(interval);
        },10000);


        /*setTimeout(function () {
            var iframe = document.getElementsByTagName('iframe')[0];

            // сработает
            document.getElementsByTagName('iframe')[0].contentWindow.window.onload = function() {
                console.log( "iframe onload" );
            };

            // не сработает
            document.getElementsByTagName('iframe')[0].contentWindow.onunload = function() {
                console.log( "contentWindow onunload" );
                document.getElementById('google-review').height('0');
            };
        },4000);*/



    </script>

</div>
</body>
</html>
