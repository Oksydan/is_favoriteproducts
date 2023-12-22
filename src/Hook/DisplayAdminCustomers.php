<?php

declare(strict_types=1);

namespace Oksydan\IsFavoriteProducts\Hook;

use Oksydan\IsFavoriteProducts\Presenter\Admin\AdminFavoriteProductPresenter;
use Oksydan\IsFavoriteProducts\Provider\CustomerFavoriteProductsProvider;
use Oksydan\IsFavoriteProducts\Services\FavoriteProductService;
use PrestaShop\PrestaShop\Adapter\Validate;
use Twig\Environment;

class DisplayAdminCustomers extends AbstractDisplayAdminHook
{
    protected CustomerFavoriteProductsProvider $customerFavoriteProductsProvider;

    protected AdminFavoriteProductPresenter $adminFavoriteProductPresenter;

    public function __construct(
        \Is_favoriteproducts $module,
        \Context $context,
        FavoriteProductService $favoriteProductService,
        Environment $twig,
        CustomerFavoriteProductsProvider $customerFavoriteProductsProvider,
        AdminFavoriteProductPresenter $adminFavoriteProductPresenter
    ) {
        parent::__construct($module, $context, $favoriteProductService, $twig);
        $this->customerFavoriteProductsProvider = $customerFavoriteProductsProvider;
        $this->adminFavoriteProductPresenter = $adminFavoriteProductPresenter;
    }

    private const TEMPLATE_FILE = 'displayAdminCustomers.html.twig';

    protected function getTemplate(): string
    {
        return self::TEMPLATE_FILE;
    }

    protected function getTemplateVariables(array $params): array
    {
        $idCustomer = (int) $params['id_customer'];
        $customer = new \Customer($idCustomer);

        if (!Validate::isLoadedObject($customer)) {
            return [];
        }

        $shop = new \Shop($customer->id_shop);

        if (!Validate::isLoadedObject($shop)) {
            return [];
        }

        $productsRaw = $this->customerFavoriteProductsProvider->getFavoriteProductsByCustomer($customer, $shop);
        $products = [];

        foreach ($productsRaw as $productRaw) {
            $products[] = $this->adminFavoriteProductPresenter->present($productRaw, $this->context->language);
        }

        return [
            'products' => $products,
        ];
    }
}
