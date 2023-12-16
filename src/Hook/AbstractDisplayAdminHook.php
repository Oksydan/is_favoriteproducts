<?php

declare(strict_types=1);

namespace Oksydan\IsFavoriteProducts\Hook;

use Oksydan\IsFavoriteProducts\Services\FavoriteProductService;
use Twig\Environment;

abstract class AbstractDisplayAdminHook extends AbstractHook
{
    /**
     * @var Environment
     */
    protected Environment $twig;

    public function __construct(
        \Is_favoriteproducts $module,
        \Context $context,
        FavoriteProductService $favoriteProductService,
        Environment $twig
    ) {
        parent::__construct($module, $context, $favoriteProductService);
        $this->twig = $twig;
    }

    public function execute(array $params): string
    {
        if (!$this->shouldBlockBeDisplayed($params)) {
            return '';
        }

        return $this->twig->render($this->getTemplateFullPath(), $this->getTemplateVariables($params));
    }

    protected function getTemplateVariables(array $params): array
    {
        return [];
    }

    protected function shouldBlockBeDisplayed(array $params)
    {
        return true;
    }

    public function getTemplateFullPath(): string
    {
        return "@Modules/{$this->module->name}/views/templates/hook/admin/{$this->getTemplate()}";
    }

    abstract protected function getTemplate(): string;
}
