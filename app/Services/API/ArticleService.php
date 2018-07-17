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

use App\Exceptions\ArticleException;
use \Exception;
use App\Article;
use Illuminate\Http\Response;
use Illuminate\Pagination\LengthAwarePaginator;

class ArticleService
{
    const PER_PAGE = 2;

    public function getPaginateData(int $page = 1)
    {
        /** @var LengthAwarePaginator $articles */
        $articles = Article::paginate(self::PER_PAGE, ['*'], 'page', $page);

        if ($articles->isEmpty()) {
            throw ArticleException::noData();
        }

        return $articles;
    }
}
