<article>
    <img
        src="{$post.preview_path|escape}"
        alt="{$post.image_alt|escape}"
        loading="lazy"
    >
    <h3>
        <a href="/posts/{$post.slug|escape:'url'}">{$post.title|escape}</a>
    </h3>
    <p>{$post.description|escape}</p>
    <small>{$post.published_at|escape} · Просмотров: {$post.views}</small>
</article>
