<?php

declare(strict_types = 1);

namespace App\Services\ClientAPI;

use App\Article;
use App\Repositories\ArticleRepository;

/**
 * Class ClientArticleService
 * @package App\Services\ClientAPI
 */
class ClientArticleService
{
    /**
     * @var ClientAuthorService
     */
    private $clientAuthorService;
    /**
     * @var ClientCategoryService
     */
    private $clientCategoryService;

    /**
     * ClientArticleService constructor.
     * @param ClientAuthorService $clientAuthorService
     * @param ClientCategoryService $clientCategoryService
     */
    public function __construct(ClientAuthorService $clientAuthorService, ClientCategoryService $clientCategoryService)
    {
        $this->clientAuthorService = $clientAuthorService;
        $this->clientCategoryService = $clientCategoryService;
    }


    /**
     * @param \stdClass $data
     * @return Article
     * @throws \Exception
     */
    public function saveFromObject(\stdClass $data): Article
    {
        /** @var Article $article */
        $article = app(ArticleRepository::class)->updateOrCreate(
            ['slug' => $data->slug],
            [
                'title' => $data->title,
                'description' => $data->content,
                'reference_article_id' => $data->article_id,
                'author_id' => $this->clientAuthorService->saveAuthorFromObject($data->author)->id,
            ]
        );

        $categoriesIds = $this->clientCategoryService->getIdsFromObjects($data->categories);

        $article->categories()->sync($categoriesIds);

        return $article;
    }
}