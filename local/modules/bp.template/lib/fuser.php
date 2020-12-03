<?php
namespace Bp\Template;

use Bitrix\Main\Localization\Loc,
    Bitrix\Main\ORM\Data\DataManager,
    Bitrix\Main\ORM\Fields\DatetimeField,
    Bitrix\Main\ORM\Fields\IntegerField,
    Bitrix\Main\ORM\Fields\StringField,
    Bitrix\Main\ORM\Fields\TextField,
    Bitrix\Main\ORM\Fields\Validators\LengthValidator,
    Bitrix\Main\Type\DateTime;

Loc::loadMessages(__FILE__);

/**
 * Class DataTable
 *
 * Fields:
 * <ul>
 * <li> ID int mandatory
 * <li> FUSER_ID int mandatory
 * <li> TYPE string(1) optional
 * <li> DATA string optional
 * </ul>
 *
 * @package Bitrix\Fuser
 **/

class FuserTable extends DataManager
{
    /**
     * Returns DB table name for entity.
     *
     * @return string
     */
    public static function getTableName()
    {
        return 'bp_fuser_data';
    }

    /**
     * Returns entity map definition.
     *
     * @return array
     */
    public static function getMap()
    {
        return [
            new IntegerField(
                'ID',
                [
                    'primary' => true,
                    'autocomplete' => true,
                    'title' => Loc::getMessage('DATA_ENTITY_ID_FIELD')
                ]
            ),
            new DatetimeField(
                'DATE',
                [
                    'default' => function()
                    {
                        return new DateTime();
                    },
                    'title' => Loc::getMessage('DATA_ENTITY_DATE_FIELD')
                ]
            ),
            new IntegerField(
                'FUSER_ID',
                [
                    'required' => true,
                    'title' => Loc::getMessage('DATA_ENTITY_FUSER_ID_FIELD')
                ]
            ),
            new StringField(
                'TYPE',
                [
                    'validation' => [__CLASS__, 'validateType'],
                    'title' => Loc::getMessage('DATA_ENTITY_TYPE_FIELD')
                ]
            ),
            new TextField(
                'DATA',
                [
                    'title' => Loc::getMessage('DATA_ENTITY_DATA_FIELD')
                ]
            ),
        ];
    }
    /**
     * Returns validators for TYPE field.
     *
     * @return array
     */
    public static function validateType()
    {
        return [
            new LengthValidator(null, 1),
        ];
    }
}
