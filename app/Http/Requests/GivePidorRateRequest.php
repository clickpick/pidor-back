<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Class GivePidorRateRequest
 * @package App\Http\Requests
 * @property $acceptor_id
 */
class GivePidorRateRequest extends FormRequest
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
            'acceptor_id' => 'required|integer|exists:users,id|' . Rule::notIn([$this->user()->id])
        ];
    }
}
