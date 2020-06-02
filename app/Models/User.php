<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Str;

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
        'email',
        'api_token'
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

    public function setApiToken()
    {
        $this->api_token = Str::random(60);
        $this->save();
        return $this->api_token;
    }

    public function getFullName()
    {
        return $this->lastname . ' ' . $this->firstname . ' ' . $this->patronymic;
    }

    public function inRole(string $roleName)
    {
        return $this->getRole() === $roleName;
    }

    public function getRole()
    {
        return $this->employee->name;
    }

}
