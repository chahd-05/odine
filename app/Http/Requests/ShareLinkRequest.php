<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ShareLinkRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email'        => 'required|email|exists:users,email',
            'access_level' => 'required|in:read,edit',
        ];
    }

    public function messages(): array
    {
        return [
            'email.required'        => 'L\'adresse email est obligatoire.',
            'email.email'           => 'L\'adresse email doit être valide.',
            'email.exists'          => 'Aucun utilisateur trouvé avec cet email.',
            'access_level.required' => 'Le niveau d\'accès est obligatoire.',
            'access_level.in'       => 'Le niveau d\'accès doit être "read" ou "edit".',
        ];
    }
}
