{extends file="layouts/main.tpl"}

{block name="title"}{$post.title|escape}{/block}

{block name="content"}
    <article>
        <img src="{$post.full_path|escape}" alt="{$post.image_alt|escape}">
        <h1>{$post.title|escape}</h1>
        <p>{$post.description|escape}</p>
        <div>{$post.content|escape|nl2br}</div>

        <ul>
            {foreach $post.categories as $category}
                <li>
                    <a href="/categories/{$category.slug|escape:'url'}">
                        {$category.name|escape}
                    </a>
                </li>
            {/foreach}
        </ul>

        <p>Дата публикации: {$post.published_at|escape}</p>
        <p>Просмотров: {$post.views}</p>
    </article>

    {if $relatedPosts}
        <section>
            <h2>Похожие статьи</h2>
            <div>
                {foreach $relatedPosts as $relatedPost}
                    {include file="partials/post-card.tpl" post=$relatedPost}
                {/foreach}
            </div>
        </section>
    {/if}
{/block}
