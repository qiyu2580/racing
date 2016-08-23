<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Racing extends Model
{
    protected $hidden = ['id', 'expired'];
    public $timestamps = false;
    protected $dates = ['awardTime'];
    protected $fillable = ['periodNumber', 'awardTime', 'awardNumbers'];

    public function scopeOfCurrentAndNext($query)
    {
        return $query->where('awardTime', '<=', \Carbon\Carbon::now()->subSeconds(35))
            ->orderBy('awardTime', 'desc')
            ->take(2);
    }
}
