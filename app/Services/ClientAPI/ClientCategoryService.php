<?php

declare(strict_types = 1);

namespace App\Services\ClientAPI;

use App\Category;

/**
 * Class ClientCategoryService
 * @package App\Services\ClientAPI
 */
class ClientCategoryService
{
    /**
     * @param \stdClass $data
     * @return Category
     */
    public function saveFromObject(\stdClass $data): Category
    {
        return Category::updateOrCreate(
            ['slug' => $data->slug],
            [
                'title' => $data->title,
                'reference_category_id' => $data->category_id
            ]
        );
    }

    /**
     * @param array $categories
     * @return array
     */
    public function getIdsFromObjects(array $categories = []): array
    {
        $ids = [];

        foreach ($categories as $category) {
           $ids[] = $this->saveFromObject($category)->id;
        }

        return $ids;
    }
}