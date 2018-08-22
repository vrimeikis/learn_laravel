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

namespace Tests\Unit\Services\API;

use App\Author;
use App\DTO\AuthorDTO;
use App\Exceptions\AuthorException;
use App\Services\API\AuthorService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;
use Tests\MemoryDatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class AuthorServiceTest
 * @package Tests\Unit\Services\API
 */
class AuthorServiceTest extends TestCase
{
    use MemoryDatabaseMigrations;

    /**
     * @test
     * @group author
     * @group author-service
     */
    public function it_should_create_singleton_instance(): void
    {
        $this->assertInstanceOf(AuthorService::class, $this->getTestClassInstance());
        $this->assertSame($this->getTestClassInstance(), $this->getTestClassInstance());
    }

    /**
     * @test
     * @group author
     * @group author-service
     * @throws \App\Exceptions\ApiDataException
     */
    public function it_should_except_author_exception_on_paginate(): void
    {
        $this->expectException(AuthorException::class);
        $this->expectExceptionMessage(AuthorException::noData()->getMessage());
        $this->expectExceptionCode(AuthorException::noData()->getCode());

        $this->getTestClassInstance()->getPaginateData();
    }

    /**
     * @test
     * @group author
     * @group author-service
     * @throws \App\Exceptions\ApiDataException
     */
    public function it_should_return_paginator(): void
    {
        /** @var Collection|Author[] $authors */
        $authors = factory(Author::class, 2)->create();

        $result = $this->getTestClassInstance()->getPaginateData();

        $this->assertInstanceOf(LengthAwarePaginator::class, $result);
        $this->assertEquals($authors->toArray(), $result->getCollection()->toArray());
    }

    /**
     * @test
     * @group author
     * @group author-service
     * @throws \Exception
     */
    public function it_should_except_exception_by_id(): void
    {
        $id = mt_rand(1, 10);

        $this->expectException(ModelNotFoundException::class);

        $this->getTestClassInstance()->getById($id);
    }

    /**
     * @test
     * @group author
     * @group author-service
     * @throws \Exception
     */
    public function it_should_return_author_dto_by_id(): void
    {
        /** @var Author $author */
        $author = factory(Author::class)->create();

        $exceptData = (new AuthorDTO())
            ->setAuthorId($author->id)
            ->setFirstName($author->first_name)
            ->setLastName($author->last_name);

        $result = $this->getTestClassInstance()->getById($author->id);

        $this->assertEquals($exceptData, $result);
    }

    /**
     * @return AuthorService
     */
    private function getTestClassInstance(): AuthorService
    {
        return $this->app->make(AuthorService::class);
    }
}
