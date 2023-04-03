<?php

declare(strict_types=1);

namespace Oksydan\IsFavoriteProducts\Hook;

class DisplayProductActions extends AbstractDisplayHook
{
    private const TEMPLATE_FILE = 'button-product.tpl';

    protected function getTemplate(): string
    {
        return self::TEMPLATE_FILE;
    }

    protected function assignTemplateVariables(array $params)
    {
        if (empty($params['product'])) {
            return;
        }

        $this->context->smarty->assign([
            'product' => $params['product'],
        ]);
    }
}
