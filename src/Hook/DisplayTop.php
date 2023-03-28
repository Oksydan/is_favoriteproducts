<?php

declare(strict_types=1);

namespace Oksydan\IsFavoriteProducts\Hook;

use Oksydan\IsFavoriteProducts\AbstractDisplayHook;

class DisplayTop extends AbstractDisplayHook
{
    private const TEMPLATE_FILE = 'top.tpl';

    protected function getTemplate(): string
    {
        return self::TEMPLATE_FILE;
    }

    protected function assignTemplateVariables(array $params)
    {

    }
}
