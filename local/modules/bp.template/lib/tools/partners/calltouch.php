<?php

namespace Bp\Template\Tools\Partners;

use Bitrix\Main\Entity;
use Bitrix\Main\Entity\DataManager;
use Bitrix\Main\Entity\IntegerField;
use Bitrix\Main\Entity\StringField;
use Bitrix\Main\Entity\Validator;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

class CalltouchTable extends DataManager
{
    /**
     * Returns DB table name for entity.
     *
     * @return string
     */
    public static function getTableName()
    {
        return 'bp_calltouch';
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
                'title' => 'ID',
            ),
            'TIMESTAMP_X' => array(
                'data_type' => 'datetime',
                'title' => 'TIMESTAMP_X',
            ),
            'JSONDATA' => array(
                'data_type' => 'text',
                'title' => 'JSONDATA',
                'required' => true,
            ),
        );
    }
}
