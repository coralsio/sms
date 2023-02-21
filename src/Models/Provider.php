<?php

namespace Corals\Modules\SMS\Models;

use Corals\Foundation\Models\BaseModel;
use Corals\Foundation\Transformers\PresentableTrait;
use Spatie\Activitylog\Traits\LogsActivity;

class Provider extends BaseModel
{
    use PresentableTrait;
    use LogsActivity;

    /**
     *  Model configuration.
     * @var string
     */
    public $config = 'sms.models.provider';

    protected $table = 'sms_providers';

    protected $casts = [
        'properties' => 'json',
        'keys' => 'json',
    ];

    protected $guarded = ['id'];
}
