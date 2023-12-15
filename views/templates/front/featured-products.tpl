{extends file="components/featured-products.tpl"}

{block name='featured_products_title'}
    {l s='Don\'t forget your favorite products' d='Module.Isfavoriteproducts.Front'}
{/block}

{block name='product_miniature'}
    {include file='module:is_favoriteproducts/views/templates/front/_partials/product.tpl' product=$product type='slider'}
{/block}
