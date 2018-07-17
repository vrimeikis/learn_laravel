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

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Response;

/**
 * Class ArticleException
 * @package App\Exceptions
 */
class ArticleException extends Exception
{
    const CODE_NO_DATA = 1001;

    /**
     * @return ArticleException
     */
    public static function noData(): ArticleException
    {
        return new self('No data found', self::CODE_NO_DATA);
    }
}
