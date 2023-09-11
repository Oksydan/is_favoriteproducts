<?php

declare(strict_types=1);

if (!defined('_PS_VERSION_')) {
    exit;
}

if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require_once __DIR__ . '/vendor/autoload.php';
} else {
    throw new \Exception('You must run "composer install --no-dev" command in module directory');
}

use Oksydan\IsFavoriteProducts\Hook\HookInterface;
use Oksydan\IsFavoriteProducts\Installer\ModuleInstaller;
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
        $this->controllers = ['favorite'];
        $this->bootstrap = true;
        parent::__construct();

        $this->displayName = $this->trans('Favorite products module', [], 'Modules.Isfavoriteproducts.Admin');
        $this->description = $this->trans('Falcon theme favorite products module', [], 'Modules.Isfavoriteproducts.Admin');
        $this->ps_versions_compliancy = ['min' => '8.0.0', 'max' => _PS_VERSION_];
    }

    public function isUsingNewTranslationSystem(): bool
    {
        return true;
    }

    private function getModuleInstaller(): ModuleInstaller
    {
        return new ModuleInstaller($this);
    }

    /**
     * @return bool
     */
    public function install(): bool
    {
        return parent::install() && $this->getModuleInstaller()->install();
    }

    /**
     * @return bool
     */
    public function uninstall(): bool
    {
        return parent::uninstall() && $this->getModuleInstaller()->uninstall();
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

    /** @param string $methodName */
    public function __call($methodName, array $arguments)
    {
        if (str_starts_with($methodName, 'hook') && $hook = $this->getHookObject($methodName)) {
            return $hook->execute(...$arguments);
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
            'Oksydan\IsFavoriteProducts\Hook\%s',
            ucwords(str_replace('hook', '', $methodName))
        );

        $hook = $this->getService($serviceName);

        return $hook instanceof HookInterface ? $hook : null;
    }
}
