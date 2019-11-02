<?php
declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEmailPost extends FormRequest
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
            'from' => 'required|array',
            'from.email' => 'required|email|max:255',
            'from.name' => 'required|string|max:255',
            'to' => 'required|array',
            'to.email' => 'required|email|max:255',
            'to.name' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'textPart' => 'required|string',
        ];
    }
}
