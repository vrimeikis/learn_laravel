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

use Throwable;
use App\Exceptions\ArticleException;
use App\Services\API\ArticleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\LengthAwarePaginator;

class ArticleController extends Controller
{
    private $articleService;

    public function __construct(ArticleService $articleService)
    {
        $this->articleService = $articleService;
    }

    public function getPaginate(Request $request): JsonResponse
    {
        try {
            /** @var LengthAwarePaginator $articles */
            $articles = $this->articleService->getPaginateData((int)$request->page);

            return response()->json([
                'status' => true,
                'data' => $articles->getCollection(),
                'current_page' => $articles->currentPage(),
                'total_page' => $articles->lastPage(),
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
                'message' => 'Somethink wrong',
                'code' => JsonResponse::HTTP_INTERNAL_SERVER_ERROR,
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getFullData(Request $request): JsonResponse
    {
        try {
            $articles = $this->articleService->getFullData((int)$request->page);

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
}
