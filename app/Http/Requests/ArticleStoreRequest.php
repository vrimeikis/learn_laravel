<?php

declare(strict_types = 1);

namespace App\Http\Requests;

use App\Article;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

/**
 * Class ArticleStoreRequest
 * @package App\Http\Requests
 */
class ArticleStoreRequest extends FormRequest
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
        return [
            'title' => 'required|min:3|max:191|string',
            'description' => 'required',
            'author_id' => 'required|exists:authors,id',
            'category' => [
                'nullable',
                'array',
                'exists:categories,id',
            ],
        ];
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->input('description');
    }

    /**
     * @return string
     */
    public function getAuthorId(): int
    {
        return (int)$this->input('author_id');
    }

    /**
     * @return Validator
     */
    protected function getValidatorInstance(): Validator
    {
        $validator = parent::getValidatorInstance();
        $validator->after(function (Validator $validator) {
            if ($this->isMethod('post') && $this->slugExists()) {
                $validator
                    ->errors()
                    ->add('title', 'Slug by name exists on DB');

                return;
            }
        });

        return $validator;
    }

    /**
     * @return bool
     */
    protected function slugExists(): bool
    {
        $title = $this->getTitle();

        if(!$title) {
            return true;
        }

        $slug = Article::whereSlug($title)->get();

        if (!empty($slug->toArray())) {
            return true;
        }

        return false;
    }

    /**
     * @return string
     */
    public function getSlug(): string
    {
        return Str::slug($this->getTitle());
    }

    /**
     * @return null|string
     */
    public function getTitle(): ? string
    {
        return $this->input('title');
    }

    /**
     * @return array
     */
    public function getCategoriesIds(): array
    {
        return $this->input('category', []);
    }
}
