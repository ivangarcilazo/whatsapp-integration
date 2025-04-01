<?php

namespace EglobalOneLab\WhatsappIntegration\Models;

use Illuminate\Database\Eloquent\Model;

class WhatsappIntegration extends Model
{
    //
    protected $table = 'eg1_whatsapp_integration';
    protected $primaryKey = 'whatsapp_id'; 
    public $incrementing = false; 
    protected $keyType = 'string'; 

    protected $fillable = [
        'whatsapp_id',
        'typebot_session_id'
    ];
}
