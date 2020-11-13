<?php

namespace Bp\Template\Tools\AdminInterface;

use DigitalWand\AdminHelper\Helper\AdminListHelper;

\CModule::IncludeModule("askaron.reviews");

/**
 * Хелпер описывает интерфейс, выводящий список новостей.
 *
 * {@inheritdoc}
 */  
class ReviewsListHelper extends AdminListHelper
{
    protected static $model = '\Askaron\Reviews\ReviewTable';
}