<?php

namespace App\Http\Requests\Admin;

use App\Models\RedirectRule;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class StoreRedirectRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->canManageContent();
    }

    /**
     * Prepare inputs before validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_active' => $this->boolean('is_active', true),
            'group' => ! empty($this->input('group')) ? trim($this->input('group')) : 'General',
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $statusCode = (int) $this->input('status_code', 301);

        return [
            'source_url' => ['required', 'string', 'max:2048'],
            'target_url' => [$statusCode === 410 ? 'nullable' : 'required', 'string', 'max:2048'],
            'status_code' => ['required', 'integer', 'in:301,302,307,308,410'],
            'match_type' => ['required', 'string', 'in:exact,wildcard,regex'],
            'query_param_option' => ['required', 'string', 'in:ignore,pass,exact'],
            'group' => ['nullable', 'string', 'max:60'],
            'description' => ['nullable', 'string', 'max:1000'],
            'is_active' => ['boolean'],
        ];
    }

    /**
     * Configure additional validation checks.
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $v) {
            $source = $this->input('source_url', '');
            $target = $this->input('target_url', '');
            $statusCode = (int) $this->input('status_code', 301);

            $normSource = RedirectRule::normalizeUrl($source, true);
            $normTarget = RedirectRule::normalizeUrl($target, false);

            // Prevent self-redirect loop
            if ($statusCode !== 410 && ! empty($normSource) && ! empty($normTarget) && strtolower($normSource) === strtolower($normTarget)) {
                $v->errors()->add('target_url', 'Target URL cannot be identical to the Source URL as this creates an infinite redirect loop.');
            }

            // Protect admin routes from accidental source hijack
            if ($normSource === '/admin' || str_starts_with($normSource, '/admin/')) {
                $v->errors()->add('source_url', 'Redirecting the admin dashboard path is prohibited to prevent lockouts.');
            }
        });
    }

    /**
     * Custom validation error messages.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'source_url.required' => 'Please provide the source URL you want to redirect from.',
            'target_url.required' => 'Destination target URL is required unless using HTTP 410 (Content Gone).',
            'status_code.in' => 'Please select a valid HTTP redirect code (301, 302, 307, 308, or 410).',
        ];
    }
}
