{extends file="layouts/main.tpl"}

{block name="title"}Блог о фотохостинге{/block}

{block name="content"}
    <h1>Блог о фотохостинге</h1>

    {foreach $categories as $category}
        <section>
            <h2>{$category.name|escape}</h2>
            <p>{$category.description|escape}</p>

            <div>
                {foreach $category.posts as $post}
                    {include file="partials/post-card.tpl" post=$post}
                {/foreach}
            </div>

            <a href="/categories/{$category.slug|escape:'url'}">Все статьи</a>
        </section>
    {/foreach}
{/block}
