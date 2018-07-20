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

namespace App\Exceptions;

use Exception;

/**
 * Class ApiDataException
 * @package App\Exceptions
 */
class ApiDataException extends Exception
{
    /**
     *
     */
    const NO_DATA_FOUND = 1001;

    /**
     * @return ApiDataException
     */
    public static function noData(): ApiDataException
    {
        return new static('No data found', self::NO_DATA_FOUND);
    }
}
