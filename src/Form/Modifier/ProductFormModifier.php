<?php

declare(strict_types=1);

namespace Oksydan\IsFavoriteProducts\Form\Modifier;

use Oksydan\IsFavoriteProducts\Form\Type\ProductFavoriteType;
use PrestaShopBundle\Form\FormBuilderModifier;
use Symfony\Component\Form\FormBuilderInterface;

class ProductFormModifier
{
    /**
     * @var FormBuilderModifier
     */
    private FormBuilderModifier $formBuilderModifier;

    /**
     * @param FormBuilderModifier $formBuilderModifier
     */
    public function __construct(
        FormBuilderModifier $formBuilderModifier
    ) {
        $this->formBuilderModifier = $formBuilderModifier;
    }

    /**
     * @param int|null $productId
     * @param FormBuilderInterface $productFormBuilder
     */
    public function modify(
        int $productId,
        FormBuilderInterface $productFormBuilder
    ): void {
        $this->formBuilderModifier->addAfter(
            $productFormBuilder,
            'options',
            'favorite_products',
            ProductFavoriteType::class,
            [
                'product_id' => $productId,
            ],
        );
    }
}
