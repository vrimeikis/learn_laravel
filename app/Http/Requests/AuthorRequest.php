<?php

declare(strict_types = 1);

namespace App\Http\Requests;

use App\Enum\AuthorLocationTypeEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Class AuthorRequest
 * @package App\Http\Requests
 */
class AuthorRequest extends FormRequest
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
     * @throws \ReflectionException
     */
    public function rules(): array
    {
        return [
            'first_name' => 'required|max:30',
            'last_name' => 'required|max:50',
            'location_type' => [
                'required',
                Rule::in(AuthorLocationTypeEnum::enum()),
            ],
        ];
    }

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->input('first_name');
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->input('last_name');
    }

    /**
     * @return string
     */
    public function getLocationType(): string
    {
        return $this->input('location_type');
    }
}
