<?php
/**
 * @copyright C VR Solutions 2018
 *
 * This software is the property of VR Solutions
 * and is protected by copyright law – it is NOT freeware.
 *
 * Any unauthorized use of this software without a valid license key
 * is a violation of the license agreement and will be prosecuted by
 * civil and criminal law.
 *
 * Contact VR Solutions:
 * E-mail: vytautas.rimeikis@gmail.com
 * http://www.vrwebdeveloper.lt
 */

declare(strict_types = 1);

namespace App\Services\API;

use App\Category;
use App\DTO\ArticleDTO;
use App\DTO\ArticleFullDTO;
use App\DTO\ArticlesDTO;
use App\DTO\AuthorDTO;
use App\DTO\CategoriesDTO;
use App\DTO\CategoryDTO;
use App\DTO\PaginatorDTO;
use App\Exceptions\ArticleException;
use App\Article;
use App\Services\ApiService;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Class ArticleService
 * @package App\Services\API
 */
class ArticleService extends ApiService
{
    /**
     * @return PaginatorDTO
     * @throws \App\Exceptions\ApiDataException
     */
    public function getPaginateData(): PaginatorDTO
    {
        /** @var LengthAwarePaginator $articles */
        $articles = Article::paginate(self::PER_PAGE);

        if ($articles->isEmpty()) {
            throw ArticleException::noData();
        }

        $articlesDTO = new ArticlesDTO();

        /** @var Article $article */
        foreach ($articles as $article) {
            $articlesDTO->setArticle(
                new ArticleDTO($article->id, $article->title, $article->description)
            );
        }

        return new PaginatorDTO(
            $articles->currentPage(),
            collect($articlesDTO)->get('data'),
            $articles->lastPage(),
            $articles->perPage(),
            $articles->total(),
            $articles->nextPageUrl(),
            $articles->previousPageUrl()
        );
    }

    /**
     * @return LengthAwarePaginator
     * @throws \App\Exceptions\ApiDataException
     */
    public function getFullData(): PaginatorDTO
    {
        /** @var LengthAwarePaginator $articles */
        $articles = Article::with(['author', 'categories'])->paginate(self::PER_PAGE);

        if ($articles->isEmpty()) {
            throw ArticleException::noData();
        }

        $articlesDTO = new ArticlesDTO();

        /** @var Article $article */
        foreach ($articles as $article) {
            $articleDTO = new ArticleDTO($article->id, $article->title, $article->description);

            $author = $article->author;
            $authorDTO = (new AuthorDTO())->setAuthorId($author->id)
                ->setFirstName($author->first_name)
                ->setLastName($author->last_name);

            $categoriesDTO = new CategoriesDTO();

            foreach ($article->categories as $category) {
                $categoriesDTO->setCategoryData(
                    new CategoryDTO($category->id, $category->title, $category->slug)
                );
            }

            $articlesDTO->setArticle(
                new ArticleFullDTO($articleDTO, $authorDTO, $categoriesDTO)
            );
        }

        return new PaginatorDTO(
            $articles->currentPage(),
            collect($articlesDTO)->get('data'),
            $articles->lastPage(),
            $articles->perPage(),
            $articles->total(),
            $articles->nextPageUrl(),
            $articles->previousPageUrl()
        );
    }

    /**
     * @param int $articleId
     * @return ArticleDTO
     */
    public function getByIdForApi(int $articleId): ArticleDTO
    {
        /** @var Article $article */
        $article = Article::findOrFail($articleId);

        return new ArticleDTO($article->id, $article->title, $article->description);
    }

    /**
     * @param int $articleId
     * @return ArticleFullDTO
     */
    public function getFullByIdForApi(int $articleId): ArticleFullDTO
    {
        /** @var Article $article */
        $article = Article::with('author', 'categories')->findOrFail($articleId);

        // make ArticleDtTO object
        $articleDTO = new ArticleDTO($article->id, $article->title, $article->description);

        //make AuthorDTO object
        $authorDTO = (new AuthorDTO())->setAuthorId($article->author->id)
            ->setFirstName($article->author->first_name)
            ->setLastName($article->author->last_name);

        // make empty CategoriesDTO object for CategoryDTO collection
        $categoriesDTO = new CategoriesDTO();

        /** @var Category $category */
        foreach ($article->categories as $category) {
            // push CategoryDTO to CategoriesDTO
            $categoriesDTO->setCategoryData(
                new CategoryDTO($category->id, $category->title, $category->slug)
            );
        }

        // return ArticleFullDTO object
        return new ArticleFullDTO($articleDTO, $authorDTO, $categoriesDTO);
    }
}
