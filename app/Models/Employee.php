<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = ['name'];
    public $timestamps = false;


    // With user class relationship one-to-many
    public function users()
    {
        return $this->hasMany(User::class);
    }



}
