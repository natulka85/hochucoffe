<?php
namespace Bp\Template\Tools\Services;

use Bitrix\Main,
    Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);

/**
 * Class EventTable
 * 
 * Fields:
 * <ul>
 * <li> ID int mandatory
 * <li> ACTIVE bool optional default 'Y'
 * <li> NAME string(255) optional
 * <li> EVENT_ID int mandatory
 * <li> SERVICE_ID int mandatory
 * <li> DESCRIPTION string optional
 * </ul>
 *
 * @package Bitrix\Service
 **/

class EventTable extends Main\Entity\DataManager
{
    /**
     * Returns DB table name for entity.
     *
     * @return string
     */
    public static function getTableName()
    {
        return 'bp_service_event';
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
                'title' => Loc::getMessage('EVENT_ENTITY_ID_FIELD'),
            ),
            'ACTIVE' => array(
                'data_type' => 'boolean',
                'values' => array('N', 'Y'),
                'title' => Loc::getMessage('EVENT_ENTITY_ACTIVE_FIELD'),
            ),
            'NAME' => array(
                'data_type' => 'string',
                'validation' => array(__CLASS__, 'validateName'),
                'title' => Loc::getMessage('EVENT_ENTITY_NAME_FIELD'),
            ),
            'EVENT_ID' => array(
                'data_type' => 'integer',
                'required' => true,
                'title' => Loc::getMessage('EVENT_ENTITY_EVENT_ID_FIELD'),
            ),
            new Main\Entity\ReferenceField(
                'EVENT',
                'Bp\Template\Tools\Services\Eventlist',
                array('=this.EVENT_ID' => 'ref.ID'),
                array('join_type' => 'LEFT')
            ),
            'SERVICE_ID' => array(
                'data_type' => 'integer',
                'required' => true,
                'title' => Loc::getMessage('EVENT_ENTITY_SERVICE_ID_FIELD'),
            ),
            new Main\Entity\ReferenceField(
                'SERVICE',
                'Bp\Template\Tools\Services\System',
                array('=this.SERVICE_ID' => 'ref.ID'),
                array('join_type' => 'LEFT')
            ),
            'FUNCTION' => array(
                'data_type' => 'text',
                'title' => Loc::getMessage('EVENT_ENTITY_DESCRIPTION_FIELD'),
            ),
            'DESCRIPTION' => array(
                'data_type' => 'text',
                'title' => Loc::getMessage('EVENT_ENTITY_DESCRIPTION_FIELD'),
            ),
        );
    }
    /**
     * Returns validators for NAME field.
     *
     * @return array
     */
    public static function validateName()
    {
        return array(
            new Main\Entity\Validator\Length(null, 255),
        );
    }
}