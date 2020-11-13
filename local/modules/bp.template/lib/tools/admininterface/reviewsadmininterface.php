<?php

namespace Bp\Template\Tools\AdminInterface;

use Bitrix\Main\Localization\Loc;
use DigitalWand\AdminHelper\Helper\AdminInterface;
use DigitalWand\AdminHelper\Widget\DateTimeWidget;
use DigitalWand\AdminHelper\Widget\ComboBoxWidget;
use DigitalWand\AdminHelper\Widget\FileWidget;
use DigitalWand\AdminHelper\Widget\NumberWidget;
use DigitalWand\AdminHelper\Widget\StringWidget;
use DigitalWand\AdminHelper\Widget\UrlWidget;
use DigitalWand\AdminHelper\Widget\UserWidget;
use DigitalWand\AdminHelper\Widget\VisualEditorWidget;
use DigitalWand\AdminHelper\Widget\TextAreaWidget;
use DigitalWand\AdminHelper\Widget\CheckboxWidget;
use DigitalWand\AdminHelper\Widget\IblockElementWidget;

Loc::loadMessages(__FILE__);

/**
 * Описание интерфейса (табок и полей) админки новостей.
 *
 * {@inheritdoc}
 */
class ReviewsAdminInterface extends AdminInterface
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
                'NAME' => 'Редактирование Отзыва',
                'FIELDS' => array(
                    'ID' => array(
                        'WIDGET' => new NumberWidget(),
                        'READONLY' => true,
                        'FILTER' => true,
                        'HIDE_WHEN_CREATE' => true,
                    ),
                    'ACTIVE' => array(
                        'WIDGET' => new CheckboxWidget(),
                        //'TYPE_STRING' => 'string',
                    ),
                    'ELEMENT_ID' => array(
                        'WIDGET' => new IblockElementWidget(),
                        'READONLY' => true,
                        'FILTER' => true,
                        'HIDE_WHEN_CREATE' => true,
                        'IBLOCK_ID' => 1
                    ),
                    'GRADE' => array(
                        'WIDGET' => new NumberWidget(),
                        'REQUIRED' => true
                    ),
                    'TEXT' => array(
                        'WIDGET' => new TextAreaWidget(),
                    ),
                    'ADVANTAGES' => array(
                        'WIDGET' => new TextAreaWidget(),
                    ),
                    'DISADVANTAGES' => array(
                        'WIDGET' => new TextAreaWidget(),
                    ),
                    'PICTURES' => array(
                        'WIDGET' => new TextAreaWidget(),
                    ),
                    'LIKE_YES' => array(
                        'WIDGET' => new NumberWidget(),
                    ),
                    'LIKE_NO' => array(
                        'WIDGET' => new NumberWidget(),
                    ),
                    'AUTHOR_NAME' => array(
                        'WIDGET' => new StringWidget(),
                        'SIZE' => '80'
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
            '\Bp\Template\Tools\AdminInterface\ReviewsListHelper',
            '\Bp\Template\Tools\AdminInterface\ReviewsEditHelper'
        );
    }
}
