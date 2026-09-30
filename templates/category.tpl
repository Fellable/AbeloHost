{extends file="layouts/main.tpl"}

{block name="title"}{$category.name|escape}{/block}

{block name="content"}
    <h1>{$category.name|escape}</h1>
    <p>{$category.description|escape}</p>

    <nav aria-label="Сортировка статей">
        <a
            href="/categories/{$category.slug|escape:'url'}?sort=date"
            {if $sort == 'date'}aria-current="page"{/if}
        >Сначала новые</a>
        <a
            href="/categories/{$category.slug|escape:'url'}?sort=views"
            {if $sort == 'views'}aria-current="page"{/if}
        >По просмотрам</a>
    </nav>

    <div>
        {foreach $posts as $post}
            {include file="partials/post-card.tpl" post=$post}
        {/foreach}
    </div>
{/block}
