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
        $defaultEmailRule = 'required|email|max:255';
        $defaultStringRule = 'required|string|min:1|max:255';

        return [
            'from'          => 'required|array',
            'from.email'    => $defaultEmailRule,
            'from.name'     => $defaultStringRule,
            'to'            => 'required|array',
            'to.*.email'    => $defaultEmailRule,
            'to.*.name'     => $defaultStringRule,
            'subject'       => $defaultStringRule,
            'textPart'      => 'required_without_all:htmlPart,markdownPart|min:1|string',
            'htmlPart'      => 'required_without_all:textPart,markdownPart|min:1|string',
            'markdownPart'  => 'required_without_all:textPart,htmlPart|min:1|string',
        ];
    }
}
