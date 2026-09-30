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
            <div class="sort-option">
                <a
                    class="sort-option__field{if $sort == 'date'} is-active{/if}"
                    href="/categories/{$category.slug|escape:'url'}?sort=date&amp;direction={$direction|escape:'url'}"
                >Дата</a>
                <div class="sort-option__directions">
                    <a
                        class="sort-option__direction{if $sort == 'date' && $direction == 'desc'} is-active{/if}"
                        href="/categories/{$category.slug|escape:'url'}?sort=date&amp;direction=desc"
                        aria-label="Сначала новые"
                    >↓</a>
                    <a
                        class="sort-option__direction{if $sort == 'date' && $direction == 'asc'} is-active{/if}"
                        href="/categories/{$category.slug|escape:'url'}?sort=date&amp;direction=asc"
                        aria-label="Сначала старые"
                    >↑</a>
                </div>
            </div>

            <div class="sort-option">
                <a
                    class="sort-option__field{if $sort == 'views'} is-active{/if}"
                    href="/categories/{$category.slug|escape:'url'}?sort=views&amp;direction={$direction|escape:'url'}"
                >Просмотры</a>
                <div class="sort-option__directions">
                    <a
                        class="sort-option__direction{if $sort == 'views' && $direction == 'desc'} is-active{/if}"
                        href="/categories/{$category.slug|escape:'url'}?sort=views&amp;direction=desc"
                        aria-label="Сначала популярные"
                    >↓</a>
                    <a
                        class="sort-option__direction{if $sort == 'views' && $direction == 'asc'} is-active{/if}"
                        href="/categories/{$category.slug|escape:'url'}?sort=views&amp;direction=asc"
                        aria-label="Сначала с меньшим количеством просмотров"
                    >↑</a>
                </div>
            </div>
        </nav>
    </div>

    <div class="post-grid">
        {foreach $posts as $post}
            {include file="partials/post-card.tpl" post=$post}
        {/foreach}
    </div>

    {include file="partials/pagination.tpl"}
{/block}
