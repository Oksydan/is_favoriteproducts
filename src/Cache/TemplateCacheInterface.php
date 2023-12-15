<?php

declare(strict_types=1);

namespace Oksydan\IsFavoriteProducts\Cache;

interface TemplateCacheInterface
{
    public function clearCartTemplateCache(): void;
}
