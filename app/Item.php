<?php

namespace App;

use App\Category;
use App\OrderItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Item extends Model
{
	public function category()
	{
		return $this->belongsTo(Category::class);
	}
	public function orderItems(){
		return $this->hasMany(OrderItem::class);
	}
    public function stock(){
		return $this->hasOne(Stock::class,'product_id');
	}
    public function createdBy(){
		return $this->belongsTo(User::class,'created_by');
	}
    public function updatedBy(){
        return $this->belongsTo(User::class,'updated_by');
    }
	public function scopeActive($query)
    {
		return $query->orderBy('created_at', 'desc')->where('status', true);
    }

}
