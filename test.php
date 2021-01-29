<?
use Bp\Template\Userstat;define('FOOTER_TYPE','type-1');
//require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
//$APPLICATION->SetTitle("");
//$APPLICATION->SetPageProperty("description","");
//$APPLICATION->SetPageProperty("keywords","");
?>

<?php
die();
global $BP_TEMPLATE;
echo "<pre>";
   print_r($_SESSION['bp_cache']['bp_user']['basket']);
echo "</pre>";
?>
<div class="diagram">
    <div class="img_1">
        <svg class="diagram__picture"
             xmlns="http://www.w3.org/2000/svg"
             xmlns:xlink="http://www.w3.org/1999/xlink"
             viewBox="0 0 300 300">
            <defs>
                <pattern id="image" x="0%" y="0%" height="100%" width="100%" viewBox="0 0 100% 100%">
                    <image x="0%" y="0%" width="100%" height="100%" xlink:href="/local/templates/hochucoffe/static/src/images/bg/coffe_cvet.png"></image>
                </pattern>
                <clipPath id="clip-path">
                    <circle cx="50%" cy="50%" r="50%"/>
                </clipPath>

                <mask id="mask">
                    <rect width="100%" height="100%" fill="#000"/>
                    <circle cx="50%" cy="50%" r="50%" fill="#fff" fill-opacity="0"/>
                    <circle id="circle-progress"
                            cx="52%" cy="52%" r="50%"
                            fill="none" stroke="#fff" stroke-width="100%"
                            stroke-dasharray="942.5" stroke-dashoffset="942.5"
                            transform="rotate(-90 150 150)"/>
                </mask>
            </defs>
            <circle id="sd" class="medium" cx="50%" cy="50%" r="50%" fill="url(#image)" stroke="none" stroke-width="0.5%"></circle>
            <g mask="url(#mask)" clip-path="url(#clip-path)">
                <image width="100%" height="100%"
                       preserveAspectRatio="xMidYMid slice"
                       xlink:href="/local/templates/hochucoffe/static/src/images/bg/coffe_chb.png"/>
            </g>
        </svg>
    </div>

</div>
<script>
    const percentText = document.querySelector('.diagram__percent');
    const circleProgress = document.querySelector('#circle-progress');
    const circleProgress_2 = document.querySelector('#circle-progress_2');

    function setPercent(perc) {
        const tl = circleProgress.getTotalLength();
        circleProgress.setAttribute('stroke-dashoffset', (1 - perc / 100) * tl);
    }
    setPercent(40);
</script>
<style>
    .diagram{
        width: 190px;
    }
    #banner {
        width: 150px;
        height: 150px;
        position: relative;
        //background: #000;
        border-radius: 50%;
        overflow: hidden;
    }
    .img_2{
        position: absolute;
        left: 0;
        top: 0;
    }


</style>


<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
