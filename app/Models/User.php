<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'firstname',
        'lastname',
        'patronymic',
        'login',
        'phone',
        'passport_number',
        'passport_series',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    // With employee class relationship one-to-many
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function statements()
    {
        return $this->hasMany(Statement::class);
    }

    public function workOperator()
    {
        return $this->hasMany(Work::class, 'operator_user_id');
    }

    public function workService()
    {
        return $this->hasMany(Work::class, 'service_user_id');
    }

}
