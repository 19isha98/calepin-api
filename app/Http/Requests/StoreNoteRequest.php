<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreNoteRequest extends FormRequest
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
            'author_id' => 'required|integer|exists:users,id',
            'title'     => 'max:100',
            'content'   => 'required',
            'language'  => 'max:50',
            'private'   => 'boolean',
            'encrypted' => 'boolean',
        ];
    }
}
