<?php

declare(strict_types=1);

namespace Oksydan\IsFavoriteProducts\Hook;

use Oksydan\IsFavoriteProducts\Form\Type\ProductFavoriteType;
use Oksydan\IsFavoriteProducts\Services\FavoriteProductService;
use Symfony\Component\Form\FormFactoryInterface;
use Twig\Environment;

class DisplayAdminProductsExtra extends AbstractHook
{
    /**
     * @var FormFactoryInterface
     */
    private FormFactoryInterface $formFactory;

    /**
     * @var Environment
     */
    private Environment $twig;

    /**
     * @param \Is_favoriteproducts $module
     * @param \Context $context
     * @param FavoriteProductService $favoriteProductService
     * @param FormFactoryInterface $formFactory
     */
    public function __construct(
        \Is_favoriteproducts $module,
        \Context $context,
        FavoriteProductService $favoriteProductService,
        FormFactoryInterface $formFactory,
        Environment $twig
    ) {
        parent::__construct($module, $context, $favoriteProductService);

        $this->formFactory = $formFactory;
        $this->twig = $twig;
    }

    public function execute(array $params): string
    {
        $productId = (int) $params['id_product'];

        $form = $this->formFactory->create(ProductFavoriteType::class, [], [
            'product_id' => $productId,
        ]);

        return $this->twig->render('@Modules/is_favoriteproducts/views/templates/admin/hook/displayAdminProductsExtra.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
