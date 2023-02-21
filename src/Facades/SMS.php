<?php

namespace Corals\Modules\SMS\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class SMS
 * @package Corals\Modules\SMS\Facades
 * @method static send($parameters);
 */
class SMS extends Facade
{
    /**
     * @return mixed
     */
    protected static function getFacadeAccessor()
    {
        return \Corals\Modules\SMS\Classes\SMS::class;
    }
}
