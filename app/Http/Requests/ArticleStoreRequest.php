<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class ArticleStoreRequest extends FormRequest
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
        return [
            'title' => 'required|min:3|max:191|string',
            'description' => 'required',
            'author' => 'required|min:2|max:100|string',
        ];
    }

    public function getTitle()
    {
        return $this->input('title');
    }

    public function getDescription()
    {
        return $this->input('description');
    }

    public function getAuthor()
    {
        return $this->input('author');
    }

    public function getSlug()
    {
        return Str::slug($this->getTitle());
    }
}
