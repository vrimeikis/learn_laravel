<?php

declare(strict_types = 1);

namespace App\Http\Controllers\Front;

use App\Repositories\CategoryRepository;
use App\Http\Controllers\Controller;
use Illuminate\View\View;

/**
 * Class CategoryController
 * @package App\Http\Controllers\Front
 */
class CategoryController extends Controller
{
    /**
     * @var CategoryRepository
     */
    private $categoryRepository;

    /**
     * CategoryController constructor.
     * @param CategoryRepository $categoryRepository
     */
    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }


    /**
     * @param string $categorySlug
     * @return View
     * @throws \Exception
     */
    public function showArticles(string $categorySlug): View
    {
        $category = $this->categoryRepository->getBySlug($categorySlug);
        $articles = $category->articles()->with(['author'])->paginate();

        return view('front.category', compact('category', 'articles'));
    }
}
