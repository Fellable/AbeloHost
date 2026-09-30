{extends file="layouts/main.tpl"}

{block name="title"}{$category.name|escape}{/block}

{block name="content"}
    <header class="page-header">
        <h1>{$category.name|escape}</h1>
        <p>{$category.description|escape}</p>
    </header>

    <div class="category-toolbar">
        <span class="category-toolbar__label">Сортировать:</span>
        <nav class="sort-switcher" aria-label="Сортировка статей">
            <a
                href="/categories/{$category.slug|escape:'url'}?sort=date"
                {if $sort == 'date'}aria-current="page"{/if}
            >По дате</a>
            <a
                href="/categories/{$category.slug|escape:'url'}?sort=views"
                {if $sort == 'views'}aria-current="page"{/if}
            >По просмотрам</a>
        </nav>
    </div>

    <div class="post-grid">
        {foreach $posts as $post}
            {include file="partials/post-card.tpl" post=$post}
        {/foreach}
    </div>

    {include file="partials/pagination.tpl"}
{/block}
