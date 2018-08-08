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

use App\Author;
use App\DTO\AuthorDTO;
use App\Exceptions\AuthorException;
use App\Repositories\AuthorRepository;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Class AuthorService
 * @package App\Services\API
 */
class AuthorService
{
    /**
     * @var AuthorRepository
     */
    private $authorRepository;

    /**
     * AuthorService constructor.
     * @param AuthorRepository $authorRepository
     */
    public function __construct(AuthorRepository $authorRepository)
    {
        $this->authorRepository = $authorRepository;
    }


    /**
     * @return LengthAwarePaginator
     * @throws \App\Exceptions\ApiDataException
     * @throws \Exception
     */
    public function getPaginateData(): LengthAwarePaginator
    {
        /** @var LengthAwarePaginator $authors */
        $authors = $this->authorRepository->paginate();

        if ($authors->isEmpty()) {
            throw AuthorException::noData();
        }

        return $authors;
    }

    /**
     * @param int $authorId
     * @return AuthorDTO
     * @throws \Exception
     */
    public function getById(int $authorId): AuthorDTO
    {
        /** @var Author $author */
        $author = $this->authorRepository->findOrFail($authorId);

        $dto = new AuthorDTO();

        return $dto->setAuthorid($author->id)
            ->setFirstName($author->first_name)
            ->setLastName($author->last_name);
    }
}
