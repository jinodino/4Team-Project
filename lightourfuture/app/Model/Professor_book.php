<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Professor_book extends Model
{
    protected $fillable = ['login_id', 'name', 'birth_date', 'faculty_id', 'employ_year'];
    public $timestamps = false;
}
