<?php

declare(strict_types = 1);

namespace App\Http\Controllers;

use App\Article;
use App\Author;
use App\Category;
use App\Http\Requests\ArticleStoreRequest;
use App\Http\Requests\ArticleUpdateRequest;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class ArticleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index(): View
    {
        /** @var LengthAwarePaginator $articles */
        $articles = Article::paginate();

        return view('article.list', compact('articles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create(): View
    {
        /** @var Collection $categories */
        $categories = Category::all();

        /** @var Collection $authors */
        $authors = Author::all();

        return view('article.create', compact('authors', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ArticleStoreRequest $request
     * @return RedirectResponse
     */
    public function store(ArticleStoreRequest $request): RedirectResponse
    {
        $data = [
            'title' => $request->getTitle(),
            'description' => $request->getDescription(),
            'author_id' => $request->getAuthorId(),
            'slug' => $request->getSlug(),
        ];

        $article = Article::create($data);

        $article->categories()->attach($request->getCategoriesIds());

        return redirect()
            ->route('article.index')
            ->with('status', 'Article created successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Article $article
     * @return View
     */
    public function show(Article $article): View
    {
        return view('article.view', compact('article'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Article $article
     * @return View
     */
    public function edit(Article $article): View
    {
        $authors = Author::all();
        $categories = Category::all();

        return view('article.edit', compact('article', 'authors', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ArticleUpdateRequest $request
     * @param  \App\Article $article
     * @return RedirectResponse
     */
    public function update(ArticleUpdateRequest $request, Article $article): RedirectResponse
    {
        $article->title = $request->getTitle();
        $article->description = $request->getDescription();
        $article->author_id = $request->getAuthorId();
        $article->slug = $request->getSlug();

        $article->categories()->sync($request->getCategoriesIds());

        $article->save();

        return redirect()
            ->route('article.index')
            ->with('status', 'Updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Article $article
     * @return RedirectResponse
     * @throws \Exception
     */
    public function destroy(Article $article): RedirectResponse
    {
        $article->delete();

        return redirect()
            ->route('article.index')
            ->with('status', 'Article delete successfully!');
    }
}
