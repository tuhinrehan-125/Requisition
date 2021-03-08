<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Purchase extends Model
{
    protected $guarded=[];

    public function item()
    {
		return $this->belongsTo(Item::class);
	}

    public function category()
	{
		return $this->belongsTo(Category::class);
	}

    public function vendor()
	{
		return $this->belongsTo(Vendor::class);
	}

	public function scopeActive($query)
    {
		return $query->orderBy('created_at', 'desc')->where('is_deleted', 0);
    }
}
