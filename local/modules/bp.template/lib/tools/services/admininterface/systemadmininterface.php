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

Loc::loadMessages(__FILE__);

/**
 * Описание интерфейса (табок и полей) админки новостей.
 *
 * {@inheritdoc}
 */
class SystemAdminInterface extends AdminInterface
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
                'NAME' => 'Редактирование сервисов',
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
                    'DESCRIPTION' => array(
                        'WIDGET' => new TextAreaWidget(),
                        'SIZE' => '80',
                        'LIST' => false,
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
            '\Bp\Template\Tools\Services\AdminInterface\SystemListHelper',
            '\Bp\Template\Tools\Services\AdminInterface\SystemEditHelper'
        );
    }
}