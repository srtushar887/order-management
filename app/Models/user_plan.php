<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class user_plan extends Model
{
    use HasFactory;

    public function plan()
    {
        return $this->hasOne(all_plan::class, 'id', 'plan_id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
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
