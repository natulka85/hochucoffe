<?php

namespace Bp\Template\Tools\Services\AdminInterface;

use Bitrix\Main\Localization\Loc;
use DigitalWand\AdminHelper\Helper\AdminInterface;
use DigitalWand\AdminHelper\Widget\DateTimeWidget;
use DigitalWand\AdminHelper\Widget\FileWidget;
use DigitalWand\AdminHelper\Widget\NumberWidget;
use DigitalWand\AdminHelper\Widget\StringWidget;
use DigitalWand\AdminHelper\Widget\UrlWidget;
use DigitalWand\AdminHelper\Widget\UserWidget;
use DigitalWand\AdminHelper\Widget\VisualEditorWidget;
use DigitalWand\AdminHelper\Widget\TextAreaWidget;
use DigitalWand\AdminHelper\Widget\CheckboxWidget;
use DigitalWand\AdminHelper\Widget\OrmElementWidget;

Loc::loadMessages(__FILE__);

/**
 * Описание интерфейса (табок и полей) админки новостей.
 *
 * {@inheritdoc}
 */
class EventtAdminInterface extends AdminInterface
{
    /*public function dependencies()
    {
        return array('\Bp\Template\Tools\Pages\AdminInterface\NewsAdminInterface');
    } */
    /**
     * {@inheritdoc}
     */
    public function fields()
    {
        return array(
            'MAIN' => array(
                'NAME' => 'Редактирование событий',
                'FIELDS' => array(
                    'ID' => array(
                        'WIDGET' => new NumberWidget(),
                        'READONLY' => true,
                        'FILTER' => true,
                        'HIDE_WHEN_CREATE' => true
                    ),
                    'ACTIVE' => array(
                        'WIDGET' => new CheckboxWidget(),
                        //'TYPE_STRING' => 'string',
                    ),
                    'NAME' => array(
                        'WIDGET' => new StringWidget(),
                        'SIZE' => '80',
                        'FILTER' => '%',
                    ),
                    'EVENT_ID' => array(
                        'WIDGET' => new OrmElementWidget(),
                        'HELPER' => '\Bp\Template\Tools\Services\AdminInterface\EventlistListHelper',
                        'TITLE_FIELD_NAME' => 'NAME',
                        'FILTER' => '%',
                        //'READONLY' => true,
                        //'LIST' => false
                    ),
                    'SERVICE_ID' => array(
                        'WIDGET' => new OrmElementWidget(),
                        'HELPER' => '\Bp\Template\Tools\Services\AdminInterface\SystemListHelper',
                        'TITLE_FIELD_NAME' => 'NAME',
                        'FILTER' => '%',
                        //'READONLY' => true,
                        //'LIST' => false
                    ),
                    'FUNCTION' => array(
                        'WIDGET' => new TextAreaWidget(),
                        'COLS' => 120,
                        'ROWS' => 40,
                    ),
                    'DESCRIPTION' => array(
                        'WIDGET' => new TextAreaWidget(),
                        'COLS' => 120,
                        'ROWS' => 20,
                    ),
                )
            )
        );
    }

    /**
     * {@inheritdoc}
     */
    public function helpers()
    {
        return array(
            '\Bp\Template\Tools\Services\AdminInterface\EventtListHelper',
            '\Bp\Template\Tools\Services\AdminInterface\EventtEditHelper'
        );
    }
}