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

namespace App\Services\ClientAPI;

use App\Author;

/**
 * Class ClientAuthorService
 * @package App\Services\ClientAPI
 */
class ClientAuthorService
{
    /**
     * @param \stdClass $data
     * @return Author
     */
    public function saveAuthorFromObject(\stdClass $data): Author
    {
        return Author::updateOrCreate(
            [
                'first_name' => $data->first_name,
                'last_name' => $data->last_name,
            ],
            ['reference_author_id' => $data->author_id]
        );
    }
}