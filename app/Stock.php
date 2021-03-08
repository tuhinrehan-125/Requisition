<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Stock extends Model
{
    protected $guarded=[];

    public function item()
	{
		return $this->belongsTo(Item::class,'product_id');
	}
	public function scopeActive($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

}
