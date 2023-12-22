<?php

declare(strict_types=1);

namespace Oksydan\IsFavoriteProducts\Installer;

class ModuleInstaller
{
    const HOOKS_LIST = [
        'displayTop',
        'actionFrontControllerSetMedia',
        'actionAuthentication',
        'displayProductFavoriteButton',
        'displayProductActions',
        'displayCrossSellingShoppingCart',
        'actionCartSave',
        'displayAdminCustomers',
        'actionProductFormBuilderModifier',
        'displayAdminProductsExtra',
    ];

    private \Is_favoriteproducts $module;

    public function __construct(
        \Is_favoriteproducts $module
    ) {
        $this->module = $module;
    }

    public function install(): bool
    {
        if (\Shop::isFeatureActive()) {
            \Shop::setContext(\Shop::CONTEXT_ALL);
        }

        return $this->installHooks() && $this->installDatabase();
    }

    public function uninstall(): bool
    {
        return $this->uninstallDatabase();
    }

    private function installHooks(): bool
    {
        $success = true;

        foreach (self::HOOKS_LIST as $hook) {
            if (!$this->module->registerHook($hook)) {
                $success = false;
            }
        }

        return $success;
    }

    private function installDatabase(): bool
    {
        $success = true;

        $sql = [
            'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'favorite_product` (
                `id_favorite_product` int(11) NOT NULL AUTO_INCREMENT,
                `id_product` int(11) NOT NULL,
                `id_product_attribute` int(11) NOT NULL,
                `id_customer` int(11) NOT NULL,
                `id_shop` int(11) NOT NULL,
                `date_add` datetime NOT NULL,
                PRIMARY KEY (`id_favorite_product`)
            ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;',
        ];

        foreach ($sql as $query) {
            if (!\Db::getInstance()->execute($query)) {
                $success = false;
            }
        }

        return $success;
    }

    private function uninstallDatabase(): bool
    {
        $success = true;

        $sql = [
            'DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'favorite_product`',
        ];

        foreach ($sql as $query) {
            if (!\Db::getInstance()->execute($query)) {
                $success = false;
            }
        }

        return $success;
    }
}
