{extends file="layouts/main.tpl"}

{block name="title"}{$post.title|escape}{/block}

{block name="content"}
    <article class="post-page">
        <img
            class="post-page__image"
            src="{$post.full_path|escape}"
            alt="{$post.image_alt|escape}"
        >
        <h1 class="post-page__title">{$post.title|escape}</h1>
        <p class="post-page__lead">{$post.description|escape}</p>
        <div class="post-page__content">{$post.content|escape|nl2br}</div>

        <ul class="post-categories">
            {foreach $post.categories as $category}
                <li>
                    <a href="/categories/{$category.slug|escape:'url'}">
                        {$category.name|escape}
                    </a>
                </li>
            {/foreach}
        </ul>

        <div class="post-meta">
            <span>Дата публикации: {$post.published_at|escape}</span>
            <span>Просмотров: {$post.views}</span>
        </div>
    </article>

    {if $relatedPosts}
        <section class="related-posts">
            <div class="related-posts__header">
                <h2>Похожие статьи</h2>
            </div>
            <div class="post-grid">
                {foreach $relatedPosts as $relatedPost}
                    {include file="partials/post-card.tpl" post=$relatedPost}
                {/foreach}
            </div>
        </section>
    {/if}
{/block}
