<?php
namespace Bp\Template\Tools\Services;

use Bitrix\Main,
    Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);

/**
 * Class EventlistTable
 * 
 * Fields:
 * <ul>
 * <li> ID int mandatory
 * <li> ACTIVE bool optional default 'Y'
 * <li> NAME string(255) optional
 * <li> CODE string(80) mandatory
 * <li> DESCRIPTION string optional
 * </ul>
 *
 * @package Bitrix\Service
 **/

class EventlistTable extends Main\Entity\DataManager
{
    /**
     * Returns DB table name for entity.
     *
     * @return string
     */
    public static function getTableName()
    {
        return 'bp_service_eventlist';
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
                'title' => Loc::getMessage('EVENTLIST_ENTITY_ID_FIELD'),
            ),
            'ACTIVE' => array(
                'data_type' => 'boolean',
                'values' => array('N', 'Y'),
                'title' => 'ACTIVE',
            ),
            /*'ACTIVE'  => array(
                'data_type' => 'string',
                'required' => true,
                'title' => 'ACTIVE',
                'validation' => array(__CLASS__, 'validateActive'),
            ),*/
            'NAME' => array(
                'data_type' => 'string',
                'validation' => array(__CLASS__, 'validateName'),
                'title' => Loc::getMessage('EVENTLIST_ENTITY_NAME_FIELD'),
            ),
            'CODE' => array(
                'data_type' => 'string',
                'required' => true,
                'validation' => array(__CLASS__, 'validateCode'),
                'title' => Loc::getMessage('EVENTLIST_ENTITY_CODE_FIELD'),
            ),
            'DESCRIPTION' => array(
                'data_type' => 'text',
                'title' => Loc::getMessage('EVENTLIST_ENTITY_DESCRIPTION_FIELD'),
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
    /**
     * Returns validators for CODE field.
     *
     * @return array
     */
    public static function validateCode()
    {
        return array(
            new Main\Entity\Validator\Length(null, 80),
        );
    }
    
    public static function validateActive()
    {
        return array(
            new Main\Entity\Validator\Length(null, 1),
        );
    }
}