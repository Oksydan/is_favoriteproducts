<?php

declare(strict_types=1);

if (!defined('_PS_VERSION_')) {
    exit;
}

if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require_once __DIR__ . '/vendor/autoload.php';
}

use Oksydan\IsImageslider\Hook\HookInterface;
use Oksydan\IsImageslider\Installer\ImageSliderInstaller;
use PrestaShop\PrestaShop\Adapter\SymfonyContainer;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;

class Is_favoriteproducts extends Module
{
    public $multistoreCompatibility = self::MULTISTORE_COMPATIBILITY_YES;

    public function __construct()
    {
        $this->name = 'is_favoriteproducts';

        $this->author = 'Igor Stępień';
        $this->version = '1.0.0';
        $this->need_instance = 0;

        $this->bootstrap = true;
        parent::__construct();

        $this->displayName = 'Favorite products module';
        $this->description = 'Favorite products module';
        $this->ps_versions_compliancy = ['min' => '8.0.0', 'max' => _PS_VERSION_];
    }

    /**
     * @return bool
     */
    public function install(): bool
    {
        return
            parent::install()
            && $this->registerHook('displayTop')
            && $this->getInstaller()->createTables();
    }

    /**
     * @return bool
     */
    public function uninstall(): bool
    {
        return $this->getInstaller()->dropTables() && parent::uninstall();
    }

    public function getContent(): void
    {
        \Tools::redirectAdmin(SymfonyContainer::getInstance()->get('router')->generate('is_favoriteproducts_controller'));
    }

    /**
     * @template T
     *
     * @param class-string<T>|string $serviceName
     *
     * @return T|object|null
     */
    public function getService($serviceName)
    {
        try {
            return $this->get($serviceName);
        } catch (ServiceNotFoundException $exception) {
            return null;
        }
    }

    /**
     * @return ImageSliderInstaller
     */
    private function getInstaller(): ImageSliderInstaller
    {
        try {
            $installer = $this->getService('oksydan.is_favoriteproducts.image_slider_installer');
        } catch (Error $error){
            $installer = null;
        }

        if (null === $installer) {
            $installer = new Oksydan\IsFavoriteProducts\Installer(
                $this->getService('doctrine.dbal.default_connection'),
                $this->context
            );
        }

        return $installer;
    }

    /** @param string $methodName */
    public function __call($methodName, array $arguments)
    {
        if (str_starts_with($methodName, 'hook')) {
            if ($hook = $this->getHookObject($methodName)) {
                return $hook->execute(...$arguments);
            }
        } else if (method_exists($this, $methodName)) {
            return $this->{$methodName}(...$arguments);
        } else {
            return null;
        }
    }

    /**
     * @param string $methodName
     *
     * @return HookInterface|null
     */
    private function getHookObject($methodName)
    {
        $serviceName = sprintf(
            'oksydan.is_favoriteproducts.hook.%s',
            \Tools::toUnderscoreCase(str_replace('hook', '', $methodName))
        );

        $hook = $this->getService($serviceName);

        return $hook instanceof HookInterface ? $hook : null;
    }
}
