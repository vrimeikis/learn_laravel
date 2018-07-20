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

namespace App\Services\API;

use App\Category;
use App\Exceptions\CategoryException;
use App\Services\ApiService;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Class CategoryService
 * @package App\Services\API
 */
class CategoryService extends ApiService
{
    /**
     * @param int $page
     * @return LengthAwarePaginator
     * @throws CategoryException
     */
    public function getPaginateData(int $page = 1): LengthAwarePaginator
    {
        /** @var LengthAwarePaginator $categories */
        $categories = Category::paginate(self::PER_PAGE, ['*'], 'page', $page);

        if ($categories->isEmpty()) {
            throw CategoryException::noData();
        }

        return $categories;
    }
}
