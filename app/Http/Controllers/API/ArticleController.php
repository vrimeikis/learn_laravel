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

use App\DTO\ArticleDTO;
use App\DTO\ArticlesDTO;
use App\DTO\PaginatorDTO;
use App\Exceptions\ArticleException;
use App\Http\Controllers\Controller;
use App\Services\API\ArticleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Throwable;

/**
 * Class ArticleController
 * @package App\Http\Controllers\API
 */
class ArticleController extends Controller
{
    /**
     * @var ArticleService
     */
    private $articleService;

    /**
     * ArticleController constructor.
     * @param ArticleService $articleService
     */
    public function __construct(ArticleService $articleService)
    {
        $this->articleService = $articleService;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getPaginate(Request $request): JsonResponse
    {
        try {
            /** @var PaginatorDTO $articles */
            $articles = $this->articleService->getPaginateData();

            return response()->json([
                'status' => true,
                'data' => $articles,
            ]);
        } catch (ArticleException $exception) {

            logger($exception->getMessage(), [
                    'code' => $exception->getCode(),
                    'page' => $request->page,
                    'url' => $request->fullUrl(),
                ]
            );

            return response()->json([
                'status' => false,
                'message' => $exception->getMessage(),
                'code' => $exception->getCode(),
            ], JsonResponse::HTTP_NOT_FOUND);
        } catch (Throwable $exception) {

            return response()->json([
                'status' => false,
                'message' => $exception->getMessage(),
                'code' => JsonResponse::HTTP_INTERNAL_SERVER_ERROR,
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @return JsonResponse
     */
    public function getFullData(): JsonResponse
    {
        try {
            $articles = $this->articleService->getFullData();

            return response()->json([
                'success' => true,
                'data' => $articles,
            ]);
        } catch (ArticleException $exception) {
            return response()->json([
                'success' => false,
                'message' => $exception->getMessage(),
                'code' => $exception->getCode(),
            ], JsonResponse::HTTP_NOT_FOUND);
        } catch (Throwable $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Something wrong.',
                'code' => $exception->getCode(),
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param int $articleId
     * @return JsonResponse
     */
    public function getById(int $articleId): JsonResponse
    {
        try {
            $article = $this->articleService->getByIdForApi($articleId);

            return response()->json([
                'success' => true,
                'data' => $article
            ]);
        } catch (Throwable $exception) {
            return response()->json([
                'success' => false,
                'code' => $exception->getCode(),
                'message' => $exception->getMessage(),
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param int $articleId
     * @return JsonResponse
     */
    public function getByIdFull(int $articleId): JsonResponse
    {
        try {
            $articleFull = $this->articleService->getFullByIdForApi($articleId);

            return response()->json([
                'success' => true,
                'data' => $articleFull,
            ]);
        } catch (Throwable $exception) {
            return response()->json([
                'status' => false,
                'message' => $exception->getMessage(),
                'code' => $exception->getCode(),
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
