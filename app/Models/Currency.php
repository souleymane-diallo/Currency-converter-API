<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'symbol'
    ];

    public function pairs()
    {
        return $this->hasMany(Pair::class);
    }

    // public function toPairs()
    // {
    //     return $this->hasMany(Pair::class, 'to_id');
    // }
}
