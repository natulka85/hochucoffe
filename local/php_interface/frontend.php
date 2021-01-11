<?php if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true)die();

/**
 * Тут описаны все js библиотеки, которые можно подключить через CJsCore
 */
 $cur_templ_path = '/local/templates/hochucoffe';

$arLibs = [

    /**
     * 'Название библиотеки' => array( // Стоит давать осмысленное название так, чтобы оно было понятно всем разработчикам
     *      'js'                => '', // Путь до библиотеки от корня сайта
     *      'css'               => '', // Путь до css файла библиотеки от корня сайта. Может быть массивом
     *      'lang'              => '', // Путь до обычного lang файла с php массивом, который будет транслирован в js
     *      'rel'               => '', // массив библиотек, от которых зависит данная библиотека
     *      'use'               => '', // CJSCore::USE_PUBLIC || CJSCore::USE_ADMIN,
     *      'skip_core'         => '', // отключает необходимость загрузки ядра JS битрикс
     *      'lang_additional'   => '', // Путь до дополнительного lang файла с php массивом, который будет транслирован в js
     * )
     */
    'lib' => [
        'js'   => $cur_templ_path.'/static/dist/js/lib/vendors.min.js',
        'rel'  => ['lib'],
    ],
    'mask' => [
        'js'   => $cur_templ_path.'/static/dist/js/vendors/maskedinput.min.js',
        'rel'  => ['lib'],
    ],
    'cookie' => [
        'js'   => $cur_templ_path.'/static/dist/js/vendors/jquery.cookie.min.js',
        'rel'  => ['lib'],
    ],
    'mango_slider' => [
        'js'   => $cur_templ_path.'/static/dist/js/vendors/mango_slider.min.js',
        'rel'  => ['lib'],
    ],
    'barba' => [
        'js'   => $cur_templ_path.'/static/dist/js/vendors/barna.min.js',
        'rel'  => ['lib'],
    ],
    'mainjs' => [
        'js'   => $cur_templ_path.'/static/dist/js/script.min.js',
        'rel'  => [
            //'jquery',
            //'jquery_ui',
            //'slick',
            //'fancy',
            'lib',
            'mask',
            //'touchSwipe',
            //'nicescroll',
            //'nouislider',
            'cookie',
            'actual',
            'mango_slider',
            'barba'
        ],
    ],
];
//if(!empty($_GET['crz'])) $arLibs['mainjs']['css'][0]="{$cur_templ_path}/static/css/global-no-adaptive.css";
//if(!empty($_GET['crz'])) {
//	header('Content-type:text/plain');
//	print_r($arLibs);
//	exit;
//}
foreach ($arLibs as $libName => $arLib)
{
    if (!isset($arLib['skip_core']))
    {
        $arLib['skip_core'] = true;
    }
    //Проверка на имя из ядра. Не будем давать подключать библиотеку с неправильным именем
    //чтобы имя всегда соответствовало ключу массива, иначе битрикс его подменит, сделав удаление всех неугодных ему символов
    if (!preg_match('~[a-z0-9_]+~', $libName))
    {
        throw new \Exception('Попытка зарегистрировать библиотеку с некорректным именем - "' . $libName . '"');
    }

    if (strlen($arLib['js']) === 0)
    {
        throw new \Exception('Попытка зарегистрировать библиотеку без js файла - "' . $libName . '"');
    }

    CJSCore::RegisterExt($libName, $arLib);
}
