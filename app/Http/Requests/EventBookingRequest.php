<?php

namespace App\Http\Requests;

use App\Exceptions\CommonValidationException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class EventBookingRequest extends FormRequest
{
        /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
    public function failedValidation(Validator $validator){
        throw new CommonValidationException($validator);
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */





    public function rules(): array
    {
        $routeName = $this->route()->getName();

        switch ($routeName) {
            case 'events.api.booking.save':
                return $this->saveEventBookingRequest();
            default:
                return [];
        }
    }


    public function saveEventBookingRequest():array {
        return [
            'event_id' =>'required|integer|exists:events,id',
            'first_name' =>'required|max:55',
            'last_name' =>'required|max:55',
            'email'=>'required|email',
            'phone'=>'required|numeric|unique:users,phone'
        ];
    }
}
