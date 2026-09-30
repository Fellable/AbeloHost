<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Repositories\PostRepository;
use App\View\SmartyView;

/**
 * Контроллер страницы статьи
 */
final class PostController
{
    /**
     * @param PostRepository $postRepository Репозиторий статей
     * @param SmartyView $view Сервис рендеринга шаблонов
     */
    public function __construct(
        private readonly PostRepository $postRepository,
        private readonly SmartyView $view,
    ) {
    }

    /**
     * Отображает статью блога
     *
     * @param string $slug Slug статьи
     * @return string HTML страницы статьи
     */
    public function show(string $slug): string
    {
        $post = $this->postRepository->findBySlug($slug);

        if ($post === null) {
            http_response_code(404);

            return $this->view->render('errors/404.tpl');
        }

        $relatedPosts = $this->postRepository->findRelated($post['id']);
        $this->postRepository->incrementViews($post['id']);
        $post['views']++;

        return $this->view->render(
            'post.tpl',
            [
                'post' => $post,
                'relatedPosts' => $relatedPosts,
            ],
        );
    }
}
