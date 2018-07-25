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

class CategoryDTO extends BaseDTO
{
    /**
     * @var int
     */
    private $categoryId;
    /**
     * @var string
     */
    private $title;
    /**
     * @var string
     */
    private $slug;

    /**
     * CategoryDTO constructor.
     * @param int $categoryId
     * @param string $title
     * @param string $slug
     */
    public function __construct(int $categoryId, string $title, string $slug)
    {
        $this->categoryId = $categoryId;
        $this->title = $title;
        $this->slug = $slug;
    }

    /**
     * @return array
     */
    protected function jsonData(): array
    {
        return [
            'category_id' => $this->categoryId,
            'title' => $this->title,
            'slug' => $this->slug,
        ];
    }
}
