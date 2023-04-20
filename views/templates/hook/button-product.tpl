
<div class="col-auto mt-2 px-1">

    <a
        class="product-page__action-btn btn btn-light shadow rounded-circle favorite-btn p-2"
        href="#"
        data-action="toggleFavorite"
        data-active="false"
        {if isset($product.id) && isset($product.id_product_attribute)}
            data-key="{$product.id}_{$product.id_product_attribute}"
        {/if}
    >
        <div class="favorite-btn__content favorite-btn__content--added">
            <span class="material-icons product-page__action-btn-icon d-block">favorite</span>
        </div>
        <div class="favorite-btn__content favorite-btn__content--add">
            <span class="material-icons product-page__action-btn-icon d-block">favorite_border</span>
        </div>
    </a>
</div>
