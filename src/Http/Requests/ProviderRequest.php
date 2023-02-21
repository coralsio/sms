<?php

namespace Corals\Modules\SMS\Http\Requests;

use Corals\Foundation\Http\Requests\BaseRequest;
use Corals\Modules\SMS\Models\Provider;
use Illuminate\Support\Str;

class ProviderRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $this->setModel(Provider::class);

        return $this->isAuthorized();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->setModel(Provider::class);
        $rules = parent::rules();

        if ($this->isUpdate() || $this->isStore()) {
            $rules = array_merge($rules, [
                'name' => 'required',
                'status' => 'required',
                'provider' => 'required',
                'keys.*' => 'required',
                'phone' => 'required',
            ]);
        }

        if ($this->isStore()) {
            $rules = array_merge($rules, [
            ]);
        }

        if ($this->isUpdate()) {
            $provider = $this->route('provider');

            $rules = array_merge($rules, [
            ]);
        }

        return $rules;
    }

    /**
     * @return array
     */
    public function attributes()
    {
        $attributes = [];

        foreach ($this->get('keys', []) as $key => $value) {
            $attributes["keys.$key"] = Str::replaceArray('_', [' '], $key);
        }

        return $attributes;
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
