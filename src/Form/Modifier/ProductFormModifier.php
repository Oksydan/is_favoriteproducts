<?php

declare(strict_types=1);

namespace Oksydan\IsFavoriteProducts\Form\Modifier;

use Oksydan\IsFavoriteProducts\Form\Type\ProductFavoriteType;
use Oksydan\IsFavoriteProducts\View\Admin\RenderAdminProductFavoriteStats;
use PrestaShopBundle\Form\FormBuilderModifier;
use Symfony\Component\Form\FormBuilderInterface;

class ProductFormModifier
{
    /**
     * @var FormBuilderModifier
     */
    private FormBuilderModifier $formBuilderModifier;

    /**
     * @var RenderAdminProductFavoriteStats
     */
    private RenderAdminProductFavoriteStats $renderAdminProductFavoriteStats;

    /**
     * @param FormBuilderModifier $formBuilderModifier
     */
    public function __construct(
        FormBuilderModifier $formBuilderModifier,
        RenderAdminProductFavoriteStats $renderAdminProductFavoriteStats
    ) {
        $this->formBuilderModifier = $formBuilderModifier;
        $this->renderAdminProductFavoriteStats = $renderAdminProductFavoriteStats;
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
                'data' => [
                    'html_content' => [
                        'content' => $this->renderAdminProductFavoriteStats->render($productId),
                    ],
                ],
            ]
        );
    }
}
