<?php

use Oksydan\IsFavoriteProducts\DTO\FavoriteProduct as FavoriteProductDTO;
use Oksydan\IsFavoriteProducts\Hook\DisplayTop;
use Oksydan\IsFavoriteProducts\Services\FavoriteProductService;

class Is_favoriteproductsAjaxModuleFrontController extends ModuleFrontController
{
    private $message = [];

    private $favoriteProductService;

    private function getFavoriteProductService(): FavoriteProductService
    {
        if ($this->favoriteProductService instanceof FavoriteProductService === false) {
            $this->favoriteProductService = $this->get(FavoriteProductService::class);
        }

        return $this->favoriteProductService;
    }

    private function checkProductExistence(): bool
    {
        $idProduct = (int) Tools::getValue('id_product', 0);
        $idProductAttribute = (int) Tools::getValue('id_product_attribute', 0);
        $exists = $this->getFavoriteProductService()->productExists($idProduct, $idProductAttribute, $this->context->shop->id);

        if (!$exists) {
            $this->errors[] = $this->module->getTranslator()->trans('Product does not exist in store', [], 'Modules.IsFavoriteProducts.Front');
        }

        return $exists;
    }

    private function checkLimit(): bool
    {
        $reached = $this->getFavoriteProductService()->isFavoriteLimitReached();

        if ($reached) {
            $this->errors[] = $this->module->getTranslator()->trans('You have reached limit of %number% products in your favorite list. Login or create an account to add more products.', [
                '%number%' => $this->getFavoriteProductService()->getFavoriteLimit(),
            ], 'Modules.IsFavoriteProducts.Front');
        }

        return $reached;
    }

    private function createFavoriteProductDto(): FavoriteProductDTO
    {
        $idProduct = (int) Tools::getValue('id_product', 0);
        $idProductAttribute = (int) Tools::getValue('id_product_attribute', 0);

        $favoriteProduct = new FavoriteProductDTO();

        $favoriteProduct->setIdProduct($idProduct);
        $favoriteProduct->setIdProductAttribute($idProductAttribute);
        $favoriteProduct->setIdShop($this->context->shop->id);
        $favoriteProduct->setDateAdd(new \DateTimeImmutable());

        if ($this->context->customer->isLogged()) {
            $favoriteProduct->setIdCustomer($this->context->customer->id);
        }

        return $favoriteProduct;
    }

    public function displayAjaxAddFavoriteProduct(): void
    {
        $this->checkProductExistence();
        $this->checkLimit();

        $favoriteProduct = $this->createFavoriteProductDto();

        if (empty($this->errors) && $this->getFavoriteProductService()->isProductAlreadyInFavorites($favoriteProduct)) {
            $this->errors[] = $this->module->getTranslator()->trans('Product already exists in your favorite list', [], 'Modules.IsFavoriteProducts.Front');
        }

        if (empty($this->errors)) {
            try {
                $this->getFavoriteProductService()->addFavoriteProduct($favoriteProduct);
            } catch (Exception $e) {
                $this->errors[] = $e->getMessage();
            }
        }

        if (empty($this->errors)) {
            $this->message[] = $this->module->getTranslator()->trans('Product added to your favorite list', [], 'Modules.IsFavoriteProducts.Front');
        }

        $this->renderResponse();
    }

    public function displayAjaxRemoveFavoriteProduct(): void
    {
        $this->checkProductExistence();

        $favoriteProduct = $this->createFavoriteProductDto();

        if (empty($this->errors) && !$this->getFavoriteProductService()->isProductAlreadyInFavorites($favoriteProduct)) {
            $this->errors[] = $this->module->getTranslator()->trans('Product don\'t exists in your favorite list', [], 'Modules.IsFavoriteProducts.Front');
        }

        if (empty($this->errors)) {
            try {
                $this->getFavoriteProductService()->removeFavoriteProduct($favoriteProduct);
            } catch (Exception $e) {
                $this->errors[] = $e->getMessage();
            }
        }

        if (empty($this->errors)) {
            $this->message[] = $this->module->getTranslator()->trans('Product removed from your favorite list', [], 'Modules.IsFavoriteProducts.Front');
        }

        $this->renderResponse();
    }

    private function renderResponse(): void
    {
        ob_end_clean();
        header('Content-Type: application/json');

        $this->ajaxRender(json_encode([
            'success' => empty($this->errors),
            'messages' => empty($this->errors) ? $this->message : $this->errors,
            'topContent' => $this->getDisplayTopContent(),
        ]));

        exit;
    }

    private function getDisplayTopContent()
    {
        $hook = $this->get(DisplayTop::class);

        return $hook->execute([]);
    }
}
