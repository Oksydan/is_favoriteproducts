<?php

declare(strict_types=1);

namespace Oksydan\IsFavoriteProducts\Hook;

use Oksydan\IsFavoriteProducts\Form\Modifier\ProductFormModifier;
use Oksydan\IsFavoriteProducts\Services\FavoriteProductService;

class ActionProductFormBuilderModifier extends AbstractHook
{

    /**
     * @var ProductFormModifier
     */
    private ProductFormModifier $productFormModifier;

    /**
     * @param \Is_favoriteproducts $module
     * @param \Context $context
     * @param FavoriteProductService $favoriteProductService
     * @param ProductFormModifier $productFormModifier
     */
    public function __construct(
        \Is_favoriteproducts $module,
        \Context $context,
        FavoriteProductService $favoriteProductService,
        ProductFormModifier $productFormModifier
    )
    {
        parent::__construct($module, $context, $favoriteProductService);
        $this->productFormModifier = $productFormModifier;
    }

    public function execute(array $params): void
    {
        $productFormBuilder = $params['form_builder'];
        $productId = $params['id'];

        $this->productFormModifier->modify($productId, $productFormBuilder);
    }
}
