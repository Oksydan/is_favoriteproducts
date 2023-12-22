<?php

declare(strict_types=1);

namespace Oksydan\IsFavoriteProducts\Presenter\Front;

use PrestaShop\PrestaShop\Adapter\Presenter\Product\ProductLazyArray;

class FrontProductProductPresenter implements FrontProductPresenterInterface
{
    protected \Context $context;

    public function __construct(
        \Context $context
    ) {
        $this->context = $context;
    }

    /**
     * @param array{id_product: int, id_product_attribute: int} $product
     *
     * @return ProductLazyArray
     */
    public function present(array $product): ProductLazyArray
    {
        $presenterFactory = $this->presenterFactory();
        $presenter = $presenterFactory->getPresenter();

        return $presenter->present(
            $presenterFactory->getPresentationSettings(),
            (new \ProductAssembler($this->context))->assembleProduct($product),
            $this->context->language
        );
    }

    private function presenterFactory(): \ProductPresenterFactory
    {
        return new \ProductPresenterFactory($this->context);
    }
}
