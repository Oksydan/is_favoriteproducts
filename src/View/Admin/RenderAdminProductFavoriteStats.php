<?php

declare(strict_types=1);

namespace Oksydan\IsFavoriteProducts\View\Admin;

use Oksydan\IsFavoriteProducts\Collection\StatsFavoriteProductCollection;
use Oksydan\IsFavoriteProducts\DTO\StatFavoriteProduct;
use Oksydan\IsFavoriteProducts\Presenter\Admin\AdminStatsFavoriteProductPresenter;
use Oksydan\IsFavoriteProducts\Repository\FavoriteProductLegacyRepository;
use Twig\Environment;

class RenderAdminProductFavoriteStats implements RenderInterface
{
    private FavoriteProductLegacyRepository $favoriteProductLegacyRepository;

    private AdminStatsFavoriteProductPresenter $adminStatsFavoriteProductPresenter;

    private \Context $context;

    private Environment $twig;

    public function __construct(
        FavoriteProductLegacyRepository $favoriteProductLegacyRepository,
        AdminStatsFavoriteProductPresenter $adminStatsFavoriteProductPresenter,
        \Context $context,
        Environment $twig
    ) {
        $this->favoriteProductLegacyRepository = $favoriteProductLegacyRepository;
        $this->adminStatsFavoriteProductPresenter = $adminStatsFavoriteProductPresenter;
        $this->context = $context;
        $this->twig = $twig;
    }

    public function render(int $id): string
    {
        $productStats = $this->getProductStats($id);

        $productStats = array_map(function (StatFavoriteProduct $favoriteProduct) {
            return $this->presentProduct($favoriteProduct);
        }, $productStats->toArray());

        return $this->twig->render('@Modules/is_favoriteproducts/views/templates/admin/product/stats.html.twig', [
            'productStats' => $productStats,
        ]);
    }

    private function presentProduct(StatFavoriteProduct $favoriteProduct): array
    {
        return $this->adminStatsFavoriteProductPresenter->present($favoriteProduct, $this->context->language);
    }

    private function getProductStats(int $idProduct): StatsFavoriteProductCollection
    {
        $productStats = $this->favoriteProductLegacyRepository->getFavoriteStatsForProduct($idProduct, $this->context->shop->id);
        $formattedStats = [];
        $collection = new StatsFavoriteProductCollection();

        if (!empty($productStats)) {
            foreach ($productStats as $product) {
                $key = $product['id_product'] . '_' . $product['id_product_attribute'];

                if (empty($formattedStats[$key])) {
                    $formattedStats[$key] = [
                        'id_product' => $product['id_product'],
                        'id_product_attribute' => $product['id_product_attribute'],
                        'total' => 0,
                    ];
                }

                ++$formattedStats[$key]['total'];
            }
        }

        foreach ($formattedStats as $stat) {
            $collection->add(new StatFavoriteProduct(
                $stat['id_product'],
                $stat['id_product_attribute'],
                $stat['total']
            ));
        }

        return $collection;
    }
}
