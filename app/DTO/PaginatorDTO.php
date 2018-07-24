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

class PaginatorDTO extends BaseDTO
{
    /**
     * @var int
     */
    private $currentPage;
    /**
     * @var Collection
     */
    private $data;
    /**
     * @var int
     */
    private $lastPage;
    /**
     * @var string
     */
    private $nextPageUrl;
    /**
     * @var string
     */
    private $prevPageUrl;
    /**
     * @var int
     */
    private $total;
    /**
     * @var int
     */
    private $perPage;

    public function __construct(
        int $currentPage,
        Collection $data,
        int $lastPage,
        int $total,
        int $perPage,
        string $nextPageUrl = null,
        string $prevPageUrl = null
    ) {
        $this->currentPage = $currentPage;
        $this->data = $data;
        $this->lastPage = $lastPage;
        $this->nextPageUrl = $nextPageUrl;
        $this->prevPageUrl = $prevPageUrl;
        $this->total = $total;
        $this->perPage = $perPage;
    }

    /**
     * @return array
     */
    protected function jsonData(): array
    {
        return [
            'current_page' => $this->currentPage,
            'data' => $this->data,
            'last_page' => $this->lastPage,
            'next_page_url' => $this->nextPageUrl,
            'prev_page_url' => $this->prevPageUrl,
            'total' => $this->total,
            'per_page' => $this->perPage,
        ];
    }
}
