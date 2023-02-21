<?php

namespace Corals\Modules\SMS\Transformers;

use Corals\Foundation\Transformers\FractalPresenter;

class ProviderPresenter extends FractalPresenter
{

    /**
     * @param array $extras
     * @return ProviderTransformer|\League\Fractal\TransformerAbstract
     */
    public function getTransformer($extras = [])
    {
        return new ProviderTransformer($extras);
    }
}
