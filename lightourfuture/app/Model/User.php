<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    //
    public $timestamps = false;
    //public $primaryKey  = 'login_id';
    protected $fillable = ['login_id','password', 'classification'];
}
