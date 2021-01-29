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

$app = Application::getInstance();
$context = $app->getContext();
$request = $context->getRequest();

$arPost = array();
$arPost = $request->getPostList()->toArray();
$arConsts = array();
$dbConst = SettingsTable::getList(array(
    'select' => array('ID','NAME','CODE','VALUE'),
    'filter' => array('CODE' => 'SEO_%')
));
while ($arConst = $dbConst->Fetch())
    $arConsts[$arConst['CODE']] = $arConst;

if ((!empty($save) || !empty($restore)) && $request->isPost() && check_bitrix_sessid()) {

    $arCONSTID = [];
    $isChanged = false;
    foreach($arPost['SEO'] as $key=>$value)
    {
        if($key!='')
        {
            foreach($value as $k=>$v)
            {
                if($v=='')
                    unset($value[$k]);
            }

            if($arConsts['SEO_'.$key]) //update
            {
                SettingsTable::update($arConsts['SEO_'.$key]['ID'],array(
                    'NAME' => $value,
                    'CODE' => 'SEO_'.$key,
                    'VALUE' => json_encode($value),
                ));
                $arConsts['SEO_'.$key]['NAME'] = $key;
                $arConsts['SEO_'.$key]['CODE'] = 'SEO_'.$key;
                $arConsts['SEO_'.$key]['VALUE'] = json_encode($value);
                $arCONSTID[] = $arConsts['SEO_'.$key]['ID'];
            }
            else  //add
            {
                $result = SettingsTable::add(array(
                    'NAME' => $key,
                    'CODE' => 'SEO_'.$key,
                    'VALUE' => json_encode($value),
                ));
                if ($result->isSuccess())
                {
                    $arCONSTID[] = $result->getId();
                    $arConsts['SEO_'.$key]['NAME'] = $key;
                    $arConsts['SEO_'.$key]['CODE'] = 'SEO_'.$key;
                    $arConsts['SEO_'.$key]['VALUE'] = json_encode($value);
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

require_once $_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_admin_after.php';

$tabControl = new CAdminTabControl("tabControl", array(
    array(
        "DIV" => "edit1",
        "TAB" => 'Переменные разделов',
        "TITLE" => 'Переменные разделов для шаблона',
    ),
    array(
        "DIV" => "edit2",
        "TAB" => 'Переменные Фильтра',
        "TITLE" => 'Переменные Фильтра для шаблона',
    ),
    array(
        "DIV" => "edit3",
        "TAB" => 'Страницы без текста',
        "TITLE" => 'Страницы без текста',
    ),
));
$tabControl->begin();
?>
<form method="post" action="<?=$request->getRequestUri()?>">
    <?php
    echo bitrix_sessid_post();
    $tabControl->beginNextTab();

    global $BP_TEMPLATE;

    $rsParentSection = CIBlockSection::GetByID($BP_TEMPLATE->getConstants()->IBLOCK_MAIN_TYPE);
    if ($arParentSection = $rsParentSection->GetNext())
    {
        $arSects[$arParentSection['XML_ID']] = [
            'NAME' => $arParentSection['NAME'],
            'ID' => $arParentSection['ID'],
            'SORT' => $arParentSection['SORT'],
            'ACTIVE' => $arParentSection['ACTIVE']
        ];
        $arFilter = array('IBLOCK_ID' => $arParentSection['IBLOCK_ID'],'>LEFT_MARGIN' => $arParentSection['LEFT_MARGIN'],'<RIGHT_MARGIN' => $arParentSection['RIGHT_MARGIN'],'>DEPTH_LEVEL' => $arParentSection['DEPTH_LEVEL']);
        $rsSect = CIBlockSection::GetList(array('left_margin' => 'asc'),$arFilter);
        while ($arSect = $rsSect->GetNext())
        {
            if($arSect['SORT']<600)
            {
                $arSects[$arSect['XML_ID']] = [
                    'NAME' => $arSect['NAME'],
                    'ID' => $arSect['ID'],
                    'SORT' => $arSect['SORT'],
                    'ACTIVE' => $arSect['ACTIVE']
                ];
            }
        }
    }
    //pre($arSects);
    ?>
    <tr><td>
        <table>
            <tr><td>Название раздела</td><td>secname</td><td>sec_search_tags</td><td>rod</td></tr>
            <?
            $arSecname = json_decode($arConsts['SEO_secname']['VALUE'],TRUE);
            $arSecname_lonely = json_decode($arConsts['SEO_sec_search_tags']['VALUE'],TRUE);
            $arSecname_Rod = json_decode($arConsts['SEO_sec_rod']['VALUE'],TRUE);

            foreach($arSects as $xml_id=>$arSect):?>
            <?echo "<pre>";
               print_r($arSect);
            echo "</pre>";?>
                <tr  <?if($arSect['SORT']>=500 || $arSect['ACTIVE']!='Y'):?>style="background: #e0dfdf;opacity: 0.5;"<?endif;?>>
                    <td><?=$arSect['NAME']?>(<?=$arSect['ID']?>)</td>
                    <td><input type="text" name="SEO[secname][<?=$arSect['ID']?>]" value="<?=$arSecname[$arSect['ID']]?>" /></td>
                    <td><input type="text" name="SEO[sec_search_tags][<?=$arSect['ID']?>]" value="<?=$arSecname_lonely[$arSect['ID']]?>" /></td>
                    <td><input type="text" name="SEO[sec_rod][<?=$arSect['ID']?>]" value="<?=$arSecname_Rod[$arSect['ID']]?>" /></td>
                </tr>
            <?endforeach?>
        </table>
    </td></tr>

    <?$tabControl->beginNextTab();

    global $BP_TEMPLATE;
    $arSects = [];
    foreach(CIBlockSectionPropertyLink::GetArray(1) as $PID => $arLink)
    {
        if($arLink["SMART_FILTER"] !== "Y")
            continue;
        $rsProperty = CIBlockProperty::GetByID($PID);
        $arProperty = $rsProperty->Fetch();
        if($arProperty)
        {
          /*  if($arProperty['ID'] == 63){//акция
                $arSects[] = [
                    'NAME' => $arProperty["NAME"].' - '.$enum_fields["VALUE"],
                    'ID' => $enum_fields["XML_ID"],
                ];
            }*/

            $arVals = array();
            $property_enums = CIBlockPropertyEnum::GetList(
                Array("DEF"=>"DESC", "SORT"=>"ASC"),
                Array("IBLOCK_ID"=>$arProperty["IBLOCK_ID"], "CODE"=>$arProperty["CODE"])
            );
            while($enum_fields = $property_enums->GetNext())
            {
                $arVals[$enum_fields['XML_ID']] = $enum_fields['VALUE'];
                $arSects[] = [
                    'NAME' => $arProperty["NAME"].' - '.$enum_fields["VALUE"],
                    'ID' => $enum_fields["XML_ID"],
                ];
            }
        }
    }
    ?>
    <tr><td>
        <table>
            <tr><td>Название Свойства</td><td>x</td><td>y</td><td>x_alt</td><td>y_alt</td><td>y_alt1</td><td>props_tags</td></tr>
            <!--<input type="hidden" name="save" value="Сохранить">-->
            <?
            $arPropsX = json_decode($arConsts['SEO_props_x']['VALUE'],TRUE);
            $arPropsY = json_decode($arConsts['SEO_props_y']['VALUE'],TRUE);
            $arPropsXAlt = json_decode($arConsts['SEO_props_xalt']['VALUE'],TRUE);
            $arPropsYAlt = json_decode($arConsts['SEO_props_yalt']['VALUE'],TRUE);
            $arPropsYAlt1 = json_decode($arConsts['SEO_props_yalt1']['VALUE'],TRUE);
            $arPropsTags = json_decode($arConsts['SEO_props_tags']['VALUE'],TRUE);

            usort($arSects, function($b1,$b2){
                    if(trim($b1['NAME'])==trim($b2['NAME'])){
                        return 0;
                    }
                    else
                        return (trim($b1['NAME'])>trim($b2['NAME'])) ? 1 : -1;
                });

            foreach($arSects as $xml_id=>$arSect):?>
                <?
                if(!$sec_name)
                    $sec_name = '';
                $sec_name2 = explode(' - ',$arSect['NAME']);
                $name =  $sec_name2[1];

                if($sec_name!=$sec_name2[0]):?>
                    <?$sec_name=$sec_name2[0];?>
                    <tr><td colspan="7"><h2><?=$sec_name?></h2></td></tr>
                <?endif?>
                <tr>
                    <td><?=$name?><br><span style="font-size: 8px;">(<?=$arSect['ID']?>)</span></td>
                    <td><input type="text" name="SEO[props_x][<?=$arSect['ID']?>]" value="<?=$arPropsX[$arSect['ID']]?>" /></td>
                    <td><input type="text" name="SEO[props_y][<?=$arSect['ID']?>]" value="<?=$arPropsY[$arSect['ID']]?>" /></td>
                    <td><input type="text" name="SEO[props_xalt][<?=$arSect['ID']?>]" value="<?=$arPropsXAlt[$arSect['ID']]?>" /></td>
                    <td><input type="text" name="SEO[props_yalt][<?=$arSect['ID']?>]" value="<?=$arPropsYAlt[$arSect['ID']]?>" /></td>
                    <td><input type="text" name="SEO[props_yalt1][<?=$arSect['ID']?>]" value="<?=$arPropsYAlt1[$arSect['ID']]?>" /></td>
                    <td><input type="text" name="SEO[props_tags][<?=$arSect['ID']?>]" value="<?=$arPropsTags[$arSect['ID']]?>" /></td>
                </tr>
            <?endforeach/*?>
            <tr><td colspan="7"><h2>Степень защиты, IP(СТАТИЧНО)</h2></td></tr>
            <?$arIPMatrix=array(10,20,21,22,23,24,30,33,40,41,42,43,44,52,53,54,55,56,63,64,65,66,67,68);
            foreach($arIPMatrix as $IP):?>
            <tr>
                <td><?=$IP?>:</td>
                <td></td>
                <td>
                    <input type="text" name="SEO[props_y][IP<?=$IP?>]" value="<?=$arPropsY['IP'.$IP]?>">
                </td>
                <td></td>
                <td>
                    <input type="text" name="SEO[props_yalt][IP<?=$IP?>]" value="<?=$arPropsYAlt['IP'.$IP]?>">
                </td>
                <td></td>
            </tr>
            <?endforeach;*/?>
        </table>
    </td></tr>
    <?$tabControl->BeginNextTab();?>
    <tr><td>
        <table>
            <tr>
                <td>

                </td>
            </tr>
            <?$arTextOff = json_decode($arConsts['SEO_text_off']['VALUE'],TRUE);
            foreach($arTextOff as $k=>$v)
            {
                if($v=='')
                    unset($arTextOff[$k]);
            }?>
            <?foreach($arTextOff as $k=>$v):?>
                <tr>
                    <td><input type="text" name="SEO[text_off][<?=$k?>]" value="<?=$v?>" /></td>
                </tr>
            <?endforeach?>
            <?for ($i = 1; $i <= 5; $i++):?>
            <tr>
                <td><input type="text" name="SEO[text_off][<?=count($arTextOff)+$i?>]" value="" /></td>
            </tr>
            <?endfor?>
        </table>
    </td></tr>
    <?
    $tabControl->Buttons();
    ?>
    <input type="submit"
           name="save"
           value="<?=Loc::getMessage("MAIN_SAVE") ?>"
           title="<?=Loc::getMessage("MAIN_OPT_SAVE_TITLE") ?>"
           class="adm-btn-save"
    />
    <?php
    $tabControl->End();
    ?>
</form>
