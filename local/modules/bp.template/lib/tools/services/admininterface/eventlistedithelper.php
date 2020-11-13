<?php

namespace Bp\Template\Tools\Services\AdminInterface;

use DigitalWand\AdminHelper\Helper\AdminEditHelper;

/**
 * Хелпер описывает интерфейс, выводящий форму редактирования новости.
 *
 * {@inheritdoc}
 */
class EventlistEditHelper extends AdminEditHelper
{
    protected static $model = '\Bp\Template\Tools\Services\EventlistTable';
}