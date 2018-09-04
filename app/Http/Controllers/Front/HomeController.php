<?php

declare(strict_types = 1);

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Repositories\ArticleRepository;

/**
 * Class HomeController
 * @package App\Http\Controllers\Front
 */
class HomeController extends Controller
{
    /**
     * @var ArticleRepository
     */
    private $articleRepository;

    /**
     * HomeController constructor.
     * @param ArticleRepository $articleRepository
     */
    public function __construct(ArticleRepository $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function index()
    {
        $articles = $this->articleRepository->makeQuery()
            ->with('author')
            ->orderBy('created_at', 'desc')
            ->limit(4)
            ->get();

        return view('home', compact('articles'));
    }
}