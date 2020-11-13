<?php
namespace Bp\Template\Tools;

use Bitrix\Main,
    Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);

/**
 * Class UrlsTable
 * 
 * Fields:
 * <ul>
 * <li> id int mandatory
 * <li> data string mandatory
 * <li> short_code string(6) mandatory
 * <li> date_created int mandatory
 * <li> last_used int mandatory
 * <li> counter int mandatory
 * </ul>
 *
 * @package Bitrix\Try
 **/

class TryurlsTable extends Main\Entity\DataManager
{
    /**
     * Returns DB table name for entity.
     *
     * @return string
     */
    public static function getTableName()
    {
        return 'bp_try_urls';
    }

    /**
     * Returns entity map definition.
     *
     * @return array
     */
    public static function getMap()
    {
        return array(
            'id' => array(
                'data_type' => 'integer',
                'primary' => true,
                'autocomplete' => true,
                'title' => Loc::getMessage('URLS_ENTITY_ID_FIELD'),
            ),
            'data' => array(
                'data_type' => 'text',
                'required' => true,
                'title' => Loc::getMessage('URLS_ENTITY_DATA_FIELD'),
            ),
            'short_code' => array(
                'data_type' => 'string',
                'required' => true,
                'validation' => array(__CLASS__, 'validateShortCode'),
                'title' => Loc::getMessage('URLS_ENTITY_SHORT_CODE_FIELD'),
            ),
            'date_created' => array(
                'data_type' => 'integer',
                'required' => true,
                'title' => Loc::getMessage('URLS_ENTITY_DATE_CREATED_FIELD'),
            ),
            'last_used' => array(
                'data_type' => 'integer',
                'required' => true,
                'title' => Loc::getMessage('URLS_ENTITY_LAST_USED_FIELD'),
            ),
            'counter' => array(
                'data_type' => 'integer',
                'required' => true,
                'title' => Loc::getMessage('URLS_ENTITY_COUNTER_FIELD'),
            ),
        );
    }
    /**
     * Returns validators for short_code field.
     *
     * @return array
     */
    public static function validateShortCode()
    {
        return array(
            new Main\Entity\Validator\Length(null, 6),
        );
    }
}