<?php

namespace Eglobal\WhatsappIntegration\Models;

use Illuminate\Database\Eloquent\Model;

class WhatsappIntegration extends Model
{
    //
    protected $table = 'eg1_whatsapp_integration';

    protected $fillable = [
        'whatsapp_id',
        'typebot_session_id'
    ];
}
