<?php

declare(strict_types = 1);

namespace App\Http\Requests;

use App\Enum\ArticleTypeEnum;
use App\Repositories\ArticleRepository;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;
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
     * @throws \ReflectionException
     */
    public function rules(): array
    {
        return [
            'title' => 'required|min:3|max:191|string',
            'cover' => 'nullable|image|max:2048|min:100|dimensions:min_width=600,min_height=300',
            'description' => 'required',
            'author_id' => 'required|exists:authors,id',
            'category' => [
                'nullable',
                'array',
                'exists:categories,id',
            ],
            'article_type' => [
                'required',
                Rule::in(ArticleTypeEnum::enum()),
            ]
        ];
    }

    /**
     * @return string
     */
    public function getArticleType(): string
    {
        return $this->input('article_type');
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->input('description');
    }

    /**
     * @return int
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
     * @throws \Exception
     */
    protected function slugExists(): bool
    {
        /** @var ArticleRepository $articleRepository */
        $articleRepository = app(ArticleRepository::class);

        $slug = $articleRepository->getBySlug($this->getSlug());

        return !empty($slug);
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

    /**
     * @return UploadedFile|null
     */
    public function getCover(): ? UploadedFile
    {
        return $this->file('cover');
    }
}
