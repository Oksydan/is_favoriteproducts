<?php

declare(strict_types=1);

namespace Oksydan\IsFavoriteProducts\Form\Type;

use Oksydan\IsFavoriteProducts\Collection\StatsFavoriteProductCollection;
use Oksydan\IsFavoriteProducts\DTO\StatFavoriteProduct;
use Oksydan\IsFavoriteProducts\Presenter\Admin\AdminStatsFavoriteProductPresenter;
use Oksydan\IsFavoriteProducts\Repository\FavoriteProductLegacyRepository;
use PrestaShopBundle\Form\Admin\Type\TranslatorAwareType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class ProductFavoriteType extends TranslatorAwareType
{
    private FavoriteProductLegacyRepository $favoriteProductLegacyRepository;

    private AdminStatsFavoriteProductPresenter $adminStatsFavoriteProductPresenter;

    private \Context $context;

    public function __construct(
        TranslatorInterface $translator,
        array $locales,
        FavoriteProductLegacyRepository $favoriteProductLegacyRepository,
        AdminStatsFavoriteProductPresenter $adminStatsFavoriteProductPresenter,
        \Context $context
    ) {
        parent::__construct($translator, $locales);

        $this->favoriteProductLegacyRepository = $favoriteProductLegacyRepository;
        $this->adminStatsFavoriteProductPresenter = $adminStatsFavoriteProductPresenter;
        $this->context = $context;
    }

    /**
     * {@inheritDoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        parent::buildView($view, $form, $options);

        $productStats = $this->getProductStats($options['product_id']);
        $view->vars['productStats'] = $productStats;
    }

    /**
     * {@inheritDoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver
            ->setDefaults([
                'label' => $this->trans('Favorite products stats', 'Modules.Isfavoriteproducts.Admin'),
                'form_theme' => '@Modules/is_favoriteproducts/views/templates/admin/FormTheme/product_form_theme.html.twig',
            ])
            ->setRequired('product_id')
            ->setAllowedTypes('product_id', 'int')
        ;
    }

    private function getProductStats(int $idProduct)
    {
        $productStats = $this->getProductStatsCollection($idProduct);

        return array_map(function (StatFavoriteProduct $favoriteProduct) {
            return $this->presentProduct($favoriteProduct);
        }, $productStats->toArray());
    }

    private function presentProduct(StatFavoriteProduct $favoriteProduct): array
    {
        return $this->adminStatsFavoriteProductPresenter->present($favoriteProduct, $this->context->language);
    }

    private function getProductStatsCollection(int $idProduct): StatsFavoriteProductCollection
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
