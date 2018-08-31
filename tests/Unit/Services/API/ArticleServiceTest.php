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
use App\Author;
use App\Category;
use App\DTO\ArticleDTO;
use App\DTO\ArticleFullDTO;
use App\DTO\ArticlesDTO;
use App\DTO\AuthorDTO;
use App\DTO\CategoriesDTO;
use App\DTO\CategoryDTO;
use App\DTO\PaginatorDTO;
use App\Exceptions\ArticleException;
use App\Repositories\ArticleRepository;
use App\Services\API\ArticleService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class ArticleServiceTest
 * @package Tests\Unit\Services\API
 */
class ArticleServiceTest extends TestCase
{
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
     * @throws \ReflectionException
     */
    public function it_should_expect_exception_on_paginate_data(): void
    {
        $this->initPHPUnitMock(ArticleRepository::class, null, ['paginate'])
            ->expects($this->once())
            ->method('paginate')
            ->willReturn(new LengthAwarePaginator(null, 0, 15));

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
     * @throws \ReflectionException
     */
    public function it_should_return_paginator_on_paginate_data(): void
    {
        $count = 2;

        /** @var Collection|Article[] $articles */
        $articles = factory(Article::class, $count)->make([
            'author_id' => mt_rand(1, 10),
        ])
            ->each(function(Article $item, $key) {
                $item->id = $key + 1;
            });

        $this->initPHPUnitMock(ArticleRepository::class, null, ['paginate'])
            ->expects($this->once())
            ->method('paginate')
            ->willReturn(new LengthAwarePaginator($articles, $count, 15));

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
     * @group article-service1
     * @throws \App\Exceptions\ApiDataException
     * @throws \ReflectionException
     */
    public function it_should_expect_exception_on_full_data_pagination(): void
    {
        $this->initPHPUnitMock(ArticleRepository::class, null, ['getFullData'])
            ->expects($this->once())
            ->method('getFullData')
            ->willReturn(new LengthAwarePaginator(null, 0, 15));

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
     * @throws \ReflectionException
     */
    public function it_should_return_paginator_dto_with_data_on_full_without_categories(): void
    {
        /** @var Author $author */
        $author = factory(Author::class)->make([
            'id' => mt_rand(1, 10),
        ]);

        /** @var Collection|Article[] $articles */
        $articles = factory(Article::class, 2)->make([
            'author_id' => $author->id,
        ])->each(function(Article $article, $key) {
            $article->id = $key + 1;
        });

        $mockData = [];

        $articles->each(function(Article $article) use (&$mockData, $author) {

            $item = $article->toArray();

            array_set($item, 'author', (object)$author->toArray());
            array_set($item, 'categories', collect());

            array_push($mockData, (object)$item);
        });


        $this->initPHPUnitMock(ArticleRepository::class, null, ['getFullData'])
            ->expects($this->once())
            ->method('getFullData')
            ->willReturn(new LengthAwarePaginator($mockData, 2, 15));

        $expectedData = new ArticlesDTO();

        $articles->each(function(Article $article) use (&$expectedData, $author) {
            $articleDTO = new ArticleDTO($article->id, $article->title, $article->description);

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
     * @throws \ReflectionException
     */
    public function it_should_return_paginator_dto_with_data_on_full_with_categories(): void
    {
        /** @var Author $author */
        $author = factory(Author::class)->make([
            'id' => mt_rand(1, 10),
        ]);

        /** @var Collection|Category[] $categories */
        $categories = factory(Category::class, 2)->make()
            ->each(function(Category $category, $key) {
                $category->id = $key + 1;
            });

        /** @var Collection|Article[] $articles */
        $articles = factory(Article::class, 2)->make([
            'author_id' => $author->id,
        ])->each(function(Article $article, $key) {
            $article->id = $key + 1;
        });

        $mockData = [];

        $articles->each(function(Article $article) use (&$mockData, $author, $categories) {

            $item = $article->toArray();

            array_set($item, 'author', (object)$author->toArray());

            $cats = collect();

            $categories->each(function(Category $category) use ($cats) {
                $cats->push((object)$category->toArray());
            });

            array_set($item, 'categories', $cats);

            array_push($mockData, (object)$item);
        });


        $this->initPHPUnitMock(ArticleRepository::class, null, ['getFullData'])
            ->expects($this->once())
            ->method('getFullData')
            ->willReturn(new LengthAwarePaginator($mockData, 2, 15));

        $expectedData = new ArticlesDTO();

        $articles->each(function(Article $article) use (&$expectedData, $author, $categories) {
            $articleDTO = new ArticleDTO($article->id, $article->title, $article->description);

            $authorDTO = (new AuthorDTO())
                ->setAuthorId($author->id)
                ->setFirstName($author->first_name)
                ->setLastName($author->last_name);

            $categoriesDTO = new CategoriesDTO();

            $categories->each(function(Category $category) use ($categoriesDTO) {
                $categoriesDTO->setCategoryData(
                    new CategoryDTO($category->id, $category->title, $category->slug)
                );
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

        $this->initPHPUnitMock(ArticleRepository::class, null, ['findOrFail'])
            ->expects($this->once())
            ->method('findOrFail')
            ->with($id)
            ->willThrowException(new ModelNotFoundException());

        $this->expectException(ModelNotFoundException::class);

        $this->getTestClassInstance()->getByIdForApi($id);
    }

    /**
     * @test
     * @group article
     * @group article-service
     * @throws \Exception
     */
    public function it_should_return_article_dto_by_id(): void
    {
        /** @var Article $article */
        $article = factory(Article::class)->make([
            'id' => mt_rand(1, 10),
            'author_id' => mt_rand(1, 100),
        ]);

        $this->initPHPUnitMock(ArticleRepository::class, null, ['findOrFail'])
            ->expects($this->once())
            ->method('findOrFail')
            ->with($article->id)
            ->willReturn($article);

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

        $this->initPHPUnitMock(ArticleRepository::class, null, ['getFullDataById'])
            ->expects($this->once())
            ->method('getFullDataById')
            ->with($id)
            ->willThrowException(new ModelNotFoundException());

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
        /** @var Author $author */
        $author = factory(Author::class)->make([
            'id' => mt_rand(1, 100),
        ]);

        /** @var Article $article */
        $article = factory(Article::class)->make([
            'id' => mt_rand(1, 100),
            'author_id' => $author->id
        ]);

        $article->setAttribute('author', $author);
        $article->setAttribute('categories', collect());

        $this->initPHPUnitMock(ArticleRepository::class, null, ['getFullDataById'])
            ->expects($this->once())
            ->method('getFullDataById')
            ->with($article->id)
            ->willReturn($article);

        $articleDTO = new ArticleDTO($article->id, $article->title, $article->description);

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
        /** @var Author $author */
        $author = factory(Author::class)->make([
            'id' => mt_rand(1, 100),
        ]);

        /** @var Collection|Category[] $categories */
        $categories = factory(Category::class, 3)->make()
            ->each(function(Category $category, $key) {
                $category->id = $key + 1;
            });

        /** @var Article $article */
        $article = factory(Article::class)->make([
            'id' => mt_rand(1, 100),
            'author_id' => $author->id
        ]);

        $article->setAttribute('author', $author);
        $article->setAttribute('categories', $categories);

        $this->initPHPUnitMock(ArticleRepository::class, null, ['getFullDataById'])
            ->expects($this->once())
            ->method('getFullDataById')
            ->with($article->id)
            ->willReturn($article);

        $articleDTO = new ArticleDTO($article->id, $article->title, $article->description);

        $authorDTO = (new AuthorDTO())->setAuthorId($author->id)->setFirstName($author->first_name)->setLastName($author->last_name);

        $categoriesDTO = new CategoriesDTO();

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
