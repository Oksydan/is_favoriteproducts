<?php

declare(strict_types=1);

namespace Oksydan\IsFavoriteProducts\Hook;

interface HookInterface
{
    public function execute(array $params);
}
