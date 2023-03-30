<?php

declare(strict_types=1);

namespace Oksydan\IsFavoriteProducts\Hook;

class ActionFrontControllerSetMedia extends AbstractHook
{
    public function execute(array $params): void
    {
        \Media::addJsDef([
            'addToFavoriteAction' => $this->context->link->getModuleLink($this->module->name, 'ajax', [
                'action' => 'addFavoriteProduct',
                'ajax' => '1',
            ]),
            'removeFromFavoriteAction' => $this->context->link->getModuleLink($this->module->name, 'ajax', [
                'action' => 'removeFavoriteProduct',
                'ajax' => '1',
            ]),
            'favoriteProducts' => $this->favoriteProductService->getFavoriteProducts(),
        ]);
    }
}
