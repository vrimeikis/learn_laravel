<?php

declare(strict_types = 1);

namespace App\Enum;

use App\Exceptions\EnumerableException;
use ReflectionClass;
use ReflectionMethod;

/**
 * Class Enumerable
 * @package App\Enum
 */
abstract class Enumerable
{
    /**
     * @var
     */
    private $id;
    /**
     * @var string
     */
    private $name;
    /**
     * @var string
     */
    private $description;

    /**
     * @var array
     */
    private static $instances = [];

    /**
     * Enumerable constructor.
     * @param $id
     * @param string $name
     * @param string $description
     */
    public function __construct($id, string $name, string $description = '')
    {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;

        self::$instances[get_called_class()][$id] = $this;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return (string)$this->id;
    }

    /**
     * @param $id
     * @return Enumerable
     * @throws EnumerableException
     * @throws \ReflectionException
     */
    public static function from($id): Enumerable
    {
        $enum = self::enum();

        if (!isset($enum[$id])) {
            throw new EnumerableException(strtr('Unable to find enumerable with id :id of type :type', [
                ':id' => $id,
                ':type' => get_called_class(),
            ]));
        }

        return $enum[$id];
    }

    /**
     * @return array
     * @throws \ReflectionException
     */
    public static function enum(): array
    {
        $reflection = new ReflectionClass(get_called_class());
        $finalMethods = $reflection->getMethods(ReflectionMethod::IS_FINAL);

        $return = [];

        foreach ($finalMethods as $finalMethod) {
            $enum = $finalMethod->invoke(null);
            $return[$enum->getId()] = $enum;
        }

        return $return;
    }

    /**
     * @return array
     * @throws \ReflectionException
     */
    public static function options(): array
    {
        return array_values(array_map(function(Enumerable $enumerable) {
            return [
                'id' => $enumerable->getId(),
                'name' => $enumerable->getName(),
                'description' => $enumerable->getDescription(),
            ];
        }, self::enum()));
    }

    /**
     * @return string
     * @throws \ReflectionException
     */
    public static function json(): string
    {
        return json_encode(array_map(function(Enumerable $enumerable) {
            return $enumerable->getName();
        }, self::enum()));
    }

    /**
     * @param $id
     * @param string $name
     * @param string $description
     * @return Enumerable|\stdClass
     * @throws \ReflectionException
     */
    protected static function make($id, string $name, string $description = ''): Enumerable
    {
        $class = get_called_class();

        if (isset(self::$instances[$class][$id])) {
            return self::$instances[$class][$id];
        }

        $reflection = new ReflectionClass($class);

        $instance = $reflection->newInstance($id, $name, $description);
        $refConstructor = $reflection->getConstructor();
        $refConstructor->setAccessible(true);

        return self::$instances[$class][$id] = $instance;
    }
}
