<?php

namespace Bp\Template\Tools\Services\AdminInterface;

use DigitalWand\AdminHelper\Helper\AdminListHelper;

/**
 * Хелпер описывает интерфейс, выводящий список новостей.
 *
 * {@inheritdoc}
 */
class MailReviewLogListHelper extends AdminListHelper
{
    protected static $model = '\Bp\Template\Tools\Services\SystemTable';
}