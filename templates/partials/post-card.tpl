<article class="post-card">
    <img
        class="post-card__image"
        src="{$post.preview_path|escape}"
        alt="{$post.image_alt|escape}"
        loading="lazy"
    >
    <div class="post-card__body">
        <h3 class="post-card__title">
            <a href="/posts/{$post.slug|escape:'url'}">{$post.title|escape}</a>
        </h3>
        <p class="post-card__description">{$post.description|escape}</p>
        <small class="post-card__meta">
            {$post.published_at|escape} · Просмотров: {$post.views}
        </small>
    </div>
</article>
