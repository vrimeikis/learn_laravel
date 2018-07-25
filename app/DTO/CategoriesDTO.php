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

namespace App\DTO;

use Illuminate\Support\Collection;


/**
 * Class CategoriesDTO
 * @package App\DTO
 */
class CategoriesDTO extends BaseDTO
{
    /**
     * @var Collection
     */
    private $collectionData;

    /**
     * CategoriesDTO constructor.
     */
    public function __construct()
    {
        $this->collectionData = collect();
    }

    /**
     * @param CategoryDTO $categoryDTO
     * @return CategoriesDTO
     */
    public function setCategoryData(CategoryDTO $categoryDTO): CategoriesDTO
    {
        $this->collectionData->push($categoryDTO);

        return $this;
    }

    /**
     * @return array
     */
    protected function jsonData(): array
    {
        return [
            'data' => $this->collectionData,
        ];
    }
}
