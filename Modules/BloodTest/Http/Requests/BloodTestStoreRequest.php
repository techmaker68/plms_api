<?php

namespace Modules\BloodTest\Http\Requests;

use App\Rules\ValidDate;
use App\Rules\ValidVenueId;
use Illuminate\Foundation\Http\FormRequest;

class BloodTestStoreRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'submit_date' => ['required', 'date', new ValidDate],
            'test_date' => ['required', 'date', new ValidDate],
            'return_date' => ['required', 'date', new ValidDate],
            'venue' => ['required',new ValidVenueId],
            'start_time' => 'required|date_format:H:i:s',
            'end_time' => 'required|date_format:H:i:s',
            'applicants_interval' => 'required|integer',
            'interval' => 'required|integer',
        ];

        return $rules;
    }
}
