<?php

declare(strict_types=1);

namespace Oksydan\IsFavoriteProducts\Grid\Definition\Factory;

use Oksydan\IsFavoriteProducts\Grid\Action\SelectActionOption;
use Oksydan\IsFavoriteProducts\Grid\Action\SelectActionOptionCollection;
use Oksydan\IsFavoriteProducts\Grid\Action\TimePeriodSelectGridAction;
use PrestaShop\PrestaShop\Core\Grid\Action\Bulk\BulkActionCollection;
use PrestaShop\PrestaShop\Core\Grid\Action\GridActionCollection;
use PrestaShop\PrestaShop\Core\Grid\Column\ColumnCollection;
use PrestaShop\PrestaShop\Core\Grid\Column\Type\Common\DataColumn;
use PrestaShop\PrestaShop\Core\Grid\Definition\Factory\AbstractGridDefinitionFactory;
use PrestaShop\PrestaShop\Core\Grid\Filter\FilterCollection;
use PrestaShop\PrestaShop\Core\Hook\HookDispatcherInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class FavoriteListGridDefinitionFactory extends AbstractGridDefinitionFactory
{
    public const GRID_ID = 'is_favoriteproducts_list';

    /**
     * @var TranslatorInterface
     */
    private TranslatorInterface $trans;

    public function __construct(
        HookDispatcherInterface $hookDispatcher = null,
        TranslatorInterface $trans
    ) {
        parent::__construct($hookDispatcher);
        $this->trans = $trans;
    }

    /**
     * {@inheritdoc}
     */
    protected function getId()
    {
        return self::GRID_ID;
    }

    /**
     * {@inheritdoc}
     */
    protected function getName()
    {
        return $this->trans->trans('Top favorite products list', [], 'Modules.Isfavoriteproducts.Admin');
    }

    /**
     * {@inheritdoc}
     */
    protected function getColumns()
    {
        return (new ColumnCollection())
            ->add(
                (new DataColumn('id_product'))
                    ->setName($this->trans->trans('Product ID', [], 'Modules.Isfavoriteproducts.Admin'))
                    ->setOptions([
                        'field' => 'id_product',
                    ])
            )
            ->add(
                (new DataColumn('name'))
                    ->setName($this->trans->trans('Product name', [], 'Modules.Isfavoriteproducts.Admin'))
                    ->setOptions([
                        'field' => 'name',
                    ])
            )
            ->add(
                (new DataColumn('count'))
                    ->setName($this->trans->trans('Count', [], 'Modules.Isfavoriteproducts.Admin'))
                    ->setOptions([
                        'field' => 'count',
                    ])
            );
    }

    /**
     * {@inheritdoc}
     */
    protected function getFilters()
    {
        return new FilterCollection();
    }

    /**
     * {@inheritdoc}
     */
    protected function getGridActions()
    {
        return new GridActionCollection();
    }

    /**
     * {@inheritdoc}
     */
    protected function getBulkActions()
    {
        return new BulkActionCollection();
    }
}
