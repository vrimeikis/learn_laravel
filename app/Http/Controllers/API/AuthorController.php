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

namespace App\Http\Controllers\API;

use App\Exceptions\AuthorException;
use App\Services\API\AuthorService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
 * Class AuthorController
 * @package App\Http\Controllers\API
 */
class AuthorController extends Controller
{
    /**
     * @var AuthorService
     */
    private $authorService;

    /**
     * AuthorController constructor.
     * @param AuthorService $authorService
     */
    public function __construct(AuthorService $authorService)
    {
        $this->authorService = $authorService;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getPaginate(Request $request): JsonResponse
    {
        try {
            $authors = $this->authorService->getPaginateData((int)$request->page);

            return response()->json([
                'status' => true,
                'data' => $authors->getCollection(),
                'current_page' => $authors->currentPage(),
                'total_page' => $authors->lastPage(),
            ]);
        } catch (AuthorException $exception) {
            return response()->json(
                [
                    'status' => false,
                    'message' => $exception->getMessage(),
                    'code' => $exception->getCode(),
                ],
                JsonResponse::HTTP_NOT_FOUND
            );
        } catch (\Throwable $exception) {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'Somethink wrong',
                    'code' => JsonResponse::HTTP_INTERNAL_SERVER_ERROR,
                ],
                JsonResponse::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function getById(Request $request, int $id): JsonResponse
    {
        try {
            $author = $this->authorService->getById($id);

            return response()->json([
                'success' => true,
                'data' => $author,
            ]);
        } catch (ModelNotFoundException $exception) {
            logger($exception->getMessage(), [
                'code' => $exception->getCode(),
                'author-id' => $id,
                'path' => $request->path(),
                'url' => $request->url(),
            ]);
            return response()->json([
                'success' => false,
                'message' => 'No data found.',
                'code' => $exception->getCode(),
            ], JsonResponse::HTTP_NOT_FOUND);
        } catch (\Throwable $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Something wrong.',
                'code' => $exception->getCode(),
            ], JsonResponse::HTTP_NOT_FOUND);
        }
    }
}
