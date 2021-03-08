<?php

namespace App;

use App\Category;
use App\OrderItem;
use App\Department;
use App\OrderApproval;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Order extends Model
{
    public function dept()
    {
        return $this->belongsTo(Department::class, 'dept_id');
    }
    public function Items()
    {
        return $this->hasMany(OrderItem::class);
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function orderApproval()
    {
        return $this->hasOne(OrderApproval::class);
    }
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
    public function scopeActive($query)
    {
        if (Auth::user()->role->role == 'dept-user') {
            return $query->where('dept_id', Auth::user()->dept_id)->orderBy('created_at', 'desc');
        } else {
            return $query->orderBy('created_at', 'desc');
        }
    }
    public function scopePending($query)
    {
        if (Auth::user()->role->role == 'dept-user') {
            return $query->where('dept_id', Auth::user()->dept_id)->where(function ($q) {
                $q->where('status', 'Pending for approval')->orWhere('status', 'Forwarded to Sr-officer');
            })->orderBy('created_at', 'desc');
        } else {
            return $query->where('status', 'pending for approval')->orWhere('status', 'Forwarded to Sr-officer')->orderBy('created_at', 'desc');
        }
    }

    public function scopeApproved($query)
    {
        if (Auth::user()->role->role == 'dept-user') {
            return $query->where('dept_id', Auth::user()->dept_id)->where(function ($q) {
                $q->where('status', '=', 'Approved by Admin-officer')->orWhere('status', '=', 'Approved by Sr-officer');
            })->orderBy('created_at', 'desc');
        } else {
            return $query->where('status', 'Approved by Admin-officer')->orWhere('status', 'Approved by Sr-officer')->orderBy('created_at', 'desc');
        }
    }
    public function scopeApprovedCount($query)
    {
        if (Auth::user()->role->role == 'dept-user') {
            return $query->where('dept_id', Auth::user()->dept_id)->where(function ($q) {
                $q->where('status', '=', 'Approved by Admin-officer')->orWhere('status', '=', 'Approved by Sr-officer');
            });
        } else {
            return $query->where('status', 'Approved by Admin-officer')->orWhere('status', 'Approved by Sr-officer');
        }
    }

    public function scopeRejected($query)
    {
        if (Auth::user()->role->role == 'dept-user') {
            return $query->where('dept_id', Auth::user()->dept_id)->where(function ($q) {
                $q->where('status', 'Rejected by Admin-officer')->orWhere('status', 'Rejected by Sr-officer');
            })->orderBy('created_at', 'desc');
        } else {
            return $query->where('status', 'Rejected by Admin-officer')->orWhere('status', 'Rejected by Sr-officer')->orderBy('created_at', 'desc');
        }
    }
}
