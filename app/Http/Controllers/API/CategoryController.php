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

use App\DTO\PaginatorDTO;
use App\Exceptions\CategoryException;
use App\Http\Controllers\Controller;
use App\Services\API\CategoryService;
use Illuminate\Http\JsonResponse;

/**
 * Class CategoryController
 * @package App\Http\Controllers\API
 */
class CategoryController extends Controller
{
    /**
     * @var CategoryService
     */
    private $categoryService;

    /**
     * CategoryController constructor.
     * @param CategoryService $categoryService
     */
    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            /** @var PaginatorDTO $categories */
            $categories = $this->categoryService->getPaginateDTOData();

            return response()->json([
                'status' => true,
                'data' => $categories,
            ]);
        } catch (CategoryException $exception) {
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
                    'message' => 'Something wrong',
                    'code' => JsonResponse::HTTP_INTERNAL_SERVER_ERROR,
                ],
                JsonResponse::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * @param int $category
     * @return JsonResponse
     */
    public function show(int $category): JsonResponse
    {
        try {
            $categoryData = $this->categoryService->getById($category);

            return response()->json([
                'status' => true,
                'data' => $categoryData,
            ]);
        } catch (\Throwable $exception) {
            return response()->json([
                'status' => false,
                'message' => $exception->getMessage(),
                'code' => $exception->getCode(),
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
