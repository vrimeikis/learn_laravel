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

use App\Repositories\UserRepository;
use App\User;
use Tests\MemoryDatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class UserRepositoryTest
 * @package Tests\Unit\Repositories
 */
class UserRepositoryTest extends TestCase
{
    use MemoryDatabaseMigrations;

    /**
     * @test
     */
    public function it_should_make_singleton_instance(): void
    {
        $this->assertInstanceOf(UserRepository::class, $this->getTestClassInstance());
        $this->assertSame($this->getTestClassInstance(), $this->getTestClassInstance());
    }

    /**
     * @test
     * @throws \Exception
     */
    public function it_should_create_user(): void
    {
        /** @var User $user */
        $user = factory(User::class)->make();

        $data = $user->toArray();

        array_set($data, 'password', bcrypt('secret'));

        $this->getTestClassInstance()->create($data);

        $this->assertDatabaseHas('users', [
            'name' => $user->name,
            'email' => $user->email,
        ]);
    }

    /**
     * @test
     * @throws \Exception
     */
    public function it_should_update_user(): void
    {
        /** @var User $user */
        $user = factory(User::class)->create();

        $name = str_random(6);

        $this->getTestClassInstance()->update(
            ['name' => $name],
            $user->id
        );

        $this->assertDatabaseHas('users', [
            'name' => $name,
            'email' => $user->email,
        ]);

        $this->assertDatabaseMissing('users', [
            'name' => $user->name,
            'email' => $user->email,
        ]);
    }

    /**
     * @return UserRepository
     */
    private function getTestClassInstance(): UserRepository
    {
        return $this->app->make(UserRepository::class);
    }
}
