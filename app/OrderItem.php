<?php

namespace App;

use App\Item;
use App\Order;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
    public function item()
    {
        return $this->belongsTo(Item::class);
    }
    public static function substructStock($deliver_qty)
    {
        foreach ($deliver_qty as $index => $value) {
            if ($stock = Stock::where('product_id', $index)->first()) {
                $stock->in_stock -= $value;
                $stock->save();
            }
        }
    }
    public function scopeCountDeliver($query)
    {
       
        return $query->sum('received_qty');
    }

}
