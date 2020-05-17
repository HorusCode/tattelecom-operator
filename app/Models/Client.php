<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{

    protected $fillable = [
        'firstname',
        'lastname',
        'patronymic',
        'email',
        'phone',
        'passport_number',
        'passport_series',
        'address'
    ];


    public function statements()
    {
        return $this->hasMany(Statement::class);
    }

}
