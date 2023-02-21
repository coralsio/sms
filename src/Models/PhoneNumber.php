<?php

namespace Corals\Modules\SMS\Models;

use Corals\Foundation\Models\BaseModel;
use Corals\Foundation\Transformers\PresentableTrait;
use Corals\Modules\SMS\Traits\MessageableTrait;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\Traits\LogsActivity;

class PhoneNumber extends BaseModel
{
    use PresentableTrait, LogsActivity, Notifiable, MessageableTrait;

    /**
     *  Model configuration.
     * @var string
     */
    public $config = 'sms.models.phone_number';

    protected $table = 'sms_phone_numbers';

    protected $casts = [
        'properties' => 'json',
    ];

    protected $guarded = ['id'];

    protected $with = ['messages'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function list()
    {
        return $this->belongsTo(SMSList::class);
    }

    /**
     * @return array
     */
    public function getSMSBodyParameters(): array
    {
        return [
            'phone' => $this->phone,
            'name' => $this->name,
            'last_name' => $this->last_name,
            'email' => $this->email,
//            'list' => $this->list->label,
        ];
    }

    /**
     * @return array
     */
    public static function getSMSBodyDescriptions(): array
    {
        return [
            'phone' => 'Phone',
            'name' => 'First Name',
            'last_name' => 'Last name',
            'email' => 'Email',
//            'list' => 'Phone list',
        ];
    }
}
