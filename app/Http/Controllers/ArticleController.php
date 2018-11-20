<?php

declare(strict_types = 1);

namespace App\Http\Controllers;

use App\Article;
use App\Enum\ArticleTypeEnum;
use App\Http\Requests\ArticleStoreRequest;
use App\Http\Requests\ArticleUpdateRequest;
use App\Repositories\ArticleRepository;
use App\Repositories\AuthorRepository;
use App\Repositories\CategoryRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Collection;
use Illuminate\View\View;

/**
 * Class ArticleController
 * @package App\Http\Controllers
 */
class ArticleController extends Controller
{
    const COVER_DIRECTORY = 'articles';

    /**
     * @var ArticleRepository
     */
    private $articleRepository;
    /**
     * @var CategoryRepository
     */
    private $categoryRepository;
    /**
     * @var AuthorRepository
     */
    private $authorRepository;

    /**
     * ArticleController constructor.
     * @param ArticleRepository $articleRepository
     * @param CategoryRepository $categoryRepository
     * @param AuthorRepository $authorRepository
     */
    public function __construct(
        ArticleRepository $articleRepository,
        CategoryRepository $categoryRepository,
        AuthorRepository $authorRepository
    ) {
        $this->middleware('auth');
        $this->articleRepository = $articleRepository;
        $this->categoryRepository = $categoryRepository;
        $this->authorRepository = $authorRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return View
     * @throws \Exception
     */
    public function index(): View
    {
        /** @var LengthAwarePaginator $articles */
        $articles = $this->articleRepository->paginate();

        return view('article.list', compact('articles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     * @throws \Exception
     */
    public function create(): View
    {
        /** @var Collection $categories */
        $categories = $this->categoryRepository->all();

        /** @var Collection $authors */
        $authors = $this->authorRepository->all();

        $articleTypes = ArticleTypeEnum::options();

        return view('article.create', compact('authors', 'categories', 'articleTypes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ArticleStoreRequest $request
     * @return RedirectResponse
     * @throws \Exception
     */
    public function store(ArticleStoreRequest $request): RedirectResponse
    {
        $data = [
            'title' => $request->getTitle(),
            'cover' => $request->getCover() ? $request->getCover()->store(self::COVER_DIRECTORY) : null,
            'description' => $request->getDescription(),
            'author_id' => $request->getAuthorId(),
            'slug' => $request->getSlug(),
            'article_type' => $request->getArticleType(),
        ];

        /** @var Article $article */
        $article = $this->articleRepository->create($data);

        $article->categories()->attach($request->getCategoriesIds());

        return redirect()
            ->route('article.index')
            ->with('status', 'Article created successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param int $articleId
     * @return View
     * @throws \Exception
     */
    public function show(int $articleId): View
    {
        $article = $this->articleRepository->find($articleId);

        return view('article.view', compact('article'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $articleId
     * @return View
     * @throws \Exception
     */
    public function edit(int $articleId): View
    {
        $article = $this->articleRepository->find($articleId);

        $authors = $this->authorRepository->all();
        $categories = $this->categoryRepository->all();
        $articleTypes = ArticleTypeEnum::options();

        return view('article.edit', compact('article', 'authors', 'categories', 'articleTypes'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ArticleUpdateRequest $request
     * @param int $articleId
     * @return RedirectResponse
     * @throws \Exception
     */
    public function update(ArticleUpdateRequest $request, int $articleId): RedirectResponse
    {

        $data = [
            'title' => $request->getTitle(),
            'description' => $request->getDescription(),
            'author_id' => $request->getAuthorId(),
            'slug' => $request->getSlug(),
            'article_type' => $request->getArticleType(),
        ];

        if ($request->getCover()) {

            $data['cover'] = $request->getCover()->store(self::COVER_DIRECTORY);
        }

        $this->articleRepository->update($data, $articleId);

        /** @var Article $article */
        $article = $this->articleRepository->find($articleId);

        $article->categories()->sync($request->getCategoriesIds());


        return redirect()
            ->route('article.index')
            ->with('status', 'Updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $articleId
     * @return RedirectResponse
     * @throws \Exception
     */
    public function destroy(int $articleId): RedirectResponse
    {
        try {
            $this->articleRepository->delete(['id' => $articleId]);

            return redirect()
                ->route('article.index')
                ->with('status', 'Article delete successfully!');
        } catch (\Throwable $exception) {
            return redirect()
                ->route('article.index')
                ->with('error', $exception->getMessage());
        }
    }
}
