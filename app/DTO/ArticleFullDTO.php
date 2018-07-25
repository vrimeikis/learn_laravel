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

use App\DTO\Interfaces\ArticleDTOInterface;


/**
 * Class ArticleFullDTO
 * @package App\DTO
 */
class ArticleFullDTO extends BaseDTO implements ArticleDTOInterface
{

    /**
     * @var AuthorDTO
     */
    private $authorDTO;
    /**
     * @var CategoriesDTO
     */
    private $categoriesDTO;
    /**
     * @var ArticleDTO
     */
    private $articleDTO;

    /**
     * ArticleFullDTO constructor.
     * @param ArticleDTO $articleDTO
     * @param AuthorDTO $authorDTO
     * @param CategoriesDTO $categoriesDTO
     */
    public function __construct(ArticleDTO $articleDTO, AuthorDTO $authorDTO, CategoriesDTO $categoriesDTO)
    {
        $this->authorDTO = $authorDTO;
        $this->categoriesDTO = $categoriesDTO;
        $this->articleDTO = $articleDTO;
    }

    /**
     * @return array
     */
    protected function jsonData(): array
    {
        return [
            'data' => $this->articleDTO,
            'author' => $this->authorDTO,
            'categories' => collect($this->categoriesDTO)->get('data'),
        ];
    }
}
