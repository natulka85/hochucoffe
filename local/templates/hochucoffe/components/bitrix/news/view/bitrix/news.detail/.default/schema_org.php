<?
preg_match_all('/src=[\d\w\"\/\.]+/', $arResult['DETAIL_TEXT'], $output_array);
foreach ($output_array[0] as $img){
    $arImages[] = 'http://hochucoffe.ru'.preg_replace('/src="|\"/', '', $img);;
}

$datetime = new DateTime($arResult['DATE_ACTIVE_FROM']);
$date = $datetime->format(DateTime::ATOM);
$datechange = new DateTime($arResult['TIMESTAMP_X']);
$dateC = $datetime->format(DateTime::ATOM);
?>

<script type="application/ld+json">
    {
        "@context": "http://schema.org",
        "@type": "NewsArticle",
        "url": "http://hochucoffe.ru<?=$arResult['DETAIL_PAGE_URL']?>",
        "publisher":{
            "@type":"Organization",
            "name":"ХочуКофе",
            "logo": {
                  "@type": "ImageObject",
                  "url": "http://hochucoffe.ru/favicon.ico"
                },
            "url":"http://hochucoffe.ru/"
        },
        "author":{
            "@type":"Organization",
            "name":"ХочуКофе",
            "logo":"http://hochucoffe.ru/<?=SITE_TEMPLATE_PATH?>/static/dist/images/logo_correct.svg",
            "url":"http://hochucoffe.ru/"
        },
        "headline": "<?=htmlspecialchars($arResult['NAME'])?>",
        "articleBody": "<?=htmlspecialchars($arResult['PREVIEW_TEXT'])?>",
        <?if(count($arImages)>0):?>
        "image":[
            <?foreach ($arImages as $k=>$img):?>
                "<?=$img?>"
                <?if($k+1 != count($arImages)):?>,<?endif;?>
            <?endforeach;?>
            ],
        <?endif;?>
        "datePublished":"<?=$date?>",
         "dateModified":"<?=$dateC?>",
        "mainEntityOfPage":"http://hochucoffe.ru<?=$arResult['DETAIL_PAGE_URL']?>"
    }
</script>
