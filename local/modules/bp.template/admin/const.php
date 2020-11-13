<?php
use Bitrix\Main\Application;
use Bitrix\Main\Loader;
use Bitrix\Main\Config\Option;
use Bitrix\Main\Localization\Loc;
use Bp\Template\SettingsTable;
use Bitrix\Main\Data\Cache;

require_once $_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_admin_before.php';

Loc::loadMessages(__FILE__);

if(!Loader::includeModule('bp.template'))
    CAdminMessage::showMessage(Loc::getMessage("REFERENCES_INVALID_VALUE"));

\CJSCore::Init(array("jquery"));
$APPLICATION->AddHeadScript('/local/templates/hochucoffe/static/js/vendors/maskedinput.js');
$APPLICATION->AddHeadScript('/local/admin/script.js');

$app = Application::getInstance();
$context = $app->getContext();
$request = $context->getRequest();

$arPost = array();
$arPost = $request->getPostList()->toArray();

$arConsts = array();
$dbConst = SettingsTable::getList(array(
    'select' => array('ID','NAME','CODE','VALUE'),
    'filter' => array('CODE' => 'CONST_%')
));
while ($arConst = $dbConst->Fetch()){
    $arConsts[$arConst['CODE']] = $arConst;
}

if ((!empty($save) || !empty($restore)) && $request->isPost() && check_bitrix_sessid()) {
    $arCONSTID = [];
    $isChanged = false;
    foreach ($arPost['CONST_NAME'] as $key => $value) {

        if (
            $value != ''
            && $arPost['CONST_CODE'][$key] != ''
            //&& $arPost['CONST_VALUE'][$key]!=''
        ) {
            if ($arConsts['CONST_' . $arPost['CONST_CODE'][$key]]) //update
            {
                if ($arPost['CONST_VALUE'][$key] == '') {
                    //pre($arConsts['CONST_'.$arPost['CONST_CODE'][$key]]['ID']);
                    SettingsTable::delete($arConsts['CONST_' . $arPost['CONST_CODE'][$key]]['ID']);
                    unset($arConsts['CONST_' . $arPost['CONST_CODE'][$key]]);
                } else {
                    SettingsTable::update($arConsts['CONST_' . $arPost['CONST_CODE'][$key]]['ID'], array(
                        'NAME' => $value,
                        'CODE' => 'CONST_' . $arPost['CONST_CODE'][$key],
                        'VALUE' => $arPost['CONST_VALUE'][$key],
                    ));
                    $arConsts['CONST_' . $arPost['CONST_CODE'][$key]]['NAME'] = $value;
                    $arConsts['CONST_' . $arPost['CONST_CODE'][$key]]['CODE'] = 'CONST_' . $arPost['CONST_CODE'][$key];
                    $arConsts['CONST_' . $arPost['CONST_CODE'][$key]]['VALUE'] = $arPost['CONST_VALUE'][$key];
                    $arCONSTID[] = $arConsts['CONST_' . $arPost['CONST_CODE'][$key]]['ID'];
                }
            } else  //add
            {
                $result = SettingsTable::add(array(
                    'NAME' => $value,
                    'CODE' => 'CONST_' . $arPost['CONST_CODE'][$key],
                    'VALUE' => $arPost['CONST_VALUE'][$key],
                ));
                if ($result->isSuccess()) {
                    $arCONSTID[] = $result->getId();
                    $arConsts['CONST_' . $arPost['CONST_CODE'][$key]]['NAME'] = $value;
                    $arConsts['CONST_' . $arPost['CONST_CODE'][$key]]['CODE'] = 'CONST_' . $arPost['CONST_CODE'][$key];
                    $arConsts['CONST_' . $arPost['CONST_CODE'][$key]]['VALUE'] = $arPost['CONST_VALUE'][$key];
                }
            }
            $isChanged = true;
        }
    }

    foreach ($arPost as $key => $val) {
        if (strpos($key, 'options') !== false) {
            if ($val != '') {

                if ($arConsts[$key]) //update
                {
                    if ($val == '') {
                        SettingsTable::delete($arConsts[$key]['ID']);

                    } else {

                        $value = json_encode($val);
                        SettingsTable::update($arConsts[$key]['ID'], array(
                            'CODE' => $key,
                            'VALUE' => $value,
                        ));
                        $arConsts[$key]['NAME'] = '';
                        $arConsts[$key]['CODE'] = $key;
                        $arConsts[$key]['VALUE'] = $value;
                    }
                } else  //add
                {
                    $value = json_encode($val);

                    $result = SettingsTable::add(array(
                        'CODE' => $key,
                        'VALUE' => $value,
                    ));
                }

                unset($arConsts[$key]);

                $isChanged = true;
            }
        }

    }
        if ($isChanged) {
            $сache = Cache::createInstance();
            $сache->clean('bp_template_consts', '/hochucoffe');
        }
        /*foreach($arConsts as $key=>$const)
        {
            if(!in_array($const['ID'],$arCONSTID))
            {
                SettingsTable::delete($const['ID']);
                unset($arConsts[$key]);
            }
        }*/

}

