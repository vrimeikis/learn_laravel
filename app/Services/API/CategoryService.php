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
use App\DTO\CategoriesDTO;
use App\DTO\CategoryDTO;
use App\DTO\PaginatorDTO;
use App\Exceptions\CategoryException;
use App\Services\ApiService;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Class CategoryService
 * @package App\Services\API
 */
class CategoryService extends ApiService
{
    /**
     * @return PaginatorDTO
     * @throws \App\Exceptions\ApiDataException
     * @throws CategoryException
     */
    public function getPaginateDTOData(): PaginatorDTO
    {
        /** @var LengthAwarePaginator $categories */
        $categories = Category::paginate(self::PER_PAGE);

        if ($categories->isEmpty()) {
            throw CategoryException::noData();
        }

        $categoriesDTO = new CategoriesDTO();

        /** @var Category $category */
        foreach ($categories as $category) {
            $categoriesDTO->setCategoryData(
                new CategoryDTO($category->id, $category->title, $category->slug)
            );
        }

        $paginatorDTO = new PaginatorDTO(
            $categories->currentPage(),
            collect($categoriesDTO)->get('data'),
            $categories->lastPage(),
            $categories->perPage(),
            $categories->total(),
            $categories->nextPageUrl(),
            $categories->previousPageUrl());

        return $paginatorDTO;
    }

    /**
     * @param int $categoryId
     * @return CategoryDTO
     */
    public function getById(int $categoryId): CategoryDTO
    {
        /** @var Category $category */
        $category = Category::findOrFail($categoryId);

        return new CategoryDTO($category->id, $category->title, $category->slug);
    }
}
