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

namespace App\Http\Controllers;

use App\Http\Requests\CategoryStoreRequest;
use App\Http\Requests\CategoryUpdateRequest;
use App\Repositories\CategoryRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

/**
 * Class CategoryController
 * @package App\Http\Controllers
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
     * Display a listing of the resource.
     *
     * @return View
     * @throws \Exception
     */
    public function index(): View
    {
        $categories = $this->categoryRepository->paginate();

        return view('category.list', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create(): View
    {
        return view('category.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CategoryStoreRequest $request
     * @return RedirectResponse
     * @throws \Exception
     */
    public function store(CategoryStoreRequest $request): RedirectResponse
    {
        $this->categoryRepository->create([
            'title' => $request->getTitle(),
            'slug' => $request->getSlug(),
        ]);

        return redirect()
            ->route('category.index')
            ->with('status', 'Category created successfully!');
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param int $categoryId
     * @return View
     * @throws \Exception
     */
    public function edit(int $categoryId): View
    {
        $category = $this->categoryRepository->find($categoryId);

        return view('category.edit', compact('category'));
    }

    /**
     * @param CategoryUpdateRequest $request
     * @param int $categoryId
     * @return RedirectResponse
     * @throws \Exception
     */
    public function update(CategoryUpdateRequest $request, int $categoryId): RedirectResponse
    {
        $this->categoryRepository->update([
            'title' => $request->getTitle(),
            'slug' => $request->getSlug(),
        ], $categoryId);

        return redirect()
            ->route('category.index')
            ->with('status', 'Category updated successfully!');
    }

}
