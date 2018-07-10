<?php

declare(strict_types = 1);

namespace App\Http\Controllers;

use App\Author;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Class AuthorController
 * @package App\Http\Controllers
 */
class AuthorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index(): View
    {
        $authors = Author::all();

        return view('author.list', compact('authors'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create(): View
    {
        return view('author.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        Author::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
        ]);

        return redirect()
            ->route('author.index')
            ->with('status', 'Author created successfully!');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Author $author
     * @return View
     */
    public function edit(Author $author): View
    {
        return view('author.edit', compact('author'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Author $author
     * @return RedirectResponse
     */
    public function update(Request $request, Author $author): RedirectResponse
    {
        $author->first_name = $request->first_name;
        $author->last_name = $request->last_name;
        $author->save();

        return redirect()
            ->route('author.index')
            ->with('status', 'Author update successfully!');
    }

}
