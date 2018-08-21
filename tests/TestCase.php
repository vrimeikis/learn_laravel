<?php

declare(strict_types = 1);

namespace Tests;

use Illuminate\Foundation\Testing\Concerns\InteractsWithDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\DB;

/**
 * Class TestCase
 * @package Tests
 */
abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, InteractsWithDatabase;

    /**
     * @var string
     */
    protected $baseUrl = 'http://localhost';

    /**
     *
     */
    protected function setUp(): void
    {
        parent::setUp();

        if (DB::connection()->getDatabaseName() == ':memory:') {
            DB::statement('PRAGMA foreign_keys = ON');
        }
    }

    /**
     * @return array
     */
    protected function setUpTraits(): array
    {
        parent::setUpTraits();

        $uses = array_flip(class_uses_recursive(static::class));

        if (isset($uses[MemoryDatabaseMigrations::class])) {
            $this->runDatabaseMigrations();
        }

        return $uses;
    }
}
