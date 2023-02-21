<?php

namespace Corals\Modules\SMS\Transformers;

use Corals\Foundation\Transformers\FractalPresenter;

class MessagePresenter extends FractalPresenter
{

    /**
     * @param array $extras
     * @return MessageTransformer|\League\Fractal\TransformerAbstract
     */
    public function getTransformer($extras = [])
    {
        return new MessageTransformer($extras);
    }
}
