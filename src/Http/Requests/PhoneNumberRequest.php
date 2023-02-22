<?php

namespace Corals\Modules\SMS\Http\Requests;

use Corals\Foundation\Http\Requests\BaseRequest;
use Corals\Modules\SMS\Models\PhoneNumber;

class PhoneNumberRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $this->setModel(PhoneNumber::class);

        return $this->isAuthorized();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->setModel(PhoneNumber::class);

        $rules = parent::rules();

        if ($this->isUpdate() || $this->isStore()) {
            $rules = array_merge($rules, [
                'email' => 'nullable|email',
                'status' => 'required',
                'list_id' => 'required',
            ]);
        }

        if ($this->isStore()) {
            $rules = array_merge($rules, [
                'phone' => 'required|unique:sms_phone_numbers,phone',
            ]);
        }

        if ($this->isUpdate()) {
            $phoneNumber = $this->route('phone_number');

            $rules = array_merge($rules, [
                'phone' => 'required|unique:sms_phone_numbers,phone,' . $phoneNumber->id,
            ]);
        }

        return $rules;
    }

    protected function getValidatorInstance()
    {
        if ($this->isUpdate() || $this->isStore()) {
            $data = $this->all();

            if (! empty($data['phone'])) {
                $data['phone'] = getCleanedPhoneNumber($data['phone']);
            }

            $this->getInputSource()->replace($data);
        }

        return parent::getValidatorInstance();
    }
}
