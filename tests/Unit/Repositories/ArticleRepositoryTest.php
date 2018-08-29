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

namespace Tests\Unit\Repositories;

use App\Article;
use App\Category;
use App\Repositories\ArticleRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Tests\MemoryDatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class ArticleRepositoryTest
 * @package Tests\Unit\Repositories
 */
class ArticleRepositoryTest extends TestCase
{
    use MemoryDatabaseMigrations;

    /**
     * @test
     * @group article
     */
    public function it_should_create_singleton_instance(): void
    {
        $this->assertInstanceOf(ArticleRepository::class, $this->getTestClassInstance());
        $this->assertSame($this->getTestClassInstance(), $this->getTestClassInstance());
    }

    /**
     * @test
     * @group article
     * @throws \Exception
     */
    public function it_should_return_null_get_by_slug(): void
    {
        $slug = str_random(10);

        $this->assertNull($this->getTestClassInstance()->getBySlug($slug));
    }

    /**
     * @test
     * @group article
     * @throws \Exception
     */
    public function it_should_return_data_by_slug(): void
    {
        /** @var Article $article */
        $article = factory(Article::class)->create([
            'reference_article_id' => null,
        ]);

        factory(Article::class)->create();

        $result = $this->getTestClassInstance()->getBySlug($article->slug);

        $this->assertInstanceOf(Article::class, $result);
        $this->assertEquals($article->toArray(), $result->toArray());
    }

    /**
     * @test
     * @group article
     * @throws \Exception
     */
    public function it_should_return_null_by_slug_and_not_id_empty_db(): void
    {
        $slug = str_random(10);
        $id = mt_rand(1, 10);

        $this->assertNull($this->getTestClassInstance()->getBySlugAndNotId($slug, $id));
    }

    /**
     * @test
     * @group article
     * @throws \Exception
     */
    public function it_should_return_null_by_slug_and_not_id(): void
    {
        /** @var Article $article */
        $article = factory(Article::class)->create();

        $this->assertNull($this->getTestClassInstance()->getBySlugAndNotId($article->slug, $article->id));
    }

    /**
     * @test
     * @group article
     * @throws \Exception
     */
    public function it_should_return_data_by_slug_and_not_id(): void
    {
        /** @var Article $article1 */
        $article1 = factory(Article::class)->create([
            'reference_article_id' => null,
        ]);

        /** @var Article $article2 */
        $article2 = factory(Article::class)->create();

        $result = $this->getTestClassInstance()->getBySlugAndNotId($article1->slug, $article2->id);

        $this->assertInstanceOf(Article::class, $result);
        $this->assertEquals($article1->toArray(), $result->toArray());
    }

    /**
     * @test
     * @group article
     * @group article-repository
     */
    public function it_should_return_empty_paginator(): void
    {
        $result = $this->getTestClassInstance()->getFullData();

        $this->assertInstanceOf(LengthAwarePaginator::class, $result);
        $this->assertTrue($result->isEmpty());
    }

    /**
     * @test
     * @group article
     * @group article-repository
     */
    public function it_should_return_paginator_with_data(): void
    {
        /** @var Collection|Article[] $articles */
        $articles = factory(Article::class, 5)->create()
            ->each(function(Article $article) {
                $article->categories()
                    ->sync(factory(Category::class, 2)->create()->pluck('id')->all());
            });

        $expectedData = [];

        $articles->each(function(Article $article) use (&$expectedData) {
            $item = $article->toArray();

            array_set($item, 'author', (object)$article->author->toArray());

            $categories = collect();

            $article->categories->each(function(Category $category) use ($categories) {
                $cat = $category->toArray();
                array_forget($cat, 'pivot');
                $categories->push((object)$cat);
            });

            array_set($item, 'categories', $categories);

            array_push($expectedData, (object)$item);
        });

        $result = $this->getTestClassInstance()->getFullData();

        $this->assertInstanceOf(LengthAwarePaginator::class, $result);
        $this->assertTrue($result->isNotEmpty());
        $this->assertEquals($expectedData, $result->items());
    }

    /**
     * @return ArticleRepository
     */
    private function getTestClassInstance(): ArticleRepository
    {
        return $this->app->make(ArticleRepository::class);
    }

}
