<?php

namespace App;

use App\Item;
use App\Order;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Category extends Model
{
	public function items()
	{
		return $this->hasMany(Item::class);
	}
	public function order()
	{
		return $this->hasOne(Order::class);
	}

	public function parentcat()
	{
		return $this->belongsTo(Category::class, 'parent_id');
	}

	public function scopeActive($query)
	{
		return $query->orderBy('created_at', 'desc')->where('status', true);
	}

}
