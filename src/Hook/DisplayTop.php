<?php

declare(strict_types=1);

namespace Oksydan\IsFavoriteProducts\Hook;

class DisplayTop extends AbstractDisplayHook
{
    private const TEMPLATE_FILE = 'top.tpl';

    protected function getTemplate(): string
    {
        return self::TEMPLATE_FILE;
    }

    protected function assignTemplateVariables(array $params)
    {
        $this->context->smarty->assign([
            'favoriteProductsCount' => count($this->favoriteProductService->getFavoriteProducts()),
            'favoritePageUrl' => $this->context->link->getModuleLink($this->module->name, 'favorite'),
        ]);
    }
}
