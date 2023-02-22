<?php

namespace Corals\Modules\SMS\Models;

use Corals\Foundation\Models\BaseModel;
use Corals\Foundation\Transformers\PresentableTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\Traits\LogsActivity;

class Message extends BaseModel
{
    use PresentableTrait;
    use LogsActivity;

    /**
     *  Model configuration.
     * @var string
     */
    public $config = 'sms.models.message';

    protected $table = 'sms_messages';

    protected $casts = [
        'properties' => 'json',
    ];

    protected $guarded = ['id'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function messageable()
    {
        return $this->morphTo();
    }

    public function provider(): BelongsTo
    {
        return $this->belongsTo(Provider::class);
    }

    /**
     * @param $status
     */
    public function markAs($status): void
    {
        $this->update(['status' => $status]);
    }
}
