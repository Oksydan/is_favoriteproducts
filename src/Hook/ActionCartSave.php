<?php

declare(strict_types=1);

namespace Oksydan\IsFavoriteProducts\Hook;

use Oksydan\IsFavoriteProducts\Cache\TemplateCache;
use Oksydan\IsFavoriteProducts\Services\FavoriteProductService;

class ActionCartSave extends AbstractHook
{
    private TemplateCache $templateCache;

    public function __construct(
        \Is_favoriteproducts $module,
        \Context $context,
        FavoriteProductService $favoriteProductService,
        TemplateCache $templateCache
    ) {
        parent::__construct($module, $context, $favoriteProductService);
        $this->templateCache = $templateCache;
    }

    public function execute(array $params): void
    {
        if (empty($this->context->cart->id)) {
            return;
        }

        $this->templateCache->clearCartTemplateCache();
    }
}
