{if $paginator->totalPages > 1}
    <nav class="pagination" aria-label="Страницы">
        {for $page = 1 to $paginator->totalPages}
            <a
                href="/categories/{$category.slug|escape:'url'}?sort={$sort|escape:'url'}&amp;direction={$direction|escape:'url'}&amp;page={$page}"
                {if $page == $paginator->currentPage}aria-current="page"{/if}
            >{$page}</a>
        {/for}
    </nav>
{/if}
