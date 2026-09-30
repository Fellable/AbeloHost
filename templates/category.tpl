{extends file="layouts/main.tpl"}

{block name="title"}{$category.name|escape}{/block}

{block name="content"}
    <h1>{$category.name|escape}</h1>
    <p>{$category.description|escape}</p>

    <div>
        {foreach $posts as $post}
            {include file="partials/post-card.tpl" post=$post}
        {/foreach}
    </div>
{/block}
