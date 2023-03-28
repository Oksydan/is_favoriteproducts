<?php

declare(strict_types=1);

namespace Oksydan\IsFavoriteProducts\Hook;

use Context;
use Module;

abstract class AbstractHook implements HookInterface
{
    /**
     * @var Module
     */
    protected Module $module;

    /**
     * @var Context
     */
    protected Context $context;

    public function __construct(Module $module, Context $context)
    {
        $this->module = $module;
        $this->context = $context;
    }
}
