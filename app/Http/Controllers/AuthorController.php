<?php

declare(strict_types = 1);

namespace App\Http\Controllers;

use App\Enum\AuthorLocationTypeEnum;
use App\Http\Requests\AuthorRequest;
use App\Repositories\AuthorRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

/**
 * Class AuthorController
 * @package App\Http\Controllers
 */
class AuthorController extends Controller
{
    private $authorRepository;

    /**
     * AuthorController constructor.
     * @param AuthorRepository $authorRepository
     */
    public function __construct(AuthorRepository $authorRepository)
    {
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
        $authors = $this->authorRepository->paginate();

        return view('author.list', compact('authors'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     * @throws \ReflectionException
     */
    public function create(): View
    {
        $locationTypes = AuthorLocationTypeEnum::options();

        return view('author.create', compact('locationTypes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param AuthorRequest $request
     * @return RedirectResponse
     * @throws \Exception
     */
    public function store(AuthorRequest $request): RedirectResponse
    {
        $this->authorRepository->create([
            'first_name' => $request->getFirstName(),
            'last_name' => $request->getLastName(),
            'location_type' => $request->getLocationType(),
        ]);

        return redirect()
            ->route('author.index')
            ->with('status', 'Author created successfully!');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $authorId
     * @return View
     * @throws \Exception
     */
    public function edit(int $authorId): View
    {
        $author = $this->authorRepository->find($authorId);
        $locationTypes = AuthorLocationTypeEnum::options();

        return view('author.edit', compact('author', 'locationTypes'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param AuthorRequest $request
     * @param int $authorId
     * @return RedirectResponse
     * @throws \Exception
     */
    public function update(AuthorRequest $request, int $authorId): RedirectResponse
    {
        $this->authorRepository->update([
            'first_name' => $request->getFirstName(),
            'last_name' => $request->getLastName(),
            'location_type' => $request->getLocationType(),
        ], $authorId);

        return redirect()
            ->route('author.index')
            ->with('status', 'Author update successfully!');
    }

}
