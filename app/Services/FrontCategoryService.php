<?php

declare(strict_types = 1);

namespace App\Services;

use App\Category;
use App\Repositories\CategoryRepository;
use Illuminate\Support\Collection;

/**
 * Class FrontCategoryService
 * @package App\Services
 */
class FrontCategoryService
{
    /**
     * @var CategoryRepository
     */
    private $categoryRepository;

    /**
     * FrontCategoryService constructor.
     * @param CategoryRepository $categoryRepository
     */
    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * @return Collection
     * @throws \Exception
     */
    public function getList(): Collection
    {
        $categories = $this->categoryRepository->all();

        $return = collect();

        $categories->each(function(Category $category) use ($return) {
            $return->push([
                'title' => $category->title,
                'slug' => $category->slug,
            ]);
        });

        return $return;
    }
}