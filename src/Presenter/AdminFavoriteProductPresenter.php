<?php

declare(strict_types=1);

namespace Oksydan\IsFavoriteProducts\Presenter;

use Oksydan\IsFavoriteProducts\DTO\FavoriteProduct;
use Oksydan\IsFavoriteProducts\Repository\ProductLegacyRepository;
use Symfony\Component\Routing\RouterInterface;

class AdminFavoriteProductPresenter implements AdminFavoriteProductPresenterInterface
{
    /**
     * @var RouterInterface $router
     */
    protected RouterInterface $router;

    protected ProductLegacyRepository $productLegacyRepository;

    protected const DATE_FORMAT = 'Y-m-d H:i:s';

    public function __construct(
        RouterInterface $router,
        ProductLegacyRepository $productLegacyRepository
    )
    {
        $this->router = $router;
        $this->productLegacyRepository = $productLegacyRepository;
    }

    public function present(FavoriteProduct $favoriteProduct, \Language $language): array
    {
        $productObject = new \Product($favoriteProduct->getIdProduct(), false, $language->id);
        $idAttribute = $favoriteProduct->getIdProductAttribute();
        $productName = $productObject->name;

        if ($idAttribute > 0) {
            $combination = $this->productLegacyRepository->getProductCombinationForIdProductAttribute($favoriteProduct->getIdProduct(), $idAttribute, $language->id);

            $combinationName = array_reduce($combination, function ($combinationReduced, $combination) {
                $combinationReduced[] = $combination['group_name'] . ': ' . $combination['attribute_name'];

                return $combinationReduced;
            }, []);

            $productName = $productName . ' - ' . implode(', ', $combinationName);
        }

        $admin_url = $this->router->generate('admin_product_form', [
            'id' => $favoriteProduct->getIdProduct(),
        ]);

        return [
            'id' => $favoriteProduct->getIdProduct(),
            'name' => $productName,
            'admin_url' => $admin_url,
            'date_add' => $favoriteProduct->getDateAdd()->format(self::DATE_FORMAT),
        ];
    }
}
