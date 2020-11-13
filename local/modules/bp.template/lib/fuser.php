<?php
namespace Bp\Template;

use Bitrix\Main\Entity;
use Bitrix\Main\Localization\Loc;
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

class FuserTable extends Entity\DataManager
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
        return array(
            'ID' => array(
                'data_type' => 'integer',
                'primary' => true,
                'autocomplete' => true,
                'title' => Loc::getMessage('DATA_ENTITY_ID_FIELD'),
            ),
            'FUSER_ID' => array(
                'data_type' => 'integer',
                'required' => true,
                'title' => Loc::getMessage('DATA_ENTITY_FUSER_ID_FIELD'),
            ),
            'TYPE' => array(
                'data_type' => 'string',
                'validation' => array(__CLASS__, 'validateType'),
                'title' => Loc::getMessage('DATA_ENTITY_TYPE_FIELD'),
            ),
            'DATA' => array(
                'data_type' => 'text',
                'title' => Loc::getMessage('DATA_ENTITY_DATA_FIELD'),
            ),
        );
    }
    /**
     * Returns validators for TYPE field.
     *
     * @return array
     */
    public static function validateType()
    {
        return array(
            new Entity\Validator\Length(null, 1),
        );
    }
}
