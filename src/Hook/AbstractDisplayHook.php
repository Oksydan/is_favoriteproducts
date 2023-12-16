<?php

declare(strict_types=1);

namespace Oksydan\IsFavoriteProducts\Hook;

abstract class AbstractDisplayHook extends AbstractHook
{
    public function execute(array $params): string
    {
        if (!$this->shouldBlockBeDisplayed($params)) {
            return '';
        }

        $this->assignTemplateVariables($params);

        return $this->module->fetch($this->getTemplateFullPath());
    }

    protected function assignTemplateVariables(array $params)
    {
    }

    protected function shouldBlockBeDisplayed(array $params)
    {
        return true;
    }

    public function getTemplateFullPath(): string
    {
        return "module:{$this->module->name}/views/templates/hook/front/{$this->getTemplate()}";
    }

    abstract protected function getTemplate(): string;
}
