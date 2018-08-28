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

use App\Category;
use App\DTO\CategoriesDTO;
use App\DTO\CategoryDTO;
use App\DTO\PaginatorDTO;
use App\Exceptions\CategoryException;
use App\Repositories\CategoryRepository;
use App\Services\API\CategoryService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class CategoryServiceTest
 * @package Tests\Unit\Services\API
 */
class CategoryServiceTest extends TestCase
{
    /**
     * @test
     * @group category
     * @group category-service
     */
    public function it_should_create_singleton_instance(): void
    {
        $this->assertInstanceOf(CategoryService::class, $this->getTestClassInstance());
        $this->assertSame($this->getTestClassInstance(), $this->getTestClassInstance());
    }

    /**
     * @test
     * @group category
     * @group category-service
     * @throws \App\Exceptions\ApiDataException
     * @throws \ReflectionException
     */
    public function it_should_fail_on_paginate_data(): void
    {
        $this->initPHPUnitMock(CategoryRepository::class, null, ['paginate'])
            ->expects($this->once())
            ->method('paginate')
            ->willReturn(new LengthAwarePaginator(null, 0, 15));

        $this->expectException(CategoryException::class);
        $this->expectExceptionMessage(CategoryException::noData()->getMessage());
        $this->expectExceptionCode(CategoryException::noData()->getCode());

        $this->getTestClassInstance()->getPaginateDTOData();
    }

    /**
     * @test
     * @group category
     * @group category-service
     * @throws \App\Exceptions\ApiDataException
     * @throws \ReflectionException
     */
    public function it_should_get_paginator_dto_data(): void
    {
        $count = 5;

        /** @var Collection|Category[] $categories */
        $categories = factory(Category::class, $count)->make()
            ->each(function(Category $item, $key) {
                $item->id = $key + 1;
            });

        $this->initPHPUnitMock(CategoryRepository::class, null, ['paginate'])
            ->expects($this->once())
            ->method('paginate')
            ->willReturn(new LengthAwarePaginator($categories, $count, 15));

        $expectData = new CategoriesDTO();

        $categories->each(function(Category $category) use (&$expectData) {
            $expectData->setCategoryData(new CategoryDTO(
                $category->id,
                $category->title,
                $category->slug
            ));
        });

        $result = $this->getTestClassInstance()->getPaginateDTOData();

        $this->assertInstanceOf(PaginatorDTO::class, $result);
        $this->assertEquals(
            collect($expectData)->get('data'),
            collect($result)->get('data')
        );
    }

    /**
     * @test
     * @group category
     * @group category-service
     * @throws \Exception
     */
    public function it_should_fail_by_id(): void
    {
        $id = mt_rand(1, 10);

        $this->initPHPUnitMock(CategoryRepository::class, null, ['findOrFail'])
            ->expects($this->once())
            ->method('findOrFail')
            ->with($id)
            ->willThrowException(new ModelNotFoundException());

        $this->expectException(ModelNotFoundException::class);

        $this->getTestClassInstance()->getById($id);
    }

    /**
     * @test
     * @group category
     * @group category-service
     * @throws \Exception
     */
    public function it_should_return_category_dto_by_id(): void
    {
        /** @var Category $category */
        $category = factory(Category::class)->make([
            'id' => mt_rand(1, 10),
        ]);

        $this->initPHPUnitMock(CategoryRepository::class, null, ['findOrFail'])
            ->expects($this->once())
            ->method('findOrFail')
            ->with($category->id)
            ->willReturn($category);

        $expectData = new CategoryDTO($category->id, $category->title, $category->slug);

        $result = $this->getTestClassInstance()->getById($category->id);

        $this->assertInstanceOf(CategoryDTO::class, $result);
        $this->assertEquals($expectData, $result);
    }

    /**
     * @return CategoryService
     */
    private function getTestClassInstance(): CategoryService
    {
        return $this->app->make(CategoryService::class);
    }
}
