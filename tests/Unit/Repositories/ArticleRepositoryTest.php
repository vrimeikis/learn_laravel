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

namespace Tests\Unit\Repositories;

use App\Repositories\ArticleRepository;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class ArticleRepositoryTest
 * @package Tests\Unit\Repositories
 */
class ArticleRepositoryTest extends TestCase
{

    /**
     * @test
     */
    public function it_should_create_singleton_instance(): void
    {
        $this->assertInstanceOf(ArticleRepository::class, $this->getTestClassInstance());
        $this->assertSame($this->getTestClassInstance(), $this->getTestClassInstance());
    }

    /**
     * @return ArticleRepository
     */
    private function getTestClassInstance(): ArticleRepository
    {
        return $this->app->make(ArticleRepository::class);
    }

}
