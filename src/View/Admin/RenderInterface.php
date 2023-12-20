<?php

namespace Oksydan\IsFavoriteProducts\View\Admin;

interface RenderInterface
{
    public function render(int $id): string;
}
