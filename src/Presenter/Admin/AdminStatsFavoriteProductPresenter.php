<?php

declare(strict_types=1);

namespace Oksydan\IsFavoriteProducts\Presenter\Admin;

use Oksydan\IsFavoriteProducts\DTO\StatFavoriteProduct;
use Oksydan\IsFavoriteProducts\Repository\ProductLegacyRepository;

class AdminStatsFavoriteProductPresenter implements AdminStatsFavoriteProductPresenterInterface
{
    protected ProductLegacyRepository $productLegacyRepository;

    protected const DATE_FORMAT = 'Y-m-d H:i:s';

    public function __construct(
        ProductLegacyRepository $productLegacyRepository
    ) {
        $this->productLegacyRepository = $productLegacyRepository;
    }

    public function present(StatFavoriteProduct $favoriteProduct, \Language $language): array
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

        return [
            'name' => $productName,
            'total' => $favoriteProduct->getTotal(),
        ];
    }
}
