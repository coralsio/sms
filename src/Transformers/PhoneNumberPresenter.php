<?php

namespace Corals\Modules\SMS\Transformers;

use Corals\Foundation\Transformers\FractalPresenter;

class PhoneNumberPresenter extends FractalPresenter
{
    /**
     * @param array $extras
     * @return ProviderTransformer|\League\Fractal\TransformerAbstract
     */
    public function getTransformer($extras = [])
    {
        return new PhoneNumberTransformer($extras);
    }
}
