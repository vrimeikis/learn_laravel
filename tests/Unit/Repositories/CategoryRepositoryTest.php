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

use App\Category;
use App\Repositories\CategoryRepository;
use Tests\MemoryDatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class CategoryRepositoryTest
 * @package Tests\Unit\Repositories
 */
class CategoryRepositoryTest extends TestCase
{
    use MemoryDatabaseMigrations;

    /**
     * @test
     */
    public function it_should_create_singleton_instance(): void
    {
        $this->assertInstanceOf(CategoryRepository::class, $this->getTestClassInstance());
        $this->assertSame($this->getTestClassInstance(), $this->getTestClassInstance());
    }

    /**
     * @test
     * @throws \Exception
     */
    public function it_should_return_null_on_get_by_slug(): void
    {
        $slug = str_random(10);

        $result = $this->getTestClassInstance()->getBySlug($slug);

        $this->assertNull($result);
    }

    /**
     * @test
     * @throws \Exception
     */
    public function it_should_return_category_by_slug(): void
    {
        factory(Category::class)->create();

        /** @var Category $category */
        $category = factory(Category::class)->create([
            'reference_category_id' => null,
        ]);

        factory(Category::class)->create();

        $result = $this->getTestClassInstance()->getBySlug($category->slug);

        $this->assertInstanceOf(Category::class, $result);
        $this->assertEquals($category->toArray(), $result->toArray());
    }

    /**
     * @return CategoryRepository
     */
    private function getTestClassInstance(): CategoryRepository
    {
        return $this->app->make(CategoryRepository::class);
    }
}
