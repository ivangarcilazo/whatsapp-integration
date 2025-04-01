<?php

namespace Eglobal\WhatsappIntegration\Models;

use Illuminate\Database\Eloquent\Model;

class WhatsappHistory extends Model
{
    //
    protected $table = 'eg1_whatsapp_history';

    protected $fillable = [
        'whatsapp_id',
        'messages'
    ];

    protected $casts = [
        'messages' => 'array'
    ];
}
