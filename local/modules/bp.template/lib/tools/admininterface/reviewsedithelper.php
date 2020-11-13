<?php

namespace Bp\Template\Tools\AdminInterface;

use DigitalWand\AdminHelper\Helper\AdminEditHelper;

\CModule::IncludeModule("askaron.reviews");

/**
 * Хелпер описывает интерфейс, выводящий форму редактирования новости.
 *
 * {@inheritdoc}
 */
class ReviewsEditHelper extends AdminEditHelper
{
    protected static $model = '\Askaron\Reviews\ReviewTable';
}