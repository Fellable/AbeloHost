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
                href="/categories/{$category.slug|escape:'url'}?sort=date&amp;direction=desc"
                {if $sort == 'date' && $direction == 'desc'}aria-current="page"{/if}
            >Дата ↓</a>
            <a
                href="/categories/{$category.slug|escape:'url'}?sort=date&amp;direction=asc"
                {if $sort == 'date' && $direction == 'asc'}aria-current="page"{/if}
            >Дата ↑</a>
            <a
                href="/categories/{$category.slug|escape:'url'}?sort=views&amp;direction=desc"
                {if $sort == 'views' && $direction == 'desc'}aria-current="page"{/if}
            >Просмотры ↓</a>
            <a
                href="/categories/{$category.slug|escape:'url'}?sort=views&amp;direction=asc"
                {if $sort == 'views' && $direction == 'asc'}aria-current="page"{/if}
            >Просмотры ↑</a>
        </nav>
    </div>

    <div class="post-grid">
        {foreach $posts as $post}
            {include file="partials/post-card.tpl" post=$post}
        {/foreach}
    </div>

    {include file="partials/pagination.tpl"}
{/block}
