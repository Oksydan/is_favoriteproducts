<?php

use Oksydan\IsFavoriteProducts\Services\FavoriteProductService;
use Oksydan\IsFavoriteProducts\DTO\FavoriteProduct as FavoriteProductDTO;

class Is_favoriteproductsAjaxModuleFrontController extends ModuleFrontController
{
    private $message = [];

    private $favotireProductService;

    private function getFavoriteProductService(): FavoriteProductService
    {
        if ($this->favotireProductService instanceof FavoriteProductService === false) {
            $this->favotireProductService = $this->get(FavoriteProductService::class);
        }

        return $this->favotireProductService;
    }

    private function checkProductExistence(): bool
    {
        $idProduct = (int) Tools::getValue('id_product', 0);
        $idProductAttribute = (int) Tools::getValue('id_product_attribute', 0);
        $exists = $this->getFavoriteProductService()->isProductExistsInStore($idProduct, $idProductAttribute, $this->context->shop->id);

        if (!$exists) {
            $this->errors[] = $this->module->getTranslator()->trans('Product does not exist in store', [], 'Modules.IsFavoriteProducts.Front');
        }

        return $exists;
    }

    private function createFavoriteProductDto(): FavoriteProductDTO
    {
        $idProduct = (int) Tools::getValue('id_product', 0);
        $idProductAttribute = (int) Tools::getValue('id_product_attribute', 0);

        $favoriteProduct = new FavoriteProductDTO();

        $favoriteProduct->setIdProduct($idProduct);
        $favoriteProduct->setIdProductAttribute($idProductAttribute);
        $favoriteProduct->setIdShop($this->context->shop->id);

        if ($this->context->customer->isLogged()) {
            $favoriteProduct->setIdCustomer($this->context->customer->id);
        }

        return $favoriteProduct;
    }

    public function displayAjaxAddFavoriteProduct(): void
    {
        if ($this->checkProductExistence()) {
            $this->getFavoriteProductService()->addFavoriteProduct($this->createFavoriteProductDto());
        }

        $this->renderResponse();
    }

    public function displayAjaxRemoveFavoriteProduct(): void
    {
        if ($this->checkProductExistence()) {
            $this->getFavoriteProductService()->removeFavoriteProduct($this->createFavoriteProductDto());
        }

        $this->renderResponse();
    }

    private function renderResponse(): void
    {
        $this->ajaxRender(json_encode([
            'success' => empty($this->errors),
            'messages' => empty($this->errors)? $this->message : $this->errors,
        ]));
    }
}
