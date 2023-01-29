<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class all_plan extends Model
{
    use HasFactory;


    public function scopeActive($query)
    {
        return $query->where('plan_status', 0);
    }

    public function scopeInActive($query)
    {
        return $query->where('plan_status', 1);
    }


    public function scopeSubscriptionPlan($query)
    {
        return $query->where('plan_type', 1);
    }

    public function scopeCreditPlan($query)
    {
        return $query->where('plan_type', 2);
    }
}
