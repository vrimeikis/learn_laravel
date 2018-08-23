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

namespace Tests\Unit\Services\API;

use App\Article;
use App\Category;
use App\DTO\ArticleDTO;
use App\DTO\ArticleFullDTO;
use App\DTO\ArticlesDTO;
use App\DTO\AuthorDTO;
use App\DTO\CategoriesDTO;
use App\DTO\CategoryDTO;
use App\DTO\PaginatorDTO;
use App\Exceptions\ArticleException;
use App\Services\API\ArticleService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;
use Tests\MemoryDatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class ArticleServiceTest
 * @package Tests\Unit\Services\API
 */
class ArticleServiceTest extends TestCase
{
    use  MemoryDatabaseMigrations;

    /**
     * @test
     * @group article
     * @group article-service
     */
    public function it_should_create_singleton_instance(): void
    {
        $this->assertInstanceOf(ArticleService::class, $this->getTestClassInstance());
        $this->assertSame($this->getTestClassInstance(), $this->getTestClassInstance());
    }

    /**
     * @test
     * @group article
     * @group article-service
     * @throws \App\Exceptions\ApiDataException
     */
    public function it_should_expect_exception_on_paginate_data(): void
    {
        $this->expectException(ArticleException::class);
        $this->expectExceptionMessage(ArticleException::noData()->getMessage());
        $this->expectExceptionCode(ArticleException::noData()->getCode());

        $this->getTestClassInstance()->getPaginateData();
    }

    /**
     * @test
     * @group article
     * @group article-service
     * @throws \App\Exceptions\ApiDataException
     */
    public function it_should_return_paginator_on_paginate_data(): void
    {
        /** @var Collection|Article[] $articles */
        $articles = factory(Article::class, 2)->create();

        $expectedData = new ArticlesDTO();

        $articles->each(function(Article $article) use (&$expectedData) {
            $expectedData->setArticle(new ArticleDTO(
                $article->id,
                $article->title,
                $article->description
            ));
        });

        $result = $this->getTestClassInstance()->getPaginateData();

        $this->assertInstanceOf(PaginatorDTO::class, $result);
        $this->assertEquals(
            collect($expectedData)->get('data'),
            collect($result)->get('data')
        );
    }

    /**
     * @test
     * @group article
     * @group article-service
     * @throws \App\Exceptions\ApiDataException
     */
    public function it_should_expect_exception_on_full_data_pagination(): void
    {
        $this->expectException(ArticleException::class);
        $this->expectExceptionMessage(ArticleException::noData()->getMessage());
        $this->expectExceptionCode(ArticleException::noData()->getCode());

        $this->getTestClassInstance()->getFullData();
    }

    /**
     * @test
     * @group article
     * @group article-service
     * @throws \App\Exceptions\ApiDataException
     */
    public function it_should_return_paginator_dto_with_data_on_full_without_categories(): void
    {
        /** @var Collection|Article[] $articles */
        $articles = factory(Article::class, 2)->create();

        $expectedData = new ArticlesDTO();

        $articles->each(function(Article $article) use (&$expectedData) {
            $articleDTO = new ArticleDTO($article->id, $article->title, $article->description);

            $author = $article->author;
            $authorDTO = (new AuthorDTO())
                ->setAuthorId($author->id)
                ->setFirstName($author->first_name)
                ->setLastName($author->last_name);

            $categoriesDTO = new CategoriesDTO();

            $expectedData->setArticle(
                new ArticleFullDTO(
                    $articleDTO,
                    $authorDTO,
                    $categoriesDTO
                )
            );
        });

        $result = $this->getTestClassInstance()->getFullData();

        $this->assertInstanceOf(PaginatorDTO::class, $result);

        $this->assertEquals(
            collect($expectedData)->get('data'),
            collect($result)->get('data')
        );
    }

