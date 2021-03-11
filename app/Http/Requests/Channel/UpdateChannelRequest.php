<?php

namespace App\Http\Requests\Channel;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class UpdateChannelRequest
 * Validate channel update data
 *
 * @package App\Http\Requests\Channel
 * @project: laratube
 */
class UpdateChannelRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->channel->user_id === auth()->user()->id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'        => 'required',
            'description' => 'max:1000',
            'image'       => 'image',
        ];
    }
}
