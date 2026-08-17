<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WaMessageLog extends Model
{
    protected $fillable = ['phone_number', 'message', 'status', 'response_data'];
}
