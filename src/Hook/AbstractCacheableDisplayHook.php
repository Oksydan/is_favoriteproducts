<?php

declare(strict_types=1);

namespace Oksydan\IsFavoriteProducts\Hook;

abstract class AbstractCacheableDisplayHook extends AbstractDisplayHook
{
    public function execute(array $params): string
    {
        if (!$this->shouldBlockBeDisplayed($params)) {
            return '';
        }

        if (!$this->isTemplateCached()) {
            $this->assignTemplateVariables($params);
        }

        return $this->module->fetch($this->getTemplateFullPath(), $this->getCacheKey());
    }

    protected function getCacheKey(): string
    {
        return $this->module->getCacheId($this->module->name . '|' . $this->context->cart->id);
    }

    protected function isTemplateCached(): bool
    {
        return $this->module->isCached($this->getTemplateFullPath(), $this->getCacheKey());
    }

    public function clearCache(): void
    {
        $this->module->_clearCache($this->getTemplateFullPath(), $this->getCacheKey());
    }
}
