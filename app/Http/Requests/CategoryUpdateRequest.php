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

namespace App\Http\Requests;

use App\Repositories\CategoryRepository;
use Illuminate\Contracts\Validation\Validator;

/**
 * Class CategoryUpdateRequest
 * @package App\Http\Requests
 */
class CategoryUpdateRequest extends CategoryStoreRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return parent::rules();
    }

    /**
     * @return Validator
     */
    protected function getValidatorInstance(): Validator
    {
        $validator = parent::getValidatorInstance();
        $validator->after(function (Validator $validator) {
            if ($this->isMethod('put') && $this->slugExists()) {
                $validator->errors()
                    ->add('slug', 'This slug already exists');
            }

            return;
        });

        return $validator;
    }

    /**
     * @return bool
     * @throws \Exception
     */
    private function slugExists(): bool
    {
        /** @var CategoryRepository $categoryRepository */
        $categoryRepository = app(CategoryRepository::class);

        $category = $categoryRepository->getBySlugAndNotId(
            $this->getSlug(),
            (int)$this->route()->parameter('category')
        );

        if (!empty($category)) {
            return true;
        }

        return false;
    }

    /**
     * @return string
     */
    public function getSlug(): string
    {
        return ($this->input('slug')) ?? parent::getSlug();
    }
}
