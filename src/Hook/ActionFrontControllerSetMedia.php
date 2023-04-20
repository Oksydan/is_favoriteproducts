<?php

declare(strict_types=1);

namespace Oksydan\IsFavoriteProducts\Hook;

use Oksydan\IsFavoriteProducts\DTO\FavoriteProduct;
use Oksydan\IsFavoriteProducts\Presenter\FavoriteProductJsonPresenter;
use Oksydan\IsFavoriteProducts\Services\FavoriteProductService;

class ActionFrontControllerSetMedia extends AbstractHook
{
    private FavoriteProductJsonPresenter $productPresenter;

    public function __construct(
        \Is_favoriteproducts $module,
        \Context $context,
        FavoriteProductService $favoriteProductService,
        FavoriteProductJsonPresenter $productPresenter
    ) {
        parent::__construct($module, $context, $favoriteProductService);
        $this->productPresenter = $productPresenter;
    }

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
            'favoriteProducts' => $this->getFavoriteProductsJsonData(),
            'isFavoriteProductsListingPage' => $this->context->controller instanceof \Is_favoriteproductsFavoriteModuleFrontController,
        ]);
    }

    private function getFavoriteProductsJsonData(): array
    {
        return array_map(function (FavoriteProduct $product) {
            return $this->productPresenter->present($product);
        }, $this->favoriteProductService->getFavoriteProducts());
    }
}
