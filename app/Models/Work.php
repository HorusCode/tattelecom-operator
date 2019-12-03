<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Work extends Model
{
    protected $fillable = [
        'statement_id',
        'service_user_id',
        'operator_user_id',
        'status'
    ];

    public function statement()
    {
        return $this->belongsTo(Statement::class);
    }

    public function serviceUser()
    {
        return $this->belongsTo(User::class, 'service_user_id');
    }

    public function operatorUser()
    {
        return $this->belongsTo(User::class, 'operator_user_id');
    }



}
