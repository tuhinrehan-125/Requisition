<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $fillable = [
        'name', 'dept_code', 'details', 'status', 'created_by',
    ];

    public function orders(){
		return $this->hasMany(Order::class,'dept_id');
	}

    public function user(){
		return $this->hasMany(User::class,'dept_id');
	}
}