require_once $_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_admin_after.php';

echo '<h1>'.Loc::getMessage('BP_CONST_TITLE').'</h1>';

$tabControl = new CAdminTabControl("tabControl", array(
    array(
        "DIV" => "edit1",
        "TAB" => 'Инфоблоки',
        "TITLE" => 'Инфоблоки каталогов',
    ),
    array(
        "DIV" => "edit2",
        "TAB" => 'Статусы и кнопки',
        "TITLE" => 'Статусы товара и названия кнопок',
    ),
    array(
        "DIV" => "edit3",
        "TAB" => 'Общие фразы',
        "TITLE" => 'Фразы и надписи на сайте',
    ),
    array(
        "DIV" => "edit4",
        "TAB" => 'Другие константы',
        "TITLE" => 'Константы сайта',
    ),
    array(
        "DIV" => "edit5",
        "TAB" => 'Системные сообщения',
        "TITLE" => 'Системные сообщения',
    ),
));
$tabControl->begin();
?>

<form method="post" action="<?=$request->getRequestUri()?>">
    <?php
    echo bitrix_sessid_post();
    $tabControl->beginNextTab();
    $tab_md5 = md5($tabControl->tabs[$tabControl->tabIndex-1]['DIV']);
    ?>
    <tr><td>
        <table id="<?=$tab_md5?>">
            <tr><td>Название</td><td>Code</td><td>Значение</td></tr>
            <?foreach($arConsts as $const):?>
                <?if(strpos($const['CODE'],'CONST_IBLOCK_')!==false):?>
                <tr>
                    <td><input type="text" name="CONST_NAME[n<?=$const['ID']?>]" value="<?=$const['NAME']?>" /></td>
                    <td><input readonly type="text" name="CONST_CODE[n<?=$const['ID']?>]" value="<?=str_replace('CONST_','', $const['CODE'])?>" /></td>
                    <td><input type="text" name="CONST_VALUE[n<?=$const['ID']?>]" value="<?=$const['VALUE']?>" /></td>
                </tr>
                <?endif?>
            <?endforeach?>
            <tr>
                <td><input type="text" name="CONST_NAME[n<?=($const['ID']+1)?>]" value="" /></td>
                <td><input type="text" name="CONST_CODE[n<?=($const['ID']+1)?>]" value="IBLOCK_" /></td>
                <td><input type="text" name="CONST_VALUE[n<?=($const['ID']+1)?>]" value="" /></td>
            </tr>
            <tr>
                <td colspan="3" align="center">
                    <input type="button" value="Еще..." onclick="addNewRow('<?=$tab_md5?>');" />
                </td>
            </tr>
        </table>
    </td></tr>
    <?
    $tabControl->BeginNextTab();
    $tab_md5 = md5($tabControl->tabs[$tabControl->tabIndex-1]['DIV']);
    ?>
    <tr><td>
        <table id="<?=$tab_md5?>">
            <tr><td>Название</td><td>Code</td><td>Значение</td></tr>
            <?foreach($arConsts as $const):?>
                <?if(strpos($const['CODE'],'CONST_STATE_')!==false):?>
                <tr>
                    <td><input type="text" name="CONST_NAME[n<?=$const['ID']?>]" value="<?=$const['NAME']?>" /></td>
                    <td><input readonly type="text" name="CONST_CODE[n<?=$const['ID']?>]" value="<?=str_replace('CONST_','', $const['CODE'])?>" /></td>
                    <td><input type="text" name="CONST_VALUE[n<?=$const['ID']?>]" value="<?=$const['VALUE']?>" /></td>
                </tr>
                <?endif?>
            <?endforeach?>
            <tr>
                <td><input type="text" name="CONST_NAME[n<?=($const['ID']+100)?>]" value="" /></td>
                <td><input type="text" name="CONST_CODE[n<?=($const['ID']+100)?>]" value="STATE_" /></td>
                <td><input type="text" name="CONST_VALUE[n<?=($const['ID']+100)?>]" value="" /></td>
            </tr>
            <tr>
                <td colspan="3" align="center">
                    <input type="button" value="Еще..." onclick="addNewRow('<?=$tab_md5?>');" />
                </td>
            </tr>
        </table>
    </td></tr>
    <?
    $tabControl->BeginNextTab();
    $tab_md5 = md5($tabControl->tabs[$tabControl->tabIndex-1]['DIV']);
    ?>
    <tr><td>
        <table id="<?=$tab_md5?>">
            <tr><td>Название</td><td>Code</td><td>Значение</td></tr>
            <?foreach($arConsts as $const):?>
                <?if(strpos($const['CODE'],'CONST_TEXT_')!==false):?>
                <tr>
                    <td><input type="text" name="CONST_NAME[n<?=$const['ID']?>]" value="<?=$const['NAME']?>" /></td>
                    <td><input readonly type="text" name="CONST_CODE[n<?=$const['ID']?>]" value="<?=str_replace('CONST_','', $const['CODE'])?>" /></td>
                    <td><input type="text" name="CONST_VALUE[n<?=$const['ID']?>]" value="<?=$const['VALUE']?>" /></td>
                </tr>
                <?endif?>
            <?endforeach?>
            <tr>
                <td><input type="text" name="CONST_NAME[n<?=($const['ID']+200)?>]" value="" /></td>
                <td><input type="text" name="CONST_CODE[n<?=($const['ID']+200)?>]" value="TEXT_" /></td>
                <td><input type="text" name="CONST_VALUE[n<?=($const['ID']+200)?>]" value="" /></td>
            </tr>
            <tr>
                <td colspan="3" align="center">
                    <input type="button" value="Еще..." onclick="addNewRow('<?=$tab_md5?>');" />
                </td>
            </tr>
        </table>
    </td></tr>
    <?
    $tabControl->BeginNextTab();
    $tab_md5 = md5($tabControl->tabs[$tabControl->tabIndex-1]['DIV']);
    ?>
    <tr><td>
        <table id="<?=$tab_md5?>">
            <tr><td>Название</td><td>Code</td><td>Значение</td></tr>
            <?foreach($arConsts as $const):?>
                <?if(
                    strpos($const['CODE'],'CONST_IBLOCK_')===false
                    && strpos($const['CODE'],'CONST_STATE_')===false
                    && strpos($const['CODE'],'CONST_TEXT_')===false
                ):?>
                <tr>
                    <td><input type="text" name="CONST_NAME[n<?=$const['ID']?>]" value="<?=$const['NAME']?>" /></td>
                    <td><input readonly type="text" name="CONST_CODE[n<?=$const['ID']?>]" value="<?=str_replace('CONST_','', $const['CODE'])?>" /></td>
                    <td><input type="text" name="CONST_VALUE[n<?=$const['ID']?>]" value="<?=$const['VALUE']?>" /></td>
                </tr>
                <?endif?>
            <?endforeach?>
            <tr>
                <td><input type="text" name="CONST_NAME[n<?=($const['ID']+300)?>]" value="" /></td>
                <td><input type="text" name="CONST_CODE[n<?=($const['ID']+300)?>]" value="" /></td>
                <td><input type="text" name="CONST_VALUE[n<?=($const['ID']+300)?>]" value="" /></td>
            </tr>
            <tr>
                <td colspan="3" align="center">
                    <input type="button" value="Еще..." onclick="addNewRow('<?=$tab_md5?>');" />
                </td>
            </tr>
        </table>
    </td></tr>
    <?$tabControl->BeginNextTab();?>
    <tr>
        <td>
            <? include_once($_SERVER['DOCUMENT_ROOT'] . '/local/admin/ajax/system_mes.php') ?>
        </td>
    </tr>
    <?
    $tabControl->buttons();
    ?>
    <input type="submit"
           name="save"banner-settings.php
           value="<?=Loc::getMessage("MAIN_SAVE") ?>"
           title="<?=Loc::getMessage("MAIN_OPT_SAVE_TITLE") ?>"
           class="adm-btn-save"
           />
    <?php
    $tabControl->end();
    ?>
</form>
<script language="JavaScript">
<!--
function addNewRow(tableID)
{
    var tbl = document.getElementById(tableID);
    var cnt = tbl.rows.length;
    var oRow = tbl.insertRow(cnt-1);
    var sHTML=tbl.rows[cnt-2].innerHTML;
    console.log(sHTML);
    var p = 0;
    while(true)
    {
        var s = sHTML.indexOf('[n',p);
        if(s<0)break;
        var e = sHTML.indexOf(']',s);
        if(e<0)break;
        var n = parseInt(sHTML.substr(s+2,e-s));
        sHTML = sHTML.substr(0, s)+'[n'+(++n)+']'+sHTML.substr(e+1);
        p=s+1;
    }

    oRow.innerHTML = sHTML;


    var patt = new RegExp ("<"+"script"+">[^\000]*?<"+"\/"+"script"+">", "g");
    var code = sHTML.match(patt);
    if(code)
    {
        for(var i = 0; i < code.length; i++)
            if(code[i] != '')
                jsUtils.EvalGlobal(code[i]);
    }
}
//-->
</script>
<?require_once $_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/epilog_admin.php';
