<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Racing extends Model
{
    protected $hidden = ['id', 'expired'];
    public $timestamps = false;
    protected $dates = ['awardTime'];
    protected $fillable = ['periodNumber', 'awardTime', 'awardNumbers'];

    public function scopeOfCurrentAndNext($query, $periodNumber)
    {
        return $query->where('periodNumber', $periodNumber)
            ->orWhere('periodNumber', $periodNumber + 1);
    }
}