    /**
     * @test
     * @group article
     * @group article-service
     * @throws \App\Exceptions\ApiDataException
     */
    public function it_should_return_paginator_dto_with_data_on_full_with_categories(): void
    {
        /** @var Collection|Article[] $articles */
        $articles = factory(Article::class, 2)->create()
            ->each(function(Article $item) {
                $item->categories()->saveMany(factory(Category::class, 3)->make());
            });

        $expectedData = new ArticlesDTO();

        $articles->each(function(Article $article) use (&$expectedData) {
            $articleDTO = new ArticleDTO($article->id, $article->title, $article->description);

            $author = $article->author;
            $authorDTO = (new AuthorDTO())
                ->setAuthorId($author->id)
                ->setFirstName($author->first_name)
                ->setLastName($author->last_name);

            $categoriesDTO = new CategoriesDTO();

            $categories = $article->categories;
            $categories->each(function(Category $category) use (&$categoriesDTO) {
                $categoriesDTO->setCategoryData(new CategoryDTO(
                    $category->id,
                    $category->title,
                    $category->slug
                ));
            });

            $expectedData->setArticle(
                new ArticleFullDTO(
                    $articleDTO,
                    $authorDTO,
                    $categoriesDTO
                )
            );
        });

        $result = $this->getTestClassInstance()->getFullData();

        $this->assertInstanceOf(PaginatorDTO::class, $result);

        $this->assertEquals(
            collect($expectedData)->get('data'),
            collect($result)->get('data')
        );
    }

    /**
     * @test
     * @group article
     * @group article-service
     * @throws \Exception
     */
    public function it_should_expect_not_found_exception_by_id(): void
    {
        $id = mt_rand(1, 10);

        $this->expectException(ModelNotFoundException::class);

        $this->getTestClassInstance()->getByIdForApi($id);
    }

    /**
     * @test
     * @group article
     * @group article->service
     * @throws \Exception
     */
    public function it_should_return_article_dto_by_id(): void
    {
        /** @var Article $article */
        $article = factory(Article::class)->create();

        $result = $this->getTestClassInstance()->getByIdForApi($article->id);

        $this->assertEquals(new ArticleDTO($article->id, $article->title, $article->description), $result);
    }

    /**
     * @test
     * @group article
     * @group article-service
     * @throws \Exception
     */
    public function it_should_expect_not_found_exception_by_id_full_data(): void
    {
        $id = mt_rand(1, 10);

        $this->expectException(ModelNotFoundException::class);

        $this->getTestClassInstance()->getFullByIdForApi($id);
    }

    /**
     * @test
     * @group article
     * @group article-service
     * @throws \Exception
     */
    public function it_should_return_full_data_dto_by_id_without_categories(): void
    {
        /** @var Article $article */
        $article = factory(Article::class)->create();

        $articleDTO = new ArticleDTO($article->id, $article->title, $article->description);

        $author = $article->author;
        $authorDTO = (new AuthorDTO())->setAuthorId($author->id)->setFirstName($author->first_name)->setLastName($author->last_name);

        $categoriesDTO = new CategoriesDTO();

        $articleFullDTO = new ArticleFullDTO($articleDTO, $authorDTO, $categoriesDTO);

        $result = $this->getTestClassInstance()->getFullByIdForApi($article->id);

        $this->assertEquals($articleFullDTO, $result);
    }

    /**
     * @test
     * @group article
     * @group article-service
     * @throws \Exception
     */
    public function it_should_return_full_data_dto_by_id_with_categories(): void
    {
        /** @var Article $article */
        $article = factory(Article::class)->create();
        $article->categories()->sync(factory(Category::class, 3)->create()->pluck('id')->all());

        $articleDTO = new ArticleDTO($article->id, $article->title, $article->description);

        $author = $article->author;
        $authorDTO = (new AuthorDTO())->setAuthorId($author->id)->setFirstName($author->first_name)->setLastName($author->last_name);

        $categoriesDTO = new CategoriesDTO();
        $categories = $article->categories;
        $categories->each(function(Category $category) use (&$categoriesDTO) {
            $categoriesDTO->setCategoryData(new CategoryDTO($category->id, $category->title, $category->slug));
        });

        $articleFullDTO = new ArticleFullDTO($articleDTO, $authorDTO, $categoriesDTO);

        $result = $this->getTestClassInstance()->getFullByIdForApi($article->id);

        $this->assertEquals($articleFullDTO, $result);
    }

    /**
     * @return ArticleService
     */
    private function getTestClassInstance(): ArticleService
    {
        return $this->app->make(ArticleService::class);
    }
}
