<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Vendor extends Model
{
    protected $fillable = [
        'name', 'phone_no', 'email', 'address', 'contact_person', 'status', 'created_by',
    ];

    public function user()
    {
       return $this->belongsTo(User::class,'created_by');
    }

    public function scopeActive($query)
    {
        if (Auth::user()->role->role == 'dept-user') {
			return $query->where('dept_id', Auth::user()->dept_id)->orderBy('created_at', 'desc')->where('status', true);
		} else {
			return $query->orderBy('created_at', 'desc')->where('status', true);
		}
    }
}
