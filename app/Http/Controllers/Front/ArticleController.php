<?php

declare(strict_types =1);

namespace App\Http\Controllers\Front;

use App\Article;
use App\Repositories\ArticleRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\View\View;

/**
 * Class ArticleController
 * @package App\Http\Controllers\Front
 */
class ArticleController extends Controller
{
    /**
     * @var ArticleRepository
     */
    private $articleRepository;

    /**
     * ArticleController constructor.
     * @param ArticleRepository $articleRepository
     */
    public function __construct(ArticleRepository $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }


    /**
     * Display a listing of the resource.
     *
     * @return View
     * @throws \Exception
     */
    public function index(): View
    {
        $articles = $this->articleRepository->paginate();

        return view('front.articles', compact('articles'));
    }

    /**
     * Display the specified resource.
     *
     * @param string $slug
     * @return View
     * @throws \Exception
     */
    public function show(string $slug): View
    {
        $article = $this->articleRepository->getBySlug($slug);

        return view('front.article', compact('article'));
    }
}
