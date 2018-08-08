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

namespace App\Repositories;

use App\Contracts\RepositoryContract;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * Class Repository
 * @package App\Repositories
 */
abstract class Repository implements RepositoryContract
{
    /**
     *
     */
    const DEFAULT_PER_PAGE = 15;

    /**
     *
     */
    const DEFAULT_FIELD_NAME = 'id';

    /**
     * @return string
     */
    abstract public function model(): string;

    /**
     * @param array $columns
     * @return Collection
     * @throws \Exception
     */
    public function all(array $columns = ['*']): Collection
    {
        return $this->makeQuery()->get($columns);
    }

    /**
     * @param int $perPage
     * @param array $columns
     * @return LengthAwarePaginator
     * @throws \Exception
     */
    public function paginate(int $perPage = self::DEFAULT_PER_PAGE, array $columns = ['*']): LengthAwarePaginator
    {
        return $this->makeQuery()->paginate($perPage, $columns);
    }

    /**
     * @param array $data
     * @return Model
     * @throws \Exception
     */
    public function create(array $data = []): Model
    {
        return $this->makeQuery()->create($data);
    }

    /**
     * @param array $data
     * @param $fieldValue
     * @param string $fieldName
     * @return int
     * @throws \Exception
     */
    public function update(array $data, $fieldValue, string $fieldName = self::DEFAULT_FIELD_NAME): int
    {
        return $this->makeQuery()->where($fieldName, $fieldValue)->update($data);
    }

    /**
     * @param int $id
     * @return Model
     * @throws \Exception
     */
    public function find(int $id): Model
    {
        return $this->makeQuery()->find($id);
    }

    /**
     * @param int $id
     * @return Model
     * @throws \Exception
     */
    public function findOrFail(int $id): Model
    {
        return $this->makeQuery()->findOrFail($id);
    }

    /**
     * @param array $criteria
     * @param array $data
     * @return Model
     * @throws \Exception
     */
    public function updateOrCreate(array $criteria, array $data): Model
    {
        return $this->makeQuery()->updateOrCreate($criteria, $data);
    }

    /**
     * @param array $relations
     * @return Builder
     * @throws \Exception
     */
    public function with(array $relations = []): Builder
    {
        return $this->makeQuery()->with($relations);
    }

    /**
     * @param array $criteria
     * @throws \Exception
     */
    public function delete(array $criteria = [])
    {
        $this->makeQuery()->where($criteria)->delete();
    }

    /**
     * @return Model
     * @throws \Exception
     */
    final protected function makeModel(): Model
    {
        $model = app($this->model());

        if (!$model instanceof Model) {
            throw new \Exception('Class ' . $this->model() . ' must be instance of Illuminate\\Database\\Eloquent\\Model');
        }

        return $model;
    }

    /**
     * @return Builder
     * @throws \Exception
     */
    public function makeQuery(): Builder
    {
        return $this->makeModel()->newQuery();
    }
}