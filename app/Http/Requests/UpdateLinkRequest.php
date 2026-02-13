<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class UpdateLinkRequest extends FormRequest
{
    public function authorize(): bool
    {
        $link = $this->route('link');
        return true;
    }

    public function rules(): array
    {
        $linkId = $this->route('id');

        return [
            'title' => 'required|string|max:255',
            'url' => [
                'required',
                'url',
                Rule::unique('links')->ignore($linkId)->where(function ($query) {
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
