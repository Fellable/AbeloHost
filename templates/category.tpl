{extends file="layouts/main.tpl"}

{block name="title"}{$category.name|escape}{/block}

{block name="content"}
    <header class="page-header">
        <h1>{$category.name|escape}</h1>
        <p>{$category.description|escape}</p>
    </header>

    <nav class="sort-controls" aria-label="Сортировка статей">
        <a
            href="/categories/{$category.slug|escape:'url'}?sort=date"
            {if $sort == 'date'}aria-current="page"{/if}
        >Сначала новые</a>
        <a
            href="/categories/{$category.slug|escape:'url'}?sort=views"
            {if $sort == 'views'}aria-current="page"{/if}
        >По просмотрам</a>
    </nav>

    <div class="post-grid">
        {foreach $posts as $post}
            {include file="partials/post-card.tpl" post=$post}
        {/foreach}
    </div>

    {include file="partials/pagination.tpl"}
{/block}
