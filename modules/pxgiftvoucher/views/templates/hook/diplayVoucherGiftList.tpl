
<section>
    <h1>{l s='New products' d='Modules.Newproducts.Shop'}</h1>
    <div class="products">
        asd
        {foreach from=$products item="product"}
            asddd
            {include file="catalog/_partials/miniatures/product.tpl" product=$product}
        {/foreach}
    </div>
</section>

