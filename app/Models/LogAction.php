<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogAction extends Model
{
    protected $table = '_log_actions';

    protected $fillable = [
        'user_id', 'action_name', 'model_type', 'model_id', 
        'ip_address', 'user_agent', 'payload'
    ];

    protected $casts = [
        'payload' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
