<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class StoreLinkRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', \App\Models\Link::class);
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'url' => [
                'required',
                'url',
                Rule::unique('links')->where(function ($query) {
                    return $query->where('user_id', Auth::id());
                }),
            ],
            'category_id' => 'required|exists:categories,id',
            'tags' => 'array',
            'tags.*' => 'exists:tags,id',
        ];
    }

    public function messages()
    {
        return [
            'url.unique' => 'Vous avez déjà enregistré ce lien.',
        ];
    }
}
