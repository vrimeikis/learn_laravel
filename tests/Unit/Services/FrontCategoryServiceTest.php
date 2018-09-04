<?php

declare(strict_types = 1);

namespace Tests\Unit\Services;

use App\Category;
use App\Repositories\CategoryRepository;
use App\Services\FrontCategoryService;
use Illuminate\Support\Collection;
use Tests\TestCase;

/**
 * Class FrontCategoryServiceTest
 * @package Tests\Unit\Services
 */
class FrontCategoryServiceTest extends TestCase
{
    /**
     * @test
     * @group front
     * @group front-category
     * @group front-category-service
     */
    public function it_should_create_singleton_instance(): void
    {
        $this->assertInstanceOf(FrontCategoryService::class, $this->getTestClassInstance());
        $this->assertSame($this->getTestClassInstance(), $this->getTestClassInstance());
    }

    /**
     * @test
     * @group front
     * @group front-category
     * @group front-category-service
     * @throws \ReflectionException
     * @throws \Exception
     */
    public function it_should_return_empty_collection(): void
    {
        $this->initPHPUnitMock(CategoryRepository::class, null, ['all'])
            ->expects($this->once())
            ->method('all')
            ->willReturn(collect());

        $result = $this->getTestClassInstance()->getList();

        $this->assertEmpty($result);
    }

    /**
     * @test
     * @group front
     * @group front-category
     * @group front-category-service
     * @throws \ReflectionException
     * @throws \Exception
     */
    public function it_should_return_collection(): void
    {
        /** @var Collection|Category[] $categories */
        $categories = factory(Category::class, 3)->make();

        $expectedData = collect();

        $categories->each(function(Category $category) use ($expectedData) {
            $expectedData->push([
                'title' => $category->title,
                'slug' => $category->slug,
            ]);
        });

        $this->initPHPUnitMock(CategoryRepository::class, null, ['all'])
            ->expects($this->once())
            ->method('all')
            ->willReturn($categories);

        $result = $this->getTestClassInstance()->getList();

        $this->assertEquals($expectedData->toArray(), $result->toArray());
    }

    /**
     * @return FrontCategoryService
     */
    private function getTestClassInstance(): FrontCategoryService
    {
        return $this->app->make(FrontCategoryService::class);
    }
}
