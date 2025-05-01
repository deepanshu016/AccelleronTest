<?php

namespace App\Http\Requests;

use App\Exceptions\CommonValidationException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class EventRequest extends FormRequest
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
            case 'events.api.save':
                return $this->saveEventRequest();
            case 'events.api.update':
                return $this->updateEventRequest();
            default:
                return [];
        }
    }


    public function saveEventRequest():array {
        return [
            'title' =>'required|max:255',
            'description'=>'nullable',
            'date'=>'required|date|after_or_equal:now',
            'venue'=>'required|max:255',
            'capacity' => 'required|integer|min:1',
            'is_recurring_event' => 'required|boolean',
            'ticket_types' => 'required|in:regular,vip',
            'recurring_type' => 'required_if:is_recurring_event,true|in:weekly,monthly',
        ];
    }
    public function updateEventRequest():array {
        return [
            'title' =>'max:255',
            'description'=>'nullable',
            'date'=>'date|after_or_equal:now',
            'venue'=>'max:255',
            'capacity' => 'integer|min:1',
            'is_recurring_event' => 'boolean',
            'ticket_types' => 'in:regular,vip',
            'recurring_type' => 'required_if:is_recurring_event,true|in:weekly,monthly',
        ];
    }
}
