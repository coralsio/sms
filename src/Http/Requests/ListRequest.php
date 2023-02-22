<?php

namespace Corals\Modules\SMS\Http\Requests;

use Corals\Foundation\Http\Requests\BaseRequest;
use Corals\Modules\SMS\Models\SMSList;

class ListRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $this->setModel(SMSList::class);

        return $this->isAuthorized();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->setModel(SMSList::class);
        $rules = parent::rules();

        if ($this->isUpdate() || $this->isStore()) {
            $rules = array_merge($rules, [
                'label' => 'required',
                'status' => 'required',
            ]);
        }

        if ($this->isStore()) {
            $rules = array_merge($rules, [
                'code' => 'required|unique:sms_lists,code',
            ]);
        }

        if ($this->isUpdate()) {
            $list = $this->route('sms_list');

            $rules = array_merge($rules, [
                'code' => 'required|unique:sms_lists,code,' . $list->id,
            ]);
        }

        return $rules;
    }
}
