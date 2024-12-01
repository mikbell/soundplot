<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    protected $fillable = ['user_id', 'sample_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function sample()
    {
        return $this->belongsTo(Sample::class);
    }
}
