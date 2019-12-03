<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Statement extends Model
{
    protected $fillable = [
        'client_id',
        'user_id',
        'problem',
    ];


    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function scopeStatus($query, bool $status)
    {
        return $query->where('status', $status);
    }

}
