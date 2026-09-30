{extends file="layouts/main.tpl"}

{block name="title"}Блог о фотохостинге{/block}

{block name="content"}
    <header class="page-header">
        <h1>Блог о фотохостинге</h1>
        <p>Практические материалы о публикации, обработке и хранении изображений</p>
    </header>

    {foreach $categories as $category}
        <section class="category-section">
            <div class="category-section__header">
                <div>
                    <h2>
                        <a href="/categories/{$category.slug|escape:'url'}">{$category.name|escape}</a>
                    </h2>
                    <p>{$category.description|escape}</p>
                </div>
                <a class="button-link" href="/categories/{$category.slug|escape:'url'}">Все статьи</a>
            </div>

            <div class="post-grid">
                {foreach $category.posts as $post}
                    {include file="partials/post-card.tpl" post=$post}
                {/foreach}
            </div>
        </section>
    {/foreach}
{/block}
