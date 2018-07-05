<?php

namespace App\Http\Requests;


use App\Article;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Str;

class ArticleUpdateRequest extends ArticleStoreRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return parent::rules();
    }

    protected function getValidatorInstance()
    {
        $validator = parent::getValidatorInstance();
        $validator->after(function (Validator $validator) {
            if ($this->isMethod('put') && $this->slugExists()) {
                $validator->errors()->add('slug', 'Slug already exists.');
                return;
            }
        });

        return $validator;
    }

    protected function slugExists()
    {
        $slug = Article::whereSlug($this->getSlug())
            ->where(
                'id',
                '!=',
                $this->route()->parameter('article')->id
            )
            ->get();

        if (!empty($slug->toArray())) {
            return true;
        }

        return false;
    }

    public function getSlug()
    {
        return Str::slug($this->input('slug') ? $this->input('slug') : $this->getTitle());
    }
}
