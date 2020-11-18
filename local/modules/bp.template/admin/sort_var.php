<?php
use Bitrix\Main\Application;
use Bitrix\Main\Loader;
use Bitrix\Main\Config\Option;
use Bitrix\Main\Localization\Loc;
use Bp\Template\SettingsTable;
use Bitrix\Main\Data\Cache;

require_once $_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_admin_before.php';

CJSCore::Init(array('fx','jquery'));

Loc::loadMessages(__FILE__);

if(!Loader::includeModule('bp.template'))
    CAdminMessage::showMessage(Loc::getMessage("REFERENCES_INVALID_VALUE"));

global $BP_TEMPLATE;

$app = Application::getInstance();
$context = $app->getContext();
$request = $context->getRequest();

$arPost = array();
$arPost = $request->getPostList()->toArray();

$arConsts = array();
$dbConst = SettingsTable::getList(array(
    'select' => array('ID','NAME','CODE','VALUE'),
    'filter' => array('CODE' => 'VAR_%')
));
while ($arConst = $dbConst->Fetch())
    $arConsts[$arConst['CODE']] = $arConst;

if ((!empty($save) || !empty($restore)) && $request->isPost() && check_bitrix_sessid()) {
    $arCONSTID = [];
    $isChanged = false;
    foreach($arPost['VAR'] as $key=>$value)
    {
        if($key!='')
        {
            foreach($value as $k=>$v)
            {
                if($v=='')
                    unset($value[$k]);
            }

            if($arConsts['VAR_'.$key]) //update
            {
                SettingsTable::update($arConsts['VAR_'.$key]['ID'],array(
                    'NAME' => $value,
                    'CODE' => 'VAR_'.$key,
                    'VALUE' => json_encode($value),
                ));
                $arConsts['VAR_'.$key]['NAME'] = $key;
                $arConsts['VAR_'.$key]['CODE'] = 'VAR_'.$key;
                $arConsts['VAR_'.$key]['VALUE'] = json_encode($value);
                $arCONSTID[] = $arConsts['VAR_'.$key]['ID'];
            }
            else  //add
            {
                $result = SettingsTable::add(array(
                    'NAME' => $key,
                    'CODE' => 'VAR_'.$key,
                    'VALUE' => json_encode($value),
                ));
                if ($result->isSuccess())
                {
                    $arCONSTID[] = $result->getId();
                    $arConsts['VAR_'.$key]['NAME'] = $key;
                    $arConsts['VAR_'.$key]['CODE'] = 'VAR_'.$key;
                    $arConsts['VAR_'.$key]['VALUE'] = json_encode($value);
                } else {
                    pre($result);
                }
            }
            $isChanged = true;
        }
    }

    if($isChanged)
    {
        $сache = Cache::createInstance();
        $сache->clean('bp_template_consts', '/hochucoffe');
    }
}

//pre($arPost);
require_once $_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_admin_after.php';

echo "<h1>Варианты и направления сортировок</h1>";


//тянем Варианты сортировок
$arSortVariants =  $BP_TEMPLATE->Catalog()->GetSortVariants();

//тянем инфоблоки типа catalog
$arIBlocks = [];
$dbElement = \Bitrix\Iblock\IblockTable::getList(array(
    'select' => [
        'ID',
        'NAME',
    ],
    'filter' => [
        'IBLOCK_TYPE_ID' => ['catalog','content']
    ],
));
$cc = 0;
$arIBlocks = [];
while($arElement = $dbElement->Fetch())
{
    $arIBlocks[] = [
        "ID" => $arElement['ID'],
        "DIV" => "edit".$arElement['ID'],
        "TAB" => $arElement['NAME'],
        "TITLE" => $arElement['NAME'],
    ];
}

$tabControl = new CAdminTabControl("tabControl", $arIBlocks);
$tabControl->begin();
?>
<form method="post" action="<?=$request->getRequestUri()?>">
    <?php
    echo bitrix_sessid_post();
    global $BP_TEMPLATE;
    foreach($arIBlocks as $arIblock)
    {
        $tabControl->BeginNextTab();

        $arData = json_decode($arConsts['VAR_'.$arIblock['ID']]['VALUE'],true);
        ?>

        <tr>
            <td>
            <h3>Типы страниц</h3>
            <table>
                <tr>
                    <td>Название</td>
                    <td>Код - Латиницей</td>
                </tr>
                <?foreach($arData['type_code'] as $k=>$v):?>
                    <?if($v!=''):?>
                    <tr>
                        <td><input type="text" size="20"  name="VAR[<?=$arIblock['ID']?>][type_name][<?=$k?>]" value="<?=$arData['type_name'][$k]?>" /></td>
                        <td><input type="text" size="40"  name="VAR[<?=$arIblock['ID']?>][type_code][<?=$k?>]" value="<?=$v?>" /></td>
                    </tr>
                    <?endif?>
                <?endforeach?>
                <tr>
                    <td><input type="text" size="20"  name="VAR[<?=$arIblock['ID']?>][type_name][]" value="" /></td>
                    <td><input type="text" size="40"  name="VAR[<?=$arIblock['ID']?>][type_code][]" value="" /></td>
                </tr>
            </table>
            <h3>Варианты и направления</h3>
            <?foreach($arData['type_code'] as $k=>$v):?>
                <?if($v!=''):?>
                    <h4><?=$arData['type_name'][$k]?></h4>
                    <table>
                        <tr>
                            <td>CODE</td>
                            <td>Название</td>
                            <td>Свойство</td>
                            <td>Направление</td>
                            <td>Классы</td>
                        </tr>
                        <?foreach($arSortVariants as $variant):?>
                        <tr>
                            <td><?=$variant?></td>
                            <td><input type="text" size="40"  name="VAR[<?=$arIblock['ID']?>][<?=$variant?>][<?=$k?>][name]" value="<?=$arData[$variant][$k]['name']?>" /></td>
                            <td><input type="text" size="30"  name="VAR[<?=$arIblock['ID']?>][<?=$variant?>][<?=$k?>][prop]" value="<?=$arData[$variant][$k]['prop']?>" /></td>
                            <td><input type="text" size="20"  name="VAR[<?=$arIblock['ID']?>][<?=$variant?>][<?=$k?>][order]" value="<?=$arData[$variant][$k]['order']?>" /></td>
                            <td><input type="text" size="20"  name="VAR[<?=$arIblock['ID']?>][<?=$variant?>][<?=$k?>][class]" value="<?=$arData[$variant][$k]['class']?>" /></td>
                        </tr>
                        <?endforeach?>
                    </table>
                <?endif?>
            <?endforeach?>
        </td></tr>
        <?
    }
    $tabControl->buttons();
    ?>
    <input type="submit"
           name="save"
           value="<?=Loc::getMessage("MAIN_SAVE") ?>"
           title="<?=Loc::getMessage("MAIN_OPT_SAVE_TITLE") ?>"
           class="adm-btn-save"
           />
    <?php
    $tabControl->end();
    ?>
</form>
<?=CAdminMessage::ShowMessage(["MESSAGE"=>'Для любого инфоблока типа Catalog можно задать любой Типы страниц, главное потом на нужной странице и  при нужных условиях запихнуть его в $BP_TEMPLATE->Catalog()->getSortList($iblock_id = 1, $type = "") и getCurSort($code = "popul", $iblock_id = 1, $type = "") как переменную $type', "TYPE"=>"OK"]);?>
