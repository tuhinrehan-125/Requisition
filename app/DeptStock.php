<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DeptStock extends Model
{
    protected $fillable = [
        'dept_id', 'product_id', 'qty',
    ];

    public function product()
    {
        return $this->hasOne(Item::class, 'id', 'product_id');
    }
    public function dept()
    {
        return $this->belongsTo(Department::class, 'dept_id');
    }

    public static function addStock($deptId, $deliver_qty)
    {
        foreach ($deliver_qty as $index => $value) {
                $deptStock = new DeptStock;
                $deptStock->dept_id = $deptId;
                $deptStock->last_supply = $value;
                $deptStock->total_supply = $value;
                $deptStock->product_id = $index;
                $deptStock->qty = $value;
                $deptStock->save();
        }
    }
}
